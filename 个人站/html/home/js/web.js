/* @北冥有鱼 */

	/* 页面切换导航变色 */
	$(".nav-li").click(function(){
		a = $(this).index(); //获取点击的索引

		if(typeof(Storage)!=="undefined") /* 判断浏览器是否支持 web存储 */
		{
			if (sessionStorage.nav_li_key)
			{
				sessionStorage.nav_li_key = a;
			}
			else
			{
				sessionStorage.nav_li_key = 0;
			}
			//alert(sessionStorage.nav_li_key);
		}
		else
		{
			document.getElementById("result").innerHTML="抱歉，您的浏览器不支持 web 存储";
		}
	});

	/* 自动获取页面导航 */
	$(function(){
		if (sessionStorage.nav_li_key) 
		{ 	//有web存储值则执行这里
			$(".nav-li").removeClass("nav-active");
			$(".nav-li").eq(sessionStorage.nav_li_key).addClass("nav-active");
		}
		else
		{	//没有web存储值，默认选中首页
			$(".nav-li").removeClass("nav-active");
			$(".nav-li").eq(0).addClass("nav-active");

			sessionStorage.nav_li_key = 0; //定义web存储
		}
	});





	/* 脑袋切换 */
	$('.nav-i').click(function(){
        $(this).toggleClass('nav-shift'); /* 导航三横样式切换 */
        $('.nav-ul').toggleClass('nav-ul-active');  /* 脑袋样式切换 */
    });