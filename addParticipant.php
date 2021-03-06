<?php

include_once "include.php";
$insertType = $_POST["type"];
$con = mysqli_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
} else {
    $db_selected = mysqli_select_db($con, 'BALOLSAV');
    if ($db_selected) {
        if (strcmp($insertType, "addParticipant") == 0 || strcmp($insertType, "editParticipant") == 0) {
            $participantName = $_POST["participantName"];
            $participantName = mysqli_real_escape_string($con, $participantName);
            $DOB = $_POST["DOB"];
            $SEX = $_POST["SEX"];
            $SEX = mysqli_real_escape_string($con, $SEX);
            $partItems = $_POST["partItems"];
            $partSId = $_POST["partSId"];
            $partSId = mysqli_real_escape_string($con, $partSId);
            $partParentName = $_POST["partParentName"];
            $partParentName = mysqli_real_escape_string($con, $partParentName);
            $partAddress = $_POST["partAddress"];
            $partAddress = mysqli_real_escape_string($con, $partAddress);
            $partMailid = $_POST["partMailid"];
            $partMailid = mysqli_real_escape_string($con, $partMailid);
            $partPhNum = $_POST["partPhNum"];
            $partPhNum = mysqli_real_escape_string($con, $partPhNum);
            $partFeePaid = $_POST["partFeePaid"];
            $partFeePaid = mysqli_real_escape_string($con, $partFeePaid);

            //explode the date to get month, day and year
            $birthDate = explode("/", $DOB);
            //get age from date or birthdate
            $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y") - $birthDate[2]) - 1) : (date("Y") - $birthDate[2]));
            if (strcmp($insertType, "editParticipant") != 0) {
                $count = mysqli_query($con, "SELECT COUNT(*) FROM participant_master WHERE
                     student_name='$participantName' and dob=STR_TO_DATE('$DOB', '%d/%m/%YY') and school_id='$partSId'");
                $count = mysqli_fetch_array($count);
            } else {
                $count[0] = 0;
            }
            if ($count[0] < 1) {
                // check for mixed events - assuming a participant can only register for one
                // type of event senior/junior
                $comma_separated = implode(",", $partItems);
                $query1 = "SELECT COUNT( DISTINCT event_type ) FROM event_master WHERE event_id IN ( $comma_separated )";
                $rsd = mysqli_query($con, $query1);
                $count = mysqli_fetch_array($rsd);
                if ($count[0] > 1) {
                    $result = array("result" => -1);
                    echo array_to_json($result);
                    mysqli_close($con);
                    return;
                }
                $query1 = "SELECT AVG( DISTINCT event_type ) FROM event_master WHERE event_id IN ( $comma_separated )";
                $rsd = mysqli_query($con, $query1);
                $category = mysqli_fetch_array($rsd);

//                mysqli_autocommit($con, FALSE);
                if (strcmp($insertType, "editParticipant") != 0) {
                    $rsd = mysqli_query($con, "SELECT MAX( regn_number ) as nextid FROM participant_master where 1");
                    $rs = mysqli_fetch_array($rsd);
                    $regn_number = $rs['nextid'] + 1;   //get next allowed id;
                    if ($regn_number == 1) {  //start the reg id from 200
                        $regn_number = 200;
                    }
                } else {    // check if edit participant, remove the entry and add again with new values
                    $regn_number = $_POST["partId"];
                    $query = "delete from participant_master where regn_number='$regn_number'";
                    $result = mysqli_query($con, $query);
                    if ($result === FALSE) {
     //                   mysqli_rollback($con);  // if error, roll back transaction
                        $result = array("result" => -10);   //returning some value other than 1(success), -1(senior/junior conflict) and 2(participant already exist incase of add)
                        echo array_to_json($result);
     //                   mysqli_autocommit($con, TRUE);
                        mysqli_close($con);
                        return;
                    }
                }

                $query = "INSERT INTO participant_master (regn_number,
                    student_name,age,dob,sex,school_id,parent_name,st_adress,
                    pa_mail_id,pa_phone_number,category) VALUES ('$regn_number',
                    '$participantName','$age',STR_TO_DATE('$DOB', '%d/%m/%YY'),'$SEX','$partSId',
                    '$partParentName','$partAddress','$partMailid','$partPhNum','$category[0]]')";

                $result = mysqli_query($con, $query);

                if ($result === FALSE) {
  //                  mysqli_rollback($con);  // if error, roll back transaction
                    $result = array("result" => 0);
                    echo array_to_json($result);
    //                mysqli_autocommit($con, TRUE);
                    mysqli_close($con);
                    return;
                }
                //for update of event_trans, there can be 3 operations
                //insert new event or delete an existing event and change fee paid.
                //to do this, we do 2 array diff ->
                //lets say $partItems contains new item list from edit form
                //and $evList contains item list previously entered
                //array_diff($partItems, $evList); will give an array of
                //items which needs to be added newly
                //array_diff($evList, $partItems); will give an array of
                //items which needs to be deleted.
                $evList = array();  //this is list of events for which participant already registered.
                //$partItems is list of events from edit form.
                if (strcmp($insertType, "editParticipant") == 0) {

                    $query = "SELECT * FROM `event_trans` WHERE regn_number=$regn_number";
                    $result2 = mysqli_query($con, $query);
                    $feePaid = 0;
                    while ($rs2 = mysqli_fetch_array($result2)) {
                        array_push($evList, $rs2['event_id']);
                        $feePaid = $rs2['fee_paid'];
                    }
                    /*
                      print_r($evList);
                      print_r($partItems);
                      echo("to be added");
                      print_r(array_values(array_diff($partItems,$evList)));    //newly added events array
                      echo("to be deleted");
                      print_r(array_values(array_diff($evList,$partItems)));    //deleted events array
                      return;
                     */
                    $eventsToAdd = array_values(array_diff($partItems, $evList));
                    $eventsToDelete = array_values(array_diff($evList, $partItems));

                    $id_nums = implode(", ", $eventsToDelete);
                    if (count($eventsToDelete) > 0) {
                        $query = "delete from event_trans where event_id in ($id_nums)";
                        $result = mysqli_query($con, $query);
                        if ($result === FALSE) {
                            //mysqli_rollback($con);  // if error, roll back transaction
                            $result = array("result" => -11);
                            echo array_to_json($result);
     //                       mysqli_autocommit($con, TRUE);
                            mysqli_close($con);
                            return;
                        }
                    }
                    if ($partFeePaid != $feePaid) {
                        //need to update fee paid for all events.....
                        $query = "update event_trans set fee_paid = $partFeePaid where regn_number=$regn_number";
                        $result = mysqli_query($con, $query);
                        if ($result === FALSE) {
                            //mysqli_rollback($con);  // if error, roll back transaction
                            $result = array("result" => -12);
                            echo array_to_json($result);
 //                           mysqli_autocommit($con, TRUE);
                            mysqli_close($con);
                            return;
                        }
                    }
                    if (count($eventsToAdd) > 0) {
                        for ($index = 0; $index < count($eventsToAdd); $index++) {
                            $partItemsEscaped = mysqli_real_escape_string($con, $eventsToAdd[$index]);
                            $result = mysqli_query($con, "INSERT INTO event_trans(regn_number,event_id,fee_paid) VALUES
                            ('$regn_number','$partItemsEscaped', '$partFeePaid')");
                            if ($result !== TRUE) {
                                //mysqli_rollback($con);  // if error, roll back transaction
                                $result = array("result" => -13);
                                echo array_to_json($result);
   //                             mysqli_autocommit($con, TRUE);
                                mysqli_close($con);
                                return;
                            }
                        }
                    }
                } else {
                    //in case of new participant no problem, just
                    //insert all items into the items table with student id.
                    //      $partId = mysqli_insert_id();
                    for ($index = 0; $index < count($partItems); $index++) {
                        $partItemsEscaped = mysqli_real_escape_string($con, $partItems[$index]);
                        $result = mysqli_query($con, "INSERT INTO event_trans(regn_number,event_id,fee_paid) VALUES
                            ('$regn_number','$partItemsEscaped', '$partFeePaid')");
                        if ($result !== TRUE) {
                            //mysqli_rollback($con);  // if error, roll back transaction
                            $result = array("result" => 0);
                            echo array_to_json($result);
   //                         mysqli_autocommit($con, TRUE);
                            mysqli_close($con);
                            return;
                        }
                    }
                }
                mysqli_commit($con);
 //               mysqli_autocommit($con, TRUE);
                $result = array("result" => 1, "sid" => $regn_number);
                echo array_to_json($result);
                //  echo "1";
            } else {
                //participant already exists
                $result = array("result" => 2);
                echo array_to_json($result);
            }
            mysqli_close($con);
        } else if (strcmp($insertType, "getParticipant") == 0) {
            $regId = $_POST["regId"];
            $regId = mysqli_real_escape_string($con, $regId);
            $eid = $_POST["eid"];
            $count = mysqli_query($con, "select count(*) from participant_master where BINARY regn_number='$regId'");
            $count = mysqli_fetch_array($count);
            if ($count[0] < 1) {
                echo -1;    //there is no participant present with that id
                mysqli_close($con);
                return;
            } else {
                $partList = array();
                $query = "select * from participant_master where regn_number='$regId'";
                $result = mysqli_query($con, $query);
                $rs = mysqli_fetch_array($result);
                $sId = $rs['school_id'];

                $query = "select school_name from school_master where school_id='$sId'";
                $result2 = mysqli_query($con, $query);
                $rs2 = mysqli_fetch_array($result2);
                $schoolName = $rs2['school_name'];

                $query = "select * from event_trans where event_id='$eid' and regn_number='$regId'";
                $result3 = mysqli_query($con, $query);
                $rs3 = mysqli_fetch_array($result3);
                $grade = $rs3['event_grade'];
                $score = $rs3['event_marks'];

                $query = "select * from event_result where event_id='$eid' and regn_number='$regId'";
                $result4 = mysqli_query($con, $query);
                $rs4 = mysqli_fetch_array($result4);
                $position = $rs4['position'];

                array_push($partList, array("student_name" => $rs['student_name']
                    , "school_name" => $schoolName, "score" => $score, "grade" => $grade, "position" => $position));
                echo array_to_json($partList);
                mysqli_close($con);
                return;
            }
        } else if (strcmp($insertType, "getpartDetailsForEdit") == 0) {
            $pId = $_POST["pId"];
            $count = mysqli_query($con, "select count(*) FROM `participant_master` WHERE regn_number=$pId");
            $count = mysqli_fetch_array($count);
            if ($count[0] < 1) {
                echo -1;    //there is no participant present with that id
                return;
            } else {
                $evList = array();
                $query = "SELECT * FROM `participant_master` WHERE regn_number=$pId";
                $result = mysqli_query($con, $query);
                $rs = mysqli_fetch_array($result);

                $query = "SELECT * FROM `event_trans` WHERE regn_number=$pId";
                $result2 = mysqli_query($con, $query);
                $feePaid = 0;
                while ($rs2 = mysqli_fetch_array($result2)) {
                    array_push($evList, $rs2['event_id']);
                    $feePaid = $rs2['fee_paid'];
                }
                $school_id = $rs['school_id'];
                $query = "SELECT school_name FROM `school_master` WHERE school_id=$school_id";
                $result3 = mysqli_query($con, $query);
                $rs3 = mysqli_fetch_array($result3);
                $school_name = $rs3['school_name'];

                $partList = array('dob' => $rs['dob'], 'student_name' => $rs['student_name'], 'sex' => $rs['sex'], 'parent_name' => $rs['parent_name'],
                    'st_adress' => $rs['st_adress'], 'mail_id' => $rs['pa_mail_id'], 'phone_number' => $rs['pa_phone_number'],
                    'school_name' => $school_name, 'events' => $evList, 'school_id' => $school_id, 'fee_paid' => $feePaid, 'part_id' => $pId);
                echo array_to_json($partList);
                mysqli_close($con);
                return;
            }
        } else if (strcmp($insertType, "getEventsResultEntered") == 0) {
            $pId = $_POST["pId"];
            $count = mysqli_query($con, "select count(*) FROM `event_result` WHERE regn_number=$pId");
            $count = mysqli_fetch_array($count);
            if ($count[0] < 1) {
                echo -1;    //there is no participant present with that id
                mysqli_close($con);
                return;
            } else {
                $evList = array();
                $query = "SELECT event_id FROM `event_result` WHERE regn_number=$pId";
                $result = mysqli_query($con, $query);
                while ($rs = mysqli_fetch_array($result)) {
                    array_push($evList, $rs["event_id"]);
                }
                $returnVal = array('evts' => $evList);
                echo array_to_json($returnVal);
                mysqli_close($con);
            }
        } else if (strcmp($insertType, "getGroupParticipant") == 0) {
            $regId = $_POST["regId"];
            $regId = mysqli_real_escape_string($con, $regId);
            $eid = $_POST["eid"];
            $count = mysqli_query($con, "select count(*) from group_master where BINARY group_id='$regId' and event_id='$eid'");
            $count = mysqli_fetch_array($count);
            if ($count[0] < 1) {
                echo -1;    //there is no participant present with that id
                mysqli_close($con);
                return;
            } else {
                $partList = array();
                $query = "select * from group_trans where group_id='$regId' and event_id='$eid'";
                $result = mysqli_query($con, $query);
                $partName = "";
                $sId = 0;
                while ($rs = mysqli_fetch_array($result)) {
                    $rId = $rs["regn_number"];
                    $query = "select student_name,school_id from participant_master where regn_number='$rId'";
                    $result1 = mysqli_query($con, $query);
                    $rs1 = mysqli_fetch_array($result1);
                    $sId = $rs1['school_id'];
                    $partName = $partName . $rs1["student_name"] . ',';
                }
                if ($sId > 0) {
                    $query = "select school_name from school_master where school_id='$sId'";
                    $result2 = mysqli_query($con, $query);
                    $rs2 = mysqli_fetch_array($result2);
                    $schoolName = $rs2['school_name'];

                    $query = "select * from group_result where event_id='$eid' and group_id='$regId'";
                    $result3 = mysqli_query($con, $query);
                    $rs3 = mysqli_fetch_array($result3);
                    $grade = $rs3['grade'];
                    $score = $rs3['marks'];
                    $position = $rs3['result'];


                    array_push($partList, array("student_name" => $partName
                        , "school_name" => $schoolName, "score" => $score, "grade" => $grade, "position" => $position));
                    echo array_to_json($partList);
                }
                mysqli_close($con);
                return;
            }
        }
    }
}
?>
