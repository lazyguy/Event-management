<?php

include_once "include.php";

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
            $ename = mysql_real_escape_string($ename);
            $eType = $_POST["etype"];
            $year = getYear();
            $etype = getEventType($eType);
            $isgroup = $_POST["isGroup"];
            //see if event exists already
            $count = mysql_query("SELECT COUNT(*) FROM event_master WHERE event_name='$ename' and event_type='$etype'");
            $count = mysql_fetch_array($count);
            if ($count[0] < 1) {
                if (!mysql_query("INSERT INTO event_master (event_name, event_type, event_year, isgroup)
				VALUES ('$ename', '$etype', '$year', '$isgroup')")) {
                    echo "0";
                } else {
                    echo "1";
                }
            } else {
                echo "2";
            }
            mysql_close($con);
        } else if (strcmp($insertType, "deleteEvent") == 0) {
            $eid = $_POST["eid"];
            $eid = mysql_real_escape_string($eid);
            $year = getYear();
            $count = mysql_query("SELECT COUNT(*) FROM event_master WHERE event_id='$eid' and event_year='$year'");
            $count = mysql_fetch_array($count);
            if ($count[0] >= 1) {
                if (!mysql_query("DELETE FROM event_master WHERE event_id='$eid' and event_year='$year'")) {
                    echo "0";
                    Print mysql_error();
                } else {
                    echo "1";
                }
            } else {
                echo "2";
            }
            mysql_close($con);
        } else if (strcmp($insertType, "getEvent") == 0 || strcmp($insertType, "NextEventPage") == 0 || strcmp($insertType, "PrevEventPage") == 0) {
            $ename = $_POST["eName"];
            $ename = mysql_real_escape_string($ename);
            $result = array();
            $year = getYear();
            $sType = $_POST["searchType"];

            if (strcmp($insertType, "NextEventPage") == 0) {
                $current_page = $_POST["currentPage"];
                $current_page = $current_page + 1;
            } else if (strcmp($insertType, "PrevEventPage") == 0) {
                $current_page = $_POST["currentPage"];
                $current_page = $current_page - 1;
            }
            else
                $current_page = 1;
            $offset = (($current_page - 1) * $resultPerPage);

            if (strcmp($sType, "exact") == 0) {
                $count = mysql_query("SELECT COUNT(*) FROM event_master WHERE event_name='$ename' and event_year='$year'");
                $count = mysql_fetch_array($count);
                $rsd = mysql_query("SELECT * FROM event_master where event_name='$ename' and event_year='$year' order by event_name  limit $offset,$resultPerPage");
                $erroror = mysql_error();
            } else {
                $count = mysql_query("SELECT COUNT(*) FROM event_master WHERE event_name LIKE '%$ename%' and event_year='$year'");
                $count = mysql_fetch_array($count);
                $rsd = mysql_query("SELECT * FROM event_master where event_name LIKE '%$ename%' and event_year='$year' order by event_name  limit $offset,$resultPerPage");
            }
            array_push($result, array("totalcount" => $count[0]));
            while ($rs = mysql_fetch_array($rsd)) {
                array_push($result, array("id" => $rs['event_id'], "value" => $rs['event_name'], "event_type" => $rs['event_type']));
            }
            echo array_to_json($result);
            mysql_close($con);
        } else if (strcmp($insertType, "getEventbyId") == 0) {
            $result = array();
            $eid = $_POST["eid"];
            $eid = mysql_real_escape_string($eid);
            $year = getYear();
            $rsd = mysql_query("SELECT * FROM event_master WHERE event_id='$eid' and event_year='$year'");
            $erroror = mysql_error();
            if (!$rsd) {
                echo "0";
               return;
            }
            while ($rs = mysql_fetch_array($rsd)) {
                array_push($result, array("id" => $rs['event_id'], "value" => $rs['event_name'], "event_type" => $rs['event_type'],"isGroup" => $rs['isgroup']));
            }
            echo array_to_json($result);
            mysql_close($con);
        } else if (strcmp($insertType, "eventModify") == 0) {
            $eName = $_POST["eName"];
            $eName = mysql_real_escape_string($eName);
            $eType = $_POST["eType"];
            $eid = $_POST["eid"];
            $isgroup = $_POST["isGroup"];
            $eid = mysql_real_escape_string($eid);
            $year = getYear();
            $etype = getEventType($eType);
            $count = mysql_query("SELECT COUNT(*) FROM event_master WHERE event_name='$eName' and event_type='$eType' and isgroup='$isgroup'");
            $count = mysql_fetch_array($count);
            if ($count[0] < 1) {
                if (!mysql_query("UPDATE event_master SET event_name='$eName',event_type='$eType',isgroup='$isgroup' WHERE event_id='$eid'")) {
                    echo "0";
                } else {
                    echo "1";
                }
            } else {
                echo "2";
            }
            mysql_close($con);
        }
    }
}
?>