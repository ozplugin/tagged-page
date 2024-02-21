<?php

/**
 * Plugin Name: Tagged Page
 * Plugin URI: http://demo.demo
 * Description: This plugin adds tags to pages. Additionally, in the page editor, there is a setting called "Related Pages." With its help, you can use tags to find the necessary page and link it to the page being edited.
 * Version: 1.0.1
 * Author: demo-demo
 * Author URI: http://demo-demo
 * License: GPLv2
 * Text Domain: demo-demo
 */

define('DDEMO_VER', '1.0.0');
define('DDEMO_FILE', __FILE__);
define('DDEMO_PATH', dirname(DDEMO_FILE) . '/');
define('DDEMO_URL', plugins_url('/', DDEMO_FILE));

require 'vendor/autoload.php';
