<?php
require('../fpdf/pdf_js.php');

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

/*
  $name = 'Sonu Lukose';
  $dob = '11/06/1985';
  $school = 'Public School Pattanakad';
  $category = 'Senior';
  $events = array('123', '456', '678');
 */
if (isset($_GET['name']))
    $name = $_GET["name"];
else
    $name = "balolsav";

if (isset($_GET['dob']))
    $dob = $_GET["dob"];
else
    $dob = "00/00/00";

if (isset($_GET['school']))
    $school = $_GET["school"];
else
    $school = "balolsav";

if (isset($_GET['category']))
    $category = $_GET["category"];
else
    $category = "balolsav";

if (isset($_GET['events']))
    $events = $_GET["events"];
else
    $events = array('balolsav', 'balolsav', 'balolsav');

$lineheight = 7;
// Instanciation of inherited class
$pdf = new PDF_AutoPrint('P', 'mm', array(115, 153));
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Image('../images/reglogo.png', 7, 6, 100);
$pdf->Ln(17);
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
$pdf->Cell(0, $lineheight, $category, 0, 1);

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(25, $lineheight, 'School:');
$pdf->SetFont('Times', '', 12);
$pdf->Cell(0, 10, $school, 0, 1);
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(25, $lineheight, 'Events:', 0, 1);
//$pdf->line(3,65,112,65);


for ($i = 0; $i < sizeof($events); $i++) {
    $pdf->Cell(15);
    $pdf->SetFont('Times', 'B', 14);
    $pdf->Cell(5, $lineheight, "\xBB");
    $pdf->SetFont('Times', '', 12);
    $pdf->Cell(0, $lineheight, $events[$i], 0, 1);
}

$pdf->AutoPrint(true);
$pdf->Output();
?>
