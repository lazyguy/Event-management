<?php

require('../fpdf/pdf_js.php');
include_once "../include.php";

class PDF_AutoPrint extends PDF_JavaScript {

    function AutoPrint($dialog = false) {
        //Open the print dialog or start printing immediately on the standard printer
        $param = ($dialog ? 'true' : 'false');
        $script = "print($param);";
        $this->IncludeJS($script);
    }

    function AutoPrintToPrinter($server, $printer, $dialog = false) {
        //Print on a shared printer (requires at least Acrobat 6)
        $script = "var pp = getPrintParams();";
        if ($dialog)
            $script .= "pp.interactive = pp.constants.interactionLevel.full;";
        else
            $script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
        $script .= "pp.printerName = '\\\\\\\\" . $server . "\\\\" . $printer . "';";
        $script .= "print(pp);";
        $this->IncludeJS($script);
    }

}

$con = mysqli_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
} else {
    $db_selected = mysqli_select_db($con, 'BALOLSAV');
    if ($db_selected) {
        $pdf = new PDF_AutoPrint('P', 'mm', array(115, 153));
        $pdf->AliasNbPages();
        $category = $_GET["printwhat"];
        $sid = $_GET["sid"];
        $eid = $_GET["eid"];
        $count = mysqli_query($con, "SELECT regn_number,school_id FROM `participant_master` WHERE category = $category and regn_number between $sid and $eid order by regn_number,school_id");
        if (!$count) {
            echo mysqli_error($con);
        }
       
        
        while ($row = mysqli_fetch_array($count)) {
            printregcard($row["regn_number"], $con,$pdf);
        }
        $pdf->AutoPrint(true);
        $pdf->Output();
    }
}

function printregcard($sid, $con,$pdf) {

    $pId = $sid;

    $count = mysqli_query($con, "select count(*) FROM `participant_master` WHERE regn_number=$pId");
    $count = mysqli_fetch_array($count);
    if ($count[0] < 1) {
        //    echo -1;    //there is no participant present with that id
        return;
    } else {
        $evList = array();
        $query = "SELECT * FROM `participant_master` WHERE regn_number=$pId";
        $result = mysqli_query($con, $query);
        $rs = mysqli_fetch_array($result);

        $query = "SELECT event_name,event_type FROM `event_master` WHERE event_id in (select event_id from `event_trans` WHERE regn_number=$pId)";
        $result2 = mysqli_query($con, $query);
        
        if(mysqli_num_rows($result2) == 0)
            return;
        $feePaid = 0;
        while ($rs2 = mysqli_fetch_array($result2)) {
            array_push($evList, $rs2['event_name']);
            $event_type = $rs2['event_type'];
        }

        $school_id = $rs['school_id'];
        $query = "SELECT school_name FROM `school_master` WHERE school_id=$school_id";
        $result3 = mysqli_query($con, $query);
        $rs3 = mysqli_fetch_array($result3);
        $school_name = $rs3['school_name'];

        $name = $rs['student_name'];
        $dob = $rs['dob'];
        $date = explode('-', $dob);
        $dob = $date[2] . '/' . $date[1] . '/' . $date[0];
        $school = $school_name;
        $category = getEventName($event_type, 0);
        $events = $evList;
    }


    $lineheight = 7;
// Instanciation of inherited class

    $pdf->AddPage();
    $pdf->Image('../images/reglogo.png', 7, 6, 100);
    $pdf->Ln(15);
    $pdf->SetFont('Times', 'B', 12);
    $pdf->Cell(25, $lineheight, 'NAME:');
    $pdf->SetFont('Times', '', 12);
    $pdf->Cell(0, $lineheight, $name, 0, 1);

    $pdf->SetFont('Times', 'B', 12);
    $pdf->Cell(25, $lineheight, 'DOB:');
    $pdf->SetFont('Times', '', 12);
    $pdf->Cell(0, $lineheight, $dob, 0, 1);

    $pdf->SetFont('Times', 'B', 12);
    $pdf->Cell(25, $lineheight, 'Category:');
    $pdf->SetFont('Times', '', 12);
    $pdf->Cell(25, $lineheight, $category, 0, 0);

    $pdf->SetFont('Times', 'B', 12);
    $pdf->Cell(25, $lineheight, 'Regn No:');
    $pdf->SetFont('Times', '', 12);
    $pdf->Cell(25, $lineheight, $pId, 0, 1);

    $pdf->SetFont('Times', 'B', 12);
    $pdf->Cell(25, $lineheight, 'School:');
    $pdf->SetFont('Times', '', 12);
    $pdf->Cell(0, $lineheight, $school, 0, 1);

    $pdf->SetFont('Times', 'B', 12);
    $pdf->Cell(25, $lineheight, 'Events:', 0, 1);
//$pdf->line(3,65,112,65);

    if (sizeof($events) <= 10) {
        $fontSize = 12;
        $lineheight = 6;
    } else {
        $fontSize = 10;
        $lineheight = 4;
    }
    for ($i = 0; $i < sizeof($events); $i++) {
        $pdf->Cell(15);
        $pdf->SetFont('Times', 'B', $fontSize);
        $pdf->Cell(5, $lineheight, "\xBB");
        $pdf->SetFont('Times', '', $fontSize);
        $pdf->Cell(0, $lineheight, $events[$i], 0, 1);
    }
}

?>
