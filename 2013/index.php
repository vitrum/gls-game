<?php
require_once 'inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coupon Game</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/game.js"></script>
<script type="text/javascript" src="js/jquery.blockUI.js"></script>
<script type="text/javascript">
var params = {
	'customer_id':'<?php echo $customer_id;?>',
	'name':'<?php echo $customer_name;?>',
	'securecode':'<?php echo $securecode;?>',
	'email':'<?php echo $customer_email;?>'
};
var isLogin = <?php echo $login?'true':'false';?>;
</script>
</head>
<body>
<div id="main">
	<a href="http://www.glamour-sales.com.cn" id="headLink"><img src="images/logo.png" /></a>
	<div id="player">		
		<a href="javascript:;" id="playBtn"><img src="images/play.png" /></a>
		<div id="playerBox">
			<div id="item1">
				<ul>
					<li class="red_heart"></li>
					<li class="black_heart"></li>
					<li class="black_club"></li>
					<li class="red_square"></li>
				</ul>
			</div>
			<div id="item2">
				<ul>
					<li class="black_heart"></li>
					<li class="red_square"></li>
					<li class="black_club"></li>
					<li class="red_heart"></li>					
				</ul>
			</div>
			<div id="item3">
				<ul>					
					<li class="black_club"></li>
					<li class="red_heart"></li>
					<li class="red_square"></li>
					<li class="black_heart"></li>					
				</ul>
			</div>
			<div class="clr"></div>
		</div>
	</div>
	<div id="intro">
		<a href="javascript:;" class="share2Sina"><img src="images/icon_sina.png" /></a>
	</div>

	<div class="dlgLogin dlg" style="display:none;">
		<a href="javascript:;" class="dlgCloseBtn"><img src="images/dlg_close_btn.png" /></a>
		<div class="dlgContent">
			<h1>亲爱的会员，</h1>
			<p>需要先登录才能玩游戏哦～</p>
			<div class="buttons">
				<a class="loginBtn" href="http://www.glamour-sales.com.cn/customer/account/login/"><img src="images/login_btn.png" /></a>
				<a class="regBtn" href="http://www.glamour-sales.com.cn/customer/account/create/"><img src="images/reg_btn.png" /></a>
			</div>
		</div>		
	</div>

	<div class="dlgWin dlg" style="display:none;">
		<a href="javascript:;" class="dlgCloseBtn"><img src="images/dlg_close_btn.png" /></a>
		<div class="dlgContent">
			<div class="title">
				<h1>运气很好哦！</h1>
				<p>恭喜获得<span id="couponValue">50</span>元</p>
			</div>
			<div class="info">
				<p>购物券号：<span id="couponNo">00000102332</span></p>
				<p>使用规则：单次购物满<span id="couponPrice">500</span>即可抵用</p>
				<p>使用时间：截止至2013年2月28日</p>
			</div>
			<div class="notice">
				<p>请务必记录您的购物券号哦~</p>
				<p>遗失优惠券号将不予补发哦~</p>
			</div>
			<div class="buttons">
				<a class="loginBtn" href="http://www.glamour-sales.com.cn/" target="_blank" ><img src="images/use_btn.png" /></a>
				分享&nbsp;<a href="javascript:;" class="share2Sina" title="我玩老虎机中了@魅力惠GlamourSales 的新年购物券！你呢？比我厉害吗？游戏链接"><img src="images/icon_sina.png" /></a>
			</div>
		</div>		
	</div>

	<div class="dlgPlayed dlg" style="display:none;">
		<a href="javascript:;" class="dlgCloseBtn"><img src="images/dlg_close_btn.png" /></a>
		<div class="dlgContent">
			<p>请别贪心~ 每天只能参与一次哦~<br />明天再来吧！</p>
			<div class="buttons">
				<a class="loginBtn" href="http://www.glamour-sales.com.cn/" target="_blank" ><img src="images/buy_btn.png" /></a>
			</div>
		</div>		
	</div>

	<div class="dlgUnlucky dlg" style="display:none;">
		<a href="javascript:;" class="dlgCloseBtn"><img src="images/dlg_close_btn.png" /></a>
		<div class="dlgContent">
			<p>谢谢参与，明天来试试运气吧！</p>
			<div class="buttons">
				<a class="loginBtn" href="http://www.glamour-sales.com.cn/" target="_blank" ><img src="images/buy_btn.png" /></a>
			</div>
		</div>		
	</div>

</div>

</body>
</html>
