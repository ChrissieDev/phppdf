<?php
require_once __DIR__ . '/vendor/autoload.php';

// handles form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // expenses)
    $pladsleje = htmlspecialchars($_POST["pladsleje"] ?? 0);
    $inventar = htmlspecialchars($_POST["inventar"] ?? 0);
    $andet = htmlspecialchars($_POST["andet"] ?? 0);

    // income
    $forventetBilletSal = htmlspecialchars($_POST["forventet_billet_salg"] ?? 0);
    $prisPerBillet = htmlspecialchars($_POST["pris_per_billet"] ?? 0);
    $andreIntaegter = htmlspecialchars($_POST["andre_intaegter"] ?? 0);

    // adds total income from ticket sales alone
    $totalBilletSalg = $forventetBilletSal * $prisPerBillet;

    // adds total expenses
    $totalUdgifter = $pladsleje + $inventar + $andet;

    // creates mPDF instance
    $mpdf = new \Mpdf\Mpdf();

    // write content to PDF
    $mpdf->WriteHTML("
        <h1>Økonomisk Oversigt</h1>
        <h2>Udgifter (Expenses)</h2>
        <p><strong>Pladsleje:</strong> {$pladsleje} DKK</p>
        <p><strong>Inventar:</strong> {$inventar} DKK</p>
        <p><strong>Andet:</strong> {$andet} DKK</p>
        <h3><strong>Total Udgifter:</strong> {$totalUdgifter} DKK</h3>

        <h2>Indtægter (Income)</h2>
        <p><strong>Forventet billet salg:</strong> {$forventetBilletSal} billetter</p>
        <p><strong>Pris per billet:</strong> {$prisPerBillet} DKK</p>
        <p><strong>Andre intægter:</strong> {$andreIntaegter} DKK</p>
        <h3><strong>Total Billet Indtægt:</strong> {$totalBilletSalg} DKK</h3>
        <h3><strong>Total Indtægter:</strong> " . ($totalBilletSalg + $andreIntaegter) . " DKK</h3>
    ");

    // Output PDF to browser
    $pdf = $mpdf->Output('', 'S');
    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=økonomsik-oversigt.pdf");
    echo $pdf;
    exit;
}
?>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Økonomisk Oversigt</title>
</head>
<body>
    <h2>Indtast Økonomiske Værdier</h2>
    <form method="POST">
        <!-- expenses -->
        <h3>Udgifter (Expenses)</h3>
        <label for="pladsleje">Pladsleje:</label><br>
        <input type="number" name="pladsleje" id="pladsleje" required><br><br>

        <label for="inventar">Inventar:</label><br>
        <input type="number" name="inventar" id="inventar" required><br><br>

        <label for="andet">Andet:</label><br>
        <input type="number" name="andet" id="andet" required><br><br>

        <!-- income -->
        <h3>Indtægter (Income)</h3>
        <label for="forventet_billet_salg">Forventet billet salg:</label><br>
        <input type="number" name="forventet_billet_salg" id="forventet_billet_salg" required><br><br>

        <label for="pris_per_billet">Pris per billet:</label><br>
        <input type="number" name="pris_per_billet" id="pris_per_billet" required><br><br>

        <label for="andre_intaegter">Andre intægter:</label><br>
        <input type="number" name="andre_intaegter" id="andre_intaegter" required><br><br>

        <input type="submit" value="Eksporter til PDF">
    </form>
</body>
</html>
