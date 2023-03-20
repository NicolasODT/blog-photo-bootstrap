<?php
session_start();
if (isset($_SESSION['id']) && ($_SESSION['role'] == 'editeur' || $_SESSION['role'] == 'admin')) {

    // Vérifie si l'identifiant de l'article est défini
    if (isset($_GET['id_article'])) {
        require_once '../includes/connect.php';
        $id = $_GET['id_article'];

        // Récupère les données de l'article à éditer
        $sql = "SELECT * FROM Article WHERE id = :id";
        $query = $bdd->prepare($sql);
        $query->bindParam(":id", $id);
        $query->execute();
        $article = $query->fetch();
        if ($article) {
            // Stocke les données de l'article dans des variables
            $titre = htmlspecialchars($article['titre']);
            $contenu = $article['contenu'];
            $slug = htmlspecialchars($article['slug']);
            $image = htmlspecialchars($article['image']);
        } else {
            echo "Cet article n'existe pas.";
            exit();
        }
    } else {
        echo "L'identifiant de l'article n'est pas défini.";
        exit();
    }
} else {
    // Redirige l'utilisateur si l'utilisateur n'est pas autorisé à accéder à cette page
    header("Location: ../../index.php");
    exit();
}
?>
<?php require_once '../includes/header.php'; ?>
<main class="main-form container">
    <h1 class="my-4">Editer l'article</h1>
    <form method="post" action="update_article.php" enctype="multipart/form-data" class="form-add-article">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" name="titre" id="titre" class="form-control" value="<?= $titre ?>">
        </div>
        <div class="form-group">
            <label for="contenu">Contenu :</label>
            <textarea name="contenu" id="story" rows="10" class="form-control"><?= $contenu ?></textarea>
        </div>
        <div class="form-group">
            <label for="slug">Slug :</label>
            <input type="text" name="slug" id="slug" class="form-control" value="<?= $slug ?>">
        </div>
        <div class="form-group">
            <label for="image">Image :</label>
            <div>
                <img src="<?= $image ?>" id="image-preview2" alt="Image actuelle" class="mb-2" style="max-width: 400px;">
            </div>
            <div class="custom-file">
                <input type="file" name="file" id="fileInput" class="custom-file-input">
                <label class="custom-file-label" for="fileInput">Choisir une image</label>
            </div>
            <div>
                <img id="image-preview" src="" alt="Image preview" class="mt-2" style="max-width: 400px; display: none;">
            </div>
        </div>
        <button class="btn btn-primary">Enregistrer</button>
    </form>
</main>
<?php require_once '../includes/footer.php'; ?>

