<?php
// Include the database connection file
include 'koneksi.php';
include 'send.php';

// Handle form submission and insert data into the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $no_absen = htmlspecialchars($_POST['no_absen']);
    $kelas = htmlspecialchars($_POST['kelas']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    date_default_timezone_set('Asia/Jakarta');
$tanggal_daftar = date('Y-m-d H:i:s');
    $nisn = htmlspecialchars($_POST['nisn']);

    // Prepare the SQL statement with nisn column
    $sql = "INSERT INTO siswa (name, no_absen, kelas, email, phone, address, tanggal_daftar, nisn)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters with nisn
    $stmt->bind_param("ssssssss", $name, $no_absen, $kelas, $email, $phone, $address, $tanggal_daftar, $nisn);

    // Execute and check for errors
    if ($stmt->execute()) {
        echo "Data successfully inserted into the database.";
    } else {
        echo "Error: " . $stmt->error;  // Output error message
    }

    $stmt->close();
}

// Handle deletion of records
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_sql = "DELETE FROM siswa WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    if ($stmt === false) {
        die("Error preparing delete statement: " . $conn->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("location: proses_tampilan.php"); // Refresh the page after deletion
    exit();
}

// Fetch data from the 'siswa' table
$sql = "SELECT * FROM siswa";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <style>
        /* CSS untuk tabel */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
        /* CSS untuk tombol */
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
        }
        .back-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <h2>Data Siswa</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>NISN</th>
            <th>Absen</th>
            <th>Kelas</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Tanggal Daftar</th>
            <th>Aksi</th>
        </tr>
        <?php
        // Display data in the table
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>"; // Use the correct column name
                echo "<td>" . $row["nisn"] . "</td>";
                echo "<td>" . $row["no_absen"] . "</td>"; // Use the correct column name
                echo "<td>" . $row["kelas"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["phone"] . "</td>"; // Use the correct column name
                echo "<td>" . $row["address"] . "</td>"; // Use the correct column name
                echo "<td>" . $row["tanggal_daftar"] . "</td>";
                echo "<td>
                        <a href='edit.php?id=" . $row['id'] . "'>Edit</a> |
                        <a href='?delete=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10'>No records found</td></tr>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </table>
    <a href="../index.html" class="back-button">Kembali</a>
</body>
</html>
