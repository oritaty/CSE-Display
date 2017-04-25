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
<!-- HEADER -->
<div class="header">
    <div class="logo-container">
        <img id="miami-logo" src="img/siteLogo.png"/>
    </div>

    <div class="title-container">
        <h1 class="title">College of Engineering and Computing</h1>
        <div class="subtitle">Senior Capstone Projects</div>
    </div>
</div>


<div class="main">
    <div class="main-content">
        <div class="search-container">
            <form class="search-form" method="post" action="search.php">
                <span>Search:</span>
                <input type="text" name="search-term">
                <input id="search-submit" class="white-button" type="submit" value="Submit">
            </form>
        </div>


        <div class="advanced-search">
            <div class="advanced-search-label"><strong>Advanced Search</strong></div>
            <ul class="advanced-search-options">

                <li class="option">
                    <span>Year:</span>
                    <select name="year" form="advanced-search-submit">
                        <?php
                        echo '<option>All</option>';
                        while ($row = $years->fetch_assoc()) {
                            echo '<option>' . $row['Year'] . '</option>';
                        }
                        ?>
                    </select>
                </li>
                <li class="option">
                    <span>Student:</span>
                    <select name="person" form="advanced-search-submit">
                        <?php
                        echo '<option>All</option>';
                        while ($row = $students->fetch_assoc()) {
                            echo '<option>' . $row['Name'] . '</option>';
                        }
                        ?>
                    </select>
                </li>
                <li class="option">
                    <span>Department:</span>
                    <select name="department" form="advanced-search-submit">
                        <?php
                        echo '<option>All</option>';
                        while ($row = $departments->fetch_assoc()) {
                            echo '<option>' . $row['Name'] . '</option>';
                        }
                        ?>
                    </select>
                </li>
                <li class="option">
                    <span>Category:</span>
                    <select name="category" form="advanced-search-submit">
                        <?php
                        echo '<option>All</option>';
                        while ($row = $categories->fetch_assoc()) {
                            echo '<option>' . $row['Name'] . '</option>';
                        }
                        ?>
                    </select>
                </li>
            </ul>
            <div class="advanced-search-submit-container">
                <form method="post" action="search.php" id="advanced-search-submit">
                    <input class="white-button" id="advanced-search-submit-btn" type="submit" name="sub" value="Submit">
                </form>
            </div>
        </div>

        <div class="spacer"></div>
        <div class="searchresults">
            <!--<a href='index.php?hello=true'>Run PHP Function</a>Test-->
            <h2><strong>
                    <span class="search-results-title">Search Results</span>
                </strong></h2>
            <?php
            $sql = "SELECT Project.Id, Project.Title, Project.Year, Project.AccessCount, 
                   Project.Description, Category.Name AS CName, 
                   Department.Name AS DName, Artifact.fileName, 
                   User.Name, User.MiamiId
            FROM Project JOIN Category ON Project.CategoryId = Category.Id
                         JOIN ProjectDepartment ON Project.Id = ProjectDepartment.ProjectId
                         JOIN Department ON ProjectDepartment.DepartmentId = Department.Id
                         JOIN ProjectArtifact ON Project.Id = ProjectArtifact.ProjectId
                         JOIN Artifact ON ProjectArtifact.ArtifactId = Artifact.Id
                         JOIN ArtifactType ON Artifact.TypeId = ArtifactType.Id
                         JOIN ProjectStudent ON ProjectStudent.ProjectId = Project.Id
                         JOIN User ON User.Id = ProjectStudent.StudentId
            WHERE ArtifactType.Name = 'IMAGE'";
            $toBeDisplayed = false;

            if (filter_input(INPUT_POST, 'searchterm') != NULL && filter_input(INPUT_POST, 'searchterm') != '') {
                $key = filter_input(INPUT_POST, 'searchterm');
                $sql = $sql . "\nAND Project.Title LIKE '%" . $key . "%'";
                $toBeDisplayed = true;
            } else if (isset($_POST['sub'])) {
                $year = $_POST['year'];
                $subCategory = $_POST['category'];
                $department = $_POST['department'];
                $person = $_POST['person'];

                if ($year != "All") {
                    $sql = $sql . "\nAND YEAR(Year) = " . $year;
                }
                if ($subCategory != "All") {
                    $sql = $sql . "\nAND Category.Name = '" . $subCategory . "'";
                }
                if ($department != "All") {
                    $sql = $sql . "\nAND Department.Name = '" . $department . "'";
                }
                if ($person != "All" && strpos($person, " (")) {
                    // Format: "Name (UniqueId)"
                    $tokens = explode(" (", $person);
                    $name = $tokens[0];
                    $uniqueId = substr($tokens[1], 0, -1);
                    $sql .= "\nAND (User.Name = '" . $name .
                        "' OR User.MiamiId = '" . $uniqueId . "')";
                }
                $toBeDisplayed = true;
            }

            if ($toBeDisplayed) {
                $sql .= "\nGROUP BY Project.Id";
                $result = $projectDb->getQueryResult($sql);
                if ($result != NULL) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<img class="search-result-image" src="pics/', $row['fileName'], '" alt=""'
                                . 'style="width:auto;height:180px"/>';
                        echo '<br>';






                        echo '<strong>', $row['Title'], '</strong><br>';
                        echo 'Department: ', $row['DName'], '<br>';
                        echo 'Start date: ', $row['Year'], '<br>';
                        echo 'Description: ', $row['Description'], '<br>';
                        echo 'Sub-category: ', $row['CName'], '<br>';
                        //echo 'Total access: ', $row['AccessCount'], '<br>';
                        echo "<form action=", '"project.php"', "method=", '"post"', "><button class='white-button' type=",
                        '"submit"', "name=", '"hello"', "value=", $row['Id'], " class=",
                        '"btn-link"', ">Go to Project Page</button></form><br><br>";
                    }
                }
            }
            $projectDb->closeConnection();
            ?>
        </div>
        <div class="spacer"></div>
    </div>
    <div class="footer">
        <div>Check out the <a href="http://miamioh.edu/cec/">CEC Webpage</a> for more details about the College of Engineering and Computing!</div>

    </div>
</div>
</body>
</html>
