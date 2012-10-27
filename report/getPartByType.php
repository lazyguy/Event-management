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
                $query = "select * from group_master where event_id = $eId";
            else
                $query = "select * from event_trans where event_id = $eId";

            $rs = mysqli_query($con, $query);

            $counter = 0;
            $winnercounter = 0;
            $group_id = 0;
            $gradePoint = 0;
            $regn_number = 0;
            $name = null;
            $sex = null;
            $age = 0;
            $school_id = 0;
            $counter2 = 0;
            while ($result = mysqli_fetch_array($rs)) {

                if ($isGrp == 1) {
                    $group_id = $result["group_id"];

                    $query = "select regn_number from group_trans where group_id =$group_id and  event_id = $eId";
                    //echo $query;
                    $rs1 = mysqli_query($con, $query);
                    $result1 = mysqli_fetch_array($rs1);
                    $regn_number = $result1["regn_number"];
                    $query = "select * from participant_master where regn_number = $regn_number";
                    $rs1 = mysqli_query($con, $query);
                    if (!$rs1) {
                       // echo $query;
                       // echo $regn_number;
                       // echo mysqli_error($con);
                        continue;
                    }
                    $result1 = mysqli_fetch_array($rs1);

                    $name = $result1["student_name"];
                    $sex = $result1["sex"];
                    $sex = getSex($sex);
                    $age = $result1["age"];
                    $school_id = $result1["school_id"];
                } else {
                    $regn_number = $result["regn_number"];
                    $query = "select * from participant_master where regn_number = $regn_number";
                    $rs1 = mysqli_query($con, $query);
                    $result1 = mysqli_fetch_array($rs1);

                    $name = $result1["student_name"];
                    $sex = $result1["sex"];
                    $sex = getSex($sex);
                    $age = $result1["age"];
                    $school_id = $result1["school_id"];
                }
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
                    $name_array = array();
                    $query = "select * from group_trans where group_id='$group_id' and event_id='$eId'";
                    $result11 = mysqli_query($con, $query);
                    $partName = "";
                    $student_name = "";
                    $count = 0;
                    while ($rs11 = mysqli_fetch_array($result11)) {
                        $rId = $rs11["regn_number"];
                        $query = "select student_name,school_id from participant_master where regn_number='$rId'";
                        $result2 = mysqli_query($con, $query);
                        $rs2 = mysqli_fetch_array($result2);
                        $sId = $rs2['school_id'];
                        $name_array[$count] = $rs2["student_name"];
                        $count = $count + 1;
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

                $query = "select event_name from event_master where event_id = $eId";
                $rs2 = mysqli_query($con, $query);
                if (!$rs2) {
                    echo $query;
                    //echo $eId;
                    echo mysqli_error($con);
                }
                $result1 = mysqli_fetch_array($rs2);

                $event_name = $result1["event_name"];
                if ($isGrp == 1) {
                    $counter1 = 0;
                    $winnercounter1 = 0;
                    while ($counter1 != $count) {
                        if ($eGrade != null) {
                            if (intval($position) == 1 || intval($position) == 2 || intval($position) == 3) {
                                $winnerArray[$winnercounter1] = array("event_name" => $event_name, "rNum" => $regn_number, "name" => $name_array[$counter1], "sex" => null, "position" => $position, "school_name" => $school_name);
                                $winnercounter1 = $winnercounter1 + 1;
                            } else {
                                $partArray[$counter2] = array("event_name" => $event_name, "rNum" => $regn_number, "name" => $name_array[$counter1], "sex" => null, "grade" => $eGrade, "school_name" => $school_name);
                                $counter2 = $counter2 + 1;
                            }
                        }
                        $counter1 = $counter1 + 1;
                    }
                } else {
                    if ($eGrade != null) {
                        if (intval($position) == 1 || intval($position) == 2 || intval($position) == 3) {
                            $winnerArray[$winnercounter] = array("event_name" => $event_name, "rNum" => $regn_number, "name" => $name, "sex" => $sex, "position" => $position, "school_name" => $school_name);
                            $winnercounter = $winnercounter + 1;
                        } else {
                            $partArray[$counter] = array("event_name" => $event_name, "rNum" => $regn_number, "name" => $name, "sex" => $sex, "grade" => $eGrade, "school_name" => $school_name);
                            $counter = $counter + 1;
                        }
                    }
                }
            }

            if (!isset($winnerArray))
                $winnerArray = array();
            if (!isset($partArray))
                $partArray = array();

            if (sizeof($winnerArray) > 0 && sizeof($partArray) > 0) {
                $returnJson = array("participants" => $partArray, "winners" => $winnerArray);
                echo array_to_json($returnJson);
            } elseif (sizeof($partArray) > 0) {
                $returnJson = array("participants" => $partArray, "winners" => 0);
                echo array_to_json($returnJson);
            } else if (sizeof($winnerArray) > 0) {
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
