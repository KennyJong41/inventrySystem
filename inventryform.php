<?php
include "inventryConfig.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productID = $_POST['productID'];
    $name      = $_POST['name'];
    $cost      = $_POST['cost'];
    $price     = $_POST['price'];
    $quantity  = $_POST['quantity'];

    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $targetDir = "inventryUploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $imageName);
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO inventry (productid, name, image, cost, price, quantity)
            VALUES (:productid, :name, :image, :cost, :price, :quantity)
        ");
        $stmt->execute([
            ':productid' => $productID,
            ':name'      => $name,
            ':image'     => $imageName,
            ':cost'      => $cost,
            ':price'     => $price,
            ':quantity'  => $quantity
        ]);
        echo "✅ Product added successfully.<br>";
        echo "<a href='inventryView2.php'>View Inventory</a>";
    } catch (PDOException $e) {
        echo "❌ Error inserting record: " . $e->getMessage();
    }
}
?>
