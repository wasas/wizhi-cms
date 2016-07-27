<?php

/**
 * Controller Class
 *
 */
class CrudController {

	private $model;
	private $column_names;
	private $rows_per_page;
	private $slug;
	private $url;

	/**
	 * Constructor - 设置表格参数和别名
	 *
	 */
	public function __construct( $table_name, $slug, $args = [ ] ) {

		// 获取设置
		$this->rows_per_page = $args[ 'rows_per_page' ];

		// 数据库
		$this->model        = new Model( $table_name );
		$this->column_names = $args[ 'column_names' ];

		// 菜单页面别名
		$this->slug[ 'list' ] = $slug . '_list';
		$this->slug[ 'add' ]  = $slug . '_add';
		$this->slug[ 'edit' ] = $slug . '_edit';

		add_action( 'admin_menu', [ $this, 'add_menu' ] );

		$this->url[ 'list' ] = admin_url( 'admin.php?page=' . $this->slug[ 'list' ] );
		$this->url[ 'edit' ] = admin_url( 'admin.php?page=' . $this->slug[ 'edit' ] );
		$this->url[ 'add' ]  = admin_url( 'admin.php?page=' . $this->slug[ 'add' ] );
	}

	/**
	 * 添加管理菜单
	 *
	 */
	public function add_menu() {
		add_menu_page( '表格', '表格', 'manage_options', $this->slug[ 'list' ], [ $this, 'list_all' ] );
		add_submenu_page( null, '表格-添加', 'Add New', 'manage_options', $this->slug[ 'add' ], [ $this, 'add_new' ] );
		add_submenu_page( null, '表格编辑', 'Edit', 'manage_options', $this->slug[ 'edit' ], [ $this, 'edit' ] );
	}

	/**
	 * Top menu - 列出表格中所有数据
	 *
	 */
	public function list_all() {

		// 搜索
		$key_word = "";
		if ( isset( $_POST[ 'search' ] ) ) {
			$key_word = $_POST[ 'search' ];
		}
		if ( isset( $_GET[ 'search' ] ) ) {
			$key_word = $_GET[ 'search' ];
		}

		$key_word = stripslashes_deep( $key_word );

		// 排序
		$order_by = "";
		$order    = "";
		if ( isset( $_GET[ 'orderby' ] ) ) {
			$order_by = $_GET[ 'orderby' ];
			$order    = $_GET[ 'order' ];
		}

		// 管理记录数量
		$begin_row = 0;
		if ( isset( $_GET[ 'beginrow' ] ) ) {
			if ( is_numeric( $_GET[ 'beginrow' ] ) ) {
				$begin_row = $_GET[ 'beginrow' ];
			}
		}

		// 统计行数
		$total = $this->model->count_rows( $key_word );

		$next_begin_row = $begin_row + $this->rows_per_page;
		if ( $total < $next_begin_row ) {
			$next_begin_row = $total;
		}

		$last_begin_row = $this->rows_per_page * ( floor( ( $total - 1 ) / $this->rows_per_page ) );

		// 需要显示的内容
		$table_name  = $this->model->get_table_name();
		$primary_key = $this->model->get_primary_key();
		$columns     = $this->model->get_columns();
		$result      = $this->model->select( $key_word, $order_by, $order, $begin_row, $this->rows_per_page );

		$column_names = $this->column_names;

		include( dirname( __FILE__ ) . "/view/list.tpl" );
	}


	/**
	 * 添加数据
	 *
	 */
	public function add_new() {

		$table_name  = $this->model->get_table_name();
		$primary_key = $this->model->get_primary_key();
		$columns     = $this->model->get_columns();
		$new_id      = $this->model->get_new_candidate_id();

		$column_names = $this->column_names;

		include( dirname( __FILE__ ) . "/view/add.tpl" );
	}

	/**
	 * 编辑数据
	 *
	 */
	public function edit() {

		$message = "";
		$status  = "";

		$id = "";
		if ( isset( $_GET[ 'id' ] ) ) {
			$id = $_GET[ 'id' ];
		}
		if ( isset( $_POST[ 'id' ] ) ) {
			$id = $_POST[ 'id' ];
		}

		// 更新
		if ( isset( $_POST[ 'update' ] ) ) {
			if ( $this->model->update( $_POST ) ) {
				$message = "更新成功";
				$status  = "success";
			} else {
				$message = "更新失败";
				$status  = "error";
			}

			// 删除
		} else if ( isset( $_POST[ 'delete' ] ) ) {
			if ( $this->model->delete( $id ) ) {
				$message = "删除成功";
				$status  = "success";
			} else {
				$message = "删除出错";
				$status  = "error";
			}

			// 插入
		} else if ( isset( $_POST[ 'add' ] ) ) {
			$id = $this->model->insert( $_POST );

			if ( "" != $id ) {
				$message = "添加成功!";
				$status  = "success";
			} else {
				$message = "添加出错";
				$status  = "error";
			}
		}

		$table_name  = $this->model->get_table_name();
		$primary_key = $this->model->get_primary_key();
		$columns     = $this->model->get_columns();
		$row         = $this->model->get_row( $id );

		$column_names = $this->column_names;

		include( dirname( __FILE__ ) . "/view/edit.tpl" );
	}

}