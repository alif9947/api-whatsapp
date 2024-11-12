<?php
include 'koneksi.php';

// Set zona waktu ke Jakarta
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid = $_POST['rfid']; 
    $tanggal = date('Y-m-d'); // Format hanya tanggal
    $waktu = date('H:i:s');   // Format hanya waktu

    // Cek apakah siswa dengan RFID tersebut ada di database
    $query_siswa = "SELECT id, phone, name FROM siswa WHERE rfid = '$rfid'"; // Menambahkan kolom phone dan name
    $result_siswa = mysqli_query($conn, $query_siswa);

    if ($result_siswa === false) {
        die("Error pada query siswa: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result_siswa) > 0) {
        $row_siswa = mysqli_fetch_assoc($result_siswa);
        $id_siswa = $row_siswa['id'];
        $phone = $row_siswa['phone']; // Ambil nomor telepon siswa
        $name = $row_siswa['name']; // Ambil nama siswa

        // Cek apakah sudah ada catatan absen pada hari ini
        $query_absen = "SELECT * FROM absen WHERE id_siswa = '$id_siswa' AND DATE(tanggal) = CURDATE()";
        $result_absen = mysqli_query($conn, $query_absen);
 
        if ($result_absen === false) {
            die("Error pada query absen: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result_absen) == 0) {
            // Jika belum ada catatan absen, masukkan data baru sebagai absen masuk
            $insert_query = "INSERT INTO absen (id_siswa, tanggal, waktu_masuk, status_masuk) VALUES ('$id_siswa', '$tanggal', '$waktu', 'Hadir')";
            if (mysqli_query($conn, $insert_query)) {
                echo "Absensi masuk berhasil tercatat!";
                // Mengirim pesan
                $message = "Halo $name, ABSENSI *Masuk* \n 
                Anda pada $tanggal jam $waktu telah tercatat.";
                sendMessage($phone, $message); // Panggil fungsi untuk mengirim pesan
            } else {
                echo "Error saat memasukkan data absen: " . mysqli_error($conn);
            }
        } else {
            // Jika sudah ada catatan absen, update data sebagai absen pulang
            $row_absen = mysqli_fetch_assoc($result_absen);

            // Cek apakah waktu_pulang masih kosong
            if (is_null($row_absen['waktu_pulang']) || $row_absen['waktu_pulang'] == '00:00:00') {
                $update_query = "UPDATE absen SET waktu_pulang = '$waktu', status_pulang = 'Pulang' WHERE id_siswa = '$id_siswa' AND DATE(tanggal) = CURDATE()";
                if (mysqli_query($conn, $update_query)) {
                    echo "Absensi pulang berhasil tercatat!";
                    // Mengirim pesan
                    $message = "$name, ABSENSI *Pulang* \n 
                    Anda pada $tanggal jam $waktu telah tercatat.";
                    sendMessage($phone, $message); // Panggil fungsi untuk mengirim pesan
                } else {
                    echo "Error saat mengupdate data absen: " . mysqli_error($conn);
                }
            } else {
                echo "Anda sudah melakukan absensi pulang hari ini.";
            }
        }
    } else {
        // Jika RFID tidak ditemukan
        echo "RFID tidak ditemukan.";
    }

    mysqli_close($conn);
}

// Fungsi untuk mengirim pesan menggunakan cURL
function sendMessage($phone, $message) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'target' => $phone,
            'message' => $message,
            'countryCode' => '62', // optional
        ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: ' // Ganti dengan API key Anda
        ),
    ));

    $response = curl_exec($curl);      
    curl_close($curl);
    return $response; // Mengembalikan respon dari API
}
?>
