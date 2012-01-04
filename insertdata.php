<?php

include_once "include.php";


function getEventType($eType) {
    if (strcmp($eType, "Junior") == 0)
        $eType = 1;
    else if (strcmp($eType, "Senior") == 0)
        $eType = 2;
    else
        $eType = 3;
    return $eType;
}

//Array ( [type] => addEvent [eName] => [etype] => Junior )
// print_r($_POST);
$insertType = $_POST["type"];
$con = mysql_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysql_error());
} else {
    $db_selected = mysql_select_db('BALOLSAV', $con);
    if ($db_selected) {
        if (strcmp($insertType, "addEvent") == 0) {
            $ename = $_POST["eName"];
            $eType = $_POST["etype"];
            $year = getYear();
            $etype = getEventType($eType);
            //see if event exists already	
            $count = mysql_query("SELECT COUNT(*) FROM event_master WHERE event_name='$ename' and event_type='$etype'");
            $count = mysql_fetch_array($count);
            if ($count[0] < 1) {
                if (!mysql_query("INSERT INTO event_master (event_name, event_type, event_year)
				VALUES ('$ename', '$etype', '$year')")) {
                    echo "0";
                } else {
                    echo "1";
                }
            } else {
                echo "2";
            }
        } else if (strcmp($insertType, "deleteEvent") == 0) {
            $eid = $_POST["eid"];
            $year = getYear();
            $count = mysql_query("SELECT COUNT(*) FROM event_master WHERE event_id='$eid' and event_year='$year'");
            $count = mysql_fetch_array($count);
            if ($count[0] >= 1) {
                if (!mysql_query("DELETE FROM event_master WHERE event_id='$eid' and event_year='$year'")) {
                    echo "0";
                    echo mysql_error();
                } else {
                    echo "1";
                }
            } else {
                echo "2";
            }
        } else if (strcmp($insertType, "getEvent") == 0) {
            $ename = $_POST["eName"];
            $result = array();
            $year = getYear();
            $sType = $_POST["searchType"];
            if(strcmp($sType, "exact") == 0)
                $rsd = mysql_query("SELECT * FROM event_master where event_name='$ename' and event_year='$year' order by event_id");
            else
                $rsd = mysql_query("SELECT * FROM event_master where event_name LIKE '%$ename%' and event_year='$year' order by event_id");
            while ($rs = mysql_fetch_array($rsd)) {
                array_push($result, array("id" => $rs['event_id'], "value" => $rs['event_name'], "event_type" => $rs['event_type']));
            }
            echo array_to_json($result);
        }
    }
}
?>