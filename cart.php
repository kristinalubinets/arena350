<?php
session_start();
include("db.php");

if (empty($_SESSION["loggedin"]))
    header("location: login.php");

$tickets = array();

// fetch ticket metadata based on session cart if exists
if (isset($_SESSION['cart'])) {
    $tickets_to_fetch = array();
    // concatenate tickets for all events together
    foreach ($_SESSION['cart'] as $event_id => $ticket_ids) {
        $tickets_to_fetch = array_merge($tickets_to_fetch, $ticket_ids);
    }
    // return unique set of tickets
    $tickets_to_fetch = array_unique($tickets_to_fetch);

    // fetch metadata for tickets in cart
    $sql = sprintf('SELECT 
            price, 
            tickets.updated as last_added_to_cart, 
            events.name as event_name, 
            image_url as event_image
            FROM tickets
            JOIN events ON events.id = tickets.event_id
            WHERE tickets.id IN (%s)',
        implode(', ', $tickets_to_fetch));

    $result = $conn->query($sql);
    if (!$result) {
        printf($conn->error);
    } else {
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $tickets = $rows;
    }
    $result->close();
    $conn->close();
} else {
    // fetch ticket metadata based on user_tickets
    $sql = 'SELECT 
            price,
            tickets.updated as last_added_to_cart,
            events.name as event_name,
            image_url as event_image
            FROM user_tickets
            JOIN tickets ON tickets.id = user_tickets.ticket_id
            JOIN events ON events.id = tickets.event_id
            WHERE user_tickets.user_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $_SESSION['id']);

    // TODO: populate cart session on login with the above query
}
$total = array_reduce($tickets, function($acc, $ticket) {
   return $acc + (float)$ticket['price'];
}, 0);
?>

<html>
<head>
    <?php include("head.php") ?>
</head>
<?php include("navbar.php") ?>

<body>
<div class="grid-container">
    <h2>
        Tickets in your cart
    </h2>
    <table>
        <thead>
        <tr>
            <td>Event</td>
            <td>Price</td>
            <td>Added to Cart</td>
        </tr>
        </thead>
        <tbody>
            <?php if (empty($tickets)): ?>
                <td>There are no tickets in your cart :(</td>
            <?php else: ?>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?=$ticket['event_name']?></td>
                    <td><?=$ticket['price']?></td>
                    <td><?=$ticket['last_added_to_cart']?></td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="clearfix" style="width: 80%">
        <div class="float-right">
            <span>Total:</span>
            <span><strong>&dollar;<?=$total?></strong></span>
        </div>
    </div>
</div>
</body>
</html>
