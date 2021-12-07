<?php
session_start();
include("db.php");

// fetch all purchased tickets for logged-in user
$sql = "SELECT 
            events.name as event_name,
            tickets.price as ticket_price,
            tickets.updated as ordered_date
            FROM user_tickets
            JOIN tickets ON user_tickets.ticket_id = tickets.id
            JOIN events ON tickets.event_id = events.id
            WHERE user_tickets.user_id = ?
            AND tickets.status = 'PURCHASED'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);
?>

<html>
<head>
    <?php include("head.php") ?>
</head>
<?php include("navbar.php") ?>
<body>

<div class="container">
    <div class="grid-container">
        <h3>Previous Orders</h3>

        <?php if ($rows) : ?>
            <table>
                <thead>
                <tr>
                    <td>Event</td>
                    <td>Price</td>
                    <td>Ordered Date</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $ticket) : ?>
                    <tr>
                        <td><?= $ticket['event_name'] ?></td>
                        <td>$<?= (float)$ticket['ticket_price'] ?></td>
                        <td>
                            <?= date('g:i A, F d, Y', strtotime($ticket['ordered_date'])); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            No past orders found.
        <?php endif ?>
    </div>
</div>
</body>
</html>
