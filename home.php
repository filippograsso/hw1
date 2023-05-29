<?php 
    require_once 'auth.php';
    if(isset($_POST['login']) && !empty($_POST['usernameEmail']) && !empty($_POST['passwordLogin'])){
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
        
        $usernameEmail = mysqli_real_escape_string($conn, $_POST['usernameEmail']);

        $query = "SELECT * FROM users WHERE username = '".$usernameEmail."'";
        $res1 = mysqli_query($conn, $query) or die(mysqli_error($conn));;

        $query = "SELECT * FROM users WHERE email = '".$usernameEmail."'";
        $res2 = mysqli_query($conn, $query) or die(mysqli_error($conn));;

        if (mysqli_num_rows($res1)>0 || mysqli_num_rows($res2)>0) {
            $entry = mysqli_num_rows($res1)>0 ? mysqli_fetch_assoc($res1) : mysqli_fetch_assoc($res2);

            if (password_verify($_POST['passwordLogin'], $entry['password'])) {
                $_SESSION["username"] = $entry['username'];
                $_SESSION["user_id"] = $entry['id'];
            } else $accessoNegato = "La password è errata";
        }else $accessoNegato = "Email o Username inserito non registrato";

        mysqli_free_result($res1);
        mysqli_free_result($res2);
        mysqli_close($conn);

    }else if(isset($_POST['signup']) && !empty($_POST['nome']) && !empty($_POST['cognome']) && !empty($_POST['data']) && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['confermaPassword']) && !empty($_POST['termini'])){
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

        $error = [];

        if(!preg_match("/^[a-zA-Z]+([\-'\s][a-zA-Z]+)*$/", $_POST['nome']))
            $error["nome"] = "visible";
        else $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        
        if(!preg_match("/^[a-zA-Z]+([\-'\s][a-zA-Z]+)*$/", $_POST['cognome']))
            $error["cognome"] = "visible";
        else $cognome = mysqli_real_escape_string($conn, $_POST['cognome']);
        
        try {
            $oggi = new DateTime();
            $nascita = new DateTime($_POST['data']);
            $differenzaAnni = $nascita->diff($oggi)->y;
                
            if ($differenzaAnni < 18 || $differenzaAnni > 100) {
                $error["data"] = "visible";
            }else $data = mysqli_real_escape_string($conn, $_POST['data']);

        } catch (Exception $e) {
            $error["data"] = "visible";
        }
        
        if (isset($_POST['genere']) && strcmp($_POST['genere'], "Uomo") !== 0 && strcmp($_POST['genere'], "Donna") !== 0 && strcmp($_POST['genere'], "Altro") !== 0) {
            $error["genere"] = "visible";
        } else if(!isset($_POST['genere'])){
            $genere = null;
        } else $genere = $_POST['genere'];

        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            $error["email"] = "Inserisci un'email valida";
        else{
            $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
 
            $query = "SELECT email FROM users WHERE email = '$email'";
            $res = mysqli_query($conn, $query);
            if (mysqli_num_rows($res) > 0) {
                $error["email"] = "Email già utilizzata";
            }
        }

        if(!preg_match("/^[a-zA-Z0-9_]{1,20}$/", $_POST['username']))
            $error["username"] = "Lo username deve avere max. 20 caratteri (alfanumerici o underscore)";
        else{
            $username = mysqli_real_escape_string($conn, $_POST['username']);
 
            $query = "SELECT username FROM users WHERE username = '$username'";
            $res = mysqli_query($conn, $query);
            if (mysqli_num_rows($res) > 0) {
                $error["username"] = "Username già utilizzato";
            }
        }
        
        if(!preg_match("/^(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $_POST['password']))
            $error["password"] = "visible";
        
        if(strcmp($_POST["password"], $_POST["confermaPassword"]) !== 0)
            $error["confermaPassword"] = "visible";        
        
        $newsletter = isset($_POST['newsletter']) ? 1 : 0; 

        if(count($error) == 0){
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $password = password_hash($password, PASSWORD_BCRYPT);
            //Gli altri parametri li ho già controllati precedentemente 
            $query = "INSERT INTO users(nome, cognome, dataNascita, genere, email, username, password, newsletter) VALUES('$nome', '$cognome', '$data', '$genere', '$email', '$username', '$password', '$newsletter')";
    
            if (mysqli_query($conn, $query)) {
                $_SESSION["username"] = $username;
                $_SESSION["user_id"] = mysqli_insert_id($conn);
            }else $error["campi"] = "Registrazione non riuscita, riprova";
        }

        mysqli_close($conn);
    }else if (isset($_POST["username"])) {
        $error["campi"] = "Riempi tutti i campi obbligatori";
    }
?>

<html>
    <head>
        <title>Home | GymLemon</title>
        <link rel="stylesheet" href="page.css">
        <link rel="stylesheet" href="home.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.png">
        <script src="home.js" defer="true"></script>
        <script src="sidebar.js" defer="true"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="SHA-384-PlaceholderForIntegrityValue" crossorigin="anonymous">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700|Roboto+Condensed:400,400i" />
    </head>
    <body <?php if(isset($accessoNegato)||isset($error)) echo "class='no-scroll'";?>>
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
                    Home - Schede pubbliche
                </div>
        </header>

        <article id="page-wrapper">
            <nav id="left">
                <a href="home.php"><i class="fas fa-home"></i> Home</a>
                <?php 
                    if(isset($_SESSION['user_id'])) 
                        echo "<a href='le_mie_schede.php'><i class='fas fa-dumbbell'></i> Le mie schede</a>
                        <a href='profilo.php'><i class='fas fa-user'></i> Il mio profilo</a>
                        <a href='logout.php'><i class='fas fa-sign-out-alt'></i> Esci</a>";
                    else echo "<a id='loginNav'><i class='fas fa-sign-in-alt'></i> Accedi</a>
                        <a id='signupNav'><i class='fas fa-user-plus'></i> Registrati</a>";
                    ?>
                
            </nav>
            <article id="main">


                <?php
                if (!isset($_SESSION['user_id'])) {
                ?>
                <section class="modale <?php echo (!isset($error) ? "hidden" : "");?>">
                    <form name="registrazione" method="post" enctype="application/x-www-form-urlencoded" autocomplete="off">
                        <img src="images/icon_bg.png">
                        <h2>SIGNUP</h2>
                        <label><input type="text" name="nome" maxlength="30" placeholder="Nome*:"<?php if(isset($_POST["nome"])){echo "value=".$_POST["nome"];} ?>></label>
                        <span id="erroreNome" <?php if (isset($error) && array_key_exists("nome", $error)) echo "class=".$error["nome"]; ?>>
                            Inserisci un nome valido
                        </span>
                        <label><input type="text" name="cognome" maxlength="30" placeholder="Cognome*:" <?php if(isset($_POST["cognome"])){echo "value=".$_POST["cognome"];} ?>></label>
                        <span id="erroreCognome" <?php if (isset($error) && array_key_exists("cognome", $error)) echo "class=".$error["cognome"]; ?>>
                            Inserisci un cognome valido
                        </span>
                        <label><input type="text" name="data" placeholder='Data di nascita*:' onfocus="(this.type='date')" onblur="(this.type='text')"<?php if(isset($_POST["data"])){echo "value=".$_POST["data"];} ?>></label>
                        <span id="erroreData" <?php if (isset($error) && array_key_exists("data", $error)) echo "class=".$error["data"]; ?>>
                            La tua età deve essere compresa tra i 18 e i 100 anni
                        </span>
                        <div>Genere: 
                            <div>
                                <label>Uomo<input type="radio" name="genere" value="Uomo"></label>
                                <label>Donna<input type="radio" name="genere" value="Donna"></label>
                                <label>Altro<input type="radio" name="genere" value="Altro"></label>
                            </div>
                        </div>
                        <span id="erroreGenere" <?php if (isset($error) && array_key_exists("genere", $error)) echo "class=".$error["genere"]; ?>>
                            Il genere deve essere uno dei 3 proposti
                        </span>
                        <label><input type="email" name="email" placeholder="Email*:"<?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?>></label>
                        <span id="erroreEmail" <?php if (isset($error) && array_key_exists("email", $error)) echo 'class="visible"';?>>
                            <?php if (isset($error) && array_key_exists("email", $error)) echo $error["email"];?>
                        </span>
                        <label><input type="text" name="username" maxlength="20" placeholder="Username*:"<?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>></label>
                        <span id="erroreUsername" <?php if (isset($error) && array_key_exists("username", $error)) echo 'class="visible"';?>>
                            <?php if (isset($error) && array_key_exists("username", $error)) echo $error["username"];?>
                        </span>
                        <label>
                            <input type="password" name="password" placeholder="Password*:">
                            <button class="showPasswordButton">
                                <i class="fas fa-eye"></i>
                            </button>
                        </label>
                        <span id="errorePassword" <?php if (isset($error) && array_key_exists("password", $error)) echo "class=".$error["password"]; ?>>La password deve avere almeno 8 caratteri, di cui almeno una lettera maiuscola e un numero</span>
                        <label> 
                            <input type="password" name="confermaPassword" placeholder="Conferma password*:">
                            <button class="showPasswordButton">
                                <i class="fas fa-eye"></i>
                            </button>
                        </label>
                        <span id="erroreConferma" <?php if (isset($error) && array_key_exists("confermaPassword", $error)) echo "class=".$error["confermaPassword"]; ?>>Le due password inserite non coincidono</span>
                        <label><input type="checkbox" name="newsletter">  Voglio essere iscritto alla newsletter per rimanere sempre aggiornato sulle ultime novità</label>
                        <label><input type="checkbox" name="termini" required> Confermo di aver letto e accettato i termini e le condizioni d'uso del sito*</label>
                        <span id="erroreCampi" <?php if (isset($error) && array_key_exists("campi", $error)) echo "class=visible"; ?>>
                            <?php if (isset($error) && array_key_exists("campi", $error)) echo $error['campi'];?>
                        </span>
                        <?php if(isset($_POST["signup"]) && isset($_SESSION['username'])) echo "<h5>".$_SESSION['username']." ti sei registrato correttamente!</h5>"?>
                        <label><input type="submit" value="Registrati" name="signup"></label>
                    </form>
                </section>
                <?php
                }
                ?>

                <?php
                if (!isset($_SESSION['user_id'])) {
                    echo '
                    <section class="modale ' . (!isset($accessoNegato) ? 'hidden' : '') . '">
                        <form name="login" method="post">
                            <img src="images/icon_bg.png">
                            <h2>LOGIN</h2>
                            <label><input type="text" name="usernameEmail" placeholder="Username o Email" required ' . (isset($_POST["usernameEmail"]) ? 'value="' . $_POST["usernameEmail"] . '"' : '') . '></label>
                            <label>
                                <input type="password" name="passwordLogin" placeholder="Password" required>
                                <button class="showPasswordButton" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </label>
                            <label><input type="submit" value="Accedi" name="login"></label>
                            <span id="accessoNegato" ' . (isset($accessoNegato) ? 'class="visible"' : '') . '>
                                ' . (isset($accessoNegato) ? $accessoNegato : '') . '
                            </span>
                        </form>
                    </section>
                    ';
                }
                ?>

                <article id="container"></article>
            </article>
            

            <article id="modaleScheda" class="hidden modale"></article>
        </article>
    </body>
</html>