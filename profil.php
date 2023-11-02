<?php
require("baglanti.php");
session_start();
$oturumacikmi=$_SESSION["profilkontrol"];
$dene=@$_GET["id"];
$oturumsorgu="SELECT*FROM kullanici WHERE kullaniciadi='$oturumacikmi[0]' AND id='$dene'";
$oturumsonuc=mysqli_query($baglan,$oturumsorgu);
if(mysqli_num_rows($oturumsonuc)==0){
    echo"Lütfen oturum açınız";
}
else{
date_default_timezone_set("Europe/Istanbul");
$profilad=@$_GET["kullaniciadi"];
$id=@$_GET["id"];
$adsorgu="SELECT kullaniciadi FROM kullanici WHERE kullaniciadi='$profilad'";
$idsorgu="SELECT id FROM kullanici WHERE kullaniciadi='$profilad'";
$idsonuc=mysqli_query($baglan,$idsorgu);
$idgetir=mysqli_fetch_row($idsonuc);
$adsonuc=mysqli_query($baglan,$adsorgu);
$adgetir=mysqli_fetch_row($adsonuc);
foreach($adgetir as $ad){
    echo"Kullancı adı:"," ",$ad;
}
$tarih="SELECT giristarih FROM kullanici WHERE kullaniciadi='$profilad'";
$tarihsorgu=mysqli_query($baglan,$tarih);
$tarihgetir=mysqli_fetch_row($tarihsorgu);
echo"<br>";
echo"Son giriş tarihi(Yıl-ay-gün-saat):",$tarihgetir[0];

?>
<br>
Mesajınızı girin:
<br>
<form action="" method="post">
<textarea name="mesaj" cols="30" rows="10"></textarea>
<br>
<input type="submit" name="gönder" value="Gönder">
<input type="submit" name="okundumu" value="Okudum">
<input type="submit" name="çıkış" value="Çıkış">
<input type="submit" name="engelle" value=" Engelle">
<input type="submit" name="engelkaldır" value="Engeli Kaldır">
</form>
<?php
$okundumu=false;
$mesajlar="SELECT mesaj FROM mesajlar WHERE alici_id='$id' AND gonderen_id='$idgetir[0]' ORDER BY tarih DESC";
$mesajsorgu=mysqli_query($mesajbaglan,$mesajlar);
$mesajlarigetir=mysqli_fetch_all($mesajsorgu);
$okumakontrol="SELECT mesaj FROM mesajlar WHERE alici_id='$id' AND gonderen_id='$idgetir[0]' AND okundumu=false ORDER BY tarih DESC";
$okumasorgu=mysqli_query($mesajbaglan,$okumakontrol);
$okumagetir=mysqli_fetch_all($okumasorgu);
echo"Okunmamış mesajlar:<br>";
foreach ($okumagetir as $okumagetir1 ) {
    foreach ($okumagetir1 as $okumagetir2) {
        echo $okumagetir2;
        echo"<br>";
    }
    
}       

echo"Bütün mesajlar:<br>";
$i=0;
foreach($mesajlarigetir as $mesaj){
    foreach($mesaj as $esasmesaj){
        echo ++$i,".";
        echo $esasmesaj;
        echo"<br>";
    }
}
if (isset($_POST["okundumu"])) {
    
$okunmadogrula="UPDATE mesajlar SET okundumu=true WHERE alici_id='$id' AND gonderen_id='$idgetir[0]' ";
$okunmadogrulasorgu=mysqli_query($mesajbaglan,$okunmadogrula);

}
$mesaj=$_POST["mesaj"];


if ($mesaj!=null) {
    

if(isset($_POST["gönder"])){
$engelkontrol="SELECT*FROM engelleme WHERE engelleyen_id='$idgetir[0]' AND engellenen_id='$id'";
$engelkontrolsorgu=mysqli_query($baglan,$engelkontrol);
$karsiengelkontrol="SELECT*FROM engelleme WHERE engelleyen_id='$id' AND engellenen_id='$idgetir[0]'";
$karsiengelkontrolsorgu=mysqli_query($baglan,$karsiengelkontrol);
if(mysqli_num_rows($engelkontrolsorgu)!=0){
         echo"<br>";
         echo"Kullanıcı sizi engellemiş";
}
elseif(mysqli_num_rows($karsiengelkontrolsorgu)!=0){
    echo"<br>";
    echo"Kullanıcıya mesaj göndermek için önce engeli kaldırmalısınız";

}

else{   
$aliciid="SELECT id FROM kullanici WHERE kullaniciadi='$profilad'";
$aliciidsorgu=mysqli_query($baglan,$aliciid);
$aliciidgetir=mysqli_fetch_row($aliciidsorgu);
$mesajtarih=date("Y-m-d H:i:s");
$mesajgonder="INSERT INTO mesajlar(gonderen_id,alici_id,mesaj,tarih,okundumu) VALUES('$id','$aliciidgetir[0]','$mesaj','$mesajtarih','$okundumu')";
$mesajgondersorgu=mysqli_query($mesajbaglan,$mesajgonder);
if($mesajgondersorgu){
    echo"<br>";
    echo"Mesajınız gönderilmiştir";
    
}
} 
}   
}

else{
    echo"Mesaj kısmı boş olamaz";
}

if(isset($_POST["çıkış"])){
    session_destroy();
    header("Location:girissayfasi.php");
}

if(isset($_POST["engelle"])){
    $engelkontrol="SELECT*FROM engelleme WHERE engelleyen_id='$id' AND engellenen_id='$idgetir[0]'";
    $engelkontrolsorgu=mysqli_query($baglan,$engelkontrol);
    if(mysqli_num_rows($engelkontrolsorgu)!=0){
         echo"Kullanıcı zaten engelli";
    }
    else{
        $engelle="INSERT INTO engelleme(engelleyen_id,engellenen_id) VALUES('$id','$idgetir[0]')";
        $engellesorgu=mysqli_query($baglan,$engelle);
        if($engellesorgu){
            echo"<br>";
            echo"Kullanıcı başarıyla engellendi";
        }
    }
   
    }

if(isset($_POST["engelkaldır"])){
    $engelkontrol="SELECT*FROM engelleme WHERE engelleyen_id='$id' AND engellenen_id='$idgetir[0]'";
    $engelkontrolsorgu=mysqli_query($baglan,$engelkontrol);
    if(mysqli_num_rows($engelkontrolsorgu)==0){
         echo"<br>";
         echo"Kullanıcı zaten engelli değil";
    }
    else{
        $engelkaldir="DELETE FROM engelleme WHERE engelleyen_id='$id' AND engellenen_id='$idgetir[0]' ";
        $engelkaldirsorgu=mysqli_query($baglan,$engelkaldir);
        if($engelkaldirsorgu){
            echo"<br>";
            echo"Kullanıcının engeli kaldırıldı";
        }
    }    
} 
}  
?>