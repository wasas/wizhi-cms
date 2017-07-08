<?php

namespace Wizhi\Metabox;

use Illuminate\View\View;

interface IMetabox
{
    /**
     * Build a metabox instance.
     *
     * @param string                $title    The metabox title
     * @param string                $postType The post type name where the metabox is displayed
     * @param array                 $options  Metabox parameters (id, context, priority,...)
     * @param \Illuminate\View\View $view     A custom view used by the metabox to render
     *
     * @return \Wizhi\Metabox\IMetabox
     */
    public function make($title, $postType, array $options = array(), View $view = null);

    /**
     * Register the metabox to WordPress.
     *
     * @param array $fields A list of custom fields to assign.
     *
     * @return \Wizhi\Metabox\IMetabox
     */
    public function set(array $fields = array());

    /**
     * Define a custom capability to check before adding the metabox.
     *
     * @param string $capability
     *
     * @return \Wizhi\Metabox\IMetabox
     */
    public function can($capability);

    /**
     * Define a list of validation rules to execute on metabox fields.
     *
     * @param array $rules
     *
     * @return \Wizhi\Metabox\IMetabox
     */
    public function validate(array $rules = array());

    /**
     * Pass custom data to the metabox view.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return \Wizhi\Metabox\IMetabox
     */
    public function with($key, $value = null);
}
