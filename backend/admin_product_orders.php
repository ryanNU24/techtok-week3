<?php
require_once "db_connect.php";

$sql = "SELECT * FROM product_orders ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MediSched Admin | Product Orders</title>

  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f8fb;
      padding: 30px;
      color: #102033;
    }

    h1 {
      color: #0f4c81;
      margin-bottom: 20px;
    }

    .top-actions {
      display: flex;
      gap: 12px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .back-link {
      display: inline-block;
      color: #0077e6;
      text-decoration: none;
      font-weight: bold;
    }

    .panel-link {
      display: inline-block;
      color: #0f4c81;
      text-decoration: none;
      font-weight: bold;
    }

    .table-wrapper {
      width: 100%;
      overflow-x: auto;
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(15, 76, 129, 0.08);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 1200px;
    }

    th, td {
      padding: 12px;
      border: 1px solid #dce8f2;
      text-align: left;
      font-size: 14px;
      vertical-align: middle;
    }

    th {
      background: #0077e6;
      color: white;
    }

    tr:nth-child(even) {
      background: #f8fbfd;
    }

    .status {
      font-weight: bold;
      text-transform: capitalize;
    }

    .status.pending {
      color: #9a6400;
    }

    .status.confirmed {
      color: #0077e6;
    }

    .status.completed {
      color: #087466;
    }

    .status.cancelled {
      color: #b42318;
    }

    select {
      padding: 8px;
      border: 1px solid #dce8f2;
      border-radius: 8px;
    }

    button {
      padding: 8px 12px;
      background: #0077e6;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
    }

    button:hover {
      background: #005eb8;
    }

    .empty {
      text-align: center;
      padding: 24px;
      color: #64748b;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <div class="top-actions">
    <a href="../medisched-website.html" class="back-link">← Back to Website</a>
    <a href="admin_appointments.php" class="panel-link">View Appointment Requests</a>
  </div>

  <h1>MediSched Product Orders</h1>

  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Patient Name</th>
          <th>Contact</th>
          <th>Address</th>
          <th>Product</th>
          <th>Type</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Total</th>
          <th>Status</th>
          <th>Date Ordered</th>
          <th>Update Status</th>
        </tr>
      </thead>

      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row["order_id"]); ?></td>
              <td><?php echo htmlspecialchars($row["patient_name"]); ?></td>
              <td><?php echo htmlspecialchars($row["contact_number"]); ?></td>
              <td><?php echo htmlspecialchars($row["address"]); ?></td>
              <td><?php echo htmlspecialchars($row["product_name"]); ?></td>
              <td><?php echo htmlspecialchars($row["product_type"]); ?></td>
              <td><?php echo htmlspecialchars($row["quantity"]); ?></td>
              <td>₱<?php echo number_format((float)$row["price"], 2); ?></td>
              <td>₱<?php echo number_format((float)$row["total_amount"], 2); ?></td>
              <td class="status <?php echo htmlspecialchars($row["status"]); ?>">
                <?php echo htmlspecialchars($row["status"]); ?>
              </td>
              <td><?php echo htmlspecialchars($row["created_at"]); ?></td>
              <td>
                <form action="update_product_order_status.php" method="POST">
                  <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row["order_id"]); ?>">

                  <select name="status">
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
            <td colspan="12" class="empty">No product orders found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</body>
</html>

<?php
$conn->close();
?>