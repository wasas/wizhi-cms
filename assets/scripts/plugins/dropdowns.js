(function($){
	$.fn.dropdowns = function (option) {
		
		// 设置移动端的窗口宽度
		var defaults = {
			toggleWidth: 768
		};
		
		// 合并默认选项和用户设置选项
		var options = $.extend(defaults, option);
		
		// 浏览器窗口宽度
		var ww = document.body.clientWidth;
		
		// 添加parend css类到导航菜单上
		var addParents = function() {
			$(".nav li a").each(function() {
				if ($(this).next().length > 0) {
					$(this).addClass("parent");
				}
			});
		};
		

		// 定义菜单宽度调整函数
		var adjustMenu = function() {

			// 窗口宽度小于设置的切换宽度，代表在移动端
			if (ww < options.toggleWidth) {

				// 显示移动端菜单切换按钮
				$(".toggleMenu").css("display", "inline-block");

				// 切换移动端和桌面端导航
				$("#site-navigation").removeClass("pure-menu-horizontal").addClass("pure-menu-vertical");

				// 激活移动端导航菜单
				if (!$(".toggleMenu").hasClass("active")) {
					$(".nav").hide();
				} else {
					$(".nav").show();
				}

				// 移动端没有鼠标进入和退出动作，解绑
				$(".nav li").unbind('mouseenter mouseleave');

				// 直接按照结构显示出来
				$(".nav li a.parent").each(function(e) {
					// must be attached to anchor element to prevent bubbling
					$(this).parent("li").addClass("pure-menu-active");
				});

				// 重新绑定点击事件
				$(".nav li a.parent").unbind('click').bind('click', function(e) {
					// must be attached to anchor element to prevent bubbling
					// e.preventDefault();
					// $(this).parent("li").toggleClass("pure-menu-active");

					// 少一个隐藏现在显示子菜单的动作
				});
			} 

			// 在桌面端
			else if (ww >= options.toggleWidth) {

				//$(".pure-menu-children").addClass("animated").addClass("fadeInUp");

				// 隐藏菜单显隐切换按钮
				$(".toggleMenu").css("display", "none");

				// 添加水平菜单类
				$("#site-navigation").addClass("pure-menu-horizontal").removeClass("pure-menu-vertical");

				// 显示导航菜单
				$(".nav").show();

				// 解绑点击事件
				$(".nav li a").unbind('click');

				$(".nav li").hover(function(){
					$(this).find('ul:first').slideDown("fast").css({display:"block"});
				},function(){
					$(this).find('ul:first').slideUp("fast").css({display:"none"});
				});

			}
		};
		

		// 返回
		return this.each(function() {

			// 点击切换菜单时
			$(".toggleMenu").click(function(e) {
				e.preventDefault();
				$(this).toggleClass("active");
				$(this).next(".nav").toggle();
				adjustMenu();
			});

			// 调整菜单
			adjustMenu();

			// 添加父级css类
			addParents();

			// 绑定缩放窗口和水平竖直切换动作
			$(window).bind('resize orientationchange', function() {

				// 重新获取浏览器窗口宽度并调整菜单
				ww = document.body.clientWidth;
				adjustMenu();
			});

		});
   
	};
})(jQuery);