<?php
include "inventryConfig.php";

if (!isset($_GET['productID'])) {
    die("No ProductID provided.");
}

$productID = $_GET['productID'];

// 更新逻辑
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['Name'];
    $cost     = $_POST['Cost'];
    $price    = $_POST['Price'];
    $quantity = $_POST['Quantity'];
    $image    = $_POST['Image'];

    $stmt = $pdo->prepare("
        UPDATE inventry
        SET name = :name, cost = :cost, price = :price, quantity = :quantity, image = :image
        WHERE productid = :productid
    ");
    $stmt->execute([
        ':name' => $name,
        ':cost' => $cost,
        ':price' => $price,
        ':quantity' => $quantity,
        ':image' => $image,
        ':productid' => $productID
    ]);

    header("Location: inventryView2.php");
    exit();
}

// 读取原数据
$stmt = $pdo->prepare("SELECT * FROM inventry WHERE productid = :id");
$stmt->execute([':id' => $productID]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) die("Record not found.");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Inventory</title>
</head>
<body>
<h2>Edit Product</h2>
<form method="post">
    Product ID: <input type="text" name="ProductID" value="<?php echo htmlspecialchars($row['productid']); ?>" readonly><br><br>
    Name: <input type="text" name="Name" value="<?php echo htmlspecialchars($row['name']); ?>"><br><br>
    Image: <input type="text" name="Image" value="<?php echo htmlspecialchars($row['image']); ?>"><br><br>
    Cost: <input type="text" name="Cost" value="<?php echo htmlspecialchars($row['cost']); ?>"><br><br>
    Price: <input type="text" name="Price" value="<?php echo htmlspecialchars($row['price']); ?>"><br><br>
    Quantity: <input type="number" name="Quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>"><br><br>
    <input type="submit" value="Update">
</form>
</body>
</html>
