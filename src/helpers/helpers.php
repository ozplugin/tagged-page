<?php

/**
 * @author    Demo <demo@demo.demo>
 * @link      https://demo.com/
 * @copyright 2024 Demo
 * @ver 1.0.0
 */

use Demo\TaggedPage\Base;

if (!defined('ABSPATH')) {
    exit;
}

global $wp_version;

if ( ! version_compare( $wp_version, '6.2', '>=' ) ) {
	add_action(
		'admin_notices',
		function() {
			?>
			<div class="notice notice-error">
				<p>
					<?php
					echo wp_kses_post(
						sprintf(
							/* translators: %s: Minimum required PHP version */
							__( 'Tagged Page requires Wordpress version %s or later. Please upgrade Wordpress or disable the plugin.', 'ddemo' ),
							'6.2'
						)
					);
					?>
				</p>
			</div>
			<?php
		}
	);
	return;
}

add_action('init', 'ddemo_init');

function ddemo_init()
{
    $base = new Base();
    $base->init();
}
