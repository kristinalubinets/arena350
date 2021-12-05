<?php
    session_start();
    include('db.php');

    $user_id = $_SESSION['id'];
    $event_id = $_GET['event'];
    $ticket_id = $_GET['ticket'];

    // remove ticket for user in mysql
    $sql = "DELETE FROM `user_tickets`
            WHERE user_id = ?
            AND ticket_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $ticket_id);
    $stmt->execute();

    $sql = "UPDATE tickets
            SET status = 'AVAILABLE'
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $ticket_id);
    $stmt->execute();

    // remove ticket for user in session
    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$event_id])) {
        $ticket_idx = array_search($ticket_id, $_SESSION['cart'][$event_id]);
        unset($_SESSION['cart'][$event_id][$ticket_idx]);
    }

    // redirect to cart page with updated cart
    header('location: cart.php');
    exit;


