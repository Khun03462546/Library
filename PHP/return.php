<?php
$servername = "localhost";
$username = "root"; 
$password = "12345678"; 
$dbname = "library"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loanId = $_POST['loan_id'];
    $returnDate = date('Y-m-d');
    
    $sql = "UPDATE loans SET return_date='$returnDate' WHERE loan_id='$loanId'";
    if ($conn->query($sql) === TRUE) {
        echo "คืนหนังสือสำเร็จ!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คืนหนังสือ</title>
</head>
<body>
    <h2>คืนหนังสือ</h2>
    <form action="return.php" method="POST">
        <label for="loan_id">รหัสการยืม:</label>
        <input type="number" id="loan_id" name="loan_id" required>
        <input type="submit" value="คืนหนังสือ">
    </form>
    <a href="index.php">กลับไปยังหน้าแรก</a>
</body>
</html>

<?php
$conn->close();
?>
