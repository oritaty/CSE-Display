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
    <form method="post" action="/prototype/search.php">
      Search:
      <input type="text" name="searchterm">
      <input type="submit" value="Submit">
    </form>
  </div>
  <div class="searchtab">
    <h4 style="margin-top:-0.0cm;margin-bottom:-0.0cm;">Search by Date</h4>
    <I>Year:</I>
    <select name="year">
      <option>2017</option>
      <option>2016</option>
      <option>2015</option>
      <option>2014</option>
      <option>2013</option>
    </select>
    <I>Month:</I>
    <select name="month">
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
    </select>
    <hr>
    <h4 style="margin-top:-0.0cm;margin-bottom:-0.0cm;">Search by Person</h4>
    <I>Name:</I>
    <select name="person">
      <option>Barack Obama</option>
      <option>Donald Trump</option>
      <option>George Bush</option>
    </select>
    <hr>
    <h4 style="margin-top:-0.0cm;margin-bottom:-0.0cm;">Search by Sub-category</h4>
    <I>Category:</I>
    <select name="sub-cetegory">
      <option>AI</option>
      <option>CG</option>
      <option>Network</option>
    </select>
    <hr>
    <h4 style="margin-top:-0.0cm;margin-bottom:-0.0cm;">Search by Department</h4>
    <I>Department:</I>
    <select name="department">
      <option>CSE</option>
      <option>CEC</option>
    </select>
    <hr>
    <input type="submit" value="Search" style="">
  </div>
  <div class="searchresults">
    <!--<a href='index.php?hello=true'>Run PHP Function</a>Test-->
    <h2><b><l>Search Result: </l></b></h2>
    <?php
    /*
    if (filter_input(INPUT_POST, 'searchterm') != NULL && filter_input(INPUT_POST, 'searchterm') != '') {
        $key = filter_input(INPUT_POST, 'searchterm');
        for ($i = 0; $i<count($projects); $i++) {
            if (stristr($projects[$i]->getProjectName(), $key)) {
                echo '<img src="', $projects[$i]->getPicURL(), '" alt="', $projects[$i]->getPicURL(),
                        '"style="width:180px;height:130px" /><br>';
                echo "Name: ", $projects[$i]->getProjectName(), "<br>";
                echo "Start date: ", $projects[$i]->getStartDate(), "<br>";
                echo "Description: ", $projects[$i]->getProjectDescription(), "<br>";
                echo "Total access: ", $projects[$i]->getAccessCount(), "<br>";
                echo "<a href=",'"search.php?hello=true"', ">[=> Go see the details.]</a><br><br><br>";
            }
        }
    }*/
    if (filter_input(INPUT_POST, 'searchterm') != NULL && filter_input(INPUT_POST, 'searchterm') != '') {
        $key = filter_input(INPUT_POST, 'searchterm');
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'testdb';
        
        //Create connection.
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "SELECT * FROM project";
        $result = $conn->query($sql);
        
	while($row = $result->fetch_assoc()) {
            if (stristr($row['name'], $key)) {
                echo '<img src="', $row['pic_url'], '" alt="', $row['pic_url'],
                        '"style="width:180px;height:130px" /><br>';
                echo "Name: ", $row['name'], "<br>";
                echo "Start date: ", $row['start_date'], "<br>";
                echo "Description: ", $row['description'], "<br>";
                echo "Total access: ", $row['access_cnt'], "<br>";
                //echo "<a href=",'"/prototype/project_pg.php?hello="', $row['name'], "[=> Go see the details.]</a><br><br><br>";
                echo "<form action=", '"/prototype/project_pg.php"', "method=", '"post"', ">
                        <button type=", '"submit"', "name=", '"hello"', "value=", $row['project_id'], 
                        " class=", '"btn-link"', ">Go see the details</button></form><br><br>";
            }
        }
        $conn->close();
    }
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
