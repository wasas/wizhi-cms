/*
 * jQuery UI Multilevel Accordion v.1
 * 
 * Copyright (c) 2011 Pieter Pareit
 *
 * http://www.scriptbreaker.com
 *
 */

//plugin definition
(function($){
    $.fn.extend({

    // 传入参数到函数中
    accordion: function(options) {
        
		var defaults = {
			accordion: 'true',
			speed: 300,
			closedSign: '[+]',
			openedSign: '[-]'
		};

		// 使用自定义的参数扩展默认的
		var opts = $.extend(defaults, options);
		// 分类当前元素到变量中，在此插件中为UL元素
 		var $this = $(this);
 		
 		// 添加展开按钮到多级菜单
 		$this.find("li").each(function() {
 			if($(this).find("ul").size() !== 0){
 				//添加折叠按钮多级菜单
 				$(this).prepend("<span>"+ opts.closedSign +"</span>");

 				//链接为#时，防止返回顶部
 				if($(this).find("a:first").attr('href') === "#"){
 		  			$(this).find("a:first").click(function(){return false;});
 		  		}
 			}

            // 为 WordPress 当前菜单优化
            if( $(this).hasClass("current-cat-parent") || $(this).hasClass("current-menu-parent") ){
                $(this).find("ul").show();
                $(this).parent().show();
                $(this).find("span:first").remove();
                $(this).prepend("<span>"+ opts.openedSign +"</span>");
            }

            // 展开当前分类的子分类
            if( $(this).hasClass("current-cat") ){
                $(this).find("ul").show();
            }

            // 展开同级分裂
            if( $(this).hasClass("current-menu-item") ){
                $(this).parent().show();
            }
 		});

 		// 打开激活的层级
 		$this.find("li.active").each(function() {
 			$(this).parents("ul").slideDown(opts.speed);
 			$(this).parents("ul").parent("li").find("span:first").html(opts.openedSign);
 		});

  		$this.find("li span").click(function() {
  			if($(this).parent().find("ul").size() !== 0){

  				if(opts.accordion){
  					// 列表打开时，什么都不做
  					if(!$(this).parent().find("ul").is(':visible')){
  						parents = $(this).parent().parents("ul");
  						visible = $this.find("ul:visible");
  						visible.each(function(visibleIndex){
  							var close = true;
  							parents.each(function(parentIndex){
  								if(parents[parentIndex] === visible[visibleIndex]){
  									close = false;
  									return false;
  								}
  							});
  							if(close){
  								if($(this).parent().find("ul") !== visible[visibleIndex]){
  									$(visible[visibleIndex]).slideUp(opts.speed, function(){
  										$(this).parent("li").find("span:first").html(opts.closedSign);
  									});

  								}
  							}
  						});
  					}
  				}

  				if($(this).parent().find("ul:first").is(":visible")){

  					$(this).parent().find("ul:first").slideUp(opts.speed, function(){
  						$(this).parent("li").find("span:first").delay(opts.speed).html(opts.closedSign);
  					});

  				}else{

  					$(this).parent().find("ul:first").slideDown(opts.speed, function(){
  						$(this).parent("li").find("span:first").delay(opts.speed).html(opts.openedSign);
  					});
  				}
  			}
  		});
    }
});
})(jQuery);