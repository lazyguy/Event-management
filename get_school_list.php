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
    $sql = "select DISTINCT school_id as id, school_name as school_name from school_master where school_name LIKE '%$q%' order by school_name";
    $rsd = mysql_query($sql);
    $result = array();
    while ($rs = mysql_fetch_array($rsd)) {
        array_push($result, array("id" => $rs['id'], "label" => $rs['school_name'], "value" => strip_tags($rs['school_name'])));

        if (count($result) > 6)
            break;
    }
    echo array_to_json($result);
    mysql_close($con);
}
?>
