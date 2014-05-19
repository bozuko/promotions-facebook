<?php

/**
 * Wordpres SEO by Yoast Integration
 */

class PromotionsFacebook_Plugin extends Promotions_Plugin_Base
{
  
  protected $tab_key  = 'facebook';
  protected $tab_text = 'Facebook';
  
  public function is_enabled()
  {
    return Snap::inst('Promotions_Functions')->is_enabled( $this->tab_key, get_the_ID() );
  }
  
  /**
   * @wp.action       promotions/init
   */
  public function promotions_init()
  {
    $this->register_field_groups('promotions-facebook');
    Snap::inst('PromotionsFacebook_Hooks');
  }
  
  /**
   * @wp.filter       promotions/features
   */
  public function add_feature( $features )
  {
    $features[$this->tab_key] = $this->tab_text;
    return $features;
  }
  
  /**
   * @wp.filter       promotions/tabs/promotion/register
   * @wp.priority     50
   */
  public function register_tab( $tabs )
  {
    $tabs[$this->tab_key] = $this->tab_text;
    return $tabs;
  }
  
}