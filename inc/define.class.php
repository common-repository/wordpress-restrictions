<?php

class wp_rest_define {
	static function user_role() {
		if (current_user_can('editor') || current_user_can('author')) {
			if (current_user_can('editor')) {
				$role = 'editor';
			} else {
				$role = 'author';
			}
		}
		define('WP_REST_ROLE', $role);
	}
	static function user_id() {
		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;
		define('WP_REST_UID', $user_id);
	}
}

?>