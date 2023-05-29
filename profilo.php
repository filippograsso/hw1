<?php 
    require_once 'auth.php';
    if (!$userid = checkAuth()) {
        header("Location: home.php");
        exit;
    }

    if(!empty($_POST['nome']) && !empty($_POST['cognome']) && !empty($_POST['data']) && !empty($_POST['username'])){
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

        $error = [];

        if(!preg_match("/^[a-zA-Z]+([\-'\s][a-zA-Z]+)*$/", $_POST['nome']))
            $error["nome"] = "Inserisci un nome valido";
        else $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        
        if(!preg_match("/^[a-zA-Z]+([\-'\s][a-zA-Z]+)*$/", $_POST['cognome']))
            $error["cognome"] = "Inserisci un cognome valido";
        else $cognome = mysqli_real_escape_string($conn, $_POST['cognome']);
        
        try {
            $oggi = new DateTime();
            $nascita = new DateTime($_POST['data']);
            $differenzaAnni = $nascita->diff($oggi)->y;
                
            if ($differenzaAnni < 18 || $differenzaAnni > 100) {
                $error["data"] = "La tua età deve essere compresa tra i 18 e i 100 anni";
            }else $data = mysqli_real_escape_string($conn, $_POST['data']);

        } catch (Exception $e) {
            $error["data"] = "visible";
        }
        
        if (isset($_POST['genere']) && strcmp($_POST['genere'], "Uomo") !== 0 && strcmp($_POST['genere'], "Donna") !== 0 && strcmp($_POST['genere'], "Altro") !== 0) {
            $error["genere"] = "Il genere deve essere uno dei 3 proposti";
        } else if(!isset($_POST['genere'])){
            $genere = null;
        } else $genere = $_POST['genere'];

        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            $error["email"] = "Inserisci un'email valida";
        else{
            $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
 
            $query = "SELECT email FROM users WHERE email = '$email' and id <> '$userid'";
            $res = mysqli_query($conn, $query);
            if (mysqli_num_rows($res) > 0) {
                $error["email"] = "Email già utilizzata";
            }
        }

        if(!preg_match("/^[a-zA-Z0-9_]{1,20}$/", $_POST['username']))
            $error["username"] = "Lo username deve avere max. 20 caratteri (alfanumerici o underscore)";
        else{
            $username = mysqli_real_escape_string($conn, $_POST['username']);
 
            $query = "SELECT username FROM users WHERE username = '$username' and id <> '$userid'";
            $res = mysqli_query($conn, $query);
            if (mysqli_num_rows($res) > 0) {
                $error["username"] = "Username già utilizzato";
            }
        }
        if(!empty($_POST['password']) && !empty($_POST['confermaPassword'])){
            if(!preg_match("/^(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $_POST['password']))
                $error["password"] = "La password deve avere almeno 8 caratteri, di cui almeno una lettera maiuscola e un numero";
            
            if(strcmp($_POST["password"], $_POST["confermaPassword"]) !== 0)
                $error["confermaPassword"] = "Le due password inserite non coincidono";        
        }
        $newsletter = isset($_POST['newsletter'] ) ? 1 : 0; 

        //print_r($_FILES);
        if (count($error) == 0 && $_FILES['propic']['size'] != 0) { 
            $file = $_FILES['propic'];
            $filePath = $_FILES['propic']['tmp_name'];
            $fileExtension = pathinfo($_FILES['propic']['name'], PATHINFO_EXTENSION);
            if (getimagesize($filePath)) {
                if ($file['error'] === 0) {
                    if ($file['size'] < 7000000) {
                        $fileNameNew = uniqid('', true).".".$fileExtension;
                        $fileDestination = 'users/'.$fileNameNew;
                        move_uploaded_file($file['tmp_name'], $fileDestination);
                    } else {
                        $error["propic"] = "L'immagine non deve avere dimensioni maggiori di 7MB";
                    }
                } else {
                    $error["propic"] = "Errore nel carimento del file";
                }
            } else {
                $error["propic"] = "Il file deve essere un'immagine";
            }
        }

        if(!isset($fileDestination)){
            $query = "SELECT propic FROM users WHERE id='$userid'";
            $res = mysqli_query($conn, $query);
            if($res){
                $row = mysqli_fetch_assoc($res);
                $fileDestination = $row['propic'];
            }else $error['caricamento'] = "Qualcosa è andato storto nel caricamento dell'immagine";
        }

        if(count($error) == 0){
            if(!empty($_POST['password'])){
                $password = mysqli_real_escape_string($conn, $_POST['password']);
                $password = password_hash($password, PASSWORD_BCRYPT);
                $query = "UPDATE users SET nome='$nome', cognome='$cognome', dataNascita='$data', genere='$genere', email='$email', username='$username', password='$password', newsletter='$newsletter', propic='$fileDestination' where id='$userid'";
            }else $query = "UPDATE users SET nome='$nome', cognome='$cognome', dataNascita='$data', genere='$genere', email='$email', username='$username', newsletter='$newsletter', propic='$fileDestination' where id='$userid'";
            if (mysqli_query($conn, $query)) {
                $_SESSION["username"] = $username;
            }else $error["query"] = "Modifica non riuscita, riprova";
        }

        mysqli_close($conn);
    }else if (isset($_POST["username"])) {
        $error["campi"] = "Riempi tutti i campi obbligatori";
    }
?>

<html>
    <head>
        <title><?php echo $_SESSION["username"]?> | GymLemon</title>
        <link rel="stylesheet" href="page.css">
        <link rel="stylesheet" href="profilo.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.png">
        <script src="profilo.js" defer="true"></script>
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
                    Il mio profilo - <?php echo $_SESSION['username']?>
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
                
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="file" name="propic" id="propic">
                        <article id="container"></article>
                    </form>
                
                <?php 
                    if(isset($error) && !empty($error)){
                        $firstKey = array_key_first($error);
                        $firstVal = $error[$firstKey];
                        echo"<span id='errore'>".$firstVal."</span>";
                    }
                    else if(!empty($_POST['nome'])) echo"<span id='successo'>Modifica effettuata con successo</span>";
                ?>
            </article>
        </article>
    </body>
</html>