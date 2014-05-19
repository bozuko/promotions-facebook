<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
    'id' => 'acf_promotions-facebook',
    'title' => 'Promotions Facebook',
    'fields' => array (
      array (
        'key' => 'field_537384d619377',
        'label' => 'Facebook Application ID',
        'name' => 'fb_app_id',
        'type' => 'text',
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'formatting' => 'html',
        'maxlength' => '',
      ),
      array (
        'key' => 'field_537384e019378',
        'label' => 'Facebook Application Secret',
        'name' => 'fb_app_secret',
        'type' => 'text',
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'formatting' => 'html',
        'maxlength' => '',
      ),
      array (
        'key' => 'field_537391239e98f',
        'label' => 'Tab Redirection',
        'name' => 'fb_tab_redirection',
        'type' => 'true_false',
        'message' => 'Enable Facebook Tab Redirection',
        'default_value' => 0,
      ),
      array (
        'key' => 'field_537391529e990',
        'label' => 'Facebook Tab Url',
        'name' => 'fb_tab_url',
        'type' => 'text',
        'conditional_logic' => array (
          'status' => 1,
          'rules' => array (
            array (
              'field' => 'field_537391239e98f',
              'operator' => '==',
              'value' => '1',
            ),
          ),
          'allorany' => 'all',
        ),
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'formatting' => 'html',
        'maxlength' => '',
      ),
      array (
        'key' => 'field_5379550087995',
        'label' => 'Facebook Like Gate',
        'name' => 'fb_like_gate',
        'type' => 'true_false',
        'message' => 'Enable Like Gate on Facebook Tab',
        'default_value' => 0,
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'promotion_tab',
          'operator' => '==',
          'value' => 'facebook',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),
    ),
    'options' => array (
      'position' => 'normal',
      'layout' => 'default',
      'hide_on_screen' => array (
      ),
    ),
    'menu_order' => 0,
  ));
}
    