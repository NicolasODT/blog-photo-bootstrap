<?php
session_start();

// Vérification que les champs requis ont été remplis
if (
    isset($_POST["email"]) && $_POST["email"] != ""
    && isset($_POST["password"]) && $_POST["password"] != ""
    && isset($_POST["password2"]) && $_POST["password2"] != ""
) {

    // Vérification que les deux mots de passe correspondent
    if ($_POST["password"] == $_POST["password2"]) {
        
        // Validation de l'adresse email
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            echo "<p class='alert alert-danger'>Adresse email invalide</p>";
            exit();
        }

        // Vérification que le mot de passe est suffisamment long
        if (strlen($_POST["password"]) < 8) {
            echo "<p class='alert alert-danger'>Le mot de passe doit contenir au moins 8 caractères</p>";
            exit();
        }

        // Nettoyage des données
        $email = htmlspecialchars(trim($_POST["email"]));
        $password = htmlspecialchars(trim($_POST["password"]));
        $options = [
            'cost' => 12,
        ];
        $password = password_hash($_POST["password"], PASSWORD_BCRYPT, $options);
        $pseudo = htmlspecialchars(trim(isset($_POST["pseudo"]) ? $_POST["pseudo"] : ""));
        $ville = htmlspecialchars(trim(isset($_POST["ville"]) ? $_POST["ville"] : ""));
        $pays = htmlspecialchars(trim(isset($_POST["pays"]) ? $_POST["pays"] : ""));

        require_once "../core/includes/connect.php";

        // Construction de la requête SQL d'insertion
        $sql = "INSERT INTO utilisateur (email, hash, pseudo, ville, pays) VALUES (:email,
        :password, :pseudo, :ville, :pays);";

        $query = $bdd->prepare($sql);
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->bindParam(":password", $password, PDO::PARAM_STR);
        $query->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
        $query->bindParam(":ville", $ville, PDO::PARAM_STR);
        $query->bindParam(":pays", $pays, PDO::PARAM_STR);

        if ($query->execute()) {
            echo "<p class='alert alert-success'>Le compte a bien été créé</p>";
            header('location: ../index.php');
        } else {
            echo "<p class='alert alert-danger'>Une erreur s'est produite</p>";
        }
    } else {
        echo "<p class='alert alert-danger'>mots de passe différents</p>";
    }
}
require_once '../core/includes/header.php';
?>

<main class="container">
  <div class="row justify-content-center mt-5">
    <div class="col-md-6">
      <h1 class="mb-3">Créer un compte</h1>
      <form class="mt-3" action="" method="post">
          <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="mb-3">
            <label for="password2" class="form-label">Confirmation mot de passe</label>
            <input type="password" class="form-control" id="password2" name="password2" required>
          </div>
          <div class="mb-3">
            <label for="pseudo" class="form-label">Pseudo</label>
            <input type="text" class="form-control" id="pseudo" name="pseudo" required>
          </div>
          <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" class="form-control" id="ville" name="ville" required>
          </div>
          <div class="mb-3">
            <label for="pays" class="form-label">Pays</label>
            <input type="text" class="form-control" id="pays" name="pays">
          </div>
          <button type="submit" class="btn btn-primary">Créer</button>
        </form>
      </div>
    </div>
  </div>
</main>
<?php
require_once '../core/includes/footer.php';
?>