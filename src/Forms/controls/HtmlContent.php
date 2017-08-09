<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

namespace Wizhi\Forms\Controls;


/**
 * Push button control with no default behavior.
 */
class HtmlContent extends \Nette\Forms\Controls\BaseControl {

	/**
	 * @param  string|object
	 */
	public function __construct( $caption = null ) {
		parent::__construct( $caption );
		$this->control->type = 'html';
		$this->setOption( 'type', 'html' );
	}

	/**
	 * 跳过 label
	 *
	 * @param null $caption
	 *
	 * @return void
	 */
	public function getLabel( $caption = null ) {
	}


	/**
	 * 输出 HTML 内容
	 *
	 * @param  string|object
	 *
	 * @return string
	 */
	public function getControl( $caption = null ) {
		$this->setOption( 'rendered', true );
		$caption = $this->caption;

		return $caption;
	}

}