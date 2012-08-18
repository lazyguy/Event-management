<?php

include_once "../include.php";
$insertType = $_POST["type"];
$con = mysqli_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
} else {
    $db_selected = mysqli_select_db($con, 'BALOLSAV');
    if ($db_selected) {
        if (strcmp($insertType, "addResult") == 0) {
            $eid = $_POST["eid"];
            $firstregId = $_POST["firstregId"];
            $firstregId = mysqli_real_escape_string($con, $firstregId);
            $secondregId = $_POST["secondregId"];
            $secondregId = mysqli_real_escape_string($con, $secondregId);
            $thirdregId = $_POST["thirdregId"];
            $thirdregId = mysqli_real_escape_string($con, $thirdregId);
            mysqli_autocommit($con, FALSE);
            $query = "DELETE from event_result where event_id='$eid'";
            $result = mysqli_query($con, $query);
            $query = "INSERT INTO event_result VALUES ('$firstregId','$eid',1)";
            if ($result === FALSE) {
                mysqli_rollback($con);  // if error, roll back transaction
                echo 0;
                mysqli_close($con);
                return;
            }
            $result = mysqli_query($con, $query);
            if ($result === FALSE) {
                mysqli_rollback($con);  // if error, roll back transaction
                echo 0;
                mysqli_close($con);
                return;
            }
            $query = "INSERT INTO event_result VALUES ('$secondregId','$eid',2)";
            $result = mysqli_query($con, $query);
            if ($result === FALSE) {
                mysqli_rollback($con);  // if error, roll back transaction
                echo 0;
                mysqli_close($con);
                return;
            }
            $query = "INSERT INTO event_result VALUES ('$thirdregId','$eid',3)";
            $result = mysqli_query($con, $query);
            if ($result === FALSE) {
                mysqli_rollback($con);  // if error, roll back transaction
                echo 0;
                mysqli_close($con);
                return;
            }
            mysqli_commit($con);
            echo "1";
        } else if (strcmp($insertType, "addResult2") == 0) {
            $eid = $_POST["eid"];
            $regId = $_POST["regId"];
            $regId = mysqli_real_escape_string($con, $regId);
            $score = $_POST["score"];
            $score = mysqli_real_escape_string($con, $score);
            $grade = $_POST["grade"];
            $grade = mysqli_real_escape_string($con, $grade);
            $position = $_POST["position"];
            $position = mysqli_real_escape_string($con, $position);
            mysqli_autocommit($con, FALSE);
            //Delete previous entry if exists
            $query = "DELETE from event_result where event_id='$eid' and position='$position'";
            $result = mysqli_query($con, $query);
            if ($result === FALSE) {
                mysqli_rollback($con);  // if error, roll back transaction
                echo 0;
                mysqli_close($con);
                return;
            }
            $query = "INSERT INTO event_result VALUES ('$firstregId','$eid',1)";
            $result = mysqli_query($con, $query);
            if ($result === FALSE) {
                mysqli_rollback($con);  // if error, roll back transaction
                echo 0;
                mysqli_close($con);
                return;
            }
            $query = "UPDATE `event_trans` SET event_marks='1', event_grade='A' where event_id='$eid' and regn_number='$regId'";
            $result = mysqli_query($con, $query);
            if ($result === FALSE) {
                mysqli_rollback($con);  // if error, roll back transaction
                echo 0;
                mysqli_close($con);
                return;
            }
            mysqli_commit($con);
            echo "1";
        }
    } else {
        echo "-1";
    }
}
?>
