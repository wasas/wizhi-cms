<?php

namespace Wizhi\Payment;

/**
 * 获取支付网关
 */
class Gateway {

	/**
	 * 设置支付宝支付网关
	 *
	 * @return mixed
	 */
	public static function Alipay() {

		global $wizhi_option;
		$payment = $wizhi_option( 'payment' );

		$gateway = Omnipay::create( 'Alipay_Express' );
		$gateway->setPartner( $payment[ 'alipay_partner' ] );
		$gateway->setKey( $payment[ 'alipay_key' ] );
		$gateway->setSellerEmail( $payment[ 'alipay_email' ] );

		return $gateway;

	}


	/**
	 * 设置微信支付网关
	 *
	 * @return mixed
	 */
	public static function Wechat( $type = 'native' ) {

		global $wizhi_option;
		$payment = $wizhi_option( 'payment' );

		if ( $type == 'native' ) {
			$gateway = Omnipay::create( 'WechatPay_Native' );
		} else {
			$gateway = Omnipay::create( 'WechatPay_Js' );
		}

		$gateway->setAppId( $payment[ 'wechat_app_id' ] );
		$gateway->setMchId( $payment[ 'wechat_mch_id' ] );

		// 这个 key 需要在微信商户里面单独设置，而吧是微信服务号里面的 key
		$gateway->setApiKey( $payment[ 'wechat_payment_key' ] );

		return $gateway;

	}

}