<?php
include 'koneksi.php';

// Ambil data waktu masuk dan pulang saat ini
$query = "SELECT * FROM time WHERE id = 1";
$result = mysqli_query($conn, $query);
$current_setting = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $waktu_masuk = $_POST['waktu_masuk'];
    $waktu_pulang = $_POST['waktu_pulang'];

    if ($current_setting) {
        // Update jika sudah ada pengaturan
        $update_query = "UPDATE time SET waktu_masuk = '$waktu_masuk', waktu_pulang = '$waktu_pulang' WHERE id = 1";
        mysqli_query($conn, $update_query);
    } else {
        // Insert jika belum ada pengaturan
        $insert_query = "INSERT INTO time (waktu_masuk, waktu_pulang) VALUES ('$waktu_masuk', '$waktu_pulang')";
        mysqli_query($conn, $insert_query);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Waktu Masuk dan Pulang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
            margin: 0;
        }
        .container {
            width: 300px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
            color: #555;
        }
        input[type="time"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pengaturan Waktu</h2>
        <form method="POST">
            <label>Waktu Masuk:</label>
            <input type="time" name="waktu_masuk" value="<?php echo $current_setting['waktu_masuk'] ?? ''; ?>" required>

            <label>Waktu Pulang:</label>
            <input type="time" name="waktu_pulang" value="<?php echo $current_setting['waktu_pulang'] ?? ''; ?>" required>

            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>
