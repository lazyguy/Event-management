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
    $query = "select * from event_trans where event_id = $eId";

    $rs = mysql_query($query);

    $partArray[] = array();
    $winnerArray[] = array();
    $counter = 0;
    $winnercounter = 0;
    while ($result = mysql_fetch_array($rs)) {
        $regn_number = $result[0];

        $query = "select * from participant_master where regn_number = $regn_number";
        $rs1 = mysql_query($query);
        $result1 = mysql_fetch_array($rs1);

        $name = $result1["student_name"];
        $sex = $result1["sex"];
        $sex = getSex($sex);
        $age = $result1["age"];
        $school_id = $result1["school_id"];

        $query = "select school_name from school_master where school_id = $school_id";
        $rs1 = mysql_query($query);
        $result1 = mysql_fetch_array($rs1);

        $school_name = $result1["school_name"];

        $query = "select position from event_result where event_id = $eId and regn_number = $regn_number";
        $rs1 = mysql_query($query);
        $result1 = mysql_fetch_array($rs1);

        $position = $result1["position"];
        // $gradePoint = $result["event_grade"] . "/" . $result["event_marks"];

        $query = "select event_name from event_master where event_id = $eId";
        $rs2 = mysql_query($query);
        $result1 = mysql_fetch_array($rs2);

        $event_name = $result1["event_name"];

        if ($result["event_grade"] != null && intval($result["event_marks"]) != 0) {
            if (intval($position) == 1 || intval($position) == 2 || intval($position) == 3) {
                $winnerArray[$winnercounter] = array("event_name" => $event_name, "rNum" => $regn_number, "name" => $name, "sex" => $sex, "position" => $position, "school_name" => $school_name);
                $winnercounter = $winnercounter + 1;
            } else {
                $partArray[$counter] = array("event_name" => $event_name, "rNum" => $regn_number, "name" => $name, "sex" => $sex, "grade" => $result["event_grade"], "school_name" => $school_name);
                $counter = $counter + 1;
            }
        }
    }
    if ($winner == 1 && $winnercounter > 0) {
        return $winnerArray;
        // return array_to_json($returnJson);
    } elseif ($winner == 0 && $counter > 0) {
        return $partArray;
    } else {
        echo "0";
    }
}

?>
