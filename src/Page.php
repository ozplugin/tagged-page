<?php

/**
 * @author    Demo <demo@demo.demo>
 * @link      https://demo.com/
 * @copyright 2024 Demo
 * @ver 1.0.0
 */

namespace Demo\TaggedPage;

use WP_Query;

if ( ! defined('ABSPATH') ) {
    exit;
}


/**
 * Page class
 */
class Page {

    /**
     * Page ID
     *
     * @var int
     */
    public $id = 0;
    /**
     * Constructor
     *
     * @param  int $post_id Page ID
     * @return void
     */
    public function __construct( $post_id = 0 ) {
        if ( $post_id ) {
            $this->id = (int) ( $post_id );
        }
    }
    /**
     * Get all pages using ElasticPress integration
     *
     * @param  array $terms Page tags
     * @return array
     */
    public function get( $terms = array() ) {
        $pages    = array();
        $tag_name = Tags::instance()->get_name();
        $args     = array(
            'post_type'      => 'page',
            'posts_per_page' => -1, // todo make pagination
            'ep_integrate'   => true, // elasticpress
            'post__not_in'   => array( $this->id ), // exclude current post
        );
        if ( ! empty($terms) ) {
            $args['tax_query'] = array(
				array(
					'taxonomy' => $tag_name,
					'terms'    => $terms,
				),
			);
        }
        $related = get_post_meta($this->id, 'dd_related_posts', true);
        $posts   = new WP_Query($args);
        if ( $posts->have_posts() ) {
            while ( $posts->have_posts() ) {
                $posts->the_post();
                $id      = get_the_ID();
                $pages[] = array(
                    'ID'      => $id,
                    'name'    => get_the_title(),
                    'tags'    => Tags::instance()->get_tags_by_post_id($id, true),
                    'related' => $related && is_array($related) && in_array($id, $related, true),
                );
            }
        }
        wp_reset_postdata();
        return $pages;
    }

    /**
     * Set tags for page
     *
     * @param  array $terms Array with terms ids
     * @return array
     */
    public function set_terms( $terms ) {
        $tags = wp_set_post_terms($this->id, $terms, Tags::instance()->get_name());
        return $tags;
    }

    /**
     * Store ids of pages related with current
     *
     * @param  int $relation_id Page ID
     * @return array
     */
    public function set_relation( $relation_id ) {
        $relation_ids = get_post_meta($this->id, 'dd_related_posts', true);
        if ( ! $relation_ids ) {
            $relation_ids = array( $relation_id );
        } else {
            $key = array_search($relation_id, $relation_ids, true);
            if ( false !== $key ) {
                unset($relation_ids[ $key ]);
            } else {
                $relation_ids[] = $relation_id;
            }
        }
        update_post_meta($this->id, 'dd_related_posts', $relation_ids);
        return $relation_ids;
    }

    /**
     * Class instance
     *
     * @param  int $post_id Page ID
     * @return Demo\TaggedPage\Page
     */
    public static function instance( $post_id = 0 ) {
        return new self($post_id);
    }
}
