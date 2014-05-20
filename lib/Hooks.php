<?php

class PromotionsFacebook_Hooks extends Snap_Wordpress_Plugin
{
  
  /**
   * @wp.action       snap/form/field/register
   */
  public function register_form_field( $form2 )
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('facebook') ) return;
    $form2->register('PromotionsFacebook_Form_Field_FacebookId');
  }
  
  /**
   * @wp.action       promotions/process
   */
  public function redirect_to_tab()
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('facebook') ) return;
    if( $_SERVER['REQUEST_METHOD'] !== 'GET' ) return;
    if( !get_field('fb_tab_redirection') ) return;
    if( !($url = get_field('fb_tab_url')) ) return;
    
    if( Snap::inst('Mobile_Detect')->isMobile() ) return;
    
    
    wp_redirect($url);
    exit;
  }
  
  /**
   * @wp.action       promotions/process
   * @wp.priority     0
   */
  public function init_facebook()
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('facebook') ) return;
    
    // We need this get IE to work 
    header("p3p: CP=\"ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV\"");
    
    Snap::inst('PromotionsFacebook_Functions')->init(
      get_field('fb_app_id'), get_field('fb_app_secret')
    );
  }
  
  /**
   * @wp.action       promotions/content/template
   */
  public function template( $template )
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('facebook') ) return $template;
    
    if( get_field('fb_like_gate') && Snap::inst('PromotionsFacebook_Functions')->is_tab() && !Snap::inst('PromotionsFacebook_Functions')->is_liked() ){
      return 'fb-likegate';
    }
    
    $user = Snap::inst('PromotionsFacebook_Functions')->get_user();
    
    if( !$user ) return 'fb-login';
    
    if( $template == 'register' ){
      // we can try to prepopulate some of this information
      $form = Snap::inst('Promotions_PostType_Promotion')->get_registration_form( get_the_ID() );
      
      if( Snap::inst('Promotions_Functions')->is_enabled('returnuser') ){
        $key = get_field('registration_key_field');
        if( is_a( $form->get_field($key), 'PromotionsFacebook_Form_Field_FacebookId' ) ){
          $return_user = Snap::inst('Promotions_Core_ReturnUser_Plugin');
          if( ($id = $return_user->get_registration_id( $user->getProperty('id') )) ){
            if( !$return_user->can_enter($id) ){
              return 'already-entered';
            }
          }
        }
      }
      /*
      if( ($first_name = $form->get_field('first_name')) && !$first_name->get_value() ){
        $first_name->set_value( $user->getProperty('first_name') );
      }
      if( ($last_name = $form->get_field('last_name')) && !$last_name->get_value() ){
        $last_name->set_value( $user->getProperty('last_name') );
      }
      if( ($email = $form->get_field('email')) && !$email->get_value() ){
        $email->set_value( $user->getProperty('email') );
      }
      */
    }
    
    
    
    return $template;
  }
}
