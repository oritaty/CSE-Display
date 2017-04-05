<?php
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

$sql = "SELECT * FROM project02 ORDER BY start_date DESC LIMIT 5";
$most_recent = $conn->query($sql);
// $sql1 = "SELECT * FROM project02 ORDER BY access_cnt DESC LIMIT 5";
// $most_popular = $conn->query($sql1);
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <link rel="stylesheet" href="idxstylesheet.css?2017219">
  <!-- Copyright 2017 https://github.com/kenwheeler/slick -->
  <link rel="stylesheet" type="text/css" href="slick/slick.css"/>
  <link rel="stylesheet" type="text/css" href="slick/slick-theme.css"/>
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="slick/slick.min.js"></script>
  <title>Prototype of prototype</title>
</head>
<body>
    <script>
    var counter = 1;

    function removeSlick() {
        $('#slides').slick('unslick');
    }

    function addSlick() {
        $('#slides').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 5000,
            dots: true,
            pauseOnHover: false,
            respondTo: 'slider'
        });
    }
    </script>
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
          var send;
          if (counter % 2 === 0) {
              send = 'popular';
          } else {
              send = 'recent';
          }

          var xmlhttp;
          try {
              // Chtome, Firefox, Safari etc.
              xmlhttp = new XMLHttpRequest();
          } catch(e) {
              try {
                  // IE.
                  xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
              } catch(e) {
                  try {
                      // IE.
                      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                  } catch(e) {
                      alert("Something is wrong.");
                  }
              }
          }
          // var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function(){
              if(xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                  counter++;
                  // alert(counter);
                  // alert(xmlhttp.responseText);
                  removeSlick();
                  document.getElementById('slides').innerHTML = xmlhttp.responseText;
                  addSlick();
              }
          };
          xmlhttp.open("GET", "ajax.php?req=" + send, true);
          xmlhttp.send();
      }
  </script>
  <label class="switch">
    <input name="checkbox" onclick="myFunction()" type="checkbox" id="checkbox"/>
    <div class="slider round"></div>
  </label>
  <div id="slides">
      <?php
      while ($row = $most_recent->fetch_assoc()) {
          echo '<div><center><img src="', $row['pic_url'], '" alt="', $row['pic_url'],
                  '" style="width:auto;height:350px;margin-top:1cm" /><br>';
          echo "<h2>Description:</h2>";
          echo 'Name: ', $row['name'], '<br>';
          echo 'Department: ', $row['department'], '<br>';
          echo 'Start date: ', $row['start_date'], '<br>';
          echo 'Description: ', $row['description'], '<br>';
          echo 'Sub-category: ', $row['sub_category'], '<br>';
          echo 'Total access: ', $row['access_cnt'], '<br>';
          echo "<form action=", '"project_pg.php"', " method=", '"post"', "><button type=",
                  '"submit" ', "name=", '"hello" ', "value=", $row['project_id']," class=",
                  '"btn-link"', ">Click here for more details.</button></form></center><br><br></div>";
          }
      ?>
  </div><br>
  <div class="searchtab">
    <h4 style="margin-top:0.1cm;margin-bottom:-0.0cm;">Search by Date</h4>
    <I>Year:</I>
    <select name="year" form="tab">
      <option>All</option>
      <option>2017</option>
      <option>2016</option>
      <option>2015</option>
      <option>2014</option>
      <option>2013</option>
    </select>
    <I>Month:</I>
    <select name="month" form="tab">
      <option>All</option>
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
    <!--
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
    -->
    <h4 style="margin-top:-0.0cm;margin-bottom:-0.0cm;">
        Search by Sub-category</h4>
    <I>Category:</I>
    <select name="subcategory" form="tab">
      <option>All</option>
      <option>AI</option>
      <option>CG</option>
      <option>Systems</option>
    </select>
    <hr>
    <h4 style="margin-top:-0.0cm;margin-bottom:-0.0cm;">
        Search by Department</h4>
    <I>Department:</I>
    <select name="department" form="tab">
      <option>All</option>
      <option>CSE</option>
      <option>CEC</option>
    </select>
    <hr>
    <form method="post" action="search.php" id="tab">
        <input type="submit" name="sub" value="Search" style="">
    </form>
  </div>
  <div class="description">
  </div>
  <div class="bottom_space" style="height:100px"></div>
  <div class="footer">
    <h2>This is footer.</h2>
    CSE Webpage:
    <a href="http://miamioh.edu/cec/academics/departments/cse/index.html/">
      http://miamioh.edu/cec/academics/departments/cse/index.html</a>
    </div>
    <script>
    $(document).ready(function() {
      addSlick();
    });
    </script>
  </body>
</html>
