<?php
    function parseYaml($yaml) {
        $lines = explode("\n", $yaml);
        $data = [];

        $context = [];
        $indentationLevel = 0;
        $previousIndentationLevel = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            $indentationLevel = strlen($line) - strlen(ltrim($line));
            $line = ltrim($line);

            if (empty($line) || $line[0] === '#') {
                // skip empty lines and comments
                continue;
            }

            $keyValue = explode(':', $line, 2);
            $key = trim($keyValue[0]);
            $value = isset($keyValue[1]) ? trim($keyValue[1]) : null;

            // indentation not supported :(
            // set top level key/value pair
            $data[$key] = $value;
            $context = &$data;
        }

        return $data;
    }
?>