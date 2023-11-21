<?php
require 'connexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location: index.php');
    exit();
}


$query = "SELECT * FROM postit WHERE id=:id";
$response = $bdd->prepare($query);
$response->execute(['id' => $_GET['id']]);
$data = $response->fetch();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    
    $deleteQuery = "DELETE FROM postit WHERE id=:id";
    $deleteResponse = $bdd->prepare($deleteQuery);
    $deleteResponse->execute(['id' => $_GET['id']]);

    header('location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer le post-it</title>
</head>
<body>
    <a href='index.php' title='back'>Retour</a><br>
    <h2>Confirmation de suppression</h2>
    <p>Confirmez-vous la suppression du post-it suivant ?</p>
    
    <strong>Titre:</strong> <?= $data['title'] ?><br>
    <strong>Content:</strong> <?= $data['content'] ?><br>
    <strong>Date:</strong> <?= $data['date'] ?><br>

    <form method="post" action="">
        <input type="submit" name="confirm" value="Confirmer la suppression">
    </form>
</body>
</html>