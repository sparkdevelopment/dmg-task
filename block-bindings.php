<?php

function dmg_task_register_block_bindings() {
	register_block_bindings_source(
		'dmg-task/stylised-link',
		array(
			'label'              => __( 'Target Post', 'dmg-task' ),
			'get_callback_value' => function( array $source_args, $block_instance ) {
				$target_post_id = $source_args['targetPostId'] ?? null;

				// If the target post ID is not set, return null.
				if ( ! $target_post_id ) {
					return null;
				}

				// If the target post ID is not a valid post, return null.
				$post = get_post( $target_post_id );
				if ( ! $post ) {
					return null;
				}

				// If the post is not published or post is not of the correct type, return null.
				if ( 'publish' !== $post->post_status || ! in_array( $post->post_type, array( 'post', 'page' ), true ) ) {
					return null;
				}

				return array(
					'title'     => get_the_title( $post ),
					'permalink' => get_permalink( $post ),
				);
			},
		)
	);
}

add_action( 'init', 'dmg_task_register_block_bindings' );
