<?php
    include("models/produits.php");

    if(isset($_GET["id"]))
    {
        $produit = getProduit($_GET["id"]);

        include("views/pages/store/detailsStore.php");
    }
?>