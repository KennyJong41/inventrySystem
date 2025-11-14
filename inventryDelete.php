<?php
include "inventryConfig.php";

if (!isset($_GET['productID'])) {
    die("No ProductID provided.");
}

$productID = $_GET['productID'];

$stmt = $pdo->prepare("DELETE FROM inventry WHERE productid = :id");
$stmt->execute([':id' => $productID]);

header("Location: inventryView2.php");
exit();
?>
