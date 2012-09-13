<?php

include_once "../include.php";
$insertType = $_POST["type"];
$con = mysqli_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
} else {
    $db_selected = mysqli_select_db($con, 'BALOLSAV');
    if ($db_selected) {
        if (strcmp($insertType, "getAll") == 0) {
            //KalaThilakom -> Female with max points
            //KalaPrathibha -> Male with max points
            $query = "SELECT SUM( event_marks ) AS points, regn_number AS reg
                FROM event_trans WHERE regn_number IN ( SELECT regn_number FROM
                `participant_master`  WHERE sex =1 ) GROUP BY regn_number
                ORDER BY points DESC  LIMIT 0 , 1";
            $rs = mysqli_query($con, $query);
            $result = mysqli_fetch_array($rs);
            $prathibhaPoints = $result["points"];
            $prathibhaRegNo = $result["reg"];

            $query = "SELECT * from `participant_master` where regn_number = " . $prathibhaRegNo;
            $rs = mysqli_query($con, $query);
            $result = mysqli_fetch_array($rs);
            $prathibhaName = $result["student_name"];
            $prathibhaschool_id = $result["school_id"];

            $query = "SELECT school_name from `school_master` where school_id = " . $prathibhaschool_id;
            $rs = mysqli_query($con, $query);
            $result = mysqli_fetch_array($rs);
            $prathibhaSchoolName = $result["school_name"];

            $prathibhaData = array("Name" => $prathibhaName, "regn_number" => $prathibhaRegNo, "points" => $prathibhaPoints, "school_name" => $prathibhaSchoolName);


            $query = "SELECT SUM( event_marks ) AS points, regn_number AS reg
                FROM event_trans WHERE regn_number IN ( SELECT regn_number FROM
                `participant_master`  WHERE sex =2 ) GROUP BY regn_number
                ORDER BY points DESC  LIMIT 0 , 1";
            $rs = mysqli_query($con, $query);
            $result = mysqli_fetch_array($rs);
            $thilakomPoints = $result["points"];
            $thilakomRegNo = $result["reg"];

            $query = "SELECT * from `participant_master` where regn_number = " . $thilakomRegNo;
            $rs = mysqli_query($con, $query);
            $result = mysqli_fetch_array($rs);
            $thilakomName = $result["student_name"];
            $thilakomschool_id = $result["school_id"];

            $query = "SELECT school_name from `school_master` where school_id = " . $thilakomschool_id;
            $rs = mysqli_query($con, $query);
            $result = mysqli_fetch_array($rs);
            $thilakomSchoolName = $result["school_name"];

            $thilakomData = array("Name" => $thilakomName, "regn_number" => $thilakomRegNo, "points" => $thilakomPoints, "school_name" => $thilakomSchoolName);


            $query = "SELECT * from `participant_master` where 1 ";
            $rs = mysqli_query($con, $query);
            $result = mysqli_fetch_array($rs);

            $partArray[] = array();
            $counter = 0;
            while ($result = mysqli_fetch_array($rs)) {
                $regn_number = $result["regn_number"];
                $name = $result["student_name"];
                $school_id = $result["school_id"];

                $query = "SELECT school_name from `school_master` where school_id = " . $thilakomschool_id;
                $rs1 = mysqli_query($con, $query);
                $result1 = mysqli_fetch_array($rs1);
                $school_name = $result1["school_name"];

                $query = "SELECT sum(event_marks) as points from `event_trans` where regn_number = " . $regn_number;
                $rs1 = mysqli_query($con, $query);
                $result1 = mysqli_fetch_array($rs1);
                $points = $result1["points"];
                if ($points == null)
                    $points = 0;
                $partArray[$counter] = array("Name" => $name, "regn_number" => $regn_number, "points" => $points, "school_name" => $school_name);
                $counter = $counter + 1;
            }

            if ($thilakomRegNo != null && $prathibhaRegNo != null && $counter > 0) {
                $returnJson = array("thilakom" => $thilakomData, "prathibha" => $prathibhaData, "participants" => $partArray);
                echo array_to_json($returnJson);
            } else if ($counter > 0) {
                $returnJson = array("participants" => $partArray);
                echo array_to_json($returnJson);
            } else {
                return 0;
            }
        }
    }
}
?>
