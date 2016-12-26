<?php
//edit.php
require("functions.php");

//kas kasutaja uuendab andmeid
if(isset($_POST["update"])){

    update_voluntary_work(cleanInput($_POST["id"]), cleanInput($_POST["event_name"]), cleanInput($_POST["place"]), cleanInput($_POST["description"]), cleanInput($_POST["date"]), cleanInput($_POST["time"]));

    header("Location: edit.php?id=".$_POST["id"]."&success=true");
    exit();

}

if(isset($_GET["delete"])){

    delete($_GET["id"]);

    header("Location: vabatahtlik_too_form.php");
    exit();
}

//kui ei ole id-d aadressireal siis suunan
if(!isset($_GET["id"])){
    header("Location: vabatahtlik_too_form.php");
    exit();
}
//saadan kaasa id
$m = getSingle_voluntary_work($_GET["id"]);
//var_dump($m);

?>

<head>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <script type="text/javascript" src="js/jquery-1.11.3.js"></script>
</head>
<a href="vabatahtlik_too_form.php"> Tagasi </a>
<h1>Muuda vabatahtliku töö andmeid:</h1>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >

    <h3>Ürituse nimi</h3>

    <input id="event_name" name = "event_name" placeholder="Ürituse nimi" type=text value="<?php echo $m->event_name;?>"> <br><br>

    <h3>Asukoht</h3>

    <input id="place" name="place" placeholder="Asukoht" type="text" value="<?php echo $m->place;?>"> <br><br>

    <h3>Sisestage ürituse kirjeldus</h3>

    <input id="description" name="description" placeholder="Kirjeldus" type="text" value="<?php echo $m->description;?>"> <br><br>

    <h3>Toimumise kuupäev</h3>

    <input id="date" name="date" placeholder="Kuupäev" type="date" value="<?php echo $m->date;?>"> <br><br>
    <h3>Toimumise kellaaeg</h3>
    <input id="time" name="time" placeholder="Kellaaeg" min="00:00:00" max="23:00:00" type="time" value="<?php echo $m->time;?>"> <br><br>

    <input type="submit" name="update" class="btn btn-info" value="Sisesta">
    <br>
    <br>


    <a href="?id=<?=$_GET["id"];?>&delete=true">Kustuta</a>
</form>
