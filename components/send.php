<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

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
            'message' => "Nama: $name\nAlamat: $address",
            'countryCode' => '62', //optional
        ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: YOUR_API_KEY' //ganti YOUR_API_KEY dengan API key Anda
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    echo $response;
}
?>
