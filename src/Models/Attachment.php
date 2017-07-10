<?php

namespace Wizhi\Models;

/**
 * 附件模型，附件是一个文章类型
 */
class Attachment extends Post {
	/**
	 * 附件文章类型
	 *
	 * @var string
	 */
	protected $postType = 'attachment';

	/**
	 * 附加可以存取的字段到模型表单
	 *
	 * @var array
	 */
	protected $appends = [
		'title',
		'url',
		'type',
		'description',
		'caption',
		'alt',
	];

	/**
	 * 获取附件标题
	 *
	 * @return string
	 */
	public function getTitleAttribute() {
		return $this->post_title;
	}

	/**
	 * 获取附件 URL
	 *
	 * @return string
	 */
	public function getUrlAttribute() {
		return $this->guid;
	}

	/**
	 * 获取附件 Mime 类型
	 *
	 * @return string
	 */
	public function getTypeAttribute() {
		return $this->post_mime_type;
	}

	/**
	 * 获取附件描述
	 *
	 * @return string
	 */
	public function getDescriptionAttribute() {
		return $this->post_content;
	}

	/**
	 * 获取附件说明
	 *
	 * @return string
	 */
	public function getCaptionAttribute() {
		return $this->post_excerpt;
	}

	/**
	 * 获取附件 Alt 属性
	 *
	 * @return string
	 */
	public function getAltAttribute() {
		return $this->meta->_wp_attachment_image_alt;
	}

	/**
	 * 返回附件基本信息
	 *
	 * @return array
	 */
	public function toArray() {
		$result = [];

		foreach ( $this->appends as $field ) {
			$result[ $field ] = $this[ $field ];
		}

		return $result;
	}
}
