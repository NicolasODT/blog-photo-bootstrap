<?php
session_start();
if (isset($_GET["slug"])) {
    require_once "../core/includes/connect.php";

    $slug = $_GET["slug"];

    // Recherche l'article correspondant au slug dans la base de données
    $sql = "SELECT a.*, u.pseudo FROM Article a JOIN Utilisateur u ON a.id_utilisateur = u.id WHERE a.slug = :slug";
    $query = $bdd->prepare($sql);
    $query->bindParam(":slug", $slug);
    $query->execute();
    $article = $query->fetch();

    if ($article) {
        require_once '../core/includes/header.php';
?>

<main class="main-article container">
    <img class="img-article" src="<?= $article["image"] ?>" alt="<?= $article["titre"] ?>" style="width:100%;height:400px;object-fit: cover;">
    <h2><?= $article["titre"] ?></h2>
    <p class="message-article"><?= $article["contenu"] ?></p>

    <?php if (isset($_SESSION["id"])) : ?>
        <!-- Formulaire pour ajouter un commentaire -->
        <form method="post" action="../core/actions/add_comment.php?slug=<?= $slug ?>">
            <div class="form-group">
                <label for="message">Laissez un commentaire :</label>
                <textarea name="message" id="message" class="form-control" rows="4" cols="50"></textarea>
            </div>
            <input type="hidden" name="id_article" value="<?= $article["id"] ?>">
            <button class="btn btn-primary">Envoyer</button>
        </form>
    <?php endif; ?>

    <section class="commentaires mt-5">
        <h3>Commentaires</h3>
        <?php
        // Récupère les commentaires de l'article depuis la base de données
        $sql = "SELECT c.*, u.pseudo FROM Commentaire c JOIN Utilisateur u ON c.id_utilisateur = u.id WHERE c.id_article = :id_article ORDER BY c.date_creation DESC";
        $query = $bdd->prepare($sql);
        $query->bindParam(":id_article", $article["id"]);
        $query->execute();
        $commentaires = $query->fetchAll();

        if (count($commentaires) > 0) {
            // Affiche chaque commentaire
            foreach ($commentaires as $commentaire) {
        ?>
                <div class="commentaire border rounded p-3 my-3">
                    <h4><?= $commentaire["pseudo"] ?></h4>
                    <p><?= $commentaire["message"] ?></p>
                    <p class="date"><?= $commentaire["date_creation"] ?></p>
                </div>
        <?php
            }
        } else {
            echo "<p class='text-center'>Aucun commentaire pour le moment.</p>";
        }
        ?>
    </section>

    <?php if (isset($_SESSION["id"]) && ($_SESSION["role"] == "editeur" || $_SESSION["role"] == "admin")) : ?>
        <!-- Lien pour éditer l'article si l'utilisateur est un éditeur ou un administrateur -->
        <div class="edit-link my-4">
            <a href="../core/actions/edit-article.php?id_article=<?= $article['id'] ?>" class="btn btn-primary me-3">Editer</a>
            <form id="delete-form" method="post" action="../core/actions/delete_article.php">
                <input type="hidden" name="id_article" value="<?= $article["id"] ?>">
                <button id="delete-btn" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    <?php endif; ?>  
</main>

<?php
    } else {
        echo "L'article n'existe pas.";
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>