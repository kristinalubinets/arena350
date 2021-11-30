<?php

class Event
{
    public $name;
    public $date;

    /**
     * @param $name

     * @param $date
     */
    public function __construct($name, $date)
    {
        $this->name = $name;
        $this->date = $date;
    }
}

session_start(); // access the session
if (empty($_SESSION["loggedin"]))
    header("location: login.php");
require("db.php");

$sql = 'SELECT name, date FROM events ORDER BY date DESC LIMIT 5';

$result = $conn->query($sql);
$events = array();
foreach ($result as $row) {
    array_push($events, new Event($row["name"], $row["date"]));
}
$conn->close();
?>

<html>
<?php include('head.php') ?>
<body>
<nav class="topnav">
    <a class="active" href="/">Home</a>
    <a href="/arena350/login.php">IDK</a>
    <a href="#">Profile</a>
    <a href="#">About</a>
    <?php if (!empty($_SESSION["loggedin"])) : ?>
        <a href="logout.php" class="btn logout">Sing Out</a>
    <?php endif; ?>
</nav>
<div>
    <h2>
        Welcome back to the Arena,
        <?php echo htmlspecialchars($_SESSION["username"]); ?>
    </h2>
</div>

<table>
    <thead>
    <tr>
        <th width="200">Event</th>
        <th width="150">Date</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($events)) : ?>
        <tr>
            <td>No events found</td>
        </tr>
    <?php else: ?>
        <?php foreach ($events as $event): ?>
            <tr>
                <td><a href="<?php echo htmlspecialchars("event.php?name=$event->name")?>"><?= $event->name; ?></a></td>
                <td><?= date('h:iA F d, Y', strtotime($event->date)); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
