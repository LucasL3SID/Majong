<?php

$dbHost = "telline.univ-tlse3.fr";
$dbHostPort = "1521";
$dbServiceName = "etupre";
$usr = "GLC2017A";
$pswd = "naves19";
$dbConnStr = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)
		(HOST = ".$dbHost.")(PORT = ".$dbHostPort."))
		(CONNECT_DATA = (SERVICE_NAME = ".$dbServiceName.")))";
if(!$dbConnStr = oci_connect($usr, $pswd, $dbConnStr)){
	$err = oci_error();
	trigger_error('Could not establish a connection: ' $err['message', E_USER_ERROR);	
}

if(isset($_POST['forminscription'])) {
   $name = htmlspecialchars($_POST['name']);
   $firstname = htmlspecialchars($_POST['firstname']);
   $username = htmlspecialchars($_POST['username']);
   $pseudo = htmlspecialchars($_POST['pseudo']);
   $mail = htmlspecialchars($_POST['mail']);
   $mail2 = htmlspecialchars($_POST['mail2']);
   $mdp = sha1($_POST['mdp']); 
   $mdp2 = sha1($_POST['mdp2']);
   if(!empty($_POST['name']) AND !empty($_POST['firstname']) AND !empty($_POST['username']) AND !empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2'])) {
      $pseudolength = strlen($pseudo);
      if($pseudolength <= 50) {
         if($mail == $mail2) {
            if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
               $reqmail = $bdd->prepare("SELECT * FROM PLAYER WHERE mail = ?");
               $reqmail->execute(array($mail));
               $mailexist = $reqmail->rowCount();
               if($mailexist == 0) {
                  if($mdp == $mdp2) {
                     $insertmbr = $bdd->prepare("INSERT INTO PLAYER(id_player, name, firstname, username, pseudo, mail, password, administrator, level) VALUES(1, ?, ? ,? ,?, ?, ?, 0, 1)");
                     $insertmbr->execute(array($name, $firstname, $username, $pseudo, $mail, $mdp));
                     $erreur = "Votre compte a bien été créé ! <a href=\"connexion.php\">Me connecter</a>";
                  } else {
                     $erreur = "Vos mots de passes ne correspondent pas !";
                  }
               } else {
                  $erreur = "Adresse mail déjà utilisée !";
               }
            } else {
               $erreur = "Votre adresse mail n'est pas valide !";
            }
         } else {
            $erreur = "Vos adresses mail ne correspondent pas !";
         }
      } else {
         $erreur = "Votre pseudo ne doit pas dépasser 50 caractères !";
      }
   } else {
      $erreur = "Tous les champs doivent être complétés !";
   }
}
?>

<html>
   <head>
      <title>TUTO PHP</title>
      <meta charset="utf-8">
   </head>
   <body>
      <div align="center">
         <h2>Inscription</h2>
         <br /><br />
         <form method="POST" action="">
            <table>
			   <tr>
                  <td align="right">
                     <label for="pseudo">Nom :</label>
                  </td>
                  <td>
                     <input type="text" placeholder="Votre nom" id="name" name="name" value="<?php if(isset($name)) { echo $name; } ?>" />
                  </td>
               </tr>
			   <tr>
                  <td align="right">
                     <label for="pseudo">Prénom :</label>
                  </td>
                  <td>
                     <input type="text" placeholder="Votre prénom" id="firstname" name="firstname" value="<?php if(isset($firstname)) { echo $firstname; } ?>" />
                  </td>
               </tr>
			   <tr>
                  <td align="right">
                     <label for="pseudo">Nom d'utilisateur :</label>
                  </td>
                  <td>
                     <input type="text" placeholder="Votre nom d'utilisateur" id="username" name="username" value="<?php if(isset($username)) { echo $username; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="pseudo">Pseudo :</label>
                  </td>
                  <td>
                     <input type="text" placeholder="Votre pseudo" id="pseudo" name="pseudo" value="<?php if(isset($pseudo)) { echo $pseudo; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mail">Mail :</label>
                  </td>
                  <td>
                     <input type="email" placeholder="Votre mail" id="mail" name="mail" value="<?php if(isset($mail)) { echo $mail; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mail2">Confirmation du mail :</label>
                  </td>
                  <td>
                     <input type="email" placeholder="Confirmez votre mail" id="mail2" name="mail2" value="<?php if(isset($mail2)) { echo $mail2; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mdp">Mot de passe :</label>
                  </td>
                  <td>
                     <input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mdp2">Confirmation du mot de passe :</label>
                  </td>
                  <td>
                     <input type="password" placeholder="Confirmez votre mdp" id="mdp2" name="mdp2" />
                  </td>
               </tr>
               <tr>
                  <td></td>
                  <td align="center">
                     <br />
                     <input type="submit" name="forminscription" value="Je m'inscris" />
                  </td>
               </tr>
            </table>
         </form>
         <?php
         if(isset($erreur)) {
            echo '<font color="red">'.$erreur."</font>";
         }
         ?>
      </div>
   </body>
</html>