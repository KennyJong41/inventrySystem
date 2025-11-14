<?php
include "inventryConfig.php";

$productID = $_POST['productID'] ?? null;
$quantity  = $_POST['quantity'] ?? null;

if (!$productID || !$quantity) {
    die("❌ Missing productID or quantity.");
}

try {
    $pdo->beginTransaction();

    // 检查产品是否存在
    $check = $pdo->prepare("SELECT quantity FROM inventry WHERE productid = :pid");
    $check->execute([':pid' => $productID]);
    $row = $check->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception("Product ID '{$productID}' not found in table 'inventry'.");
    }

    // 更新库存数量
    $update = $pdo->prepare("UPDATE inventry SET quantity = quantity + :qty WHERE productid = :pid");
    $update->execute([':qty' => $quantity, ':pid' => $productID]);

    // 插入入库记录
    $insert = $pdo->prepare("INSERT INTO stockin (productid, quantity) VALUES (:pid, :qty)");
    $insert->execute([':pid' => $productID, ':qty' => $quantity]);

    $pdo->commit();

    echo "✅ Stock updated successfully for product '{$productID}'.<br>";
    echo "<a href='inventryView.php'>View Inventory</a>";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "<p style='color:red;'>❌ Transaction failed: " . htmlspecialchars($e->getMessage()) . "</p>";

    // 调试输出: 显示 PostgreSQL 错误详情
    if ($e instanceof PDOException) {
        echo "<pre>SQLSTATE: " . $e->getCode() . "\n" . $e->getTraceAsString() . "</pre>";
    }
}
?>
