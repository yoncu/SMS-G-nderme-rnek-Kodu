<?php
$YoncuUser	= 'API ID';	// Üye İşlemler/Menü Devamı/Güvenlik Ayarları/API Erişimi menüsünden alabilirsiniz.
$YoncuPass	= 'API KEY';// Üye İşlemler/Menü Devamı/Güvenlik Ayarları/API Erişimi menüsünden alabilirsiniz.
$SmsBaslik	= 'GonderenAdi';	// Başlık SMS Gönderim Panelinden Alınabilir.
$SmsMesaj	= 'Bu bir deneme mesajı içeriğidir';
$GidecekNo	= '905320000000';

$Post	= 'ka='.urlencode($YoncuUser).'&sf='.urlencode($YoncuPass);
$Post	.= '&sb='.urlencode($SmsBaslik).'&sm='.urlencode($SmsMesaj).'&gn='.urlencode($GidecekNo);
$Curl = curl_init();
curl_setopt($Curl, CURLOPT_URL, "http://www.yoncu.com/apiler/sms/gonder.php");
curl_setopt($Curl, CURLOPT_HEADER, false);
curl_setopt($Curl, CURLOPT_ENCODING, false);
curl_setopt($Curl, CURLOPT_COOKIESESSION, false);
curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($Curl, CURLOPT_HTTPHEADER, array(
	'Connection: keep-alive',
	'User-Agent: '.$_SERVER['SERVER_NAME'],
	'Referer: http://www.yoncu.com/',
	'Cookie: YoncuKoruma='.$_SERVER['SERVER_ADDR'],
));
curl_setopt($Curl, CURLOPT_POSTFIELDS, $Post);
if(curl_errno($Curl) == 0){
	$Json	= trim(curl_exec($Curl));
	if($Json != ""){
		list($Durum,$Bilgi)	= (array)json_decode($Json,true);
		if(json_last_error() == 0){
			if($Durum == true){
				echo 'SMS Gönderildi. Detaylı Bilgi: '.var_export($Bilgi,true);
			}else{
				echo 'Hata: '.$Json;
			}
		}else{
			$JsEr=array(
				0=>'Hata Bulunamadı',
				1=>'Max İçeriğe Ulaşıldı',
				2=>'Data Uyumsuz',
				3=>'Yanlış Kodlanmış',
				4=>'Sözdizimi Hatalı',
				5=>'Karakter Kodlama Hatalı',
			);
			echo 'Data Hata: Veri Json Değil ('.$JsEr[json_last_error()].')';
			echo "<br/>Gelen Veri:<br/>".$Json;
		}
	}else{
		echo 'Data Hata: Veri Boş Çekildi';
	}
}else{
	echo "Curl Hata: ".curl_errno($Curl)." - ".curl_error($Curl);
}
curl_close($Curl);
?>
