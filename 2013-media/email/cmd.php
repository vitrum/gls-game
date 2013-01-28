<?php
/**
 * Command for send email with coupons
 * The script will automaticlly send email to users who has win in the game.
 * NOTICE: please run this script command as deamon;
 * @author Jack Wang<hi@phpecho.net>
 * @usage: php cmd.php
 */

date_default_timezone_set('Asia/Shanghai');
require_once '/srv/www/games/config.php';
require_once '/srv/www/games/classes/class.phpmailer.php';

$mail = new PHPMailer();
$mail->IsSMTP(); // telling the class to use SMTP
$mail->SMTPAuth      = FALSE;                  // enable SMTP authentication
$mail->SMTPKeepAlive = true;                  // SMTP connection will not close after each email sent
$mail->Host          = "localhost";  // sets the SMTP server
$mail->Port          = 25;                    // set the SMTP port for the GMAIL server
$mail->Username      = "";        // SMTP account username
$mail->Password      = "";           // SMTP account password
$mail->SetFrom('service@glamour-sales.com.cn', '魅力惠拜年');//service@glamour-sales.com.cn
$mail->Subject = '您的运气真好！恭喜您赢得魅力惠新年购物券，赶快来网站上看看有什么心仪的东东吧~';
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
$mail->CharSet = 'utf-8';

/*
$coupon_value = array(
	1 => 50,
	2 => 100,
	3 => 150,
	4 => 300
);
$coupon_cost = array(
	1 => 600,
	2 => 800,
	3 => 1000,
	4 => 1500
);
*/

$coupon_value = array(
	4 => 50,
	3 => 100,
	2 => 150,
	1 => 300
);
$coupon_cost = array(
	4 => 600,
	3 => 800,
	2 => 1000,
	1 => 1500
);


$body = file_get_contents('email_template.html');

$dsn = sprintf('mysql:dbname=%s;host=%s', DB_NAME, DB_HOST);
$db = new PDO($dsn, DB_USER, DB_PASSWORD);

$limit = 100;

while(1){
	$sql = "SELECT * FROM record WHERE code IS NOT NULL 
		AND email_sent=0 
		AND customer_email!='' 
		ORDER BY id ASC LIMIT 0, $limit";
	$stmt = $db->query($sql);
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$hard_code = array(
			'{coupon_value}',
			'{coupon_code}',
			'{coupon_cost}',
		);
		$real_code = array(
			$coupon_value[$row['type']],
			$row['code'],
			$coupon_cost[$row['type']],
		);
		$_body = str_replace($hard_code, $real_code, $body);
		$mail->ClearAddresses();		
		$mail->MsgHTML($_body);
		$mail->AddAddress($row["customer_email"], $row['customer_name']);
		
		if(!$mail->Send()){
			echo "Mailer Error ".$row["customer_email"]." :".$mail->ErrorInfo."\n";
		}
		else{
	    	$ret = $db->exec('UPDATE record set email_sent=1 WHERE id='.$row['id']);
	    	echo "Message sent to:".$row['customer_email']." \n";
		}
	}

	sleep(5);
}

