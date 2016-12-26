<?php
require("functions.php");
// kui kasutaja on sisseloginud, siis suuna avaleheküljele lehele
if(isset ($_SESSION["userId"])) {
	header("Location: avalehekülg.php");
}
//var_dump($_GET);
//echo "<br>";
//var_dump($_POST);
$signupEmailError = "";
$signupEmail = "";
//kas on üldse olemas
if (isset ($_POST["signupEmail"])) {
	// oli olemas, ehk keegi vajutas nuppu
	// kas oli tühi
	if (empty ($_POST["signupEmail"])) {
		//oli tõesti tühi
		$signupEmailError = "See väli on kohustuslik";
	} else {
		// kõik korras, email ei ole tühi ja on olemas
		$signupEmail = $_POST["signupEmail"];
	}
}
$signupPasswordError = "";
//kas on üldse olemas
if (isset ($_POST["signupPassword"])) {
	// oli olemas, ehk keegi vajutas nuppu
	// kas oli tühi
	if (empty ($_POST["signupPassword"])) {
		//oli tõesti tühi
		$signupPasswordError = "See väli on kohustuslik";
	} else {
		// oli midagi, ei olnud tühi
		// kas pikkus vähemalt 8
		if (strlen ($_POST["signupPassword"]) < 8 ) {
			$signupPasswordError = "Parool peab olema vähemalt 8 tm pikk";
		}
	}
}
$gender = "";
if(isset($_POST["gender"])) {
	if(!empty($_POST["gender"])){
		//on olemas ja ei ole tühi
		$gender = $_POST["gender"];
	}
}
if ( isset($_POST["signupEmail"]) &&
	isset($_POST["signupPassword"]) &&
	$signupEmailError == "" &&
	empty($signupPasswordError)
) {
	// ühtegi viga ei ole, kõik vajalik olemas
	echo "salvestan...<br>";
	echo "email ".$signupEmail."<br>";
	//echo "parool ".$_POST["signupPassword"]."<br>";
	$password = hash("sha512", $_POST["signupPassword"]);
	//echo "räsi ".$password."<br>";
	echo"Kasutaja loodud<br>";
	//kutsun funktsiooni, et salvestada
	signup($signupEmail, $password, $gender);
}
$notice = "";
// mõlemad login vormi väljad on täidetud
if (	isset($_POST["loginEmail"]) &&
	isset($_POST["loginPassword"]) &&
	!empty($_POST["loginEmail"]) &&
	!empty($_POST["loginPassword"])
) {
	$notice = login($_POST["loginEmail"], $_POST["loginPassword"]);
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Sisselogimise leht</title>

</head>
<body>

<div class="fieldBlock1">

	<h1>Logi sisse</h1>


	<link rel="stylesheet" href="style1.css">

	<p style="color:red;"><?php echo $notice; ?></p>
	<form method="POST">


		<input placeholder="E-mail" name="loginEmail" type="email" >


		<input placeholder="parool" name="loginPassword" type="password">


		<input type="submit" value="Logi sisse">



	</form>

</div>


<div class="fieldBlock2">

	<h1>Loo kasutaja</h1>

	<form method="POST">



		<input placeholder="E-mail" name="signupEmail" type="email" value="<?=$signupEmail;?>" > <?php echo $signupEmailError; ?>


		<input placeholder="Parool" name="signupPassword" type="password" > <?php echo $signupPasswordError; ?>


		<?php if ($gender == "male") { ?>
			<input type="radio" name="gender" value="male" checked > Mees
		<?php } else { ?>
			<input type="radio" name="gender" value="male"> Mees
		<?php } ?>

		<?php if ($gender == "female") { ?>
			<input type="radio" name="gender" value="female" checked > Naine
		<?php } else { ?>
			<input type="radio" name="gender" value="female"> Naine<br>
		<?php } ?>

		<?php if ($gender == "other") { ?>
			<input type="radio" name="gender" value="other" checked > Muu<br>
		<?php } else { ?>
			<input type="radio" name="gender" value="other"> Muu<br>
		<?php } ?>

		<input type="submit" value="Loo kasutaja">

	</form>
</div>
</body>
</html>