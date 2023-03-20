<?php
session_start();
require_once '../includes/connect.php';

if ($_SESSION["role"] != "admin") {
    header("Location: ../../index.php");
}

// Récupérer l'ID de l'utilisateur et le nouveau rôle
$id = $_POST['id'];
$role = $_POST['role'];

// Mettre à jour le rôle de l'utilisateur dans la base de données
$query = "UPDATE Utilisateur SET role = :role WHERE id = :id";
$stmt = $bdd->prepare($query);
$stmt->bindParam(':role', $role);
$stmt->bindParam(':id', $id);
$stmt->execute();

// Rediriger vers la page du panel administrateur
header("Location: ../../templates/panel.php");
exit;
