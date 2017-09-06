<?php

namespace Wizhi\Models;

use Corcel\Model;
use Corcel\Model\User;

/**
 * 社交
 *
 * Class Order
 */
class OpenAuth extends Model {

	/**
	 * @var string
	 */
	protected $table = 'security_oauths';

	/**
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 * @var bool
	 */
	public $timestamps = true;

	/**
	 * @var array
	 */
	protected $fillable = [
		'user_id',
		'open_id',
		'union_id',
		'access_token',
		'refresh_token',
		'nickname',
		'avatar',
		'city',
		'sex',
	];

	/**
	 * 订单属于的用户
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {
		return $this->belongsTo( User::class, 'user_id' );
	}

}
