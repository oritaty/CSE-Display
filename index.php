<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'ResearchDisplayDb';

//Create connection.
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection.
if ($conn->connect_error) {
    die("Connection failed: ".$conn->connect_error);
}

$sql1 = "SELECT DISTINCT Project.Id, Project.Title, Project.Year,
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
         ORDER BY Project.YEAR DESC LIMIT 5";
$sql2 = "SELECT DISTINCT YEAR(YEAR) AS Year FROM Project";
$sql3 = "SELECT DISTINCT Name FROM Department";
$sql4 = "SELECT DISTINCT Name FROM Category";
$sql5 = "SELECT DISTINCT User.Name, User.MiamiId
         FROM User, UserType
         WHERE User.UserTypeId = UserType.Id AND
               User.Name <> '' AND
               User.MiamiId <> '' AND
               UserType.Name <> 'Admin'";

$mostRecent = $conn->query($sql1);
$years = $conn->query($sql2);
$departments = $conn->query($sql3);
$categories = $conn->query($sql4);
$students = $conn->query($sql5);
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
            while($row = $mostRecent->fetch_assoc()) {
                echo '<div><center><img src="pics/', $row['fileName'], '" alt="', $row['pic_url'],
                        '" style="width:auto;height:350px;margin-top:1cm" /><br>';
                echo "<h2>Description:</h2>";
                echo 'Title: ', $row['Title'], '<br>';
                echo 'Department: ', $row['DName'], '<br>';
                echo 'Start date: ', $row['Year'], '<br>';
                echo 'Description: ', $row['Description'], '<br>';
                echo 'Sub-category: ', $row['CName'], '<br>';
                echo 'Total access: ', $row['AccessCount'], '<br>';
                echo "<form action=", '"project.php"', "method=", '"post"', "><button type=",
                        '"submit"', "name=", '"hello"', "value=", $row['Id'], " class=",
                        '"btn-link"', ">Click here for more details.</button></form></center><br><br></div>";
            }
            ?>
        </div>
        <div class="searchtab">
            <h4 style="margin-top:0.1cm;margin-bottom:-0.0cm;">Search by Date</h4>
            <I>Year:</I>
            <select name="year" form="tab">
              <?php
              echo '<option>All</option>';
              while($row = $years->fetch_assoc()) {
                  echo '<option>'.$row['Year'].'</option>';
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
              while($row = $students->fetch_assoc()) {
                  echo '<option>'.$row['Name'].' ('.$row['MiamiId'].')</option>';
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
              while($row = $categories->fetch_assoc()) {
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
              while($row = $departments->fetch_assoc()) {
                  echo '<option>'.$row['Name'].'</option>';
              }
              ?>
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
