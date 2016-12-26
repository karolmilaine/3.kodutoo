<?php
// et saada ligi sessioonile
require("functions.php");

//ei ole sisseloginud, suunan login lehele
if(!isset ($_SESSION["userId"])) {
	header("Location: login.php");
}


//kas kasutaja tahab välja logida
// kas aadressireal on logout olemas
if (isset($_GET["logout"])) {

	session_destroy();

	header("Location: login.php");

}
$msg = "";
if(isset($_SESSION["message"])){
	$msg = $_SESSION["message"];
	//kui ühe näitame siis kustuta ära, et pärast refreshi ei näitaks
	unset($_SESSION["message"]);


}

//var_dump($_POST);


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
<?=$msg;?>



<!DOCTYPE html>
<html>
<head>
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
	<li><a class="active" href="avalehekülg.php">Avaleht</a></li>
	<li><a href="vabatahtlik_too_form.php">Lisa vabatahtlik töö</a></li>
	<li><a href="voluntarywork.php">Vabatahtlik töö</a></li>
	<li><a href="contact.php">Kontakt</a></li>
</ul>


</body>
</html>
<!DOCTYPE html>
<html>
<head>
	<style>
		.center {
			margin: auto;
			width: 90%;
			padding: 10px;
		}
	</style>
</head>
<body>

<div class="center">

	<p>
		Tere tulemast <?=$_SESSION["userEmail"];?>!
	</p>

	<p>
	<span style="font-family: tahoma, arial, helvetica, sans-serif;">

	Kesklinna noortekeskuse vabatahtlike klubi korraldab igakuiselt külastusi
	erinevate organisatsioonide juurde, mis ühelt poolt omavad hariduslikku
	aspekti ning teisalt täidavad abistamise eesmärki. Lisaks sellele püüab
	klubi oma liikmetele vahendada võimalikult palju informatsiooni vabatahtliku
	töö võimalustest üldiselt ning korraldada fun-üritusi tänuks tubli töö eest.
	Mõned toimunud äramainimist väärivad ettevõtmised: Toidupanga abistamine,
	Tallinn Music Week, Teeme Ära talgud, Tallinna Vanalinna Päevad jne.

	</span>
	</p>
	<p> <span style="font-family: tahoma, arial, helvetica, sans-serif;">
	Vabatahtlike klubi on avatud kõikidele huvilistele,
	kuid eelkõige Kesklinna noortekeskuse sihtrühmale,
	milleks on noored vanuses 16-26.
	<br><br>		Lisainfo: </span>
		<span id="cloak35897"><a
				href="mailto:ivar@tallinnanoored.ee">ivar@tallinnanoored.ee</a>
	</span><script type="text/javascript">
		</script></p>

	<a href="?logout=1">Logi välja</a>

</div>


