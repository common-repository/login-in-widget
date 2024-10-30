<?php
/**
 * @package Login_in_widget
 * @version 1.2.0
 */
/*
  Plugin Name: Login in Widget
  Plugin URI: http://www.mimtel.it
  Description: Displays a login in a widget
  Version: 1.2.0
  Author: Luca Preziati
  Author URI: http://www.mintel.it
  License: GPL2
 */

/*  Copyright 2013  Luca Preziati(luca.preziati@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * page_in_widget_Widget Class
 */
class login_in_widget_Widget extends WP_Widget {
  static $domain =  'login_in_widget';
  static $content_default_open = '<ul class="ulTopHeader">';
  static $content_default_close = '</ul>';
  static $decorator_default_open = '<li class="liTopHeader">';
  static $decorator_default_close = '</li>';
	/** constructor */
	function login_in_widget_Widget() {
          
    parent::WP_Widget(false, 'Login in widget', array('description' => 'Displays the login and register url or access to profile and logout url in a widget'));
	      
    load_plugin_textdomain(self::$domain, '/wp-content/plugins/login-in-widget/lang' );

  }

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		echo $before_widget;

		if ($title) {
			echo $before_title . $title . $after_title;
		}          

		$content = $this->get_header_widget($instance['contentStyleOpen'],$instance['decoratorStyleOpen'],$instance['decoratorStyleClose'],$instance['contentStyleClose']);

		echo $content;

		echo $after_widget;
	}

	/** @see WP_Widget::form */
	function form($instance) {
    $contentStyleOpen = self::$content_default_open;
    if (isset($instance['contentStyleOpen'])) {
      $contentStyleOpen = $instance['contentStyleOpen'];
    }
    
    $contentStyleClose = self::$content_default_close;
    if (isset($instance['contentStyleClose'])) {
      $contentStyleClose = $instance['contentStyleClose'];
    }
    
    $decoratorStyleOpen = self::$decorator_default_open;
    if (isset($instance['decoratorStyleOpen'])) {
      $decoratorStyleOpen = $instance['decoratorStyleOpen'];
    }
    $decoratorStyleClose = self::$decorator_default_close;
    if (isset($instance['decoratorStyleClose'])) {
      $decoratorStyleClose = $instance['decoratorStyleClose'];
    }
    
    $contentStyleOpen = esc_attr($contentStyleOpen);
    $contentStyleClose = esc_attr($contentStyleClose);
    $decoratorStyleOpen = esc_attr($decoratorStyleOpen);
    $decoratorStyleClose = esc_attr($decoratorStyleClose);
?>    
    <label for="<?php echo $this->get_field_id('contentStyleOpen'); ?>">Content Open Style</label>
    <input class="widefat" id="<?php echo $this->get_field_id('contentStyleOpen'); ?>" name="<?php echo $this->get_field_name('contentStyleOpen'); ?>" type="text" value="<?php echo $contentStyleOpen; ?>" />

    <label for="<?php echo $this->get_field_id('decoratorStyleOpen'); ?>">Decorator Open Style</label>
    <input class="widefat" id="<?php echo $this->get_field_id('decoratorStyleOpen'); ?>" name="<?php echo $this->get_field_name('decoratorStyleOpen'); ?>" type="text" value="<?php echo $decoratorStyleOpen; ?>" />

    <label for="<?php echo $this->get_field_id('decoratorStyleClose'); ?>">Decorator Close Style</label>
    <input class="widefat" id="<?php echo $this->get_field_id('decoratorStyleClose'); ?>" name="<?php echo $this->get_field_name('decoratorStyleClose'); ?>" type="text" value="<?php echo $decoratorStyleClose; ?>" />

    <label for="<?php echo $this->get_field_id('contentStyleClose'); ?>">Content Close Style</label>
    <input class="widefat" id="<?php echo $this->get_field_id('contentStyleClose'); ?>" name="<?php echo $this->get_field_name('contentStyleClose'); ?>" type="text" value="<?php echo $contentStyleClose; ?>" />

<?php
	}
    
  function createLink($link,$title,$description,$class = null){
            if($class == null || $class =='')
              return $output.='<a href="'.$link.'" title="'.$title.'">'.$description.'</a>';
            else
              return $output.='<a href="'.$link.'" title="'.$title.'" class="'.$class.'">'.$description.'</a>';
   }
	/* Local version of get_the_content function,
	 * adapted to suit the widget
	 */
	function get_header_widget($contentStyleOpen="",$decoratorStyleOpen="",$decoratorStyleClose="",$contentStyleClose="") {
   // add_action('wp_enqueue_scripts', 'login_in_widget_styles');  
    if(!empty ($contentStyleOpen)){
      $output.=$contentStyleOpen;
    }
    
    if(!empty ($decoratorStyleOpen)){
      $output.=$decoratorStyleOpen;
    }
    if ( !is_user_logged_in() ){
     $output.=$this->createLink(wp_login_url( get_permalink()),__("TitleLogin",self::$domain),__("Login",self::$domain));
    if(!empty ($decoratorStyleOpen)){
	     $output.=$decoratorStyleClose.$decoratorStyleOpen;
     }
		 $output.= wp_register('', '',false);
    } else{
     $currentUser = wp_get_current_user();
	   $name = __("Welcome",self::$domain)." ".$currentUser->user_login; 
     $output.= $this->createLink(admin_url( 'profile.php' ),__("TitleProfileModify",self::$domain),$name);
     if(!empty ($decoratorStyleOpen)){
       $output.=$decoratorStyleClose.$decoratorStyleOpen;
     }
		 $output.= $this->createLink(wp_logout_url( get_permalink() ),__("TitleLogout",self::$domain),__("Logout",self::$domain));
		}
    if(!empty ($decoratorStyleClose)){
		  $output.=$decoratorStyleClose;
    }
    if(!empty ($contentStyleClose)){
      $output.=$contentStyleClose;
	  }
  return $output;
  }
}

function login_in_widget_styles(){ 
  // Register the style like this for a theme:  
  // (First the unique name for the style (custom-style) then the src, 
  // then dependencies and ver no. and media type)
    
    wp_register_style( login_in_widget_Widget::$domain, plugins_url() .'/login-in-widget/css/login-in-widget.css');
    // enqueing:
    wp_enqueue_style( login_in_widget_Widget::$domain );
  }
function login_in_widget_init(){
    register_widget("login_in_widget_Widget");
}
// class page_in_widget_Widget
// register page_in_widget

add_action('widgets_init', 'login_in_widget_init');
add_action('wp_enqueue_scripts', 'login_in_widget_styles');


