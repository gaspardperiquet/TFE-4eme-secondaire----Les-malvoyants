<?php 
    include("models/cnx.php");

    if(isset($_POST['envoyer'])){
        if(!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['mdp'])){
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $mdp = $_POST['mdp'];

            $sql = $pdo->prepare("INSERT INTO users (nom, prenom, email, mdp) VALUES (?, ?, ?, ?)");
            $sql->execute([$nom, $prenom, $email, $mdp]);

            if ($sql){
                header("Location:?section=connexion");
            }
            else{
                $message = '<p class=\'reponse_form\'>Erreur d\'insertion</p>';
            }
        }        
        else{
            $message = '<p class="reponse_form" >Aucun champ complété</p>';
        } 
    }

    include("views/pages/inscription.php");
?>
