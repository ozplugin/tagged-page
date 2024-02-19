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
 * Base class
 */
class Base {

    /**
     * Tags object
     *
     * @var Tags
     */
    private $tags = null;

    /**
     * Ajax requests
     *
     * @var Ajax
     */
    private $ajax = null;
    /**
     * Plugin initialization
     *
     * @return void
     */
    public function init() {
        $this->tags = new Tags();
        $this->tags->register();
        $this->ajax = new Ajax();
        $this->ajax->register();
        $this->register_post_meta();
        add_filter('ep_find_related_args', array( $this, 'change_query' )); // change elasticpress query
        add_action('enqueue_block_editor_assets', array( $this, 'enqueue_scripts' ));
    }

    /**
     * Add React script
     *
     * @return void
     */
    public function enqueue_scripts() {
        if ( $this->is_edit_page_screen() ) {
            wp_enqueue_style('ddemo-style', DDEMO_URL . 'dist/style-index.css', array(), DDEMO_VER);
            wp_enqueue_script('ddemo-script', DDEMO_URL . 'dist/index.js', array( 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor' ), DDEMO_VER, true);
            $ddemo_vars = apply_filters('ddemo_js_options', $this->js_options());
            wp_localize_script('ddemo-script', 'ddemo_vars', $ddemo_vars);
        }
    }

    /**
     * Is block editor page for post type 'page'
     *
     * @return bool
     */
    public function is_edit_page_screen() {
        $screen = function_exists('get_current_screen') ? get_current_screen() : false;
        return $screen && 'page' == $screen->id && $screen->is_block_editor && 'page' == $screen->post_type;
    }

    /**
     * Plugin JS options
     *
     * @return array
     */
    public function js_options() {
        global $post;
        $opts = array(
            'id'        => get_post_meta($post->ID, 'dd_related_posts', true),
            'admin_url' => admin_url(),
            'ajax_url'  => admin_url('admin-ajax.php'),
            'lang'      => get_locale(),
            'nonce'     => wp_create_nonce('ddemo'),
            'site_url'  => site_url(),
            'tag_name'  => $this->tags->get_name(),
            'tags'      => $this->tags->get_tags_json(),
            'post_tags' => $this->tags->get_tags_by_post_id(false, true),
        );
        return $opts;
    }

    /**
     * Register post meta for related posts
     *
     * @return void
     */
    public function register_post_meta() {
        register_post_meta(
            'page',
            'dd_related_posts',
            array(
				'single'       => false,
				'type'         => 'array',
				'show_in_rest' => array(
					'schema' => array(
						'type'  => 'array',
						'items' => array(
							'type' => 'number',
						),
					),
				),
            )
        );
    }

    /**
     * Change ElasticPress query to find related pages using 'dd_related_posts' post meta data
     *
     * @param  mixed $args WP_Query args
     * @return array
     */
    public function change_query( $args ) {
        if ( isset($args['more_like']) ) {
            $id        = (int) ( $args['more_like'] );
            $post_type = get_post_type($id);
            if ( 'page' == $post_type ) {
                $args['orderby'] = '_score';
                $args['order']   = 'DESC';
                $related = get_post_meta($id, 'dd_related_posts', true);
                if ( ! empty($related) ) {
                    $args['more_like'] = '';
                    $args['post_type'] = 'page';
                    $args['post__in']  = get_post_meta($id, 'dd_related_posts', true);
                }
            }
        }
        return $args;
    }
}
