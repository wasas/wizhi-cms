<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class TT_Example_List_Table extends WP_List_Table {

	var $example_data = [
		[
			'ID'       => 1,
			'title'    => '300',
			'rating'   => 'R',
			'director' => 'Zach Snyder',
		],
		[
			'ID'       => 2,
			'title'    => 'Eyes Wide Shut',
			'rating'   => 'R',
			'director' => 'Stanley Kubrick',
		],
		[
			'ID'       => 3,
			'title'    => 'Moulin Rouge!',
			'rating'   => 'PG-13',
			'director' => 'Baz Luhrman',
		],
		[
			'ID'       => 4,
			'title'    => 'Snow White',
			'rating'   => 'G',
			'director' => 'Walt Disney',
		],
		[
			'ID'       => 5,
			'title'    => 'Super 8',
			'rating'   => 'PG-13',
			'director' => 'JJ Abrams',
		],
		[
			'ID'       => 6,
			'title'    => 'The Fountain',
			'rating'   => 'PG-13',
			'director' => 'Darren Aronofsky',
		],
		[
			'ID'       => 7,
			'title'    => 'Watchmen',
			'rating'   => 'R',
			'director' => 'Zach Snyder',
		],
		[
			'ID'       => 8,
			'title'    => '2001',
			'rating'   => 'G',
			'director' => 'Stanley Kubrick',
		],
	];

	//  $data, $sort, $args
	function __construct() {
		global $status, $page;

		// 设置父级类默认值
		parent::__construct( [
			'singular' => 'movie',
			'plural'   => 'movies',
			'ajax'     => false,
		] );

		$this->show();

		add_action( 'admin_menu', [ $this, 'menu' ] );

	}


	// 设置数据列默认值
	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'rating':
			case 'director':
				return $item[ $column_name ];
			default:
				return false;
		}
	}


	// 数据列标题
	function column_title( $item ) {

		// 行操作
		$actions = [
			'edit'   => sprintf( '<a href="?page=%s&action=%s&movie=%s">编辑</a>', $_REQUEST[ 'page' ], 'edit', $item[ 'ID' ] ),
			'delete' => sprintf( '<a href="?page=%s&action=%s&movie=%s">删除</a>', $_REQUEST[ 'page' ], 'delete', $item[ 'ID' ] ),
		];

		// 标题内容
		return sprintf( '%1$s <span style="color:silver">(id:%2$s)</span>%3$s', $item[ 'title' ], $item[ 'ID' ], $this->row_actions( $actions ) );
	}


	// 显示批量操作选框
	function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args[ 'singular' ], $item[ 'ID' ] );
	}


	// 获取数据列
	function get_columns() {
		$columns = [
			'cb'       => '<input type="checkbox" />',
			'title'    => 'Title',
			'rating'   => 'Rating',
			'director' => 'Director',
		];

		return $columns;
	}


	// 获取可排序数据列
	function get_sortable_columns() {
		$sortable_columns = [
			'title'    => [ 'title', false ],
			'rating'   => [ 'rating', false ],
			'director' => [ 'director', false ],
		];

		return $sortable_columns;
	}


	// 获取批量操作
	function get_bulk_actions() {
		$actions = [
			'delete' => 'Delete',
		];

		return $actions;
	}


	// 批量操作动作
	function process_bulk_action() {

		// Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {
			wp_die( 'Items deleted (or they would be if we had items to delete)!' );
		}

	}


	// 准备需要显示的条目数据
	function prepare_items() {
		global $wpdb;

		$per_page = 5;

		$columns  = $this->get_columns();
		$hidden   = [ ];
		$sortable = $this->get_sortable_columns();


		$this->_column_headers = [ $columns, $hidden, $sortable ];


		$this->process_bulk_action();


		$data = $this->example_data;

		function usort_reorder( $a, $b ) {
			$orderby = ( ! empty( $_REQUEST[ 'orderby' ] ) ) ? $_REQUEST[ 'orderby' ] : 'title';
			$order   = ( ! empty( $_REQUEST[ 'order' ] ) ) ? $_REQUEST[ 'order' ] : 'asc';
			$result  = strcmp( $a[ $orderby ], $b[ $orderby ] );

			return ( $order === 'asc' ) ? $result : - $result;
		}

		usort( $data, 'usort_reorder' );


		$current_page = $this->get_pagenum();

		$total_items = count( $data );

		$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

		$this->items = $data;

		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page ),
		] );
	}


	// 显示数据
	function show() {

		$this->prepare_items();

		?>
		<div class="wrap">
			<form id="movies-filter" method="get">
				<input type="hidden" name="page" value="<?php echo $_REQUEST[ 'page' ] ?>"/>
				<?php $this->display() ?>
			</form>
		</div>
		<?php

	}


	// 添加菜单项目
	function menu() {
		add_menu_page( 'Example Plugin List Table', 'List Table Example', 'activate_plugins', 'tt_list_test', [ $this, 'show', ] );
	}

}