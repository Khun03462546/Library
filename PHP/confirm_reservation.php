<?php
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "library";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}


if (isset($_POST['book_id']) && isset($_POST['user_id'])) {
    $book_id = $_POST['book_id'];
    $user_id = $_POST['user_id'];

    $sql = "INSERT INTO reservations (user_id, book_id, reservation_date) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("เกิดข้อผิดพลาดในการเตรียมคำสั่ง: " . $conn->error);
    }

    $stmt->bind_param("ii", $user_id, $book_id);

    if ($stmt->execute()) {
        echo "จองหนังสือเรียบร้อยแล้ว!";
       
        header("Location: reservebook.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
} else {
    echo "ไม่พบข้อมูลการจอง";
}

$stmt->close();
$conn->close();
?>
