<?php
    function getIp(): string {
        return $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
    }

    function sqlLogin($user_name, $ipRequired = false, $neededIP = "127.0.0.1") {
        $ipError = false;
        if($ipRequired == "true") {
            if(getIp() != $neededIP) {
                return false;
            }
        }

        $_SESSION["flowsql__isloggedin"] = true;
        $_SESSION["flowsql__username"] = $user_name;
        $_SESSION["flowsql__pass"] = AUTOLOGIN_PASS;
        $_SESSION["flowsql__hostname"] = AUTOLOGIN_HOSTNAME;
    }