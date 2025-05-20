<?php
/**
 * Plugin Name:       DMG Task
 * Description:       Contains Stylised Link block and the Read More Search WP-CLI command.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Ryan Jarrett
 * Text Domain:       dmg-task
 *
 * @package CreateBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once 'commands/dmg-read-more-search.php';

/**
 * Register the Stylised Link block.
 *
 * @return void
 */
function dmg_task_init_styled_link_block() {
	if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
		wp_register_block_types_from_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
		return;
	}

	if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
		wp_register_block_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
	}

	$manifest_data = require __DIR__ . '/build/blocks-manifest.php';
	foreach ( array_keys( $manifest_data ) as $block_type ) {
		register_block_type( __DIR__ . "/build/{$block_type}" );
	}
}
add_action( 'init', 'dmg_task_init_styled_link_block' );

/**
 * Register a custom post meta field for the Stylised Link block.
 *
 * @return void
 */
function dmg_task_register_custom_post_meta() {
	register_post_meta(
		'post',
		'_dmg_has_stylised_link',
		array(
			'type'          => 'boolean',
			'show_in_rest'  => true,
			'single'        => true,
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			},
		)
	);
}
add_action( 'init', 'dmg_task_register_custom_post_meta' );

/**
 * Enqueue the metadata sync script for the Stylised Link block.
 *
 * @return void
 */
function enqueue_metadata_sync_script() {
	if ( ! is_admin() ) {
		return;
	}

	wp_enqueue_script(
		'dmg-task-sync-script',
		plugins_url( 'build/dmg-task/metadata-sync.js', __FILE__ ),
		array( 'wp-api-fetch', 'wp-components', 'wp-data', 'wp-element' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'src/dmg-task/metadata-sync.js' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'enqueue_metadata_sync_script' );
