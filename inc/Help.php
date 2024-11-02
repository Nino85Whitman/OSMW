<?php 
if (isset($_SESSION['authentification']) )
{
	echo'https://github.com/Nino85Whitman/OSMW-OpenSim-Manager-Web';
	
}
else {header('Location: index.php');}
?>