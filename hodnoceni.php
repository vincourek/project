<?php

include 'connect.php';

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:index.php');
}

if(isset($_POST['delete_review'])){

   $delete_id = $_POST['delete_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_delete = $conn->prepare("SELECT * FROM `hodnoceni` WHERE id = ?");
   $verify_delete->execute([$delete_id]);
   
   if($verify_delete->rowCount() > 0){
      $delete_review = $conn->prepare("DELETE FROM `hodnoceni` WHERE id = ?");
      $delete_review->execute([$delete_id]);
      $success_msg[] = 'Komentář smazán!';
   }else{  
      $warning_msg[] = 'Komentář nelze smazat!';
   }

}

?>

<!DOCTYPE html>
<html lang="cs">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Holry volají</title>
   

   <!-- odkaz na css  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="shortcut icon" href="img/favicon.png">

</head>
<body class="home">

<?php
    include 'header.php';
?>



<!-- view posts section starts  -->

<section class="view-post">

   <div class="heading"><h1>Detail výletu</h1> <a href="index.php" class="tlacitko" style="margin-top: 0;">Všechny tipy</a></div>

   <?php
      $select_post = $conn->prepare("SELECT * FROM `vylet` WHERE id = ? LIMIT 1");
      $select_post->execute([$get_id]);
      if($select_post->rowCount() > 0){
         while($fetch_post = $select_post->fetch(PDO::FETCH_ASSOC)){

        $total_ratings = 0;
        $rating_1 = 0;
        $rating_2 = 0;
        $rating_3 = 0;
        $rating_4 = 0;
        $rating_5 = 0;

        $select_ratings = $conn->prepare("SELECT * FROM `hodnoceni` WHERE post_id = ?");
        $select_ratings->execute([$fetch_post['id']]);
        $total_reivews = $select_ratings->rowCount();
        while($fetch_rating = $select_ratings->fetch(PDO::FETCH_ASSOC)){
            $total_ratings += $fetch_rating['rating'];
            if($fetch_rating['rating'] == 1){
               $rating_1 += $fetch_rating['rating'];
            }
            if($fetch_rating['rating'] == 2){
               $rating_2 += $fetch_rating['rating'];
            }
            if($fetch_rating['rating'] == 3){
               $rating_3 += $fetch_rating['rating'];
            }
            if($fetch_rating['rating'] == 4){
               $rating_4 += $fetch_rating['rating'];
            }
            if($fetch_rating['rating'] == 5){
               $rating_5 += $fetch_rating['rating'];
            }
        }

        if($total_reivews != 0){
            $average = round($total_ratings / $total_reivews, 1);
        }else{
            $average = 0;
        }

         $select_profile = $conn->prepare("SELECT * FROM `users`");
         $select_profile->execute();
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
   ?>
   <div class="row">
      <div class="col">
         <p class="user"><?= $fetch_profile['name']; ?></p>
         <p class="datum"><?= $fetch_post['datum']; ?></p>
         
         <h3 class="title"><?= $fetch_post['nazev']; ?></h3>
         <p class="popis"><?= $fetch_post['popis']; ?></p></br>
         <p class="kategorie"><b>Kategorie: </b><?= $fetch_post['kategorie']; ?></p>
         <p class="kategorie"><b>Délka: </b><?= $fetch_post['vzdalenost']; ?> km</p>         
      </div>
      <div class="col">
         <div class="flex">
            <div class="total-reviews">
               <h3><?= $average; ?><i class="fas fa-star"></i></h3>
               <p><?= $total_reivews; ?> Hodnocení</p>
            </div>
            <div class="total-ratings">
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= $rating_5; ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= $rating_4; ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= $rating_3; ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <span><?= $rating_2; ?></span>
               </p>
               <p>
                  <i class="fas fa-star"></i>
                  <span><?= $rating_1; ?></span>
               </p>              
            </div>
            
         </div>
         
      </div>
      <img src="uploaded_files/<?= $fetch_post['foto1']; ?>" alt="" class="image">
      <img src="uploaded_files/<?= $fetch_post['foto2']; ?>" alt="" class="image">
      <img src="uploaded_files/<?= $fetch_post['foto3']; ?>" alt="" class="image">
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">post is missing!</p>';
      }
   ?>

</section>

<!-- view posts section ends -->

<!-- reviews section starts  -->

<section class="reviews-container">

   <div class="heading"><h1>Hodnocení uživatelů</h1> <a href="pridat_komentar.php?get_id=<?= $get_id; ?>" class="tlacitko" style="margin-top: 0;">Přidat komentář</a></div>

   <div class="box-container">

   <?php
      $select_reviews = $conn->prepare("SELECT * FROM `hodnoceni` WHERE post_id = ?");
      $select_reviews->execute([$get_id]);
      if($select_reviews->rowCount() > 0){
         while($fetch_review = $select_reviews->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box" <?php if($fetch_review['user_id'] == $user_id){echo 'style="order: -1;"';}; ?>>
      <?php
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_user->execute([$fetch_review['user_id']]);
         while($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="user">
         <?php if($fetch_user['image'] != ''){ ?>
            <img src="uploaded_files/<?= $fetch_user['image']; ?>" alt="">
         <?php }else{ ?>   
            <h3><?= substr($fetch_user['name'], 0, 1); ?></h3>
         <?php }; ?>   
         <div>
            <p><?= $fetch_user['name']; ?></p>
            <span><?= $fetch_review['datum']; ?></span>
         </div>
      </div>
      <?php }; ?>
      <div class="ratings">
         <?php if($fetch_review['rating'] == 1){ ?>
            <p style="background:var(--red);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
         <?php }; ?> 
         <?php if($fetch_review['rating'] == 2){ ?>
            <p style="background:var(--orange);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
         <?php }; ?>
         <?php if($fetch_review['rating'] == 3){ ?>
            <p style="background:var(--orange);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
         <?php }; ?>   
         <?php if($fetch_review['rating'] == 4){ ?>
            <p style="background:var(--main-color);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
         <?php }; ?>
         <?php if($fetch_review['rating'] == 5){ ?>
            <p style="background:var(--main-color);"><i class="fas fa-star"></i> <span><?= $fetch_review['rating']; ?></span></p>
         <?php }; ?>
      </div>
      <h3 class="nazev"><?= $fetch_review['title']; ?></h3>
      <?php if($fetch_review['popis'] != ''){ ?>
         <p class="popis"><?= $fetch_review['popis']; ?></p>
      <?php }; ?>  
      <?php if($fetch_review['user_id'] == $user_id){ ?>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="delete_id" value="<?= $fetch_review['id']; ?>">
            <a href="uprava_komentare.php?get_id=<?= $fetch_review['id']; ?>" class="tlacitko">Upravit</a>
            <input type="submit" value="Vymazat" class="tlacitko" name="delete_review" onclick="return confirm('Smazat komentář?');">
         </form>
      <?php }; ?>   
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Zatím žádné hodnocení</p>';
      }
   ?>

   </div>

</section>



   
<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'alert.php'; ?>

</body>
</html>