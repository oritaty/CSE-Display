<?php require_once('project.php'); ?>
<!DOCTYPE html>
<html>
<meta charset="utf-8"/>
<link rel="stylesheet" href="idxstylesheet.css?2017218">
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <title>Individual project page.</title>
</head>
<body>
  <a href="index.php"><u>Home</u></a>
  <div class="header">
    <h1 class="header">CSE Display Project</h1>
  </div>
  <div class="searchbox">
    <form method="post" action="search.php">
      Search:
      <input type="text" name="searchterm">
      <input type="submit" value="Submit">
    </form>
  </div>
  <div class="searchtab">
    <h4 style="margin-top:-0.0cm;margin-bottom:-0.0cm;">Search by Date</h4>
    <I>Year:</I>
    <select name="year" form="tab">
      <option>2017</option>
      <option>2016</option>
      <option>2015</option>
      <option>2014</option>
      <option>2013</option>
      <option>All</option>
    </select>
    <I>Month:</I>
    <select name="month" form="tab">
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
      <option>5</option>
      <option>6</option>
      <option>7</option>
      <option>8</option>
      <option>9</option>
      <option>10</option>
      <option>11</option>
      <option>12</option>
      <option>All</option>
    </select>
    <hr>
    <h4 style="margin-top:-0.0cm;margin-bottom:-0.0cm;">
        Search by Person<br>(Not available now).</h4>
    <I>Name:</I>
    <select name="person" form="">
      <option>Barack Obama</option>
      <option>Donald Trump</option>
      <option>George Bush</option>
      <option>All</option>
    </select>
    <hr>
    <h4 style="margin-top:-0.0cm;margin-bottom:-0.0cm;">
        Search by Sub-category</h4>
    <I>Category:</I>
    <select name="subcategory" form="tab">
      <option>AI</option>
      <option>CG</option>
      <option>Systems</option>
      <option>All</option>
    </select>
    <hr>
    <h4 style="margin-top:-0.0cm;margin-bottom:-0.0cm;">
        Search by Department</h4>
    <I>Department:</I>
    <select name="department" form="tab">
      <option>CSE</option>
      <option>CEC</option>
      <option>All</option>
    </select>
    <hr>
    <form method="post" action="search.php" id="tab">
        <input type="submit" name="sub" value="Search" style="">
    </form>
  </div>
  <div class="searchresults">
    <!--<a href='index.php?hello=true'>Run PHP Function</a>Test-->
    <h2><b><l>Search Result: </l></b></h2>
    <?php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'test';

    //Create connection.
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "SELECT * FROM project02";
    $toBeDisplayed = false;

    if (filter_input(INPUT_POST, 'searchterm') != NULL && filter_input(INPUT_POST, 'searchterm') != '') {
        $key = filter_input(INPUT_POST, 'searchterm');
        $sql = $sql." WHERE name LIKE '%".$key."%'";
        $toBeDisplayed = true;
    } else if (isset($_POST['sub'])) {
        $year = $_POST['year'];
        $month = $_POST['month'];
        $subCategory = $_POST['subcategory'];
        $department = $_POST['department'];

        if ($year != "All") {
            $sql = $sql." WHERE YEAR(start_date) = ".$year;
        }
        if ($month != "All") {
            if (!stristr($sql, "WHERE")) {
                $sql = $sql." WHERE MONTH(start_date) = ".$month;
            } else {
                $sql = $sql." AND MONTH(start_date) = ".$month;
            }
        }
        if ($subCategory != "All") {
            if (!stristr($sql, "WHERE")) {
                $sql = $sql." WHERE sub_category = '".$subCategory."'";
            } else {
                $sql = $sql." AND sub_category = '".$subCategory."'";
            }
        }
        if ($department != "All") {
            if (!stristr($sql, "WHERE")) {
                $sql = $sql." WHERE department = '".$department."'";
            } else {
                $sql = $sql." AND department = '".$department."'";
            }
        }
        $toBeDisplayed = true;
    }

    if ($toBeDisplayed) {
        $result = $conn->query($sql);
        if ($result != NULL) {
            while($row = $result->fetch_assoc()) {
                echo '<img src="', $row['pic_url'], '" alt="', $row['pic_url'],
                        '"style="width:180px;height:130px" /><br>';
                echo "Name: ", $row['name'], "<br>";
                echo "Department: ", $row['department'], "<br>";
                echo "Start date: ", $row['start_date'], "<br>";
                echo "Description: ", $row['description'], "<br>";
                echo "Sub-category: ", $row['sub_category'], "<br>";
                echo "Total access: ", $row['access_cnt'], "<br>";
                echo "<form action=", '"project_pg.php"', "method=", '"post"', ">
                        <button type=", '"submit"', "name=", '"hello"', "value=", $row['project_id'],
                        " class=", '"btn-link"', ">Go see the details</button></form><br><br>";
            }
        }
    }
    $conn->close();
    ?>
  </div>
  <div class="bottom_space" style="height:100px"></div>
  <div class="footer">
    <h2>This is footer.</h2>
    CSE Webpage:
    <a href="http://miamioh.edu/cec/academics/departments/cse/index.html/">
      http://miamioh.edu/cec/academics/departments/cse/index.html</a>
  </div>
</body>
</html>
