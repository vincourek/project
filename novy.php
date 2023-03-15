<?php

include 'connect.php';



if(isset($_POST['submit'])){

    if($user_id != ''){
 
        $id = create_unique_id();
        $kraj = $_POST['kraj'];
        $kraj = filter_var($kraj, FILTER_SANITIZE_STRING);
        $narocnost = $_POST['narocnost'];
        $narocnost = filter_var($narocnost, FILTER_SANITIZE_STRING);
        $kategorie = $_POST['kategorie'];
        $kategorie = filter_var($kategorie, FILTER_SANITIZE_STRING);
        $vzdalenost = $_POST['vzdalenost'];
        $vzdalenost = filter_var($vzdalenost, FILTER_SANITIZE_STRING);
        $nazev = $_POST['nazev'];
        $nazev = filter_var($nazev, FILTER_SANITIZE_STRING);
        $popis = $_POST['popis'];
        $popis = filter_var($popis, FILTER_SANITIZE_STRING);

        $foto1 = $_FILES['foto1']['name'];
        $foto1 = filter_var($foto1, FILTER_SANITIZE_STRING);
        $ext = pathinfo($foto1, PATHINFO_EXTENSION);
        $rename = create_unique_id().'.'.$ext;
        $image_size = $_FILES['foto1']['size'];
        $image_tmp_name = $_FILES['foto1']['tmp_name'];
        $image_folder = 'uploaded_files/'.$rename;

        $foto2 = $_FILES['foto2']['name'];
        $foto2 = filter_var($foto2, FILTER_SANITIZE_STRING);
        $ext2 = pathinfo($foto2, PATHINFO_EXTENSION);
        $rename2 = create_unique_id().'.'.$ext2;
        $image_size2 = $_FILES['foto2']['size'];
        $image_tmp_name2 = $_FILES['foto2']['tmp_name'];
        $image_folder2 = 'uploaded_files/'.$rename2;

        $foto3 = $_FILES['foto3']['name'];
        $foto3 = filter_var($foto3, FILTER_SANITIZE_STRING);
        $ext3 = pathinfo($foto3, PATHINFO_EXTENSION);
        $rename3 = create_unique_id().'.'.$ext3;
        $image_size3 = $_FILES['foto3']['size'];
        $image_tmp_name3 = $_FILES['foto3']['tmp_name'];
        $image_folder3 = 'uploaded_files/'.$rename3;

        if(!empty($foto1)){
            if($image_size > 7000000){
               $warning_msg[] = 'Obrázek1 je příliš velký!';
            }else{
               move_uploaded_file($image_tmp_name, $image_folder);
            }
         }else{
            $rename = '';
         }

         if(!empty($foto2)){
            if($image_size2 > 7000000){
               $warning_msg[] = 'Obrázek2 je příliš velký!';
            }else{
               move_uploaded_file($image_tmp_name2, $image_folder2);
            }
         }else{
            $rename2 = '';
         }

         if(!empty($foto3)){
            if($image_size3 > 7000000){
               $warning_msg[] = 'Obrázek3 je příliš velký!';
            }else{
               move_uploaded_file($image_tmp_name3, $image_folder3);
            }
         }else{
            $rename3 = '';
         }
         
         
       
          $add_review = $conn->prepare("INSERT INTO `vylet` (id, user_id, kraj, narocnost, kategorie, vzdalenost, nazev, popis, foto1, foto2, foto3) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
          $add_review->execute([$id, $user_id, $kraj, $narocnost, $kategorie, $vzdalenost, $nazev, $popis, $rename, $rename2, $rename3]);
          $success_msg[] = 'Tip přidán!';
       
 
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

<?php
    include 'header.php';
?>

<section class="formular">
        
        <form action="" method="post" enctype="multipart/form-data">
        <h3>Přidat nový tip</h3>
        
            <?php
                    if (isset($_GET['error'])) { ?>
                    <p class="error"><?php echo $_GET['error']; ?></p>

                    <?php } ?>
                <p for="kraj">Vyber kraj: </p>
                <select id="kraje" name="kraj" class="box">
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
                <p for="cil">Jak do cíle: </p>
                <select id="cil" name="cil" class="box">
                    <option value="Autem">Autem</option>
                    <option value="Pěšky">Pěšky</option>
                    <option value="NaKole">Na kole</option>
                    <option value="Vlakem">Vlakem</option>
                </select>
                <p for="narocnost">Obtížnost: </p>
                <select id="narocnost" name="narocnost" class="box">
                     <option value="S kočárkem">S kočárkem</option>
                     <option value="Pro děti">Pro děti</option>
                     <option value="Lehká">Lehká</option>
                     <option value="Střední">Střední</option>
                     <option value="Těžká">Těžká</option>
                </select>
                <p for="kategotie">Kategorie: </p>
                <select id="kategorie" name="kategorie" class="box" required>
                    <option value="Hory">Hory</option>
                    <option value="Rozhledny">Rozhledny</option>
                    <option value="Zážitky">Zážitky</option>
                    <option value="Jeskyně">Jeskyně</option>
                    <option value="Zajímavá místa">Zajímavá místa</option>
                </select>
                <p for="vzdalenost">Vzdálenost v kilometrech: <span>*</span></p>
                <input type="number" name="vzdalenost" id="vzdalenost" class="box">
        
                <p for="nazev">Název <span>*</span></p>
                <input type="text" id="nazev" name="nazev" required placeholder="Název výletu" class="box"/>
            
        
                <p for="popis">Popis výletu <span>*</span></p>
                <textarea name="popis" id="popis" cols="80" rows="50" required maxlength="2000" class="box"></textarea>
            
        
                <p for="foto1">Fotografie náhledová: </p>
                <input type="file"  id="foto1" name="foto1" />
                <p for="foto2">Fotografie: </p>
                <input type="file"  id="foto2" name="foto2" />
                <p for="foto3">Fotografie: </p>
                <input type="file"  id="foto3" name="foto3" />
                <p class="instrukce">Maximální počet fotografií jsou 3. Povolené formáty JPEG, PNG, JPG</p>
            
        
                <button type="submit" name="submit" value="submit" class="tlacitko">Přidat</button>
            
            <p>Pokud ještě nemáš účet, zaregistruj se <a href="registrace.php">
                zde.</a></p>
            
        </form>
</section>









<!-- sweetalert cdn link  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'alert.php'; ?>

</body>
</html>