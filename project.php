<?php
class Project {
  public $projectName;
  public $picURL;
  public $accessCount;
  public $startDate;
  public $projectDescription;

  function __construct($name, $url, $description) {
    $this->projectName = $name;
    $this->picURL = $url;
    $this->accessCount = 0;
    $this->startDate = date("Y/m/d");
    $this->projectDescription = $description;
  }

  function getProjectName() {
      return $this->projectName;
  }

  function getPicURL() {
      return $this->picURL;
  }

  function getAccessCount() {
      return $this->accessCount;
  }

  function getStartDate() {
      return $this->startDate;
  }

  function getProjectDescription() {
      return $this->projectDescription;
  }

  function incrementCnt() {
      $this->accessCount++;
  }
}

function changeTarget(Project $next) {
    $this->currentTarget = $next;
}

function cmpByAccessCounts(Project $proj1, Project $proj2) {
  $a = $proj1->getAccessCount();
  $b = $proj2->getAccessCount();
  if ($a == $b) {
    return 0;
  }
  return ($a > $b) ? 1 : -1;
}

function cmpByStartDate(Project $proj1, Project $proj2) {
  $a = $proj1->getStartDate();
  $b = $proj2->getStartDate();
  if (strtotime($a) == strtotime($b)) {
      return 0;
  }
  return (strtotime($a) > strtotime($b)) ? 1 : -1;
}

function sortByStartDate($projects) {
  uasort($projects, 'cmpByStartDate');
}

function sortByAccessCounts($projects) {
  uasort($projects, 'cmpByAccessCounts');
}

$proj1 = new Project('AI', 'pics/ai.jpg', 'A research project about Artificial Intelligence.');
$proj2 = new Project('CG', 'pics/cg.jpg', 'A research project about Computer Graphics.');
$proj3 = new Project('Network', 'pics/network.png', 'A research project about networking.');
$proj4 = new Project('Compiler', 'pics/compiler.png', 'A research project about compiler and programming language.');
$proj5 = new Project('Database', 'pics/no_img.png', 'A research project about database systems.');
$proj6 = new Project('Independent', 'pics/no_img.png', 'An independent project.');
$proj7 = new Project('Other', 'pics/no_img.png', 'Not a project from the department.');
$projects = array($proj1, $proj2, $proj3, $proj4, $proj5, $proj6, $proj7);
$currentTarget = $projects[2];
?>
