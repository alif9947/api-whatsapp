<?php
// Include the database connection file
include 'koneksi.php';

// Get the ID of the student to edit
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the student data based on the ID
    $sql = "SELECT * FROM siswa WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!$data) {
        die("Student with ID: $id not found.");
    }
}

// Handle the form submission for updating the data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $no_absen = htmlspecialchars($_POST['no_absen']);
    $kelas = htmlspecialchars($_POST['kelas']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    $nisn = htmlspecialchars($_POST['nisn']);
    $rfid = htmlspecialchars($_POST['rfid']);

    // Prepare the SQL update statement
    $sql_update = "UPDATE siswa SET name = ?, no_absen = ?, kelas = ?, email = ?, phone = ?, address = ?, nisn = ?, rfid = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssssssi", $name, $no_absen, $kelas, $email, $phone, $address, $nisn, $rfid, $id);

    // Execute and check for errors
    if ($stmt_update->execute()) {
        echo "Data updated successfully.";
        header("location: proses_tampilan.php"); // Redirect after updating
        exit();
    } else {
        echo "Error: " . $stmt_update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Siswa</title>
    <style>
        /* Basic form styling */
        form {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h2>Edit Data Siswa</h2>

<form action="" method="POST">
    <label for="name">Nama:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($data['name']); ?>" required>

    <label for="nisn">NISN:</label>
    <input type="text" id="nisn" name="nisn" value="<?php echo htmlspecialchars($data['nisn']); ?>" required>

    <label for="no_absen">Absen:</label>
    <input type="text" id="no_absen" name="no_absen" value="<?php echo htmlspecialchars($data['no_absen']); ?>" required>

    <label for="kelas">Kelas:</label>
    <input type="text" id="kelas" name="kelas" value="<?php echo htmlspecialchars($data['kelas']); ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" required>

    <label for="phone">Telepon:</label>
    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($data['phone']); ?>" required>

    <label for="address">Alamat:</label>
    <textarea id="address" name="address" required><?php echo htmlspecialchars($data['address']); ?></textarea>

    <label for="rfid">RFID:</label>
    <input type="text" id="rfid" name="rfid" value="<?php echo htmlspecialchars($data['rfid']); ?>" required>

    <input type="submit" value="Update Data">
</form>

</body>
</html>
