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
	 * 允许读写的字段
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
			// 如果我们得到了 false, 但是原生数据不是 false, 那肯定是什么地方出错了，原样返回option_value，而不是反序列化数据
			// 添加这个来处理反序列化不抛出可获取的错误信息的情况
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