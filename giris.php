<a href="profil.php?"></a>

<?php
session_start();
require("baglanti.php");
$dene=@$_GET["id"];
$oturumacikmi=$_SESSION["kullaniciadi"];
$oturumsorgu="SELECT*FROM kullanici WHERE kullaniciadi='$oturumacikmi' AND id='$dene'";
$oturumsonuc=mysqli_query($baglan,$oturumsorgu);
if(mysqli_num_rows($oturumsonuc)==0){
    echo"Lütfen oturum açınız";
}
else{
$girisbaglan=mysqli_connect("localhost","root","","kullanici");
$mesaj=mysqli_connect("localhost","root","","mesajlar");
$sec="SELECT kullaniciadi FROM kullanici WHERE id='$dene'";
$sifredegismeid="SELECT id FROM kullanici WHERE id='$dene'";
$sifredegis=mysqli_query($girisbaglan,$sifredegismeid);
$baglan=mysqli_query($girisbaglan,$sec);
$getirme=mysqli_fetch_array($baglan);


    echo"Hoşgeldin"," ",$getirme['kullaniciadi'];
    echo"<br>";

      


?>
<form action="" method="post">
<input type="submit" value="Şifreni değiştirmek mi istiyorsun?" name="şifredeğiş">
Kullanıcı Ara:<input type="text" name="ara">
<br>
<input type="submit" name="arabuton" value="Ara">
<input type="submit" name="çıkış" value="Çıkış yap">


</form>
<?php
$_SESSION["profilkontrol"]=$getirme;
$aramacubugu=$_POST["ara"];
$kimgondermis="SELECT DISTINCT gonderen_id FROM mesajlar WHERE alici_id='$dene' ";
$kgs=mysqli_query($mesaj,$kimgondermis);
$kgssorgu=mysqli_fetch_all($kgs);
$tarihsec="SELECT giristarih FROM kullanici WHERE id='$dene'";
$tarihsorgu=mysqli_query($girisbaglan,$tarihsec);
$tarihgetir=mysqli_fetch_row($tarihsorgu);
$mesajtarihsorgu="SELECT tarih FROM mesajlar WHERE alici_id='$dene' ORDER BY tarih DESC";
$mesajtarihsonuc=mysqli_query($mesaj,$mesajtarihsorgu);
$mesajtarihgetir=mysqli_fetch_row($mesajtarihsonuc);
$okunmayan="SELECT gonderen_id FROM mesajlar WHERE alici_id='$dene' AND okundumu=false";
$okunmayanidsorgu=mysqli_query($mesaj,$okunmayan);
$okunmayangetir=mysqli_fetch_all($okunmayanidsorgu);
foreach ($okunmayangetir as $okunmayanlar) {
    echo"OKUNMAYAN MESAJ:";
    echo"<br>";
    for ($i=0; $i <count($okunmayanlar) ; $i++) { 
        $esasokunmayan="SELECT kullaniciadi FROM kullanici WHERE id='$okunmayanlar[$i]' ";
        $esasokunmayansorgu=mysqli_query($girisbaglan,$esasokunmayan);
        $esasokunmayangetir=mysqli_fetch_row($esasokunmayansorgu);
        echo "<a href='profil.php?kullaniciadi=$esasokunmayangetir[0]&id=$dene'> $esasokunmayangetir[$i] </a>";
        echo"<br>"; 
    }
    
}

echo"Son mesaj tarihiniz:",$mesajtarihgetir[0];



echo"<br>";
echo"TÜM MESAJLAR:";
echo"<br>";
foreach($kgssorgu as $sorgu){
    
    for ($i=0; $i <count($sorgu) ; $i++) { 
        
        $gonderensorgula="SELECT kullaniciadi FROM kullanici WHERE id='$sorgu[$i]'";
        $gss=mysqli_query($girisbaglan,$gonderensorgula);
        $gssgetir=mysqli_fetch_all($gss);
        foreach ($gssgetir as $gssgetir1 ) {
            
                
            foreach ($gssgetir1 as $gssgetir2) {
                echo "<a href='profil.php?kullaniciadi=$gssgetir2&id=$dene'> $gssgetir2 </a>";
                echo"<br>";  
        
        }    
    
    
    }
         
        
    }

}

    
        


if(isset($_POST["şifredeğiş"])){
    header("Location:sifredegis.php?id=$dene");
}

if(isset($_POST["arabuton"])){
    if($_POST["ara"]!=null){

        $ara="SELECT kullaniciadi FROM kullanici WHERE kullaniciadi LIKE '%$aramacubugu%' ";
    $arabaglan=mysqli_query($girisbaglan,$ara);
    $aragetir=mysqli_fetch_all($arabaglan);
    echo"ARAMA SONUÇLARI:";
    foreach($aragetir as $arananlar1){
        foreach($arananlar1 as $arananlar2){
            echo"<br>";
        echo "<a href='profil.php?kullaniciadi=$arananlar2&id=$dene'>$arananlar2</a>";
        }
        
    }
    
    
        
    }
    else{
        echo"Aratma kısmı boş";
    }
    
}

if(isset($_POST["çıkış"])){
session_destroy();
header("Location:girissayfasi.php");
}
}

?>