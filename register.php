<?php
session_start();
session_destroy();
session_start();

require 'connexion.php';

function validationEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

    $first_Name = $_POST['first-name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $password_confirmation = $_POST['password_confirmation'];
    $image = '';

    if (!empty($email) && !empty($password) && !empty($name) && !empty($password_confirmation)) {
        if (validationEmail($email) && strlen($password) >= 8) {
            if ($password !== $password_confirmation) {
                echo 'Les mots de passe ne correspondent pas.';
            } else {
                if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                    if ($_FILES['image']['size'] <= 200000 && $_FILES['image']['type'] === 'image/png') {
                        $image = $_FILES['image']['name'];
                        move_uploaded_file($_FILES['image']['tmp_name'], '/Applications/MAMP/htdocs/memento/' . basename($_FILES['image']['name']));
                    } else {
                        echo 'Erreur lors du téléchargement (l\'image doit être au format PNG et inférieure à 200ko).';
                    }
                }
                
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                try {
                    $stmt = $bdd->prepare("INSERT INTO admin (first_name, name, email, password, image) VALUES (:first_name, :name, :email, :password, :image)");
                    $stmt->bindParam(':first_name', $first_Name);
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $hashedPassword);
                    $stmt->bindParam(':image', $image);
                    $stmt->execute();

                    echo "Données enregistrées avec succès.";
                    
                    $_SESSION['first_name']=$first_Name;
                    $_SESSION['email']=$email;
                    header("Location: loged.php");
                    exit();
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }
            }
        } else {
            echo 'Email invalide ou mot de passe trop court (minimum 8 caractères).';
        }
    } else {
        echo 'Veuillez remplir tous les champs du formulaire.';
    }
}

    else{
        echo "Erreur CSRF : Tentative de manipulation du formulaire détectée.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Formulaire d'inscription</title>
</head>
<body>

<form action="register.php" method="post" enctype="multipart/form-data">

    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <label for="first-name">Prénom :</label>
    <input type="text" id="first-name" name="first-name"><br>

    <label for="name">Nom :</label>
    <input type="text" id="name" name="name"><br>

    <label for="email">Email :</label>
    <input type="text" id="email" name="email"><br>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password"><br>

    <label for="password_confirmation">Confirmer le mot de passe :</label>
    <input type="password" id="password_confirmation" name="password_confirmation"><br>

    <label for="image">Avatar :</label>
    <input type="file" id="image" name="image"><br>

    <input type="submit" value="S'inscrire">

</form>
    
</body>
</html>
