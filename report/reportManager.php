<?php

include_once "../include.php";
$insertType = $_POST["type"];
$con = mysqli_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
} else {
    $db_selected = mysqli_select_db($con, 'BALOLSAV');
    if ($db_selected) {
        if (strcmp($insertType, "getSummary") == 0) {
            $returnJson = getSummary($con);
            echo array_to_json($returnJson);
        }
        mysqli_close($con);
    }
}

function getSummary($con) {
    $query = "select count(*) from participant_master where sex='1'";
    $rsd = mysqli_query($con, $query);
    $count = mysqli_fetch_array($rsd);
    $maleCount = $count[0];

    $query = "select count(*) from participant_master where sex='2'";
    $rsd = mysqli_query($con, $query);
    $count = mysqli_fetch_array($rsd);
    $femaleCount = $count[0];

    $query = "select count(*) from participant_master where category='1'";
    $rsd = mysqli_query($con, $query);
    $count = mysqli_fetch_array($rsd);
    $juniorCount = $count[0];

    $query = "select count(*) from participant_master where category='2'";
    $rsd = mysqli_query($con, $query);
    $count = mysqli_fetch_array($rsd);
    $seniorCount = $count[0];
    $partArray = array("maleCount" => $maleCount, "femaleCount" => $femaleCount, "juniorCount" => $juniorCount, "seniorCount" => $seniorCount);

    $query = "select count(*) from school_master where 1";
    $rsd = mysqli_query($con, $query);
    $result = mysqli_fetch_array($rsd);
    $schoolCount = $result[0];

    $query = "select count(*) from event_master where 1";
    $rsd = mysqli_query($con, $query);
    $result = mysqli_fetch_array($rsd);
    $eventCount = $result[0];

    $countsArray = array("schoolCount" => $schoolCount, "eventsCount" => $eventCount);

    $sArray[] = array();
    $query = "SELECT school_id from school_master";
    $rsd = mysqli_query($con, $query);
    $counter = 0;
    while ($result = mysqli_fetch_array($rsd)) {
        $sId = $result["school_id"];

        $query = "SELECT SUM( asset_sums ) as total_sum,school_id1 FROM ( SELECT 
                b.school_id AS school_id1, AVG( a.fee_paid ) AS asset_sums 
                FROM event_trans a, participant_master b WHERE b.regn_number = a.regn_number 
                GROUP BY b.regn_number, b.school_id )inner_query where school_id1=$sId";

        $rsd0 = mysqli_query($con, $query);
        $result0 = mysqli_fetch_array($rsd0);
        $sSumPaid = $result0["total_sum"];
        if ($sSumPaid == NULL)
            $sSumPaid = 0;

        $query = "select school_name from school_master where school_id = $sId";
        $rsd1 = mysqli_query($con, $query);
        $result1 = mysqli_fetch_array($rsd1);
        $sName = $result1["school_name"];

        $query = "select count(*) from participant_master where school_id = $sId and category='1'";
        $rsd1 = mysqli_query($con, $query);
        $result1 = mysqli_fetch_array($rsd1);
        $juniorCount = $result1[0];

        $query = "select count(*) from participant_master where school_id = $sId and category='2'";
        $rsd1 = mysqli_query($con, $query);
        $result1 = mysqli_fetch_array($rsd1);
        $seniorCount = $result1[0];

        $sArray[$counter] = array("sId" => $sId, "sName" => $sName, "sCount" => $seniorCount, "jCount" => $juniorCount, "sSum" => $sSumPaid);
        $counter = $counter + 1;
    }

    $eArray[] = array();
    $query = "SELECT * from event_master";
    $rsd = mysqli_query($con, $query);
    $counter = 0;
    while ($result = mysqli_fetch_array($rsd)) {
        $eId = $result["event_id"];
        $eName = $result["event_name"];
        $eName = $eName . " - " . getEventName($result["event_type"]);
        $query = "select count(*) from participant_master where regn_number IN(select regn_number from event_trans where event_id = $eId) and category = '1'";
        $rsd0 = mysqli_query($con, $query);
        $result0 = mysqli_fetch_array($rsd0);
        $jCount = $result0[0];

        $query = "select count(*) from participant_master where regn_number IN(select regn_number from event_trans where event_id = $eId) and category = '1'";
        $rsd0 = mysqli_query($con, $query);
        $result0 = mysqli_fetch_array($rsd0);
        $sCount = $result0[0];

        $eArray[$counter] = array("eId" => $eId, "eName" => $eName, "jCount" => $jCount, "sCount" => $sCount);
        $counter = $counter + 1;
    }

//  
    $returnJson = array("participant" => $partArray, "counts" => $countsArray, "school" => $sArray, "events" => $eArray);
    return $returnJson;
}
