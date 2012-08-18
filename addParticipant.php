<?php

include_once "include.php";
$insertType = $_POST["type"];
$con = mysqli_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
} else {
    $db_selected = mysqli_select_db($con, 'BALOLSAV');
    if ($db_selected) {
        if (strcmp($insertType, "addParticipant") == 0) {
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

            $count = mysqli_query($con, "SELECT COUNT(*) FROM participant_master WHERE
                     student_name='$participantName' and dob=STR_TO_DATE('$DOB', '%m/%d/%Y') and school_id='$partSId'");
            $count = mysqli_fetch_array($count);
            if ($count[0] < 1) {
                $rsd = mysqli_query($con, "SELECT MAX( regn_number ) as nextid FROM participant_master where 1");
                $rs = mysqli_fetch_array($rsd);
                $regn_number = $rs['nextid'] + 1;   //get next allowed id;
                if ($regn_number == 1) {  //start the reg id from 200
                    $regn_number = 200;
                }
                mysqli_autocommit($con, FALSE);
                $query = "INSERT INTO participant_master (regn_number,
                    student_name,age,dob,sex,school_id,parent_name,st_adress,
                    pa_mail_id,pa_phone_number) VALUES ('$regn_number',
                    '$participantName','$age',STR_TO_DATE('$DOB', '%m/%d/%Y'),'$SEX','$partSId',
                    '$partParentName','$partAddress','$partMailid','$partPhNum')";
                $result = mysqli_query($con, $query);

                if ($result === FALSE) {
                    mysqli_rollback($con);  // if error, roll back transaction
                    echo 0;
                    mysqli_close($con);
                    return;
                }
                //now insert all items into the items table with student id.
                //      $partId = mysqli_insert_id();
                for ($index = 0; $index < count($partItems); $index++) {
                    $partItemsEscaped = mysqli_real_escape_string($con, $partItems[$index]);
                    $result = mysqli_query($con, "INSERT INTO event_trans VALUES
                            ('$regn_number','$partItemsEscaped', '$partFeePaid')");
                    if ($result !== TRUE) {
                        mysqli_rollback($con);  // if error, roll back transaction
                        echo 0;
                        mysqli_close($con);
                        return;
                    }
                }
                mysqli_commit($con);
                echo "1";
            } else {
                echo "2"; //participant already exists
            }
            mysqli_close($con);
        } else if (strcmp($insertType, "getParticipant") == 0) {
            $regId = $_POST["regId"];
            $regId = mysqli_real_escape_string($con, $regId);

            $count = mysqli_query($con, "select count(*) from participant_master where BINARY regn_number='$regId'");
            $count = mysqli_fetch_array($count);
            if ($count[0] < 1) {
                echo -1;    //there is no participant present with that id
                return;
            } else {
                $query = "select * from participant_master where regn_number='$regId'";
                $result = mysqli_query($con, $query);
                $partList = array();
                $rs = mysqli_fetch_array($result);
                $sId = $rs['school_id'];
                $query = "select school_name from school_master where school_id='$sId'";
                $result2 = mysqli_query($con, $query);
                $rs2 = mysqli_fetch_array($result2);
                $schoolName = $rs2['school_name'];
                array_push($partList, array("student_name" => $rs['student_name'], "school_name" => $schoolName));
                echo array_to_json($partList);
                return;
            }
        }
    }
}
?>
