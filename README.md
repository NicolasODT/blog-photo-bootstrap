### Projet: Évaluation - Étude de cas – CMS / App / Backend

#### Description du projet

Ce projet consiste à concevoir et développer une application PHP pour gérer un petit blog multi-utilisateurs. Les éditeurs peuvent publier des articles accessibles publiquement, qui pourront être commentés par les utilisateurs enregistrés. Les administrateurs peuvent promouvoir les utilisateurs en éditeurs ou administrateurs. Les visiteurs peuvent créer un compte en tant que simple utilisateur par défaut.

#### Fonctionnalités clés

- **Page d'accueil:** affiche les 10 derniers articles publiés avec le titre, les 50 premiers caractères, l'auteur et un lien vers l'article et avec une pagination.
- **Pages de connexion et de création de compte:** incluent des formulaires avec des champs pertinents, une protection contre les tentatives de connexion trop nombreuses et un champ CAPTCHA.
- **Résultats de recherche:** un champ de recherche permet d'afficher une page de résultats présentant les articles répondant aux critères de recherche.
- **Profil de l'utilisateur:** permet à l'utilisateur connecté de modifier ses informations, y compris son mot de passe.
- **Édition d'un nouvel article:** permet de saisir le titre et le contenu de l'article, en utilisant un éditeur de texte enrichi comme TinyMCE.
- **Modification d'un article:** similaire à la page précédente, mais permet de modifier un article existant plutôt que d'en créer un nouveau.
- **Les articles:** chaque article est présenté sur une page unique, accessible via un identifiant unique ou un slug pour des raisons SEO.
- **Tableau de bord:** permet aux éditeurs de créer de nouveaux articles et de gérer les existants, aux administrateurs de lister les utilisateurs et de les promouvoir ou de les désactiver.

#### Sécurité

L'application doit être protégée contre les failles XSS et les injections SQL. Les mots de passe sont hachés avec un coût de 12 pour une meilleure protection.

#### Github

[Focale creative](https://github.com/NicolasODT/blog-photo-bootstrap)
