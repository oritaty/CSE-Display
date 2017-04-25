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
$sql = "SELECT Project.Id, Project.Title, Project.Year, Project.AccessCount, 
               Project.Description, Category.Name AS CName, 
               Department.Name AS DName, Artifact.fileName
        FROM Project JOIN Category ON Project.CategoryId = Category.Id
                     JOIN ProjectDepartment ON Project.Id = ProjectDepartment.ProjectId
                     JOIN Department ON ProjectDepartment.DepartmentId = Department.Id
                     JOIN ProjectArtifact ON Project.Id = ProjectArtifact.ProjectId
                     JOIN Artifact ON ProjectArtifact.ArtifactId = Artifact.Id
                     JOIN ArtifactType ON Artifact.TypeId = ArtifactType.Id
        WHERE ArtifactType.Name = 'IMAGE'
        GROUP BY Project.Id ";
$sqlRecent = $sql."\nORDER BY Project.Year DESC LIMIT 5";
$sqlPopular = $sql."\nORDER BY Project.AccessCount DESC LIMIT 5";

$target = NULL;
if ($displayRecent) {
    $target = $projectDb->getQueryResult($sqlRecent);
} else {
    $target = $projectDb->getQueryResult($sqlPopular);
}
$projectDb->closeConnection();

while($row = $target->fetch_assoc()) {
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
