<?php

include_once "../include.php";
$insertType = $_POST["type"];
$con = mysqli_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
} else {
    $db_selected = mysqli_select_db($con, 'BALOLSAV');
    if ($db_selected) {
        if (strcmp($insertType, "bySchool") == 0) {
            $eId = $_POST["eId"];
            $counter = 0;
            if ($eId == 999999) {
                $query = "select * from event_result where 1 order by regn_number";
            } else {
                $query = "select * from event_result where regn_number  in
                     (select regn_number from event_trans where regn_number in
                     (select regn_number from participant_master where school_id=$eId))";
            }
            $rs = mysqli_query($con, $query);
            $returnArray[] = array();
            while ($result = mysqli_fetch_array($rs)) {
                //need to return regn number,name,school name, event name, position
                $event_id = $result["event_id"];
                $regn_number = $result["regn_number"];
                $position = $result["position"];

                $query = "select student_name,school_id from participant_master where regn_number = $regn_number";
                $rs1 = mysqli_query($con, $query);
                echo mysqli_error($con);
                $result1 = mysqli_fetch_array($rs1);
                $school_id = $result1["school_id"];
                $student_name = $result1["student_name"];

                $query = "select school_name from school_master where school_id = $school_id";
                $rs1 = mysqli_query($con, $query);
                $result1 = mysqli_fetch_array($rs1);
                $school_name = $result1["school_name"];

                $query = "select event_name from event_master where event_id = $event_id";
                $rs1 = mysqli_query($con, $query);
                $result1 = mysqli_fetch_array($rs1);
                $event_name = $result1["event_name"];


                $returnArray[$counter] = array("regn_number" => $regn_number,
                    "name" => $student_name, "school_name" => $school_name,
                    "event_name" => $event_name, "position" => $position);
                $counter = $counter + 1;
            }

            //Group events
            if ($eId == 999999) {
                $query = "select * from group_result where 1 order by group_id";
            } else {
                $query = "select * from group_result where group_id  in
                     (select group_id from group_master where school_id =$eId)";
            }
            $rs = mysqli_query($con, $query);
            while ($result = mysqli_fetch_array($rs)) {
                //need to return regn number,name,school name, event name, position
                $event_id = $result["event_id"];
                $regn_number = $result["group_id"];
                $position = $result["result"];

                $query = "select * from group_trans where group_id='$regn_number' and event_id='$event_id'";
                $result1 = mysqli_query($con, $query);
                $partName = "";
                $student_name ="";
                while ($rs1 = mysqli_fetch_array($result1)) {
                    $rId = $rs1["regn_number"];
                    $query = "select student_name,school_id from participant_master where regn_number='$rId'";
                    $result2 = mysqli_query($con, $query);
                    $rs2 = mysqli_fetch_array($result2);
                    $sId = $rs2['school_id'];
                    $student_name = $student_name . $rs2["student_name"] . ',';
                }

                $query = "select school_name from school_master where school_id = $sId";
                $rs1 = mysqli_query($con, $query);
                $result1 = mysqli_fetch_array($rs1);
                $school_name = $result1["school_name"];

                $query = "select event_name from event_master where event_id = $event_id";
                $rs1 = mysqli_query($con, $query);
                $result1 = mysqli_fetch_array($rs1);
                $event_name = $result1["event_name"];

                $returnArray[$counter] = array("regn_number" => $regn_number,
                    "name" => $student_name, "school_name" => $school_name,
                    "event_name" => $event_name, "position" => $position);
                $counter = $counter + 1;
            }


            echo mysqli_error($con);
            $returnSumArray[] = array();
            $query = "select * from school_master where 1";
            $rs = mysqli_query($con, $query);
            $counter1 = 0;
            while ($result = mysqli_fetch_array($rs)) {
                //need to return regn number,name,school name, event name, position
                $school_id = $result["school_id"];
                $school_name = $result["school_name"];

                $query = "select sum(event_marks) as marks_sum from event_trans where regn_number in (select regn_number from participant_master where school_id = $school_id)";
                $rs1 = mysqli_query($con, $query);
                $result1 = mysqli_fetch_array($rs1);
                $marks_sum = $result1["marks_sum"];
                if ($marks_sum == null)
                    $marks_sum = 0;

                $query = "select sum(marks) as marks_sum from group_result where group_id in (select group_id from group_master where school_id = $school_id)";
                $rs1 = mysqli_query($con, $query);
                $result1 = mysqli_fetch_array($rs1);
                $marks_sum = $marks_sum + $result1["marks_sum"];
                if ($marks_sum == null)
                    $marks_sum = 0;

                $returnSumArray[$counter1] = array("school_id" => $school_id,
                    "school_name" => $school_name, "marks_sum" => $marks_sum);
                $counter1 = $counter1 + 1;
            }

            if ($counter > 0 && $counter1 > 0) {
                $returnJson = array("participants" => $returnArray, "sum" => $returnSumArray);
            } else if ($counter > 0) {
                $returnJson = array("participants" => $returnArray);
            } else if ($counter1 > 0) {
                $returnJson = array("sum" => $returnSumArray);
            } else {
                echo "0";
                mysqli_close($con);
                return;
            }
            echo array_to_json($returnJson);
        }
        mysqli_close($con);
    }
}
?>
