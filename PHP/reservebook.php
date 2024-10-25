<?php
include("login.php");

$user_id = 1;

$sql = "SELECT r.reservation_id, b.title, b.author, b.genre, b.cover_image 
        FROM reservations r 
        JOIN books b ON r.book_id = b.book_id 
        WHERE r.user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("เกิดข้อผิดพลาดในการเตรียมคำสั่ง: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
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
        /* ทำให้การ์ดเรียงต่อกันในแนวนอน */
        .book-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
        }

        .book-list .card {
            flex: 0 1 calc(33.33% - 20px); /* แสดงการ์ด 3 ใบต่อแถว */
            margin: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
            text-align: center;
        }

        .delete-button {
            padding: 10px 15px;
            background-color: #A52A2A;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin:10px auto;
            display: block;
            width: 80%;
        }

        .delete-button:hover {
            background-color: #8B0000;
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
    <div class="container mt-5">
        <h1>หนังสือที่จอง</h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="book-list">
                <?php while ($book = $result->fetch_assoc()): ?>
                    <div class="card">
                        <img src="<?php echo $book['cover_image']; ?>" alt="<?php echo $book['title']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $book['title']; ?></h5>
                            <p class="card-text"><strong>ผู้แต่ง:</strong> <?php echo $book['author']; ?></p>
                            <p class="card-text"><strong>ประเภท:</strong> <?php echo $book['genre']; ?></p>
                            <form action="delete_reservation.php" method="post" style="display:inline;">
                                <input type="hidden" name="reservation_id"
                                    value="<?php echo htmlspecialchars($book['reservation_id']); ?>">
                                <button type="submit" class="delete-button">ลบ</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-center">ยังไม่มีการจองหนังสือ</p>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>


<?php
$stmt->close();
$conn->close();
?>