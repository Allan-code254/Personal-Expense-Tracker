<?php
include 'db.php';
$id = $_GET['budget_id'];

$budget = $conn->query("SELECT * FROM budgets WHERE id = $id")->fetch_assoc();
$allocs = $conn->query("SELECT * FROM allocations WHERE budget_id = $id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SmartBudget – Budget Allocation</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .container {
      width: 80%;
      margin: 0 auto;
      padding: 20px;
      text-align: center;
    }

    h2 {
      font-size: 2em;
      color: #333;
      margin-bottom: 20px;
    }

    .budget-summary {
      background: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
    }

    .budget-summary p {
      font-size: 1.2em;
      color: #555;
    }

    #budgetChart {
      width: 250px;
      height: 250px;
      margin: 20px auto;
    }

    .categories-list {
      list-style: none;
      padding: 0;
      text-align: left;
    }

    .categories-list li {
      margin: 10px 0;
      font-size: 1.1em;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .categories-list li span {
      color: #007bff;
      font-weight: bold;
    }

    .categories-list li i {
      margin-right: 10px;
      color: #007bff;
    }

    .button-container {
      margin-top: 30px;
    }

    .button-container a {
      padding: 10px 20px;
      background-color: #28a745;
      color: white;
      border-radius: 5px;
      text-decoration: none;
      font-size: 1em;
    }

    .button-container a:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Budget for <?= date("F, Y", strtotime($budget['budget_month'])) ?></h2>
    <div class="budget-summary">
      <p>Total Income: KES <?= number_format($budget['income'], 2) ?></p>
    </div>

    <!-- Pie chart -->
    <canvas id="budgetChart"></canvas>

    <div class="categories-list">
      <?php
        $labels = [];
        $data = [];
        $icons = ['💰', '🚗', '🍲', '💼', '🏠']; // Example icons for categories
        $iconIndex = 0;

        foreach ($allocs as $row) {
          echo "<li><i>{$icons[$iconIndex]}</i><span>{$row['category']}</span>: KES " . number_format($row['amount'], 2) . " ({$row['percentage']}%)</li>";
          $labels[] = $row['category'];
          $data[] = $row['amount'];
          $iconIndex++;
        }
      ?>
    </div>

    <!-- Button to go back to history -->
    <div class="button-container">
      <a href="history.php">View Budget History</a>
    </div>
  </div>

  <script>
    const ctx = document.getElementById('budgetChart').getContext('2d');
    new Chart(ctx, {
      type: 'pie',
      data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
          label: 'Budget Allocation',
          data: <?= json_encode($data) ?>,
          backgroundColor: [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
          ]
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
            labels: {
              font: {
                size: 14
              }
            }
          },
          tooltip: {
            callbacks: {
              label: function(tooltipItem) {
                return `${tooltipItem.label}: KES ${tooltipItem.raw.toFixed(2)}`;
              }
            }
          }
        }
      }
    });
  </script>
</body>
</html>
