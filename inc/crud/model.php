<?php


/**
 * Model Class
 *
 */
class Model {

	private $db;
	private $table_name;
	private $columns;
	private $primary_key;
	private $pk_is_int;

	/**
	 * Constructor
	 *
	 */
	public function __construct( $table_name ) {
		$this->init( $table_name );
	}

	/**
	 * 设置数据表名称, 列信息和主键
	 *
	 */
	public function init( $table_name ) {

		// 设置和数据表
		global $wpdb;
		$this->db         = $wpdb;
		$this->table_name = $table_name;

		// 收集列信息
		$this->db->get_row( "SELECT * FROM `$this->table_name`" );
		$this->columns = [ ];

		foreach ( $this->db->get_col_info( 'name' ) as $i => $name ) {
			$this->columns[ $name ] = $this->db->col_info[ $i ]->type;
		}

		// 查找主键、判断是否为整数
		$row               = $this->db->get_row( "SHOW FIELDS FROM `$this->table_name` WHERE `Key` = 'PRI'" );
		$this->primary_key = $row->Field;
		$this->pk_is_int   = false;

		if ( stristr( $row->Type, 'int' ) ) {
			$this->pk_is_int = true;
		}
	}

	/**
	 * 返回主键键名
	 *
	 */
	public function get_primary_key() {
		return $this->primary_key;
	}

	/**
	 * 返回表格名称
	 *
	 */
	public function get_table_name() {
		return $this->table_name;
	}

	/**
	 * 返回列名称和类型
	 *
	 */
	public function get_columns() {
		return $this->columns;
	}

	/**
	 * Returns candidate id for new record
	 *
	 */
	public function get_new_candidate_id() {
		$new_id = "";

		// autoincrement if pk is integer
		if ( $this->pk_is_int ) {
			$new_id = $this->db->get_var( "SELECT MAX(`$this->primary_key`)+1 FROM `$this->table_name`" );
		} else {
			$new_id = $this->db->get_var( "SELECT MAX(`$this->primary_key`) FROM `$this->table_name`" );
			$new_id .= NEW_ID_HINT;
		}
		if ( $new_id == "" ) {
			$new_id = "1";
		}

		return $new_id;
	}

	/**
	 * 选择所有数据
	 *
	 */
	public function select_all() {
		return $this->db->get_results( "SELECT * FROM `$this->table_name`" );
	}

	/**
	 * 选择合适的数据
	 *
	 */
	public function select( $key_word, $order_by, $order, $begin_row, $end_row ) {

		$where_qry = $this->generate_where_query( $key_word );
		$order_qry = $this->generate_order_query( $order_by, $order );
		$sql       = "SELECT * FROM `$this->table_name` $where_qry $order_qry LIMIT $begin_row, $end_row";

		return $this->db->get_results( $sql );
	}

	/**
	 * Returns total row count
	 *
	 */
	public function count_rows( $key_word = "" ) {

		$where_qry = $this->generate_where_query( $key_word );
		$sql       = "SELECT COUNT(*) FROM `$this->table_name` $where_qry";

		return $this->db->get_var( $sql );
	}

	/**
	 * 生成数据库 where 查询
	 *
	 */
	private function generate_where_query( $key_word ) {
		$qry = "";
		if ( $key_word != "" ) {
			$like_statements = [ ];
			foreach ( $this->columns as $name => $type ) {
				$like_statements[] = $this->db->prepare( " `$name` LIKE '%%%s%%'", $key_word );
			}
			$qry = " WHERE " . implode( " OR ", $like_statements );
		}

		return $qry;
	}

	/**
	 * 生成排序查询
	 *
	 */
	private function generate_order_query( $order_by, $order ) {
		$qry = "";
		if ( $order_by != "" ) {
			$order    = esc_sql( $order );
			$order_by = esc_sql( $order_by );
			$qry      = " ORDER BY `$order_by` $order";
		}

		return $qry;
	}

	/**
	 * 返回一行数据
	 *
	 */
	public function get_row( $id ) {
		$sql = $this->db->prepare( "SELECT * FROM `$this->table_name` WHERE `$this->primary_key` = '%s'", $id );

		return $this->db->get_row( $sql );
	}

	/**
	 * 添加新记录
	 *
	 */
	public function insert( $vals ) {

		// 收集需要插入的数据, 处理 /
		$insert_vals = [ ];
		foreach ( $this->columns as $name => $type ) {
			$insert_vals[ $name ] = stripslashes_deep( $vals[ $name ] );
		}

		// 检查主键是否已存在
		$sql    = $this->db->prepare( "SELECT `$this->primary_key` FROM `$this->table_name` WHERE `$this->primary_key` = '%s'", $insert_vals[ $this->primary_key ] );
		$exists = $this->db->get_var( $sql );

		// 插入数据库
		if ( $exists == "" ) {
			if ( $this->db->insert( $this->table_name, $insert_vals ) ) {
				return $insert_vals[ $this->primary_key ];
			}
		}

		return "";
	}

	/**
	 * 更新记录
	 *
	 */
	public function update( $vals ) {

		// 收集需要更新的数据, 处理 /
		$update_vals = [ ];
		foreach ( $this->columns as $name => $type ) {
			$update_vals[ $name ] = stripslashes_deep( $vals[ $name ] );
		}

		// 更新记录
		if ( $this->db->update( $this->table_name, $update_vals, [ $this->primary_key => $vals[ $this->primary_key ] ] ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 删除记录
	 *
	 */
	public function delete( $id ) {
		$sql = $this->db->prepare( "DELETE FROM `$this->table_name` WHERE `$this->primary_key` = '%s'", $id );
		if ( $this->db->query( $sql ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 检查数据表的正确性
	 *
	 */
	public function validate( $table_name ) {

		// 收集列信息并检查错误
		$err_msg = "";
		$results = $this->db->get_results( "SHOW KEYS FROM `$table_name` WHERE `Key_name` = 'PRIMARY'" );
		if ( 1 < $this->db->num_rows ) {
			$err_msg = "Error: table $table_name has multiple primary keys";

		} else if ( $results[ 0 ]->Seq_in_index != 1 ) {
			$err_msg = "Error: table $table_name's primary key is not set at first column";

		}

		return $err_msg;
	}

	/**
	 * 获取可用的数据表为schema
	 *
	 */
	public function get_table_options() {
		$options = [ ];
		foreach ( $this->db->get_results( "SHOW TABLES" ) as $row ) {
			foreach ( $row as $k => $v ) {
				$options[] = $v;
			}
		}

		return $options;
	}
}