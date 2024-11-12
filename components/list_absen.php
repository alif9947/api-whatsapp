<?php
include 'koneksi.php'; // Menghubungkan ke database

// Query untuk mengambil data absensi
$query = "SELECT absen.id_absen, siswa.name, absen.tanggal, absen.waktu_masuk, absen.waktu_pulang, absen.status_masuk, absen.status_pulang 
          FROM absen 
          JOIN siswa ON absen.id_siswa = siswa.id"; // Mengambil nama siswa dari tabel siswa
$result = mysqli_query($conn, $query);

if ($result === false) {
    die("Error pada query: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Data Absensi Siswa</h2>

<table>
    <tr>
        <th>ID Absen</th>
        <th>Nama Siswa</th>
        <th>Tanggal</th>
        <th>Waktu Masuk</th>
        <th>Waktu Pulang</th>
        <th>Status Masuk</th>
        <th>Status Pulang</th>
    </tr>
    
    <?php
    // Menampilkan data dari hasil query
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id_absen'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['tanggal'] . "</td>";
        echo "<td>" . $row['waktu_masuk'] . "</td>";
        echo "<td>" . $row['waktu_pulang'] . "</td>";
        echo "<td>" . $row['status_masuk'] . "</td>";
        echo "<td>" . $row['status_pulang'] . "</td>";
        echo "</tr>";
    }
    ?>
    
</table>

</body>
</html>

<?php
// Menutup koneksi
mysqli_close($conn);
?>
