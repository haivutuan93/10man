<?php

    class MadaraShortcode
    {
        public $id = '';

        public $attributes = array();

        public $tag = '';

        public $content = '';
        
        protected $meta;
        
        protected $thumbnail;

        public function __construct($tag, $attrs = NULL, $content = '')
        {
            $this->tag = $tag;

            
            if (isset($attrs)) {
                $this->attributes = $attrs;
                $this->content    = $content;
            } else {
                add_action('init', array($this, 'init'));
            }
        }
        
        function init(){
            if( ! class_exists('App\Madara') ){
                return;
            }

            $this->meta      = new App\Views\ParseMeta();
            $this->thumbnail = new App\Views\ParseThumbnail();
            
            
            add_shortcode($this->tag, array($this, 'renderShortcode'));
        }


        public function generate_id()
        {
            $id       = 'c-custom-' . rand(0, 1000) . time();
            $this->id = $id;
        }

        /**
         * print shortcode output
         *
         * $attritube_only - to print out attributes string only
         *
         * @return string
         */

        public function toString($attritube_only = false)
        {
            $attrs = '';

            if ($this->id == '') {
                $this->generate_id();
            }

            $tag = '[' . $this->tag;

            $attrs .= ' id="' . $this->id . '"';

            if (isset($this->attributes) && is_array($this->attributes)) {
                foreach ($this->attributes as $key => $val) {
                    if ($key != 'id') {
                        $attrs .= ' ' . $key . '="' . $val . '"';
                    }
                }
            }

            $tag .= $attrs;

            $tag .= ']';

            if ($this->content != '') {
                $tag .= $this->content . '[/' . $this->tag . ']';
            }

            if ($attritube_only) return $attrs;

            return $tag;
        }

        /**
         * Do shortcode
         */
        protected function renderShortcode($atts, $content)
        {

        }

        /**
         * if shortcode has some attritubes that need to be put in custom css, put it here
         *
         * @return string
         */

        public function inlineCSSGenerator($attrs = array())
        {
        }
        
        public static function implode_props( $props ){
            $str_property = '';
            
            foreach( $props as $key => $value ){
                $str_property .= $key . '="' . esc_attr($value) . '" ';
            }
            
            return $str_property;
        }

    }