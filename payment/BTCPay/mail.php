<?php

/**
 * Simpla CMS 2.1.5
 * @copyright 	2016 mXtbZ
 * К этому скрипту обращается BTCPay в процессе оплаты
 * Отправляет уведомление если не возможно получить номер кошелька для оплаты
 */

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: Mobil1 <'.$servicemail.'>' . "\r\n";
$subject = "Ошибка при оплате с Bitcoin";
$body="Не удалось получить курс валют или кошелек (необходимо проверить API ключ) для оплаты для заказа: ".$order."<br><br>URL: ".$actual_link;
mail($servicemail, $subject, $body,$headers);

?>
