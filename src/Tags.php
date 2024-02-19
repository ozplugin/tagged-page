<?php

/**
 * @author    Demo <demo@demo.demo>
 * @link      https://demo.com/
 * @copyright 2024 Demo
 * @ver 1.0.0
 */

namespace Demo\TaggedPage;

if ( ! defined('ABSPATH') ) {
    exit;
}

/**
 * Tags class
 */
class Tags {


    /**
     * Taxonomy name
     *
     * @var string
     */
    private $name = 'd-tag';

    /**
     * Register tags taxonomy for pages
     *
     * @return void
     */
    public function register() {
        register_taxonomy(
            $this->name,
            'page',
            array(
				'hierarchical'      => false,
				'labels'            => array(
					'name'                       => __('Tags', 'ddemo'),
					'singular_name'              => __('Tag', 'ddemo'),
					'search_items'               => __('Search tags', 'ddemo'),
					'all_items'                  => __('All tags', 'ddemo'),
					'parent_item'                => __('Parent tag', 'ddemo'),
					'parent_item_colon'          => __('Parent tag:', 'ddemo'),
					'edit_item'                  => __('Edit tag', 'ddemo'),
					'update_item'                => __('Refresh tag', 'ddemo'),
					'add_new_item'               => __('Add new tag', 'ddemo'),
					'new_item_name'              => __('New tag name', 'ddemo'),
					'menu_name'                  => __('Tags', 'ddemo'),
					'popular_items'              => __('Popular tags', 'ddemo'),
					'separate_items_with_commas' => __('Tags separated by comma', 'ddemo'),
					'not_found'                  => __('Tags not found.', 'ddemo'),
				),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'd-tag' ),
            )
        );
    }

    /**
     * Get tags
     *
     * @param  array $params taxonomy params
     * @return WP_Term[]|int|WP_Error
     */
    public function get_tags( $params = array() ) {
        $args = array_merge(
            array(
				'taxonomy'   => $this->name,
				'hide_empty' => false,
            ),
            $params
        );
        $tags = get_terms($args);
        return $tags;
    }

    /**
     * Formatting list of tags to [label, value] format
     *
     * @param  array $params Taxonomy params
     * @return array
     */
    public function get_tags_json( $params = array() ) {
        $tags      = $this->get_tags($params);
        $json_tags = array();
        if ( ! is_wp_error($tags) && ! empty($tags) ) {
            foreach ( $tags as $tag ) {
                $json_tags[] = self::toFormat($tag->name, $tag->term_id);
            }
        }
        return $json_tags;
    }

    /**
     * Convert label and value vars format to array
     *
     * @param  mixed $label Label
     * @param  mixed $value Value
     * @return array
     */
    public static function toFormat( $label, $value ) {
        return array(
            'label' => $label,
            'value' => $value,
        );
    }

    /**
     * Get taxonomy name
     *
     * @return array
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Class instance
     *
     * @return Demo\TaggedPage\Tags
     */
    public static function instance() {
        return new self();
    }

    /**
     * Get tags by post id
     *
     * @param  int  $post_id Post ID
     * @param  bool $formatting Return in [label, value] format or not
     * @return array
     */
    public function get_tags_by_post_id( $post_id = 0, $formatting = false ) {
        if ( ! $post_id ) {
            global $post;
            if ( ! $post && ! isset($post->ID) ) {
                return array();
            } else {
                $post_id = $post->ID;
            }
        }
        $tags       = array();
        $tags_terms = wp_get_post_terms($post_id, $this->get_name());
        if ( ! is_wp_error($tags_terms) && $tags_terms ) {
            if ( $formatting ) {
                foreach ( $tags_terms as $term ) {
                    $tags[] = self::toFormat($term->name, $term->term_id);
                }
            } else {
                $tags = $tags_terms;
            }
        }
        return $tags;
    }
}
