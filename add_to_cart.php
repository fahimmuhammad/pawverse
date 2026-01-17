<?php
session_start();
header('Content-Type: application/json');

if (!isset($_POST['product_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$productId = (int) $_POST['product_id'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + 1;

echo json_encode([
    'success'   => true,
    'cartCount'=> array_sum($_SESSION['cart'])
]);
