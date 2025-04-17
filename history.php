<?php
include 'db.php';
$budgets = $conn->query("SELECT * FROM budgets ORDER BY budget_month DESC"); // Order by month (latest first)
?>
<!DOCTYPE html>
<html>
<head>
  <title>Budget History</title>
  <style>
    /* Style for a clickable list */
    ul {
      list-style-type: none;
      padding: 0;
    }

    li {
      margin: 10px 0;
    }

    a {
      text-decoration: none;
      color: #007bff;
      font-weight: bold;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <h2>Saved Budget History</h2>
  <ul>
    <?php while ($b = $budgets->fetch_assoc()): ?>
      <?php
        $month = date("F Y", strtotime($b['budget_month'])); // Format month as 'Month Year'
      ?>
      <li>
        <a href="view.php?budget_id=<?= $b['id'] ?>">
          <?= $month ?> – Income: KES <?= number_format($b['income'], 2) ?>
        </a>
      </li>
    <?php endwhile; ?>
  </ul>
</body>
</html>
