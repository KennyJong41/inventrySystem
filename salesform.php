<?php
include "inventryConfig.php";

$productID = $_POST['productID'] ?? null;
$quantity  = $_POST['quantity'] ?? null;

if (!$productID || !$quantity) {
    die("❌ Missing product ID or quantity.");
}

try {
    // 开启事务
    $pdo->beginTransaction();

    // 查询库存
    $checkStock = $pdo->prepare("SELECT quantity, price FROM inventry WHERE productid = :pid");
    $checkStock->execute([':pid' => $productID]);
    $stockResult = $checkStock->fetch(PDO::FETCH_ASSOC);

    if (!$stockResult) {
        throw new Exception("Product not found in inventory: {$productID}");
    }

    $currentStock = $stockResult['quantity'];
    $price        = $stockResult['price'];

    if ($currentStock < $quantity) {
        throw new Exception("Not enough stock for product '{$productID}'. Current stock: {$currentStock}");
    }

    // 计算总价
    $total = $price * $quantity;

    // 插入销售记录
    $stmt = $pdo->prepare("INSERT INTO sales (productid, quantity, total) VALUES (:pid, :qty, :total)");
    $stmt->execute([':pid' => $productID, ':qty' => $quantity, ':total' => $total]);

    // 更新库存
    $updateStock = $pdo->prepare("UPDATE inventry SET quantity = quantity - :qty WHERE productid = :pid");
    $updateStock->execute([':pid' => $productID, ':qty' => $quantity]);

    // 提交事务
    $pdo->commit();

    echo "✅ Sale recorded successfully for product '{$productID}'.<br>";
    echo "<a href='salesView.php'>View Sales</a>";

} catch (Exception $e) {
    // 回滚事务
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "<p style='color:red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";

    // 输出调试信息
    if ($e instanceof PDOException) {
        echo "<pre>SQLSTATE: " . $e->getCode() . "\n" . $e->getTraceAsString() . "</pre>";
    }
}
?>
