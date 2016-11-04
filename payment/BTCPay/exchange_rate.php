<?php

/**
 * Simpla CMS 2.1.5
 * @copyright 	2016 mXtbZ
 * К этому скрипту обращается BTCPay в процессе оплаты
 * Получение курса валют, указывается параметр в админ.панели
 */
 
// Получаем курс валют
$get_exchange_price = 'https://bitpay.com/api/rates/'.$currency;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $get_exchange_price);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
$exchange_price = curl_exec ($ch);
curl_close($ch);
$json3 = json_decode($exchange_price, true);
$price = round($json3['rate'] / $rate,2);

if (empty ($price))
	{
		// Получаем курс валют с другой биржи
		$get_exchange_price = 'https://api.cryptonator.com/api/ticker/BTC-'.$currency;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $get_exchange_price);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		$exchange_price = curl_exec ($ch);
		curl_close($ch);
		$json3 = json_decode($exchange_price, true);
		$price = round($json3['ticker']['price'] / $rate / 1.04,2); // 1.04 дополнительно изменяем курс т.к. на на разных биржах всегда выше-ниже курс, стараемся брать тот, что ниже
	
	
			if (empty ($price))
				{
					$price="failed";
				}
	
	}
?>
