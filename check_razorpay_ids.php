<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=chatboat', 'root', 'root');
$stmt = $pdo->query('SELECT id, name, razorpay_plan_id FROM plans');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo 'Plan ' . $row['id'] . ': ' . $row['name'] . ' => ' . ($row['razorpay_plan_id'] ?: 'NULL') . "\n";
}
