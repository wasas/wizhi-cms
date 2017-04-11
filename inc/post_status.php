<?php

/**
 * 添加自定义文章状态
 *
 */
class WizhiPostStatus {
	/**
	 * 文章状态别名
	 *
	 * @var string
	 */
	protected $post_status;

	/**
	 * 支持的文章类型
	 *
	 * @var array
	 */
	protected $post_types = [ "post" ];

	/**
	 * 文章状态操作名称
	 *
	 * @var string
	 */
	protected $action_label;

	/**
	 * 文章状态标签名称
	 *
	 * @var string
	 */
	protected $applied_label;

	/**
	 * 传递到 register_post_status() 函数的参数
	 *
	 * @var array
	 */
	protected $args = [];

	/**
	 * WizhiPostStatus constructor.
	 *
	 * @param  string $post_status 文章状态
	 * @param  array  $post_types  支持的文章类型
	 * @param  array  $args        文章状态参数，参考：http://codex.wordpress.org/Function_Reference/register_post_status
	 */
	public function __construct( $post_status, $post_types, $args ) {
		$this->post_status   = $post_status;
		$this->post_types    = $post_types;
		$this->action_label  = isset( $args[ "label" ] ) ? $args[ "label" ] : $post_status;
		$this->applied_label = isset( $args[ "applied_label" ] ) ? $args[ "applied_label" ] : $this->action_label;
		$this->args          = $args;

		// get rid of elements that don't belong in the args for register_post_status
		unset( $this->args[ "applied_label" ] );

		// set a default label count
		if ( ! isset( $this->args[ "label_count" ] ) ) {
			$this->args[ "label_count" ] = _n_noop( "{$this->applied_label} <span class=\"count\">(%s)</span>", "{$this->applied_label} <span class=\"count\">(%s)</span>" );
		}

		// setup the actions
		add_action( "init", [ $this, "register_post_status" ] );
		add_action( "admin_footer-post.php", [ $this, "append_to_post_status_dropdown" ] );
		add_action( "admin_footer-edit.php", [ $this, "append_to_inline_status_dropdown" ] );
		add_filter( "display_post_states", [ $this, "update_post_status" ] );
	}

	/**
	 * 注册自定义文章状态
	 */
	public function register_post_status() {
		register_post_status( $this->post_status, $this->args );
	}

	/**
	 * 添加文章状态到编辑文章状态的下拉菜单
	 *
	 */
	public function append_to_post_status_dropdown() {
		global $post;
		$selected = "";
		$label    = "";

		if ( in_array( $post->post_type, $this->post_types ) ) {

			if ( $post->post_status === $this->post_status ) {
				$selected = " selected=\"selected\"";
				$label    = "<span id=\"post-status-display\"> {$this->applied_label}</span>";
			}

			echo "
		  <script>
		  jQuery(document).ready(function ($){
		       $('select#post_status').append('<option value=\"{$this->post_status}\"{$selected}>{$this->action_label}</option>');
		       $('.misc-pub-section label').append('{$label}');
		  });
		  </script>";
		}
	}

	/**
	 * 添加文章状态到快速编辑文章状态的下拉菜单
	 *
	 */
	public function append_to_inline_status_dropdown() {
		global $post;

		// no posts
		if ( ! $post ) {
			return;
		}

		if ( in_array( $post->post_type, $this->post_types ) ) {

			echo "
			<script>
			jQuery(document).ready(function ($){
				$('.inline-edit-status select').append('<option value=\"{$this->post_status}\">{$this->action_label}</option>');
			});
			</script>
			";

		}
	}

	/**
	 * 添加文章状态到文章列表
	 *
	 * @return null
	 */
	public function update_post_status( $states ) {
		global $post;

		$status = get_query_var( "post_status" );

		if ( $status !== $this->post_status && $post->post_status === $this->post_status ) {
			return [ $this->applied_label ];
		}

		return $states;
	}
}