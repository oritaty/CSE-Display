<?php include 'includes.php'?>
<?php
$sql1 = "SELECT Project.Id, Project.Title, Project.Year,
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
         GROUP BY Project.Id
         ORDER BY Project.YEAR DESC LIMIT 5";
$projectDb = new ProjectDb();
$mostRecent = $projectDb->getQueryResult($sql1);
$years = $projectDb->getYears();
$departments = $projectDb->getDepartments();
$categories = $projectDb->getCategories();
$students = $projectDb->getStudents();

/*
 To enable GROUP BY clause, sql_mode must be set as...
 SET sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
*/

/* 
 Check sql_mode...
 $sql2 = "SELECT @@sql_mode AS Mode";
 $status = $projectDb->getQueryResult($sql2);
 $row = $status->fetch_assoc();
 echo $row['Mode'];
*/

$projectDb->closeConnection();
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

        <!-- THIS IS THE MAIN STYLESHEET -->
        <link rel="stylesheet" href="idxstylesheet.css">


        <title>CEC Research Display - Miami University</title>
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
                arrows: false,
                pauseOnHover: false,
                respondTo: 'slider'
            });
        }
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
                        while($row = $years->fetch_assoc()) {
                            echo '<option>'.$row['Year'].'</option>';
                        }
                        ?>
                    </select>
                </li>
                <li class="option">
                    <span>Student:</span>
                        <select name="person" form="advanced-search-submit">
                        <?php
                        echo '<option>All</option>';
                        while($row = $students->fetch_assoc()) {
                            echo '<option>'.$row['Name'].'</option>';
                        }
                        ?>
                    </select>
                </li>
                <li class="option">
                    <span>Department:</span>
                    <select name="department" form="advanced-search-submit">
                        <?php
                        echo '<option>All</option>';
                        while($row = $departments->fetch_assoc()) {
                            echo '<option>'.$row['Name'].'</option>';
                        }
                        ?>
                    </select>
                </li>
                <li class="option">
                    <span>Category:</span>
                    <select name="category" form="advanced-search-submit">
                        <?php
                        echo '<option>All</option>';
                        while($row = $categories->fetch_assoc()) {
                            echo '<option>'.$row['Name'].'</option>';
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



        <div id="slides">
            <?php
            while($row = $mostRecent->fetch_assoc()) {
                echo '<div class = "slide">';
                echo '<div class="project-image-container"><img class="project-image" src="pics/', $row['fileName'], '" alt="pics/', $row['fileName'],
                '"/><br></div>';

                echo '<div class="project-details-container">';
                echo '<div class="project-title">'.$row['Title'].'</div>';
                echo '<div class="project-details"> Department: ', $row['DName'], '<br>';

                echo $row['Description'], '<br>';
                echo 'Sub-category: ', $row['CName'], '<br><br>';

                echo '<form action=', '"project.php"', ' method=', '"post"','><button class="white-button" type=',
                '"submit"', ' name=', '"hello"', ' value=', $row['Id'], ' class=',
                '"btn-link"','>Go to Project Page</button></form><br><br></div></div>';
                echo '</div>'; // Close slide box
            }
            ?>
        </div>








        <!-- BELOW THIS LINE UNTOUCHED BY CSS REDESIGN -->




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
                    // Chrome, Firefox, Safari etc.
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
            while($row = $mostRecent->fetch_assoc()) {
                echo '<div class = "slide">';
                echo '<div class="project-image-container"><img class="project-image" src="pics/', $row['fileName'], '" alt="pics/', $row['fileName'],
                '"/><br></div>';

                echo '<div class="project-details-container">';
                echo '<div class="project-title">'.$row['Title'].'</div>';
                echo '<div class="project-details"> Department: ', $row['DName'], '<br>';

                echo $row['Description'], '<br>';
                echo 'Sub-category: ', $row['CName'], '<br><br>';

                echo '<form action=', '"project.php"', ' method=', '"post"','><button type=',
                '"submit"', ' name=', '"hello"', ' value=', $row['Id'], ' class=',
                '"btn-link"','>Click here for more details.</button></form><br><br></div></div>';
                echo '</div>'; // Close slide box
            }
            ?>
        </div>

        <div class="description">
        </div>
        <div class="bottom_space" style="height:100px"></div>
        <div class="footer">
        <h3>Check out the link below for more details about the College of Engineering and Computing!</h3>
        CEC Webpage:
        <a href="http://miamioh.edu/cec/">
        http://miamioh.edu/cec/</a>
        </div>
        <script>
        $(document).ready(function() {
            addSlick();
        });
        </script>
    </body>
</html>
