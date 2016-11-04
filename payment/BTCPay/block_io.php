<?php

/**
 * Simpla CMS 2.1.5
 * @copyright 	2016 mXtbZ
 * К этому скрипту обращается BTCPay в процессе оплаты
 * Получает номер кошелька для оплаты. При создании кошелька id кошелька (label) в платежной системе block.io равен вашему номеру заказа.
 */
 
 
// получаем адрес для оплаты
$get_new_address = 'https://block.io/api/v2/get_new_address/?api_key='.$api_key.'&label='.$order;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $get_new_address);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
$result = curl_exec ($ch);
curl_close($ch);

// Разбираем массив
$json = json_decode($result, true);
$status = $json['status'];


	if ($status == "success")
	{
		//если адрес еще не создавался - создаем его
		$address = $json['data']['address'];	
	}
	else
	{
		// Ошибка, значит адрес уже создавался ранее.
		// Делаем cURL запрос на сервер чтобы получить ранее созданный адресс
		$get_existing_address = 'https://block.io/api/v2/get_address_by_label/?api_key='.$api_key.'&label='.$order;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $get_existing_address);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		$result2 = curl_exec ($ch);
		curl_close($ch);
		$json2 = json_decode($result2, true);
		$status2 = $json2['status'];
			if ($status2 =="fail")
			{echo "Ошибка системы";
			$address="failed";
			}
			else 
			{
				if (empty ($json2['data']['address']))
					{$address="failed";}
				else 
					{$address = $json2['data']['address'];}	
			}		
	}
?>
