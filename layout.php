<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>memento</title>
</head>
<body>

<hr>
    <main>
        <div class="title">
            <h2>Memento</h2>
            <a href="create.php">Add Postit</a>
        </div>

        

    
<section class="container1">

<?php
$query = "SELECT * FROM postit";
$response = $bdd->query($query);
$datas = $response->fetchAll();

foreach ($datas as $data) {
?>
 <div class="postit"> 
    <div class="interior">
         <div class="high">
        <h3 ><?= $data['title'] ?></h3>
        <a href='delete.php?id=<?= $data['id'] ?>' title='<?= $data['title'] ?>'><i class="fa-solid fa-x"></i></a>

         </div>
        <p><?= $data['content'] ?><br><?= $data['date'] ?></p>
       
    </div>
</div>
<?php
}
?>
</section>


    </main>
    
</body>
</html>