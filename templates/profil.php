<?php
session_start();
require_once '../core/includes/connect.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

// Récupère l'utilisateur
$query = "SELECT id, pseudo, email, ville, pays FROM Utilisateur WHERE id = ?";
$stmt = $bdd->prepare($query);
$stmt->execute([$_SESSION['id']]);
$utilisateur = $stmt->fetch();

// Récupère les commentaires de l'utilisateur
$limit = 6; // Nombre de commentaires par page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit; // Calcul de l'offset

$sql = "SELECT c.*, a.titre AS article_titre, a.image AS article_image FROM Commentaire c JOIN Article a ON c.id_article = a.id WHERE c.id_utilisateur = :id_utilisateur ORDER BY c.date_creation DESC LIMIT :limit OFFSET :offset";
$stmt = $bdd->prepare($sql);
$stmt->bindParam(":id_utilisateur", $_SESSION['id']);
$stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
$stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();
$commentaires = $stmt->fetchAll();

// Met à jour les informations de l'utilisateur
if (isset($_POST['submit'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $ville = htmlspecialchars($_POST['ville']);
    $pays = htmlspecialchars($_POST['pays']);

    $query = "UPDATE Utilisateur SET hash = ?, ville = ?, pays = ? WHERE id = ?";
    $stmt = $bdd->prepare($query);
    $stmt->execute([$password, $ville, $pays, $_SESSION['id']]);

    header("Location: profil.php");
    exit;
}

require_once '../core/includes/header.php';

?>

<main class="container my-5">
  <h1 class="text-center">Profil de <?= $utilisateur['pseudo'] ?></h1>
  <div class="row">
    <div class="col-md-6 mx-auto">
      <form action="" method="post" class="form-profil mt-4">
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" name="email" id="email" class="form-control" value="<?= $utilisateur['email'] ?>" disabled>
        </div>
        <div class="form-group">
          <label for="password">Nouveau mot de passe:</label>
          <input type="password" name="password" id="password" class="form-control" placeholder="*********" required>
        </div>
        <div class="form-group">
          <label for="pseudo">Pseudo</label>
          <input type="text" name="pseudo" id="pseudo" class="form-control" value="<?= $utilisateur['pseudo'] ?>" disabled>
        </div>
        <div class="form-group">
          <label for="ville">Ville:</label>
          <input type="text" name="ville" id="ville" class="form-control" value="<?= $utilisateur['ville'] ?>">
        </div>
        <div class="form-group">
          <label for="pays">Pays:</label>
          <input type="text" name="pays" id="pays" class="form-control" value="<?= $utilisateur['pays'] ?>">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Enregistrer</button>
      </form>
    </div>
  </div>
  <section class="commentaires mt-5">
  <h2 class="text-center">Vos commentaires</h2>
  <div class="row">
    <?php
    // Vérifie s'il y a des commentaires à afficher
    if (count($commentaires) > 0) {
      // Affiche chaque commentaire récupéré de la base de données
      foreach ($commentaires as $commentaire) {
    ?>
        <div class="col-md-4">
        <div class="commentaire border rounded">
            <h3>Article : <?= substr($commentaire["article_titre"], 0, 23) ?></h3>
            <img src="<?= $commentaire["article_image"] ?>" alt="<?= $commentaire["article_titre"] ?>" class="img-fluid img-thumbnail" style="width:150px;height:auto;">
            <p><?= $commentaire["message"] ?></p>
            <p class="date"><?= $commentaire["date_creation"] ?></p>
            </div>
        </div>
    <?php
      }
    } else {
      echo "<p class='text-center'>Aucun commentaire trouvé.</p>";
    }
    ?>
  </div>
</section>


  <nav aria-label="Pagination" class="mt-5">
    <ul class="pagination justify-content-center">
      <?php if ($page > 1): ?>
        <li class="page-item">
          <a href="?page=<?= $page - 1 ?>" class="page-link" aria-label="Précédent">
            <span aria-hidden="true">&laquo;</span>
            <span class="sr-only">Précédent</span>
          </a>
        </li>
      <?php endif; ?>
      <?php if (count($commentaires) == $limit): ?>
        <li class="page-item">
          <a href="?page=<?= $page + 1 ?>" class="page-link" aria-label="Suivant">
            <span aria-hidden="true">&raquo;</span>
            <span class="sr-only">Suivant</span>
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
      </main>

      <?php
require_once '../core/includes/footer.php';
?>