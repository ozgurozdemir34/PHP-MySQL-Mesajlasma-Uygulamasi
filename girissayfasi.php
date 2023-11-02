<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="" method="post">
    Kullanıcı Adı:<input type="text" name="kullaniciadi">
    Şifre:<input type="password" name="sifre">
    <input type="submit" value="Giriş Yap" name="giris">
    <input type="submit" value="Kaydol" name="kaydol">
</form>
</body>
</html>

<?php
session_start();
$id=md5(rand(0,10000000000));
$kullaniciadi=$_POST["kullaniciadi"];
$sifre=md5($_POST["sifre"]);
$kullanici=mysqli_connect("localhost","root","","kullanici");
$kayitvarmi="SELECT*FROM kullanici WHERE kullaniciadi='$kullaniciadi'";
$kayitvarmisorgu=mysqli_query($kullanici,$kayitvarmi);
date_default_timezone_set("Europe/Istanbul");



if($kullaniciadi==null and $_POST["sifre"]==null){
    echo"Kullanıcı adı ve şifre boş bırakılamaz";
}
elseif($kullaniciadi==null){
    echo"Kullanıcı adı boş bırakılamaz";
}
elseif($_POST["sifre"]==null){
    echo"Şifre kısmı boş bırakılamaz";
}

else{    
if(isset($_POST["kaydol"])){
    
    if (mysqli_num_rows($kayitvarmisorgu)>0) {
    echo"Zaten kayıt var";
}
    else{
        if(strlen($_POST["sifre"])<6){
            echo"Şifreniz en az 6 karakterli olmalıdır";       
        }
        else{
            $idsorgu="SELECT id FROM kullanici WHERE id='$id'";
            $idsorgulama=mysqli_query($kullanici,$idsorgu);
            $tarih=date("Y-m-d H:i:s");
         while(mysqli_num_rows($idsorgulama)==0){
            
            $id=md5(rand(0,10000000000));
            $kaydet="INSERT INTO kullanici(kullaniciadi,sifre,id,giristarih) VALUES('$kullaniciadi','$sifre','$id','$tarih')";
        $kaydetmek=mysqli_query($kullanici,$kaydet);
        if($kaydetmek){
            
            echo"Başarıyla kaydınız oluşturuldu";
            header("Refresh:3;giris.php?id=$id");
            die();
        }
         }
            
        
        
       }
    }

}

if(isset($_POST["giris"])){
    $giris="SELECT*FROM kullanici WHERE kullaniciadi='$kullaniciadi' AND sifre='$sifre'";
    $girissorgu=mysqli_query($kullanici,$giris);
    if(mysqli_num_rows($girissorgu)==1){
        $_SESSION["kullaniciadi"]=$kullaniciadi;
        $getir=mysqli_fetch_array($girissorgu);
        $getiryonlen=$getir['id'];
        echo"Hoşgeldin";
        $yenitarih=date("Y-m-d H:i:s");
        $yenitarihdegis="UPDATE kullanici SET giristarih='$yenitarih' WHERE kullaniciadi='$kullaniciadi' AND sifre='$sifre' ";
        $yenitarihgir=mysqli_query($kullanici,$yenitarihdegis);
        header("Location:giris.php?id=$getiryonlen");
        die();
             
    }
    else{
        echo"Tekrar dene";
    }
}
}
?>