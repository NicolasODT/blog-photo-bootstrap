<?php

session_start();

function slugify($text)
{
  // Remplace les caractères spéciaux par des tirets
  // preg_replace() est utilisé pour la compatibilité avec les caractères non latins
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // Convertit en minuscules
  //mb_strtolower() est utilisé pour la compatibilité avec les caractères non latins
  $text = mb_strtolower($text, 'UTF-8');

  // Supprime tout caractère non alphanumérique ou tiret en début et fin de chaîne
  $text = trim($text, '-');

  // Supprime les doubles tirets
  // preg_replace() est utilisé pour la compatibilité avec les caractères non latins
  $text = preg_replace('~-+~', '-', $text);

  // Retourne la chaîne convertie
  return $text;
}

if (
  isset($_POST["titre"]) && $_POST["titre"] != ""
  && isset($_POST["story"]) && $_POST["story"] != ""
  && isset($_FILES["file"]) && $_FILES["file"]["error"] == UPLOAD_ERR_OK
) {

  // Vérifier que le fichier est une image
  // mime_content_type() detecte le type d'un fichier
  // strpos() cherche la position de la première occurrence d'une chaîne dans une autre
  $file_type = mime_content_type($_FILES['file']['tmp_name']);
  if (strpos($file_type, 'image/') !== 0) {
    // Le fichier n'est pas une image, afficher une erreur
    $errorMsg = "Le fichier téléchargé doit être une image valide";
  } else {

    $titre = trim($_POST["titre"]);
    $story = trim($_POST["story"]);

    $utilisateur_id = $_SESSION['id'];

    $target_dir = "../../public/media/";
    // basename() peut empêcher les attaques de système de fichiers;
    $image_name = basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $image_name;
    // move_uploaded_file() peut empêcher les attaques de système de fichiers
    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);


    // Générer le slug
    $slug = slugify($titre);

    //connexion à la base de données
    require_once "../includes/connect.php";

    $sql = "INSERT INTO article (titre, contenu, image, id_utilisateur, slug) VALUES (:titre,
    :story, :file_path, :utilisateur_id, :slug);";

    $query = $bdd->prepare($sql);
    $query->bindParam(":titre", $titre, PDO::PARAM_STR);
    $query->bindParam(":story", $story, PDO::PARAM_STR);
    $query->bindParam(":file_path", $target_file, PDO::PARAM_STR);
    $query->bindParam(":utilisateur_id", $utilisateur_id, PDO::PARAM_INT);
    $query->bindParam(":slug", $slug, PDO::PARAM_STR);


    if ($query->execute()) {
      echo "<p>L'article a bien été créé</p>";
      header('location: /index.php');
    } else {
      echo "<p>Une erreur s'est produite</p>";
    }
  }
}
require_once '../includes/header.php';
?>
<main class="container">
  <form action="" method="post" enctype="multipart/form-data" class="form-add-article my-5">
    <div class="form-floating mb-3">
      <input type="text" name="titre" id="titre" class="form-control" placeholder="Titre de l'article" required>
      <label for="titre">Titre de l'article</label>
    </div>
    <div class="form-floating mb-3">
      <textarea placeholder="Entrez votre article ici..." id="story" name="story" class="form-control" style="height: 300px;"></textarea>
      <label for="story">Contenu de l'article</label>
    </div>
    <div class="mb-3">
      <label for="fileInput" class="form-label">Image</label>
      <?php if (isset($errorMsg)) : ?>
        <p class="alert alert-danger"><?= $errorMsg; ?></p>
      <?php endif; ?>
      <input type="file" name="file" id="fileInput" class="form-control">
      <img id="image-preview" src="" alt="Image preview" class="my-3" style="max-width: 400px; display: none;">
    </div>
    <button class="btn btn-primary w-25">Envoyer</button>
  </form>
</main>




<?php
require_once '../includes/footer.php';
