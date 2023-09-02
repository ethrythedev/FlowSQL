<?php
    require_once("../components/ymlParser.php");

    $clientConfig = parseYaml(file_get_contents("../config.yml"));
    $latestConfigURL = str_replace("\"", "", $clientConfig["latest_config"]);

    $serverVersion = $newUpdateVersion = parseYaml(file_get_contents($latestConfigURL))["config_version"];
    $clientVersion = $oldUpdateVersion = parseYaml(file_get_contents("../config.yml"))["config_version"];
    $versionCompare = version_compare($serverVersion, $clientVersion);

    $downgradeAvailable = false;

    if($versionCompare == "0") {
        // no update needed
        $updateAvailable = false;
    } else {
        if($versionCompare == "1") {
            // update needed
            $updateAvailable = true;
        } else {
            // downgrade needed
            $updateAvailable = false;
            $downgradeAvailable = true;
        }
    }

    header("Content-type: application/json");
?>

{
    "update": <?php echo $updateAvailable ? "true" : "false"; ?>,
    "downgrade": <?php echo $downgradeAvailable ? "true" : "false"; ?>,
    "versions": {
        "current_version": "<?php echo $clientVersion; ?>",
        "new_version": "<?php echo $serverVersion; ?>"
    },
    "update_src": "<?php echo str_replace("\"", "", $clientConfig["update_src"]); ?>"
}