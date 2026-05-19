<?php
    include("models/produits.php");

    if(isset($_GET["action"]))
    {
        if(in_array($_GET["action"], ["add", "update", "delete"]))
        {
            $produit = null;

            if($_GET["action"] == "update")
            {
                $produit = getProduit($_GET["id"]);
                include("views/pages/store/updateStore.php");
        
                global $pdo;
        
                // Vérifier si l'ID du produit à mettre à jour est défini dans l'URL
                $id_produits = isset($_GET["id"]) ? $_GET["id"] : '';
        
                if(isset($id_produits)){
                    // Récupérer les données du formulaire
                    $nom_produit = isset($_POST["nom_produit"]) ? $_POST["nom_produit"] : '';
                    $description = isset($_POST["description"]) ? $_POST["description"] : '';
                    $prix = isset($_POST["prix"]) ? $_POST["prix"] : '';

                    // Récupérer l'URL de l'image existante depuis le champ caché
                    $image_existante = isset($_POST['image_existante']) ? $_POST['image_existante'] : '';

                    // Initialiser la variable $image avec l'image existante
                    $image = $image_existante;

                    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
                    // Gérer l'upload de la nouvelle image
                    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    if (!in_array($ext, array('jpg', 'jpeg', 'jfif','JPG','png','PNG')))
                        die('<p class="reponse_form">Pirate !</p>');
                    $image = uniqid() . ".$ext";
                    move_uploaded_file($_FILES['image']['tmp_name'], "public/images/produits/$image"); 
                    } 

                // Vérifier si l'image existante n'est pas vide avant de l'utiliser
                if(!empty($image_existante)) {
                    // Mettre à jour les informations du produit dans la base de données
                    $req = $pdo->prepare("UPDATE produits SET nom_produit=:nom_produit, description=:description, prix=:prix, image=:image WHERE id_produits=:id_produits");
                    $req->bindValue(':nom_produit', $nom_produit);
                    $req->bindValue(':description', $description);
                    $req->bindValue(':prix', $prix);
                    $req->bindValue(':image', $image);
                    $req->bindValue(':id_produits', $id_produits);
                    $result = $req->execute();

                    if($result){
                        // Rediriger l'utilisateur vers la liste des produits si la mise à jour a réussi
                        header("Location:?section=store");
                    } else {
                         // Afficher un message d'erreur si la mise à jour a échoué
                        $message = '<p>Produit Non mis à jour !</p>';
                    }
                } else {
                    // Afficher un message d'erreur si l'image existante est vide
                    $message = '<p>Image existante non trouvée !</p>';
                }

                } else {
                    // Afficher un message d'erreur si tous les champs ne sont pas remplis
                    $message = '<p>Veuillez remplir tous les champs !</p>';
                }
        

            }
            

            else if($_GET["action"] == "add")
            {
                include("views/pages/store/addStore.php");
            
                global $pdo;

                //recupération des données du formulaire
                $nom_produit = isset($_POST["nom_produit"]) ? $_POST["nom_produit"] : '';
                $description = isset($_POST["description"]) ? $_POST["description"] : '';
                $prix = isset($_POST["prix"]) ? $_POST["prix"] : '';
                
                if(!empty($nom_produit) && !empty($description) && !empty($prix)){
                    
                    if(isset($_FILES['image'])){                
                        // gérer l'upload
                        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION); //on gère l'extension réelle du fichier récupéré
                        if (!in_array($ext, array('jpg', 'jpeg', 'jfif','JPG','png','PNG'))) //on vérifie le format
                            die('<p class="reponse_form">Pirate !</p>');
                        $image = uniqid() . ".$ext"; //générer un nom de fichier au hasard 
                        move_uploaded_file($_FILES['image']['tmp_name'], "public/images/produits/$image"); //on transfère dans le dossier qu'on crée 
            
                        if($image){
                            //si l'image a été déplacé :
                            //insérons le titre ,la description  et le nom de l'image dans la base de donnée 
                            $req = $pdo->prepare("INSERT INTO produits (`nom_produit`, `description`, `prix`, `image`) VALUES (:nom_produit, :description, :prix, :image)");
                            $req->bindValue(':nom_produit', $nom_produit);
                            $req->bindValue(':description', $description);
                            $req->bindValue(':prix', $prix);
                            $req->bindValue(':image', $image);
                            $result = $req->execute();
                            if($result){
                                //si les informations ont été insérées dans la base de données
                                header("Location:?section=store");
                            }else {
                                //si non
                                $message = '<p style="color:red ">Produit Non ajouté !</p>';
                            }
                        }
            
                    }
                } else {
                    //si tous les champs ne sont pas remplis on a :
                    $message = '<p style="color:red">Veuillez remplir tous les champs !</p>';
                }
            
            }

            else if($_GET["action"] == "delete")
            {
                $id_produits = null;
                if(isset($_GET["id"]))
                {
                    $produit = getProduit($_GET["id"]);
                    include("views/pages/store/deleteStore.php");
                }

                if(isset($_POST["id_produits"]))
                {
                    $produit = array(
                        ":id_produits" => $_POST["id_produits"],
                    );
                    
                    try{
                        $id_produits = $produit[":id_produits"];
                        deleteProduit($id_produits);
                        header("Location:?section=store");
                        exit();
                    }
                    catch(PDOException $e){
                        var_dump($e);
                        die;
                    }
                }
            }

            else
            {
                include("views/pages/404.php");
            }
        } else
            {
                include("views/pages/404.php");
            }
    }

?>