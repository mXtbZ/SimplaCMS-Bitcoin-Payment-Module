<?php

/**
 * Simpla CMS 2.1.5
 * @copyright 	2016 mXtbZ
 * К этому скрипту обращается BTCPay в процессе оплаты
 * Основной файл для работы с оплатой
 */

require_once('api/Simpla.php');

class BTCPay extends Simpla
{

	public function checkout_form($order_id)
	{
		$order = $this->orders->get_order((int)$order_id);
		$payment_method = $this->payment->get_payment_method($order->payment_method_id);
		$payment_settings = $this->payment->get_payment_settings($payment_method->id);
		$amount = $this->money->convert($order->total_price, $payment_method->currency_id, false);
		
		$work_directory = $payment_settings['workdir']; // рабочая директория
		include($work_directory."/config.php");
		
	if ( isset( $_GET['notify'] ) && !empty( $_GET['notify'] ) )
	{
		if 	($_GET['notify'] == 1)
			{
				if (!file_exists($work_directory.'/temp/'.$order.'.check'))
				{
					header('Location: '.strstr($actual_link, '?', true));
				}
				
			rename($payfile, $work_directory.'/temp/'.$order.'.check');
			if (file_exists($work_directory.'/temp/'.$order.'.check'))
			{
				$string = file_get_contents($work_directory.'/temp/'.$order.'.check');
				$json_a = json_decode($string, true);
				$topay = $json_a["amount"];
				$address = $json_a["address"];
				$price = $json_a["price"];
				$currency = $json_a["currency"];
			echo "<h1>Начат процес проверки поступления средств.</h1>";	
			
echo "<table style='width: 100%'>";
echo "<tr>";
echo "<td style='width: 250px'><center><img src='http://chart.apis.google.com/chart?cht=qr&chs=200x200&chl=bitcoin:".$address."?amount=".$topay."&chld=H|0'&chld=H|0'></center></td>";
echo "<td><h1><strong><a href='https://blockchain.info/address/".$address."' target='_blank'>".$address."</a></h1><h1>Поиск транзакции на сумму: ".$topay." BTC</h1></strong><br><h2>Ожидание <b style='color:red'>".$btcconfirmation."</b> подтверждений транзакции.</h2><br>Вы будете уведомлены по почте как только ваш заказ получит статус <b>ОПЛАЧЕН</b>.<br><br>Эту страницу можно теперь закрыть.</td>";
echo "</tr>";
echo "</table>";

			}
			else {echo "<h1>Ошибка: в течение часа оплата не была найдена.</h1><h2>Выберите другой способ оплаты.</h2>";}
			}
			else
			{
				if (file_exists($work_directory.'/temp/'.$order.'.check'))
					{
						$string = file_get_contents($work_directory.'/temp/'.$order.'.check');
						$json_a = json_decode($string, true);
						$redirect = $json_a["url"];
					header('Location: '.$redirect.'?notify=1');
					}
					else 
					{
					include($work_directory."/no-variable.php");
					}
			}
	}	
	else
	{
	
					if (file_exists($work_directory.'/temp/'.$order.'.check'))
					{
						$string = file_get_contents($work_directory.'/temp/'.$order.'.check');
						$json_a = json_decode($string, true);
						$redirect = $json_a["url"];
					header('Location: '.$redirect.'?notify=1');
					}
					else 
					{
					include($work_directory."/no-variable.php");
					}
	}
	

}		
}
