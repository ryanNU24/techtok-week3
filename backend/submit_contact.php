<?php
// ==========================================
// Submit Contact Message
// MediSched Barangay Healthcare Appointment System
// ==========================================

require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    if (empty($full_name) || empty($email) || empty($message)) {
        header("Location: ../medisched-website.html?error=contact_missing#contact");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../medisched-website.html?error=invalid_email#contact");
        exit();
    }

    $sql = "INSERT INTO contact_messages (full_name, email, message)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $full_name, $email, $message);

    if ($stmt->execute()) {
        header("Location: ../medisched-website.html?success=contact#contact");
        exit();
    } else {
        header("Location: ../medisched-website.html?error=contact_failed#contact");
        exit();
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: ../medisched-website.html");
    exit();
}
?>