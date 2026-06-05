<?php
// ==========================================
// Submit Appointment Request
// MediSched Barangay Healthcare Appointment System
// ==========================================

require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $patient_name = trim($_POST["patient_name"]);
    $age = trim($_POST["age"]);
    $contact_number = trim($_POST["contact_number"]);
    $address = trim($_POST["address"]);
    $service_type = trim($_POST["service_type"]);
    $appointment_date = trim($_POST["appointment_date"]);
    $appointment_time = trim($_POST["appointment_time"]);
    $assigned_staff = trim($_POST["assigned_staff"]);

    if (
        empty($patient_name) ||
        empty($age) ||
        empty($contact_number) ||
        empty($address) ||
        empty($service_type) ||
        empty($appointment_date) ||
        empty($appointment_time)
    ) {
        header("Location: ../medisched-website.html?error=missing_fields#appointment");
        exit();
    }

    $sql = "INSERT INTO appointment_requests 
            (patient_name, age, contact_number, address, service_type, appointment_date, appointment_time, assigned_staff)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "sissssss",
        $patient_name,
        $age,
        $contact_number,
        $address,
        $service_type,
        $appointment_date,
        $appointment_time,
        $assigned_staff
    );

    if ($stmt->execute()) {
        header("Location: ../medisched-website.html?success=appointment#appointment");
        exit();
    } else {
        header("Location: ../medisched-website.html?error=appointment_failed#appointment");
        exit();
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: ../medisched-website.html");
    exit();
}
?>