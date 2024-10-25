<?php $servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "library";
$conn = new
    mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    $sql = "SELECT title, author, genre, availability, cover_image FROM books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        die("ไม่พบหนังสือที่ต้องการ");
    }
} else {
    die("ไม่พบข้อมูลหนังสือ");
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ห้องสมุด - รายละเอียดหนังสือ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <style>
        .book-details {
            margin-top: 50px;
            padding: 10px;
            /* ลด padding */
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
            max-width: 350px;
            /* ปรับขนาดกรอบเล็กลง */
            margin-left: auto;
            /* ชิดซ้าย */
            margin-right: auto;
            /* จัดกรอบให้อยู่ทางซ้าย */
        }

        .book-details img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            max-width: 50%;
            /* รูปภาพจะเล็กลงตามขนาดกรอบ */
            height: auto;
        }

        .book-details h1,
        .book-details p {
            text-align: center;
            font-size: 1.1em;
            /* ลดขนาดตัวอักษร */
        }

        .book-details .button {
            display: block;
            width: 80%;
            /* ลดความกว้าง */
            text-align: center;
            margin: 5px auto;
            /* ลดระยะห่างด้านบนและล่าง */
            padding: 6px;
            /* ลด padding */
            font-size: 0.9em;
            /* ลดขนาดฟอนต์ */
        }

        .button,
        .delete-button {
            display: block;
            width: 80%;
            /* ใช้ความกว้างเท่ากัน */
            text-align: center;
            margin: 5px auto;
            /* ระยะห่างด้านบนและล่าง */
            padding: 6px;
            /* ลด padding */
            font-size: 0.9em;
            /* ลดขนาดฟอนต์ */
            border-radius: 5px;
            /* เพิ่มมุมโค้งให้ปุ่ม */
            border: none;
            /* ลบกรอบ */
            cursor: pointer;
            /* เปลี่ยนเคอร์เซอร์เมื่อชี้ที่ปุ่ม */
        }

        .delete-button {
            background-color: #dc3545;
            /* สีพื้นหลัง */
            color: white;
            /* สีตัวอักษร */
        }

        .delete-button:hover {
            background-color: #c82333;
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
        <div class="book-details">
            <h1>รายละเอียดหนังสือ: <?php echo htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <img src="<?php echo htmlspecialchars($book['cover_image'], ENT_QUOTES, 'UTF-8'); ?>"
                alt="<?php echo htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="details">
                <p><strong>ผู้แต่ง:</strong> <?php echo htmlspecialchars($book['author'], ENT_QUOTES, 'UTF-8'); ?>
                </p>
                <p><strong>ประเภท:</strong> <?php echo htmlspecialchars($book['genre'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>สถานะ:</strong>
                    <?php echo htmlspecialchars($book['availability'], ENT_QUOTES, 'UTF-8'); ?>
                </p>
                <a href="reserve.php?book_id=<?php echo $book_id; ?>" class="button">จองหนังสือ</a>
                <a href="loan.php?book_id=<?php echo $book_id; ?>" class="button">ยืมหนังสือ</a>
                <a href="purchases.php?book_id=<?php echo $book_id; ?>" class="button">ซื้อหนังสือ</a>

                <!-- Delete book form -->
                <form action="delete_book.php" method="post" style="display:inline;">
                    <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                    <button type="submit" class="delete-button"
                        onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบหนังสือ?');">ลบหนังสือ</button>
                </form>

            </div>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>