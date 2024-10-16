<?php

include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid = $_POST['rfid']; 
    $tanggal = date('Y-m-d H:i:s');
    $waktu = date('H:i:s');          
}