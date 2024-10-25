<?php
// เพิ่มหนังสือ
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "library";

$conn = new mysqli($servername, $username, $password, $dbname);

// เช็คว่าการเชื่อมต่อสำเร็จหรือไม่
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีข้อมูล POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars(trim($_POST['title']));
    $author = htmlspecialchars(trim($_POST['author']));
    $status = htmlspecialchars(trim($_POST['status']));
    $genre = htmlspecialchars(trim($_POST['genre'])); // ตรวจสอบว่า genre ได้รับค่าหรือไม่
    $price = htmlspecialchars(trim($_POST['price']));
    $table = $_POST['table'];

    // แสดงค่าของตัวแปรสำหรับตรวจสอบ
    echo "Title: $title, Author: $author, Status: $status, Genre: $genre, Price: $price<br>";

    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        if (in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                if ($table == 'books') {
                    $sql = "INSERT INTO books (title, author, status, cover_image, genre, price) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);

                    // ปรับปรุงการใช้ bind_param
                    $stmt->bind_param("sssssd", $title, $author, $status, $targetFile, $genre, $price);
                } elseif ($table == 'purchases') {
                    $sql = "INSERT INTO purchases (title, author, price, genre, cover_image) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);

                    // ตรวจสอบการใช้ bind_param
                    $stmt->bind_param("ssdsd", $title, $author, $price, $genre, $targetFile);
                }

                // ตรวจสอบการดำเนินการ
                if ($stmt->execute()) {
                    echo "<div class='success'>เพิ่มหนังสือสำเร็จ!</div>";
                    header("refresh:2; url=index.php");
                } else {
                    echo "Error: " . $stmt->error; // แสดงข้อผิดพลาดหากการดำเนินการไม่สำเร็จ
                }
            } else {
                echo "<div class='error'>ขอโทษ! ไม่สามารถอัปโหลดไฟล์ได้.</div>";
            }
        } else {
            echo "<div class='error'>ขอโทษ! ไฟล์ที่อัปโหลดต้องเป็นรูปภาพ (jpg, png, jpeg, gif).</div>";
        }
    } else {
        echo "<div class='error'>เกิดข้อผิดพลาดในการอัปโหลดไฟล์: " . $_FILES["image"]["error"] . "</div>";
    }
}
?>



<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ห้องสมุด - Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <style>

    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg custom-header">
        <a class="navbar-brand" href="index.php"><b>NU<span class="hover-span"> BookCenter</span></b></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="addbook.php">Add</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reservebook.php">Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="loanbooks.php">Loan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="purchasesbooks.php">Buy
                    </a>
                </li>
            </ul>
        </div>
    </nav>


    <div class="container">
        <div class="form-container">
            <h2 class="add">Add a NewBook</h2>
            <form action="addbook.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">ชื่อหนังสือ:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="author">ผู้เขียน:</label>
                    <input type="text" id="author" name="author" required>
                </div>
                <div class="form-group">
                    <label for="genre">ประเภท:</label>
                    <select id="genre" name="genre" required>
                        <option value="นิยาย">นิยาย</option>
                        <option value="สารคดี">หนังสือสารคดี</option>
                        <option value="ศิลปะ">หนังสือศิลปะ</option>
                        <option value="การศึกษา">หนังสือการศึกษา</option>
                        <option value="หนังสือเด็ก">หนังสือเด็ก</option>
                        <option value="การเดินทาง">หนังสือการเดินทาง</option>
                        <option value="สุขภาพและการดำรงชีวิต">หนังสือสุขภาพและการดำรงชีวิต</option>
                        <option value="การเงินและธุรกิจ">หนังสือการเงินและธุรกิจ</option>
                        <option value="อ้างอิง">หนังสืออ้างอิง</option>
                        <option value="บันเทิง">หนังสือบันเทิง</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">สถานะ:</label>
                    <select id="status" name="status">
                        <option value="available">พร้อมใช้งาน</option>
                        <option value="unavailable">ไม่พร้อมใช้งาน</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">ราคา:</label>
                    <input type="number" id="price" name="price" required>
                </div>
                <div class="form-group">
                    <label for="image">เลือกรูปภาพ:</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
                <input type="hidden" name="table" value="books">
                <input type="submit" value="เพิ่มหนังสือ" class="submit-btn">
            </form>
        </div>
    </div>


</body>

</html>

<?php
$conn->close();
?>