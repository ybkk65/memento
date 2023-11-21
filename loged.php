<?php

session_start();

require 'connexion.php'; 

try {
    $Email = $_SESSION['email']; 
    $stmt = $bdd->prepare("SELECT first_name FROM admin WHERE email = :email");
    $stmt->bindParam(':email', $Email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    
    $firstName = ($result) ? $result['first_name'] : 'Utilisateur';

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<header>
    <nav> 
        <h1>Memento</h1>
        <div class="log">
            <?php 
            
            echo "Bonjour, ", $firstName;
            echo '<a href="index.php">Déconnexion</a>';
            ?>
        </div>
    </nav>
</header>

<?php include "layout.php"; ?>
