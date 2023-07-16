<?php
    session_start();
    if(!isset($_SESSION["loggedin"])) {
        die("Session invalid.");
    }

    // read config
    require_once "../components/ymlParser.php";
    $yamlContent = file_get_contents('../config.yml');
    $configData = parseYaml($yamlContent);
    // read config end

    $page = "home";

    if(isset($_GET["p"])) { $page = $_GET["p"]; }
    if(isset($_POST["p"])) { $page = $_POST["p"]; }

    if(isset($_GET["r"]) && $_GET["r"] == "logout") {
        header("Location: ./login/logout.php");
    }


    // database connection settings
    $host = $_SESSION['hostname'];
    $username = $_SESSION['username'];
    $password = $_SESSION['pass'];
    $database = ''; // no db

    // create a new mysqli thingamajig
    $mysqli = new mysqli($host, $username, $password, $database);

    // check if the connection was successful
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    // get ssl info
    $sslCipher = $mysqli->query("SHOW STATUS LIKE 'Ssl_cipher'")->fetch_assoc()['Value'];
    $securityType = (empty($sslCipher)) ? 'Non-SSL' : 'SSL';
    $sslUsed = $securityType == "SSL" ? true : false;

    // get the database server version
    $result = $mysqli->query("SELECT VERSION() AS version");
    $row = $result->fetch_assoc();
    $version = $row['version'];

    // check if mysql or mariadb and output it
    $isMariaDB = (stripos($version, 'MariaDB') !== false);
    $databaseType = ($isMariaDB) ? 'MariaDB' : 'MySQL';

    // get database server version
    $result = $mysqli->query("SELECT VERSION() AS version");
    $row = $result->fetch_assoc();
    $versionString = $row['version'];
    $matches = [];
    preg_match('/\d+\.\d+\.\d+/', $versionString, $matches);
    $versionNumber = $matches[0] ?? 'Unknown';

    // get list of all dbs
    $query = "SHOW DATABASES";
    $result = $mysqli->query($query);
    if (!$result) {
        // error handling is in sidebar
        // exit;
    }
    $databaseArray = array();
    while ($row = $result->fetch_array()) {
        $databaseArray[] = $row[0];
    }

    // close the database connection
    $mysqli->close();


    function isWebSecure() { // no
        return
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || $_SERVER['SERVER_PORT'] == 443;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlowSQL</title>

    <link rel="stylesheet" href="../components/style.css">
</head>
<body>
    <nav><?php include "../components/navContents.php"; ?></nav>
    <div class="sidebars"><?php include "../components/sidebars.php"; ?></div>
    <div class="container container-spacing container-spacing-top <?php if($page == "db") { echo "container-spacing-sidebar-2"; } ?>">
        <br>
        <?php if($page == "logout") { ?> 
            <p>You can logout below</p>
            <a href="./login/logout.php"><button class="btn btn-primary">Logout</button></a>
        <?php } ?>

        <?php if($page == "home") { ?> 
            <div class="panel-grid-container">
                <div class="panel-grid-item">
                    <h1>Appearance</h1>
                    <form action="./" method="post">
                        <input type="hidden" name="action" value="setAppearance">
                        <label for="setTheme">Theme: </label>
                        <select name="setTheme" id="setTheme" class="panelInput panelInputMost"> 
                            <option value="original-0">Original (default)</option>
                        </select>
                        <br><br>
                        <label for="setLanguage">Language: </label>
                        <select name="setLang" id="setLanguage" class="panelInput panelInputMost"> 
                            <option value="en">English</option>
                            <option value="de" disabled>Deutsch (in progress)</option>
                        </select>
                        <br><br>
                        <input type="submit" value="Save" class="btn btn-primary">
                    </form>
                </div>
                <div class="panel-grid-item">
                    <h1>Database Server Info</h1>
                    <form action="./" method="post">
                        <label for="dbSrvHostname">Hostname: </label>
                        <input type="text" name="dbSrvHostname" id="dbSrvHostname" class="panelInput panelInputMostTxt" value="<?php echo isset($_SESSION["hostname"]) ? str_contains($_SESSION["hostname"], "localhost") ? "127.0.0.1" : $_SESSION["hostname"] : "?"; ?>" readonly>
                        <br><br>
                        <label for="dbConnType">Connection: </label>
                        <input type="text" name="dbConnType" id="dbConnType" class="panelInput panelInputMostTxt <?php echo $sslUsed ? "" : "text-danger"; ?>" value="<?php echo $sslUsed ? "HTTPS" : "HTTP (insecure)"; ?>" readonly>
                        <br><br>
                        <label for="dbType">DB Type: </label>
                        <input type="text" name="dbType" id="dbType" class="panelInput panelInputMostTxt" value="<?php echo $databaseType; ?>" readonly>
                        <br><br>
                        <label for="dbVer">DB Version: </label>
                        <input type="text" name="dbVer" id="dbVer" class="panelInput panelInputMostTxt" value="<?php echo $versionNumber; ?>" readonly>
                        <br><br>
                        <label for="dbUser">User: </label>
                        <input type="text" name="dbUser" id="dbUser" class="panelInput panelInputMostTxt" value="<?php echo $username . "@" . $host; ?>" readonly>
                    </form>
                </div>
                <div class="panel-grid-item">
                    <h1>Web Server Info</h1>
                    <form action="./" method="post">
                        <label for="webSrvHostname">Hostname: </label>
                        <input type="text" name="webSrvHostname" id="webSrvHostname" class="panelInput panelInputMostTxt" value="<?php echo $_SERVER['SERVER_NAME'] == "localhost" ? "127.0.0.1" : $_SERVER['SERVER_NAME']; ?>" readonly>
                        <br><br>
                        <label for="webConnType">Connection: </label>
                        <input type="text" name="webConnType" id="webConnType" class="panelInput panelInputMostTxt <?php echo isWebSecure() ? "" : "text-danger"; ?>" value="<?php echo isWebSecure() ? "HTTPS" : "HTTP (insecure)"; ?>" readonly>
                        <br><br>
                        <label for="webType">Server: </label>
                        <input type="text" name="webType" id="webType" class="panelInput panelInputMostTxt" value="<?php echo $_SERVER['SERVER_SOFTWARE']; ?>" readonly>
                        <br><br>
                        <label for="usedExt">Extension: </label>
                        <input type="text" name="usedExt" id="usedExt" class="panelInput panelInputMostTxt" value="mysqli" readonly>
                        <br><br>
                        <label for="phpVer">PHP Ver: </label>
                        <input type="text" name="phpVer" id="phpVer" class="panelInput panelInputMostTxt" value="<?php echo phpversion(); ?>" readonly>
                        <br><br>
                        <label for="phpInfo">PHP Info: </label>
                        <a href="./phpInfo.php" target="_blank"><input type="text" name="phpInfo" id="phpInfo" class="panelInput panelInputMostTxt inputLink" value="See PHP info" readonly></a>
                    </form>
                </div>
                <div class="panel-grid-item">
                    <h1>FlowSQL Info</h1>
                    <form action="./" method="post">
                        <label for="flowsqlVer">Version: </label>
                        <input type="text" name="flowsqlVer" id="flowsqlVer" class="panelInput panelInputMostTxt" value="<?php echo $configData["config_version"] ?>" readonly>
                        <br><br>
                        <label for="flowsqlUpdate">Updates: </label>
                        <a href="./flowSQLUpdate.php" target="_blank"><input type="text" name="flowsqlUpdate" id="flowsqlUpdate" class="panelInput panelInputMostTxt inputLink" value="Check for updates" readonly></a>
                        <br><br>
                        <label for="flowsqlGH">GitHub: </label>
                        <a href="https://github.com/ethrythedev/FlowSQL/" rel="nofollow" target="_blank"><input type="text" name="flowsqlGH" id="flowsqlGH" class="panelInput panelInputMostTxt inputLink" value="See GitHub repo" readonly></a>
                        <br><br>
                        <label for="flowsqlLicense">Licence: </label>
                        <a href="../LICENSE" rel="nofollow" target="_blank"><input type="text" name="flowsqlLicense" id="flowsqlLicense" class="panelInput panelInputMostTxt inputLink" value="See LICENSE" readonly></a>
                    </form>
                    <br>
                </div>
            </div>
        <?php } ?>

        <?php
            if($page == "db") {
                if(isset($_GET['db'])) {
                    // modularize bc this file is getting too large
                    echo "<br>";
                    include "../components/dbStructure.php";
                    echo "<br>";
                    include "../components/dbSQLBox.php";
                    echo "<br>";
                } else {
                    echo isset($_GET['action']) ? " ": "<p>Choose a database on the left.</p>";
                    if(isset($_GET['action']) && $_GET['action'] == "addDbModal") {
                        ?>
                            <h1>Create Database</h1>
                            <form action="../components/sqlCreateDB.php" method="get" target="_blank" onsubmit="reloadAfter3Sec();">
                                <label for="sqlDBCreationName">DB Name:</label>
                                <input type="text" name="db" id="sqlDBCreationName">
                                <br><br>
                                <input type="submit" value="Create" class="width-full btn-primary btn">
                            </form>
                        <?php
                    }
                }
            }
        ?>

        <?php if($page == "settings") { ?> 
            <p>Settings can be changed in config.yml</p>
            <form action="./" method="post" id="settingsSaveForm"><input type="hidden" name="p" value="settings"><input type="hidden" name="saved" value="true"></form>
            <button onclick="document.getElementById('settingsSaveForm').submit();" class="btn btn-primary">Apply Changes</button>
            <?php if(isset($_POST['saved']) && $_POST['saved'] == true) echo "<p class='text-success'>Changes saved!</p>"; ?>
        <?php } ?>
    </div>

    <script>
        async function reloadAfter3Sec() {
            await new Promise(resolve => setTimeout(resolve, 3000));
            window.location.reload();
        }
    </script>
</body>
</html>