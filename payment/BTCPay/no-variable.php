<?php 

/**
 * Simpla CMS 2.1.5
 * @copyright 	2016 mXtbZ
 * К этому скрипту обращается BTCPay в процессе оплаты
 * Часть основного скрипта (BTCPay.php) был вынесен в отдельный файл. Отрабатывает когда нет параметра ?notify. Также кеширует данные для оплаты в папку temp
 */
 
 
/// ЕСЛИ КЕШ ФАЙЛ СУЩЕСТВУЕТ ///	
	if (file_exists($payfile))
	{
	$string = file_get_contents($payfile);
	$json_a = json_decode($string, true);
	$time_from_file = $json_a["date"];
	$time_now = time();
	$time_diff=$time_now - $time_from_file;
	$time_left=$time_from_file + $paytime - $time_now;
	
	/// ЕСЛИ ВРЕМЯ КЕША ПРОШЛО - УДАЛЯЕМ ВРЕМЕННЫЙ ФАЙЛ //
	if ($time_left<0)
			{
			unlink($payfile);
			}
	}		
	
	/// ПРОВЕРЯЕМ ЛИ ФАЙЛ КЕША ВСЕ ЕЩЕ СУЩЕСТВУЕТ ///
	if (file_exists($payfile)) 
	{
	// если существует выводим данные из него
    $string = file_get_contents($payfile);
	$json_a = json_decode($string, true);
	$topay = $json_a["amount"];
	$address = $json_a["address"];
	$price = $json_a["price"];
	$currency = $json_a["currency"];
	$time_from_file = $json_a["date"];
	$time_now = time();
	$time_diff=$time_now - $time_from_file;
	$time_left=$time_from_file + $paytime - $time_now;
			//echo "кеш версия";
			include($work_directory."/page.php");
	}
	else 
	{
	// ФАЙЛ КЕША НЕ СУЩЕСТВУЕТ ДОСТАЕМ ВСЮ ИНФОРМАЦИЮ

		// Получаем курс валют
		include($work_directory."/exchange_rate.php");
		if ($price == "failed")
		{
			echo "<h1>К сожалению сейчас оплата по Bitcoin не возможна.<br>Попробуйте через 5-10 минут обновить страницу</h1>";
			include($work_directory."/mail.php");
		}
	else {
		// Получаем номер кошелька для оплаты
		include($work_directory."/block_io.php");
	
	if ($address != "failed")
	{
		// ЕСЛИ ВСЕ ОК
		$topay=round($order_price / $price,7);
		$time_left=$paytime;
		// Инклудим шаблон оплаты
		//echo "ОРИГ ВЕРСИЯ";
		include($work_directory."/page.php");
		$payfile = fopen($payfile, "w") or die("Unable to open file!");
		$txt = '{"amount" : "'.$topay.'", "address" : "'.$address.'", "date" : "'.time().'","price" : "'.$price.'", "currency" : "'.$currency.'", "url" : "'.$actual_link.'", "order" : "'.$order.'", "confirmations" : "'.$btcconfirmation.'", "transactiontime" : "'.$transactiontime.'", "paytime" : "'.$paytime.'"}';
		fwrite($payfile, $txt);
		fclose($payfile);
	}
	else
		// ЕСЛИ ОШИБКА КОШЕЛЬКА
	{
	echo "<h1>К сожалению сейчас оплата по Bitcoin не возможна.<br>Попробуйте через 5-10 минут обновить страницу</h1>";
	include($work_directory."/mail.php");
	}
	}
}
?>
