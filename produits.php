<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tfa_gaspard";

    try {
        // se connecter à mysql
        $pdo = new PDO("mysql:host=$host;dbname=$dbname","$username","$password");
        } catch (PDOException $exc) {
          echo $exc->getMessage();
          exit();
        }

function getAllProduits()
{
    global $pdo;

    // On construit la requête SQL qui permet de récupérer toutes les lignes de la table
    $req = "SELECT * FROM produits ORDER BY prix ASC";

    // On prépare la requête en créant un objet $stmt qui va permettre de l'exécuter
    $stmt = $pdo->prepare($req);

    // On exécute la requête préparée
    $stmt->execute();

    // On récupère toutes les lignes de résultats sous forme de tableau associatif
    $data = $stmt->fetchAll();

    // On retourne le tableau de résultats
    return $data;
}

function getProduit($id_produits)
{
    global $pdo;  

    $req = "SELECT * FROM produits WHERE id_produits = :id_produits";

    $stmt = $pdo->prepare($req);
    $stmt->execute([":id_produits" => $id_produits]);

    $produit = $stmt->fetch();

    return $produit;
}

function deleteProduit($id_produits)
{
    global $pdo;
    
    $req = "DELETE FROM produits WHERE id_produits = :id_produits";

    $stmt = $pdo->prepare($req);
    $stmt->execute([":id_produits" => $id_produits]);

    return true;
}

?>