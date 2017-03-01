<?php require_once('project.php'); ?>
<?php
$proj = NULL;
if (filter_input(INPUT_POST, 'hello') != NULL && filter_input(INPUT_POST, 'hello') != '') {
    $id = filter_input(INPUT_POST, 'hello');
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'testdb';
        
    //Create connection.
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql0 = "UPDATE project SET access_cnt = access_cnt + 1 WHERE project_id = ". $id;
    $conn->query($sql0);
    $sql = "SELECT * FROM project WHERE project_id = ". $id;
    $result = $conn->query($sql);
    $proj = $result->fetch_assoc();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<meta charset="utf-8"/>
<link rel="stylesheet" href="idxstylesheet.css?2017218">
<link rel="stylesheet" href="/lib/w3.css">
<head>
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
    <h4 style="margin-top:0.1cm;margin-bottom:-0.0cm;">Search by Date</h4>
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
  <div class="media_buttons">
    <p>
    <button class="videoButton" style="width:100px;height:50px;margin-right:1.5cm">Videos</button>
    <button class="picButton" style="width:100px;height:50px;margin-right:1.5cm;margin-left:1.5cm">Pictures</button>
    <button class="reportButton" style="width:100px;height:50px;margin-left:1.5cm">Reports</button>
  </p>
  </div>
  <div class="project_pic">
    <?php
    if ($proj != NULL) {
        echo '<img src="', $proj['pic_url'], '" alt="', $proj['pic_url'], '" /><br>';
    }
    ?>
  </div>
  <div class="project_description">
    <h2><b>Metadata: </b></h2>
    <?php
    if ($proj != NULL) {
        echo 'Name: ', $proj['name'], '<br>';
        echo 'Start date: ', $proj['start_date'], '<br>';
        echo 'Description: ', $proj['description'], '<br>';
        echo 'Total access: ', $proj['access_cnt'], '<br>';
    }
    ?>
  </div>
  <div class="recommendations">
    <p>
    <button class="recButton1" style="width:150px;height:75px;margin-right:2cm">Recommendation 1</button>
    <button class="recButton2" style="width:150px;height:75px;margin-right:2cm;margin-left:2cm">Recommendation 2</button>
    <button class="recButton3" style="width:150px;height:75px;margin-left:2cm">Recommendation 3</button>
  </p>
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
