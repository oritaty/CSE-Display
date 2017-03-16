<?php
if (isset($_REQUEST['req'])) {
    $req = $_REQUEST['req'];
    if ($req == 'popular') {
        $displayRecent = true;
    } else {
        $displayRecent = false;
    }
} else {
    $displayRecent = true;
}

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'test';

//Create connection.
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection.
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql1 = "SELECT * FROM project02 ORDER BY start_date DESC LIMIT 5";
$sql2 = "SELECT * FROM project02 ORDER BY access_cnt DESC LIMIT 5";
$most_recent = $conn->query($sql1);
$most_popular = $conn->query($sql2);
$conn->close();

$target = NULL;
if ($displayRecent) {
    $target = $most_recent;
} else {
    $target = $most_popular;
}

while ($row = $target->fetch_assoc()) {
    echo '<div><center><img src="', $row['pic_url'], '" alt="', $row['pic_url'],
            '" style="width:auto;height:350px;margin-top:1cm" /><br>';
    echo "<h2>Description:</h2>";
    echo 'Name: ', $row['name'], '<br>';
    echo 'Department: ', $row['department'], '<br>';
    echo 'Start date: ', $row['start_date'], '<br>';
    echo 'Description: ', $row['description'], '<br>';
    echo 'Sub-category: ', $row['sub_category'], '<br>';
    echo 'Total access: ', $row['access_cnt'], '<br>';
    echo "<form action=", '"project_pg.php"', "method=", '"post"', "><button type=",
            '"submit"', "name=", '"hello"', "value=", $row['project_id'], " class=",
            '"btn-link"', ">Click here for more details.</button></form></center><br><br></div>";
}
