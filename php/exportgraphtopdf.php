<?php
	include('cfg.php');
	session_start();
	require_once __DIR__ . '/../vendor/autoload.php';

	$graphNo = $_GET['graphNo'];

	$sql = "SELECT ID, Name FROM Graphs WHERE GraphNo = :graphNo AND UserID = :userID";
	$stmt = $dbh->prepare($sql);
	$stmt->execute([
		':graphNo' => $graphNo,
		':userID' => $_SESSION['userID']
	]);
	$result = $stmt->fetchAll(PDO::FETCH_NUM);
	$graphID = $result[0][0];
	$graphName = $result[0][1];

	$pdf = new TCPDF();
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->AddPage();

	$html = "<p style='text-align:center'><i>PDF wygenerowano: ".date('Y-m-d H:i:s')."</i></p>";
	$pdf->writeHTML($html, true, false, true, false, '');

	$pdf->Output('example.pdf', 'I');
?>