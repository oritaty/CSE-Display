<?php require_once('project.php'); ?>
<?php
//session_start();
$displayRecent = true;
$id = filter_input(INPUT_POST, 'hello');
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'testdb';

//Create connection.
$conn = new mysqli($servername, $username, $password, $dbname);
$sql0 = "SELECT * FROM project02 WHERE access_cnt = (SELECT max(access_cnt) FROM project02)";
$sql = "SELECT * FROM project02 WHERE start_date = (SELECT max(start_date) FROM project02)";
$sql2 = "SELECT * FROM project02 ORDER BY start_date DESC LIMIT 1 OFFSET 1";
$sql3 = "SELECT * FROM project02 ORDER BY start_date DESC LIMIT 1 OFFSET 2";
$sql4 = "SELECT * FROM project02 ORDER BY start_date DESC LIMIT 1 OFFSET 3";
$sql5 = "SELECT * FROM project02 ORDER BY start_date DESC LIMIT 1 OFFSET 4";
$sql6 = "SELECT * FROM project02 ORDER BY access_cnt DESC LIMIT 1 OFFSET 1";
$sql7 = "SELECT * FROM project02 ORDER BY access_cnt DESC LIMIT 1 OFFSET 2";
$sql8 = "SELECT * FROM project02 ORDER BY access_cnt DESC LIMIT 1 OFFSET 3";
$sql9 = "SELECT * FROM project02 ORDER BY access_cnt DESC LIMIT 1 OFFSET 4";
$result0 = $conn->query($sql0); // or die($conn->error);
$result = $conn->query($sql);  //or die($conn->error);
$recent2 = $conn->query($sql2);
$recent3 = $conn->query($sql3);
$recent4 = $conn->query($sql4);
$recent5 = $conn->query($sql5);
$popular2 = $conn->query($sql6);
$popular3 = $conn->query($sql7);
$popular4 = $conn->query($sql8);
$popular5 = $conn->query($sql9);

$most_popular = $result0->fetch_assoc();
$most_recent = $result->fetch_assoc();
$most_recent2 = $recent2->fetch_assoc();
$most_recent3 = $recent3->fetch_assoc();
$most_recent4 = $recent4->fetch_assoc();
$most_recent5 = $recent5->fetch_assoc();
$most_popular2 = $popular2->fetch_assoc();
$most_popular3 = $popular3->fetch_assoc();
$most_popular4 = $popular4->fetch_assoc();
$most_popular5 = $popular5->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <link rel="stylesheet" href="idxstylesheet.css?2017218">
  <link rel="stylesheet" type="text/css" href="slick/slick.css"/>
  <link rel="stylesheet" type="text/css" href="slick/slick-theme.css"/>
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
		  var displayRecent = <?php echo json_encode($displayRecent); ?>;
          alert(displayRecent);
		  if(displayRecent) {
			  <?php $displayRecent = false;?>
			  alert("false");
		  }
		  else {
			  <?php $displayRecent = true;?>
			  alert("true");
		  }
          //document.write("<?//php $displayRecent = false; ?>");
          //window.location.href = "index.php";
      }
  </script>
  <label class="switch">
    <!-- <input type="checkbox"> -->
    <input name="checkbox" onclick="myFunction()" type="checkbox" id="checkbox"/>
    <div class="slider round"></div>
  </label>
  <!--<div class="searchtab">
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
  </div>-->
  <div class="slides">
    <?php
    //if ($displayRecent) {
    //    echo '<img src="', $most_recent['pic_url'], '" alt="', $most_recent['pic_url'], '" /><br>';
    //} else {
    //    echo '<img src="', $most_popular['pic_url'], '" alt="', $most_popular['pic_url'], '" /><br>';
    //}
    ?>
  </div>
  
  <div class="pics_by_recent">
    <div><center><img src="<?php echo $most_recent['pic_url'] ?>" alt="<?php $most_recent['pic_url'] ?>" style="width:25%"/>
	<h2>Description</h2>
	<?php echo 'Name: ', $most_recent['name'], '<br>';
        echo 'Department: ', $most_recent['department'], '<br>';
        echo 'Start date: ', $most_recent['start_date'], '<br>';
        echo 'Description: ', $most_recent['description'], '<br>';
        echo 'Sub-category: ', $most_recent['sub_category'], '<br>';
        echo 'Total access: ', $most_recent['access_cnt'], '<br>';
        echo "<form action=", '"project_pg.php"', "method=", '"post"', ">
                          <button type=", '"submit"', "name=", '"hello"', "value=", $most_recent['project_id'],
                          " class=", '"btn-link"', ">Click here for more details.</button></form><br>"; ?></center></div>
    <div><center><img src="<?php echo $most_recent2['pic_url'] ?>" alt="<?php $most_recent2['pic_url'] ?>" style="width:25%"/>
	<h2>Description</h2>
	<?php echo 'Name: ', $most_recent2['name'], '<br>';
        echo 'Department: ', $most_recent2['department'], '<br>';
        echo 'Start date: ', $most_recent2['start_date'], '<br>';
        echo 'Description: ', $most_recent2['description'], '<br>';
        echo 'Sub-category: ', $most_recent2['sub_category'], '<br>';
        echo 'Total access: ', $most_recent2['access_cnt'], '<br>';
        echo "<form action=", '"project_pg.php"', "method=", '"post"', ">
                          <button type=", '"submit"', "name=", '"hello"', "value=", $most_recent2['project_id'],
                          " class=", '"btn-link"', ">Click here for more details.</button></form><br>"; ?></center></div>
    <div><center><img src="<?php echo $most_recent3['pic_url'] ?>" alt="<?php $most_recent3['pic_url'] ?>" style="width:25%"/>
	<h2>Description</h2>
	<?php echo 'Name: ', $most_recent3['name'], '<br>';
        echo 'Department: ', $most_recent3['department'], '<br>';
        echo 'Start date: ', $most_recent3['start_date'], '<br>';
        echo 'Description: ', $most_recent3['description'], '<br>';
        echo 'Sub-category: ', $most_recent3['sub_category'], '<br>';
        echo 'Total access: ', $most_recent3['access_cnt'], '<br>';
        echo "<form action=", '"project_pg.php"', "method=", '"post"', ">
                          <button type=", '"submit"', "name=", '"hello"', "value=", $most_recent3['project_id'],
                          " class=", '"btn-link"', ">Click here for more details.</button></form><br>"; ?></center></div>
	<div><center><img src="<?php echo $most_recent4['pic_url'] ?>" alt="<?php $most_recent4['pic_url'] ?>" style="width:25%"/>
	<h2>Description</h2>
	<?php echo 'Name: ', $most_recent4['name'], '<br>';
        echo 'Department: ', $most_recent4['department'], '<br>';
        echo 'Start date: ', $most_recent4['start_date'], '<br>';
        echo 'Description: ', $most_recent4['description'], '<br>';
        echo 'Sub-category: ', $most_recent4['sub_category'], '<br>';
        echo 'Total access: ', $most_recent4['access_cnt'], '<br>';
        echo "<form action=", '"project_pg.php"', "method=", '"post"', ">
                          <button type=", '"submit"', "name=", '"hello"', "value=", $most_recent4['project_id'],
                          " class=", '"btn-link"', ">Click here for more details.</button></form><br>"; ?></center></div>
	<div><center><img src="<?php echo $most_recent5['pic_url'] ?>" alt="<?php $most_recent5['pic_url'] ?>" style="width:25%"/>
	<h2>Description</h2>
	<?php echo 'Name: ', $most_recent5['name'], '<br>';
        echo 'Department: ', $most_recent5['department'], '<br>';
        echo 'Start date: ', $most_recent5['start_date'], '<br>';
        echo 'Description: ', $most_recent5['description'], '<br>';
        echo 'Sub-category: ', $most_recent5['sub_category'], '<br>';
        echo 'Total access: ', $most_recent5['access_cnt'], '<br>';
        echo "<form action=", '"project_pg.php"', "method=", '"post"', ">
                          <button type=", '"submit"', "name=", '"hello"', "value=", $most_recent5['project_id'],
                          " class=", '"btn-link"', ">Click here for more details.</button></form><br>"; ?></center></div>
  </div>
  
  <div class="pics_by_popular">
    <div><center><img src="<?php echo $most_popular['pic_url'] ?>" alt="<?php $most_popular['pic_url'] ?>" style="width:25%"/>
	<h2>Description</h2>
	<?php echo 'Name: ', $most_popular['name'], '<br>';
        echo 'Department: ', $most_popular['department'], '<br>';
        echo 'Start date: ', $most_popular['start_date'], '<br>';
        echo 'Description: ', $most_popular['description'], '<br>';
        echo 'Sub-category: ', $most_popular['sub_category'], '<br>';
        echo 'Total access: ', $most_popular['access_cnt'], '<br>';
        echo "<form action=", '"project_pg.php"', "method=", '"post"', ">
                          <button type=", '"submit"', "name=", '"hello"', "value=", $most_popular['project_id'],
                          " class=", '"btn-link"', ">Click here for more details.</button></form><br>"; ?></center></div>
    <div><center><img src="<?php echo $most_popular2['pic_url'] ?>" alt="<?php $most_popular2['pic_url'] ?>" style="width:25%"/>
	<h2>Description</h2>
	<?php echo 'Name: ', $most_popular2['name'], '<br>';
        echo 'Department: ', $most_popular2['department'], '<br>';
        echo 'Start date: ', $most_popular2['start_date'], '<br>';
        echo 'Description: ', $most_popular2['description'], '<br>';
        echo 'Sub-category: ', $most_popular2['sub_category'], '<br>';
        echo 'Total access: ', $most_popular2['access_cnt'], '<br>';
        echo "<form action=", '"project_pg.php"', "method=", '"post"', ">
                          <button type=", '"submit"', "name=", '"hello"', "value=", $most_popular2['project_id'],
                          " class=", '"btn-link"', ">Click here for more details.</button></form><br>"; ?></center></div>
    <div><center><img src="<?php echo $most_popular3['pic_url'] ?>" alt="<?php $most_popular3['pic_url'] ?>" style="width:25%"/>
	<h2>Description</h2>
	<?php echo 'Name: ', $most_popular3['name'], '<br>';
        echo 'Department: ', $most_popular3['department'], '<br>';
        echo 'Start date: ', $most_popular3['start_date'], '<br>';
        echo 'Description: ', $most_popular3['description'], '<br>';
        echo 'Sub-category: ', $most_popular3['sub_category'], '<br>';
        echo 'Total access: ', $most_popular3['access_cnt'], '<br>';
        echo "<form action=", '"project_pg.php"', "method=", '"post"', ">
                          <button type=", '"submit"', "name=", '"hello"', "value=", $most_popular3['project_id'],
                          " class=", '"btn-link"', ">Click here for more details.</button></form><br>"; ?></center></div>
	<div><center><img src="<?php echo $most_popular4['pic_url'] ?>" alt="<?php $most_popular4['pic_url'] ?>" style="width:25%"/>
	<h2>Description</h2>
	<?php echo 'Name: ', $most_popular4['name'], '<br>';
        echo 'Department: ', $most_popular4['department'], '<br>';
        echo 'Start date: ', $most_popular4['start_date'], '<br>';
        echo 'Description: ', $most_popular4['description'], '<br>';
        echo 'Sub-category: ', $most_popular4['sub_category'], '<br>';
        echo 'Total access: ', $most_popular4['access_cnt'], '<br>';
        echo "<form action=", '"project_pg.php"', "method=", '"post"', ">
                          <button type=", '"submit"', "name=", '"hello"', "value=", $most_popular4['project_id'],
                          " class=", '"btn-link"', ">Click here for more details.</button></form><br>"; ?></center></div>
	<div><center><img src="<?php echo $most_popular5['pic_url'] ?>" alt="<?php $most_popular5['pic_url'] ?>" style="width:25%"/>
	<h2>Description</h2>
	<?php echo 'Name: ', $most_popular5['name'], '<br>';
        echo 'Department: ', $most_popular5['department'], '<br>';
        echo 'Start date: ', $most_popular5['start_date'], '<br>';
        echo 'Description: ', $most_popular5['description'], '<br>';
        echo 'Sub-category: ', $most_popular5['sub_category'], '<br>';
        echo 'Total access: ', $most_popular5['access_cnt'], '<br>';
        echo "<form action=", '"project_pg.php"', "method=", '"post"', ">
                          <button type=", '"submit"', "name=", '"hello"', "value=", $most_popular5['project_id'],
                          " class=", '"btn-link"', ">Click here for more details.</button></form><br>"; ?></center></div>
  </div>
  
  <div class="description">
    <!--<h2>Description.</h2>-->
    <?php
    /*if ($displayRecent) {
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
	*/
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
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="slick/slick.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
      $('.pics_by_recent').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 5000,
	    dots: true,
		pauseOnHover: false,
		respondTo: 'slider',
      });
    });
	</script>
	<script type="text/javascript">
	$(document).ready(function(){
      $('.pics_by_popular').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 5000,
	    dots: true,
		pauseOnHover: false,
		respondTo: 'slider',
      });
    });
	</script>
  </body>
</html>
