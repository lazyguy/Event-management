<?php

$pass = $_POST["pass"];
$origPass = "4805052e6cbb52609d947ee0a0bc2496c8ee82dffb94ffdfb93e800e14b01638"; //sha256 hash for password
$passhash = hash('sha256', $pass);
if ($passhash == $origPass) {


    $con = mysqli_connect("localhost", "root", "");
    if (!$con) {
        die('Could not connect: ' . mysqli_error($con));
        return;
    } else {
        $db_selected = mysqli_select_db($con, 'BALOLSAV');
        if ($db_selected) {
            backup_tables("localhost", "root", "", 'BALOLSAV');
            $query = "drop database BALOLSAV";
            $result = mysqli_query($con, $query);
            if ($result) {
                echo 1;
                mysqli_close($con);
                return;
            } else {
                echo -1;
                mysqli_close($con);
                return;
            }
        } else {
            echo -2;
            mysqli_close($con);
            return;
        }
    }
} else {
    echo 0;
    mysqli_close($con);
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

    //cycle through
    foreach ($tables as $table) {
        $result = mysql_query('SELECT * FROM ' . $table);
        $num_fields = mysql_num_fields($result);

        $return.= 'DROP TABLE ' . $table . ';';
        $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE ' . $table));
        $return.= "\n\n" . $row2[1] . ";\n\n";

        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = mysql_fetch_row($result)) {
                $return.= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = ereg_replace("\n", "\\n", $row[$j]);
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
    $handle = fopen('db-backup-' . time() . '-' . (md5(implode(',', $tables))) . '.sql', 'w+');
    fwrite($handle, $return);
    fclose($handle);
}

?>
