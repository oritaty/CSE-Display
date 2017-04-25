<?php include 'includes.php'; ?>
<?php

$id = NULL;
$media = NULL;
$proj = NULL;
$pics = NULL;
$videos = NULL;
$reports = NULL;
$toBeDisplayed = false;
$projectDb = new ProjectDb();
$years = $projectDb->getYears();
$departments = $projectDb->getDepartments();
$categories = $projectDb->getCategories();
$students = $projectDb->getStudents();

if (isset($_POST['hello']) && $_POST['hello'] !== '') {
    $id = filter_input(INPUT_POST, 'hello');
    $sql0 = "UPDATE Project SET AccessCount = AccessCount + 1 WHERE Id = " . $id;
    $projectDb->getQueryResult($sql0);
    $toBeDisplayed = true;
} else if (isset($_POST['media']) && isset($_POST['projectid'])) {
    $id = $_POST['projectid'];
    $media = $_POST['media'];
    $toBeDisplayed = true;
} else { //display newest project
    /*
    $query = $projectDb->getQueryResult("SELECT * FROM Project ORDER BY Project.Id DESC");
    $row= $query->fetch_assoc();
    $id = $row["Id"];
    $sql0 = "UPDATE Project SET AccessCount = AccessCount + 1 WHERE Id = ". $id;
    $projectDb->getQueryResult($sql0);
    $toBeDisplayed = true;
    */
    $projectDb->closeConnection();
    header('Location: index.php');
    exit();
}

if ($toBeDisplayed) {
    $sql = "SELECT Project.Id, Project.Title, Project.Year,
                   Project.AccessCount, Project.Description, 
                   Category.Name AS CName, Department.Name AS DName 
            FROM Project, Category, Department, ProjectDepartment
            WHERE Project.CategoryId = Category.Id AND 
                  Project.Id = ProjectDepartment.ProjectId AND 
                  ProjectDepartment.DepartmentId = Department.Id AND 
                  Project.Id = " . $id . " LIMIT 1";
    $sqlCommon = "SELECT DISTINCT Artifact.fileName, Artifact.Link, 
                         ArtifactType.Name AS TName
                  FROM Project, Artifact, ArtifactType, ProjectArtifact
                  WHERE Project.Id = " . $id . " AND
                        Project.Id = ProjectArtifact.ProjectId AND
                        ProjectArtifact.ArtifactId = Artifact.Id AND
                        Artifact.TypeId = ArtifactType.Id";
    $sqlPics = $sqlCommon . "\nAND ArtifactType.Name = 'IMAGE'"; // No need to use GROUP BY here.
    $sqlVideos = $sqlCommon . "\nAND (ArtifactType.Name = 'VIDEO_FILE' OR
                              ArtifactType.Name = 'VIDEO_LINK')\nGROUP BY Project.Id";
    $sqlReports = $sqlCommon . "\nAND (ArtifactType.Name = 'PDF' OR
                              ArtifactType.Name = 'POWERPOINT')\nGROUP BY Project.Id";
    $pics = $projectDb->getQueryResult($sqlPics);
    $videos = $projectDb->getQueryResult($sqlVideos);
    $reports = $projectDb->getQueryResult($sqlReports);
    $result = $projectDb->getQueryResult($sql);
    $proj = $result->fetch_assoc();
    $recommends = $projectDb->getRecommendations($id);
    $recFirst = $recommends[0];
    $recSecond = $recommends[1];
    $recThird = $recommends[2];
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/lib/w3.css">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <!-- Copyright 2017 https://github.com/kenwheeler/slick -->
    <!-- Get the source files from web.-->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
    <!-- Get the source files from internal link
    <link rel="stylesheet" type="text/css" href="slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="slick/slick-theme.css"/>
    <script type="text/javascript" src="slick/slick.js"></script> -->
    <link rel="stylesheet" href="idxstylesheet.css">
    <title>Individual project page</title>
</head>
<body>


<script>
    $(document).ready(function () {
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
                            echo '<option>'.$row['Year'].' - Spring</option>';
                            echo '<option>'.$row['Year'].' - Fall</option>'; // Modified.
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

        <div class="media-buttons-container">
            <!--<p>-->
            <form action="" method="post">
                <ul class="media-buttons">
                    <li class="media-button">
                        <button class="videoButton white-button" type="submit" name="media" value="1">Videos
                        </button>
                    </li>
                    <li class="media-button">
                        <button class="picButton white-button" type="submit" name="media" value="2">Pictures
                        </button>
                    </li>
                    <li class="media-button">
                        <button class="reportButton white-button" type="submit" name="media" value="3">Reports
                        </button>
                    </li>
                    <input type="hidden" name="projectid" value="<?php echo $id ?>">
                </ul>
            </form>
            <!--</p>-->
        </div>
        <div class="project_artifacts" style="text-align: center">
            <?php
            function getArtifactType($artifact)
            {
                try {
                    $rtn = $artifact['TName'];
                    return $rtn;
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                    exit();
                }
            }

            // For pictures.
            function displayMultipleArtifacts($artifacts)
            {
                // Every project must have at least one picture.
                assert($artifacts->num_rows >= 1);
                echo '<div id="project_pics">';
                while ($row = $artifacts->fetch_assoc()) {
                    assert(getArtifactType($row) === 'IMAGE');
                    echo '<div><center><img src="pics/' . $row['fileName'] . '" alt="' .
                        $row['fileName'] . '" style="width:auto;height:450px"/><br>
                    </center></div>';
                }
                echo '</div>';
            }

            // For reports and videos.
            function displaySingleArtifact($artifact)
            {
                if ($artifact->num_rows < 1) {
                    echo '<div><h2>No contents to be displayed.<h2></div>';
                    return;
                }
                $row = $artifact->fetch_assoc();
                switch (getArtifactType($row)) {
                    case 'VIDEO_FILE':
                        echo '<div><center><video width="600" controls><source src="videos/' .
                            $row['fileName'] . '"type="video/mp4"></video></center></div>';
                        break;
                    case 'VIDEO_LINK':
                        /*
                         * Embded youtube link example:
                         *
                         * <iframe width="560" height="315"
                         * src="https://www.youtube.com/embed/3JluqTojuME"
                         * frameborder="0" allowfullscreen></iframe>
                         *
                         * You must change char length of Artifact.Link from 100 to 200.
                         */
                        echo '<div><center>' . $row['Link'] . '</center></div>';
                        break;
                    case 'PDF':
                        echo '<div><center><embed src="reports/' . $row['fileName'] .
                            '" width="700px" height="800px" /></center></div>';
                        break;
                    case 'POWERPOINT':
                        echo '<div><center><iframe height="" width="" src="reports/' .
                            $row['fileName'] . '"></iframe></center></div>';
                        break;
                    case 'TEXT':
                        break;
                    default:
                        echo "Shouldn't reach this line.";
                        exit();
                        break;
                }
            }

            ?>
            <?php
            if ($proj != NULL && $media != NULL) {
                switch ($media) {
                    case 1:
                        displaySingleArtifact($videos);
                        break;
                    case 2:
                        displayMultipleArtifacts($pics);
                        break;
                    case 3:
                        displaySingleArtifact($reports);
                        break;
                    default:
                        echo "Shouldn't reach this line.";
                        exit();
                        break;
                }
            } else {
                displayMultipleArtifacts($pics);
            }
            ?>
        </div>
        <div class="project_details">
            <!--<h2><b>Metadata: </b></h2>-->
            <?php
            if ($proj != NULL) {
                echo "<h2>" . $proj['Title'] . "</h2><br>";
                //echo 'Title: ', $proj['Title'], '<br>';
                echo 'Department: ', $proj['DName'], '<br>';
                echo 'Start date: ', $proj['Year'], '<br>';
                echo $proj['Description'], '<br>';
                echo 'Sub-Category: ', $proj['CName'], '<br><br>';
                //echo 'Total access: ', $proj['AccessCount'], '<br>';
            }
            ?>
        </div>

        <div class="recommendation-container">
            <h2>Recommended for you</h2>
            <form action="" method="post">
                <ul class="recommendation-buttons">
                    <li class="recommendation-button">
                        <button type="submit" class="recButton1 white-button" name="hello"
                                value="<?php echo $recFirst ?>">
                            <?php echo $projectDb->getProjectTitle($recFirst) ?></button>
                    </li>
                    <li class="recommendation-button">
                        <button type="submit" class="recButton2 white-button" name="hello"
                                value="<?php echo $recSecond ?>">
                            <?php echo $projectDb->getProjectTitle($recSecond) ?></button>
                    </li>
                    <li class="recommendation-button">
                        <button type="submit" class="recButton3 white-button" name="hello"
                                value="<?php echo $recThird ?>">
                            <?php echo $projectDb->getProjectTitle($recThird) ?></button>
                    </li>
                </ul>
            </form>
        </div>

        <div class="spacer"></div>
    </div>
    <div class="footer">
        <div>Check out the <a href="http://miamioh.edu/cec/">CEC Webpage</a> for more details about the College of
            Engineering and Computing!
        </div>

    </div>
</div>

</body>

</html>

<?php $projectDb->closeConnection(); ?>
