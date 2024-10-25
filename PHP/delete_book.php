<?php
include("login.php");

if (isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];

    $sqlDeletePurchases = "DELETE FROM purchases WHERE book_id = ?";
    $stmtDeletePurchases = $conn->prepare($sqlDeletePurchases);
    $stmtDeletePurchases->bind_param("i", $book_id);
    $stmtDeletePurchases->execute();
    $stmtDeletePurchases->close();

   
    $sql = "DELETE FROM books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    
    if ($stmt->execute()) {
        echo "หนังสือลบเรียบร้อยแล้ว.";
        header("Location: index.php"); 
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการลบหนังสือ: " . $conn->error;
    }
} else {
    echo "ไม่มีหมายเลขหนังสือ.";
}


$stmt->close();
$conn->close();
?>
