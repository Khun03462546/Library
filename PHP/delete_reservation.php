<?php
include("login.php");

$reserve_id = $_POST['reservation_id'];

$sql_get_book_id = "SELECT book_id FROM reservations WHERE reservation_id = ?";
$stmt_get_book_id = $conn->prepare($sql_get_book_id);
$stmt_get_book_id->bind_param("i", $reserve_id);
$stmt_get_book_id->execute();
$result_get_book_id = $stmt_get_book_id->get_result();

if ($result_get_book_id->num_rows > 0) {
    $row = $result_get_book_id->fetch_assoc();
    $book_id = $row['book_id'];

    $sql_delete_reserve = "DELETE FROM reservations WHERE reservation_id = ?";
    $stmt_delete_reserve = $conn->prepare($sql_delete_reserve);
    $stmt_delete_reserve->bind_param("i", $reserve_id);
    $stmt_delete_reserve->execute();

    $sql_update = "UPDATE books SET availability = 'available' WHERE book_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $book_id);
    $stmt_update->execute();

    header("Location: reservebook.php?message=return_success");
    exit();
} else {
    echo "ไม่พบข้อมูลการจอง";
}

$stmt_get_book_id->close();
$stmt_delete_reserve->close();
$stmt_update->close();
$conn->close();
?>
