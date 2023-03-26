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
    // filter_var() permet de valider une chaîne selon un filtre
    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
      $errorMsg = "Adresse email invalide";
    } else {

        // strlen() compte le nombre de caractères d'une chaîne
        // Vérification que le mot de passe est suffisamment complexe et long
        // preg_match() effectue une recherche de correspondance avec une expression rationnelle
        $uppercase = preg_match('@[A-Z]@', $_POST["password"]);
        $lowercase = preg_match('@[a-z]@', $_POST["password"]);
        $number    = preg_match('@[0-9]@', $_POST["password"]);

        if (strlen($_POST["password"]) < 8 || !$uppercase || !$lowercase || !$number) {
          $errorMsg = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre";
        } else {

          // Nettoyage des données
          // htmlspecialchars() convertit les caractères spéciaux en entités HTML
          // trim() supprime les espaces (ou d'autres caractères) en début et fin de chaîne
          $email = htmlspecialchars(trim($_POST["email"]));
          $password = htmlspecialchars(trim($_POST["password"]));
          // $options est un tableau qui contient les options de hachage et cost est le coût du hachage c'est à dire le nombre d'itérations
          // plus le coût est élevé plus le hachage est sécurisé mais plus il est long à générer
          $options = [
            'cost' => 12,
          ];
          $password = password_hash($_POST["password"], PASSWORD_BCRYPT, $options);
          $pseudo = htmlspecialchars(trim(isset($_POST["pseudo"]) ? $_POST["pseudo"] : ""));
          $ville = htmlspecialchars(trim(isset($_POST["ville"]) ? $_POST["ville"] : ""));
          $pays = htmlspecialchars(trim(isset($_POST["pays"]) ? $_POST["pays"] : ""));

          require_once "../core/includes/connect.php";
             // Vérification si l'email ou le pseudo existe déjà
            $checkExistenceSql = "SELECT * FROM utilisateur WHERE email = :checkEmail OR pseudo = :checkPseudo";
            $checkExistenceQuery = $bdd->prepare($checkExistenceSql);
            $checkExistenceQuery->bindParam(":checkEmail", $email, PDO::PARAM_STR);
            $checkExistenceQuery->bindParam(":checkPseudo", $pseudo, PDO::PARAM_STR);
            $checkExistenceQuery->execute();
            
            if ($checkExistenceQuery->rowCount() > 0) {
               // L'email ou le pseudo existe déjà
              $errorMsg = "Ce pseudo ou cet adresse email est déja utilisée";
            } else {

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
          header('location: ../index.php');
        } else {
          $errorMsg = "Erreur lors de l'inscription, veuillez réessayer.";
        }
      }
    }

}
  } else {
    $errorMsg = "Mots de passe différents";
  }
}
require_once '../core/includes/header.php';
?>

<main class="container">
  <div class="row justify-content-center mt-5">
    <div class="col-md-6">
    <h1 class="mb-3">Créer un compte</h1>
      <?php if (isset($errorMsg)): ?>
        <p class="alert alert-danger"><?= $errorMsg; ?></p>
      <?php endif; ?>
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
        <button class="btn btn-primary w-100">Créer</button>
      </form>
    </div>
  </div>
</main>
<?php
require_once '../core/includes/footer.php';
?>
