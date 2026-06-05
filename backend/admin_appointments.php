<?php
require_once "db_connect.php";

$sql = "SELECT * FROM appointment_requests ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MediSched Admin | Appointments</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f8fb;
      padding: 30px;
      color: #102033;
    }

    h1 {
      color: #0f4c81;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 10px 25px rgba(15, 76, 129, 0.08);
    }

    th, td {
      padding: 12px;
      border: 1px solid #dce8f2;
      text-align: left;
      font-size: 14px;
    }

    th {
      background: #0077e6;
      color: white;
    }

    .status {
      font-weight: bold;
      text-transform: capitalize;
    }

    select, button {
      padding: 8px;
      border-radius: 6px;
      border: 1px solid #dce8f2;
    }

    button {
      background: #0077e6;
      color: white;
      cursor: pointer;
      border: none;
    }

    button:hover {
      background: #005eb8;
    }

    .back-link {
      display: inline-block;
      margin-bottom: 20px;
      color: #0077e6;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <a href="../medisched-website.html" class="back-link">← Back to Website</a>

  <h1>MediSched Appointment Requests</h1>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Patient Name</th>
        <th>Age</th>
        <th>Contact</th>
        <th>Address</th>
        <th>Service</th>
        <th>Date</th>
        <th>Time</th>
        <th>Assigned Staff</th>
        <th>Status</th>
        <th>Update Status</th>
      </tr>
    </thead>

    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row["request_id"]); ?></td>
            <td><?php echo htmlspecialchars($row["patient_name"]); ?></td>
            <td><?php echo htmlspecialchars($row["age"]); ?></td>
            <td><?php echo htmlspecialchars($row["contact_number"]); ?></td>
            <td><?php echo htmlspecialchars($row["address"]); ?></td>
            <td><?php echo htmlspecialchars($row["service_type"]); ?></td>
            <td><?php echo htmlspecialchars($row["appointment_date"]); ?></td>
            <td><?php echo htmlspecialchars($row["appointment_time"]); ?></td>
            <td><?php echo htmlspecialchars($row["assigned_staff"]); ?></td>
            <td class="status"><?php echo htmlspecialchars($row["status"]); ?></td>
            <td>
              <form action="update_appointment_status.php" method="POST">
                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($row["request_id"]); ?>">

                <select name="status" required>
                  <option value="pending" <?php if ($row["status"] === "pending") echo "selected"; ?>>Pending</option>
                  <option value="confirmed" <?php if ($row["status"] === "confirmed") echo "selected"; ?>>Confirmed</option>
                  <option value="completed" <?php if ($row["status"] === "completed") echo "selected"; ?>>Completed</option>
                  <option value="cancelled" <?php if ($row["status"] === "cancelled") echo "selected"; ?>>Cancelled</option>
                </select>

                <button type="submit">Save</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="11">No appointment requests found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

</body>
</html>

<?php
$conn->close();
?>