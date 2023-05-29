<?php 
    require_once 'auth.php';
    if ((!$userid = checkAuth()) || (!isset($_POST['idScheda']))) {
        header("Location: home.php");
        exit;
    }
    if(isset($_SESSION['idScheda'])) unset($_SESSION['idScheda']);
    $_SESSION['idScheda'] = $_POST['idScheda'];
?>

<html>
    <head>
        <title>Aggiungi esercizi | GymLemon</title>
        <link rel="stylesheet" href="page.css">
        <link rel="stylesheet" href="crea_scheda.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.png">
        <script src="crea_scheda.js" defer="true"></script>
        <script src="sidebar.js" defer="true"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="SHA-384-PlaceholderForIntegrityValue" crossorigin="anonymous">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700|Roboto+Condensed:400,400i" />
    </head>
    <body>
        <header>
            <div id="left_header">
                <a href="home.php">
                    <img id="icon" src="images/icon_bg.png" alt="">
                    <img id="logo" src="images/logo.png" alt="">
                </a>
            </div>
            <div id="main_header">
                <div> 
                    <button id="hamburger"><i class="fas fa-bars"></i></button>
                    <a href="home.php">
                        <img id="icon" src="images/icon_bg.png" alt="">
                        <img id="logo" src="images/logo.png" alt="">
                    </a>
                </div>
                Aggiungi esercizi - 
                <?php 
                    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
                    $query = "SELECT nome FROM schede WHERE id='".$_SESSION['idScheda']."'";
                    $res = mysqli_query($conn, $query);
                    if($res){
                        $row = mysqli_fetch_assoc($res);
                        echo $row['nome'];
                    }
                    mysqli_close($conn);
                ?>
            </div>
        </header>

        <article id="page-wrapper">
            <nav id="left">
                <a href="home.php"><i class="fas fa-home"></i> Home</a>
                <a href='le_mie_schede.php'><i class='fas fa-dumbbell'></i> Le mie schede</a>
                <a href='profilo.php'><i class='fas fa-user'></i> Il mio profilo</a>
                <a href='logout.php'><i class='fas fa-sign-out-alt'></i> Esci</a>
            </nav>
            <article id="main">

                <form method="post" name='ricerca' autocomplete="off">
                    <label><input type="search" placeholder="Cerca un esercizio (es: press)" name="search"></label>
                    <div><input type="submit" name="submit" value="Cerca"></div>
                </form>

                <article id="container"></article>
            </article>  

            <article id="modale" class="hidden"></article>
        </article>
    </body>
</html>