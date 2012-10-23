<?php

include_once "../include.php";
require('../fpdf/pdf_js.php');


$titleg = "Summary";
$width = array();
$headerg = array();

class PDF extends PDF_JavaScript {

    function AutoPrint($dialog = false) {
        //Open the print dialog or start printing immediately on the standard printer
        $param = ($dialog ? 'true' : 'false');
        $script = "print($param);";
        $this->IncludeJS($script);
    }

    function Header() {
        $title = $GLOBALS["titleg"];
        //$title = "Rotary Balolsav " . getYear();
        $header = $GLOBALS["headerg"];
        $w = $GLOBALS["width"];
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(120);
        $this->Cell(25, 10, $title, 0, 0, 'C');
        $this->Ln(20);
        $this->SetFont('Arial', 'B', 14);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', false);
        $this->Ln();
    }

    function Footer() {
        $foot = $GLOBALS["foot"];
        $w = $GLOBALS["width"];
        //  if ($GLOBALS["type"] != 1)
        $this->Cell(array_sum($w), 0, '', 'T');
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        $this->SetX(-50);
        // Select Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Print current and total page numbers
        $this->Cell(0, 10, $foot . '-' . 'Page ' . $this->PageNo() . '/{nb}', 0, 0);
    }

    function FancyTable($data, $type, $sortby) {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        //  $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('Arial', 'B', 14);

        // Color and font restoration
        $this->SetFillColor(220, 220, 220);
        $this->SetTextColor(0);
        $this->SetFont('');

        $w = $GLOBALS["width"];
        $fill = false;

        $eId = $_GET["eId"];
        if (isset($_GET["printall"]))
            $printall = $_GET["printall"];
        else
            $printall = 0;


        if ($eId == 999999 && $printall != 0) {
            for ($counter = 0; $counter < sizeof($data[1]); $counter++) {
                $row = $data[1][$counter];
                $this->SetFont('Arial', '', 14);
                $this->Cell($w[0], 9, number_format($row["school_id"]), 'LR', 0, 'L', $fill);
                $this->Cell($w[1], 9, $row["school_name"], 'LR', 0, 'L', $fill);
                $this->Cell($w[2], 9, number_format($row["marks_sum"]), 'LR', 0, 'L', $fill);
                $this->Ln();
                $fill = !$fill;
            }
        } else {
            for ($counter = 0; $counter < sizeof($data[0]); $counter++) {
                $row = $data[0][$counter];
                $this->SetFont('Arial', '', 14);
                $this->Cell($w[0], 9, number_format($row["regn_number"]), 'LR', 0, 'L', $fill);
                $this->Cell($w[1], 9, $row["name"], 'LR', 0, 'L', $fill);
                $this->Cell($w[2], 9, $row["school_name"], 'LR', 0, 'L', $fill);
                $this->Cell($w[3], 9, $row["event_name"], 'LR', 0, 'L', $fill);
                $this->Cell($w[4], 9, number_format($row["position"]), 'LR', 0, 'L', $fill);
                $this->Ln();
                $fill = !$fill;
            }
        }
    }

}

$con = mysql_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysql_error($con));
} else {
    $eId = $_GET["eId"];
    if (isset($_GET["printall"]))
        $printall = $_GET["printall"];
    else
        $printall = 0;

    $db_selected = mysql_select_db('BALOLSAV', $con);
    if ($db_selected) {
        $d = getData($con);

        $pdf = new PDF('L', 'mm', 'A4');
        $pdf->AliasNbPages();
        // $pdf->SetMargins(2, 5, 2);

        if ($eId == 999999 && $printall != 0) {
            $GLOBALS["foot"] = "Points By School";
            $GLOBALS["headerg"] = array('School Id', 'School Name', 'Total Score');
            $GLOBALS["titleg"] = "Rotary Balolsav " . getYear() . " - Points By School";
            $GLOBALS["width"] = array(30, 160, 35);
            $pdf->AddPage();
            $pdf->FancyTable(array_values($d), 2, -1);
        } else {
            $GLOBALS["foot"] = "Winners By School";
            $GLOBALS["headerg"] = array('Reg No', 'Name', 'School Name', 'Event Name', 'Position');
            $GLOBALS["titleg"] = "Rotary Balolsav " . getYear() . " - Winners By School";
            $GLOBALS["width"] = array(20, 60, 110, 60, 20);
            $pdf->AddPage();
            $pdf->FancyTable(array_values($d), 2, -1);
        }
        $pdf->AutoPrint(true);
        $pdf->Output();
    }
}

function getData($con) {
    $eId = $_GET["eId"];
    $counter = 0;
    if ($eId == 999999) {
        $query = "select * from event_result where 1 order by regn_number";
    } else {
        $query = "select * from event_result where regn_number  in
                     (select regn_number from event_trans where regn_number in
                     (select regn_number from participant_master where school_id=$eId))";
    }
    $rs = mysql_query($query);
    $returnArray[] = array();
    while ($result = mysql_fetch_array($rs)) {
        //need to return regn number,name,school name, event name, position
        $event_id = $result["event_id"];
        $regn_number = $result["regn_number"];
        $position = $result["position"];

        $query = "select student_name,school_id from participant_master where regn_number = $regn_number";
        $rs1 = mysql_query($query);
        echo mysql_error($con);
        $result1 = mysql_fetch_array($rs1);
        $school_id = $result1["school_id"];
        $student_name = $result1["student_name"];

        $query = "select school_name from school_master where school_id = $school_id";
        $rs1 = mysql_query($query);
        $result1 = mysql_fetch_array($rs1);
        $school_name = $result1["school_name"];

        $query = "select event_name from event_master where event_id = $event_id";
        $rs1 = mysql_query($query);
        $result1 = mysql_fetch_array($rs1);
        $event_name = $result1["event_name"];


        $returnArray[$counter] = array("regn_number" => $regn_number,
            "name" => $student_name, "school_name" => $school_name,
            "event_name" => $event_name, "position" => $position);
        $counter = $counter + 1;
    }

    //Group events
    if ($eId == 999999) {
        $query = "select * from group_result where 1 order by group_id";
    } else {
        $query = "select * from group_result where group_id  in
                     (select group_id from group_master where school_id =$eId)";
    }
    $rs = mysql_query($query);
    while ($result = mysql_fetch_array($rs)) {
        //need to return regn number,name,school name, event name, position
        $event_id = $result["event_id"];
        $regn_number = $result["group_id"];
        $position = $result["result"];

        $query = "select * from group_trans where group_id='$regn_number' and event_id='$event_id'";
        $result1 = mysql_query($query);
        $partName = "";
        $student_name = "";
        while ($rs1 = mysql_fetch_array($result1)) {
            $rId = $rs1["regn_number"];
            $query = "select student_name,school_id from participant_master where regn_number='$rId'";
            $result2 = mysql_query($query);
            $rs1 = mysql_fetch_array($result2);
            $sId = $rs1['school_id'];
            $student_name = $student_name . $rs1["student_name"] . ',';
        }

        $query = "select school_name from school_master where school_id = $sId";
        $rs1 = mysql_query($query);
        $result1 = mysql_fetch_array($rs1);
        $school_name = $result1["school_name"];

        $query = "select event_name from event_master where event_id = $event_id";
        $rs1 = mysql_query($query);
        $result1 = mysql_fetch_array($rs1);
        $event_name = $result1["event_name"];

        $returnArray[$counter] = array("regn_number" => $regn_number,
            "name" => $student_name, "school_name" => $school_name,
            "event_name" => $event_name, "position" => $position);
        $counter = $counter + 1;
    }

    $returnSumArray[] = array();
    $query = "select * from school_master where 1";
    $rs = mysql_query($query);
    $counter1 = 0;
    while ($result = mysql_fetch_array($rs)) {
        //need to return regn number,name,school name, event name, position
        $school_id = $result["school_id"];
        $school_name = $result["school_name"];

        $query = "select sum(event_marks) as marks_sum from event_trans where regn_number in (select regn_number from participant_master where school_id = $school_id)";
        $rs1 = mysql_query($query);
        $result1 = mysql_fetch_array($rs1);
        $marks_sum = $result1["marks_sum"];
        if ($marks_sum == null)
            $marks_sum = 0;

        $query = "select sum(marks) as marks_sum from group_result where group_id in (select group_id from group_master where school_id = $school_id)";
        $rs1 = mysql_query($query);
        $result1 = mysql_fetch_array($rs1);
        $marks_sum = $marks_sum + $result1["marks_sum"];
        if ($marks_sum == null)
            $marks_sum = 0;

        $returnSumArray[$counter1] = array("school_id" => $school_id,
            "school_name" => $school_name, "marks_sum" => $marks_sum);
        $counter1 = $counter1 + 1;
    }

    if ($counter > 0 && $counter1 > 0) {
        $returnJson = array("participants" => $returnArray, "sum" => $returnSumArray);
    } else if ($counter > 0) {
        $returnJson = array("participants" => $returnArray);
    } else if ($counter1 > 0) {
        $returnJson = array("sum" => $returnSumArray);
    } else {
        return "0";
        mysql_close($con);
        return;
    }
    return $returnJson;
}

?>
