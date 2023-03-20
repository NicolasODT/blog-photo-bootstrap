<?php

$serveur = "localhost";
$utilisateur = "root";
$mdp = "";
$nom_bdd = "focale";

$bdd = new PDO("mysql:host=$serveur;dbname=$nom_bdd", $utilisateur, $mdp);
