<?php
 
class DbOperation
{
    //Database connection link
    private $con;
 
    //Class constructor
    function __construct()
    {
        //Getting the DbConnect.php file
        require_once dirname(__FILE__) . '/DbConnect.php';
 
        //Creating a DbConnect object to connect to the database
        $db = new DbConnect();
 		
        //Initializing our connection link of this class
        //by calling the method connect of DbConnect class
        $this->con = $db->connect();
    }
	
	/*
	* The create operation
	* When this method is called a new record is created in the database
	*/
	function createHero($name, $realname, $rating, $teamaffiliation){
		$stmt = $this->con->prepare("INSERT INTO heroes (name, realname, rating, teamaffiliation) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssis", $name, $realname, $rating, $teamaffiliation);
		if($stmt->execute())
			return true; 
		return false; 
	}

	/*
	* The read operation
	* When this method is called it is returning all the existing record of the database
	*/
	// function getHeroes(){
		
	// 	$stmt = $this->con->prepare("SELECT app_id, title, url, developerID, date_scraped FROM app");
	// 	$stmt->execute();
	// 	$stmt->bind_result($app_id, $title, $url, $developerID, $date_scraped);
		
	// 	$heroes = array(); 
		
	// 	while($stmt->fetch()){
	// 		$hero  = array();
	// 		$hero['app_id'] = $app_id; 
	// 		$hero['title'] = $title; 
	// 		$hero['url'] = $url;
	// 		$hero['developerID'] = $developerID;
	// 		$hero['date_scraped'] = $date_scraped;
			
	// 		array_push($heroes, $hero); 
	// 	}
		
	// 	return $heroes;  
	// }

	function getPermission(){
		$stmt = $this->con->prepare("SELECT * FROM permissions");
		$stmt->execute();
		$stmt->bind_result($permId,$name, $levelId);

		$details = array();

		while($stmt->fetch()){

		    $detail = array();
		    $detail['perm_id'] = $permId;
		    $detail['name'] = $name;
		    $detail['protect_id'] = $levelId;

		    array_push($details, $detail);
        }
		return $details;
	}

	function getMethod($id){
	    $stmt = $this->con->prepare("SELECT name FROM method WHERE perm_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($name);

        $details = array();

        while($stmt->fetch()){
            $detail = array();
            $detail['name'] = $name;

            array_push($details, $detail);

        }

        return $details;
    }

	/*
	* The update operation
	* When this method is called the record with the given id is updated with the new given values
	*/
	function updateHero($id, $name, $realname, $rating, $teamaffiliation){
		$stmt = $this->con->prepare("UPDATE heroes SET name = ?, realname = ?, rating = ?, teamaffiliation = ? WHERE id = ?");
		$stmt->bind_param("ssisi", $name, $realname, $rating, $teamaffiliation, $id);
		if($stmt->execute())
			return true; 
		return false; 
	}
	
	
	/*
	* The delete operation
	* When this method is called record is deleted for the given id 
	*/
	
	function getHeroes($id){
		$stmt = $this->con->prepare("SELECT app_id, title, url, developerID, date_scraped FROM app WHERE app_id = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->bind_result($app_id, $title, $url, $developerID, $date_scraped);

		$details = array();

		while($stmt->fetch()){
			$detail = array();
			$detail['app_id'] = $app_id; 
			$detail['title'] = $title; 
			$detail['url'] = $url;
			$detail['developerID'] = $developerID;
			$detail['date_scraped'] = $date_scraped;

			array_push($details, $detail); 
		}
		return $details;
	}

	function deleteHero($id){
		$stmt = $this->con->prepare("SELECT `perm_exist`.`app_id`, `permissions`.`name`, `protection`.`level` FROM perm_exist INNER JOIN permissions ON `perm_exist`.`perm_id`= `permissions`.`perm_id`INNER JOIN protection ON `protection`.`protect_id` = `permissions`.`protect_id` WHERE `perm_exist`.`app_id` = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->bind_result($app_id, $permName, $protectLevel);

		$heroes = array();

		while($stmt->fetch()){
			$hero = array();
			$hero['app_id'] = $app_id;
			$hero['permName'] = $permName;
			$hero['protectLevel'] = $protectLevel;
			
			array_push($heroes, $hero); 
		}
		return $heroes;
	}
}



?>