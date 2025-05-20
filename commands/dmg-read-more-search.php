<?php

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

class DmgReadMoreSearch {

	/**
	 * Search for posts that contain the "DMG Read More" block.
	 *
	 * ## OPTIONS
	 *
	 * [--date-before[=<date>]]
	 * : The date before which to search for posts. Format: YYYY-MM-DD.
	 *
	 * [--date-after[=<date>]]
	 * : The date after which to search for posts. Format: YYYY-MM-DD.
	 *
	 * ## EXAMPLES
	 *
	 *    # Search for posts containing the "DMG Read More" block in the last 30 days.
	 *     wp dmg-read-more-search
	 *
	 *    # Search for posts containing the "DMG Read More" block before a specific date.
	 *     wp dmg-read-more-search --date-before=2023-01-01
	 *
	 *   # Search for posts containing the "DMG Read More" block after a specific date.
	 *     wp dmg-read-more-search --date-after=2023-01-01
	 *
	 *   # Search for posts containing the "DMG Read More" block between two dates.
	 *     wp dmg-read-more-search --date-before=2023-01-01 --date-after=2022-01-01
	 *
	 * @when after_wp_load
	 */
	public function __invoke( $args, $assoc_args ) {
		$date_before = $assoc_args['date-before'] ?? null;
		$date_after  = $assoc_args['date-after'] ?? null;

		// If no dates are provided, default to the last 30 days.
		if ( ! $date_before && ! $date_after ) {
			$date_after = date( 'Y-m-d', strtotime( '-30 days' ) );
		}

		// Validate date format
		if ( $date_before && ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date_before ) ) {
			WP_CLI::error( 'Invalid date format for --date-before. Use YYYY-MM-DD.' );
			return;
		}
		if ( $date_after && ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date_after ) ) {
			WP_CLI::error( 'Invalid date format for --date-after. Use YYYY-MM-DD.' );
			return;
		}

		// If both dates are provided, ensure date_before is before date_after
		if ( $date_before && $date_after && strtotime( $date_before ) > strtotime( $date_after ) ) {
			WP_CLI::error( 'The --date-before date must be before the --date-after date.' );
			return;
		}

		$query_args = array(
			'post_type'      => 'post',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => '_dmg_has_stylised_link',
					'compare' => 'EXISTS',
				),
			),
		);

		if ( $date_before ) {
			$query_args['date_query'][] = array(
				'before' => $date_before,
			);
		}

		if ( $date_after ) {
			$query_args['date_query'][] = array(
				'after' => $date_after,
			);
		}

		$posts = get_posts( $query_args );

		if ( empty( $posts ) ) {
			WP_CLI::success( 'No posts found with the DMG Read More block.' );
			return;
		}

		WP_CLI::success( 'Found the following posts with the DMG Read More block:' );
		foreach ( $posts as $post ) {
			WP_CLI::line( "{$post->post_title} (ID: {$post->ID})" );
		}
	}
}
WP_CLI::add_command( 'dmg-read-more-search', 'DmgReadMoreSearch' );
