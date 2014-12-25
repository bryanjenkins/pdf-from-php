<?php

require('fpdf/fpdf.php');

class PDF_reciept extends FPDF {
	function __construct ($orientation = 'P', $unit = 'pt', $format = 'Letter', $margin = 40) {
		$this->FPDF($orientation, $unit, $format);
		$this->SetTopMargin($margin);
		$this->SetLeftMargin($margin);
		$this->SetRightMargin($margin);

		$this->SetAutoPageBreak(true, $margin);
	}

	function Header () {
		$this->SetFont('Arial', 'B', 20);
		$this->SetFillColor(14,50,94);
		$this->SetTextColor(255);
		$this->Cell(0, 30, "CaptiveOne", 0, 1, 'C', true);
	}

	function Footer () {
		$this->SetFont('Arial', '', 12);
		$this->SetTextColor(0);
		$this->SetXY(40, -60);
		$this->Cell(0, 20, "Thank you for your business", 'T', 0, 'C');
	}

	function PriceTable($products, $prices) {
		$this->SetFont('Arial', 'B', 12);
		$this->SetTextColor(0);
		$this->SetFillColor(146,146,146);
		$this->SetLineWidth(1);
		$this->Cell(427, 25, "Item Description", 'LTR', 0, 'C', true);
		$this->Cell(100, 25, "Price", 'LTR', 1, 'C', true);

		$this->SetFont('Arial', '');
		$this->SetFillColor(238);
		$this->SetLineWidth(0.2);
		$fill = false;

		for ($i = 0; $i < count($products); $i++) {
				$this->Cell(427, 20, $products[$i], 1, 0, 'L', $fill);
				$this->Cell(100, 20, '$' . $prices[$i], 1, 1, 'R', $fill);
				$fill = !$fill;
			}
			$this->SetX(367);
			$this->Cell(100, 20, "Total", 1);
			$this->Cell(100, 20, '$' . array_sum($prices), 1, 1, 'R');
		}


}

$pdf = new PDF_reciept();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetY(100);

$pdf->Cell(100, 13, "Requested By:");
$pdf->SetFont('Arial', '');

$pdf->Cell(200, 13, $_POST['name']);

$pdf->SetFont('Arial', 'B');
$pdf->Cell(50, 13, "Date:");
$pdf->SetFont('Arial', '');
$pdf->Cell(100, 13, date('F j, Y'), 0, 1);

$pdf->SetFont('Arial', 'I');
$pdf->SetX(140);
$pdf->Cell(200, 15, $_POST['address'], 0, 2);
$pdf->Cell(200, 15, $_POST['city'] . ',' . $_POST['state'] , 0, 2);
$pdf->Cell(200, 15, $_POST['postal_code'] . ' ' . $_POST['country'], 0, 2);

$pdf->Ln(100);

$pdf->PriceTable($_POST['product'], $_POST['price']);

$pdf->Ln(50);

$message = "Thank you for working with us.\n\nIf you have any questions, you can email us at the following email address:";

$pdf->MultiCell(0, 15, $message);

$pdf->SetFont('Arial', 'U', 12);
$pdf->SetTextColor(1, 162, 232);

$pdf->Write(13, "example@example.com", "mailto:example@example.com");

$pdf->Output();
