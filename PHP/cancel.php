<?php
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "library";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['purchase_id'])) {
    $purchase_id = $_POST['purchase_id'];

    $sql = "DELETE FROM purchases WHERE purchase_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $purchase_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo $stmt->error;
    }


    $stmt->close();
} else {
    echo "ไม่พบข้อมูลการซื้อที่ต้องการยกเลิก";
}

$conn->close();
?>