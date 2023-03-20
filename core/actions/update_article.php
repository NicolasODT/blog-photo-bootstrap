<?php
session_start();

// Vérifie si l'utilisateur connecté a le rôle "editeur" ou "admin"
if (isset($_SESSION['id']) && ($_SESSION['role'] == 'editeur' || $_SESSION['role'] == 'admin')) {

    // Vérifie si les données du formulaire ont été soumises
    if (isset($_POST['id']) && isset($_POST['titre']) && isset($_POST['contenu']) && isset($_POST['slug'])) {
        require_once '../includes/connect.php';

        // Récupère les données du formulaire
        $id = $_POST['id'];
        $titre = htmlspecialchars($_POST['titre']);
        $contenu = $_POST['contenu'];
        $slug = htmlspecialchars($_POST['slug']);

        // Vérifie si une image a été téléchargée et traite-la si oui
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = $_FILES['image'];
            $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            move_uploaded_file($image['tmp_name'], '../../public/media/' . $filename);
        
            $imagePath = '../../public/media/' . $filename;
        
             // Met à jour les données de l'article sans changer l'image
            $sql = "UPDATE Article SET titre = :titre, contenu = :contenu, slug = :slug, image = :image WHERE id = :id";
            $query = $bdd->prepare($sql);
            $query->bindParam(":image", $imagePath);
        } else {
            $sql = "UPDATE Article SET titre = :titre, contenu = :contenu, slug = :slug WHERE id = :id";
            $query = $bdd->prepare($sql);
        }
        
        // Exécute la requête pour mettre à jour l'article dans la base de données
        $query->bindParam(":id", $id);
        $query->bindParam(":titre", $titre);
        $query->bindParam(":contenu", $contenu);
        $query->bindParam(":slug", $slug);
        $query->execute();

        // Redirige vers la page de l'article modifié
        header("Location: ../../templates/article.php?id_article=" . $id);
        exit();
    } else {
        echo "Tous les champs ne sont pas renseignés.";
        exit();
    }
} else {
    // Redirige vers la page d'accueil si l'utilisateur n'est pas connecté en tant qu'éditeur ou administrateur
    header("Location: ../../index.php");
    exit();
}
?>
