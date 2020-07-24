<?php

/*
Plugin Name: Query Monitor REST API
Description: Adds query info to REST API headers.
Author: Mike Nelson
Version: 0.0.1
*/

add_filter(
	'rest_post_dispatch',
	function (WP_REST_Response $rest_response, WP_REST_Server $server, $request) {
		global $wpdb;

		$rest_response->header('X-Mike', 'the guy');
		if ($wpdb instanceof QM_DB) {
			$rest_response->header(
				'Query-Monitor-Status',
				'wp-content/db.php working. Query data added to response headers.'
			);
			foreach ($wpdb->queries as $query_index => $query) {
				$rest_response->header(
					'Query-Monitor-Query-' . strval($query_index),
					sprintf('Time:%f,Query "%s"', $query[1], $query[0])
				);
			}
		} else {
			$rest_response->header(
				'Query-Monitor-Status',
				'wp-content/db.php not working. See https://github.com/johnbillion/query-monitor/wiki/db.php-Symlink'
			);
		}

		return $rest_response;
	},
	10,
	3
);
