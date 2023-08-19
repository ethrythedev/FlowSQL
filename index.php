<?php
    session_start();

    // read config
    require_once "./components/ymlParser.php";
    $yamlContent = file_get_contents('config.yml');
    $configData = parseYaml($yamlContent);
    // read config end

    if($configData["autologin"] == true) {
        // sqlLogin();
    }

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
        header("Location: ./panel/");
        exit;
    }

    function is_ssl(){
        if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO']=="https"){ return true; }
        elseif(isset($_SERVER['HTTPS'])){ return true; }
        elseif($_SERVER['SERVER_PORT'] == 443){ return true; }
        else{ return false; }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to FlowSQL database</title>

    <?php if($configData["font_src"] == "bunny") { ?>
    <!-- use fonts.bunny.net -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=ubuntu:300,300i,400,400i,500,500i,700,700i" rel="stylesheet" />
    <?php } elseif ($configData["font_src"] == "google") { ?>
    <!-- use fonts.google.com -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <?php } ?>

    <link rel="stylesheet" href="./components/style.css">
</head>
<body>
    <nav><?php include "./components/navContents.php"; ?></nav>

    <div class="center">
        <div class="center-inner">
            <div class="center-2">
                <div class="center-inner-2">
                    <h1>Login</h1>
                    <p>Login to your MariaDB/MySQL database with FlowSQL.</p>
                    <?php
                        if(is_ssl()){
                            // WHAT TO DO IF IT IS SSL / HTTPS
                        }else{
                            echo "<p class='text-danger'>⚠ This server is not using HTTPS!</p>";
                        }
                    ?>
                    <br>
                    <form action="./panel/login/login.php" method="post">
                        <input type="hidden" name="login" value="true">
                        <div class="d-flex d-flex-gap d-flex-center userlogintxt">
                            <label for="loginUser">Username:</label>
                            <input type="text" name="username" id="loginUser" class="logininput">
                        </div><br>
                        <div class="d-flex d-flex-gap d-flex-center userlogintxt">
                            <label for="loginPass">Password:</label>
                            <input type="password" name="password" id="loginPass" class="logininput">
                        </div><br>
                        <?php if($configData["allow_ext_srcs"] == "true") { ?> 
                        <div>
                            <div class="d-flex d-flex-gap d-flex-center userlogintxt">
                                <label for="externalLoc">Location:</label>
                                <input type="text" name="loc" id="externalLoc" class="logininput" value="localhost:3306">
                            </div>
                            <p class="text-secondary spacing-0"><i>Do not use localhost unless<a href="#<?php echo rand(1,9999999); ?>" class="no-decoration" onclick="document.getElementById('unlessExtended1').classList.remove('d-none'); this.classList.add('d-none'); return false;">...</a><span id="unlessExtended1" class="d-none"> DB is hosted on the same server.</span></i></p>
                        </div>
                        <br>
                        <?php } ?>
                        <input type="submit" value="Login" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div style="position: fixed; bottom: 10px; color: gray; left: 50%; transform: translateX(-50%);">FlowSQL v<?php echo $configData["config_version"]; ?><span style="margin-left: 7px; margin-right: 7px;">&nbsp;</span><a href="./LICENSE">License</a></div>
</body>
</html>