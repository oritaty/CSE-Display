<?php include 'sql_conn.php'; ?>
<?php
$id = NULL;
$media = NULL;
$proj = NULL;
$toBeDisplayed = false;
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'test';

//Create connection.
$conn = new mysqli($servername, $username, $password, $dbname);

if (filter_input(INPUT_POST, 'hello') != NULL && filter_input(INPUT_POST, 'hello') != '') {
    $id = filter_input(INPUT_POST, 'hello');
    $sql0 = "UPDATE project02 SET access_cnt = access_cnt + 1 WHERE project_id = ". $id;
    $conn->query($sql0);
    $toBeDisplayed = true;
} else if (isset($_POST['media']) && isset($_POST['projectid'])) {
    $id = $_POST['projectid'];
    $media = $_POST['media'];
    $toBeDisplayed = true;
} else {
    // Need error handling.
}

if ($toBeDisplayed) {
    $sql = "SELECT * FROM project02 WHERE project_id = ". $id;
    $result = $conn->query($sql);
    $proj = $result->fetch_assoc();
    $recommends = getRecommendations($id);
    $recFirst = $recommends[0];
    $recSecond = $recommends[1];
    $recThird = $recommends[2];
}
$conn->close();
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
    <form method="post" action="search.php">
      Search:
      <input type="text" name="searchterm">
      <input type="submit" value="Submit">
    </form>
  </div>
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
  <div class="media_buttons">
    <!--<p>-->
    <form action="" method="post">
        <button class="videoButton" type="submit" name="media" value="1"
            style="width:100px;height:50px;margin-right:1.5cm">Videos</button>
        <button class="picButton" type="submit" name="media" value="2"
            style="width:100px;height:50px;margin-right:1.5cm;margin-left:1.5cm">Pictures</button>
        <button class="reportButton" type="submit" name="media" value="3"
            style="width:100px;height:50px;margin-left:1.5cm">Reports</button>
            <input type="hidden" name="projectid" value="<?php echo $id ?>">
    </form>
  <!--</p>-->
  </div>
  <div class="project_pic">
    <?php
    if ($proj != NULL) {
        if ($media != NULL) {
            switch($media) {
                case 1:
                    // Video borrowed from w3 school: https://www.w3schools.com/html/html5_video.asp
                    echo '<video width="600" controls><source src="'.$proj['video_url'].
                        '"type="video/mp4"></video>';
                    break;
                case 2:
                    echo '<img src="', $proj['pic_url'], '" alt="', $proj['pic_url'], '" /><br>';
                    break;
                case 3:
                    echo '<embed src="', $proj['repo_url'], '" width="800px" height="1000px /><br>';
                    break;
                default:
                    break;
            }
        } else {
            echo '<img src="', $proj['pic_url'], '" alt="', $proj['pic_url'], '" /><br>';
        }
    }
    ?>
  </div>
  <div class="project_description">
    <h2><b>Metadata: </b></h2>
    <?php
    if ($proj != NULL) {
        echo 'Name: ', $proj['name'], '<br>';
        echo 'Department: ', $proj['department'], '<br>';
        echo 'Start date: ', $proj['start_date'], '<br>';
        echo 'Description: ', $proj['description'], '<br>';
        echo 'Sub-Category: ', $proj['sub_category'], '<br>';
        echo 'Total access: ', $proj['access_cnt'], '<br>';
    }
    ?>
  </div>
  <div class="recommendations">
    <!--<p>-->
    <h2><b>Recommended to you: </b></h2><br>
    <form action="" method="post">
        <button type="submit" class="recButton1" name="hello" value="<?php echo $recFirst ?>"
                style="width:150px;height:75px;margin-right:2cm">Recommendation 1</button>
        <button type="submit" class="recButton2" name="hello" value="<?php echo $recSecond ?>"
                style="width:150px;height:75px;margin-right:2cm;margin-left:2cm">Recommendation 2</button>
        <button type="submit" class="recButton3" name="hello" value="<?php echo $recThird ?>"
                style="width:150px;height:75px;margin-left:2cm">Recommendation 3</button>
    </form>
  <!--</p>-->
  </div>
  <br>
  <div class="bottom_space" style="height:100px"></div>
  <div class="footer">
    <h2>This is footer.</h2>
    CSE Webpage:
    <a href="http://miamioh.edu/cec/academics/departments/cse/index.html/">
      http://miamioh.edu/cec/academics/departments/cse/index.html</a>
  </div>
</body>
</html>
