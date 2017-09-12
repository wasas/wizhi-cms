<?php

namespace Wizhi\Models;

use Corcel\Model;

/**
 * 手机验证码记录
 *
 * Class PhoneCode
 */
class PhoneCode extends Model {

	/**
	 * @var string
	 */
	protected $table = 'security_phone_codes';

	/**
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = [
		'mobile',
		'code',
	];
}
