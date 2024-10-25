<?php
// เพิ่มหนังสือ
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "library";

$conn = new mysqli($servername, $username, $password, $dbname);

// เช็คว่าการเชื่อมต่อสำเร็จหรือไม่
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>