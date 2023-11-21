<?php
session_start();
require 'connexion.php'; 

if ($_SESSION['islog'] == true) {
    try {
        $email = $_SESSION['email']; 
        $stmt = $bdd->prepare("SELECT first_name FROM admin WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $first_name = $result['first_name'];
        } else {
            $first_name = "Utilisateur inconnu"; 
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
    $csrf_token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrf_token;
} else {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>ajouter</title>
</head>
<body>
    <header>
        <nav> 
            <h1>Memento</h1>
            <div class="log">
                <?php 
                echo "Bonjour, ", $first_name;
                ?>
            </div>
        </nav>
    </header>

    <?php
    require "connexion.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $date = $_POST['date'];

        try {
            $stmt = $bdd->prepare("INSERT INTO postit (title, content, date) VALUES (:title, :content, :date)");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':date', $date);
            $stmt->execute();
            
            echo "Données enregistrées avec succès.";
            header('Location: loged.php');
            exit();
        } catch(PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
    ?>

    <section class="formmodif">
        <form method="post" action="create.php" class="theform" value="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="title2 form-floating mb-3">
                <label for="title">Titre</label>
                <input type="text" name="title" class="" id="title" placeholder="Titre">
            </div>
            <div class="content2 form-floating">
                <label for="content">Contenu</label>
                <input type="text" name="content" class="" id="content" placeholder="Contenu">
            </div>
            <div class="date2 form-floating">
                <label for="date">Date</label>
                <input type="date" name="date" class="form-control" id="date" placeholder="Date">
            </div>
            <button type="submit" class="btn">Envoyer</button>
        </form>
    </section>
</body>
</html>
