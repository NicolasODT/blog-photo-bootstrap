<?php
session_start();

if (isset($_SESSION["id"]) && ($_SESSION["role"] == "editeur" || $_SESSION["role"] == "admin")) {
    require_once "../includes/connect.php";

    $id_article = $_POST["id_article"];

    // Supprime l'article de la base de données
    $sql = "DELETE FROM Article WHERE id = :id_article";
    $query = $bdd->prepare($sql);
    $query->bindParam(":id_article", $id_article);
    $query->execute();

    header("Location: ../../index.php");
    exit();
} else {
    header("Location: ../../index.php");
    exit();
}
?>
