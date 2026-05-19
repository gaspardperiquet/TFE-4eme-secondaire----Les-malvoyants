<?php
    include("models/cnx.php");

    $msg = "";
    if(isset($_POST["email"], $_POST["mdp"]))
    {
        if(!empty($_POST["email"]) && !empty($_POST["mdp"]))
        {
            $email = $_POST["email"];
            $mdp = $_POST["mdp"];

            if($email == "admin@gmail.com" && $mdp == "admin")
            {
                $_SESSION["user"] = "admin@gmail.com";
                header("Location:?section=accueil");
                exit();
            }
            else
            {
                // Préparer la requête pour vérifier si l'utilisateur existe dans la base de données
                $query = "SELECT * FROM users WHERE email = :email AND mdp = :mdp";
                $stmt = $pdo->prepare($query);

                // Lier les valeurs aux paramètres dans la requête préparée
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':mdp', $mdp);

                // Exécuter la requête
                $stmt->execute();

                // Récupérer le premier enregistrement
                $user = $stmt->fetch();

                // Vérifier si l'utilisateur a été trouvé dans la base de données
                if ($user !== false)
                {
                    $_SESSION["user"] = $user["id_user"];
                    header("Location:?section=accueil");
                    exit();
                }
                else
                {
                    $message = '<p class="reponse_form">Identifiant ou mot de passe incorrect</p>';
                }
            }
        }
        else
        {
            $message = '<p class="reponse_form">Aucun champ complété</p>';
        }
    }

    include("views/pages/connexion.php");
?>