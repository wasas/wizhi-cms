<?php
/**
 * Created by PhpStorm.
 * User: amoslee
 * Date: 2017/8/26
 * Time: 23:02
 */

namespace Wizhi\Walker;


class Category extends \Walker_Category {

	function start_lvl( &$output, $depth = 0, $args = [] ) {
		if ( 'list' != $args[ 'style' ] ) {
			return;
		}

		$indent = str_repeat( "\t", $depth );
		$output .= "$indent<ul class='pure-menu-children'>\n";
	}

	function start_el( &$output, $category, $depth = 0, $args = [], $id = 0 ) {
		extract( $args );
		$cat_name = esc_attr( $category->name );
		$cat_name = apply_filters( 'list_cats', $cat_name, $category );
		$aclass   = '';

		$termchildren = get_term_children( $category->term_id, $category->taxonomy );
		if ( count( $termchildren ) > 0 ) {
			$aclass = ' class="pure-menu-link" ';
		}
		$link = '<a ' . $aclass . ' href="' . esc_url( get_term_link( $category ) ) . '" ';

		if ( $use_desc_for_title == 0 || empty( $category->description ) ) {
			$link .= 'class="pure-menu-link" title="' . esc_attr( sprintf( __( 'View all posts filed under %s' ), $cat_name ) ) . '"';
		} else {
			$link .= 'class="pure-menu-link" title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
		}
		$link .= '>';
		$link .= $cat_name . '</a>';
		if ( ! empty( $show_count ) ) {
			$link .= ' (' . intval( $category->count ) . ')';
		}
		if ( 'list' == $args[ 'style' ] ) {
			$output .= "\t<li";
			$class  = 'pure-menu-item cat-item-' . $category->term_id;
			if ( ! empty( $current_category ) ) {
				$_current_category = get_term( $current_category, $category->taxonomy );
				if ( $category->term_id == $current_category ) {
					$class .= ' pure-menu-selected';
				} elseif ( $category->term_id == $_current_category->parent ) {
					$class .= ' current-cat-parent';
				}
			}

			if ( 'list' == $args[ 'has_children' ] ) {
				$class .= ' pure-menu-has-children';
			}

			$output .= ' class="' . $class . '"';
			$output .= ">$link\n";
		} else {
			$output .= "\t$link<br />\n";
		}
	}

	function end_lvl( &$output, $depth = 0, $args = [] ) {
		if ( 'list' != $args[ 'style' ] ) {
			return;
		}

		$indent = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}

}

