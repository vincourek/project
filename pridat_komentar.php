<?php

include 'connect.php';

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:index.php');
}

if(isset($_POST['submit'])){

   if($user_id != ''){

      $id = create_unique_id();
      $title = $_POST['title'];
      $title = filter_var($title, FILTER_SANITIZE_STRING);
      $popis = $_POST['popis'];
      $popis = filter_var($popis, FILTER_SANITIZE_STRING);
      $rating = $_POST['rating'];
      $rating = filter_var($rating, FILTER_SANITIZE_STRING);

      $verify_review = $conn->prepare("SELECT * FROM `hodnoceni` WHERE post_id = ? AND user_id = ?");
      $verify_review->execute([$get_id, $user_id]);

      if($verify_review->rowCount() > 0){
         $warning_msg[] = 'Tohle už jsi komentoval!';
      }else{
         $add_review = $conn->prepare("INSERT INTO `hodnoceni` (id, post_id, user_id, rating, title, popis) VALUES (?,?,?,?,?,?)");
         $add_review->execute([$id, $get_id, $user_id, $rating, $title, $popis,]);
         $success_msg[] = 'Hodnocení přidáno!';
      }

   }else{
      $warning_msg[] = 'Nejprve se musíš přihlásit!';
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

<!-- header section starts  -->
<?php include 'header.php'; ?>
<!-- header section ends -->

<!-- add review section starts  -->

<section class="formular">

   <form action="" method="post">
      <h3>Hodnocení</h3>
      <p class="placeholder">Název <span>*</span></p>
      <input type="text" name="title" required maxlength="50" placeholder="Jedním slovem?" class="box">
      <p class="placeholder">Shrnutí</p>
      <textarea name="popis" class="box" placeholder="Jak se tip líbí?" maxlength="1000" cols="30" rows="10"></textarea>
      <p class="placeholder">Hodnocení <span>*</span></p>
      <select name="rating" class="box" required>
         <option value="1">1</option>
         <option value="2">2</option>
         <option value="3">3</option>
         <option value="4">4</option>
         <option value="5">5</option>
      </select>
      <input type="submit" value="Ohodnotit" name="submit" class="tlacitko">
      <a href="hodnoceni.php?get_id=<?= $get_id; ?>" class="tlacitko">Zpět</a>
   </form>

</section>

<!-- add review section ends -->



   
<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'alert.php'; ?>

</body>
</html>
