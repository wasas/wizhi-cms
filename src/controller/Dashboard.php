<?php

namespace Wizhi\Controllers;

use Themosis\Route\BaseController;
use Wizhi\Models\Affiliate;
use Wizhi\Models\Income;
use Wizhi\Models\Withdrawal;

class Dashboard extends BaseController {

	/**
	 * @return string
	 */
	public function index() {

		if ( ! is_user_logged_in() ) {
			wp_redirect( home_url('affiliate-login-page') );
		}

		$user = wp_get_current_user();

		// 总收益
		$sum = Income::where( 'user_id', '=', $user->ID )->sum( 'amount' );

		// 未提现收益
		$sum_wait = Income::where( 'user_id', '=', $user->ID )
		                  ->where( 'status', '=', 'wait' )
		                  ->sum( 'amount' );

		// 已提现收益
		$sum_complete = Income::where( 'user_id', '=', $user->ID )
		                      ->where( 'status', '=', 'complete' )
		                      ->sum( 'amount' );

		// 收益排行榜
		$tops = Income::selectRaw( '*, sum(amount) as sum' )
		              ->groupBy( 'user_id' )
		              ->orderBy( 'sum', 'desc' )
		              ->get( 10 );

		return view( 'dashboard.index', [
			'user'         => $user,
			'sum'          => $sum,
			'sum_wait'     => $sum_wait,
			'sum_complete' => $sum_complete,
			'records'      => $tops,
		] );
		
	}


	/**
	 * 推荐记录
	 *
	 * @return string
	 */
	public function affiliate() {

		if ( ! is_user_logged_in() ) {
			wp_redirect( home_url() );
		}

		$user = wp_get_current_user();

		$records = Affiliate::where( 'aff_user_id', '=', $user->ID )->get();

		// 处理分页
		$per_page = 2;
		$paged    = ( isset( $_GET[ 'paged' ] ) ) ? $_GET[ 'paged' ] : 1;
		$pages    = $records->count() / $per_page;

		// 获取当前页文章
		$records = $records->forPage( $paged, $per_page );

		return view( 'affiliate.index', [
			'user'    => $user,
			'records' => $records,
			'pages'   => $pages,
			'paged'   => $paged,
		] );
	}


	/**
	 * 收益记录
	 *
	 * @return string
	 */
	public function income() {

		if ( ! is_user_logged_in() ) {
			wp_redirect( home_url() );
		}

		$user = wp_get_current_user();

		$records = Income::where( 'user_id', '=', $user->ID )->get();

		$per_page = 2;
		$paged    = ( isset( $_GET[ 'paged' ] ) ) ? $_GET[ 'paged' ] : 1;
		$pages    = $records->count() / $per_page;

		// 获取当前页文章
		$records = $records->forPage( $paged, $per_page );

		return view( 'income.index', [
			'user'    => $user,
			'records' => $records,
			'pages'   => $pages,
			'paged'   => $paged,
		] );
	}


	/**
	 * 提现记录
	 *
	 * @return string
	 */
	public function withdrawal() {

		if ( ! is_user_logged_in() ) {
			wp_redirect( home_url() );
		}

		$user = wp_get_current_user();

		$records = Withdrawal::where( 'user_id', '=', $user->ID )->get();

		$per_page = 2;
		$paged    = ( isset( $_GET[ 'paged' ] ) ) ? $_GET[ 'paged' ] : 1;
		$pages    = $records->count() / $per_page;

		// 获取当前页文章
		$records = $records->forPage( $paged, $per_page );

		return view( 'withdrawal.index', [
			'user'    => $user,
			'records' => $records,
			'pages'   => $pages,
			'paged'   => $paged,
		] );
	}

}