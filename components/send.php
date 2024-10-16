<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name']; 
    $nisn = $_POST['nisn']; // Menambahkan NISN
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $kelas = $_POST['kelas']; // Menambahkan Kelas
    $no_absen = $_POST['no_absen']; // Menambahkan No Absen
    $tanggal = date('Y-m-d'); // Menambahkan tanggal saat ini
    $rfid = $_POST['rfid']; // Menambahkan RFID

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
            'message' => "
            \nNama: $name
            \nNISN: $nisn
            \nNo Absen: $no_absen
            \nKelas: $kelas
            \nEmail: $email
            \nAlamat: $address
            \nRFID: $rfid
            \nTanggal: $tanggal", // Menambahkan RFID ke dalam pesan
            'countryCode' => '62', // optional
        ),
        CURLOPT_HTTPHEADER => array(
            'Authorization:-' // ganti YOUR_API_KEY dengan API key Anda
        ),
    ));

    $response = curl_exec($curl);      
    curl_close($curl);
    echo $response;
}
?>
