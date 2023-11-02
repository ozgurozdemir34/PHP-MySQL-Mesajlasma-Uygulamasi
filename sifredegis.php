<form action="" method="post">
Eski şifreniz nedir:<input type="password" name="şifre">
Yeni şifrenizi giriniz:<input type="password" name="yenişifre">
Yeni şifrenizi tekrar giriniz:<input type="password" name="tekrar">
<input type="submit" value="Değiştir" name="değiştir">


</form>

<?php
$sifre=md5($_POST['şifre']);
$yenisifre=md5($_POST["yenişifre"]);
$tekrar=md5($_POST["tekrar"]);
$baglan=mysqli_connect("localhost","root","","kullanici");
$id_al=@$_GET['id'];
if(isset($_POST["değiştir"])){
$sifredogrumu="SELECT sifre FROM kullanici WHERE id='$id_al' AND sifre='$sifre'";
$dogrumu=mysqli_query($baglan,$sifredogrumu);
if(mysqli_num_rows($dogrumu)!=1){
    echo"Şifrenizi hatalı girdiniz";


    
}

elseif(strlen($_POST["yenişifre"])<6){
    echo"Yeni şifreniz en az 6 karakterden oluşmalı";
}

elseif($yenisifre!=$tekrar){
    echo"Yeni şifre tekrarı uyuşmuyor";
}

else{
    $degistir="UPDATE kullanici SET sifre='$yenisifre' WHERE id='$id_al'";
    $degistirme=mysqli_query($baglan,$degistir);
    if($degistirme){
        echo"Şifreniz başarıyla değiştirildi";
    }
}
}
?>