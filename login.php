<?php
session_start();
session_unset();

require 'connexion.php';
function validationEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    if (!empty($email) && !empty($password)) {
        if (validationEmail($email) && strlen($password) >= 8) {
            try {
                $stmt = $bdd->prepare("SELECT * FROM admin WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    
                    if (password_verify($password, $user['password'])) {

                        $stmt = $bdd->prepare("SELECT first_name FROM admin WHERE email = :email");
                        $stmt->bindParam(':email', $email);
                        $stmt->execute();
                        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
                
                        if ($userData) {
                            $_SESSION['first_name'] = $userData['first_name'];
                        }
                        $_SESSION['email'] = $email;
                        $_SESSION['islog']= true;
                        header('Location: loged.php');
                        exit();
                    } else {
                        
                        echo "Le mot de passe est incorrect.";
                    }
                } else {
                   
                    echo "L'email n'est pas enregistré.";
                }
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
        } else {
            echo "L'email n'est pas valide ou le mot de passe doit contenir au moins 8 caractères.";
        }
    } else {
        echo "Les champs email et password ne peuvent pas être vides.";
    }
}
?>

<!DOCTYPE html> 
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Formulaire de connexion</title>
</head>
<body>

<div class="login-box">
    <h2>LOGIN</h2>
  <form action="login.php" method="post" value="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="email">Email:</label>
    <input type="text" id="email" name="email"><br>

    <label for="password">Mot de passe:</label><br>
    <input type="password" id="password" name="password"><br>

    <a href="#">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <input type="submit" value="Connexion">
    </a>
     
  </form>
</div>
</body>
</html>
