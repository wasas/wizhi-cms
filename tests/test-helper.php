<?php
/**
 * Class HelperTest
 *
 * @package Wizhi_Cms
 */

/**
 * Sample test case.
 */
class HelperTest extends WP_UnitTestCase {

	/**
	 * 测试插件辅助功能
	 */
	function test_conditions() {
		$this->assertFalse( is_wechat() );
		$this->assertFalse( is_ajax() );
		$this->assertTrue( is_en() );

		$this->assertEquals( get_ip(), '127.0.0.1' );
	}


	/**
	 * 测试前端文件路径
	 */
	function test_fronted() {
		$this->assertEquals( assets( 'images/logo.png' ), get_theme_file_uri( 'front/dist/images/logo.png' ) );
		$this->assertEquals( assets( '/images/logo.png' ), get_theme_file_uri( 'front/dist/images/logo.png' ) );

		$this->assertEquals( unslug( 'today-is-a-good-day' ), 'Today Is A Good Day' );
	}

}
