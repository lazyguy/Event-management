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
                    $query = "select event_name,event_type,isgroup from event_master where event_id = $event_id";
                    $rs1 = mysqli_query($con, $query);
                    $result1 = mysqli_fetch_array($rs1);
                    $event_name = $result1["event_name"];
                    $event_name = $event_name . " - " . getEventName($result1["event_type"], $result1["isgroup"]);
                    if ($result1["isgroup"] == 1) {
                        $query = "select group_id from group_trans where event_id = $event_id and regn_number=$regn_number ";
                        $rs3 = mysqli_query($con, $query);
                        $result3 = mysqli_fetch_array($rs3);
                        $group_id = $result3["group_id"];
                        $regn_number = $group_id;
                    }
                } else {
                    $query = "select event_name,event_type,isgroup from event_master where event_id = $eId";
                    $rs1 = mysqli_query($con, $query);
                    $result1 = mysqli_fetch_array($rs1);
                    if ($result1["isgroup"] == 1) {
                        $query = "select group_id from group_trans where event_id = $eId and regn_number=$regn_number ";
                        $rs3 = mysqli_query($con, $query);
                        $result3 = mysqli_fetch_array($rs3);
                        $group_id = $result3["group_id"];
                        $regn_number = $group_id;
                    }
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
        } else if (strcmp($insertType, "byEventWinner") == 0) {
            $eId = $_POST["eId"];
            $isGrp = 0;
            //check if event is a group event or not
            $query = "select * from event_master where event_id = $eId";

            $rs = mysqli_query($con, $query);
            $result = mysqli_fetch_array($rs);
            if ($result["isgroup"] == 1)
                $isGrp = 1;
            else
                $isGrp = 0;
            //need to get regn number, name, school name, sex, age
            if ($isGrp == 1)
                $query = "select * from group_trans where event_id = $eId";
            else
                $query = "select * from event_trans where event_id = $eId";

            $rs = mysqli_query($con, $query);

            $partArray[] = array();
            $winnerArray[] = array();
            $counter = 0;
            $winnercounter = 0;
            $group_id = 0;
            $gradePoint = 0;
            while ($result = mysqli_fetch_array($rs)) {

                $regn_number = $result["regn_number"];
                if ($isGrp == 1)
                    $group_id = $result["group_id"];

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
                if ($isGrp == 1)
                    $query = "select * from group_result where event_id = $eId and group_id = $group_id";
                else
                    $query = "select position from event_result where event_id = $eId and regn_number = $regn_number";
                $rs1 = mysqli_query($con, $query);

                $result1 = mysqli_fetch_array($rs1);
                if ($isGrp == 1)
                    $position = $result1["result"];
                else
                    $position = $result1["position"];

                if ($isGrp == 1) {
                    $name = null;
                    $query = "select * from group_trans where group_id='$group_id' and event_id='$eId'";
                    $result11 = mysqli_query($con, $query);
                    $partName = "";
                    $student_name = "";
                    while ($rs11 = mysqli_fetch_array($result11)) {
                        $rId = $rs11["regn_number"];
                        $query = "select student_name,school_id from participant_master where regn_number='$rId'";
                        $result2 = mysqli_query($con, $query);
                        $rs2 = mysqli_fetch_array($result2);
                        $sId = $rs2['school_id'];
                        $name = $name . $rs2["student_name"] . ',';
                    }
                    $gradePoint = $result1["grade"]; // . "/" . $result1["marks"];
                }
                else
                    $gradePoint = $result["event_grade"]; // . "/" . $result["event_marks"];

                $eGrade = null;
                $eMarks = null;

                if ($isGrp == 1) {
                    $eGrade = $result1["grade"];
                    $eMarks = $result1["marks"];
                    $regn_number = $group_id;
                } else {
                    $eGrade = $result["event_grade"];
                    $eMarks = $result["event_marks"];
                }

                if ($eGrade != null) {
                    if (intval($position) == 1 || intval($position) == 2 || intval($position) == 3) {
                        $winnerArray[$winnercounter] = array("rNum" => $regn_number, "name" => $name, "sex" => $sex, "position" => $position, "school_name" => $school_name);
                        $winnercounter = $winnercounter + 1;
                    } else {
                        $partArray[$counter] = array("rNum" => $regn_number, "name" => $name, "sex" => $sex, "grade" => $gradePoint, "school_name" => $school_name);
                        $counter = $counter + 1;
                    }
                }
            }
            if ($counter > 0 && $winnercounter > 0) {
                $returnJson = array("participants" => $partArray, "winners" => $winnerArray);
                echo array_to_json($returnJson);
            } elseif ($counter > 0 && $winnercounter <= 0) {
                $returnJson = array("participants" => $partArray, "winners" => 0);
                echo array_to_json($returnJson);
            } else if ($counter <= 0 && $winnercounter > 0) {
                $returnJson = array("participants" => 0, "winners" => $winnerArray);
                echo array_to_json($returnJson);
            } else {
                echo "0";
            }
        }
        mysqli_close($con);
    }
}
?>
