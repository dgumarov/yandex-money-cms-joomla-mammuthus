<?php
// Модуль ps_yandex_money для оплаты через платежную систему Яндекс.Деньги
// Используется с схема с передачей номера заказа в поле orderNumber
// Компонент yandex_money_notify.php, реализующий ответы по протоколу Яндекс.Деньги 3.0(ЕПР)
// 
// & JFactory::getApplication( 'site' ) необходимая для инициализации базы дает warning
// потому отключим warning'и, для корректного XML ответа
error_reporting(0);
$messages = Array();
function debug_msg($msg) { global $messages;}

if(isset($_POST['action'])){	
	global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang, $database,
	$mosConfig_mailfrom, $mosConfig_fromname;

	/*** access Joomla's configuration file ***/
	$my_path = dirname(__FILE__);

	if( file_exists($my_path."/../../../configuration.php")) 
	{
		$absolute_path = dirname( $my_path."/../../../configuration.php" );
		require_once($my_path."/../../../configuration.php");
	} elseif( file_exists($my_path."/../../configuration.php")) {
		$absolute_path = dirname( $my_path."/../../configuration.php" );
		require_once($my_path."/../../configuration.php");
	} elseif( file_exists($my_path."/configuration.php")) {
		$absolute_path = dirname( $my_path."/configuration.php" );
		require_once( $my_path."/configuration.php" );
	} else {
		die( "Joomla Configuration File not found!" );
	}

	// способ инициализации 
	$absolute_path = realpath( $absolute_path );
	if( class_exists( 'jconfig' ) ) 
	{
		define( '_JEXEC', 1 );
		define( 'JPATH_BASE', $absolute_path );
		define( 'DS', DIRECTORY_SEPARATOR );

		// Load the framework
		require_once ( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
		require_once ( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );

		// create the mainframe object
		$mainframe = & JFactory::getApplication( 'site' );

		// Initialize the framework
		$mainframe->initialise();
		
		// load system plugin group
		JPluginHelper::importPlugin( 'system' );

		// trigger the onBeforeStart events
		$mainframe->triggerEvent( 'onBeforeStart' );
		$lang =& JFactory::getLanguage();
		$mosConfig_lang = $GLOBALS['mosConfig_lang']          = strtolower( $lang->getBackwardLang() );
		// Adjust the live site path

		$mosConfig_live_site = str_replace('/administrator/components/com_virtuemart', '', JURI::base());
		$mosConfig_absolute_path = JPATH_BASE;
	} else {
		define('_VALID_MOS', '1');
		require_once($mosConfig_absolute_path. '/includes/joomla.php');
		require_once($mosConfig_absolute_path. '/includes/database.php');

		$database = new database( $host, $user, $password, $db, $dbprefix );
		$mainframe = new mosMainFrame($database, 'com_virtuemart', $mosConfig_absolute_path );
	}

	$my_path = dirname($_SERVER['SCRIPT_FILENAME']);
	$mambo_path = str_replace("administrator/components/com_virtuemart", "", $my_path);

	$mosConfig_absolute_path = $mambo_path;
	
	/*** Начало части VirtueMart ***/
	require_once($mosConfig_absolute_path.'/administrator/components/com_virtuemart/virtuemart.cfg.php');
	require_once( CLASSPATH. 'ps_main.php');
	require_once( CLASSPATH. 'language.class.php' );
	require_once( $mosConfig_absolute_path . '/includes/phpmailer/class.phpmailer.php');
	
	
	$mail = new PHPMailer();
	$mail->PluginDir = $mosConfig_absolute_path . '/includes/phpmailer/';
	$mail->SetLanguage("en", $mosConfig_absolute_path . '/includes/phpmailer/language/');

	/* Загрузка файла класса базы данных VirtueMart */
	require_once( CLASSPATH. 'ps_database.php' );

	/*** END части VirtueMart ***/

	/* Загрузка файла конфигурации Яндекс.Деньги */
	require_once( CLASSPATH. 'payment/ps_yandex_money.cfg.php' );

	$kassa = new YandexMoneyObj(YM_SHOPID, YM_SHOPPASSWORD);
	$order_id=(is_numeric($_POST['orderNumber']))?$_POST['orderNumber']:0;

	$qv = "";
	$d = array();
	
	if($order_id){ 
		//Проверка заказа и переводимой за него суммы
		$qv = "SELECT `order_id`, `order_total`, `order_status` 
				 FROM #__{vm}_orders 
				 WHERE `order_id`='".$order_id."' AND `order_total`>='".floatval($_POST['orderSumAmount'])."'";
		//Запрос в к базе данных о заказе
        	$dbbt = new ps_DB;
        	$dbbt->query($qv);
        	$dbbt->next_record();
		if ( $dbbt->num_rows() == 1 ){
			if ($kassa->checkSign($_POST)){
				$d['order_id'] = $order_id;
				$d['current_order_status'] = $dbbt->f('order_status'); 
				$d['order_status'] = ($_POST['action']=='paymentAviso')?'C':'P';
				$d['notify_customer'] = ($_POST['action']=='paymentAviso')?'Y':'N'; 
				$message='Order is '.$order_id.' (status:'.$dbbt->f('order_status').')';
				if ($d['current_order_status']!=$d['order_status']){
					// Обновление состояния заказа
					require_once ( CLASSPATH . 'ps_order.php' );
					//$ps_order= new ps_order;
					$ps_order->order_status_update($d);
					$message='Status changed on '.$d['order_status'];
				}
				$kassa->sendCode($_POST, 0, $message);
			} else {
				$kassa->sendCode($_POST, 1, "Let's check shopPassword or shopid");
			}
		} else {
			$kassa->sendCode($_POST, 100, "Order doesn't exist or its amount is wrong."); 
		}
	}else{
		$kassa->sendCode($_POST, 200, "OrderNumber isn't a number.");
	}
}

Class YandexMoneyObj {
	public $shopid;
	public $password;
	
	public function __construct($sid, $psw){
		$this->shopid = $sid;
		$this->password = $psw;
	}

	public function checkSign($callbackParams){
			$string = $callbackParams['action'].';'.$callbackParams['orderSumAmount'].';'.$callbackParams['orderSumCurrencyPaycash'].';'.$callbackParams['orderSumBankPaycash'].';'.$this->shopid.';'.$callbackParams['invoiceId'].';'.$callbackParams['customerNumber'].';'.$this->password;
			$md5 = strtoupper(md5($string));
			return (strtoupper($callbackParams['md5'])==$md5);
	}

	public function sendCode($callbackParams, $code, $message=''){
		header("Content-type: text/xml; charset=utf-8");
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
			<'.$callbackParams['action'].'Response performedDatetime="'.date("c").'" code="'.$code.'" invoiceId="'.$callbackParams['invoiceId'].'" shopId="'.$this->shopid.'" techmessage="'.$message.'"/>';
		echo $xml;
	}
}