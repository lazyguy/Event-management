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
        $header = $GLOBALS["headerg"];
        $w = $GLOBALS["width"];
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(25, 10, $title, 0, 0, 'C');
        $this->Ln(20);
        $this->SetFont('Arial', 'B', 14);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', false);
        $this->Ln();
    }

    function Footer() {

        if ($this->PageNo() != 1) {
            $w = $GLOBALS["width"];
            $this->Cell(array_sum($w), 0, '', 'T');
        }
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Print current and total page numbers

        $this->Cell(0, 10, 'Summary - Page ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }

    function FancyTable($data, $type) {
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
        // Data
        $w = $GLOBALS["width"];

        //    print_r($data);
        $fill = false;
        foreach ($data as $row) {
            if ($type == 1) {
                $this->Cell($w[0], 6, $row[1], 'LR', 0, 'L', $fill);
                $this->Cell($w[1], 6, number_format($row[2]), 'LR', 0, 'R', $fill);
                $this->Cell($w[2], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
                $this->Cell($w[3], 6, number_format($row[4]), 'LR', 0, 'R', $fill);
                $this->Ln();
            } else {
                $this->Cell($w[0], 6, $row[1], 'LR', 0, 'L', $fill);
                $this->Cell($w[1], 6, number_format($row[2]), 'LR', 0, 'R', $fill);
                $this->Cell($w[2], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
                $this->Ln();
            }
            $fill = !$fill;
        }
    }

}

$con = mysql_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysql_error($con));
} else {
    $db_selected = mysql_select_db('BALOLSAV', $con);
    if ($db_selected) {
        $d = getSummary($con);

        $pdf = new PDF();
        $pdf->AliasNbPages();

        $pdf->AddPage();
        $GLOBALS["titleg"] = "Summary";
        $GLOBALS["width"] = array(0, 0, 0, 0);
        $GLOBALS["headerg"] = array('', '', '', '');

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(100, 6, 'Total Number of Participants :');
        $pdf->SetFont('Times', '', 12);
        $pdf->Cell(15, 6, $d["participant"]["maleCount"] + $d["participant"]["femaleCount"], 15, 1,'R');
        $pdf->Ln();

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(100, 6, 'Total Number of Male Participants :');
        $pdf->SetFont('Times', '', 12);
        $pdf->Cell(15, 6, $d["participant"]["maleCount"], 0, 1,'R');
        $pdf->Ln();

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(100, 6, 'Total Number of Feale Participants :');
        $pdf->SetFont('Times', '', 12);
        $pdf->Cell(15, 6, $d["participant"]["femaleCount"], 0, 1,'R');
        $pdf->Ln();

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(100, 6, 'Total Number of Senior Participants :');
        $pdf->SetFont('Times', '', 12);
        $pdf->Cell(15, 6, $d["participant"]["seniorCount"], 0, 1,'R');
        $pdf->Ln();

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(100, 6, 'Total Number of Junior Participants :');
        $pdf->SetFont('Times', '', 12);
        $pdf->Cell(15, 6, $d["participant"]["juniorCount"], 0, 1,'R');
        $pdf->Ln();

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(100, 6, 'Total Number of Schools :');
        $pdf->SetFont('Times', '', 12);
        $pdf->Cell(15, 6, $d["counts"]["schoolCount"], 0, 1,'R');
        $pdf->Ln();

        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(100, 6, 'Total Number of Events :');
        $pdf->SetFont('Times', '', 12);
        $pdf->Cell(15, 6, $d["counts"]["eventsCount"], 0, 1,'R');
        $pdf->Ln();
        $pdf->Ln();

        $pdf->Cell(180, 0, '', 'T');
        $pdf->Ln();
        $pdf->SetFont('Times', 'B', 12);
        $pdf->Cell(100, 6, 'Total Fee Collected :');
        $pdf->SetFont('Times', '', 12);
        $pdf->Cell(15, 6, number_format($d["counts"]["totalFeePaid"]), 0, 1,'R');
        $pdf->Cell(180, 0, '', 'T');

        $GLOBALS["titleg"] = "School Summary";
        $GLOBALS["width"] = array(125, 20, 20, 15);
        $GLOBALS["headerg"] = array('Name', 'Seniors', 'Juniors', 'Fees');
        $pdf->AddPage();
        $pdf->FancyTable(array_values($d["school"]), 1);

        $GLOBALS["titleg"] = "Event Summary";
        $GLOBALS["width"] = array(130, 25, 25);
        $GLOBALS["headerg"] = array('Event Name', 'Seniors', 'Juniors');
        $pdf->AddPage();
        $pdf->FancyTable(array_values($d["events"]), 2);

        $pdf->AutoPrint(true);
        $pdf->Output();
    }
}

function getSummary($con) {
    $query = "select count(*) from participant_master where sex='1'";
    $rsd = mysql_query($query);
    $count = mysql_fetch_array($rsd);
    $maleCount = $count[0];

    $query = "select count(*) from participant_master where sex='2'";
    $rsd = mysql_query($query);
    $count = mysql_fetch_array($rsd);
    $femaleCount = $count[0];

    $query = "select count(*) from participant_master where category='1'";
    $rsd = mysql_query($query);
    $count = mysql_fetch_array($rsd);
    $juniorCount = $count[0];

    $query = "select count(*) from participant_master where category='2'";
    $rsd = mysql_query($query);
    $count = mysql_fetch_array($rsd);
    $seniorCount = $count[0];
    $partArray = array("maleCount" => $maleCount, "femaleCount" => $femaleCount, "juniorCount" => $juniorCount, "seniorCount" => $seniorCount);

    $query = "select count(*) from school_master where 1";
    $rsd = mysql_query($query);
    $result = mysql_fetch_array($rsd);
    $schoolCount = $result[0];

    $query = "select count(*) from event_master where 1";
    $rsd = mysql_query($query);
    $result = mysql_fetch_array($rsd);
    $eventCount = $result[0];

    $query = "SELECT sum(asset_sums) FROM (select avg(a.fee_paid) as asset_sums from event_trans a, participant_master b where b.regn_number = a.regn_number group by b.regn_number) inner_query";
    $rsd = mysql_query($query);
    $result = mysql_fetch_array($rsd);
    $totalFeePaid = $result[0];

    $countsArray = array("schoolCount" => $schoolCount, "eventsCount" => $eventCount, "totalFeePaid" => $totalFeePaid);

    $sArray[] = array();
    $query = "SELECT school_id from school_master where 1 order by school_name asc";
    $rsd = mysql_query($query);
    $counter = 0;
    while ($result = mysql_fetch_array($rsd)) {
        $sId = $result["school_id"];

        $query = "SELECT SUM( asset_sums ) as total_sum,school_id1 FROM ( SELECT 
                b.school_id AS school_id1, AVG( a.fee_paid ) AS asset_sums 
                FROM event_trans a, participant_master b WHERE b.regn_number = a.regn_number 
                GROUP BY b.regn_number, b.school_id )inner_query where school_id1=$sId";

        $rsd0 = mysql_query($query);
        $result0 = mysql_fetch_array($rsd0);
        $sSumPaid = $result0["total_sum"];
        if ($sSumPaid == NULL)
            $sSumPaid = 0;

        $query = "select school_name from school_master where school_id = $sId";
        $rsd1 = mysql_query($query);
        $result1 = mysql_fetch_array($rsd1);
        $sName = $result1["school_name"];

        $query = "select count(*) from participant_master where school_id = $sId and category='1'";
        $rsd1 = mysql_query($query);
        $result1 = mysql_fetch_array($rsd1);
        $juniorCount = $result1[0];

        $query = "select count(*) from participant_master where school_id = $sId and category='2'";
        $rsd1 = mysql_query($query);
        $result1 = mysql_fetch_array($rsd1);
        $seniorCount = $result1[0];

        $sArray[$counter] = array($sId, $sName, $seniorCount, $juniorCount, $sSumPaid);
        $counter = $counter + 1;
    }

    $eArray[] = array();
    $query = "SELECT * from event_master";
    $rsd = mysql_query($query);
    $counter = 0;
    while ($result = mysql_fetch_array($rsd)) {
        $eId = $result["event_id"];
        $eName = $result["event_name"];
        $eName = $eName . " - " . getEventName($result["event_type"]);
        $query = "select count(*) from participant_master where regn_number IN(select regn_number from event_trans where event_id = $eId) and category = '1'";
        $rsd0 = mysql_query($query);
        $result0 = mysql_fetch_array($rsd0);
        $jCount = $result0[0];

        $query = "select count(*) from participant_master where regn_number IN(select regn_number from event_trans where event_id = $eId) and category = '1'";
        $rsd0 = mysql_query($query);
        $result0 = mysql_fetch_array($rsd0);
        $sCount = $result0[0];

        $eArray[$counter] = array($eId, $eName, $jCount, $sCount);
        $counter = $counter + 1;
    }

//  
    $returnJson = array("participant" => $partArray, "counts" => $countsArray, "school" => $sArray, "events" => $eArray);
    return $returnJson;
}

?>
