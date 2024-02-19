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
 * Ajax class
 */
class Ajax {


    /**
     * Actions prefix
     *
     * @var string
     */
    private $prefix = 'ddemo_';

    /**
     * Name
     *
     * @var string
     */
    private $name = null;

    /**
     * Result
     *
     * @var bool
     */
    private $success = false;

    /**
     * Payload
     *
     * @var mixed
     */
    private $payload = false;

    /**
     * Names of ajax actions
     *
     * @return array
     */
    public function get_actions() {
        $actions = array(
            array(
                'name'   => 'get_pages',
                'public' => false,
            ),
            array(
                'name'   => 'set_tags',
                'public' => false,
            ),
            array(
                'name'   => 'set_relation',
                'public' => false,
            ),
        );
        return $actions;
    }

    /**
     * Register ajax actions
     *
     * @return void
     */
    public function register() {
        $actions = $this->get_actions();
        foreach ( $actions as $action ) {
            $this->name = $action['name'];
            add_action('wp_ajax_' . $this->prefix . $this->name, array( $this, $this->name ));
            if ( $action['public'] ) {
                add_action('wp_ajax_nopriv_' . $this->prefix . $this->name, array( $this, $this->name ));
            }
        }
    }

    /**
     * Get WordPress pages
     *
     * @return void
     */
    public function get_pages() {
        if ( wp_doing_ajax() && wp_verify_nonce($_POST['_wpnonce'], 'ddemo') ) {
            $response = false;
            add_filter('ep_ajax_wp_query_integration', '__return_true');
            add_filter('ep_enable_do_weighting', '__return_true');
            $terms   = isset($_POST['tags']) ? explode(',', sanitize_text_field($_POST['tags'])) : array();
            $post_id = isset($_POST['post_id']) ? (int) ( $_POST['post_id'] ) : 0;
            if ( is_array($terms) ) {
                $terms = array_map('intval', $terms);
            }
            $response = Page::instance($post_id)->get($terms);
            $this->response($response);
        }
        wp_die();
    }

    /**
     * Set tags for page
     *
     * @return void
     */
    public function set_tags() {
        if ( wp_doing_ajax() && wp_verify_nonce($_POST['_wpnonce'], 'ddemo') ) {
            $response = false;
            $terms    = isset($_POST['tags']) ? explode(',', sanitize_text_field($_POST['tags'])) : array();
            $post_id  = isset($_POST['post_id']) ? (int) ( $_POST['post_id'] ) : 0;
            if ( is_array($terms) ) {
                $terms = array_map('intval', $terms);
            }
            $response = Page::instance($post_id)->set_terms($terms);
            $this->response($response);
        }
        wp_die();
    }

    /**
     * Set related pages for page
     *
     * @return void
     */
    public function set_relation() {
        if ( wp_doing_ajax() && wp_verify_nonce($_POST['_wpnonce'], 'ddemo') ) {
            $response    = false;
            $relation_id = isset($_POST['related']) ? (int) ( $_POST['related'] ) : 0;
            $post_id     = isset($_POST['post_id']) ? (int) ( $_POST['post_id'] ) : 0;
            $response    = Page::instance($post_id)->set_relation($relation_id);
            $this->response($response);
        }
        wp_die();
    }

    /**
     * Ajax response
     *
     * @param  mixed $answer Ajax request result
     * @return void
     */
    public function response( $answer ) {
        if ( ! is_wp_error($answer) && $answer ) {
            $this->success = true;
            $this->payload = $answer;
        } else {
            $this->payload = is_wp_error($answer) ? $answer->get_error_message() : $answer;
        }
        echo wp_json_encode(
            array(
                'success' => $this->success,
                'payload' => $this->payload,
            )
        );
        wp_die();
    }
}
