## Install OSMW 
=============================
- http://domain.com/OSMW/install.php

#More configuration
=============================
- inc/config.php

#Admin Login for new install
=============================
- firstname = Super
- lastname  = Admin
- password  = password

#For Linux !!! REQUIREMENTS !!!!
=============================
- tmux  (apt install tmux)
- lib php ssh2 (apt install php-ssh2)

- Writing permission  /inc/config.php just for Installation after remove permission
- Writing permission writing for install.php and delete after installation
- Writing permission for folder files  /Regions of Opensimulator (writing Regions.ini)  

# Systeme Web
=============================
- LINUX : Apache / MySQL / TMUX / PHP8 / LibSSH2
- WINDOWS : WAMP (exemple : Laragon)

Enjoy!


# Fonctionnement

-- OSMW envoie des commandes au simulateur via Remote Admin, sauf pour le START (Démarrage en console TMUX pour LINUX et Terminal pour WINDOWS)
-- Certains fichiers et/ou dossiers doivent avoir les droits d'écriture pour pouvoir être modifiés par OSMW (LINUX)

-- ATTENTION aux droits d'accès aux fichiers et au format des données saisies dans vos fichiers INI
	--> Régions.ini (droits d'écriture) / OpensimDefaults.ini, etc., qui doivent être accessibles
	--> Préférer l'utilisation de fichier de config dans addon-modules/NameGrid/config/NameGrid.ini
	
# Gestion des Utilisateurs:
## 4 Niveaux d'accès sont autorisés
- 1 compte root
- Administrateurs 
- Gestionnaires de sauvegardes
- Invités / Compte privé par Simulateur



# Suivi de versions 

## V 24.11 (Novembre 2024) ***
/* NEWS 2024 by Nino85 Whitman (creator)*/
-- OSMW compatible WINDOWS et LINUX
-- Cleanup page
-- Optimize size of project
-- Fix bugs
-- Add more actions, options, infos, ...
And more ...

## V 21.6 (Juin 2021) ***
/* NEWS 2021 by Nino85 Whitman (creator)*/
-- Cleanup page
-- Optimize size of project
-- Fix bugs
-- Add more actions, options, infos, ...
And more ...

## V 20.8 (Aout 2020) ***
/* NEWS 2020 by Nino85 Whitman  (creator)*/
-- add TMUX command for simulator
-- Fix bugs
-- Add more actions, options, infos, ...
And more ...

## V 9.0 *** 2019
/* NEWS 2019 by Nino85 Whitman  (creator)*/
-- compatible PHP 7 
-- bdd mysql format PDO
-- Cleanup Code
-- Fix bugs
-- Add more actions, options, infos, ...
And more ...

## V 7 ***
/* NEWS 2017 by Nino85 Whitman  (creator)*/
-- Cleanup Code
-- Fix bugs
-- Add more actions, options, infos, ...
And more ...

## V 5.5 ***
/* NEWS 2015 by Nino85 Whitman (creator)*/
-- Cleanup Code
-- Fix bugs

## V 5.0 ***
/* NEWS 2015 by djphil and modified by Nino85 Whitman (creator)*/
-- Cleanup Code
-- Fix bugs
-- Add Themes
-- Add bootstrap
-- Add Multilanguage
-- Add Google Recaptcha v2.0
-- Add Navbar
-- Add more actions, options, infos, ...
And more ...

## V 4 Beta *** 2014
-- Mise à jours des SESSION
-- Systeme d'installation intégrés **
-- ...

## V 3.2 Final *** 2013
-- Gestion des sauvegardes de la config des moteurs Opensim et pour chaque sim
-- Transfert des fichiers de sauvagardes vers un serveur FTP exterieur
-- Detection des fichiers de config moteurs

## V 3.0 *** MISE A JOUR MAJEUR ***  2013
-- OSMW à sa propre base de donnée *** Nouveauté
-- Les Fichiers de config , conf moteurs et users sont en BDD ( prb de sécurité !)
-- Compte Utilisateur filtré au niveau des moteurs (choix du moteur) *** Nouveauté
-- Verifier/ Modifier/ configurer vos INIs, opensim, grid, ... *** Nouveauté
-- Connectivité AdmOSMW (Referencement sur le site Fgagod.net) 

## V 2.0 ***  2012
-- Optimisations du code

## V 1.1 *** 2011
-- Refonte complete de l'interface
-- Système d'installation simplifié
-- Gestion des moteurs OpenSim, des utilisateurs et de la config en .INI
-- ...

## V 1.0 *** 2010
-- Ajout de la gestion multi-Utilisateurs dans OSMW

## V0.9.11 *** 2019
-- Authentification multi-users via fichier texte  (pas encore intégrer à OSMW)

## V0.7.11 ***  2077
-- Ajouts de Fonctionnaltées;
	-- Cartographie ajouté
	-- TOUS demarrer et arreter d'une seule fois
	-- Une serie de tests pour voir si tous fonctionne bien
	-- Ce fichier LOL
-- Optimisations du code

## V0.6.11 ***  2006
-- Premiere version de OSWebManager