<?php

/**
 * 创建需要的数据表
 *
 * @since 0.1
 *
 * @return  void
 */
function wizhi_install_database() {
	global $wpdb;
	$collate = '';

	if ( $wpdb->has_cap( 'collation' ) ) {
		if ( ! empty( $wpdb->charset ) ) {
			$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if ( ! empty( $wpdb->collate ) ) {
			$collate .= " COLLATE $wpdb->collate";
		}
	}

	$table_schema = [

		// 手机验证码
		"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}security_phone_codes` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`mobile` text NOT NULL,
			`code` text NOT NULL DEFAULT '',
			PRIMARY KEY (`id`)
		) $collate;",


		// 社交登录
		"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}security_oauths` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`user_id` bigint(20) NOT NULL,
			`mobile` varchar(20) NOT NULL,
			`open_id` varchar(200) DEFAULT NULL,
			`union_id` varchar(200) DEFAULT NULL,
			`access_token` varchar(200) DEFAULT NULL,
			`refresh_token` varchar(200) DEFAULT NULL,
			`nickname` varchar(20) DEFAULT NULL,
			`avatar` varchar(200) DEFAULT NULL,
			`city` varchar(20) DEFAULT NULL,
			`sex` varchar(20) DEFAULT NULL,
			`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`deleted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`),
			KEY `user_id` (`user_id`)
		) $collate;",

	];

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	foreach ( $table_schema as $table ) {
		dbDelta( $table );
	}

}