<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 使用 Neon 的 PDO 连接
$host     = "ep-long-surf-a8qwqt6y-pooler.eastus2.azure.neon.tech";
$dbname   = "neondb";
$user     = "neondb_owner";
$pass     = "npg_wnPkGaNDKW46";

try {
    $conn = new PDO("pgsql:host=$host;port=5432;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// 查询数据
$stmt = $conn->query("SELECT * FROM inventry ORDER BY ProductID ASC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);



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

echo "<h2>Apple Inventory System</h2>";

echo "<div class='button-bar'>
        <a href='inventryMain.php'><input type='submit' value='Back to main page'></a>
        <a href='inventryform.html'><input type='submit' value='Create New Product'></a>
        <a href='stockinform.html'><input type='submit' value ='Insert Stock In'></a>
        <a href='salesform.html'><input type='submit' value='Insert New Sale'></a> |
        <a href='inventryView.php'><input type='submit' value = 'Inventry Record'></a>
        <a href='stockinView.php'><input type='submit' value='Stock In Record'></a>
        <a href='salesView.php'><input type='submit' value='Sales Record'></a>
        
      </div>";

echo "<div class='table-container'>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ProductID</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Cost (RM)</th>
                    <th>Price (RM)</th>
                    <th>Stock<br>Balance</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>";

$counter = 1;
foreach ($rows as $row) {
    echo "<tr>
            <td>".$counter."</td>
            <td>".$row['productid']."</td>
            <td>".$row['name']."</td>
            <td>".(!empty($row['image']) ? "<img src='inventryUploads/".$row['image']."' alt='Product Image'>" : "No image")."</td>
            <td>".$row['cost']."</td>
            <td>".$row['price']."</td>
            <td>".$row['quantity']."</td>
            <td>
                <a href='inventryEdit.php?productID=".$row['productid']."'><input type='submit' value='Edit'></a> |
                <a href='inventryDelete.php?productID=".$row['productid']."' onclick=\"return confirm('Are you sure you want to delete this record?');\"><input type='submit' value='Delete'></a>
            </td>
          </tr>";
    $counter++;
}

echo "      </tbody>
        </table>
      </div>";
?>