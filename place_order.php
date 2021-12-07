<?php
    session_start();
    include("db.php");

    $user_id = $_SESSION['id'];

    // update all tickets in user cart to status of PURCHASED
    if (isset($_SESSION['cart'])) {
        $tickets_to_purchase = array();
        foreach ($_SESSION['cart'] as $event_id => $ticket_ids) {
            $tickets_to_purchase = array_merge($tickets_to_purchase, $ticket_ids);
        }

        if (!empty($tickets_to_purchase)) {
            // update tickets to purchased
            $id_placeholders = str_repeat('?,', count($tickets_to_purchase) - 1) . '?';
            $sql = "UPDATE tickets
                    SET status = 'PURCHASED'
                    WHERE id IN ($id_placeholders)
                    AND tickets.status = 'CART'";
            $stmt = $conn->prepare($sql);
            $bound_types = str_repeat('s', count($tickets_to_purchase));
            $stmt->bind_param($bound_types, ...$tickets_to_purchase);
            $result = $stmt->execute();

            $sql = "UPDATE user_tickets
                    SET status = 'PURCHASED'
                    WHERE ticket_id IN ($id_placeholders)
                    AND status = 'CART'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($bound_types, ...$tickets_to_purchase);
            $result = $stmt->execute();

            $conn->close();

            // remove tickets from session cart
            $_SESSION['cart'] = array();
            header('location: order_confirmation.php');
            exit;
        } else {
            // TODO: take user to order failure page
        }
    }
