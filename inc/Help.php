<?php 
if (isset($_SESSION['authentification']) )
{
	echo'HELP';
	
}
else {header('Location: index.php');}
?>