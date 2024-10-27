<?php
include("login.php");

$book_id = $_POST['book_id'];
$user_id = $_POST['user_id'];

// ตรวจสอบสถานะการจองหนังสือ
$sql_check_reservation = "SELECT user_id FROM reservations WHERE book_id = ?";
$stmt_check_reservation = $conn->prepare($sql_check_reservation);
$stmt_check_reservation->bind_param("i", $book_id);
$stmt_check_reservation->execute();
$result_check_reservation = $stmt_check_reservation->get_result();

if ($result_check_reservation->num_rows > 0) {
    $reservation = $result_check_reservation->fetch_assoc();
    
    // ตรวจสอบว่าผู้ใช้ที่จองคือผู้ใช้คนเดียวกันหรือไม่
    if ($reservation['user_id'] == $user_id) {
        // หนังสือถูกจองแล้วโดยผู้ใช้คนเดียวกัน สามารถจองได้
        $sql_reserve = "INSERT INTO reservations (user_id, book_id, reservation_date) VALUES (?, ?, NOW())";
        $stmt_reserve = $conn->prepare($sql_reserve);
        $stmt_reserve->bind_param("ii", $user_id, $book_id);

        if ($stmt_reserve->execute()) {
            // เปลี่ยนสถานะของหนังสือเป็น unavailable
            $sql_update = "UPDATE books SET availability = 'unavailable' WHERE book_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("i", $book_id);
            $stmt_update->execute();

            header("Location: reservebook.php?message=success");
            exit(); 
        } else {
            echo "เกิดข้อผิดพลาดในการบันทึกการจอง: " . $conn->error;
        }

        $stmt_reserve->close(); 
        $stmt_update->close(); 
    } else {
        // หนังสือถูกจองแล้วโดยผู้ใช้คนอื่น
        header("Location: reservebook.php?message=book_reserved");
        exit();
    }
} else {
    // ไม่มีการจองสำหรับหนังสือ
    echo "หนังสือไม่ถูกจองในระบบ สามารถจองได้.";
    
    // ทำการจองหนังสือโดยตรง
    $sql_reserve = "INSERT INTO reservations (user_id, book_id, reservation_date) VALUES (?, ?, NOW())";
    $stmt_reserve = $conn->prepare($sql_reserve);
    $stmt_reserve->bind_param("ii", $user_id, $book_id);

    if ($stmt_reserve->execute()) {
        // เปลี่ยนสถานะของหนังสือเป็น unavailable
        $sql_update = "UPDATE books SET availability = 'unavailable' WHERE book_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $book_id);
        $stmt_update->execute();

        header("Location: reservebook.php?message=success");
        exit(); 
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกการจอง: " . $conn->error;
    }

    $stmt_reserve->close(); 
    $stmt_update->close(); 
}

$stmt_check_reservation->close();
$conn->close();
?>
