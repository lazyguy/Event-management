<?php
include_once "include.php";

$con = mysql_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysql_error());
} else {
    $db_selected = mysql_select_db('BALOLSAV', $con);

    $q = strtolower($_GET["term"]);
    if (!$q)
        return;
    $year = getYear();
    $sql = "select DISTINCT event_name as event_name from event_master where event_name LIKE '%$q%' and event_year='$year' order by event_name";
    $rsd = mysql_query($sql);
    $result = array();
    while ($rs = mysql_fetch_array($rsd)) {
        array_push($result, array("id" => $rs['event_name'], "label" => $rs['event_name'], "value" => strip_tags($rs['event_name'])));

        if (count($result) > 6)
            break;
    }
    echo array_to_json($result);
}
?>
