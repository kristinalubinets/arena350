<?php
session_start(); // store user information once logged-in
require("db.php");
if (empty($_SESSION["loggedin"]))
    header("location: login.php");

// fetch event metadata on initial page load
$queries = array();
// QUERY_STRING everything after " ? " in URL
// "parse"- to break a string into key value pairs
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
    // replace or assign/bind the value of the $param_event_name string variable, to the first '?' parameter
    // in the prepared statement
    $stmt->bind_param('s', $param_event_name);
    // assign $param_event_name variable to the query string 'name' parameter
    $param_event_name = $queries['name'];

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
    <link rel="stylesheet" type="text/css" href="event.css"/>
</head>

<body>
<nav class="topnav">
    <a href="/arena350/home.php">Home</a>
    <a href="/arena350/login.php">IDK</a>
    <a href="#">Profile</a>
    <a href="#">About</a>

    <div class="navbar-end">
        <a class="cart-link" href="/arena350/cart.php">
<!--            <span>1</span>-->
            <svg value=1 version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
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
                <!-- fill out $_POST global variable with event id at $_POST['event_id'] -->
                <form action="add_to_cart.php" method="post">
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
