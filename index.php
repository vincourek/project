<?php

include 'connect.php';

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   
}

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="cs">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Holry volají</title>
   
   <script
  src="https://code.jquery.com/jquery-3.6.4.js"
  integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
  crossorigin="anonymous"></script>


   <!-- odkaz na css  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="shortcut icon" href="img/favicon.png">

</head>
<body class="home">

<?php
    include 'header.php';
?>

<section class="nav">  
   <form method="POST" action="">
      <label for="text">Hledej text: </label>
      <input type="search" name="text" id="text" class="box">
      <label for="kraj">Kraj: </label>
      <select id="kraj" name="kraj" class="box">
         <option value=""></option>
         <option value="Praha">Praha</option>
         <option value="Středočeský">Středočeský kraj</option>
         <option value="Jihočeský">Jihočeský kraj</option>
         <option value="Plzeňský">Plzeňský kraj</option>
         <option value="Karlovarský">Karlovarský kraj</option>
         <option value="Ústecký">Ústecký kraj</option>
         <option value="Liberecký">Liberecký kraj</option>
         <option value="Královéhradecký">Královéhradecký kraj</option>
         <option value="Pardubický">Pardubický kraj</option>
         <option value="Vysočina">Kraj Vysočina</option>
         <option value="Jihomoravský">Jihomoravský kraj</option>
         <option value="Olomoucký">Olomoucký kraj</option>
         <option value="Moravskoslezský">Moravskoslezský kraj</option>
         <option value="Zlínský">Zlínský kraj</option>
      </select>
      <label for="jak">Jak do cíle: </label>
      <select id="jak" name="jak" class="box">
         <option value=""></option>
         <option value="autem">Autem</option>
         <option value="pěšky">Pěšky</option>
         <option value="Na kole">Na kole</option>
         <option value="Vlakem">Vlakem</option>
      </select>
      <label for="narocnost">Obtížnost: </label>
      <select id="narocnost" name="narocnost" class="box">
         <option value=""></option>
         <option value="Pro děti">Pro děti</option>
         <option value="Lehká">Lehká</option>
         <option value="Střední">Střední</option>
         <option value="Těžká">Těžká</option>
      </select>
      <label for="vzdalenost">Délka trasy od: 
      <input type="number" name="od" id="vzdalenost" class="box"> do: <input type="number" name="do" id="vzdalenost" class="box"> Km</label>
      <div style="float: right">
      <button name="submit" value="Hledej" class="tlacitko">Hledej</button>
      <a href="novy.php" role="button" class="tlacitko" onclick="return confirm('Pro přidání musíš být přihlášen!')">Přidat</a>
      </div>
   </form> 
</section>
<section class="vylet">

   <div class="box-container">

   <?php
   if(isset($_POST['submit'])){

      


      $text = $_POST['text'];
      $text = filter_var($text, FILTER_SANITIZE_STRING);
      $kraj = $_POST['kraj'];
      $kraj = filter_var($kraj, FILTER_SANITIZE_STRING);
      $jak = $_POST['jak'];
      $jak = filter_var($jak, FILTER_SANITIZE_STRING);
      $narocnost = $_POST['narocnost'];
      $narocnost = filter_var($narocnost, FILTER_SANITIZE_STRING);
      $vzdalenost_od = $_POST['od'];
      $vzdalenost_od = filter_var($vzdalenost_od, FILTER_SANITIZE_NUMBER_INT);
      $vzdalenost_do = $_POST['do'];
      $vzdalenost_do = filter_var($vzdalenost_do, FILTER_SANITIZE_NUMBER_INT);
      
      $select_properties = $conn->prepare("SELECT * FROM vylety WHERE nazev LIKE '%$text%' AND kraj LIKE '%$kraj%' AND jak LIKE '%$jak%' AND narocnost LIKE '%$narocnost%' AND vzdalenost BETWEEN '$vzdalenost_od' AND '$vzdalenost_do'");
      $select_properties->execute();
      if($select_properties->rowCount() > 0){
         while($fetch_post = $select_properties->fetch(PDO::FETCH_ASSOC)){

            

         $post_id = $fetch_post['id'];

         $count_reviews = $conn->prepare("SELECT * FROM `hodnoceni` WHERE post_id = ?");
         $count_reviews->execute([$post_id]);
         $total_reviews = $count_reviews->rowCount();

         $select_profile = $conn->prepare("SELECT * FROM `users`");         
         $select_profile->execute();
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

         $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_user->execute([$user_id]);
         $select_user = $count_reviews->rowCount();

         $user_id = $fetch_user['id'];

        
   ?>
   <a href="hodnoceni.php?get_id=<?= $post_id; ?>" class="">
   <div class="box">
   
      <h3 style="float:left"><b><?= $fetch_profile['name']; ?></b></h3><h3 style="float: right"> <?= $fetch_post['datum']; ?></h3>
      <img src="uploaded_files/<?= $fetch_post['foto1']; ?>" alt="" class="image">
      <h3 class="title"><?= $fetch_post['nazev']; ?></h3>
      <p class="popis" style="float:left">Kraj: <?= $fetch_post['kraj'];?></p><p style="float:right">Obtížnost: <?= $fetch_post['narocnost']; ?></p><br>
      <p class="popis" style="float:left">Kategorie: <?= $fetch_post['kategorie'];?></p><p style="float:right">Délka: <?= $fetch_post['vzdalenost']; ?> km</p><br>
      <p class="total-reviews"><i class="fas fa-star"></i> <span><?= $total_reviews; ?></span></p>
      
   </div>
   </a>
   <?php
         }
      }

   }else{
      $select_posts = $conn->prepare("SELECT * FROM `vylet`");
      $select_posts->execute();
      if($select_posts->rowCount() > 0){
         while($fetch_post = $select_posts->fetch(PDO::FETCH_ASSOC)){

            

         $post_id = $fetch_post['id'];

         $count_reviews = $conn->prepare("SELECT * FROM `hodnoceni` WHERE post_id = ?");
         $count_reviews->execute([$post_id]);
         $total_reviews = $count_reviews->rowCount();

         $select_profile = $conn->prepare("SELECT * FROM `users`");         
         $select_profile->execute();
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

         $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_user->execute([$user_id]);
         $select_user = $count_reviews->rowCount();

         $user_id = $fetch_user['id'];

        
   ?>
   <a href="hodnoceni.php?get_id=<?= $post_id; ?>" class="">
   <div class="box">
   
      <h3 style="float:left"><b><?= $fetch_profile['name']; ?></b></h3><h3 style="float: right"> <?= $fetch_post['datum']; ?></h3>
      <img src="uploaded_files/<?= $fetch_post['foto1']; ?>" alt="" class="image">
      <h3 class="title"><?= $fetch_post['nazev']; ?></h3>
      <p class="popis" style="float:left">Kraj: <?= $fetch_post['kraj'];?></p><p style="float:right">Obtížnost: <?= $fetch_post['narocnost']; ?></p><br>
      <p class="popis" style="float:left">Kategorie: <?= $fetch_post['kategorie'];?></p><p style="float:right">Délka: <?= $fetch_post['vzdalenost']; ?> km</p><br>
      <p class="total-reviews"><i class="fas fa-star"></i> <span><?= $total_reviews; ?></span></p>
      
   </div>
   </a>
   <?php
         }
         
   }else{
      echo '<p class="Zatím nebyl přidán žádný výlet"</p>';
   }
   }


  
   
      
   ?>

   </div>

</section>








   
<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>

document.querySelector('#filter-btn').onclick = () =>{
   document.querySelector('.filters').classList.add('active');
}

document.querySelector('#close-filter').onclick = () =>{
   document.querySelector('.filters').classList.remove('active');
}

</script>

<?php include 'alert.php'; ?>

</body>
</html>