<?php
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../medisched-website.html?error=invalid_request#products");
    exit();
}

$patient_name = trim($_POST["patient_name"] ?? "");
$contact_number = trim($_POST["contact_number"] ?? "");
$address = trim($_POST["address"] ?? "");
$product_name = trim($_POST["product_name"] ?? "");
$product_type = trim($_POST["product_type"] ?? "");
$price = $_POST["price"] ?? "";
$quantity = $_POST["quantity"] ?? "";
$total_amount = $_POST["total_amount"] ?? "";

if (
    empty($patient_name) ||
    empty($contact_number) ||
    empty($address) ||
    empty($product_name) ||
    empty($product_type) ||
    $price === "" ||
    $quantity === "" ||
    $total_amount === ""
) {
    header("Location: ../medisched-website.html?error=product_missing#products");
    exit();
}

$price = floatval($price);
$quantity = intval($quantity);
$total_amount = floatval($total_amount);

if ($quantity < 1) {
    header("Location: ../medisched-website.html?error=product_missing#products");
    exit();
}

$computed_total = $price * $quantity;

$stmt = $conn->prepare("
    INSERT INTO product_orders 
    (patient_name, contact_number, address, product_name, product_type, quantity, price, total_amount, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')
");

if (!$stmt) {
    header("Location: ../medisched-website.html?error=product_failed#products");
    exit();
}

$stmt->bind_param(
    "sssssidd",
    $patient_name,
    $contact_number,
    $address,
    $product_name,
    $product_type,
    $quantity,
    $price,
    $computed_total
);

if ($stmt->execute()) {
    header("Location: ../medisched-website.html?success=product#products");
    exit();
} else {
    header("Location: ../medisched-website.html?error=product_failed#products");
    exit();
}

$stmt->close();
$conn->close();
?>