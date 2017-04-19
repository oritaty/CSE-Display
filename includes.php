<?php
define('NUM_OF_REC', 3);

class ProjectDb {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;
    
    function __construct() {
        $this->servername = 'localhost';
        $this->username = 'root';
        $this->password = '';
        $this->dbname = 'ResearchDisplayDb';
        $this->conn = new mysqli($this->servername, $this->username, 
                $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: ".$this->conn->connect_error);
        }
    }
    
    function __construct2($sn, $un, $pw, $dbn) {
        $this->servername = $sn;
        $this->username = $un;
        $this->password = $pw;
        $this->dbname = $dbn;
        $this->conn = new mysqli($this->servername, $this->username, 
                $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: ".$this->conn->connect_error);
        }
    }
    
    function __destruct() {
        // print("This is destructor.");
    }
    
    function closeConnection() {
        if (mysqli_ping($this->conn)) {
            $this->conn->close();
        }
    }
    
    function getQueryResult($sql) {
        try {
            $result = $this->conn->query($sql);
            return $result;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return null;
        }
    }

    function getProjectTitle($id) {
        $sql = "SELECT DISTINCt Project.Title FROM Project WHERE Project.Id=".$id;
        $result = $this->getQueryResult($sql);
       $row = $result->fetch_assoc();
       return $row['Title'];
   }

    private function getTotalProjects() {
        $sql = "SELECT COUNT(*) As Total FROM Project";
        $result = $this->getQueryResult($sql);
        assert(($result->num_rows) == 1);
        $row = $result->fetch_assoc();
        return $row['Total'];
    }
    
    function getYears() {
        $sql = "SELECT DISTINCT YEAR(YEAR) AS Year FROM Project";
        return $this->getQueryResult($sql);
    }

    function getDepartments() {
        $sql = "SELECT DISTINCT Name FROM Department";
        return $this->getQueryResult($sql);
    }

    function getCategories() {
        $sql = "SELECT DISTINCT Name FROM Category";
        return $this->getQueryResult($sql);
    }

    function getStudents() {
        $sql = "SELECT DISTINCT User.Name, User.MiamiId
            FROM User, UserType 
            WHERE User.UserTypeId = UserType.Id AND User.Name <> '' AND 
                  User.Name IS NOT NULL AND User.MiamiId <> '' AND 
                  User.MiamiId IS NOT NULL AND UserType.Name <> 'Admin'";
        return $this->getQueryResult($sql);
    }
    
    private function checkNumOfEntries() {
        // Currently displayed project (1) + recommendations (3).
        if ($this->getTotalProjects() < NUM_OF_REC + 1) {
            echo "You need to have at least four project entries in the database";
            echo "<br>Number of entries: " . $this->getTotalProjects();
            $this->closeConnection();
            exit();
        }
    }
    
   // private function getIdsFromQueryResult(&$arr, $result) {
     //   $count = sizeof($arr);
      //  while ($row = $result->fetch_assoc()) {
      //      if ($count == NUM_OF_REC) {
       //         break;
        //    }
        //    array_push($arr, (int) $row['Id']);
       //     $count++;
      //  }
//}
   
    //project.php
    function getRecommendations($id) {
        $this->checkNumOfEntries();
        $sql1 = "SELECT DISTINCT Category.Name FROM Category, Project
        WHERE Category.Id = Project.CategoryId AND Project.Id = " .$id;
        $result1 = $this->getQueryResult($sql1);
        // One category per project.
        // assert(($result1->num_rows) == 1);
        $category = $result1->fetch_assoc();
        $sql2 = "SELECT DISTINCT Project.Id FROM Project, Category
                 WHERE Category.Id = Project.CategoryId AND Project.Id <> ".
                 $id." AND Category.Name = '" .$category['Name'].
                 "' ORDER BY Project.AccessCount DESC LIMIT ".NUM_OF_REC;
        $array = array();
        $result2 = $this->getQueryResult($sql2);
        $this->getIdsFromQueryResult($array, $result2);
        if (sizeof($array) == NUM_OF_REC) {
            return $array;
        }
        $sql3 = "SELECT DISTINCT Project.Id FROM Project, Category
                 WHERE Category.Id = Project.CategoryId AND Category.Name <> '".
                 $category['Name']."' ORDER BY Project.AccessCount DESC LIMIT ".
                 (NUM_OF_REC - sizeof($array));
        $result3 = $this->getQueryResult($sql3);
        //$this->getIdsFromQueryResult($array, $result3);
        
        
       //avoid pass-by-reference
        $count = sizeof($array);
        while ($row = $result3->fetch_assoc()) {
            if ($count == NUM_OF_REC) {
                break;
            }
            array_push($array, (int) $row['Id']);
            $count++;
        }
        
        if (sizeof($array) < NUM_OF_REC) {
            echo "You need to allocate more project entries into the Database.";
            echo '<br>Number of recommendations: '.sizeof($array);
            $this->closeConnection();
            exit();
        }
        return $array;
    }
}
