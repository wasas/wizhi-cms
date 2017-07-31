<?php
/**
 * 主题辅助函数
 *
 */

use Nette\Forms\Form;

if ( ! function_exists( 'dd' ) ) {
	/**
	 * 输出传入的变量并结束程序
	 *
	 * @param  mixed
	 *
	 * @return void
	 */
	function dd( ...$args ) {
		foreach ( $args as $x ) {
			( new Dumper )->dump( $x );
		}
		die( 1 );
	}
}

if ( ! function_exists( 'dda' ) ) {
	/**
	 * 输出传入的变量并结束程序
	 *
	 * @param  mixed
	 *
	 * @return void
	 */
	function dda( ...$args ) {
		foreach ( $args as $x ) {
			( new Dumper )->dump( $x->toArray() );
		}
		die( 1 );
	}
}


/**
 * 根据分类法获取文章类型
 *
 * @param string $taxonomy 分类法名称
 *
 * @return array
 */
if ( ! function_exists( 'get_post_types_by_taxonomy' ) ) {
	function get_post_types_by_taxonomy( $taxonomy = 'category' ) {
		global $wp_taxonomies;

		return ( isset( $wp_taxonomies[ $taxonomy ] ) ) ? $wp_taxonomies[ $taxonomy ]->object_type : [];
	}
}

/**
 * 获取当前分类的父级类 ID
 *
 * @param int    $term_id  分类 id
 * @param string $taxonomy 分类法名称
 *
 * @return mixed
 */
if ( ! function_exists( 'wizhi_get_term_root_id' ) ) {
	function wizhi_get_term_root_id( $term_id, $taxonomy ) {
		$this_term = get_term( $term_id, $taxonomy );

		while ( $this_term->parent ) {
			$this_term = get_term( $this_term->parent, $taxonomy );
		}

		return $this_term->term_id;
	}
}


/**
 * 给当前链接加 “active” 类
 *
 * @param $var   string 查询参数
 * @param $value string 当前连接的查询值
 *
 * @package conditions
 *
 * @return mixed string|bool 如果是当前链接, 返回“active” 字符串, 如果不是,返回 false
 */
if ( ! function_exists( 'is_current_link' ) ) {
	function is_current_link( $var, $value, $default ) {
		$query_value = isset( $_GET[ $var ] ) ? $_GET[ $var ] : $default;

		if ( $query_value == $value ) {
			return "active";
		}

		return false;
	}
}


/**
 * 获取分类法列表模板, 排除默认的页面模板
 *
 * @package   helper
 * @return array 分类法列表模板
 */
if ( ! function_exists( 'wizhi_get_taxonomy_templates' ) ) {
	function wizhi_get_taxonomy_templates() {

		$page_templates = wp_get_theme()->get_page_templates();

		$taxonomy_templates = [];

		foreach ( $page_templates as $filename => $template_name ) {

			if ( strchr( $filename, 'taxonomy-parts' ) ) {
				$taxonomy_templates[ $filename ] = $template_name;
			}

		}

		return $taxonomy_templates;

	}
}


/**
 * 判断文章是否包含在父级分类中
 *
 * @param int $cats  分类id
 * @param int $_post 文章id
 *
 * @package   helper
 *
 * @return boolean
 */
if ( ! function_exists( 'post_is_in_descendant_category' ) ) {

	function post_is_in_descendant_category( $cats, $_post = null ) {
		foreach ( (array) $cats as $cat ) {
			// get_term_children() accepts integer ID only
			$descendants = get_term_children( (int) $cat, 'category' );
			if ( $descendants && in_category( $descendants, $_post ) ) {
				return true;
			}
		}

		return false;
	}

}


/**
 * 反向转换slug为正常的字符串
 *
 * @param  $slug string 分类id
 *
 * @package   helper
 *
 * @return string 反格式化后的字符串
 */
if ( ! function_exists( "wizhi_unslug" ) ) {
	function wizhi_unslug( $slug = null ) {

		if ( ! $slug ) {
			$post_data = get_post( get_the_id(), ARRAY_A );
			$slug      = $post_data[ 'post_name' ];
		}

		return ucwords( str_replace( "-", " ", $slug ) );
	}
}


if ( ! function_exists( "order_no" ) ) {
	/**
	 * 生成订单号
	 *
	 * @package   helper
	 *
	 * @return string 订单号字符串
	 */
	function order_no() {
		return date( 'Ymd' ) . str_pad( mt_rand( 1, 99999 ), 5, '0', STR_PAD_LEFT );
	}
}


/**
 * 格式化 Nette Form
 *
 * @package   helper
 *
 * @param  \Form  $form Nette 表单
 * @param  string $type 表单显示类型
 *
 * @return string 订单号字符串
 */
function wizhi_form( Form $form, $type = 'horizontal' ) {
	$renderer                                            = $form->getRenderer();
	$renderer->wrappers[ 'controls' ][ 'container' ]     = null;
	$renderer->wrappers[ 'pair' ][ 'container' ]         = 'div class=form-group';
	$renderer->wrappers[ 'pair' ][ '.error' ]            = 'has-error';
	$renderer->wrappers[ 'control' ][ 'container' ]      = $type == 'horizontal' ? 'div class=col-sm-9' : '';
	$renderer->wrappers[ 'label' ][ 'container' ]        = $type == 'horizontal' ? 'div class="col-sm-3 control-label"' : '';
	$renderer->wrappers[ 'control' ][ 'description' ]    = 'span class=help-block';
	$renderer->wrappers[ 'control' ][ 'errorcontainer' ] = 'span class=help-block';
	$form->getElementPrototype()->class( $type == 'horizontal' ? 'form-horizontal' : '' );
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