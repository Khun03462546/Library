<?php
include("login.php");


    $user_id = 1;

    $sql = "SELECT l.loan_id, b.title, b.author, b.cover_image, l.loan_date
    FROM loans l
    JOIN books b ON l.book_id = b.book_id
    WHERE l.user_id = ?";
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

            <h1>หนังสือที่ยืม</h1>

            <?php if ($result->num_rows > 0): ?>
                <div class="book-list">
                    <?php while ($book = $result->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="<?php echo htmlspecialchars($book['cover_image'], ENT_QUOTES, 'UTF-8'); ?>"
                                    alt="<?php echo htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8'); ?>">
                                <h2 class="card-title"><?php echo htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                                <p class = "card-text"><strong>ผู้แต่ง:</strong><?php echo htmlspecialchars($book['author'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <p class = "card-text"><strong>วันที่ยืม:</strong><?php echo htmlspecialchars($book['loan_date'], ENT_QUOTES, 'UTF-8'); ?></p>

                                <form action="return_book.php" method="post" style = "display:inline;">
                                    <input type="hidden" name="loan_id" value="<?php echo $book['loan_id']; ?>">
                                    <button type="submit" class="button">คืนหนังสือ</button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-center">คุณยังไม่มีหนังสือที่ยืมในขณะนี้</p>
            <?php endif; ?>
        </div>
    </body>

    </html>

    <?php

    $stmt->close();
    $conn->close();
    ?>