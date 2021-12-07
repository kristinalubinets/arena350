<?php
session_start();
include("db.php");

$user_id = $_SESSION['id'];

// get all ticket ids in cart
$sql = "SELECT ticket_id FROM user_tickets
            WHERE status = 'CART'
            AND user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);

$ticket_ids = array_map(function ($row) {
    return $row['id'];
}, $rows);

// delete tickets in user cart
$sql = "DELETE FROM user_tickets
            WHERE status = 'CART'
            AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user_id);
$stmt->execute();

$id_placeholders = str_repeat('?,', count($ticket_ids) - 1) . '?';
$sql = "UPDATE tickets
                SET status = 'AVAILABLE'
                WHERE id in ($id_placeholders)";
$stmt = $conn->prepare($sql);
$bound_types = str_repeat('s', count($ticket_ids));
$stmt->bind_param($bound_types, ...$ticket_ids);
$result = $stmt->execute();

// clear session cart
$_SESSION['cart'] = array();

// redirect user back to cart page
header('location: cart.php');

