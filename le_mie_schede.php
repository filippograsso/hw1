<?php 
    require_once 'auth.php';
    if (!$userid = checkAuth()) {
        header("Location: home.php");
        exit;
    }
?>

<html>
    <head>
        <title>Le mie schede | GymLemon</title>
        <link rel="stylesheet" href="page.css">
        <link rel="stylesheet" href="le_mie_schede.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.png">
        <script src="le_mie_schede.js" defer="true"></script>
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
                    Le mie schede
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

                <button id="creaScheda">Crea scheda</button>
                <p id="msgSchede" class="msg hidden"></p>

                <article id="container"></article>

                <article id="modaleForm" class="modale hidden">
                    <section>
                        <form method="post" name="creaScheda">
                            <img src="images/icon_bg.png">
                            <h2>Crea scheda</h2>
                            <label><input type="text" name="nome" placeholder="Nome*:" maxlength="20" required></label>
                            <label>
                                <select name="tipo">
                                    <option value="" disabled selected>Tipo</option>
                                    <option value="Forza">Forza</option>
                                    <option value="Ipertrofia">Ipertrofia</option>
                                    <option value="Resistenza">Resistenza</option>
                                    <option value="Funzionale">Funzionale</option>
                                    <option value="Hiit">HIIT</option>
                                </select>
                            </label> 
                            
                            <label>
                                <select name="giorno">
                                    <option value="" disabled selected>Giorno</option>
                                    <option value="Lunedi">Lunedì</option>
                                    <option value="Martedi">Martedì</option>
                                    <option value="Mercoledi">Mercoledì</option>
                                    <option value="Giovedi">Giovedì</option>
                                    <option value="Venerdi">Venerdì</option>
                                    <option value="Sabato">Sabato</option>
                                    <option value="Domenica">Domenica</option>
                                </select>
                            </label> 
                            <section>
                                <div id='nomeCategorie'>Categorie: </div>
                                <div id='divCategorie'>
                                    <label>Abs: <input type="checkbox" name="categoria[]" value="Abs"></label> 
                                    <label>Arms: <input type="checkbox" name="categoria[]" value="Arms"></label> 
                                    <label>Back: <input type="checkbox" name="categoria[]" value="Back"></label> 
                                    <label>Calves: <input type="checkbox" name="categoria[]" value="Calves"></label> 
                                    <label>Cardio: <input type="checkbox" name="categoria[]" value="Cardio"></label> 
                                    <label>Chest: <input type="checkbox" name="categoria[]" value="Chest"></label> 
                                    <label>Legs: <input type="checkbox" name="categoria[]" value="Legs"></label> 
                                    <label>Shoulders: <input type="checkbox" name="categoria[]" value="Shoulders"></label> 
                                </div>
                            </section>
                            <section>
                                <label class='centrale'>Scheda pubblica<input type="radio" name="pubblica" value="1"></label>
                                <label class='centrale'>Scheda privata<input type="radio" name="pubblica" value="0" checked></label>
                                </section>    
                            <label>
                                <input type="submit" value="Crea scheda" name="submit">
                            </label>
                        </form>
                    </section>
                </article>

                <article id="modaleScheda" class="modale hidden"></article>
            </article>
        </article>
        
    </body>
</html>