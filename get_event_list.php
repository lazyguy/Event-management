<?php

include_once "include.php";

function convertEventType($eType) {
    switch ($eType) {
        case 1:
            $eType = "Junior";
            break;
        case 2:
            $eType = "Senior";
            break;
        case 3:
            $eType = "Junior and Senior";
            break;
    }
    return $eType;
}

$con = mysql_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysql_error());
} else {
    $db_selected = mysql_select_db('BALOLSAV', $con);

    $q = strtolower($_GET["term"]);
    if (!$q)
        return;
    $year = getYear();
    if (strcmp($q, "######getallstuff##########") == 0) {
        $sql = "select * from event_master where event_year='$year' order by event_name";
    } else {
        $sql = "select DISTINCT event_name as event_name, event_id as event_id
    from event_master where event_name LIKE '%$q%' and event_year='$year' order by event_name";
    }
    $rsd = mysql_query($sql);
    $result = array();
    if (strcmp($q, "######getallstuff##########") != 0) {
        while ($rs = mysql_fetch_array($rsd)) {

            array_push($result, array("id" => $rs['event_id'], "label" => $rs['event_name'], "value" => strip_tags($rs['event_name'])));

            if (count($result) > 6)
                break;
        }
    }else {
        while ($rs = mysql_fetch_array($rsd)) {
            array_push($result, array("id" => $rs['event_id'], "label" => convertEventType($rs['event_type']), "value" => strip_tags($rs['event_name'])));
        }
    }
    echo array_to_json($result);
    mysql_close($con);
}
?>
