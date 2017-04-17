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
    $sql0 = "UPDATE Project SET AccessCount = AccessCount + 1 WHERE Id = ". $id;
    $projectDb->getQueryResult($sql0);
    $toBeDisplayed = true;
} else if (isset($_POST['media']) && isset($_POST['projectid'])) {
    $id = $_POST['projectid'];
    $media = $_POST['media'];
    $toBeDisplayed = true;
} else { //display newest project
    $query = $projectDb->getQueryResult("SELECT * FROM Project ORDER BY Project.Id DESC");
    $row= $query->fetch_assoc();
    $id = $row["Id"];
    echo $id;
    $sql0 = "UPDATE Project SET AccessCount = AccessCount + 1 WHERE Id = ". $id;
    $projectDb->getQueryResult($sql0);
    $toBeDisplayed = true;
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
    <link rel="stylesheet" href="idxstylesheet.css?2017219">
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

    // For pictures.
    function displayMultipleArtifacts($artifacts) {
        // Every project must have at least one picture.
        assert($artifacts->num_rows >= 1);
        echo '<div id="project_pics">';
        while ($row = $artifacts->fetch_assoc()) {
            assert(getArtifactType($row) === 'IMAGE');
            echo '<div><center><img src="pics/'.$row['fileName'].'" alt="'.
                $row['fileName'].'" style="width:auto;height:450px"/><br>
                    </center></div>';
        }
        echo '</div>';
    }

    // For reports and videos.
    function displaySingleArtifact($artifact) {
        if ($artifact->num_rows < 1) {
            echo '<div><h2>No contents to be displayed.<h2></div>';
            return;
        }
        $row = $artifact->fetch_assoc();
        switch (getArtifactType($row)) {
            case 'VIDEO_FILE':
                echo '<div><center><video width="600" controls><source src="videos/'.
                    $row['fileName'].'"type="video/mp4"></video></center></div>';
                break;
            case 'VIDEO_LINK':
                echo '<iframe src="'.$row['Link'].
                    '" width="560" height="315" frameborder="0" allowfullscreen>
                     </iframe>';
                break;
            /* Comvert to embed URL.
            echo '<iframe src="https://www.youtube.com/embed/XGSy3_Czz8k
                 "width="560" height="315" frameborder="0" allowfullscreen>
                 </iframe>';
             */
            case 'PDF':
                echo '<div><center><embed src="reports/'.$row['fileName'].
                    '" width="700px" height="800px" /></center></div>';
                break;
            case 'POWERPOINT':
                echo '<div><center><iframe height="" width="" src="reports/'.
                    $row['fileName'].'"></iframe></center></div>';
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
        switch($media) {
            case 1: displaySingleArtifact($videos); break;
            case 2: displayMultipleArtifacts($pics); break;
            case 3: displaySingleArtifact($reports); break;
            default: echo "Shouldn't reach this line."; exit(); break;
        }
    } else {
        displayMultipleArtifacts($pics);
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
    <h2><b>Recommended to you: </b></h2><br>



    <form action="" method="post">
        <button type="submit" class="recButton1" name="hello" value="<?php echo $recFirst ?>"
                style="width:150px;height:75px;margin-right:2cm"><?php echo $projectDb->getProjectTitle($recFirst)?></button>
        <button type="submit" class="recButton2" name="hello" value="<?php echo $recSecond ?>"
                style="width:150px;height:75px;margin-right:2cm;margin-left:2cm"><?php echo $projectDb->getProjectTitle($recSecond)?></button>
        <button type="submit" class="recButton3" name="hello" value="<?php echo $recThird ?>"
                style="width:150px;height:75px;margin-left:2cm"><?php echo $projectDb->getProjectTitle($recThird)?></button>
    </form>
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

<?php $projectDb->closeConnection(); ?>
