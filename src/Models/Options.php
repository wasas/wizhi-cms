<?php

namespace Wizhi\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * 设置选项
 *
 * @package Wizhi\Models
 */
class Options extends Model {
	const CREATED_AT = null;
	const UPDATED_AT = null;

	/**
	 * 选项类数据表
	 *
	 * @var string
	 */
	protected $table = 'options';


	/**
	 * 选项类主键
	 *
	 * @var string
	 */
	protected $primaryKey = 'option_id';


	/**
	 * 可修改的选项属性
	 *
	 * @var array
	 */
	protected $fillable = [
		'option_name',
		'option_value',
		'autoload',
	];


	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = [ 'value' ];


	/**
	 * 获取值
	 * 如果不工作、尝试反序列化对象并返回值
	 *
	 * @return bool|mixed
	 */
	public function getValueAttribute() {
		try {
			$value = unserialize( $this->option_value );
			// if we get false, but the original value is not false then something has gone wrong.
			// return the option_value as is instead of unserializing
			// added this to handle cases where unserialize doesn't throw an error that is catchable
			return $value === false && $this->option_value !== false ? $this->option_value : $value;
		} catch ( Exception $ex ) {
			return $this->option_value;
		}
	}

	/**
	 * 根据名称获取选项值
	 *
	 * @param string $name
	 *
	 * @return string|array
	 */
	public static function get( $name ) {
		if ( $option = self::where( 'option_name', $name )->first() ) {
			return $option->value;
		}

		return;
	}

	/**
	 * 获取所有选项
	 *
	 * @return array
	 */
	public static function getAll() {
		$options = self::all();
		$result  = [];
		foreach ( $options as $option ) {
			$result[ $option->option_name ] = $option->value;
		}

		return $result;
	}
}