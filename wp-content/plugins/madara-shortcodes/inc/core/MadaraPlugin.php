<?php
/**
 * @package Madara
 * @version 1.0
 */
/*

Description: Base plugin class
Author: Madara
Version: 1.0
Author URI: http://www.madara.com
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class MadaraPlugin {
    /* custom template relative url in theme, default is "ct-portfolio" */
    public $template_url;
    
    /* Plugin path */
    public $plugin_path;
    
    /**
     * Name of plugin option page
     */
    public $plugin_option_page;
    
    /* Get main options of the plugin. If there are any sub options page, pass Options Page Id to the second args
     * @return null of value not set or option does not exist
     *
     */
    public function get_option($option_name, $op_id = ''){
        $options = $GLOBALS[$op_id != '' ? $op_id : $this->plugin_option_page];
        
        if(isset($options))
            return $options->get($option_name);
        
        return null;
    }
    
    /** 
     * Locate template file
     * @param $templates - array - Templates to locate
     * @param $file - string - If not any template found, return detault file template
     */
    public function locate_template($templates, $file = ''){
        $template = locate_template( $templates );
        
        if ( ! $template && $file ) {
            $template = $this->plugin_path() . '/templates/' . $file;
        }
        
        return $template;
    }
    
    /**
     * Include template part
     * @param $template - string - name of template
     * @param $context - array - Context variables to pass to the template
     */
    public function get_template_part($template, $madara_context = array() ) {
        $file = $this->locate_template( array( $this->template_url . $template . '.php' ), $template . '.php' );
        
        if( $file && file_exists($file) ) {
            include $file;
        } else {
            echo $file . ' not found';
        }
    }
}