<?php

include_once "../include.php";
$insertType = $_POST["type"];
$con = mysqli_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
} else {
    $db_selected = mysqli_select_db($con, 'BALOLSAV');
    if ($db_selected) {
        if (strcmp($insertType, "byEvent") == 0) {
            $eId = $_POST["eId"];
            //need to get regn number, name, school name, sex, age
            if ($eId == 999999) {
                $query = "select * from event_trans where 1"; // order by event_name";
            } else {
                $query = "select regn_number from event_trans where event_id = $eId";
            }
            $rs = mysqli_query($con, $query);

            $partArray[] = array();
            $counter = 0;
            while ($result = mysqli_fetch_array($rs)) {
                $regn_number = $result[0];
                if ($eId == 999999)
                    $event_id = $result["event_id"];
                $query = "select * from participant_master where regn_number = $regn_number";
                $rs1 = mysqli_query($con, $query);

                $result1 = mysqli_fetch_array($rs1);
                $name = $result1["student_name"];
                $sex = $result1["sex"];
                $sex = getSex($sex);
                $age = $result1["age"];
                $school_id = $result1["school_id"];

                $query = "select school_name from school_master where school_id = $school_id";
                $rs1 = mysqli_query($con, $query);
                $result1 = mysqli_fetch_array($rs1);
                $school_name = $result1["school_name"];
                if ($eId == 999999) {
                    $query = "select event_name,event_type from event_master where event_id = $event_id";
                    $rs1 = mysqli_query($con, $query);
                    $result1 = mysqli_fetch_array($rs1);
                    $event_name = $result1["event_name"];
                    $event_name = $event_name . " - " . getEventName($result1["event_type"]);
                }
                if ($eId == 999999) {
                    $partArray[$counter] = array("rNum" => $regn_number, "ename" => $event_name, "name" => $name, "sex" => $sex, "age" => $age, "school_name" => $school_name);
                    $counter = $counter + 1;
                } else {
                    $partArray[$counter] = array("rNum" => $regn_number, "name" => $name, "sex" => $sex, "age" => $age, "school_name" => $school_name);
                    $counter = $counter + 1;
                }
            }
            if ($counter > 0) {
                $returnJson = array("participants" => $partArray);
                echo array_to_json($returnJson);
            } else {
                echo "0";
            }
        }
        mysqli_close($con);
    }
}
?>
