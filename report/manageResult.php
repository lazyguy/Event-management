<?php

include_once "include.php";
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
        }
    }
}
?>
