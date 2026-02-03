<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=hotel_db;charset=utf8", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: ".$e->getMessage());
}

function create_unique_id(){
    return bin2hex(random_bytes(8)); // generates 16-char unique ID
}
?>
