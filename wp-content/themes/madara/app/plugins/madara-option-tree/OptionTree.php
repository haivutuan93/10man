<?php

	namespace App\Plugins\madara_Option_Tree;

	class OptionTree {

		public function __construct() {

		}

		public function insertRow() {
			$html = '';

			$html .= '<div id="setting_less_compiler" class="format-settings">';
			$html .= '<div class="format-setting-wrap">';

			$html .= '<div class="format-setting-label">';
			$html .= '<h3 class="label">' . esc_html__( 'LESS Compiler', 'madara' ) . '</h3>';
			$html .= '</div>';

			$html .= '<div class="format-setting type-radio has-desc">';

			$html .= '<div class="description">' . esc_html__( 'Compile LESS into .css files, used for production', 'madara' ) . '</div>';
			$html .= '<div class="format-setting-inner">';

			$html .= $this->insertCompiler();

			$html .= '</div>';

			$html .= '</div>';

			$html .= '<div id="notification" class="notification"></div>';

			$html .= '</div>';
			$html .= '</div>';

			return $html;
		}

		public function insertCompiler() {
			return '<input id="less_compiler" type="button" class="option-tree-ui-button button button-primary pull-left" value="Compile"/>';
		}

	}
