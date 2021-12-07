<?php
session_start();
include("db.php");

// redirect user to login if not logged-in
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

    if (!empty($tickets_to_fetch)) {
        // return unique set of tickets
        $tickets_to_fetch = array_unique($tickets_to_fetch);

        // fetch metadata for tickets in cart
        $sql = sprintf("SELECT 
            tickets.id as ticket_id,
            events.id as event_id,
            price, 
            tickets.updated as last_added_to_cart, 
            events.name as event_name, 
            image_url as event_image
            FROM tickets
            JOIN events ON events.id = tickets.event_id
            JOIN user_tickets ON user_tickets.ticket_id = tickets.id
            WHERE (tickets.id IN (%s)
            OR user_tickets.user_id = %s) 
            AND tickets.status = 'CART'",
            implode(', ', $tickets_to_fetch), $_SESSION['id']);

        $result = $conn->query($sql);

        // if query failed, display error
        if (!$result) {
            printf($conn->error);
        } else {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $tickets = $rows;
        }
        $result->close();
        $conn->close();
    }
}

// calculate total price
$total = array_reduce($tickets, function ($acc, $ticket) {
    return $acc + (float)$ticket['price'];
}, 0);
?>

<html>
<head>
    <?php include("head.php") ?>
    <link rel="stylesheet" type="text/css" href="assets/cart.css"/>

</head>
<?php include("navbar.php") ?>

<body>
<div class="container">
    <div class="grid-container">
        <h3>
            Tickets in your cart
        </h3>
        <table>
            <thead>
            <tr>
                <td>Event</td>
                <td>Price</td>
                <td>Added to Cart</td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($tickets)): ?>
                <td>There are no tickets in your cart :(</td>
            <?php else: ?>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td>
                            <a href="<?php echo htmlspecialchars("event.php?name={$ticket['event_name']}") ?>">
                                <?= $ticket['event_name'] ?>
                            </a>
                        </td>
                        <td><?= $ticket['price'] ?></td>
                        <td>
                            <?= date('g:i A, F d, Y', strtotime($ticket['last_added_to_cart'])); ?>
                        </td>
                        <td>
                            <a href="<?php echo("remove_from_cart.php?event={$ticket['event_id']}&ticket={$ticket['ticket_id']}") ?>"
                               class="close-btn small button alert" style="font-weight: 700">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
        <div class="clearfix">
            <div class="float-right">
                <div class="total">Total: <strong>$<?= number_format((float)$total, 2, '.', '') ?></strong></div>
            </div>
        </div>
        <hr>
        <?php if(!empty($tickets)) : ?>
            <div class="cart-footer grid-x">
                <div class="cell medium-8">
                    <a href="empty_cart.php" class="btn">
                        Empty Cart
                    </a>
                </div>
                <div class="cell medium-4 order-button">
                    <a href="place_order.php" class="btn">
                        Place Order
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
