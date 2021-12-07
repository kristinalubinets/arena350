<?php
session_start();

/*
 * Event class represents an event that users can buy tickets for
 */
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

// if the user is not logged-in, redirect them to the login page
if (empty($_SESSION["loggedin"]))
    header("location: login.php");
require("db.php");

// fetch all the events
$sql = 'SELECT name, date FROM events ORDER BY date DESC LIMIT 5';

$result = $conn->query($sql);
$events = array();
foreach ($result as $row) {
    array_push($events, new Event($row["name"], $row["date"]));
}
?>

<html>
<head>
    <?php include('head.php') ?>
</head>
<body>
<?php include('navbar.php') ?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide">

<div class="container">


    <div class="grid-y">
        <div class="cell medium-6 large-4">
            <div class="container2">
                <img src="./assets/images/ballfixed.png">
                <div class="text-block">
                    <h2>The Sports Arena  350</h2>
                </div>
            </div>
        </div>


    <div class="grid-container">
        <div>
            <h3>
                Welcome back to the Arena,
                <?php echo htmlspecialchars($_SESSION["username"]); ?>
            </h3>
        </div>


            <div class="cell medium-6">
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
                                <td><?= date('g:i A, F d, Y', strtotime($event->date)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            </divu>
        </div>
</div>

</body>
</html>
