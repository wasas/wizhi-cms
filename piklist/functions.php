<?php

  // 添加后台样式
  add_filter('piklist_assets', 'ibio_assets');
   
  function ibio_assets($assets){
    array_push($assets['styles'], array(
      'handle' => 'wizhi-admin-styles'
      ,'src' => get_bloginfo('template_url') . '/piklist/assets/piklist-admin.css'
      ,'ver' => '1.2'
      ,'enqueue' => true
      ,'in_footer' => true
      ,'admin' => true
      ,'media' => 'screen, projection'
    ));
   
    return $assets;
  }


  // 添加自定义权限
	add_filter('option_page_capability_custom_settings', 'wizhi_theme_custom_capability');
	function wizhi_theme_custom_capability() {
		return 'read'; // allow users with the capability of read to SAVE this page.
	}


	//添加插件设置
	add_filter('piklist_admin_pages', 'wizhi_theme_options_pages');
	function wizhi_theme_options_pages($pages){
		$pages[] = array(
			'page_title'  => __( 'CMSONE设置', 'piklist' ),
			'menu_title'  => __( 'CMSONE设置', 'piklist' ),
			'capability'  => 'read',
			'sub_menu'    => 'options-general.php',
			'menu_slug'   => 'wizhi_theme_options',
			'setting'     => 'wizhi_theme_options',
			'menu_icon'   => plugins_url( 'piklist/parts/img/piklist-icon.png' ),
			'page_icon'   => plugins_url( 'piklist/parts/img/piklist-page-icon-32.png' ),
			'default_tab' => 'General',
			'single_line' => true,
			'save_text'   => '保存设置'
		);

		return $pages;
	}


// 获取所有自定义分类法
add_filter('piklist_add_part', 'wizhi_theme_assign_all_public_tax', 10, 2);
function wizhi_theme_assign_all_public_tax($data, $type){

	// if is not term return it
	if($type != 'terms')
	{
		return $data;
	}

    $args = array(
       'public'   => true,
       '_builtin' => false
    );

    $output = 'objects';

    // get all custom taxonomy
    $taxonomies = get_taxonomies($args, $output);

    // get taxonomy names
    foreach ($taxonomies  as $taxonomy)
    {
      $taxonomy_list[] = $taxonomy->name;
    }

    // get piklist need`s string
    $comma_list = implode(', ', $taxonomy_list);

    //Add the default category
	$comma_list .= ', category';

    // add all custom taxonomy to piklist
    $data['taxonomy'] = $comma_list;

  return $data;
}

?>