<?php
function getConnection() {
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'test';
    // Create connection.
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection.
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function getTotalEntries() {
    $conn = getConnection();
    $sql = "SELECT count(*) as total FROM project02";
    $result = $conn->query($sql);
    assert(($result->num_rows) == 1);
    $row = $result->fetch_assoc();
    $conn->close();
    return $row['total'];
}

// index.php.
function getTopFiveByPopularity() {
    $conn = getConnection();
    if (getTotalEntries() < 5) {
        echo "You need to have at least five project entries in the database";
        $conn->close();
    }
    $sql1 = "SELECT * FROM project02 ORDER BY access_cnt DESC LIMIT 5";
    $result1 = $conn->query($sql1);
    $cnt = 0;
    $arr = array();
    while ($row1 = $result1->fetch_assoc()) {
        array_push($arr, (int)$row1['project_id']);
        $cnt++;
    }
    assert(sizeof($arr) == 5);
    $conn->close();
    return $arr;
}

// index.php.
function getTopFiveByStartDate() {
    $conn = getConnection();
    if (getTotalEntries() < 5) {
        echo "You need to have at least five project entries in the database";
        $conn->close();
    }
    $sql1 = "SELECT * FROM project02 ORDER BY start_date DESC LIMIT 5";
    $result1 = $conn->query($sql1);
    $cnt = 0;
    $arr = array();
    while ($row1 = $result1->fetch_assoc()) {
        array_push($arr, (int)$row1['project_id']);
        $cnt++;
    }
    assert(sizeof($arr) == 5);
    $conn->close();
    return $arr;
}

// project_pg.php.
function getRecommendations($id) {
    $conn = getConnection();
    if (getTotalEntries() < 3) {
        echo "You need to have at least three project entries in the database";
        $conn->close();
    }
    $sql1 = "SELECT sub_category FROM project02 WHERE project_id = ".$id;
    $result1 = $conn->query($sql1);
    assert(($result1->num_rows) == 1);
    $row1 = $result1->fetch_assoc();
    $sql2 = "SELECT project_id FROM project02 WHERE sub_category = '".$row1['sub_category'].
            "' AND project_id <> ".$id." ORDER BY access_cnt DESC LIMIT 3";
    $result2 = $conn->query($sql2);
    $cnt = 0;
    $arr = array();
    while ($row2 = $result2->fetch_assoc()) {
        array_push($arr,(int)$row2['project_id']);
        $cnt++;
        if ($cnt == 3) {
            $conn->close();
            return $arr;
        }
    }
    $sql3 = "SELECT project_id FROM project02 WHERE sub_category <> '".$row1['sub_category'].
            "' ORDER BY access_cnt DESC";
    $result3 = $conn->query($sql3);
    while($row3 = $result3->fetch_assoc()) {
        array_push($arr,(int)$row3['project_id']);
        $cnt++;
        if ($cnt == 3) {
            break;
        }
    }
    $conn->close();
    return $arr;
}
?>
<!--
<!DOCTYPE html>
<html>
    <head>
        <title>
        </title>
    </head>
    <body>
        <div>
            <?php
            /*
            $arr = getRecommendations(3);
            echo $arr[0], " ", gettype($arr[0]), "<br>";
            echo $arr[1], "<br>";
            echo $arr[2], "<br>";
            */
            ?>
        </div>
    </body>
</html>
-->
