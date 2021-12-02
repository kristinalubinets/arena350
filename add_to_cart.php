<?php
session_start(); // store user information once logged-in
require("db.php");

if (empty($_SESSION["loggedin"]))
    header("location: login.php");

// submit add to cart
// check for event id of tickets
if (isset($_POST['event_id']) && !is_null($_POST['event_id']) && !isset($_POST['ticket_id'])) {
    $stmt = $conn->prepare("SELECT id FROM tickets WHERE event_id = ? AND status='AVAILABLE' LIMIT 1");

    $stmt->bind_param('i', $_POST['event_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    // get all of the rows from query
    $row = $result->fetch_row();
    // $row[0] because we gonna get the number of rows by 'count(*)' in query
    $ticket_id = $row[0];

    // check that the amount of tickets > 0, the user allowed to get only one ticket
    if ($result->num_rows > 0) {
        $user_id = $_SESSION['id'];

        // associate the user with the added ticket
        $stmt = $conn->prepare("INSERT INTO user_tickets (`user_id`, `ticket_id`, `status`) VALUES (?, ?, 'CART')");

        if ($stmt) {
            // assign ticket to the user with status CART
            $stmt->bind_param('ii', $user_id, $ticket_id);
            $stmt->execute();
            $sql = "UPDATE tickets SET tickets.status = 'CART' WHERE tickets.id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $ticket_id);
            $stmt->execute();
        } else {
            printf($conn->error);
            return;
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        if (!isset($_SESSION['cart'][$_POST['event_id']])) {
            $_SESSION['cart'][$_POST['event_id']] = array();
        }
        // make an associative array with a key as the event_id
        // with the values as another array of the ticket_id for that event in the $_SESSION
        array_push($_SESSION['cart'][$_POST['event_id']], $ticket_id);
    }
    // redirect to cart page with cart items
    header("location: cart.php");
    exit;
}
