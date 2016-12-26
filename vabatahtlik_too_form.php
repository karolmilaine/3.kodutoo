
<?php
// et saada ligi sessioonile
require("functions.php");

//ei ole sisseloginud, suunan login lehele
if(!isset ($_SESSION["userId"])) {
	header("Location: login.php");
	exit();
}

//kas kasutaja tahab välja logida
// kas aadressireal on logout olemas
if (isset($_GET["logout"])) {
	session_destroy();
	header("Location: login.php");
	exit();
}

$event_name = "";
$place = "";
$description = "";
$date = "";
$time = "";

$event_nameError = "";
$placeError = "";
$descriptionError = "";
$dateError = "";
$timeError = "";

if(!isset($_POST["event_name"])){
	$event_nameError = "See väli on kohustuslik";
}else{
	$event_name = $_POST["event_name"];
}


if(isset($_POST["place"])) {
	if(!empty($_POST["place"])){
		$place = $_POST["place"];
	}
}

if(isset($_POST["description"])){
	if(empty($_POST["description"])){
		$descriptionError = "See väli on kohustuslik";
	} else {
		$_POST["description"] = cleanInput($_POST["description"]);
		$description = $_POST["description"];
	}
}

if(isset($_POST["date"])) {
	if(!empty($_POST["date"])){
		$date = $_POST["date"];
	}
}

$time = "";
if(isset($_POST["time"])) {
	if(!empty($_POST["time"])){
		$time = $_POST["time"];
	}
}

	save_voluntary_work($event_name,$place,$description,$date,$time);


var_dump($_POST);

if(isset($_POST["event_name"]) &&
	isset($_POST["place"]) &&
	isset($_POST["description"])&&
	isset($_POST["date"])&&
	isset($_POST["time"])&&
	!empty($_POST["event_name"])&&
	!empty($_POST["place"])&&
	!empty($_POST["description"])&&
	!empty($_POST["date"])&&
	!empty($_POST["time"])
){
	save_voluntary_work(cleanInput($_POST["event_name"]),$_POST["place"],$_POST["description"],$_POST["date"],$_POST["time"]);


}

//Kui väli on tühi
$emptyFieldError = "";

if ( isset($_POST["event_name"]) && empty($_POST["event_name"]) ) {

	$emptyFieldError = "See väli on kohustuslik!";

}
if ( isset($_POST["user_voluntary_work"]) &&
	!empty($_POST["user_voluntary_work"])
) {
	save_user_voluntary_work(cleanInput($_POST["user_voluntary_work"]));

}
$voluntary_work = get_all_voluntary_work();
$user_voluntary_work = get_all_user_voluntary_work();

//saan kõik töö andmed
get_all_voluntary_work();
echo "<pre>";

//saan kõik töö andmed
$voluntary_work_data = get_all_voluntary_work();

echo "</pre>";

// sorteerib
if(isset($_GET["sort"]) && isset($_GET["direction"])){
	$sort = $_GET["sort"];
	$direction = $_GET["direction"];
}else{
	// kui ei ole määratud siis vaikimis id ja ASC
	$sort = "id";
	$direction = "ascending";
}


//kas kasutaja otsib -> q=searching
if(isset($_GET["q"])){
	$q = cleanInput($_GET["q"]);
	$get_voluntary_work_data = get_all_voluntary_work($q, $sort, $direction);
} else {
	$q = "";
	$get_voluntary_work_data = get_all_voluntary_work($q, $sort, $direction);
}


?>


<h1 class="logo">
</h1>

<header>
	<img src="logo.png" alt="Vabatahtlike klubi" style="width:150px;height:110px;">
	<h1>Kesklinna noortekeskuse vabatahtlike klubi</h1>
</header>

<style>
	header {
		padding: 1em;
		color: white;
		background-color: #94cfdd;
		clear: left;
		text-align: center;
	}
</style>


<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">
	<script type="text/javascript" src="js/jquery-1.11.3.js"></script>
<style>

		ul {
			list-style-type: none;
			margin: 0;
			padding: 0;
			overflow: hidden;
			border: 1px solid #e7e7e7;
			background-color: #f3f3f3;
		}
		li {
			float: left;
		}
		li a {
			display: block;
			color: #666;
			text-align: center;
			padding: 14px 16px;
			text-decoration: none;
		}
		li a:hover:not(.active) {
			background-color: #ddd;
		}
		li a.active {
			color: white;
			background-color: #ddd;
		}
	</style>
</head>
<body>

<ul>
	<li><a href="avalehekülg.php">Avaleht</a></li>
	<li><a class="active" href="vabatahtlik_too_form.php">Lisa vabatahtlik töö</a></li>
	<li><a href="voluntarywork.php">Vabatahtlik töö</a></li>
	<li><a href="contact.php">Kontakt</a></li>
</ul>


<h2>Lisa vabatahtlik töö</h2>
<form method="POST">

	<label>Ürituse nimi</label><br>
	<input name="event_name" type=text value="<?=$event_name;?>">

	<br><br>

	<label>Asukoht</label><br>
	<input name="place" type=text value="<?=$place;?>">

	<br><br>

	<label> Sisestage ürituse kirjeldus</label><br>
	<input name="descripition"  type="text" value="<?=$description;?>"> <?$descriptionError;?> 

	<br><br>

	<label> Toimumise kuupäev </label><br>
	<input placeholder="aaaa-kk-pp" name="date" min="2016-11-14" max = "2019-11-14" type=date value="<?=$date;?>">

	<br><br>

	<label> Toimumise kellaaeg </label><br>
	<input placeholder="tt-mm-ss" name="time" min="00:00:00" max="23:00:00" type=time value="<?=$time;?>">
	<br><br>

	<input type="submit" class="btn btn-info" value="Salvesta">

</form>

<br>
<form>
	<input type="search" name="q" value="<?=$q;?>">
	<input type="submit" class="btn btn-info" value="Otsi">
</form>
<br>


<?php

$direction = "ascending";
if (isset($_GET["direction"])){
	if ($_GET["direction"] == "ascending"){
		$direction = "descending";
	}
}

$html = "<table class='table table-striped table-bordered'>";

$html .= "<tr>";
$html .= "<th>
						<a href='?q=".$q."&sort=id&direction=".$direction."'>
							id
						</a>
					</th>";
$html .= "<th>
						<a href='?q=".$q."&sort=event_name&direction=".$direction."'>
							Ãœritus
						</a>
					</th>";
$html .= "<th>
						<a href='?q=".$q."&sort=place&direction=".$direction."'>
							Asukoht
						</a>
					</th>";
$html .= "<th>
						<a href='?q=".$q."&sort=description&direction=".$direction."'>
							Kirjeldus
						</a>
					</th>";
$html .= "<th>
						<a href='?q=".$q."&sort=date&direction=".$direction."'>
							Toimumise kuupÃ¤ev
						</a>
					</th>";
$html .= "<th>
						<a href='?q=".$q."&sort=time&direction=".$direction."'>
							Toimumise kellaaeg
						</a>
					</th>";

$html .= "</tr>";

//iga liikme kohta massiivis
foreach($get_voluntary_work_data as $c){


	$html .= "<tr>";
	$html .= "<td>".$c->id."</td>";
	$html .= "<td>".$c->event_name."</td>";
	$html .= "<td>".$c->place."</td>";
	$html .= "<td>".$c->description."</td>";
	$html .= "<td>".$c->date."</td>";
	$html .= "<td>".$c->time."</td>";
	$html .= "<td>
							<a href='edit.php?id=".$c->id."' class='btn btn-default'>
							
								<span class='glyphicon glyphicon-pencil'></span>
								Muuda
								
							</a>
						</td>";

	$html .= "</tr>";
}

$html .= "</table>";

echo $html;


$listHtml = "<br><br>";






?>

<br>
<br>
<br>
<br>
<br>
</div>


