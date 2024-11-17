<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/favicon.ico">
    <title>OpenSimulator Manager Web</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container">
<h1>Open Simulator Manager Web Installer</h1>

<?php if (!isset($_POST['etape'])): ?>

<form class="form-horizontal" action="" method="post">
    <input type="hidden" name="etape" value="1" />

    <div class="form-group">
    <label for="hote" class="col-sm-2 control-label">IP server Host :</label>
        <div class="col-sm-4">
            <input class="form-control" type="text" name="server" maxlength="40" />
        </div>
    </div>

    <div class="form-group">
    <label for="hote" class="col-sm-2 control-label">Database Host :</label>
        <div class="col-sm-4">
            <input class="form-control" type="text" name="hote" maxlength="40" />
        </div>
    </div>

    <div class="form-group">
    <label for="login" class="col-sm-2 control-label">Database User :</label>
        <div class="col-sm-4">
            <input class="form-control" type="text" name="login" maxlength="40" />
        </div>
    </div>

    <div class="form-group">
    <label for="mdp" class="col-sm-2 control-label">Database Password :</label>
        <div class="col-sm-4">
            <input class="form-control" type="password" name="mdp" maxlength="40" />
        </div>
    </div>

    <div class="form-group">
    <label for="base" class="col-sm-2 control-label">Database Name :</label>
        <div class="col-sm-4">
            <input class="form-control" type="text" name="base" maxlength="40" />
        </div>
    </div>

    <div class="form-group">
    <label for="base" class="col-sm-2 control-label">Username SSH :</label>
        <div class="col-sm-4">
            <input class="form-control" type="text" name="ussh" maxlength="40" />
        </div>
    </div>
    <div class="form-group">
    <label for="base" class="col-sm-2 control-label">Password SSH :</label>
        <div class="col-sm-4">
            <input class="form-control" type="text" name="pssh" maxlength="40" />
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button class="btn btn-success" type="submit" name="submit" value="Installer">Install</button>
        </div>
    </div>

</form>

<?php endif ?>

<?php if (isset($_POST['delete']))
{
    unlink('install.php');
    header('Location: ./');
}
?>

<?php
/*
 foreach($_POST as $key => $val) echo '$_POST["'.$key.'"]='.$val.'<br />';
 foreach($_GET as $key => $val) echo '$_GET["'.$key.'"]='.$val.'<br />';
 */
if (isset($_POST['etape']) AND $_POST['etape'] == 1)
{
    // on crée une constante dont on se servira plus tard
    define('RETOUR', '<input class="btn btn-primary" type="button" value="Return of form" onclick="history.back()">');

    $fichier = './inc/config/config.php';

    if (file_exists($fichier) AND filesize($fichier ) > 0)
    {
        // si le fichier existe et qu'il n'est pas vide alors
        exit('<div class="alert alert-danger">Not this configuration file, installation corrupt ...</div>'. RETOUR);
    }

    // on crée nos variables, et au passage on retire les éventuels espaces	
	$server   = trim($_POST['server']);
    $hote   = trim($_POST['hote']);
    $login  = trim($_POST['login']);
    $pass   = trim($_POST['mdp']);
    $base   = trim($_POST['base']);
	if(isset($_POST['ussh'])){$ussh = $_POST['ussh'];}
	if(isset($_POST['pssh'])){$pssh = $_POST['pssh'];}

	$a = str_replace("-","",implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 40), 10)));
	$b = str_replace("-","",implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 40), 10)));
	$api_key = $a.$b;

    // le texte que l'on va mettre dans le config.php
    $texte = '
		<?php
		$hostname = "'. $server .'";		// IP of server
		$hostnameBDD = "'. $hote .'";		// IP of Bdd
		$userBDD = "'. $login .'";       	// login
		$passBDD  = "'. $pass .'";     	// password
		$database = "'. $base .'"; 		// Name of BDD

		//FOR LINUX HOST SIMULATOR
		$usernameSSH    = "'.$ussh.'";
		$passwordSSH    = "'.$pssh.'";

		$api_key="'.$api_key.'";

		// Languages 
		// fr / en /it
		$lang = "fr";

		?>';

    if (!$ouvrir = fopen($fichier, 'w'))
    {
        exit('<div class="alert alert-danger">Unable to open file : <strong>'. $fichier .'</strong>, installation corrupt ...</div>'. RETOUR);
    }

    if (fwrite($ouvrir, $texte) == FALSE)
    {
        exit('<div class="alert alert-danger">Can not write to the file : <strong>'. $fichier .'</strong>, installation corrupt ...</div>'. RETOUR);
    }

    echo '<div class="alert alert-success">Creation of effected configuration file with success ...</div>';
    fclose($ouvrir);

    // on vérifie la connectivité avec le serveur avant d'aller plus loin
	try{		$bdd = new PDO('mysql:host='.$hote.';charset=utf8', $login, $pass);	}
	catch (Exception $e){		die('Erreur : ' . $e->getMessage());	}

	$reponse = $bdd->query("CREATE DATABASE IF NOT EXISTS `".$base."`;");
	$reponse = $bdd->query("USE `".$base."`;");
 
	$reponse = $bdd->query("CREATE TABLE IF NOT EXISTS `config` (
		  `id` int NOT NULL AUTO_INCREMENT,
		  `cheminAppli` varchar(50) NOT NULL,
		  `destinataire` varchar(50) NOT NULL,
		  `Autorized` int NOT NULL,
		  `NbAutorized` int NOT NULL,
		  `VersionOSMW` varchar(50) NOT NULL,
		  `urlOSMW` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;");
	
	$reponse = $bdd->query("INSERT INTO `config` (`id`, `cheminAppli`, `destinataire`, `Autorized`, `NbAutorized`, `VersionOSMW`, `urlOSMW`) VALUES
		(1, '/OSMW/', 'mymail@domain.com', 1, 4, 'Version 24.11', 'https://www.domain.com/');");	
		
	$reponse = $bdd->query("CREATE TABLE IF NOT EXISTS `moteurs` (
		  `osAutorise` tinyint NOT NULL AUTO_INCREMENT,
		  `id_os` varchar(50) NOT NULL,
		  `name` varchar(50) NOT NULL,
		  `version` varchar(50) NOT NULL,
		  `address` varchar(50) NOT NULL,
		  `DB_OS` varchar(50) NOT NULL,
		  `hypergrid` varchar(255) NOT NULL,
		  PRIMARY KEY (`osAutorise`)
		) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;");

	$reponse = $bdd->query("INSERT INTO `moteurs` (`osAutorise`, `id_os`, `name`, `version`, `address`, `DB_OS`, `hypergrid`) VALUES
		(1, 'Simulateur_1_Exemple', 'Whitman Corporation', 'Opensim 0.9.3', 'C:/OPENSIM/', 'addon-modules/osgrid/config/osgrid.ini', 'hg.osgrid.org:80');");

	$reponse = $bdd->query("CREATE TABLE IF NOT EXISTS `users` (
		  `id` int NOT NULL AUTO_INCREMENT,
		  `firstname` varchar(15) NOT NULL,
		  `lastname` varchar(15) NOT NULL,
		  `password` text NOT NULL,
		  `privilege` int NOT NULL,
		  `osAutorise` varchar(50) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;");

	$reponse = $bdd->query("INSERT INTO `users` (`id`, `firstname`, `lastname`, `password`, `privilege`, `osAutorise`) VALUES
		(1, 'Super', 'Admin', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 4, '');");


    echo '<div class="alert alert-success">Installing the database tables of data effected with success...</div>';
    echo '<div class="alert alert-warning">Please delete the file <strong>install.php</strong> of server ...</div>';
    echo '<form class="form-group" action="" method="post">';
    echo '<input type="hidden" name="delete" value="1" />';
    echo '<div class="form-group">';
    echo '<button class="btn btn-danger" type="submit" name="submit" >Delete file install.php</button>';
    echo '</div>';
    echo '</form>';
	
}

?>
<div class="clearfix"></div>

<footer class="footer">
    <p><CENTER>Open Simulator Manager Web Intaller <?php echo date('Y'); ?></CENTER></p>
</footer>
</div>

</body>
</html>
