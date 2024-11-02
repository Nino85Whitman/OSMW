
<?php 
/*
 foreach($_POST as $key => $val) echo '$_POST["'.$key.'"]='.$val.'<br />';
 foreach($_GET as $key => $val) echo '$_GET["'.$key.'"]='.$val.'<br />';
*/


/*
// Return Ok else NOK
//http://YOUR_OSMW_WEBSERVER/api/?api_key=API_KEY_CONFIG&opensim_select=NAME_OPENSIMULATOR&cmd=COMMANDE
// COMMANDE FOR SIMULATOR:
Start
Stop
GenerateMap
WindlightEnable
WindlightDisable
WindlightLoad
StartLogin
StopLogin
StatusLogin

kick_user&avatar_name=AVATAR_NAME
Estate_NameSet&estate_name=ESTATE
Estate_OwnerSet&estate_owner=AVATAR_NAME

//http://YOUR_OSMW_WEBSERVER/api/?api_key=API_KEY_CONFIG&opensim_select=NAME_OPENSIMULATOR&cmd_tmux=COMMANDE
// COMMANDE FOR TMUX:
tmux_load
tmux_kill

//http://YOUR_OSMW_WEBSERVER/api/?api_key=API_KEY_CONFIG&cmd_tmux=COMMANDE
// COMMANDE FOR TMUX:
tmux_load_all
tmux_kill_all

//http://YOUR_OSMW_WEBSERVER/api/?api_key=API_KEY_CONFIG&opensim_select=NAME_OPENSIMULATOR&cmd_gest=COMMANDE
// COMMANDE DE GESTION
list_simulateurs
list_regions

*/
	include ('../inc/config/config.php');
	include ('../inc/config/fonctions.php');
	include ('../inc/config/radmin.php');
	

if (isset($_GET['api_key']) || isset($_POST['api_key']))
{
	if ($_GET['api_key'] == $api_key || $_POST['api_key'] == $api_key)
	{
		$_SESSION['authentification_api'] = "autorized";
		$opensim_select ="";
		$messageInfo ="";
	
		if(isset($_GET['msg_alert'])){		if($_GET['msg_alert']){$msg_alert = $_GET['msg_alert'];}			}
		if(isset($_GET['avatar_name'])){	if($_GET['avatar_name']){$avatar_name = $_GET['avatar_name'];}		}
		if(isset($_GET['estate_name'])){	if($_GET['estate_name']){$estate_name = $_GET['estate_name'];}		}
		if(isset($_GET['estate_owner'])){	if($_GET['estate_owner']){$estate_owner = $_GET['estate_owner'];}	}
		
	}
	else{echo "ERROR API KEY";exit;}
}
else{echo "ERROR API KEY";exit;}
//############################################################################################################

if (isset($_SESSION['authentification_api']))
{
	//#################################################################################################################
	if ( isset($_GET['cmd']))
	{
		
		try{$bdd = new PDO('mysql:host='.$hostnameBDD.';dbname='.$database.';charset=utf8', $userBDD, $passBDD);}
		catch (Exception $e){       die('Erreur : ' . $e->getMessage());    }
		
		if(isset($_GET['opensim_select']))
		{
			$req_sql = "SELECT * FROM moteurs WHERE id_os ='".$_GET['opensim_select']."'";
			$reponse = $bdd->query($req_sql);	
			$data = $reponse->fetch();
			$FichierConfINIPrivate = $data['address']. $data['DB_OS'];
			$reponse->closeCursor(); 	
			 
			$tableauIniSimu = parse_ini_file($FichierConfINIPrivate, true);
			$RemotePort  =  $tableauIniSimu['RemoteAdmin']['port'] ;
			$access_password2  =  $tableauIniSimu['RemoteAdmin']['access_password'] ;
		}	
		else
		{
			
		}
		
		if(isset($_GET['cmd']))			{$CMD = $_GET['cmd'];}	else	{$CMD = 'NOK';}
		
		//echo $CMD ;
		
		if($CMD == 'get'){$messageInfo = "OK";}
		
		if($CMD == 'info'){$messageInfo = $_SERVER['SERVER_NAME'];}	
					
		if($CMD == 'Start')	
		{			
			if(PHP_OS == "Linux")
			{
				$cmd = 'tmux send-keys -t '.$data['name'].' "cd '.$data['address'].';./opensim.sh" Enter';
				CommandeSSH($hostname,$usernameSSH,$passwordSSH,$cmd);
			}
			
			if(PHP_OS == "WINNT")
			{
				$new_chemin = str_replace("/", "\\",$data['address'] );			
				$cmd ="start /D ".$new_chemin." OpenSim.exe";	
				exec( $cmd);					
			}			
		}	

		if($CMD == 'Stop')				
		{
			$parameters = array('command' => 'quit');

			if(PHP_OS == "Linux")
			{
				$cmd = "rm ".$data['address']."OpenSim.log";
				CommandeSSH($hostname,$usernameSSH,$passwordSSH,$cmd);
			}
			if(PHP_OS == "WINNT")
			{
				$new_chemin = str_replace("/", "\\",$data['address']);			
				$cmd ="del ".$new_chemin."\OpenSim.log";	
				exec($cmd);
			}	
		}
	
		if($CMD == 'GenerateMap')		{$parameters = array('command' => 'generate map');}
		if($CMD == 'WindlightEnable')	{$parameters = array('command' => 'windlight enable');}
		if($CMD == 'WindlightDisable')	{$parameters = array('command' => 'windlight disable');}
		if($CMD == 'WindlightLoad')		{$parameters = array('command' => 'windlight load');}
		if($CMD == 'StartLogin')		{$parameters = array('command' => 'login enable');}
		if($CMD == 'StopLogin')			{$parameters = array('command' => 'login disable');}
		if($CMD == 'StatusLogin')		{$parameters = array('command' => 'login status');}
		if($CMD == 'Alerte')			{$parameters = array('command' => 'alert '.$msg_alert);}
		if($CMD == 'kick_user')			{$kick = 'kick user '.$avatar_name.' ejected by administrator.' ; $parameters = array('command' =>  $kick );}
		if($CMD == 'Estate_NameSet')	{$estate_cmd = 'estate set name 101 "'.$estate_name.'"' ; $parameters = array('command' => $estate_cmd );}
		if($CMD == 'Estate_OwnerSet')	{$estate_cmd = 'estate set owner 101 '.$estate_owner ; $parameters = array('command' => $estate_cmd );}
				
		if($CMD=='ReloadEstate')	
		{
			$myRemoteAdmin = new RemoteAdmin(trim($hostname), trim($RemotePort), trim($access_password2));
			$myRemoteAdmin->SendCommand('admin_estate_reload',  array());
		}	
		
		//********************************************************************************************************
		if ($CMD!= '' AND $CMD!= 'get' AND $CMD!= 'info' AND $CMD!= 'Start')
		{
			$myRemoteAdmin = new RemoteAdmin(trim($hostname), trim($RemotePort), trim($access_password2));
			$retour_radmin = $myRemoteAdmin->SendCommand('admin_console_command', $parameters);
			$messageInfo = "OK";
		}
	}
	//#################################################################################################################
	if (isset($_GET['cmd_tmux']))	// Pour Config Linux
	{

		$osmw_simu =$_GET['opensim_select'];
		// on se connecte a MySQL
		try{$bdd = new PDO('mysql:host='.$hostnameBDD.';dbname='.$database.';charset=utf8', $userBDD, $passBDD);}
		catch (Exception $e){		die('Erreur : ' . $e->getMessage());	}
		
		if($_GET['cmd_tmux'] == 'tmux_load')
		{
			$cmd = 'tmux new -d -s '.$_GET['opensim_select'];
			CommandeSSH($hostname,$usernameSSH,$passwordSSH,$cmd);
			$messageInfo = "OK"; 
		}				
		if($_GET['cmd_tmux'] == 'tmux_kill')
		{
			$cmd = 'tmux kill-session -t '.$_GET['opensim_select'];
			CommandeSSH($hostname,$usernameSSH,$passwordSSH,$cmd);
			$messageInfo = "OK"; 
		}				
		if($_GET['cmd_tmux'] == 'tmux_kill_all')
		{
			// on se connecte a MySQL
			try{$bdd = new PDO('mysql:host='.$hostnameBDD.';dbname='.$database.';charset=utf8', $userBDD, $passBDD);}
			catch (Exception $e){		die('Erreur : ' . $e->getMessage());	}
			$reponse = $bdd->query('SELECT * FROM moteurs');
			// On affiche chaque entrée une à une
			while ($data = $reponse->fetch())
			{
					$cmd = 'tmux kill-session -t '.$data['name'];
					CommandeSSH($hostname,$usernameSSH,$passwordSSH,$cmd);
			}
			$messageInfo = "OK"; 
		}	
		if($_GET['cmd_tmux'] == 'tmux_load_all')
		{
			// on se connecte a MySQL
			try{$bdd = new PDO('mysql:host='.$hostnameBDD.';dbname='.$database.';charset=utf8', $userBDD, $passBDD);}
			catch (Exception $e){		die('Erreur : ' . $e->getMessage());	}
			$reponse = $bdd->query('SELECT * FROM moteurs');
			// On affiche chaque entrée une à une
			while ($data = $reponse->fetch())
			{
					$cmd = 'tmux new -d -s '.$data['name'];
					CommandeSSH($hostname,$usernameSSH,$passwordSSH,$cmd);
			}
			$messageInfo = "OK"; 
		}	
	}
	//#################################################################################################################
	if (isset($_GET['cmd_gest']))
	{
		$list ="";
		if($_GET['cmd_gest'] == 'list_simulateurs')
		{
			// on se connecte a MySQL
			try{$bdd = new PDO('mysql:host='.$hostnameBDD.';dbname='.$database.';charset=utf8', $userBDD, $passBDD);}
			catch (Exception $e){		die('Erreur : ' . $e->getMessage());	}
			
			$reponse = $bdd->query('SELECT * FROM moteurs');
			
			$list ='';
			// On affiche chaque entrée une à une
			while ($data = $reponse->fetch())
			{
				$list .= $data['id_os'].";";
			}
			$messageInfo = "OK;" . $list;
			
		}		
		//****************************************************************************************
		if($_GET['cmd_gest'] == 'list_regions')
		{

			try{$bdd = new PDO('mysql:host='.$hostnameBDD.';dbname='.$database.';charset=utf8', $userBDD, $passBDD);}
			catch (Exception $e){       die('Erreur : ' . $e->getMessage());    }
				
			$req_sql = "SELECT * FROM moteurs WHERE id_os ='".$_GET['opensim_select']."'";
			$reponse = $bdd->query($req_sql);	
			$data = $reponse->fetch();
			$FichierConfINIPrivate = $data['address']. $data['DB_OS'];
			$reponse->closeCursor(); 	
			 
			$tableauIniSimu = parse_ini_file($FichierConfINIPrivate, true);
			$RemotePort  =  $tableauIniSimu['RemoteAdmin']['port'] ;
			$access_password2  =  $tableauIniSimu['RemoteAdmin']['access_password'] ;
			
			$filename2 = $data['address']."Regions/Regions.ini";	 
		
			if (file_exists($filename2)) {$filename = $filename2;}
			$tableauIni = parse_ini_file($filename, true);
			if ($tableauIni == FALSE) {$messageInfo = "OK;";}

			// *** Recuperation du port Http du Simulateur
			$FichierConfINIPrivate = $data['address']. $data['DB_OS'];
			$tableauIniSimu = parse_ini_file($FichierConfINIPrivate, true);
			$srvOS  = $tableauIniSimu['Network']['http_listener_port'];
					 
			$tableauIni = parse_ini_file($filename, true);
			//print_r($tableauIni);

			$list ="";
			foreach ($tableauIni as $key => $val)
			{
				$UUID_image = str_replace("-","",$tableauIni[$key]['RegionUUID']);
				$ImgMapHttp = "http://".$hostname.":".trim($srvOS)."/index.php?method=regionImage".$UUID_image;
				$list = $list.$key."#".$ImgMapHttp.";";
			}

			$messageInfo = "OK;" . $list;
		}		
	}
	//#################################################################################################################
	
	echo $messageInfo;
	$_SESSION = array();
}
else {echo "NO API KEY";}


?>
