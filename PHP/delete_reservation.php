<?php
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "library";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}


if (isset($_POST['reservation_id'])) {
    $reservation_id = $_POST['reservation_id'];


    $sql = "DELETE FROM reservations WHERE reservation_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);
    
    if ($stmt->execute()) {
        echo "การจองถูกลบเรียบร้อยแล้ว.";
        header("Location: reservebook.php"); 
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการลบการจอง: " . $conn->error;
    }
} else {
    echo "ไม่มีหมายเลขการจอง.";
}

$stmt->close();
$conn->close();
?>
