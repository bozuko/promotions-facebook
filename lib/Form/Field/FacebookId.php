<?php

/**
 * @field.name      facebook_id
 * @field.label     Facebook ID
 */
class PromotionsFacebook_Form_Field_FacebookId extends Snap_Wordpress_Form2_Field_Abstract
{
  protected $style = 'hidden';
  
  public function init()
  {
    // this automatically sets Facebook ID
    $user = Snap::inst('PromotionsFacebook_Functions')->get_user();
    if( $user ){
      $this->set_value( $user->getProperty('id') );
    }
  }
  
  public function get_html()
  {
    $attrs = array(
        'name'  => $this->get_name()
      , 'type'  => 'hidden'
      , 'id'    => $this->get_id()
      , 'class' => $this->get_classes()
      , 'title' => $this->get_config('label')
      , 'value' => $this->get_value()
    );
    
    $attrs = $this->apply_filters('attributes', $attrs);
    
    $html = Snap_Util_Html::tag( 'input', $attrs );
    return $this->apply_filters('html', $html);
  }
}
