<?php

// inclusion des fichiers nécessaires
include("models/produits.php");
include("models/cnx.php");

// récupération de l'ID de l'utilisateur connecté
$id_user = $_SESSION["user"];

// si un ID de produit est fourni en GET, on affiche les détails du produit
if (isset($_GET["id"])) {
    $id_produits = $_GET["id"];
    $produit = getProduit($id_produits);
    if ($produit) {
        // afficher les détails du produit
        include("views/pages/store/detailsStore.php");

        // si le formulaire a été soumis, on ajoute le produit au panier
        if(isset($_POST['panier']) && isset($_POST['id_produits'])){
            if(!empty($_POST['id_produits'])){
                // récupération de l'ID du produit
                $id_produits = $_POST['id_produits'];
                
                // vérification si l'utilisateur a déjà un panier dans la base de données
                $sql = "SELECT * FROM panier WHERE id_user = :id_user";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id_user' => $id_user]);

                // creation d'un nouveau panier si l'user n'en a pas 
                if(!$stmt->rowCount()){
                    $sql2 = "INSERT INTO `panier`(`id_user`) VALUES (:id_user)";
                    $stmt2 = $pdo->prepare($sql2);
                    $stmt2->execute(['id_user' => $id_user]);
                }

                // s'il y a des résultats, l'utilisateur a déjà un panier dans la base de données
                if ($stmt->rowCount() > 0) {
                    // récupération des informations du panier
                    $panier = $stmt->fetch();
                    $id_panier = $panier['id_panier'];

                    // insertion du produit dans la table panier_produits
                    $sql = "INSERT INTO panier_produits (id_panier, id_produits) VALUES (:id_panier, :id_produits)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['id_panier' => $id_panier, 'id_produits' => $id_produits]);
                
                    // redirection vers la page du panier
                    if ($stmt){
                        header("Location:?section=panier");
                    }
                    else{
                        $message = '<p class=\'reponse_form\'>Erreur d\'insertion dans le panier !!!</p>';
                    }
                }   
            }        
            else{
                $message = '<p class="reponse_form" >Aucun produit trouvé</p>';
            } 
        }
    } 
    else {
        // afficher un message d'erreur si aucun produit n'a été trouvé
        include("views/pages/404.php");
    }

} else {
    // afficher la liste des produits si aucun ID n'est fourni
    $produits = getAllProduits();
    include("views/pages/store/viewsStore.php");
}

?>