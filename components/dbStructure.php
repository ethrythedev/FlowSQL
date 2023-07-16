<?php
    if(!isset($_GET['db']) || !isset($_SESSION['username'])) {
        die("error");
    }
?>

<div class="db-box overflow-lr-auto">
    <h1 class="spacing-0">Structure</h1>
    <div>
        <?php 
            // database settings
            $host = $_SESSION['hostname'];
            $username = $_SESSION['username'];
            $password = $_SESSION['pass'];
            $database = base64_decode($_GET['db']);

            if(!isset($_GET['table'])) {
                // create the thing
                $mysqli = new mysqli($host, $username, $password, $database);

                // check if connection works
                if ($mysqli->connect_errno) {
                    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
                    exit();
                }

                // get table names
                $query = "SHOW TABLES";
                $result = $mysqli->query($query);

                // check if the query was successful
                if (!$result) {
                    echo "Error retrieving table list: " . $mysqli->error;
                    exit();
                }

                // store the table names in an array
                $tableArray = array();
                while ($row = $result->fetch_array()) {
                    $tableArray[] = $row[0];
                }

                $tables = $tableArray;

                // close the db connection
                $mysqli->close();

                for ($i=0; $i < count($tables); $i++) { 
                    if($i % 2 == 0) {
                        // even number
                        $bgHighlighted = false;
                    } else {
                        // odd number
                        $bgHighlighted = true;
                    }

                    $tableClass = $bgHighlighted ? "highlighted-table-part" : "non-highlighted-table-part";
                    $tableVal = $tables[$i];

                    $database64 = base64_encode($database);
                    $tableVal64 = base64_encode($tableVal);

                    echo "<div class=\"table-part $tableClass\" onclick=\"window.location.href = '?p=db&db=$database64&table=$tableVal64';\">$tableVal</div>";
                }
            } else {
                $table = base64_decode($_GET['table']);

                // basically repeat everything thats above
                $mysqli = new mysqli($host, $username, $password, $database);
                if ($mysqli->connect_errno) {
                    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
                    exit();
                }

                // get all columns and rows from table
                $query = "SELECT * FROM $table";
                $result = $mysqli->query($query);

                // check if the query was successful
                if (!$result) {
                    echo "Error retrieving data from table: " . $mysqli->error;
                    exit();
                }

                // store the table data in an array
                $tableData = array();
                while ($row = $result->fetch_assoc()) {
                    $tableData[] = $row;
                }

                // check if any data was retrieved
                if (empty($tableData)) {
                    echo "No data found in the table.";
                    exit();
                }

                // output the table as HTML
                echo '<table>';
                // table headers
                echo '<tr>';
                foreach ($tableData[0] as $column => $value) {
                    echo '<th class="dbTableOldTh">' . $column . '</th>';
                }
                echo '</tr>';

                // table rows
                $i = 0;
                foreach ($tableData as $row) {
                    if($i % 2 == 0) {
                        // even number
                        $bgHighlighted = false;
                    } else {
                        // odd number
                        $bgHighlighted = true;
                    }

                    $trClass = $bgHighlighted ? "highlighted-table-part" : "non-highlighted-table-part";

                    echo '<tr class="hover-bg-silver">';
                    foreach ($row as $value) {
                        echo '<td class="dbTableOldTd">' . $value . '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';


                // close the connection
                $mysqli->close();

                echo "<p class='text-secondary'>This page will be redesigned & improved soon&trade;.</p>";
            }
        ?>
    </div>
</div>