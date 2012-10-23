<?php

    include_once "include.php";

$con = mysql_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysql_error());
} else {
    $db_selected = mysql_select_db('BALOLSAV', $con);

    $q = strtolower($_GET["term"]);
    $q = mysql_real_escape_string($q);
    if (!$q)
        return;
    $year = getYear();
    if (strcmp($q, "######getallstuff##########") == 0) {
        $sql = "select school_id as id, school_name as school_name from school_master order by school_name";
    } else {
        $sql = "select DISTINCT school_id as id, school_name as school_name from school_master where school_name LIKE '%$q%' order by school_name";
    }
    $rsd = mysql_query($sql);
    $result = array();
    if (strcmp($q, "######getallstuff##########") == 0) {
        while ($rs = mysql_fetch_array($rsd)) {
            array_push($result, array("id" => $rs['id'], "label" => $rs['school_name'], "value" => strip_tags($rs['school_name'])));
        }
    } else {
        while ($rs = mysql_fetch_array($rsd)) {
            array_push($result, array("id" => $rs['id'], "label" => $rs['school_name'], "value" => strip_tags($rs['school_name'])));

            if (count($result) > 6)
                break;
        }
    }
    echo array_to_json($result);
    mysql_close($con);
}
?>
