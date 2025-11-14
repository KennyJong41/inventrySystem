<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host   = "ep-long-surf-a8qwqt6y-pooler.eastus2.azure.neon.tech";
$dbname = "neondb";
$user   = "neondb_owner";
$pass   = "npg_wnPkGaNDKW46";

try {
    $conn = new PDO("pgsql:host=$host;port=5432;dbname=$dbname;sslmode=require", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // 🚀 禁用 prepare 缓存，防止 cached plan 错误
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// ✅ 查询 stockin + inventry
$sql = "SELECT s.stockinid, s.productid, i.cost, s.quantity, s.stockindate
        FROM stockin s
        LEFT JOIN inventry i ON s.productid = i.productid
        ORDER BY s.stockinid DESC";
$stmt = $conn->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 输出样式
echo "<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
    }
    h2 {
        text-align: center;
        margin-top: 20px;
    }
    .table-container {
        max-height: 750px;
        overflow-y: auto;
        margin: 20px auto;
        width: 95%;
        border: 1px solid #ccc;
        background-color: #fff;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: center;
    }
    thead th {
        position: sticky;
        top: 0;
        background-color: #eaeaea;
        z-index: 1;
    }
    img {
        max-width: 100px;
    }
    .button-bar {
        text-align: left;
        margin: 37px;
    }
    .button-bar a input {
        margin: 5px;
        padding: 8px 16px;
        cursor: pointer;
    }
</style>";

echo "<h2>Stock In Record</h2>";

echo "<div class='button-bar'>
        <a href='inventryMain.php'><input type='submit' value='Back to main page'></a>
        <a href='inventryform.html'><input type='submit' value='Create New Product'></a>
        <a href='stockinform.html'><input type='submit' value ='Insert Stock In'></a>
        <a href='salesform.html'><input type='submit' value='Insert New Sale'></a> |
        <a href='inventryView2.php'><input type='submit' value='Manage Data'></a>
        <a href='inventryView.php'><input type='submit' value = 'Inventry Rercord'></a>
        <a href='salesView.php'><input type='submit' value='Sales Record'></a>
      </div>";

echo "<div class='table-container'>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>StockIn ID</th>
                    <th>Product ID</th>
                    <th>Cost</th>
                    <th>Quantity</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>";

$counter = 1;
foreach ($rows as $row) {
    echo "<tr>
            <td>".$counter."</td>
            <td>".$row['stockinid']."</td>
            <td>".$row['productid']."</td>
            <td>".$row['cost']."</td>
            <td>".$row['quantity']."</td>
            <td>".$row['stockindate']."</td>
          </tr>";
    $counter++;
}

echo "      </tbody>
        </table>
      </div>";
?>
