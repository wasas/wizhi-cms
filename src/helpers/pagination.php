<?php
/**
 * 显示分页导航
 *
 * @Author: kriesi
 * @Link  : http://www.kriesi.at/archives/how-to-build-a-wordpress-post-pagination-without-plugin
 *
 * @uses  get_pagenum_link
 *
 * @since wizhi 1.0
 */

namespace Wizhi\Helper;

class Pagination {

	use \Nette\StaticClass;

	/**
	 * Bootstrap 分页导航
	 *
	 * @param string $query 需要分页的查询对象名称
	 * @param string $pages 总页数
	 * @param int    $range 每次显示的页数
	 *
	 * @package template
	 *
	 * @usage   wizhi_bootstrap_pagination();
	 *
	 */
	public static function bootstrap( $query = '', $pages = '', $range = 5 ) {
		$showitems = ( $range * 2 ) + 1;

		global $paged;
		if ( empty( $paged ) ) {
			$paged = 1;
		}

		if ( ! $query ) {
			global $wp_query;
			$wizhi_query = $wp_query;
		} else {
			$wizhi_query = $query;
		}

		if ( $pages == '' ) {
			$pages = $wizhi_query->max_num_pages;
			if ( ! $pages ) {
				$pages = 1;
			}
		}

		if ( 1 != $pages ) {
			echo '<ul class="pagination">';
			if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
				echo '<li><a aria-label="Previous" href="' . get_pagenum_link( 1 ) . '"><span aria-hidden="true">«</span></a></li>';
			}
			if ( $paged > 1 && $showitems < $pages ) {
				echo '<li><a aria-label="Previous" href="' . get_pagenum_link( $paged - 1 ) . '"><span aria-hidden="true"><</span></a></li>';
			}

			for ( $i = 1; $i <= $pages; $i ++ ) {
				if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
					if ( $paged == $i ) {
						echo '<li class="active"><a href="#">' . $i . '</a></li>';
					} else {
						echo '<li><a href="' . get_pagenum_link( $i ) . '">' . $i . '</a></li>';
					}
				}
			}

			if ( $paged < $pages ) {
				echo '<li><a class="nextpostslink" aria-label="Next" href="' . get_pagenum_link( $paged + 1 ) . '"><span aria-hidden="true">></span></a></li>';
			}
			if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
				echo '<li><a class="nextpostslink" aria-label="Next" href="' . get_pagenum_link( $pages ) . '"><span aria-hidden="true">»</span></a></li>';
			}
			echo '</ul>';
		}
	}

}