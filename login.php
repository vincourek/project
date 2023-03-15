<?php

include 'connect.php';

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = $_POST['pass'];
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $verify_email = $conn->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
   $verify_email->execute([$email]);
   

   if($verify_email->rowCount() > 0){
      $fetch = $verify_email->fetch(PDO::FETCH_ASSOC);
      $verfiy_pass = password_verify($pass, $fetch['password']);
      if($verfiy_pass == 1){
         setcookie('user_id', $fetch['id'], time() + 60*60*24*30, '/');
         header('location:index.php');
      }else{
         $warning_msg[] = 'Nesprávné heslo!';
      }
   }else{
      $warning_msg[] = 'Chybný email!';
   }
   
}

?>

<!DOCTYPE html>
<html lang="cs">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Přihlášení</title>
   <link rel="shortcut icon" href="img/favicon.png"/>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body class="home">
   
<!-- načtení hlavičky  -->
<?php include 'header.php'; ?>
<!-- konec sekce hlavička -->

<!-- přihlašovací formulář  -->

<section class="formular">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Vítej zpět</h3>
      <p class="placeholder">Email <span>*</span></p>
      <input type="email" name="email" required maxlength="50" placeholder="Zadej svůj email" class="box">
      <p class="placeholder">Heslo <span>*</span></p>
      <input type="password" name="pass" required maxlength="50" placeholder="Zadej heslo" class="box">
      <p class="link">Ještě nemáš účet? <a href="registrace.php">Zaregistruj se zde!</a></p>
      <input type="submit" value="Přihlásit" name="submit" class="tlacitko">
   </form>

</section>

<!-- konec sekce formulář -->

   
<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'alert.php'; ?>

</body>
</html>