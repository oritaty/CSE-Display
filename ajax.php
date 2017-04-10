<?php include 'includes.php'?>
<?php
if (isset($_REQUEST['req'])) {
    $req = $_REQUEST['req'];
    if ($req == 'popular') {
        $displayRecent = true;
    } else {
        $displayRecent = false;
    }
} else {
    $displayRecent = true;
}

$projectDb = new ProjectDb();
$sql = "SELECT DISTINCT Project.Id, Project.Title, Project.Year,
                        Project.AccessCount, Project.Description, 
                        Category.Name AS CName, Department.Name AS DName, 
                        Artifact.fileName
        FROM Project JOIN Category ON Project.CategoryId = Category.Id
                     JOIN ProjectDepartment ON Project.Id = ProjectDepartment.ProjectId
                     JOIN Department ON ProjectDepartment.DepartmentId = Department.Id
                     JOIN ProjectArtifact ON Project.Id = ProjectArtifact.ProjectId
                     JOIN Artifact ON ProjectArtifact.ArtifactId = Artifact.Id
                     JOIN ArtifactType ON Artifact.TypeId = ArtifactType.Id
        WHERE ArtifactType.Name = 'IMAGE' ";
$sqlRecent = $sql."\nORDER BY Project.Year DESC LIMIT 5";
$sqlPopular = $sql."\nORDER BY Project.AccessCount DESC LIMIT 5";

$target = NULL;
if ($displayRecent) {
    $target = $projectDb->getQueryResult($sqlRecent);
} else {
    $target = $projectDb->getQueryResult($sqlPopular);
}
$projectDb->closeConnection();
    
while ($row = $target->fetch_assoc()) {
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