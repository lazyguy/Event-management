<?php

include_once "../include.php";
require('../fpdf/pdf_js.php');

class PDF extends PDF_JavaScript {

    function AutoPrint($dialog = false) {
        //Open the print dialog or start printing immediately on the standard printer
        $param = ($dialog ? 'true' : 'false');
        $script = "print($param);";
        $this->IncludeJS($script);
    }

    function FancyTable($data) {
        //y,x where printing has to be done for winners certificate
        $nameLoc = array(110, 130);
        $schoolLoc = array(123, 40);
        $positionLoc = array(123, 160);
        $eventLoc = array(123, 205);

        $partcertificateX = 30;
        $partcertificateY = 150;

        $this->AddFont("BLKCHCRY");
        $this->SetFont('BLKCHCRY', '', 18);
        $this->SetTextColor(220, 20, 60);
        for ($counter = 0; $counter < sizeof($data); $counter++) {
            $this->AddPage();
            $name = $data[$counter]["name"];
            $school_name = $data[$counter]["school_name"];
            $event_name = $data[$counter]["event_name"];
            if ($_GET["winner"] == 1) {
                $position = $data[$counter]["position"];
                switch (intval($position)) {
                    case 1:
                        $positionName = "First";
                        break;
                    case 2:
                        $positionName = "Second";
                        break;
                    case 3:
                        $positionName = "Third";
                        break;
                }

                $this->SetXY($nameLoc[1], $nameLoc[0]);
                $this->Cell(0, 10, ucfirst($name), 0, 0);

                $this->SetXY($schoolLoc[1], $schoolLoc[0]);
                $this->Cell(0, 10, ucfirst($school_name), 0, 0);

                $this->SetXY($positionLoc[1], $positionLoc[0]);
                $this->Cell(0, 10, ucfirst($positionName), 0, 0);

                $this->SetXY($eventLoc[1], $eventLoc[0]);
                $this->Cell(0, 10, ucfirst($event_name), 0, 0);
            } else {
                $grade = $data[$counter]["grade"];

                $this->SetXY($partcertificateX, $partcertificateY);
                $this->SetTextColor(220, 20, 60);
                $this->SetFont('Arial', '', 18);
                $this->Cell(40, 10, "Name :    ", 0, 0, "R");
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('BLKCHCRY', '', 18);
                $this->Cell(0, 10, ucfirst($name), 0, 1);

                $this->SetX($partcertificateX);
                $this->SetTextColor(220, 20, 60);
                $this->SetFont('Arial', '', 18);
                $this->Cell(40, 10, "School Name :    ", 0, 0, "R");
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('BLKCHCRY', '', 18);
                $this->Cell(0, 10, ucfirst($school_name), 0, 1);

                $this->SetX($partcertificateX);
                $this->SetTextColor(220, 20, 60);
                $this->SetFont('Arial', '', 18);
                $this->Cell(40, 10, "Event Name :    ", 0, 0, "R");
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('BLKCHCRY', '', 18);
                $this->Cell(0, 10, ucfirst($event_name), 0, 1);

                $this->SetX($partcertificateX);
                $this->SetTextColor(220, 20, 60);
                $this->SetFont('Arial', '', 18);
                $this->Cell(40, 10, "Grade :    ", 0, 0, "R");
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('BLKCHCRY', '', 18);
                $this->Cell(0, 10, ucfirst($grade), 0, 1);
            }
        }
    }

}

$con = mysql_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysql_error($con));
} else {
    $eId = $_GET["eId"];
    $winner = $_GET["winner"];
    $db_selected = mysql_select_db('BALOLSAV', $con);
    if ($db_selected) {
        $d = getData($con);
        if ($winner == 1)
            $pdf = new PDF('L', 'mm', 'A4');
        else
            $pdf = new PDF('P', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->FancyTable($d);
        $pdf->AutoPrint(true);
        $pdf->Output();
    }
}

function getData($con) {
    $eId = $_GET["eId"];
    $winner = $_GET["winner"];

    $isGrp = 0;
    //check if event is a group event or not
    $query = "select * from event_master where event_id = $eId";

    $rs = mysql_query($query);
    $result = mysql_fetch_array($rs);
    if ($result["isgroup"] == 1)
        $isGrp = 1;
    else
        $isGrp = 0;
    //need to get regn number, name, school name, sex, age
    if ($isGrp == 1)
        $query = "select * from group_master where event_id = $eId";
    else
        $query = "select * from event_trans where event_id = $eId";

    $rs = mysql_query($query);

    $partArray[] = array();
    $winnerArray[] = array();
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
    while ($result = mysql_fetch_array($rs)) {

        if ($isGrp == 1) {
            $group_id = $result["group_id"];

            $query = "select regn_number from group_trans where group_id =$group_id and  event_id = $eId";
            $rs1 = mysql_query($query);
            $result1 = mysql_fetch_array($rs1);
            $regn_number = $result1["regn_number"];
            $query = "select * from participant_master where regn_number = $regn_number";
            $rs1 = mysql_query($query);
            if (!$rs1) {
                continue;
            }
            $result1 = mysql_fetch_array($rs1);

            $name = $result1["student_name"];
            $sex = $result1["sex"];
            $sex = getSex($sex);
            $age = $result1["age"];
            $school_id = $result1["school_id"];
        } else {
            $regn_number = $result["regn_number"];
            $query = "select * from participant_master where regn_number = $regn_number";
            $rs1 = mysql_query($query);
            $result1 = mysql_fetch_array($rs1);

            $name = $result1["student_name"];
            $sex = $result1["sex"];
            $sex = getSex($sex);
            $age = $result1["age"];
            $school_id = $result1["school_id"];
        }
        $query = "select school_name from school_master where school_id = $school_id";
        $rs1 = mysql_query($query);
        if ($school_id <= 0)
            continue;
        $result1 = mysql_fetch_array($rs1);

        $school_name = $result1["school_name"];
        if ($isGrp == 1)
            $query = "select * from group_result where event_id = $eId and group_id = $group_id";
        else
            $query = "select position from event_result where event_id = $eId and regn_number = $regn_number";
        $rs1 = mysql_query($query);
        $result1 = mysql_fetch_array($rs1);

        if ($isGrp == 1)
            $position = $result1["result"];
        else
            $position = $result1["position"];
        if ($isGrp == 1) {
            $name_array = array();
            $query = "select * from group_trans where group_id='$group_id' and event_id='$eId'";
            $result11 = mysql_query($query);
            $partName = "";
            $student_name = "";
            $count = 0;
            while ($rs11 = mysql_fetch_array($result11)) {
                $rId = $rs11["regn_number"];
                $query = "select student_name,school_id from participant_master where regn_number='$rId'";
                $result2 = mysql_query($query);
                $rs2 = mysql_fetch_array($result2);
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
        $rs2 = mysql_query($query);
        $result1 = mysql_fetch_array($rs2);

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
    if ($winner == 1 && sizeof($winnerArray) > 0) {
        return $winnerArray;
        // return array_to_json($returnJson);
    } elseif ($winner == 0 && sizeof($partArray) > 0) {
        return $partArray;
    } else {
        echo "0";
    }
}

?>
