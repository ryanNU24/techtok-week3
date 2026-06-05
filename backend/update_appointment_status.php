<?php
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $request_id = $_POST["request_id"];
    $status = $_POST["status"];

    $allowed_statuses = ["pending", "confirmed", "completed", "cancelled"];

    if (!in_array($status, $allowed_statuses)) {
        die("Invalid status selected.");
    }

    $sql = "UPDATE appointment_requests SET status = ? WHERE request_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL prepare failed: " . $conn->error);
    }

    $stmt->bind_param("si", $status, $request_id);

    if ($stmt->execute()) {
        header("Location: admin_appointments.php");
        exit();
    } else {
        die("Failed to update appointment status.");
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: admin_appointments.php");
    exit();
}
?>