<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

namespace Wizhi\Forms\Controls;

use Nette\Forms\Controls\TextBase;

/**
 * 颜色选择
 */
class GetSmsInput extends TextBase {

	private $settings = [];

	/**
	 * @param  string|object Html      标签
	 * @param  array         $settings TinyMce 设置
	 */
	public function __construct( $label = null, $settings = [] ) {
		parent::__construct( $label );
		$this->settings = $settings;
	}


	/**
	 * 生成控件 HTML 内容
	 *
	 * @return string
	 */
	public function getControl() {

		$id       = $this->getHtmlId();
		$action_id = $id . '-action';

		$name     = $this->getHtmlName();
		$settings = $this->settings;
		$data_url = $this->control->getAttribute( 'data-url' );
		$default_value = $this->value ? $this->value : '';

		$settings_default = [
			'textarea_name' => $name,
			'teeny'         => true,
			'media_buttons' => false,
		];

		$settings = wp_parse_args( $settings_default, $settings );

		$html = '<div class="input-group">
                    <input data-url="' . $data_url . '" id="' . $id . '" class="form-control" name="' . $name . '" value="' . $default_value . '">
                    <span class="input-group-btn">
						<input class="btn btn-primary" type="button" name="get_validate_code" id="'. $action_id .'" value="获取验证码" />
					</span>
                </div>';

		$html .= "<script>
            jQuery(document).ready(function ($) {
                //timer处理函数
			    var InterValObj; //timer变量，控制时间
			    var count = 60; //间隔函数，1秒执行
			    var curCount;//当前剩余秒数
                
                var action_id= $('#" . $action_id . "');
                
                // 设置倒计时
                function set_count_dwon() {
			        if (curCount === 0) {
			            window.clearInterval(InterValObj);//停止计时器
			            action_id.removeAttr('disabled');//启用按钮
			            action_id.val('重新发送');
			        }
			        else {
			            curCount--;
			            action_id.val(curCount + '后重新获取');
			        }
			    }
                
                action_id.click(function () {
                    $.ajax({
                        type      : 'POST',
                        dataType  : 'json',
                        url       : '" . $data_url . "',
                        data      : {
                            'mobile': $('input[name=mobile]').val()
                        },
                        beforeSend: function () {
                            $(this).addClass('loading');
                        },
                        success   : function (data) {
                            // 验证码发送成功后，启动计时器
		                    curCount = count;
		
		                    // 设置button效果，开始计时
		                    action_id.prop('disabled', true);
		                    action_id.val(curCount + '后重新获取');
		                    
                            InterValObj = window.setInterval(set_count_dwon, 1000); //启动计时器，1秒执行一次
                            alert(data.message);
                            if (data.sucees === 1) {
                                $(this).removeClass('loading');
                            }
                        },
                        error     : function (data) {
                            $(this).removeClass('loading');
                            alert('发送失败');
                        }
                    });
                })
            });
        </script>";

		return $html;
	}
}
