<?php
// Модуль ps_yandex_money для оплаты через платежную систему Яндекс.Деньги
// Используется с схема с передачей номера заказа в поле orderNumber
// Компонент ps_yandex_money.php, реализующий настройку подключения по протоколу Яндекс.Деньги 3.0(ЕПР)
// 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
define ('YM_VERSION', '1.3.0');
class yandex_money_language 
{
	var $PHPSHOP_ADMIN_CFG_YM_CURRENCY = "руб.";

	var $PHPSHOP_ADMIN_CFG_YM_SETTINGS = "Яндекс.Касса";
	var $PHPSHOP_ADMIN_CFG_YM_PAYMENTTYPES = "Методы оплаты";

	var $PHPSHOP_ADMIN_CFG_YM_LICENSE = "Работая с модулем, вы автоматически соглашаетесь с <a href='https://money.yandex.ru/doc.xml?id=527132' target='_blank'>условиями его использования</a>.";
	var $PHPSHOP_ADMIN_CFG_YM_TEXT_VERSION = "Версия модуля";
	var $PHPSHOP_ADMIN_CFG_YM_TEXT_CONNECT = "Для работы с модулем нужно подключить магазин к <a target=\"_blank\" href=\"https://money.yandex.ru/joinups\">Яндекс.Кассе</a>.";
	var $PHPSHOP_ADMIN_CFG_YM_TESTMODE = "Тестовый режим";
	var $PHPSHOP_ADMIN_CFG_YM_WORKMODE = "Рабочий режим";
	var $PHPSHOP_ADMIN_CFG_YM_EXTRA_CHECKURL = "Скопируйте эту ссылку в поля Check URL и Aviso URL в настройках <a target=\"_blank\" href=\"https://kassa.yandex.ru/my\">личного кабинета Яндекс.Кассы</a>";
	var $PHPSHOP_ADMIN_CFG_YM_SUCCESSURL = "Страницы с динамическими адресами";
	var $PHPSHOP_ADMIN_CFG_YM_EXTRA_SUCCESSURL = "Включите «Использовать страницы успеха и ошибки с динамическими адресами» в <a target=\"_blank\" href=\"https://kassa.yandex.ru/my\">личного кабинета Яндекс.Кассы</a>";
	var $PHPSHOP_ADMIN_CFG_YM_HEAD_LK = "Параметры из личного кабинета Яндекс.Кассы";
	var $PHPSHOP_ADMIN_CFG_YM_EXTRA_SETTING = "Shop ID, scid, ShopPassword можно посмотреть в <a target=\"_blank\" href=\"https://kassa.yandex.ru/my\">личном кабинете</a> после подключения Яндекс.Кассы.";
	var $PHPSHOP_ADMIN_CFG_YM_PAYMODE1 = "Выбор способа оплаты на стороне Яндекс.Кассы";
	var $PHPSHOP_ADMIN_CFG_YM_PAYMODE0 = "Выбор способа оплаты на стороне магазина";
	var $PHPSHOP_ADMIN_CFG_YM_EXTRA_PAYMODE = "Подробнее о сценариях оплаты";

	var $PHPSHOP_ADMIN_CFG_YM_DEBUG = "Демо-режим";
	var $PHPSHOP_ADMIN_CFG_YM_DEBUG_EXPLAIN = "Демо-режим. Режим тестирования подключения к платежной системе Яндекс.Деньги.";

	var $PHPSHOP_ADMIN_CFG_YM_SHOPID = "Идентификатор магазина";
	var $PHPSHOP_ADMIN_CFG_YM_SHOPID_EXPLAIN = "";

	var $PHPSHOP_ADMIN_CFG_YM_SCID = "Номер витрины магазина";
	var $PHPSHOP_ADMIN_CFG_YM_SCID_EXPLAIN = "";

	var $PHPSHOP_ADMIN_CFG_YM_SHOPPASSWORD = "Секретное слово";
	var $PHPSHOP_ADMIN_CFG_YM_SHOPPASSWORD_EXPLAIN = "";

	var $PHPSHOP_ADMIN_CFG_YM_PC = "Кошелек Яндекс.Деньги";
	var $PHPSHOP_ADMIN_CFG_YM_PC_EXPLAIN = "Оплата из кошелька в Яндекс.Деньгах.";

	var $PHPSHOP_ADMIN_CFG_YM_AC = "Банковская карта";
	var $PHPSHOP_ADMIN_CFG_YM_AC_EXPLAIN = "Оплата с произвольной банковской карты.";

	var $PHPSHOP_ADMIN_CFG_YM_GP = "Наличными через кассы и терминалы";
	var $PHPSHOP_ADMIN_CFG_YM_GP_EXPLAIN = "Оплата наличными через кассы и терминалы.";

	var $PHPSHOP_ADMIN_CFG_YM_MC = "Счет мобильного телефона";
	var $PHPSHOP_ADMIN_CFG_YM_MC_EXPLAIN = "Платеж со счета мобильного телефона.";

	var $PHPSHOP_ADMIN_CFG_YM_WM = "Кошелек WebMoney";
	var $PHPSHOP_ADMIN_CFG_YM_WM_EXPLAIN = "Оплата из кошелька в системе WebMoney.";
	
	var $PHPSHOP_ADMIN_CFG_YM_SB = "Сбербанк: оплата по SMS или Сбербанк Онлайн";
	var $PHPSHOP_ADMIN_CFG_YM_SB_EXPLAIN = "Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн.";

	var $PHPSHOP_ADMIN_CFG_YM_AB = "Альфа-Клик";
	var $PHPSHOP_ADMIN_CFG_YM_AB_EXPLAIN = "Оплата через Альфа-Клик.";
	
	var $PHPSHOP_ADMIN_CFG_YM_MA = "MasterPass";
	var $PHPSHOP_ADMIN_CFG_YM_MA_EXPLAIN = "Оплата через MasterPass.";
	
	var $PHPSHOP_ADMIN_CFG_YM_PB = "Интернет-банк Промсвязьбанка";
	var $PHPSHOP_ADMIN_CFG_YM_PB_EXPLAIN = "Оплата через интернет-банк Промсвязьбанка.";
	
	var $PHPSHOP_ADMIN_CFG_YM_QW = "QIWI Wallet";
	var $PHPSHOP_ADMIN_CFG_YM_QW_EXPLAIN = "Оплата через QIWI Wallet.";	
	
	var $PHPSHOP_ADMIN_CFG_YM_QP = "Доверительный платеж (Куппи.ру)";
	var $PHPSHOP_ADMIN_CFG_YM_QP_EXPLAIN = "Оплата через доверительный платеж (Куппи.ру)";
}
 
class ps_yandex_money 
{
	var $classname = "ps_yandex_money";
	var $payment_code = "YMP";

	/**
	* Conctructor, that merge our language varibles to VM_LANG
	*/
	function ps_yandex_money() 
	{
		global $VM_LANG;
		$status = (!empty($VM_LANG))?$VM_LANG->merge('yandex_money_language'):array();
	}

	/**
	* Отображение параметров конфигурации этого модуля оплаты
	* @returns boolean False when the Payment method has no configration
	*/
	function show_configuration() 
	{
		global $db, $VM_LANG;
		if(htmlspecialchars( $db->sf("payment_extrainfo")) == '' ) 
		{
			$db->record[$db->row]->payment_extrainfo = '<?php
// Класс для оплаты через сервис Яндекс.Касса
// Лицензионный договор.
// Любое использование Вами программы означает полное и безоговорочное принятие Вами условий лицензионного договора, размещенного по адресу https://money.yandex.ru/doc.xml?id=527132 (далее – «Лицензионный договор»). Если Вы не принимаете условия Лицензионного договора в полном объёме, Вы не имеете права использовать программу в каких-либо целях.

require_once(CLASSPATH."payment/ps_yandex_money.php");
$host = getenv("HTTP_HOST");
// сервер Яндекс.Денег для отправки данных платежной формы
$ym_action_host = ( intval(YM_DEBUG) ? "demomoney.yandex.ru" : "money.yandex.ru" );

// номер заказа
// number of order
$orderNumber = $db->f("order_id");

// сумма заказа
// sum of order
$out_sum = $db->f("order_total");

// валюта
// currency
$currency = $VM_LANG->PHPSHOP_ADMIN_CFG_YM_CURRENCY;

// плательщик
// customerNumber
$user =& JFactory::getUser();
$customerNumber = $user->username;
$paymode = intval(YM_PAYMODE);
// способы оплаты
// payment types
$paymentTypes = array();
if ( intval(YM_AC) )
{
	$paymentTypes["AC"] = "Оплатить с произвольной банковской карты";
}
if ( intval(YM_PC) )
{
	$paymentTypes["PC"] = "Оплатить из кошелька в Яндекс.Деньгах";
}
if ( intval(YM_GP) )
{
	$paymentTypes["GP"] = "Оплатить наличными через кассы и терминалы";
}
if ( intval(YM_MC) )
{
	$paymentTypes["MC"] = "Оплатить со счета мобильного телефона";
}
if ( intval(YM_WM) )
{
	$paymentTypes["WM"] = "Оплатить из кошелька в системе WebMoney";
}
if ( intval(YM_AB) )
{
	$paymentTypes["AB"] = "Оплатить через Альфа-Клик";
}
if ( intval(YM_SB) )
{
	$paymentTypes["SB"] = "Оплатить через Сбербанк: оплата по SMS или Сбербанк Онлайн";
}
if ( intval(YM_PB) )
{
	$paymentTypes["PB"] = "Оплатить через интернет-банк Промсвязьбанка";
}
if ( intval(YM_MA) )
{
	$paymentTypes["MA"] = "Оплатить через MasterPass.";
}
if ( intval(YM_QW) )
{
	$paymentTypes["QW"] = "Оплатить через QIWI Wallet.";
}
if ( intval(YM_QP) )
{
	$paymentTypes["QP"] = "Оплатить через доверительный платеж (Куппи.ру).";
}
?>

<style type="text/css">
div.payments_methods img {border: none; width: 32px; height: 32px; margin: 0 0 0 0;}
div.smart_pay img {border: none; width: 165px; height: 76px; margin: 0 0 0 0;}
div.payments_methods button {
	cursor:pointer;
	display:inline;
	height:32px;
	width:32px;
	line-height:32px;
	text-align:center;
	background-repeat:no-repeat;
	background-color: transparent;
	border: none;
	margin: 0 6px 0 6px;
}
div.smartpay button {
	cursor:pointer;
	background-color: transparent;
	background-repeat:no-repeat;
	height:76px;
	width:165px;
	border: none;
	margin: 0 6px 0 6px;
	background-image:url(\'<?php echo JURI::base(); ?>images/yamoney/smart.png\')
}
div.payments_methods #btnAC {background-image:url(\'<?php echo JURI::base(); ?>images/yamoney/ac.png\')}
div.payments_methods #btnPC {background-image:url(\'<?php echo JURI::base(); ?>images/yamoney/pc.png\')}
div.payments_methods #btnGP {background-image:url(\'<?php echo JURI::base(); ?>images/yamoney/gp.png\')}
div.payments_methods #btnMC {background-image:url(\'<?php echo JURI::base(); ?>images/yamoney/mc.png\')}
div.payments_methods #btnWM {background-image:url(\'<?php echo JURI::base(); ?>images/yamoney/wm.png\')}
div.payments_methods #btnAB {background-image:url(\'<?php echo JURI::base(); ?>images/yamoney/ab.png\')}
div.payments_methods #btnSB {background-image:url(\'<?php echo JURI::base(); ?>images/yamoney/sb.png\')}
div.payments_methods #btnPB {background-image:url(\'<?php echo JURI::base(); ?>images/yamoney/pb.png\')}
div.payments_methods #btnMA {background-image:url(\'<?php echo JURI::base(); ?>images/yamoney/ma.png\')}
div.payments_methods #btnQW {background-image:url(\'<?php echo JURI::base(); ?>images/yamoney/qw.png\')}
div.payments_methods #btnQP {background-image:url(\'<?php echo JURI::base(); ?>images/yamoney/qp.png\')}
div.payments_methods #btnSmartPay {background-image:url(\'<?php echo JURI::base(); ?>images/yamoney/smart.png\')}

h4.span.txt_h4 {font-weight: normal;}
</style>
<div style="width: 100%; text-align: left">
<h4>Номер заказа: <span class="txt_h4"><?php echo $orderNumber; ?></span></h4>
<h4>Ваш логин в ...: <span class="txt_h4"><?php echo $customerNumber; ?></span></h4>
<h4>Сумма к оплате: <span class="txt_h4"><?php echo number_format($out_sum, 2, ",", " ")." ".$currency; ?></span></h4>
</div>
<form method="POST" action="https://<?php echo $ym_action_host; ?>/eshop.xml">

<?php
$ym = new ps_yandex_money();
echo $ym->get_ym_params_block($host, number_format($out_sum, 2, ".", ""), $customerNumber, $orderNumber, array() );
?>
<?php if (!$paymode){ ?>
	<div class="payments_methods">
	<?php foreach( $paymentTypes as $ptKey => $ptName ) { ?>
	<button name="paymentType" value="<?php echo $ptKey; ?>" type="submit" id="btn<?php echo $ptKey; ?>" title="<?php echo $ptName; ?>"></button>
	<?php } ?>
	</div>
<?php }else{ ?>
<div class="smart_pay">
	<div class="smartpay">
		<button name="paymentType" value="" type="submit" id="btnSmartPay" title="Заплатить через Яндекс"></button>
	</div>
<?php } ?>
</form>
';
		}

		/** Загрузка файла конфигурации ***/
		if($this->has_configuration())
			include_once(CLASSPATH ."payment/".$this->classname.".cfg.php");
		else
			return false;
?>
		<h3><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_SETTINGS; ?></h3>
		<br><p><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_LICENSE; ?></p>
		<p><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_TEXT_VERSION; ?> <?php echo YM_VERSION; ?></p>
		<br>
		<p><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_TEXT_CONNECT; ?></p>
		<br>
		<input type="radio" name="YM_DEBUG" class="checkbox" value="1" <?php if( intval(YM_DEBUG) ) echo "checked=\"checked\" "; ?>/> <?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_TESTMODE; ?>
		<input type="radio" name="YM_DEBUG" class="checkbox" value="0" <?php if( !intval(YM_DEBUG) ) echo "checked=\"checked\" "; ?>/> <?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_WORKMODE; ?>
		<br><br>
		<table style="text-align:left">
			<tr>
				<td><strong>checkUrl/avisoUrl</strong></td>
				<td></td>
				<td><?php echo JURI::base().'components/com_virtuemart/yandex_money_notify.php'; ?></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_EXTRA_CHECKURL; ?></td>
			</tr>
			<tr>
				<td><strong>successUrl/failUrl</strong></td>
				<td></td>
				<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_SUCCESSURL; ?></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_EXTRA_SUCCESSURL; ?></td>
			</tr>
		</table>
		<br><h4><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_HEAD_LK; ?></h4>
		<table style="text-align:left">
		<tr>
			<td colspan=2></td>
		</tr>
		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_SHOPID; ?>:</strong></td>
			<td>
				<input type="text" name="YM_SHOPID" class="inputbox" value="<?php if(YM_SHOPID != '') echo intval(YM_SHOPID); ?>" />
			</td>
		
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_SHOPID_EXPLAIN; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_SCID; ?>:</strong></td>
			<td>
				<input type="text" name="YM_SCID" class="inputbox" value="<?php if(YM_SCID != '') echo intval(YM_SCID); ?>" />
			</td>
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_SCID_EXPLAIN; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_SHOPPASSWORD; ?>:</strong></td>
			<td>
				<input type="text" name="YM_SHOPPASSWORD" class="inputbox" value="<?php if(YM_SHOPPASSWORD != '') echo YM_SHOPPASSWORD; ?>" />
			</td>
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_SHOPPASSWORD_EXPLAIN; ?></td>
		</tr>
			<tr>
				<td></td>
				<td colspan="2">
					<?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_EXTRA_SETTING; ?>
				</td>
			</tr>
		</table>

		<br><h4>Настройка сценария оплаты</h4>
		<table style="text-align:left">
			<tr>
				<td><strong>Сценарий оплаты</strong></td>
				<td></td>
				<td>
					<input type="radio" name="YM_PAYMODE" class="radio" value="1" <?php if( intval(YM_PAYMODE) ) echo "checked=\"checked\" "; ?>/>
					<?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_PAYMODE1; ?><br>
					<input type="radio" name="YM_PAYMODE" class="radio" value="0" <?php if( !intval(YM_PAYMODE) ) echo "checked=\"checked\" "; ?>/>
					<?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_PAYMODE0; ?>
				</td>
			</tr>
			<tr>
				<td></td>
				<td colspan="2">
					<a target="_blank" href="https://tech.yandex.ru/money/doc/payment-solution/payment-form/payment-form-docpage/">
						<?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_EXTRA_PAYMODE; ?></a>
				</td>
			</tr>

		</table>

		<table style="text-align:left" class="selectPayOpt">
		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_PC; ?>:</strong></td>
			<td>
				<input type="checkbox" name="YM_PC" class="checkbox" value="1" <?php if( intval(YM_PC) ) echo "checked=\"checked\" "; ?>/>
			</td>
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_PC_EXPLAIN; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_AC; ?>:</strong></td>
			<td>
				<input type="checkbox" name="YM_AC" class="checkbox" value="1" <?php if( intval(YM_AC) ) echo "checked=\"checked\" "; ?>/>
			</td>
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_AC_EXPLAIN; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_GP; ?>:</strong></td>
			<td>
				<input type="checkbox" name="YM_GP" class="checkbox" value="1" <?php if( intval(YM_GP) ) echo "checked=\"checked\" "; ?>/>
			</td>
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_GP_EXPLAIN; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_MC; ?>:</strong></td>
			<td>
				<input type="checkbox" name="YM_MC" class="checkbox" value="1" <?php if( intval(YM_MC) ) echo "checked=\"checked\" "; ?>/>
			</td>
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_MC_EXPLAIN; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_WM; ?>:</strong></td>
			<td>
				<input type="checkbox" name="YM_WM" class="checkbox" value="1" <?php if( intval(YM_WM) ) echo "checked=\"checked\" "; ?>/>
			</td>
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_WM_EXPLAIN; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_AB; ?>:</strong></td>
			<td>
				<input type="checkbox" name="YM_AB" class="checkbox" value="1" <?php if( intval(YM_AB) ) echo "checked=\"checked\" "; ?>/>
			</td>
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_AB_EXPLAIN; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_SB; ?>:</strong></td>
			<td>
				<input type="checkbox" name="YM_SB" class="checkbox" value="1" <?php if( intval(YM_SB) ) echo "checked=\"checked\" "; ?>/>
			</td>
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_SB_EXPLAIN; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_PB; ?>:</strong></td>
			<td>
				<input type="checkbox" name="YM_PB" class="checkbox" value="1" <?php if( intval(YM_PB) ) echo "checked=\"checked\" "; ?>/>
			</td>
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_PB_EXPLAIN; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_MA; ?>:</strong></td>
			<td>
				<input type="checkbox" name="YM_MA" class="checkbox" value="1" <?php if( intval(YM_MA) ) echo "checked=\"checked\" "; ?>/>
			</td>
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_MA_EXPLAIN; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_QW; ?>:</strong></td>
			<td>
				<input type="checkbox" name="YM_QW" class="checkbox" value="1" <?php if( intval(YM_QW) ) echo "checked=\"checked\" "; ?>/>
			</td>
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_QW_EXPLAIN; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_QP; ?>:</strong></td>
			<td>
				<input type="checkbox" name="YM_QP" class="checkbox" value="1" <?php if( intval(YM_QP) ) echo "checked=\"checked\" "; ?>/>
			</td>
			<td><?php echo $VM_LANG->PHPSHOP_ADMIN_CFG_YM_QP_EXPLAIN; ?></td>
		</tr>
		<tr>
			<td colspan=2><hr /></td>
		</tr>
        </table>
        <table style="text-align:left">
            <tr>
                <td><strong>Отправлять в Яндекс.Кассу данные для чеков (54-ФЗ)</strong></td>
            </tr>
            <tr>
                <td>
                    <input type="radio" name="YM_SEND_CHECK" class="radio" value="1" <?php if(YM_SEND_CHECK) echo "checked=\"checked\" "; ?>/>
                    Включить<br>
                    <input type="radio" name="YM_SEND_CHECK" class="radio" value="0" <?php if(!YM_SEND_CHECK) echo "checked=\"checked\" "; ?>/>
                    Выключить
                </td>
                <td>Отправлять в Яндекс.Кассу данные для чеков (54-ФЗ) НДС</td>
            </tr>
            <? if (YM_SEND_CHECK) { ?><?
                $q = "SELECT `tax_rate_id`, `tax_rate`  FROM `#__{vm}_tax_rate` ORDER BY `tax_rate` DESC, `tax_rate_id` ASC" ;
                $db->query( $q ) ;

                $tax_rates = Array( ) ;
                while( $db->next_record() ) {
                    $tax_rates[$db->f( "tax_rate_id" )] = $db->f( "tax_rate" ) ;
                }

                ?>
            <tr>
                <td colspan="3">
                    <table style="text-align:left">
                        <tr>
                            <td><strong>Ставка в вашем магазине.</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Слева — ставка НДС в вашем магазине, справа — в Яндекс.Кассе. Пожалуйста, сопоставьте их.</strong></td>
                        </tr>
                        <? foreach ($tax_rates as $id_tax =>  $tax) {?>
                            <?
                            $rate = false;
                            if (defined('YM_TAXES_'.$id_tax)) {
                                $rate = constant('YM_TAXES_'.$id_tax);
                            } else {
                                $rate = 1;
                            }

                            ?>
                            <tr>
                                <td></td>
                                <td><strong><? echo ($tax*100); ?>% Передать в Яндекс.Кассу как</strong></td>
                                <td></td>
                                <td>
                                    <select name="YM_TAXES_<? echo $id_tax; ?>">
                                        <option <? if ($rate == 1) { ?> selected="selected" <? } ?> value="1">Без НДС</option>
                                        <option <? if ($rate == 2) { ?> selected="selected" <? } ?> value="2">0%</option>
                                        <option <? if ($rate == 3) { ?> selected="selected" <? } ?> value="3">10%</option>
                                        <option <? if ($rate == 4) { ?> selected="selected" <? } ?> value="4">18%</option>
                                        <option <? if ($rate == 5) { ?> selected="selected" <? } ?> value="5">Расчётная ставка 10/110</option>
                                        <option <? if ($rate == 6) { ?> selected="selected" <? } ?> value="6">Расчётная ставка 18/118</option>
                                    </select>
                                </td>
                            </tr>
                        <? } ?>
                    </table>
                </td>
            </tr>
            <? } ?>



		<?php
        $q = "SELECT order_status_name, order_status_code FROM #__{vm}_order_status ORDER BY list_order";
		$dbs = new ps_DB;
		$dbs->query($q);
		$order_status_code = Array();
		$order_status_name = Array();

		while ($dbs->next_record()) 
		{
			$order_status_code[] = $dbs->f("order_status_code");
			$order_status_name[] =  $dbs->f("order_status_name");
		}
		?>
	</table>
	<?php
	}

  /**
  * Returns the "has_configuration" status of the module
  * @param void
  * @returns boolean True when the configuration is, false when not
  */
	function has_configuration() 
	{
		if(file_exists( CLASSPATH."payment/".$this->classname.".cfg.php" )) 
		{
			include_once( CLASSPATH."payment/".$this->classname.".cfg.php" );
		}
		else 
		{
			if(!$this->write_configuration($d))
			{
				return false;
			}
		}
		return true;
	}

	/**
	* Returns the "is_writeable" status of the configuration file
	* @param void
	* @returns boolean True when the configuration file is writeable, false when not
	*/
	function configfile_writeable() 
	{
		return is_writeable( CLASSPATH."payment/".$this->classname.".cfg.php" );
	}

	/**
	* Returns the "is_readable" status of the configuration file
	* @param void
	* @returns boolean True when the configuration file is readable, false when not
	*/
	function configfile_readable() 
	{
		return is_readable( CLASSPATH."payment/".$this->classname.".cfg.php" );
	}

	/**
	* Writes the configuration file for this payment method
	* @param array An array of objects
	* @returns boolean True when writing was successful
	*/
	function write_configuration( &$d ) 
	{
		global $database, $mosConfig_live_site, $mosConfig_absolute_path, $mosConfig_lang, $mosConfig_locale;


		/** Check for empty values **/
		if (is_array($d)) 
		{
		    foreach ($d as $conf => $val) {
                if (strpos($conf,'YM_TAXES_') === 0) {
                    $my_config_array[$conf] = $val;
                }
            }

			if(!isset($d['YM_SHOPID']))
			{
				$my_config_array['YM_SHOPID'] = '0';
			} else {
				$my_config_array['YM_SHOPID'] = $d['YM_SHOPID'];
			}

			if(!isset($d['YM_SCID']))
			{
				$my_config_array['YM_SCID'] = '0';
			} else {
				$my_config_array['YM_SCID'] = $d['YM_SCID'];
			}

			if(!isset($d['YM_SHOPPASSWORD']))
			{
				$my_config_array['YM_SHOPPASSWORD'] = '';
			} else {
				$my_config_array['YM_SHOPPASSWORD'] = $d['YM_SHOPPASSWORD'];
			}

			if(!isset($d['YM_SEND_CHECK']))
			{
				$my_config_array['YM_SEND_CHECK'] = '';
			} else {
				$my_config_array['YM_SEND_CHECK'] = $d['YM_SEND_CHECK'];
			}

			if(!isset($d['YM_DEBUG']) || !$d['YM_DEBUG']) 
			{
				$my_config_array['YM_DEBUG'] = '0';
			} else {
				$my_config_array['YM_DEBUG'] = '1';
			}
			if(!isset($d['YM_PAYMODE']))
			{
				$my_config_array['YM_PAYMODE'] = '1';
			} else {
				$my_config_array['YM_PAYMODE'] = $d['YM_PAYMODE'];
			}

			$list=array('PC','AC','GP','MC','WM','AB','SB','MA','PB','QW', 'QP');
			foreach ($list as $item) $my_config_array['YM_'.$item] =(isset($d['YM_'.$item]) && $d['YM_'.$item])?'1':'0';
		}

		$config = "<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die('Direct Access to this location is not allowed.');\n\n";

		while (list($key, $value) = each($my_config_array)) 
		{
			if(substr($key, 0, 5) == 'text_') 
			{
				$config .= $value."\n";
			} else {
				$key = strtoupper($key);
				$config .= "define ('".$key."', '".$value."');\n";
			}
		}
		$config .= "?>";

		if ($fp = fopen( CLASSPATH."payment/".$this->classname.".cfg.php", "w")) 
		{
			fputs($fp, stripslashes($config));
			fclose($fp);
			new yamoney_statistics();
			return true;
		}
		else return false;
	}
	
	//Форма для оплаты заказа через систему Яндекс.Деньги
	//Выводится после оформления заказа. Через payment_extrainfo
	function get_ym_params_block($host, $out_sum, $customerNumber, $orderNumber, $hidden_param)
	{
		global $db;

        if(file_exists( CLASSPATH."payment/".$this->classname.".cfg.php" ))
        {
            include_once( CLASSPATH."payment/".$this->classname.".cfg.php" );
        }

        if (YM_SEND_CHECK) {
            $dbo = $db;
            $receipt = array(
                'customerContact' => '99',
                'items' => array(),
            );

            if ($shipping = $dbo->f('ship_method_id')) {
                $info = explode('|', $shipping);
            }

            $dbi = new ps_DB();
            $dbi->query("SELECT oi.*, p.product_tax_id
              FROM #__{vm}_order_item oi
              LEFT JOIN #__{vm}_product p ON (p.product_id = oi.product_id)
              WHERE
                oi.order_id=".$db->getEscaped($orderNumber));

            $osum = 0;
            foreach ($dbi->record as $item) {
                $osum += (float)$item->product_final_price * (float)$item->product_quantity;
            }

            if (isset($info) && isset($info[3])) {
                $osum += (float)$info[3];
            }

            $disc = 0;
            if ($db->f('coupon_discount')) {
                $disc = (float)$db->f('coupon_discount')/$osum;
            }

            while ($dbi->next_record()) {
                $tax_id = 1;
                if ($product_tax_id = $dbi->f('product_tax_id')) {
                    if ($product_tax_id) {
                        if (defined('YM_TAXES_'.$product_tax_id)) {
                            $tax_id = constant('YM_TAXES_'.$product_tax_id);
                        }
                    }
                }

                $receipt['items'][] = array(
                    'quantity' => $dbi->f('product_quantity'),
                    'text' => strip_tags(substr($dbi->f('order_item_name').' '.$dbi->f('product_attribute'), 0, 128)),
                    'tax' => $tax_id,
                    'price' => array(
                        'amount' => number_format($dbi->f('product_final_price') * (1 - $disc), 2, '.', ''),
                        'currency' => 'RUB'
                    ),
                );
            }

            if (isset($info) && isset($info[3])) {
                if (isset($info[3]) && $info[3] > 0) {
                    $tax_shipping = 1;
                    if (defined(strtoupper($info[0]).'_TAX_CLASS')) {
                        $tax_class = constant(strtoupper($info[0]).'_TAX_CLASS');
                        if (defined('YM_TAXES_'.$tax_class)) {
                            $tax_shipping = constant('YM_TAXES_'.$tax_class);
                        }
                    }

                    $receipt['items'][] = array(
                        'quantity' => 1,
                        'text' => substr($info[2], 0, 128),
                        'tax' => $tax_shipping,
                        'price' => array(
                            'amount' => number_format($info[3] * (1 - $disc), 2, '.', ''),
                            'currency' => 'RUB'
                        ),
                    );
                }
            }
        }

		$ym_shopID=YM_SHOPID; //Ваше "shopID" (идентификатор магазина) в системе Яндекс.Деньги
		$ym_SCID=YM_SCID; //Ваш "SCID" (идентификатор витрины) в системе Яндекс.Деньги
		$ym_mode=YM_PAYMODE; //

		// HTML-страница с формой
		$htmlBlock = '
            <input type="hidden"name="cms_name" value="joomla-virtuemart">
            <input type="hidden"name="scid" value="$ym_SCID">
            <input type="hidden" name="ShopID" value="$ym_shopID">
            <input type="hidden" name="Sum" value="$out_sum">
            <input type="hidden" name="CustomerNumber" value="$customerNumber">';

		if ( $orderNumber != "" )
		{
			$htmlBlock .= '<input type="hidden" name="orderNumber" value="'.$orderNumber.'">'."\n";
			$htmlBlock .= '<input type="hidden" name="shopSuccessURL" value="http://'.$host.'/ru/cart?page=account.order_details&order_id='.$orderNumber.'">';
			$htmlBlock .= '<input type="hidden" name="shopFailURL" value="http://'.$host.'/ru/cart?page=account.order_details&order_id='.$orderNumber.'">'."\n";
		}

        if (YM_SEND_CHECK) {
            $htmlBlock .= '<input type="hidden" name="ym_merchant_receipt" value=\''.json_encode($receipt).'\'>'."\n";
        }

		if ( $hidden_param && is_array($hidden_param) )
		{
			foreach($hidden_param as $k=>$v)
			{
				if ( is_scalar($k) && is_scalar($v) )
				{
					if ( strcasecmp($k, "order_id") !== 0 )
					{
						$htmlBlock .=  '<input type="hidden" name="'.$k.'" value="'.$v.'">'."\n";
					} else {
						$htmlBlock .= '<input type="hidden" name="shopSuccessURL" value="http://'.$host.'/ru/cart?page=account.order_details&order_id='.intval($v).'">';
						$htmlBlock .= '<input type="hidden" name="shopFailURL" value="http://'.$host.'/ru/cart?page=account.order_details&order_id='.intval($v).'">'."\n";
					}
				}
			}
		}

		return $htmlBlock;
	}


	//Функция округления для md5
	function to_float($sum) 
	{
		if (strpos($sum, ".")) 
		{
			$sum=round($sum,2);
		} else {
			$sum=$sum.".0";
		}
		return $sum;
	}

	/**************************************************************************
	** name: process_payment()
	** returns:
	***************************************************************************/
	function process_payment($order_number, $order_total, &$d) 
	{
		return true;
	}
}
class yamoney_statistics {
	public function __construct(){
		$this->send();
	}

	private function send()
	{
		$headers = array();
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
		$user = JFactory::getUser(); 
		$array = array(
			'url' => JURI::base(),
			'cms' => 'joomla',
			'version' => JVERSION,
			'ver_mod' => YM_VERSION,
			'yacms' => false,
			'email' => $user->email,
			'shopid' => YM_SHOPID || 0,
			'settings' => array(
				'kassa' => true
			)
		);
		$array_crypt = base64_encode(serialize($array));

		$url = 'https://statcms.yamoney.ru/v2/';
		$curlOpt = array(
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLINFO_HEADER_OUT => true,
			CURLOPT_POST => true,
		);

		$curlOpt[CURLOPT_HTTPHEADER] = $headers;
		$curlOpt[CURLOPT_POSTFIELDS] = http_build_query(array('data' => $array_crypt, 'lbl' => 1));

		$curl = curl_init($url);
		curl_setopt_array($curl, $curlOpt);
		$rbody = curl_exec($curl);
		$errno = curl_errno($curl);
		$error = curl_error($curl);
		$rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
	}
}