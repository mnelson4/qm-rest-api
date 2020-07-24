<?php
/*
Plugin Name: Query Monitor - REST API
Description: Adds query info to REST API headers
Author: Mike Nelson
Version: 0.0.1
*/
add_filter(
	'rest_post_dispatch',
	function(WP_REST_Response $rest_response, WP_REST_Server $server, $request){
		$rest_response->header('mike','the guy');
		global $wpdb;
		if($wpdb instanceof QM_DB){
			$queries = $wpdb->queries;
			$rest_response->header('Query-Monitor-Status','wp-content/db.php working. Query data added to response headers.');
			$x = 0;
			foreach($queries as $query){
				$rest_response->header('Query-Monitor-Query-' . $x++,sprintf('Time:%f,Query "%s"',$query[1],$query[0]));
			}
		} else {
			$rest_response->header('Query-Monitor-Status','wp-content/db.php not working. See https://github.com/johnbillion/query-monitor/wiki/db.php-Symlink');
		}
		return $rest_response;
	},
	10,
	3
);