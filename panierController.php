<?php
    include("models/cnx.php");

    // Récupération de l'ID de l'utilisateur connecté
    $id_user = $_SESSION["user"];

    // Requête SQL pour récupérer les informations du panier et des produits
    $sql = "SELECT p.id_panier, p.id_user , u.nom, u.prenom, pp.id_produits, pr.nom_produit, pr.image, pr.prix, count(pp.id_produits) as quantite, (count(pp.id_produits) * pr.prix) as total_produit
    FROM `panier` p
    INNER JOIN users u ON p.id_user = u.id_user
    LEFT JOIN panier_produits pp ON p.id_panier = pp.id_panier
    INNER JOIN produits pr ON pp.id_produits = pr.id_produits
    WHERE p.id_user = :id_user
    GROUP BY pp.id_produits";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_user' => $id_user]);
    $panier = $stmt->fetchAll();

    // Suppression d'un produit du panier
    if(isset($_POST['supprimer']) && isset($_POST['id_produits'])) {
        $id_produits = $_POST['id_produits'];

        $sql = "DELETE FROM `panier_produits` WHERE id_produits = :id_produits AND id_panier IN (SELECT id_panier FROM `panier` WHERE id_user = :id_user) LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_produits' => $id_produits, 'id_user' => $id_user]);

        // Rediriger l'utilisateur vers la page du panier
        header("Location:?section=panier");
        exit();
    }

    // Affichage du panier
    include("views/pages/panier.php");
?>
