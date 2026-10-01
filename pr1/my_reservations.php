<?php
session_start();
require 'connection.php';


if (!isset($_SESSION['user_id'])) { 
header("Location: Login.html");
 exit();
 }


$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE user_id = :user_id ORDER BY check_in ASC");
$stmt->execute(['user_id' => $user_id]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Reservations</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 40px; background: #f9f9f9; }
    h2 { text-align: center; margin-bottom: 20px; }
    table { width: 80%; margin: 0 auto; border-collapse: collapse; }
    th, td { padding: 12px; border: 1px solid #ccc; text-align: center; }
    th { background: #eee; }
    button { padding: 6px 12px; border-radius: 6px; border: none; background: #ff4d4d; color: #fff; cursor: pointer; }
    button:hover { background: #d93636; }
    .logout { text-align: center; margin-top: 20px; }
  </style>
</head>
<body>
  <h2>My Reservations</h2>
  <table>
    <tr>
      <th>Apartment</th>
      <th>Check-in</th>
      <th>Check-out</th>
      <th>Guests</th>
      <th>Action</th>
    </tr>
    <?php if ($reservations): ?>
      <?php foreach ($reservations as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['apartment']) ?></td>
          <td><?= htmlspecialchars($row['check_in']) ?></td>
          <td><?= htmlspecialchars($row['check_out']) ?></td>
          <td><?= htmlspecialchars($row['guests']) ?></td>
          <td>
            <form action="cancel_reservation.php" method="POST" style="display:inline;">
              <input type="hidden" name="reservation_id" value="<?= $row['id'] ?>">
              <button type="submit">Cancel</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="5">No reservations found.</td></tr>
    <?php endif; ?>
  </table>
 <div class="logout">
  <button id="logout-btn">Logout</button>
</div>


<script>
document.getElementById("logout-btn").addEventListener("click", async function(e) {
  e.preventDefault();

  // Show confirmation dialog
  const confirmLogout = confirm("Are you sure you want to log out?");

  if (confirmLogout) {
    // User pressed OK → call logout.php
    await fetch("logout.php", { method: "POST" });

    // Redirect to homepage (or wherever you want after logout)
    window.location.href = "index.html";
  } else {
    // User pressed Cancel → do nothing
    return;
  }
});
</script>


</body>
</html>
