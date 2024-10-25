<?php
include("login.php");

if (isset($_POST['purchase_id'])) {
    $purchase_id = $_POST['purchase_id'];


    $sql = "SELECT p.price, b.title 
            FROM purchases p 
            JOIN books b ON p.book_id = b.book_id 
            WHERE p.purchase_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $purchase_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $price = $row['price'];
        $title = $row['title'];

        $paymentUrl = "https://payment-gateway.com/pay?amount=" . $price . "&item=" . urlencode($title);

  
        $qrCodeImage = 'uploads/pay.jpg'; 

        echo "<h2>รายละเอียดการชำระเงิน</h2>";
        echo "<p>ชื่อหนังสือ: " . htmlspecialchars($title) . "</p>";
        echo "<p>ราคา: " . number_format($price, 2) . " บาท</p>";
        echo "<h3>สแกน QR Code เพื่อชำระเงิน:</h3>";
        echo "<img src='" . $qrCodeImage . "' alt='QR Code'>"; 
    } else {
        echo "ไม่พบข้อมูลการซื้อ.";
    }
} else {
    echo "ไม่มีหมายเลขการซื้อ.";
}

$conn->close();
?>

<a href="purchasesbooks.php" style="display:inline-block; margin-top: 20px; padding: 10px 20px; background-color: #6A5ACD; color: white; text-decoration: none; border-radius: 5px;">กลับไป</a>
