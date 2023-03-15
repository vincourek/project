<?php

include 'connect.php';

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:index.php');
}

if(isset($_POST['submit'])){

   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $popis = $_POST['popis'];
   $popis = filter_var($popis, FILTER_SANITIZE_STRING);
   $rating = $_POST['rating'];
   $rating = filter_var($rating, FILTER_SANITIZE_STRING);

   $update_review = $conn->prepare("UPDATE `hodnoceni` SET rating = ?, title = ?, popis = ? WHERE id = ?");
   $update_review->execute([$rating, $title, $popis, $get_id]);

   $success_msg[] = 'Upraveno!';

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

<!-- update reviews section starts  -->

<section class="formular">

   <?php
      $select_review = $conn->prepare("SELECT * FROM `hodnoceni` WHERE id = ? LIMIT 1");
      $select_review->execute([$get_id]);
      if($select_review->rowCount() > 0){
         while($fetch_review = $select_review->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post">
      <h3>edit your review</h3>
      <p class="placeholder">review title <span>*</span></p>
      <input type="text" name="title" required maxlength="50" placeholder="enter review title" class="box" value="<?= $fetch_review['title']; ?>">
      <p class="placeholder">review description</p>
      <textarea name="popis" class="box" placeholder="enter review description" maxlength="1000" cols="30" rows="10"><?= $fetch_review['popis']; ?></textarea>
      <p class="placeholder">review rating <span>*</span></p>
      <select name="rating" class="box" required>
         <option value="<?= $fetch_review['rating']; ?>"><?= $fetch_review['rating']; ?></option>
         <option value="1">1</option>
         <option value="2">2</option>
         <option value="3">3</option>
         <option value="4">4</option>
         <option value="5">5</option>
      </select>
      <input type="submit" value="Upravit" name="submit" class="tlacitko">
      <a href="hodnoceni.php?get_id=<?= $fetch_review['post_id']; ?>" class="tlacitko">go back</a>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">something went wrong!</p>';
      }
   ?>

</section>

<!-- update reviews section ends -->














<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'alert.php'; ?>

</body>
</html>