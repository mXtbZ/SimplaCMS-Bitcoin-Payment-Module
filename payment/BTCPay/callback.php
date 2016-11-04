<?php

/**
 * Simpla CMS 2.1.5
 * @copyright 	2016 mXtbZ
 * К этому скрипту обращается BTCPay в процессе оплаты
 * Callback файл для установки, что заказ оплачен
 */

include('security.php');
// Работаем в корневой директории
chdir ('../../');
require_once('api/Simpla.php');
$simpla = new Simpla();

$status = $_GET['btcpaystatus'];
$signature = $_GET['signature'];
$order_id = $_GET['order'];
$paid = $_GET['paid'];

if($signature !== $serurity_signature)
	die('bad signature status');

if($status !== 'success')
	exit();

////////////////////////////////////////////////
// Выберем заказ из базы
////////////////////////////////////////////////
$order = $simpla->orders->get_order(intval($order_id));
if(empty($order))
	die('Оплачиваемый заказ не найден');
 
////////////////////////////////////////////////
// Выбираем из базы соответствующий метод оплаты
////////////////////////////////////////////////
$method = $simpla->payment->get_payment_method(intval($order->payment_method_id));
if(empty($method))
	die("Неизвестный метод оплаты");
	
$settings = unserialize($method->settings);
$payment_currency = $simpla->money->get_currency(intval($method->currency_id));

// Нельзя оплатить уже оплаченный заказ  
if($order->paid)
	die('Этот заказ уже оплачен');
	       
// Установим статус оплачен
$simpla->orders->update_order(intval($order->id), array('paid'=>1));

// Отправим уведомление на email
$simpla->notify->email_order_user(intval($order->id));
$simpla->notify->email_order_admin(intval($order->id));

// Спишем товары  
$simpla->orders->close(intval($order->id));

// Перенаправим пользователя на страницу заказа
//header('Location: '.$simpla->request->root_url.'/order/'.$order->url);

exit();
