<?php

namespace Wizhi\Models;

use Corcel\Model;
use Corcel\Model\User;

/**
 * 社交，保存和绑定社交数据
 *
 * Class OpenAuth
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
		'mobile',
		'open_id',
		'union_id',
		'access_token',
		'refresh_token',
		'nickname',
		'avatar',
		'city',
		'sex',
		'platform',
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
