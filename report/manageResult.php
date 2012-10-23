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
            $query = "SELECT count(*) from event_trans where event_id='$eid' and regn_number='$regId'";
            $result = mysqli_query($con, $query);
            $result = mysqli_fetch_array($result);
            if ($result[0] < 1) {
                echo -4;
                mysqli_close($con);
                return;
            }

            mysqli_autocommit($con, FALSE);
            //Delete previous entry if exists
            $query = "DELETE from event_result where event_id='$eid' and regn_number='$regId'";
            $result = mysqli_query($con, $query);
            if ($result === FALSE) {
                mysqli_rollback($con);  // if error, roll back transaction
                echo -1;
                mysqli_close($con);
                return;
            }
            if ($position > 0) {
                $query = "INSERT INTO event_result VALUES ('$regId','$eid','$position')";
                $result = mysqli_query($con, $query);
                if ($result === FALSE) {
                    mysqli_rollback($con);  // if error, roll back transaction
                    echo -2;
                    mysqli_close($con);
                    return;
                }
            }
            $query = "UPDATE `event_trans` SET event_marks='$score', event_grade='$grade' where event_id='$eid' and regn_number='$regId'";
            $result = mysqli_query($con, $query);
            if ($result === FALSE) {
                mysqli_rollback($con);  // if error, roll back transaction
                echo -3;
                mysqli_close($con);
                return;
            }
            mysqli_commit($con);
            echo "1";
        } else if (strcmp($insertType, "addGroupResult") == 0) {
            $eid = $_POST["eid"];
            $regId = $_POST["regId"];
            $regId = mysqli_real_escape_string($con, $regId);
            $score = $_POST["score"];
            $score = mysqli_real_escape_string($con, $score);
            $grade = $_POST["grade"];
            $grade = mysqli_real_escape_string($con, $grade);
            $position = $_POST["position"];
            $position = mysqli_real_escape_string($con, $position);
            $query = "SELECT count(*) from group_trans where event_id='$eid' and group_id='$regId'";
            $result = mysqli_query($con, $query);
            $result = mysqli_fetch_array($result);
            if ($result[0] < 1) {
                echo mysqli_error($con);
//                echo "evid =".$event_id;
//                echo "group_id =".$regId;
                echo -4;
                mysqli_close($con);
                return;
            }

            mysqli_autocommit($con, FALSE);
            //Delete previous entry if exists
            $query = "DELETE from group_result where event_id='$eid' and group_id='$regId'";
            $result = mysqli_query($con, $query);
            if ($result === FALSE) {
                mysqli_rollback($con);  // if error, roll back transaction
                echo -1;
                mysqli_close($con);
                return;
            }

            $query = "INSERT INTO group_result VALUES ('$regId','$eid','$grade','$score','$position')";
            $result = mysqli_query($con, $query);
            if ($result === FALSE) {
                mysqli_rollback($con);  // if error, roll back transaction
                echo -2;
                mysqli_close($con);
                return;
            }

            /*
              $query = "UPDATE `event_trans` SET event_marks='$score', event_grade='$grade' where event_id='$eid' and regn_number='$regId'";
              $result = mysqli_query($con, $query);
              if ($result === FALSE) {
              mysqli_rollback($con);  // if error, roll back transaction
              echo -3;
              mysqli_close($con);
              return;
              }
             */
            mysqli_commit($con);
            echo "1";
        }
    } else {
        echo "-1";
    }
}
?>
