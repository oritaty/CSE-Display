<?php include 'include.php'; ?>
<?php
$id = NULL;
$media = NULL;
$proj = NULL;
$pics = NULL;
$videos = NULL;
$reports = NULL;
$toBeDisplayed = false;
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ResearchDisplayDb';

//Create connection.
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection.
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (filter_input(INPUT_POST, 'hello') != NULL && filter_input(INPUT_POST, 'hello') !== '') {
    $id = filter_input(INPUT_POST, 'hello');
    $sql0 = "UPDATE Project SET AccessCount = AccessCount + 1 WHERE Id = ". $id;
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
    $sql = "SELECT Project.Id, Project.Title, Project.Year,
                   Project.AccessCount, Project.Description,
                   Category.Name AS CName, Department.Name AS DName
            FROM Project, Category, Department, ProjectDepartment
            WHERE Project.CategoryId = Category.Id AND
                  Project.Id = ProjectDepartment.ProjectId AND
                  ProjectDepartment.DepartmentId = Department.Id AND
                  Project.Id = ".$id. " LIMIT 1";
    $sqlCommon = "SELECT DISTINCT Artifact.fileName, ArtifactType.Name AS TName
                  FROM Project, Artifact, ArtifactType, ProjectArtifact
                  WHERE Project.Id = ".$id." AND
                        Project.Id = ProjectArtifact.ProjectId AND
                        ProjectArtifact.ArtifactId = Artifact.Id AND
                        Artifact.TypeId = ArtifactType.Id";
    $sqlPics = $sqlCommon."\nAND ArtifactType.Name = 'IMAGE'";
    $sqlVideos = $sqlCommon."\nAND (ArtifactType.Name = 'VIDEO_FILE' OR
                              ArtifactType.Name = 'VIDEO_LINK')";
    $sqlReports = $sqlCommon."\nAND (ArtifactType.Name = 'PDF' OR
                              ArtifactType.Name = 'POWERPOINT')";
    $result = $conn->query($sql);
    $pics = $conn->query($sqlPics);
    $videos = $conn->query($sqlVideos);
    $reports = $conn->query($sqlReports);
    $proj = $result->fetch_assoc();
    $recommends = getRecommendations($id);
    $recFirst = $recommends[0];
    $recSecond = $recommends[1];
    $recThird = $recommends[2];
}

$sql2 = "SELECT DISTINCT YEAR(YEAR) AS Year FROM Project";
$sql3 = "SELECT DISTINCT Name FROM Department";
$sql4 = "SELECT DISTINCT Name FROM Category";
$sql5 = "SELECT DISTINCT User.Name, User.MiamiId
         FROM User, UserType
         WHERE User.UserTypeId = UserType.Id AND
               User.Name <> '' AND User.Name IS NOT NULL AND
               User.MiamiId <> '' AND User.MiamiId IS NOT NULL AND
               UserType.Name <> 'Admin'";
$years = $conn->query($sql2);
$departments = $conn->query($sql3);
$categories = $conn->query($sql4);
$students = $conn->query($sql5);
$conn->close();
?>
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<link rel="stylesheet" href="/lib/w3.css">
<!-- Copyright 2017 https://github.com/kenwheeler/slick -->
<!-- Get the source files from web.
<link rel="stylesheet" type="text/css" href="http://kenwheeler.github.io/slick/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="http://kenwheeler.github.io/slick/slick/slick-theme.css"/>
<script type="text/javascript" src="http://kenwheeler.github.io/slick/slick/slick.min.js"></script>
-->
<link rel="stylesheet" type="text/css" href="slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="slick/slick-theme.css"/>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="slick/slick.js"></script>
<link rel="stylesheet" href="idxstylesheet.css?2017219">
<head>
  <title>Individual project page.</title>
</head>
<body>
  <script>
    $(document).ready(function() {
        $('#project_pics').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            draggable: false,
            dots: true,
            arrows: true,
            pauseOnHover: false,
            respondTo: 'slider'
        });
    });
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
  <div class="project_artifacts" style="text-align: center" >
    <?php
    function getArtifactType($artifact) {
        try {
            $rtn = $artifact['TName'];
            return $rtn;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit();
        }
    }

    function displayPics($pics) {
        // Every project must have at least one picture.
        assert($pics->num_rows >= 1);
        echo '<div id="project_pics">';
        while ($row = $pics->fetch_assoc()) {
            assert(getArtifactType($row) === 'IMAGE');
            echo '<div><center><img src="pics/'.$row['fileName'].'" alt="'.
                    $row['fileName'].'" style="width:450px;height:auto"/></center></div>';
        }
        echo '</div>';
    }

    // Slick slider can't handle pdf file.
    function displayReports($reports) {
        if ($reports->num_rows < 1) {
            echo '<div><h2>No contents to be displayed.<h2></div>';
            return;
        }
        while ($row = $reports->fetch_assoc()) {
            switch(getArtifactType($row)) {
                case 'PDF':
                    echo '<div><center><embed src="reports/'.$row['fileName'].
                            '" width="700px" height="800px" /></center></div>';
                    break;
                case 'POWERPOINT':
                    break;
                case 'TEXT':
                    break;
                default:
                    // Error.
                    break;
            }
        }
    }

    function displayVideos($videos) {
        if ($videos->num_rows < 1) {
            echo '<div><h2>No contents to be displayed.<h2></div>';
            return;
        }
        while ($row = $videos->fetch_assoc()) {
            switch(getArtifactType($row)) {
                case 'VIDEO_FILE':
                    echo '<div><center><video width="600" controls><source src="videos/'.
                        $row['fileName'].'"type="video/mp4"></video></center></div>';
                    break;
                case 'VIDEO_LINK':
                    /*
                    echo '<iframe src="videos/'.$row['fileName'].
                        '"width="560" height="315" frameborder="0" allowfullscreen></iframe>';
                     */
                    break;
                default:
                    // Error.
                    break;
            }
        }
    }
    ?>
    <?php
    if ($proj != NULL && $media != NULL) {
        switch($media) {
            case 1:
                displayVideos($videos);
                break;
            case 2:
                displayPics($pics);
                break;
            case 3:
                displayReports($reports);
                break;
            default:
                echo "You shouldn't reach this line.";
                exit();
                break;
        }
    } else {
        // Need to be tested.
        echo '<div id="project_pics">';
        while ($row = $pics->fetch_assoc()) {
            echo '<div><img src="pics/', $row['fileName'].'" alt="'.$row['fileName'].
                '" style="width:450px;height:auto;margin: 0 auto"/><br></div>';
        }
        echo '</div>';
    }
    ?>
  </div>
  <div class="project_description">
    <h2><b>Metadata: </b></h2>
    <?php
    if ($proj != NULL) {
        echo 'Title: ', $proj['Title'], '<br>';
        echo 'Department: ', $proj['DName'], '<br>';
        echo 'Start date: ', $proj['Year'], '<br>';
        echo 'Description: ', $proj['Description'], '<br>';
        echo 'Sub-Category: ', $proj['CName'], '<br>';
        echo 'Total access: ', $proj['AccessCount'], '<br>';
    }
    ?>
  </div>
  <div class="searchtab">
      <h4 style="margin-top:0.1cm;margin-bottom:-0.0cm;">Search by Date</h4>
      <I>Year:</I>
      <select name="year" form="tab">
          <?php
          echo '<option>All</option>';
          while ($row = $years->fetch_assoc()) {
              echo '<option>' . $row['Year'] . '</option>';
          }
          ?>
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
      <h4 style="margin-top:-0.0cm;margin-bottom:-0.0cm;">
          Search by Person</h4>
      <I>Name:</I>
      <select name="person" form="tab">
          <?php
          echo '<option>All</option>';
          while ($row = $students->fetch_assoc()) {
              echo '<option>'.$row['Name'].' ('. $row['MiamiId'].')</option>';
          }
          ?>
      </select>
      <hr>
      <h4 style="margin-top:-0.0cm;margin-bottom:-0.0cm;">
          Search by Sub-category</h4>
      <I>Category:</I>
      <select name="subcategory" form="tab">
          <?php
          echo '<option>All</option>';
          while ($row = $categories->fetch_assoc()) {
              echo '<option>'.$row['Name'].'</option>';
          }
          ?>
      </select>
      <hr>
      <h4 style="margin-top:-0.0cm;margin-bottom:-0.0cm;">
          Search by Department</h4>
      <I>Department:</I>
      <select name="department" form="tab">
          <?php
          echo '<option>All</option>';
          while ($row = $departments->fetch_assoc()) {
              echo '<option>'.$row['Name'].'</option>';
          }
          ?>
      </select>
      <hr>
      <form method="post" action="search.php" id="tab">
          <input type="submit" name="sub" value="Search" style="">
      </form>
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
