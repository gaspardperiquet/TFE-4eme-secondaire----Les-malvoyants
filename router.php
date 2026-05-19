<?php 
// Vérifie si la section n'est pas définie, alors la définit par défaut comme "accueil"
if (!isset($_GET["section"])) {
    $_GET["section"] = "accueil";
}

// Tableau des sections accessibles pour les visiteurs non connectés
$tabVisitor = array("accueil", "a-propos", "store", "inscription", "connexion");

// Tableau des sections accessibles pour les utilisateurs connectés
$tabLogged = array("accueil", "a-propos", "store", "inscription", "deconnexion", "panier", "admin");

// Vérifie si l'utilisateur n'est pas connecté
if (!isset($_SESSION["user"])) {
    // Vérifie si la section demandée fait partie des sections accessibles pour les visiteurs
    if (in_array($_GET["section"], $tabVisitor)) {
        include("controllers/" . $_GET["section"] . "Controller.php");
    } else {
        include("views/pages/404.php");
    }
}
// Vérifie si l'utilisateur est connecté en tant qu'admin
else if ($_SESSION["user"] == "admin@gmail.com") {
    // Vérifie si la section demandée fait partie des sections accessibles pour les utilisateurs connectés en tant qu'admin
    if (in_array($_GET["section"], $tabLogged) && $_GET["section"] != "panier") {
        include("controllers/" . $_GET["section"] . "Controller.php");
    } else {
        include("views/pages/404.php");
    }
}
// L'utilisateur est connecté, mais pas en tant qu'admin
else {
    // Vérifie si la section demandée fait partie des sections accessibles pour les utilisateurs connectés
    // (à l'exception de la section "admin")
    if (in_array($_GET["section"], $tabLogged) && $_GET["section"] != "admin") {
        include("controllers/" . $_GET["section"] . "Controller.php");
    } else {
        include("views/pages/404.php");
    }
}
?>