<?php
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: admin_product_orders.php");
    exit();
}

$order_id = $_POST["order_id"] ?? "";
$status = $_POST["status"] ?? "";

$allowed_statuses = ["pending", "confirmed", "completed", "cancelled"];

if (empty($order_id) || !in_array($status, $allowed_statuses)) {
    header("Location: admin_product_orders.php?error=invalid_update");
    exit();
}

$stmt = $conn->prepare("UPDATE product_orders SET status = ? WHERE order_id = ?");

if (!$stmt) {
    header("Location: admin_product_orders.php?error=update_failed");
    exit();
}

$stmt->bind_param("si", $status, $order_id);

$stmt->execute();

$stmt->close();
$conn->close();

header("Location: admin_product_orders.php?success=status_updated");
exit();
?>