<?php
session_start(); // store user information once logged-in
require("db.php");

// fetch event metadata on initial page load
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
$event_id = null;

// check if 'name' (event name) exists in the query string of the url
if (!empty($queries['name'])) {
    // prepare a SQL query that looks at the events table
    // for a event row with name equal to the event name
    $sql = 'SELECT id, name, description, image_url FROM events WHERE name = ?';
    // send the prepared statement to the MySQL database to check if the query is valid
    // prepared statements, once validated, can be reused
    $stmt = $conn->prepare($sql);
    // replace or assign the value of the $param_event_name string variable, to the first '?' parameter
    // in the prepared statement
    $stmt->bind_param('s', $param_event_name);
    // assign $param_event_name variable to the query string 'name' parameter
    $param_event_name = $queries['name'];

    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    if ($result->num_rows > 0) {
        $event_id = $data[0]['id'];
    }

}

// submit add to cart
// check for event id of tickets
if (isset($_POST['event_id']) && !is_null($_POST['event_id'])) {
    $stmt = $conn->prepare('SELECT count(*) FROM tickets WHERE event_id = ?');
    $stmt->bind_param('i', $_POST['event_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_row();

    $ticket_quantity = $row[0];

    // check that the amount of tickets > 0
    if ($ticket_quantity > 0) {
        $user_id = $_SESSION['id'];
        $conn->prepare('INSERT INTO user_tickets');
    }


    // assign ticket to the user with status CART
    // make an index array with a key as the event_id
    // with the values as another array of the ticket_id for that event in the $_SESSION

}
?>

<html>
<?php include('head.php') ?>

<body>
<nav class="topnav">
    <a href="/">Home</a>
    <a href="/login.php">IDK</a>
    <a href="#">Profile</a>
    <a href="#">About</a>

    <div class="navbar-end">
        <a>
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                 x="0px" y="0px"
                 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
    <g>
        <path d="M435.892,124.541H332.108V76.108C332.108,34.142,297.966,0,256,0s-76.108,34.142-76.108,76.108v48.432H76.108L62.27,512
			H449.73L435.892,124.541z M221.405,76.108c0-19.075,15.519-34.595,34.595-34.595c19.076,0,34.595,15.519,34.595,34.595v48.432
			h-69.189V76.108z M336.561,320.736L256,401.297l-80.561-80.561c-16.392-16.392-16.392-42.969,0-59.36
			c16.392-16.392,42.968-16.392,59.36,0l21.201,21.2l21.201-21.201c16.392-16.392,42.969-16.392,59.36,0
			C352.953,277.767,352.953,304.344,336.561,320.736z"/>
    </g>
</g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
                <g>
                </g>
</svg>
            </svg>
        </a>
        <?php if (!empty($_SESSION["loggedin"])) : ?>
            <a href="logout.php" class="logout">Logout</a>
        <?php endif; ?>
    </div>
</nav>
<?php
if ($data) : ?>
    <div class="container">
    <?php foreach ($data as $row): ?>
        <div class="card">
            <div class="card-divider">
                <h3><?= $row['name'] ?></h3>
            </div>
            <div class="card-section">
                <img src="<?php echo htmlspecialchars($row['image_url']) ?>"/>
            </div>
            <div class="card-section">
                <p><?= $row['description'] ?></p>
                <form method="post">
                   <input type="hidden" name="ticket_id" value="<?=$row['id']?>">
                    <input type="hidden" name="event_id" value="<?=$event_id ?>">
                   <input type="submit" value="Add To Cart" class="btn">
                </form>
            </div>
        </div>
        </div>
    <?php endforeach ?>
<?php else: ?>
    No Event found.
<?php endif ?>
</body>
</html>
