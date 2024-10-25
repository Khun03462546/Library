<?php
include("login.php");


$sql = "SELECT p.purchase_id, p.user_id, p.book_id, p.price, p.purchase_date, b.title, b.author, b.cover_image 
        FROM purchases p 
        JOIN books b ON p.book_id = b.book_id 
        WHERE p.user_id = ?";

$user_id = 1;

$stmt = $conn->prepare($sql);
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
        .button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #8B4513;
            /* เปลี่ยนเป็นสีน้ำตาล */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #A0522D;
            /* สีน้ำตาลอ่อนกว่า */
        }

        .delete-button {
            padding: 10px 15px;
            background-color: #8B4513;
            /* เปลี่ยนเป็นสีน้ำตาล */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-button:hover {
            background-color: #A0522D;
            /* สีน้ำตาลอ่อนกว่า */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: auto;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #8B4513;
            /* สีน้ำตาล */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
    <script>
        function cancelPurchase(purchaseId) {
            if (confirm("คุณแน่ใจว่าต้องการยกเลิกการซื้อ?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "cancel.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        if (xhr.responseText === "success") {
                            document.getElementById("purchase-" + purchaseId).remove();
                        } else {
                            alert("ไม่สามารถยกเลิกการซื้อได้: " + xhr.responseText);
                        }
                    } else {
                        alert("เกิดข้อผิดพลาดในการยกเลิกการซื้อ");
                    }
                };
                xhr.send("purchase_id=" + purchaseId);
            }
        }
    </script>
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

    <h1>รายการซื้อหนังสือของคุณ</h1>
    <table>
        <tr>
            <th>รูปภาพ</th>
            <th>ชื่อหนังสือ</th>
            <th>ผู้แต่ง</th>
            <th>ราคา</th>
            <th>วันที่ซื้อ</th>
            <th>การดำเนินการ</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr id='purchase-" . $row['purchase_id'] . "'>";
                echo "<td><img src='" . $row['cover_image'] . "' alt='" . $row['title'] . "'></td>";
                echo "<td>" . $row['title'] . "</td>";
                echo "<td>" . $row['author'] . "</td>";
                echo "<td>" . number_format($row['price'], 2) . " บาท</td>";
                echo "<td>" . $row['purchase_date'] . "</td>";
                echo "<td>
                        <form action='payment.php' method='post' style='display:inline;'>
                            <input type='hidden' name='purchase_id' value='" . $row['purchase_id'] . "'>
                            <button type='submit' class='button'>ชำระเงิน</button>
                        </form>
                        <button type='button' class='button' onclick='cancelPurchase(" . $row['purchase_id'] . ")'>ยกเลิก</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>ไม่มีรายการซื้อ</td></tr>";
        }
        ?>
    </table>
</body>

</html>

<?php
$conn->close();
?>