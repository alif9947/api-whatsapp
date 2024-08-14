<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $address = htmlspecialchars($_POST['address']);
    $phone = htmlspecialchars($_POST['phone']);
    $kelas = htmlspecialchars($_POST['kelas']);
    $no_absen = htmlspecialchars($_POST['no_absen']);
} else {
    header("location: index.html");
    exit(); // Perbaikan dari exit():
}

include'send.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pendaftaran</title>
</head>
<body>
    <h2>Data Pendaftaran</h2>
    <p><strong>Nama:</strong> <?php echo $name; ?></p>
    <p><strong>Alamat:</strong> <?php echo $address; ?></p>
    <p><strong>Nomor HP:</strong> <?php echo $phone; ?></p>
    <p><strong>Kelas:</strong> <?php echo $kelas; ?></p>
    <p><strong>No Absen:</strong> <?php echo $no_absen; ?></p>
</body>
</html>
