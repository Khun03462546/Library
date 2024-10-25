<?php
include("login.php");


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
        die("ไม่พบหนังสือที่ต้องการยืม");
    }
} else {
    die("ไม่พบข้อมูลหนังสือ");
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

        .container {
            text-align: center; 
            padding: 20px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            width: 90%;
            max-width: 600px;
        }
        h1 {
            color: #8B4513;
            font-size: 1.8em;
        }
        img {
            max-width: 250px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(106, 90, 205, 0.2);
            border: 2px solid #C1A278;
        }
        .confirm-button {
            padding: 15px 30px;
            font-size: 1.2em;
            background-color: #C1A278;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            color: #FFFFFF;
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        .confirm-button:hover {
            background-color: #8B4513;
            box-shadow: 0 6px 12px rgba(106, 90, 205, 0.3);
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
        <h1>ยืมหนังสือ: <?php echo htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <img src="<?php echo htmlspecialchars($book['cover_image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8'); ?>">
        <p><strong>ผู้แต่ง:</strong> <?php echo htmlspecialchars($book['author'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>ประเภท:</strong> <?php echo htmlspecialchars($book['genre'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>สถานะ:</strong> <?php echo htmlspecialchars($book['availability'], ENT_QUOTES, 'UTF-8'); ?></p>
        

        <form action="confirm_loan.php" method="post">
            <input type="hidden" name="book_id" value="<?php echo $book_id; ?>"> 
            <input type="hidden" name="user_id" value="1"> 
            <button type="submit" class="confirm-button">ยืนยันการยืม</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
