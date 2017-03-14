<?php
$displayRecent = true;
$id = filter_input(INPUT_POST, 'hello');
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'test';

//Create connection.
$conn = new mysqli($servername, $username, $password, $dbname);
$sql0 = "SELECT * FROM project02 WHERE access_cnt = (SELECT max(access_cnt) FROM project02)";
$sql = "SELECT * FROM project02 WHERE start_date = (SELECT max(start_date) FROM project02)";
$result0 = $conn->query($sql0);
$result = $conn->query($sql);
$most_popular = $result0->fetch_assoc();
$most_recent = $result->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <link rel="stylesheet" href="idxstylesheet.css?2017218">
  <title>Prototype of prototype</title>
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
  <h6 style="margin-top:-0.5cm;margin-bottom:-0.01cm">Latest/Popular</h6>
  <script type="text/javascript">
      function myFunction() {
          alert("checked");
          //document.write("<?//php $displayRecent = false; ?>");
          //window.location.href = "index.php";
      }
  </script>
  <label class="switch">
    <!-- <input type="checkbox"> -->
    <input name="checkbox" onclick="myFunction()" type="checkbox" id="checkbox"/>
    <div class="slider round"></div>
  </label>
  <div class="searchtab">
    <h4 style="margin-top:0.1cm;margin-bottom:-0.0cm;">Search by Date</h4>
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
  <div class="slides">
    <?php
    if ($displayRecent) {
        echo '<img src="', $most_recent['pic_url'], '" alt="', $most_recent['pic_url'], '" /><br>';
    } else {
        echo '<img src="', $most_popular['pic_url'], '" alt="', $most_popular['pic_url'], '" /><br>';
    }
    ?>
  </div>
  <div class="description">
    <h2>Description.</h2>
    <?php
    if ($displayRecent) {
        echo 'Name: ', $most_recent['name'], '<br>';
        echo 'Department: ', $most_recent['department'], '<br>';
        echo 'Start date: ', $most_recent['start_date'], '<br>';
        echo 'Description: ', $most_recent['description'], '<br>';
        echo 'Sub-category: ', $most_recent['sub_category'], '<br>';
        echo 'Total access: ', $most_recent['access_cnt'], '<br>';
        echo "<form action=", '"project_pg.php"', "method=", '"post"', ">
                          <button type=", '"submit"', "name=", '"hello"', "value=", $most_recent['project_id'],
                          " class=", '"btn-link"', ">Click here for more details.</button></form><br>";
    } else {
        echo 'Name: ', $most_popular['name'], '<br>';
        echo 'Department: ', $most_popular['department'], '<br>';
        echo 'Start date: ', $most_popular['start_date'], '<br>';
        echo 'Description: ', $most_popular['description'], '<br>';
        echo 'Sub-category: ', $most_popular['sub_category'], '<br>';
        echo 'Total access: ', $most_popular['access_cnt'], '<br>';
        echo "<form action=", '"project_pg.php"', "method=", '"post"', ">
                          <button type=", '"submit"', "name=", '"hello"', "value=", $most_popular['project_id'],
                          " class=", '"btn-link"', ">Click here for more details.</button></form><br>";
    }
    ?>
    <!--(<a href="/prototype/project_pg.php">Click here for more details.</a>)-->
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
