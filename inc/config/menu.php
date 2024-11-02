<?php
if (isset($_GET['a'])){$PAGE = $_GET['a'];}else{$PAGE = "";}


// **********************
// TRADUCTIONS
// **********************

if (isset($_SESSION['authentification']))
{
	//echo $_COOKIE['lang'];
	$languages = array("fr" => "French","en" => "English");

	if (!empty($_COOKIE['lang']))$lang=$_COOKIE['lang'];
	if (!empty($_GET['lang']))$lang=$_GET['lang'];
	
	if (!empty($lang) && array_key_exists($lang, $languages))
	{
		include('./inc/lang/lang_'.$lang.'.php');
		setcookie('lang',$lang,time()+3600*25*365,'/');
	}
}

?>

<!-- MENU NAVBAR -->
<nav class="navbar navbar-default">
<div class="container-fluid">
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
		
			<li <?php if ($PAGE == "GestSims") {echo 'class="active"';} ?>>
				<a href="index.php?a=GestSims"><i class="glyphicon glyphicon-th-large"></i> <?php echo $osmw_menu_GestSims;?> </a>
			</li>
			<li <?php if ($PAGE == "GestSave") {echo 'class="active"';} ?>>
				<a href="index.php?a=GestSave"><i class="glyphicon glyphicon-hdd"></i> <?php echo $osmw_menu_GestSave;?> </a>
			</li>
			
			<?php if (isset($_SESSION['authentification']) && $_SESSION['privilege']>= 3){?>	
				<li <?php if ($PAGE == "AdminSims") {echo 'class="active"';} ?>>
					<a href="index.php?a=AdminSims"> <i class="glyphicon glyphicon-folder-open"></i> <?php echo $osmw_menu_admin_Sims;?></a>
				</li>					
				<li <?php if ($PAGE == "AdminLands") {echo 'class="active"';} ?>>
					<a href="index.php?a=AdminLands"><i class="glyphicon glyphicon-th"></i> <?php echo $osmw_menu_admin_Lands;?></a>
				</li>
			<?php }?>	
			
<!-- MENU ADMINISTRATOR NAVBAR (SETTINGS)-->			
<?php if (isset($_SESSION['authentification']) && $_SESSION['privilege']>= 3){?>						
			<li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                   <i class="glyphicon glyphicon-wrench"></i> <?php echo $osmw_menu_admin;?> <span class="caret"></span>
                </a>
               <ul class="dropdown-menu">
					<li <?php if ($PAGE == "AdminUsers") {echo 'class="active"';} ?>>
                        <a href="index.php?a=AdminUsers"> <i class="glyphicon glyphicon-user"></i> <?php echo $osmw_menu_admin_Users;?></a>
                    </li>
					<li <?php if ($PAGE == "AdminOsmw") {echo 'class="active"';} ?>>
                        <a href="index.php?a=AdminOsmw"><i class="glyphicon glyphicon-cog"></i> <?php echo $osmw_menu_admin_Osmw;?></a>
                    </li>
					<li <?php if ($PAGE == "AdminEdit") {echo 'class="active"';} ?>>
                        <a href="index.php?a=Help"><i class="glyphicon glyphicon glyphicon-book"></i> <?php echo $osmw_menu_help;?></a>
                    </li>
                </ul>
            </li>
			
<?php }?>				
        </ul>

<!-- MENU USER NAVBAR -->            
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="glyphicon glyphicon-user"></i> <strong><?php echo $_SESSION['login']; ?></strong> <span class="label label-default"><?php echo $_SESSION['privilege']; ?></span> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
					 <li><a href="index.php?a=GestUsers"><i class="glyphicon glyphicon-check"></i> <?php echo $osmw_menu_user_login;?></a></li>
					 <li><a href="index.php?a=logout"><i class="glyphicon glyphicon-log-out"></i> <?php echo $osmw_menu_user_logout;?></a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
</nav>
