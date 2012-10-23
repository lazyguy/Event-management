<?php

$pass = $_POST["pass"];
$origPass = "e859a17c459ca78e615dcd3a79b6e1a8d3f86418c9a48921dcb5281c7853095a"; //sha256 hash for password
$passhash = hash('sha256', $pass);
if ($passhash == $origPass) {


    $con = mysql_connect("localhost", "root", "");
    if (!$con) {
        die('Could not connect: ' . mysqli_error($con));
        return;
    } else {
        $db_selected = mysql_select_db('BALOLSAV');
        if ($db_selected) {
            backup_tables("localhost", "root", "", 'BALOLSAV');
            $query = "drop database BALOLSAV";
            $result = mysql_query($query);
            if ($result) {
                echo 1;
                mysql_close($con);
                return;
            } else {
                echo -1;
                mysql_close($con);
                return;
            }
        } else {
            echo -2;
            mysql_close($con);
            return;
        }
    }
} else {
    echo 0;
    return;
}





/* backup the db OR just a table */

function backup_tables($host, $user, $pass, $name, $tables = '*') {

    $link = mysql_connect($host, $user, $pass);
    mysql_select_db($name, $link);

    //get all of the tables
    if ($tables == '*') {
        $tables = array();
        $result = mysql_query('SHOW TABLES');
        while ($row = mysql_fetch_row($result)) {
            $tables[] = $row[0];
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }
    $return = null;
    //cycle through
    foreach ($tables as $table) {
        $result = mysql_query('SELECT * FROM ' . $table);
        $num_fields = mysql_num_fields($result);

        $return.= 'DROP TABLE IF EXISTS ' . $table . ';';
        $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE ' . $table));
        $return.= "\n\n" . $row2[1] . ";\n\n";

        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = mysql_fetch_row($result)) {
                $return.= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n", "\\n", $row[$j]);
                    if (isset($row[$j])) {
                        $return.= '"' . $row[$j] . '"';
                    } else {
                        $return.= '""';
                    }
                    if ($j < ($num_fields - 1)) {
                        $return.= ',';
                    }
                }
                $return.= ");\n";
            }
        }
        $return.="\n\n\n";
    }

    //save file
    $handle = fopen('db-backup-' . date('Y-m-d_His') . '-' . '.sql', 'w+');
    fwrite($handle, $return);
    fclose($handle);
}

?>
