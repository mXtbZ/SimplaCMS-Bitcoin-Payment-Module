<?php
/**
 * Simpla CMS 2.1.5
 * @copyright 	2016 mXtbZ
 * К этому скрипту обращается BTCPay в процессе оплаты
 * Страница с таймером оплаты
 */
 ?>

<script type='text/javascript'>//<![CDATA[
window.onload=function(){
var downloadButton = document.getElementById("pay");
var counter = <?php echo $time_left; ?>;
var newElement = document.createElement("p");
newElement.innerHTML = "";
var id;

downloadButton.parentNode.replaceChild(newElement, downloadButton);

id = setInterval(function() {
    counter--;
    if(counter < 0) {
        newElement.parentNode.replaceChild(downloadButton, newElement);
        clearInterval(id);
    } else {
        newElement.innerHTML = "<table style='width: 100%'><tr><td style='width: 250px'><center><img src='http://chart.apis.google.com/chart?cht=qr&amp;chs=200x200&amp;chl=bitcoin:<?php echo $address; ?>?amount=<?php echo $topay; ?>&chld=H|0'></center></td><td><h1><storng><?php echo $address; ?></h1><h2>К оплате: <?php echo $topay.' BTC ('.$order_price.' '.$currency.')</h2></strong>'; ?><br> Оплата возможна в течение <b>" + counter.toString() + "</b> секунд.<br>По истечению этого времени Вам необходимо обновить страницу.<br><br><a href='<?php echo $actual_link.'?notify=1';?>'><button class='button'>Я оплатил, проверить поступление средств  &#8594;</button></a></td></tr></table>";
    }
}, 1000);

}//]]> 
</script>


<?php
echo "<span id='pay'><h1>Время для произведения оплаты истекло. Вам необходимо обновить страницу. </h1><br><a href='".$actual_link."' class='button'>Обновить платежную страницу</a></span>";
?>
