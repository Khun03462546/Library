<?php
include("login.php");

$loan_id = $_POST['loan_id'];

$sql_get_book_id = "SELECT book_id FROM loans WHERE loan_id = ?";
$stmt_get_book_id = $conn->prepare($sql_get_book_id);
$stmt_get_book_id->bind_param("i", $loan_id);
$stmt_get_book_id->execute();
$result_get_book_id = $stmt_get_book_id->get_result();

if ($result_get_book_id->num_rows > 0) {
    $row = $result_get_book_id->fetch_assoc();
    $book_id = $row['book_id'];

    $sql_delete_loan = "DELETE FROM loans WHERE loan_id = ?";
    $stmt_delete_loan = $conn->prepare($sql_delete_loan);
    $stmt_delete_loan->bind_param("i", $loan_id);
    $stmt_delete_loan->execute();

    $sql_update = "UPDATE books SET availability = 'available' WHERE book_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $book_id);
    $stmt_update->execute();

    header("Location: loanbooks.php?message=return_success");
    exit();
} else {
    echo "ไม่พบข้อมูลการยืม";
}

$stmt_get_book_id->close();
$stmt_delete_loan->close();
$stmt_update->close();
$conn->close();
?>
