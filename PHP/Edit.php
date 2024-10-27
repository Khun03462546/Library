<?php
include("login.php");

// ตรวจสอบว่ามีการส่ง book_id มาหรือไม่
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // ดึงข้อมูลหนังสือจากฐานข้อมูล
    $sql = "SELECT * FROM books WHERE book_id = $book_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        echo "ไม่พบข้อมูลหนังสือ";
        exit;
    }
} else {
    echo "ไม่พบ book_id";
    exit;
}

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars(trim($_POST['title']));
    $author = htmlspecialchars(trim($_POST['author']));
    $status = htmlspecialchars(trim($_POST['status']));
    $genre = htmlspecialchars(trim($_POST['genre'])); // ตรวจสอบว่า genre ได้รับค่าหรือไม่
    $price = htmlspecialchars(trim($_POST['price']));
    $table = $_POST['table'];


    // เพิ่มฟิลด์อื่นๆ ที่คุณต้องการแก้ไขที่นี่

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE books SET title='$title', author='$author',status ='$status',genre = '$genre',price = '$price' WHERE book_id=$book_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: book_details.php?book_id=$book_id"); // เปลี่ยนไปยังหน้ารายละเอียดหลังการอัปเดต
        exit;
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
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
               .error,
        .success {
            margin-top: 15px;
            padding: 15px;
            border-radius: 8px;
        }

        .error {
            color: white;
            background-color: #e74c3c;
        }

        .success {
            color: white;
            background-color: #2ecc71;
        }

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
            <h2 class="add">Edit a book</h2>
            <form  method="POST" >
                <div class="form-group">
                    <label for="title">ชื่อหนังสือ:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book['title']);?>" required>
                </div>
                <div class="form-group">
                    <label for="author">ผู้เขียน:</label>
                    <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['author']);?>" required>
                </div>
                <div class="form-group">
                    <label for="genre">ประเภท:</label>
                    <select id="genre" name="genre" value="<?php echo htmlspecialchars($book['genre']);?>" required>
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
                    <select id="status" name="status"value="<?php echo htmlspecialchars($book['status']);?>" >
                        <option value="available">พร้อมใช้งาน</option>
                        <option value="unavailable">ไม่พร้อมใช้งาน</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">ราคา:</label>
                    <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($book['price']);?>" required>
                </div>
              
                <input type="hidden" name="table" value="books">
                <input type="submit" value="แก้ไขข้อมูล" class="submit-btn">
            </form>
        </div>
    </div>


</body>

</html>

<?php
$conn->close();
?>