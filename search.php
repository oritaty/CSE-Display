<?php include 'includes.php'; ?>
<?php
$projectDb = new ProjectDb();
$years = $projectDb->getYears();
$departments = $projectDb->getDepartments();
$categories = $projectDb->getCategories();
$students = $projectDb->getStudents();
?>
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
    <h1 class="header">CEC Display Project</h1>
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
              echo '<option>' . $row['Name'] . ' (' . $row['MiamiId'] . ')</option>';
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
              echo '<option>' . $row['Name'] . '</option>';
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
              echo '<option>' . $row['Name'] . '</option>';
          }
          ?>
      </select>
      <hr>
      <form method="post" action="search.php" id="tab">
          <input type="submit" name="sub" value="Search" style="">
      </form>
  </div>
  <div class="searchresults">
    <!--<a href='index.php?hello=true'>Run PHP Function</a>Test-->
    <h2><b><l>Search Results: </l></b></h2>
    <?php
    $sql = "SELECT Project.Id, Project.Title, Project.Year,
                            Project.AccessCount, Project.Description, 
                            Category.Name AS CName, Department.Name AS DName, 
                            Artifact.fileName
            FROM Project JOIN Category ON Project.CategoryId = Category.Id
                         JOIN ProjectDepartment ON Project.Id = ProjectDepartment.ProjectId
                         JOIN Department ON ProjectDepartment.DepartmentId = Department.Id
                         JOIN ProjectArtifact ON Project.Id = ProjectArtifact.ProjectId
                         JOIN Artifact ON ProjectArtifact.ArtifactId = Artifact.Id
                         JOIN ArtifactType ON Artifact.TypeId = ArtifactType.Id
            WHERE ArtifactType.Name = 'IMAGE'
			GROUP BY Project.Id";
    $toBeDisplayed = false;

    if (filter_input(INPUT_POST, 'searchterm') != NULL && filter_input(INPUT_POST, 'searchterm') != '') {
        $key = filter_input(INPUT_POST, 'searchterm');
        $sql = $sql."\nAND Project.Title LIKE '%".$key."%'";
        $toBeDisplayed = true;
    } else if (isset($_POST['sub'])) {
        $year = $_POST['year'];
        $month = $_POST['month'];
        $subCategory = $_POST['subcategory'];
        $department = $_POST['department'];
        $person = $_POST['person'];

        if ($year != "All") {
            $sql = $sql."\nAND YEAR(Year) = ".$year;
        }
        if ($month != "All") {
            $sql = $sql."\nAND MONTH(Year) = ".$month;
        }
        if ($subCategory != "All") {
            $sql = $sql."\nAND Category.Name = '".$subCategory."'";
        }
        if ($department != "All") {
            $sql = $sql."\nAND Department.Name = '".$department."'";
        }
        if ($person != "All" && strpos($person, " (")) {
            //$tok = explode(" (", $person);
        }
        $toBeDisplayed = true;
    }

    if ($toBeDisplayed) {
        $result = $projectDb->getQueryResult($sql);
        if ($result != NULL) {
            while($row = $result->fetch_assoc()) {
                echo '<img src="pics/', $row['fileName'], '" alt="', $row['pic_url'], 
                        '" style="width:auto;height:180px" /><br>';
                echo '<strong>', $row['Title'], '</strong><br>';
                echo 'Department: ', $row['DName'], '<br>';
                echo 'Start date: ', $row['Year'], '<br>';
                echo 'Description: ', $row['Description'], '<br>';
                echo 'Sub-category: ', $row['CName'], '<br>';
                //echo 'Total access: ', $row['AccessCount'], '<br>';
                echo "<form action=", '"project.php"', "method=", '"post"', "><button type=", 
                        '"submit"', "name=", '"hello"', "value=", $row['Id'], " class=", 
                        '"btn-link"', ">Go see the details</button></form><br><br>";
            }
        }
    }
    $projectDb->closeConnection();
    ?>
  </div>
  <div class="bottom_space" style="height:100px"></div>
  <div class="footer">
    <h3>Check out the link below for more details about the College of Engineering and Computing!</h3>
    CEC Webpage:
    <a href="http://miamioh.edu/cec/">
      http://miamioh.edu/cec/
  </div>
</body>
</html>
