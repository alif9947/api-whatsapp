<?php
include 'koneksi.php';
// Set zona waktu ke Jakarta
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid = $_POST['rfid']; 
    $tanggal = date('Y-m-d'); // Format hanya tanggal
    $waktu = date('H:i:s');   // Format hanya waktu

    // Cek apakah siswa dengan RFID tersebut ada di database
    $query_siswa = "SELECT id, phone, name FROM siswa WHERE rfid = '$rfid'";
    $result_siswa = mysqli_query($conn, $query_siswa);

    if ($result_siswa === false) {
        die("Error pada query siswa: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result_siswa) > 0) {
        $row_siswa = mysqli_fetch_assoc($result_siswa);
        $id_siswa = $row_siswa['id'];
        $phone = $row_siswa['phone']; 
        $name = $row_siswa['name']; 

        // Ambil waktu masuk dan pulang standar dari tabel time
        $query_time = "SELECT waktu_masuk, waktu_pulang FROM time ORDER BY id DESC LIMIT 1"; 
        $result_time = mysqli_query($conn, $query_time);
        if ($result_time === false) {
            die("Error pada query time: " . mysqli_error($conn));
        }

        $row_time = mysqli_fetch_assoc($result_time);
        $waktu_masuk_standar = $row_time['waktu_masuk'];
        $waktu_pulang_standar = $row_time['waktu_pulang']; // Waktu pulang standar dari tabel time

        // Cek apakah sudah ada catatan absen pada hari ini
        $query_absen = "SELECT * FROM absen WHERE id_siswa = '$id_siswa' AND DATE(tanggal) = CURDATE()";
        $result_absen = mysqli_query($conn, $query_absen);
 
        if ($result_absen === false) {
            die("Error pada query absen: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result_absen) == 0) {
            // Jika belum ada catatan absen, masukkan data baru sebagai absen masuk
            // Membandingkan waktu absen dengan waktu masuk standar
            $status_masuk = (strtotime($waktu) > strtotime($waktu_masuk_standar)) ? 'Terlambat' : 'Hadir';

            $insert_query = "INSERT INTO absen (id_siswa, tanggal, waktu_masuk, status_masuk, status_pulang) 
                             VALUES ('$id_siswa', '$tanggal', '$waktu', '$status_masuk', NULL)";
            if (mysqli_query($conn, $insert_query)) {
                // Menampilkan alert dan kembali ke halaman awal
                echo "<script>alert('Absensi masuk berhasil tercatat!'); window.location.href = '../absen.html';</script>";
                
                // Mengirim pesan WA
                $message = "Halo $name, ABSENSI *Masuk*\nAnda pada $tanggal jam $waktu telah tercatat. Status: $status_masuk.";
                sendMessage($phone, $message); 
            } else {
                echo "<script>alert('Error saat memasukkan data absen: " . mysqli_error($conn) . "'); window.location.href = '../absen.html';</script>";
            }
        } else {
            // Jika sudah ada catatan absen, update data sebagai absen pulang
            $row_absen = mysqli_fetch_assoc($result_absen);

            // Cek apakah waktu_pulang masih kosong dan apakah sudah mencapai waktu pulang standar
            if ((is_null($row_absen['waktu_pulang']) || $row_absen['waktu_pulang'] == '00:00:00') && strtotime($waktu) >= strtotime($waktu_pulang_standar)) {
                $update_query = "UPDATE absen SET waktu_pulang = '$waktu', status_pulang = 'Pulang' 
                                 WHERE id_siswa = '$id_siswa' AND DATE(tanggal) = CURDATE()";
                if (mysqli_query($conn, $update_query)) {
                    echo "<script>alert('Absensi pulang berhasil tercatat!'); window.location.href = '../absen.html';</script>";
                    
                    // Mengirim pesan WA
                    $message = "$name, ABSENSI *Pulang*\nAnda pada $tanggal jam $waktu telah tercatat. Status: Pulang.";
                    sendMessage($phone, $message); 
                } else {
                    echo "<script>alert('Error saat mengupdate data absen: " . mysqli_error($conn) . "'); window.location.href = '../absen.html';</script>";
                }
            } else {
                echo "<script>alert('Belum waktunya untuk absen pulang atau Anda sudah absen pulang hari ini!'); window.location.href = '../absen.html';</script>";
            }
        }
    } else {
        // Jika RFID tidak ditemukan
        echo "<script>alert('RFID tidak ditemukan.'); window.location.href = '../absen.html';</script>";
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
