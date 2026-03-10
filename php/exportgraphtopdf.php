<?php
	include('cfg.php');
	session_start();
	require_once __DIR__ . '/../vendor/autoload.php';
	require_once __DIR__ . '/graph_image_temp.php';

	if (!isset($_SESSION['userID'])) {
		http_response_code(403);
		exit('Brak uprawnień do wygenerowania PDF. Zaloguj się.');
	}

	$graphNo = isset($_GET['graphNo']) ? (int)$_GET['graphNo'] : 1;

	$sql = "SELECT ID, Name FROM Graphs WHERE GraphNo = :graphNo AND UserID = :userID";
	$stmt = $dbh->prepare($sql);
	$stmt->execute([
		':graphNo' => $graphNo,
		':userID' => $_SESSION['userID']
	]);
	$result = $stmt->fetchAll(PDO::FETCH_NUM);

	if (empty($result)) {
		http_response_code(404);
		exit('Nie znaleziono wykresu.');
	}

	$graphID = $result[0][0];
	$graphName = $result[0][1];

	$dataSql = "
		SELECT Day, Temperature, isDone, isIllness
		FROM Temperature
		WHERE GraphID = :graphID
		ORDER BY Day ASC
	";
	$dataStmt = $dbh->prepare($dataSql);
	$dataStmt->execute([
		':graphID' => $graphID
	]);
	$rows = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

	$pdf = new TCPDF();
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->SetCreator('Graphs');
	$pdf->SetAuthor('Graphs');
	$pdf->SetTitle('Wykres temperatury');
	$pdf->SetSubject('Eksport wykresu do PDF');
	$pdf->SetMargins(15, 15, 15);
	$pdf->SetAutoPageBreak(true, 15);
	$pdf->setFontSubsetting(true);
	$pdf->AddPage();

	$pdf->SetFont('dejavusans', 'B', 16);

	$pdf->Ln(2);
	$pdf->SetFont('dejavusans', '', 10);
	$generatedAt = date('Y-m-d H:i:s');
	$userId = (int)$_SESSION['userID'];
	$pdf->writeHTMLCell(0, 0, '', '', "<i>PDF wygenerowano: {$generatedAt}</i>", 0, 1, 0, true, 'C', true);

	$pdf->Ln(4);
	$graphWidth = 1200;
	$graphHeight = 500;
	$margin = 100;

	$tempImagePath = generateGraphTempImage($dbh, $userId, $graphNo, $graphWidth, $graphHeight, $margin);

	if ($tempImagePath && file_exists($tempImagePath)) {
		$startY = $pdf->GetY();
		$pdf->Image($tempImagePath, '', $startY, 180, 0, 'PNG');
		@unlink($tempImagePath);

		$pdf->SetY($startY + 75);
	} else {
		$pdf->Ln(8);
	}

	if (!empty($rows)) {
		$legendHtml = '
			<table border="0" cellpadding="3" width="75%">
				<tr>
					<td colspan="2"><b>Legenda</b></td>
				</tr>
				<tr>
					<td width="25%" align="center">
						<span style="font-size:14px; color:#ff0000;">&#9679;</span>
					</td>
					<td width="75%" align="left">- choroba</td>
				</tr>
				<tr>
					<td width="25%" align="center">
						<span style="font-size:14px; color:#555555;">&#9679;</span>
					</td>
					<td width="75%" align="left">- brak pomiaru</td>
				</tr>
			</table>
		';

		$tableHtml = '
			<table border="1" cellpadding="3" width="75%">
				<thead>
					<tr style="background-color:#f0f0f0;">
						<th width="50%">Dzień</th>
						<th width="50%">Temperatura</th>
					</tr>
				</thead>
				<tbody>
		';

		foreach ($rows as $row) {
			$day = (int)$row['Day'];

			if ((int)$row['isIllness'] === 1) {
				$display = 'choroba';
			} elseif ((int)$row['isDone'] !== 1) {
				$display = 'brak pom.';
			} elseif ($row['Temperature'] !== null) {
				$display = number_format((float)$row['Temperature'], 1, ',', ' ') . ' °C';
			} else {
				$display = '-';
			}

			$tableHtml .= "
				<tr>
					<td width='30%' align='center'>{$day}</td>
					<td width='70%' align='center'>{$display}</td>
				</tr>
			";
		}

		$tableHtml .= '
				</tbody>
			</table>
		';

		$layoutHtml = '
			<table border="0" cellpadding="4" width="100%">
				<tr>
					<td width="50%" valign="top">
						' . $legendHtml . '
					</td>
					<td width="11%">Pomiary:</td>
					<td width="39%" valign="top" margin-left="100px">
						' . $tableHtml . '
					</td>
				</tr>
			</table>
		';

		$pdf->writeHTML($layoutHtml, true, false, true, false, '');
	} else {
		$pdf->writeHTML("<p>Brak danych pomiarów dla wybranego wykresu.</p>", true, false, true, false, '');
	}

	$safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $graphName);
	$fileName = "wykres_{$graphNo}_{$safeName}.pdf";

	$pdf->Output($fileName, 'I');
?>