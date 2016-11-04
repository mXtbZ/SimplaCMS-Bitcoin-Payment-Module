<?php

/**
 * Simpla CMS 2.1.5
 * @copyright 	2016 mXtbZ
 * К этому скрипту обращается BTCPay в процессе оплаты
 * Крон для проверки поступления оплаты. Рекомендую его поставить на каждые 5-10 минут. Также указан параметр SLEEP так как у chain.so - max 5 запросов в секунду, а они обычно отрабатываются быстро.
 */
 
 
	$work_directory = "payment/BTCPay"; // Папка с платежным модулем
	include($work_directory.'/security.php'); // Сигнатура для безопасности
	$files = glob($work_directory.'/temp/*.check');
		foreach ($files as $filename) 
		{
				$string = file_get_contents($filename);
				$json_a = json_decode($string, true);
				$topay = $json_a["amount"];
				$address = $json_a["address"];
				$price = $json_a["price"];
				$currency = $json_a["currency"];
				$time_from_file = $json_a["date"];
				$order = $json_a["order"];
				$confirmations = $json_a["confirmations"];
				$transactiontime = $json_a["transactiontime"];
				$paytime = $json_a["paytime"];
				$time_now = time();
				$time_diff=$time_now - $time_from_file;
				$time_left=$time_from_file + $transactiontime + $paytime - $time_now; // Проверяем в течение часа (3600) + время которые было отведено на оплату (1200 - 20 минут)
					if ($time_left<0)
					{// если время проверки вышло, удаляем файл
					unlink($filename);
					}
					else
					{
					// если время еще не вышло, делаем запрос на проверку баланса
					$get_available_balance = 'https://chain.so/api/v2/get_address_balance/BTC/'.$address.'/'.$confirmations; // Ожидаем подтверждения транзакции
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $get_available_balance);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
					$result = curl_exec ($ch);
					curl_close($ch);
					$json = json_decode($result, true); // Разбираем массив
					//получаем баланс
					$balance = $json['data']['confirmed_balance'];
						if ($balance >= $topay)
						{
						$payment_notify = 'http://'.$_SERVER['HTTP_HOST'].'/'.$work_directory.'/callback.php?btcpaystatus=success&order='.$order.'&paid='.$topay.'&signature='.$serurity_signature;
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $payment_notify);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
						$result = curl_exec ($ch);
						curl_close($ch);
						unlink($filename);
						}
						sleep(3);
					}
		}		
?>
