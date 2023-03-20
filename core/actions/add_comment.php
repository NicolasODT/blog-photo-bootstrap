<?php
session_start();
require_once '../includes/connect.php';

if (isset($_POST['message']) && isset($_POST['id_article'])) {
    // Récupération des données du formulaire
    $message = htmlspecialchars($_POST['message']);
    $id_article = htmlspecialchars($_POST['id_article']);

     // Vérification que l'utilisateur est connecté et a le rôle autorisé
    if (isset($_SESSION['id']) && (($_SESSION['role'] == 'utilisateur') || ($_SESSION['role'] == 'editeur') || ($_SESSION['role'] == 'admin'))) {
        $user_id = $_SESSION['id'];
        // Requête d'insertion du commentaire dans la base de données
        $sql = "INSERT INTO commentaire (message, id_article, id_utilisateur) VALUES (:message, :id_article, :user_id)";
        $query = $bdd->prepare($sql);
        $query->bindParam(":message", $message);
        $query->bindParam(":id_article", $id_article);
        $query->bindParam(":user_id", $user_id);
        $query->execute();

        // Redirection vers la page de l'article
        header("Location: ../../templates/article.php?slug=" . $_GET["slug"]);
        exit();
    } else {
        echo "Vous devez être connecté et avoir un rôle autorisé pour ajouter un commentaire.";
    }
} else {
    echo "Une erreur s'est produite lors de l'ajout du commentaire.";
}
