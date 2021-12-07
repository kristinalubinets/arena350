<?php
session_start(); // store user information once logged-in
require("db.php");
if (empty($_SESSION["loggedin"]))
    header("location: login.php");

$event_id = null;

// check if 'name' (event name) exists in the query string of the url
if (!empty($_GET['name'])) {
    // prepare a SQL query that looks at the events table
    // for a event row with name equal to the event name
    $sql = 'SELECT id, name, description, image_url FROM events WHERE name = ?';
    // send the prepared statement to the MySQL database to check if the query is valid
    // prepared statements, once validated, can be reused
    $stmt = $conn->prepare($sql);
    // replace or assign/bind the value of the $param_event_name string variable, to the first '?' parameter
    // in the prepared statement
    $stmt->bind_param('s', $param_event_name);
    // assign $param_event_name variable to the query string 'name' parameter
    $param_event_name = $_GET['name'];

    $stmt->execute();
    // $result return all the rows from the query
    $result = $stmt->get_result();
    // 'fetch'/get   - turns the rows into an array where each element corresponds to an individual row
    // represented as an associative array (use string keys as colums)
    // $data - info about the event
    $data = $result->fetch_all(MYSQLI_ASSOC);
    if ($result->num_rows > 0) {
        $event_id = $data[0]['id'];
    }
}

// check if tickets for the event are available
$stmt = $conn->prepare("SELECT id FROM tickets WHERE event_id = ? AND status='AVAILABLE' LIMIT 1");
$stmt->bind_param('i', $event_id);
$stmt->execute();
$result = $stmt->get_result();
// get all of the rows from query
$row = $result->fetch_row();
if (is_null($row)) {
    echo "<div class='callout alert'>No more tickets for this event! Sorry, try again later.</div>";
}
?>

<html>
<head>
    <?php include('head.php') ?>
    <link rel="stylesheet" type="text/css" href="assets/event.css"/>
</head>
<?php include('navbar.php') ?>

<body>
<?php
if ($data) : ?>
<div class="container">
    <div class="grid-container">
        <?php foreach ($data
        as $event): ?>
        <div class="card">
            <div class="card-divider">
                <h3><?= $event['name'] ?></h3>
            </div>
            <div class="grid-x">
                <div class="card-section cell medium-6">
                    <img src="<?php echo htmlspecialchars($event['image_url']) ?>"/>
                </div>
                <div class="card-section cell medium-6">
                    <p><?= $event['description'] ?></p>
                    <!-- fill out $_POST global variable with event id at $_POST['event_id'] -->
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="event_id" value="<?=$event['id']?>">
                        <input type="submit" value="Add To Cart" class="btn">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach ?>
    <?php else: ?>
        No Event found.
    <?php endif ?>
</div>
</body>
</html>
