<?php
/**
 * 为文章添加点赞功能
 *
 */

use TheFold\WordPress\Dispatch;

/**
 * 在网站上显示喜欢/不喜欢按钮
 *
 * @param array
 *
 * @return string
 */
function wizhi_like( $arg = null ) {
	$post_id = get_the_ID();
	$ip      = wizhi_get_real_ip();
	$html    = "";
	$class   = "";

	$has_already_voted = wizhi_already_vote( $post_id, $ip );

	if ( $has_already_voted ) {
		$class = 'voted';
	}

	$title_text_like   = __( 'Like', 'wizhi' );
	$title_text_unlike = __( 'Unlike', 'wizhi' );

	// 安全验证
	$nonce = wp_create_nonce( "wizhi_like_post_vote_nonce" );

	$like_count   = wizhi_get_like_count( $post_id );
	$unlike_count = wizhi_get_like_count( $post_id );

	$msg = wizhi_get_voted_msg( $post_id );

	$alignment    = ( "left" == get_option( 'wti_like_post_alignment' ) ) ? 'align-left' : 'align-right';
	$show_dislike = 0;

	$html .= "<span class='watch-action $class'>";
	$html .= "<span class='watch-position $alignment'>";

	$html .= "<span class='action-like'>";
	$html .= "<a class='like-$post_id jlk' href='javascript:void(0)' data-task='like' data-post_id=' $post_id' data-nonce='$nonce' rel='nofollow'>";
	$html .= "<i class='fa fa-thumbs-up' aria-hidden='true' title='$title_text_like'></i>";
	$html .= "<span class='lc-$post_id lc'>$like_count</span>";
	$html .= "</a></span>";

	if ( $show_dislike ) {
		$html .= "<span class='action-unlike'>";
		$html .= "<a class='unlike-$post_id jlk' href='javascript:void(0)' data-task='unlike' data-post_id='$post_id' data-nonce='$nonce' rel='nofollow'>";
		$html .= "<i class='fa fa-thumbs-down' aria-hidden='true' title='$title_text_unlike'></i>";
		$html .= "<span class='unlc-$post_id unlc'>$unlike_count</span>";
		$html .= "</a></span> ";
	}

	$html .= "</span>";
	$html .= "<span class='status-$post_id status $alignment'>$msg</span>";
	$html .= "</span>";

	echo $html;

}


/**
 * 获取真实 IP 地址
 *
 * @param no -param
 *
 * @return string
 */
function wizhi_get_real_ip() {
	if ( getenv( 'HTTP_CLIENT_IP' ) ) {
		$ip = getenv( 'HTTP_CLIENT_IP' );
	} elseif ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
		$ip = getenv( 'HTTP_X_FORWARDED_FOR' );
	} elseif ( getenv( 'HTTP_X_FORWARDED' ) ) {
		$ip = getenv( 'HTTP_X_FORWARDED' );
	} elseif ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
		$ip = getenv( 'HTTP_FORWARDED_FOR' );
	} elseif ( getenv( 'HTTP_FORWARDED' ) ) {
		$ip = getenv( 'HTTP_FORWARDED' );
	} else {
		$ip = $_SERVER[ 'REMOTE_ADDR' ];
	}

	return $ip;
}


/**
 * 获取最近投票日期
 *
 * @param      $post_id
 * @param null $ip
 *
 * @return mixed
 */
function get_late_vote_date( $post_id, $ip = null ) {

	if ( null == $ip ) {
		$ip = wizhi_get_real_ip();
	}

	$like = R::findOne( LIKE, ' post_id = ? AND ip = ? ', [ $post_id, $ip ] );

	return $like->date_time;
}

/**
 * 获取下一个可以投票的日期
 *
 * @param $last_voted_date string
 * @param $voting_period   integer
 *
 * @return string
 */
function get_next_vote_date( $last_voted_date, $voting_period ) {
	$day = $month = $year = 0;

	switch ( $voting_period ) {
		case "1":
			$day = 1;
			break;
		case "2":
			$day = 2;
			break;
		case "3":
			$day = 3;
			break;
		case "7":
			$day = 7;
			break;
		case "14":
			$day = 14;
			break;
		case "21":
			$day = 21;
			break;
		case "1m":
			$month = 1;
			break;
		case "2m":
			$month = 2;
			break;
		case "3m":
			$month = 3;
			break;
		case "6m":
			$month = 6;
			break;
		case "1y":
			$year = 1;
			break;
	}

	$last_strtotime = strtotime( $last_voted_date );
	$next_strtotime = mktime( date( 'H', $last_strtotime ), date( 'i', $last_strtotime ), date( 's', $last_strtotime ), date( 'm', $last_strtotime ) + $month, date( 'd', $last_strtotime ) + $day, date( 'Y', $last_strtotime ) + $year );

	$next_voting_date = date( 'Y-m-d H:i:s', $next_strtotime );

	return $next_voting_date;
}


/**
 * 获取喜欢数量
 *
 * @param $post_id integer
 *
 * @return string
 */
function wizhi_get_like_count( $post_id = 0 ) {

	if ( $post_id == 0 ) {
		$post_id = get_the_id();
	}

	$count = R::count( LIKE, ' post_id = ? AND value >= ? ', [ $post_id, 0 ] );

	return $count;
}

/**
 * 获取不喜欢数量
 *
 * @param $post_id integer
 *
 * @return string
 */
function wizhi_get_unlike_count( $post_id ) {
	$count = R::count( LIKE, ' post_id = ? AND value <= ? ', [ $post_id, 0 ] );

	return $count;
}


/**
 * Check whether user has already voted or not
 *
 * @param $post_id integer
 * @param $ip      string
 *
 * @return integer
 */
function wizhi_already_vote( $post_id, $ip = null ) {

	if ( null == $ip ) {
		$ip = wizhi_get_real_ip();
	}

	$count = R::count( LIKE, ' post_id = ? AND ip = ? ', [ $post_id, $ip ] );

	return $count;
}


/**
 * Check whether user has already voted or not
 *
 * @param $post_id integer
 * @param $ip      string
 *
 * @return integer
 */
function wizhi_get_voted_msg( $post_id, $ip = null ) {

	$msg = '';

	if ( null == $ip ) {
		$ip = wizhi_get_real_ip();
	}

	$count = R::count( LIKE, ' post_id = ? AND ip = ? ', [ $post_id, $ip ] );

	if ( $count > 0 ) {
		$msg = "Already voted";
	}

	return $msg;
}

/**
 * 个人资料页面
 */
new Dispatch( [

	'like' => function ( $request ) {

		// 获取请求数据
		$wizhi_ip_address = wizhi_get_real_ip();
		$post_id          = (int) $_REQUEST[ 'post_id' ];
		$task             = $_REQUEST[ 'task' ];

		// 检查随机数
		if ( ! wp_verify_nonce( $_REQUEST[ 'nonce' ], 'wizhi_like_post_vote_nonce' ) ) {
			$error = 1;
			$msg   = __( 'Access denied', 'wizhi' );

			// 判断是否可以投票
		} else {
			// 获取设置数据
			$is_logged_in   = is_user_logged_in();
			$login_required = 0;
			$can_vote       = false;

			if ( $login_required && ! $is_logged_in ) {
				// 是否需要登录
				$error = 1;
				$msg   = 'Please login to vote!';
			} else {

				$has_already_voted = wizhi_already_vote( $post_id, $wizhi_ip_address );

				$voting_period = 0;
				$datetime_now  = date( 'Y-m-d H:i:s' );

				if ( "once" == $voting_period && $has_already_voted ) {
					// 是否只能投票一次
					$error = 1;
					$msg   = 'Already voted';

				} elseif ( '0' == $voting_period ) {
					// 是否可投票多次
					$can_vote = true;

				} else {
					if ( ! $has_already_voted ) {
						// 以前没有投过票
						$can_vote = true;
					} else {
						// 获取最近一次投票日期
						$last_voted_date = get_late_vote_date( $post_id, $wizhi_ip_address );

						// 获取最近可以投票的日期
						$next_vote_date = get_next_vote_date( $last_voted_date, $voting_period );

						if ( $next_vote_date > $datetime_now ) {
							$revote_duration = ( strtotime( $next_vote_date ) - strtotime( $datetime_now ) ) / ( 3600 * 24 );

							$can_vote = false;
							$error    = 1;
							$msg      = __( 'You can vote again after', 'wizhi' ) . ' ' . ceil( $revote_duration ) . ' ' . __( 'days', 'wizhi' );
						} else {
							$can_vote = true;
						}
					}
				}
			}


			if ( $can_vote ) {
				$current_user = wp_get_current_user();
				$user_id      = (int) $current_user->ID;

				$like = R::findOrCreate( LIKE, [ 'user_ID' => $user_id, 'post_id' => $post_id ] );

				// 喜欢
				if ( $task == "like" ) {
					if ( $has_already_voted ) {
						$like->value = $like->value + 1;
					} else {
						$like->value = 1;
					}


					// 不喜欢
				} else {
					if ( $has_already_voted ) {
						$like->value = $like->value - 1;
					} else {
						$like->value = 1;
					}
				}

				$like->post_id   = $post_id;
				$like->user_id   = $user_id;
				$like->ip        = $wizhi_ip_address;
				$like->date_time = date( 'Y-m-d H:i:s' );
				$success         = R::store( $like );

				if ( $success ) {
					$error = 0;
					$msg   = 'Thanks for voting!';
				} else {
					$error = 1;
					$msg   = __( 'Voting failed, tye again later。', 'wizhi' );
				}
			}

			$wizhi_like_count   = wizhi_get_like_count( $post_id );
			$wizhi_unlike_count = wizhi_get_unlike_count( $post_id );
		}

		// 检查数据数据的方法
		$result = [
			"msg"    => $msg,
			"error"  => $error,
			"like"   => $wizhi_like_count,
			"unlike" => $wizhi_unlike_count,
		];

		wp_send_json( $result );

	},

] );