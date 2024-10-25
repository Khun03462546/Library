<?php
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "library";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the selected genre from the form
$selected_genre = isset($_GET['genre']) ? $_GET['genre'] : '';

// Prepare the SQL query based on the selected genre
if ($selected_genre) {
    $stmt = $conn->prepare("SELECT book_id, title, author, genre, availability, cover_image FROM books WHERE genre = ?");
    $stmt->bind_param("s", $selected_genre);
} else {
    $stmt = $conn->prepare("SELECT book_id, title, author, genre, availability, cover_image FROM books");
}

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
        .sidebar {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f8f9fa;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center content horizontally */
        }

        .book {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin: 10px;
            text-align: center;
        }

        .book img {
            max-width: 100%;
            height: auto;
        }
        h3
        {
            text-align: center;
            font-weight: bold;
            color: #8B4513;
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
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="addbook.php">Add</a></li>
                <li class="nav-item"><a class="nav-link" href="reservebook.php">Booking</a></li>
                <li class="nav-item"><a class="nav-link" href="loanbooks.php">Loan</a></li>
                <li class="nav-item"><a class="nav-link" href="purchasesbooks.php">Buy</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1>Library Room</h1>

        <div class="row">
            <div class="col-md-3">
                <div class="sidebar">
                    <h3>เลือกประเภท</h3>
                    <form method="GET" action="index.php"> <!-- Change to the correct path if necessary -->
                        <select name="genre" class="form-control" onchange="this.form.submit()" style="text-align: center;">
                            <option value="">ทั้งหมด</option>
                            <option value="นิยาย" <?php if ($selected_genre == 'นิยาย') echo 'selected'; ?>>นิยาย</option>
                            <option value="สารคดี" <?php if ($selected_genre == 'สารคดี') echo 'selected'; ?>>สารคดี</option>
                            <option value="ศิลปะ" <?php if ($selected_genre == 'ศิลปะ') echo 'selected'; ?>>ศิลปะ</option>
                            <option value="การศึกษา" <?php if ($selected_genre == 'การศึกษา') echo 'selected'; ?>>การศึกษา</option>
                            <option value="การเดินทาง" <?php if ($selected_genre == 'การเดินทาง') echo 'selected'; ?>>การเดินทาง</option>
                            <option value="สุขภาพและการดำรงชีวิต" <?php if ($selected_genre == 'สุขภาพและการดำรงชีวิต') echo 'selected'; ?>>สุขภาพและการดำรงชีวิต</option>
                            <option value="เงินและธุระกิจ" <?php if ($selected_genre == 'เงินและธุระกิจ') echo 'selected'; ?>>เงินและธุระกิจ</option>
                            <option value="อ้างอิง" <?php if ($selected_genre == 'อ้างอิง') echo 'selected'; ?>>อ้างอิง</option>
                            <option value="บรรเทิง" <?php if ($selected_genre == 'บรรเทิง') echo 'selected'; ?>>บรรเทิง</option>
                            <option value="หนังสือเด็ก" <?php if ($selected_genre == 'หนังสือเด็ก') echo 'selected'; ?>>หนังสือเด็ก</option>
                            <!-- Add more genres as needed -->
                        </select>
                    </form>
                </div>
            </div>

            <div class="col-md-9">
                <div class="row">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='col-md-4'>";
                            echo "<div class='book'>";
                            echo "<img src='" . $row["cover_image"] . "' alt='" . $row["title"] . "' class='img-fluid'>";
                            echo "<h2><a href='book_details.php?book_id=" . $row["book_id"] . "'>" . $row["title"] . "</a></h2>";
                            echo "<p><strong>ผู้แต่ง:</strong> " . $row["author"] . "</p>";
                            echo "<p><strong>ประเภท:</strong> " . $row["genre"] . "</p>";
                            echo "<p><strong>สถานะ:</strong> " . $row["availability"] . "</p>";
                            echo "</div></div>";
                        }
                    } else {
                        echo "<p>ไม่มีหนังสือในประเภทที่เลือก</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>
