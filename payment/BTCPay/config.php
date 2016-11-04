<?php 

/**
 * Simpla CMS 2.1.5
 * @copyright 	2016 mXtbZ
 * К этому скрипту обращается BTCPay в процессе оплаты
 * Основные конфиги
 */
 
 
		/// КОНФИГ ///
		$api_key = $payment_settings['apikey']; // апи ключ для block.io
		$currency = $payment_settings['apicurrency']; // национальная валюта
		$rate = $payment_settings['rateadd']; // надбавка к цене
		$transactiontime = $payment_settings['transactiontime']; // Сколько ждем после подтв. пользователем оплаты
		$btcconfirmation = $payment_settings['btcconfirmation']; // кол-во подтверждений транзакции
		$paytime = $payment_settings['paytime']; // сколько ожидаем оплату
		$servicemail = $payment_settings['servicemail'];
		$order=$order->id; // номер заказа
		$order_price=$amount; // цена
		$payfile = $work_directory.'temp/'.$order.'.txt'; // путь к кеш файлу
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; // путь к странице оплаты
		/// КОНФИГ ///
		
		
?>
