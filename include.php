<?php
function getConnection($servername = 'localhost', $username = 'root',
        $password = '', $dbname = 'ResearchDisplayDb') {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function closeConnection($conn) {
    if (mysqli_ping($conn)) {
        $conn->close();
    }
}

function getTotalProjects() {
    $conn = getConnection();
    $sql = "SELECT COUNT(*) As Total FROM Project";
    $result = $conn->query($sql);
    assert(($result->num_rows) == 1);
    $row = $result->fetch_assoc();
    closeConnection($conn);
    return $row['Total'];
}

// project.php.
function getRecommendations($id) {
    $conn = getConnection();
    if (getTotalProjects() < 3) {
        echo "You need to have at least three project entries in the database";
        echo "<br>Number of entries: ".getTotalProjects();
        closeConnection($conn);
        exit();
    }
    $sql1 = "SELECT DISTINCT Category.Name FROM Category, Project
        WHERE Category.Id = Project.CategoryId AND Project.Id = ".$id;
    $result1 = $conn->query($sql1);
    // One category per project.
    assert(($result1->num_rows) == 1);
    $category = $result1->fetch_assoc();
    $sql2 = "SELECT DISTINCT Project.Id FROM Project, Category
             WHERE Category.Id = Project.CategoryId AND Category.Name = '".
            $category['Name']."' ORDER BY Project.AccessCount DESC LIMIT 3";
    $count = 0;
    $arr = array();
    $result2 = $conn->query($sql2);
    while ($row = $result2->fetch_assoc()) {
        array_push($arr, (int)$row['Id']);
        $count++;
    }
    if ($count == 3) {
        closeConnection($conn);
        return $arr;
    }
    $sql3 = "SELECT DISTINCT Project.Id FROM Project, Category
             WHERE Category.Id = Project.CategoryId AND Category.Name <> '".
            $category['Name']."' ORDER BY Project.AccessCount DESC LIMIT ".(3 - $count);
    $result3 = $conn->query($sql3);
    while($row = $result3->fetch_assoc()) {
        array_push($arr, (int) $row['Id']);
        $count++;
    }
    closeConnection($conn);
    if ($count < 3) {
        echo "You need to allocate more project entries into the Database.";
        exit();
    }
    return $arr;
}
