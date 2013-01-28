jQuery(function(){
	
	if(!isLogin) showLoginDlg();

	$(window).resize(function(){
		$('.dlg').css('left', ($('body').width()-$('.dlg').width())/2);
	}).resize();

	$("#playBtn").click(function(){
		if(!isLogin){
			// return showLoginDlg();
		}

		$.blockUI({message:null});

		var t1 = playItem('#item1', rand(50, 100));
		var t2 = playItem('#item2', rand(50, 100));
		var t3 = playItem('#item3', rand(50, 100));

		//Delay some seconds, so it will be have fun.
		setTimeout(function(){
			$.get('process.php', params, function(data){
				// console.log(data);
				$.unblockUI();
				//stop play and show reward result.
				clearInterval(t1);
				clearInterval(t2);
				clearInterval(t3);
				stopAnimate();

				$('.dlg').hide();
				if(data.status==1){
					var type = data.params.type;
					win(type);
					var _thisCoupon = coupon[type];
					$("#couponValue").text(_thisCoupon[1]);
					$('#couponNo').text(data.params.code);
					$("#couponPrice").text(_thisCoupon[0]);
					$('.dlgWin').show();
				}else if(data.status==-6){
					showLoginDlg();
				}else if(data.status==-2){
					$('.dlgPlayed').show();
				}else if(data.status==-1){
					$('.dlgUnlucky').show();
				}
				
			}, 'json');
		}, 1500);
		return false;
	});
	
	$('.share2Sina').click(function(){
		var title = $(this).attr('title');
		if(!title) title = '新年好运气！来@魅力惠GlamourSales 玩老虎机中购物券！';
		share2weibo(title, null, 'http://media2.glamour-sales.com.cn/media/ad_banner/game2013/share.jpg');
	});

	$('.dlgCloseBtn').click(function(){
		$(this).closest('.dlg').fadeOut();
	});

});

var coupon = [
	[],
	[1500, 300, 'red_heart'], //1
	[1000, 150, 'black_heart'], //2
	[800, 100, 'red_square'], //3
	[600, 50, 'black_club'] //4
];

function showLoginDlg(){
	$('.dlg').hide();
	$('.dlgLogin').fadeIn();
}

function win(type){
	if(coupon[type]){
		// console.log(coupon[type]);
		var style = coupon[type][2];
		$('#item1').find('li.'+style).prependTo($('#item1>ul'));
		$('#item2').find('li.'+style).prependTo($('#item2>ul'));
		$('#item3').find('li.'+style).prependTo($('#item3>ul'));
	}
}

function fail(){
	$('#item1').find('li.black_heart').prependTo($('#item1>ul'));
	$('#item2').find('li.red_heart').prependTo($('#item2>ul'));
	$('#item3').find('li.black_club').prependTo($('#item3>ul'));
}

function playItem(id, speed){
	return setInterval('startAnimate("'+id+'",'+speed+')', 100);
}

function startAnimate(id, speed){
	$(id+'>ul').animate({
		marginTop: '-83px'
	}, speed, function(){
		$(this).css('marginTop', 0).find('li:first').appendTo(this);
	});
}

function stopAnimate(){
	$('#item1>ul').stop(true, true);
	$('#item2>ul').stop(true, true);
	$('#item3>ul').stop(true, true);
}

function share2weibo(title, link, pic){
	if(!link){
		link = location.href;
	}
	var url = 'http://service.weibo.com/share/share.php?url='+link+'&appkey=&title='+title+'&pic='+pic;
	window.open(url,'Game', 'height=500, width=600');
}

function rand(n, m){
	return Math.random()*(n-m)+m;
}
