<?php
session_start();
require_once '../includes/connect.php';


// Vérifie si l'utilisateur est un admin, sinon redirige vers la page index
if ($_SESSION["role"] != "admin") {
    header("Location: index.php");
}

$id = $_POST['id'];
$actif = $_POST['actif'];

// Met à jour le statut actif de l'utilisateur dans la base de données
$query = "UPDATE Utilisateur SET actif = :actif WHERE id = :id";
$stmt = $bdd->prepare($query);
$stmt->bindParam(':actif', $actif);
$stmt->bindParam(':id', $id);
$stmt->execute();

header("Location: ../../templates/panel.php");
exit;
