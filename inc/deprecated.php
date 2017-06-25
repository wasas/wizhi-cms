<?php
/**
 * 已被弃用的类，在未来的版本中会被移除
 */

/**
 * 格式化 Nette Form
 *
 * @param \Form $form
 *
 * @deprecated
 */
function wizhi_form( Form $form ) {
	$renderer                                            = $form->getRenderer();
	$renderer->wrappers[ 'controls' ][ 'container' ]     = null;
	$renderer->wrappers[ 'pair' ][ 'container' ]         = 'div class=form-group';
	$renderer->wrappers[ 'pair' ][ '.error' ]            = 'has-error';
	$renderer->wrappers[ 'control' ][ 'container' ]      = 'div class=col-sm-9';
	$renderer->wrappers[ 'label' ][ 'container' ]        = 'div class="col-sm-3 control-label"';
	$renderer->wrappers[ 'control' ][ 'description' ]    = 'span class=help-block';
	$renderer->wrappers[ 'control' ][ 'errorcontainer' ] = 'span class=help-block';
	$form->getElementPrototype()->class( 'form-horizontal' );
	$form->onRender[] = function ( $form ) {
		foreach ( $form->getControls() as $control ) {
			$type = $control->getOption( 'type' );
			if ( $type === 'button' ) {
				$control->getControlPrototype()->addClass( empty( $usedPrimary ) ? 'btn btn-primary' : 'btn btn-default' );
				$usedPrimary = true;
			} elseif ( in_array( $type, [ 'text', 'textarea', 'select' ], true ) ) {
				$control->getControlPrototype()->addClass( 'form-control' );
			} elseif ( in_array( $type, [ 'checkbox', 'radio' ], true ) ) {
				$control->getSeparatorPrototype()->setName( 'div' )->addClass( $type );
			}
		}
	};
}


/**
 * 获取文章类型存档设置
 *
 * @param string $type 文章类型别名
 *
 * @return mixed
 *
 * @deprecated
 */
function get_archive_option( $type ) {
	return get_option( $type . "_archive_settings" );
}


/**
 *  获取存档或文章标题作为页面标题使用
 *
 * @package   template
 *
 * @deprecated
 */
function wizhi_get_the_archive_title() {
	if ( is_category() ) {
		$title = sprintf( __( '%s' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( '%s' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( '%s' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( '%s' ), get_the_date( _x( 'Y', 'wizhi', 'yearly archives date format' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( '%s' ), get_the_date( _x( 'F Y', 'wizhi', 'monthly archives date format' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( '%s' ), get_the_date( _x( 'F j, Y', 'wizhi', 'daily archives date format' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'wizhi', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'wizhi', 'post format archive title' );
		}
	} elseif ( is_post_type_archive() ) {

		$title = sprintf( __( '%s' ), post_type_archive_title( '', false ) );

		$post_type = get_queried_object()->name;

		if ( ! Archive::option( $post_type, 'title' ) ) {
			Archive::option( $post_type, 'title' );
		}

	} elseif ( is_page() ) {
		$title = sprintf( __( '%s' ), get_the_title( '', false ) );
	} elseif ( is_single() ) {
		$title = sprintf( __( '%s' ), get_the_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax   = get_taxonomy( get_queried_object()->taxonomy );
		$title = sprintf( __( '%1$s' ), single_term_title( '', false ) );
	} else {
		$title = __( 'Archives', 'wizhi' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @since 4.1.0
	 *
	 * @param string $title Archive title to be displayed.
	 */
	return apply_filters( 'get_the_archive_title', $title );
}


/**
 * @param $post_type string 文章类型标签
 *
 * @return bool
 *
 * @deprecated
 */
function wizhi_get_the_archive_description( $post_type = '' ) {

	if ( ! Archive::description( $post_type ) ) {
		return Archive::description( $post_type );
	}

	return false;

}
