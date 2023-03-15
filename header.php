<header class="hlava">
    <section class="sekce">

        <a href="index.php"><img class="logo" src="img/logo.png" alt="Hory volají"/></a>

        

      <?php
         if($user_id != ''){
      ?>
      <div class="profil">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <?php if($fetch_profile['image'] != ''){ ?>
            <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="" class="image" ";>
         <?php }; ?>   
         <p><b><?= $fetch_profile['name']; ?></b></p>
         <a href="update.php" class="tlacitko">Upravit</a>
         <a href="logout.php" class="tlacitko" onclick="return confirm('Opravdu se chceš odhlásit?');">Odhlásit</a>
         <?php }else{ ?>
            <div class="tlacitko">
               <p>Nejprve se musíš přihlásit!</p>
               <a href="login.php" class="tlacitko">Přihlásit</a>
               <a href="register.php" class="tlacitko">Registrovat</a>
            </div>
         <?php }; ?>
      </div>
      <?php }else{ ?>
        <nav class="log">
            <a href="login.php" class="tlacitko" role="button">
            Přihlásit
            </a>
            <a href="registrace.php" class="tlacitko" role="button">
            Registrovat
            </a>
            
         
      </nav>


      <?php }; ?>

   </section>

</header>
