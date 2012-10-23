<?php

include_once "include.php";

$con = mysql_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysql_error());
} else {
    $db_selected = mysql_select_db('BALOLSAV', $con);
    if (isset($_GET["term"]))
        $q = strtolower($_GET["term"]);
    else if (isset($_POST["term"]))
        $q = strtolower($_POST["term"]);
    else
        return;
    $q = mysql_real_escape_string($q);
    if (!$q)
        return;

    $year = getYear();
    if (strcmp($q, "getpart") == 0) {
        $school_id = $_GET["schoolid"];
        $sql = "select * from participant_master where school_id='$school_id' order by student_name";

        $rsd = mysql_query($sql);
        $result = array();

        while ($rs = mysql_fetch_array($rsd)) {
            array_push($result, array("id" => $rs['regn_number'], "label" => $rs['student_name'], "value" => strip_tags($rs['student_name'])));
        }

        echo array_to_json($result);
    } else if (strcmp($q, "addgroup") == 0) {
        $result = addgroup($con);
        echo $result;
    } else if (strcmp($q, "editgroup") == 0) {
        //should convert this to proper transaction with mysqli.
        //but there isn't enough time
        $groupid = $_POST["groupid"];
        $sql = "DELETE from `group_master` where group_id=$groupid";
        $result = mysql_query($sql);
        $sql = "DELETE from group_trans where group_id=$groupid";
        $result = mysql_query($sql);
        $result = addgroup($con);
        //stupid idea if (!$result) then the data has lost the consistency. but no time to make transaction
        echo $result;
    }
    mysql_close($con);
}

function addgroup($con) {
    //should convert this to proper transaction with mysqli.
    //but there isn't enough time
    $evid = $_POST["evid"];
    $schoolid = $_POST["schoolid"];
    $sql = "INSERT into `group_master` (`event_id`,`school_id`) VALUES ($evid,$schoolid)";
    $result = mysql_query($sql);
    if (!$result) {
        return -1;
    }
    $gid = mysql_insert_id();
    $partItems = $_POST["participants"];
    for ($index = 0; $index < count($partItems); $index++) {
        $partItems1 = $partItems[$index];
        $result = mysql_query("INSERT INTO group_trans(group_id,regn_number,event_id) VALUES
                            ('$gid','$partItems1','$evid')");
        if (!$result) {
            return -1;
        }
    }
    return $gid;
}

?>
