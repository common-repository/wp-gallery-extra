<?php

// extend walker for wp_terms_checklist
class Walker_WP_Media_Taxonomy_Checklist extends Walker {
	
	public $tree_type = 'category';
	public $db_fields = array ('parent' => 'parent', 'id' => 'term_id');
	public $post_id   = 0;
	
	
	public function __construct($post_id = false) {
		if(empty($post_id)){
			return false;
		}
		$this->post_id = $post_id;
	}
	
	/**
	 * Starts the list before the elements are added
	 * @since 1.0.5
	 */
	public function start_lvl( &$output, $depth = 0, $args = array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children'>\n";
	}
	
	/**
	 * Ends the list of after the elements are added
	 * @since 1.0.5
	 */
	public function end_lvl( &$output, $depth = 0, $args = array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}
	
	/**
	 * Start the element output
	 * wp-includes/category-template.php
	 * @since 1.0.5
	 */
	public function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0) {
		
		if (empty($args['taxonomy'])) {
			$taxonomy = 'category';
		} else {
			$taxonomy = $args['taxonomy'];
		}

		$name  = 'tax_input['.esc_attr($taxonomy).']['.esc_attr($category->slug).']';
		$class = in_array($category->term_id, $args['popular_cats']) ? ' class="popular-category"' : '';
		
		$args['popular_cats']  = empty( $args['popular_cats'] ) ? array() : $args['popular_cats'];
		$args['selected_cats'] = empty( $args['selected_cats'] ) ? array() : $args['selected_cats'];
		
		if (!empty($args['list_only'])) {
			$aria_cheched = 'false';
			$inner_class  = 'category';
			if (in_array($category->term_id, $args['selected_cats'])) {
				$inner_class .= ' selected';
				$aria_cheched = 'true';
			}
			$output .= '<li'.$class.'>';
			$output .= '<div class="'.$inner_class.'" data-term-id='.$category->term_id.' tabindex="0" role="checkbox" aria-checked="'.$aria_cheched.'">';
			$output .= esc_html(apply_filters('the_category', $category->name));
			$output .= '</div>';		
		} else {
			$checked  = checked(in_array( $category->term_id, $args['selected_cats']), true, false);
			$disabled = disabled(empty($args['disabled']), false, false);
			$output .= '<li id="'.$taxonomy.'-'.$category->term_id.'"'.$class.'>';
			$output .= '<label class="selectit"><input value="'.$category->term_id.'" type="checkbox" name="'.$name.'" id="in-'.$taxonomy.'-'.$category->term_id.'" '.$checked.' '.$disabled.'/>';
			$output .= esc_html(apply_filters('the_category', $category->name));
			$output .= '</label>';
		}
		
	}
	
	/**
	 * Ends the element output, if needed
	 * @since 1.0.5
	 */
	public function end_el( &$output, $category, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}
	
}
?>