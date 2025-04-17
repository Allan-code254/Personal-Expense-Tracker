<?php
include 'db.php';

$income = $_POST['income'];
$month = $_POST['budget_month'];
$categories = $_POST['category'];
$percents = $_POST['percent'];

// Insert into budgets table
$stmt = $conn->prepare("INSERT INTO budgets (income, budget_month) VALUES (?, ?)");
$stmt->bind_param("ds", $income, $month);
$stmt->execute();
$budget_id = $stmt->insert_id;
$stmt->close();

// Insert each allocation
$stmt2 = $conn->prepare("INSERT INTO allocations (budget_id, category, percentage, amount) VALUES (?, ?, ?, ?)");
for ($i = 0; $i < count($categories); $i++) {
  $category = $categories[$i];
  $percent = $percents[$i];
  $amount = ($percent / 100) * $income;
  $stmt2->bind_param("isdd", $budget_id, $category, $percent, $amount);
  $stmt2->execute();
}
$stmt2->close();
$conn->close();

header("Location: view.php?budget_id=$budget_id");
exit;
?>
