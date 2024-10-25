<?php
include("login.php");

$book_id = $_POST['book_id'];
$user_id = $_POST['user_id'];

$sql_check = "SELECT availability FROM books WHERE book_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $book_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    $book = $result_check->fetch_assoc();

    if ($book['availability'] == 'available') {
     
        $sql_loan = "INSERT INTO loans (user_id, book_id, loan_date) VALUES (?, ?, NOW())";
        $stmt_loan = $conn->prepare($sql_loan);
        $stmt_loan->bind_param("ii", $user_id, $book_id);

        if ($stmt_loan->execute()) {
           
            $sql_update = "UPDATE books SET availability = 'unavailable' WHERE book_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("i", $book_id);
            $stmt_update->execute();

            
            header("Location: loanbooks.php?message=success");
            exit(); 
        } else {
            echo "เกิดข้อผิดพลาดในการบันทึกการยืม: " . $conn->error;
        }

        
        $stmt_loan->close(); 
        $stmt_update->close(); 
    } else {
        
        header("Location: loanbooks.php?message=book_unavailable");
        exit();
    }
} else {
    echo "ไม่พบข้อมูลหนังสือ";
}

$stmt_check->close();
$conn->close();
?>
