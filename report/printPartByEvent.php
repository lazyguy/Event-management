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
        $w = $GLOBALS["width"];
        //  if ($GLOBALS["type"] != 1)
        $this->Cell(array_sum($w), 0, '', 'T');
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        $this->SetX(-50);
        // Select Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Print current and total page numbers
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0);
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
        if ($type == 1 && $sortby == 1) {
            $eventName = $data[0][0]["school_name"];
        } else {
            $eventName = $data[0][0]["ename"];
        }
        $GLOBALS["titleg"] = "Rotary Balolsav - " . getYear() . " - " . $eventName;
        //$GLOBALS["titleg"] = "All Participants For " . $eventName;
        // $this->Header1();

        for ($counter = 0; $counter < sizeof($data[0]); $counter++) {
            $row = $data[0][$counter];
            if ($type == 1) {
                if ($type == 1 && $sortby == 1)
                    $compareThis = $row["school_name"];
                else
                    $compareThis = $row["ename"];
                if (strcmp($eventName, $compareThis) != 0) {
                    //  $this->Cell(array_sum($w), 0, '', 'T');

                    if ($type == 1 && $sortby == 1) {
                        $eventName = $row["school_name"];
                    } else {
                        $eventName = $row["ename"];
                    }
                    $GLOBALS["titleg"] = "All Participants For " . $eventName;
                    $this->AddPage();
                    $fill = false;
                }
                $this->SetFont('Arial', '', 14);
                $this->Cell($w[0], 9, '', 'LR', 0, 'L', $fill);
                $this->Cell($w[1], 9, number_format($row["rNum"]), 'LR', 0, 'L', $fill);
                $this->Cell($w[2], 9, $row["name"], 'LR', 0, 'L', $fill);
                $this->Cell($w[3], 9, $row["school_name"], 'LR', 0, 'L', $fill);
                $this->Cell($w[4], 9, $row["sex"], 'LR', 0, 'L', $fill);
                $this->Cell($w[5], 9, $row["age"], 'LR', 0, 'R', $fill);
                $this->Ln();
            } else {
                $this->SetFont('Arial', '', 14);
                $this->Cell($w[0], 9, '', 'LR', 0, 'L', $fill);
                $this->Cell($w[1], 9, number_format($row["rNum"]), 'LR', 0, 'L', $fill);
                $this->Cell($w[2], 9, $row["name"], 'LR', 0, 'L', $fill);
                $this->Cell($w[3], 9, $row["school_name"], 'LR', 0, 'L', $fill);
                $this->Cell($w[4], 9, $row["sex"], 'LR', 0, 'L', $fill);
                $this->Cell($w[5], 9, $row["age"], 'LR', 0, 'R', $fill);
                $this->Ln();
            }
            $fill = !$fill;
        }
        //  if ($type == 1)
        //  $this->Cell(array_sum($w), 0, '', 'T');
    }

}

$con = mysql_connect("localhost", "root", "");
if (!$con) {
    die('Could not connect: ' . mysql_error($con));
} else {
    $eId = $_GET["eId"];
    $db_selected = mysql_select_db('BALOLSAV', $con);
    if ($db_selected) {
        $d = getData($con);

        $pdf = new PDF('L', 'mm', 'A4');
        $pdf->AliasNbPages();
        // $pdf->SetMargins(2, 5, 2);
        $GLOBALS["width"] = array(25, 20, 60, 100, 20, 15);
        $GLOBALS["headerg"] = array('Chest No', 'Reg No', 'Name', 'School Name', 'Sex', 'Age');

        if ($eId == 999999) {
            $GLOBALS["type"] = 1;
            if (isset($_GET["sortby"]))
                $sortby = $_GET["sortby"];
            else
                $sortby = 0;

            if ($type == 1 && $sortby == 1) {
                $eventName = $d["participants"][0]["school_name"];
            } else {
                $eventName = $d["participants"][0]["ename"];
            }
            $GLOBALS["titleg"] = "Rotary Balolsav - " . getYear() . " - " . $eventName;

            if ($sortby == 1) {
                foreach ($d["participants"] as $key => $row) {
                    $dates[$key] = $row["school_name"];
                    // of course, replace 0 with whatever is the date field's index
                }

                array_multisort($dates, SORT_DESC, $d["participants"]);
            }
            $pdf->AddPage();
            $pdf->FancyTable(array_values($d), 1, $sortby);
        } else {
            $GLOBALS["type"] = 2;
            $pdf->AddPage();
            $pdf->FancyTable(array_values($d), 2, -1);
        }
        $pdf->AutoPrint(true);
        $pdf->Output();
    }
}

function getData($con) {
    $eId = $_GET["eId"];
    //need to get regn number, name, school name, sex, age
    if ($eId == 999999) {
        $query = "select * from event_trans where 1 order by event_id"; // order by event_name";
    } else {
        $query = "select regn_number from event_trans where event_id = $eId";
    }
    $rs = mysql_query($query);

    $partArray[] = array();
    $counter = 0;
    while ($result = mysql_fetch_array($rs)) {
        $regn_number = $result[0];
        if ($eId == 999999)
            $event_id = $result["event_id"];
        else
            $event_id = $eId;
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

        $query = "select event_name,event_type from event_master where event_id = $event_id";
        $rs1 = mysql_query($query);
        $result1 = mysql_fetch_array($rs1);
        $event_name = $result1["event_name"];
        $event_name = $event_name . " - " . getEventName($result1["event_type"]);

        $partArray[$counter] = array("rNum" => $regn_number, "ename" => $event_name, "name" => $name, "sex" => $sex, "age" => $age, "school_name" => $school_name);
        $counter = $counter + 1;
    }
    if ($counter > 0) {
        $returnJson = array("participants" => $partArray);
        return ($returnJson);
    } else {
        return "0";
    }
}

?>
