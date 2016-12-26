<?php
require("../../config.php");
session_start();

$database = "if16_karojyrg_2";

//***************
//**** SIGNUP ***
//***************

function signup($signupEmail, $password, $signupGender) {

    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    $stmt = $mysqli->prepare("INSERT INTO user_sample (email, password, gender) VALUES (?, ?, ?)");
    echo $mysqli->error;

    $stmt->bind_param("sss", $signupEmail, $password, $signupGender );
    if ( $stmt->execute() ) {
        echo "salvestamine õnnestus";
    } else {
        echo "ERROR ".$stmt->error;
    }
    $stmt->close();
    $mysqli->close();
}

//***************
//**** LOGIN ****
//***************

function login($loginEmail, $loginPassword) {
    $database = "if16_karojyrg_2";

    $error = "";
    $password = $loginPassword;
    $email = $loginEmail;


    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"],  $GLOBALS["serverPassword"],  $GLOBALS["database"]);

    $stmt = $mysqli->prepare("SELECT id, email, password, created, gender FROM user_sample WHERE email = ? ");

    echo $mysqli->error;
    // asendan ?
    $stmt->bind_param("s", $email);

    // määran muutujad reale mis kätte saan
    $stmt->bind_result($id, $emailFromDB, $passwordFromDB, $created, $genderFromDB);

    $stmt->execute();

    // ainult SLECTI'i puhul
    if ($stmt->fetch()) {

        // vähemalt üks rida tuli
        // kasutaja sisselogimise parool räsiks
        $hash = hash("sha512", $password);
        if ($hash == $passwordFromDB) {
            // õnnestus
            echo "Kasutaja ".$id." logis sisse";

            $_SESSION["userId"] = $id;
            $_SESSION["userEmail"] = $emailFromDB;

            $_SESSION["userGender"] = ucfirst($genderFromDB);

            $_SESSION["message"] = "<h1>Tere tulemast!</h1>";

            header("Location: avalehekülg.php");
            exit();
        } else {
            $notice = "Vale parool!";
        }

    } else {
        // ei leitud ühtegi rida
        $notice = "Sellist emaili ei ole!";
    }

    return $error;

    $stmt->close();
    $mysqli->close();
}


//*********************
//***VOLUNTARY WORK ***
//*********************

function save_voluntary_work($event_name,$place,$description,$date,$time){
    $database="if16_karojyrg_2";
    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
    $stmt = $mysqli->prepare("INSERT INTO voluntary_work(event_name,place,description,date, time)VALUES(?,?,?,?,?)");
    echo $mysqli->error;
    $stmt-> bind_param("sssss",$event_name,$place,$description,$date,$time);
    if($stmt->execute()){
        echo "salvestamine õnnestus";

    }else{
        echo "ERROR".$stmt->error;
    }
    $stmt->close();

	

}


//*********************
//***VOLUNTARY WORK ***
//*********************


function get_voluntary_work($q, $sort, $direction){
    $database = "if16_karojyrg_2";
    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);

    $allowedSortOptions = ["id", "event_name", "place"];

    if(!in_array($sort, $allowedSortOptions)){
        $sort = "id";
    }
    echo "Sorteerin: ".$sort." ";

    $orderBy= "ASC";
    if($direction == "descending"){
        $orderBy= "DESC";
    }
    echo "Järjekord: ".$orderBy." ";

    if($q == "") {
        echo "Ei otsi";
        $stmt = $mysqli->prepare ("SELECT id, event_name,place,description,date, time FROM voluntary_work WHERE deleted is NULL ORDER BY $sort $orderBy");
    }else{
        echo "Otsib";
        $searchword = "%".$q."%";
        $stmt = $mysqli->prepare ("SELECT id, event_name,place,description,date, time FROM voluntary_work WHERE deleted is NULL 
								   AND (event_name LIKE ? OR place LIKE ?) ORDER BY $sort $orderBy");
        $stmt->bind_param("ss", $searchword, $searchword);
    }
    $stmt->bind_result($id, $event_name,$place,$description,$date,$time);
    $stmt->execute();

    //tekitan massiivi
    $result = array();


    //tee seda seni, kuni on rida andmeid, mis vastab select lausele
    while($stmt->fetch()) {
        //tekitan objekti
        $run = new StdClass();

        $run->id = $id;
        $run->event_name = $event_name;
        $run->place = $place;
        $run->description = $description;
        $run->date = $date;
        $run->time = $time;


        array_push($result, $run);
    }
    $stmt->close();
    $mysqli->close();
    return $result;
}

function getSingle_voluntary_work($edit_id){

$database = "if16_karojyrg_2";

 	//echo "id on ".$edit_id;

 		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);

 $stmt = $mysqli->prepare("SELECT event_name,place,description,date, time FROM voluntary_work WHERE id = ? ");

		echo $mysqli->error;

		$stmt->bind_param("i", $edit_id);
 		$stmt->bind_result($event_name,$place,$description,$date,$time);
 		$stmt->execute();

 		//tekitan objekti
 	$run = new Stdclass();

 		//saime ühe rea andmeid
 		if($stmt->fetch()){
            // saan siin alles kasutada bind_result muutujaid

            $run->event_name = $event_name;
            $run->place = $place;
            $run->description = $description;
            $run->date = $date;
            $run->time = $time;

        }else{
            // ei saanud rida andmeid kätte
            // sellist id'd ei ole olemas
            // see rida võib olla kustutatud
            header("Location: vabatahtlik_too_form.php");
            exit();
        }

 		$stmt->close();
 		$mysqli->close();

 		return $run;

 	}


 	function update_voluntary_work($id, $event_name,$place,$description,$date,$time){

        $database = "if16_karojyrg_2";


        $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
        echo $mysqli->error;
        $stmt = $mysqli->prepare("UPDATE voluntary_work SET event_name = ?, place = ?, description = ?, date = ?, time = ? WHERE id = ?");
        $stmt->bind_param("sssss", $event_name,$place,$description,$date,$time, $id);
        echo $mysqli->error;
        // kas õnnestus salvestada
        if($stmt->execute()){
            // õnnestus
            echo "salvestus õnnestus!";
        }

        $stmt->close();
        $mysqli->close();

    }

	function delete($id){

        $database = "if16_karojyrg_2";

        $mysqli = new mysqli ($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);

        $stmt = $mysqli->prepare("UPDATE voluntary_work SET deleted=NOW() WHERE id=? AND deleted IS NULL");
        $stmt->bind_param("i",$id);

        // kas õnnestus salvestada
        if($stmt->execute()){
            // õnnestus
            echo "kustutamine õnnestus!";
        }

        $stmt->close();
        $mysqli->close();

    }

//*********************
//***VOLUNTARY WORK ***
//*********************


function get_all_voluntary_work(){
    $database="if16_karojyrg_2";
    $mysqli = new mysqli ($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
    $stmt = $mysqli ->prepare("
		SELECT id, event_name, place, description, date, time
		FROM voluntary_work
		");
    echo $mysqli->error;

    $stmt ->bind_result($id, $event_name,$place, $description, $date, $time);
    $stmt->execute();

    //tekitan massiivi
    $result = array();


    //tee seda seni, kuni on rida andmeid
    //mis vastab select lausele
    while($stmt->fetch()) {
        //tekitan objekti
        $i = new StdClass();
        $i->id = $id;
        $i->event_name = $event_name;
        $i->place = $place;
        $i->description = $description;
        $i->date = $date;
        $i->time = $time;

        //igakord massivi lisan juurde nr märgi
        array_push($result,$i);
    }




    $stmt->close();

    return $result;

}

//*********************
//***VOLUNTARY WORK ***
//*********************

function get_all_user_voluntary_work(){
    $database="if16_karojyrg_2";
    $mysqli = new mysqli ($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);

    $stmt = $mysqli->prepare("SELECT event_name from voluntary_work join user_voluntary_work
    on voluntary_work.id = user_voluntary_work.work_id where user_voluntary_work.user_id = ?");

    echo $mysqli->error;

    $stmt->bind_param("i", $_SESSION["userId"]);

    $stmt->bind_result($voluntary_work);
    $stmt->execute();

    $result = array();
    while ($stmt->fetch()) {
        $i = new StdClass();
        $i-> voluntary_work = $voluntary_work;
        array_push($result, $i);
    }
    $stmt->close();
    return $result;
}




//***************
//** CLEANINPUT *
//***************

function cleanInput($imput){
    $imput = trim($imput);
    $imput = htmlspecialchars($imput);
    $imput = stripslashes($imput);
    return $imput;

}

?>



