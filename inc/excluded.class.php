<?php

class wp_rest_excluded {
	static function user($user_id) {
		$wp_restrictions = get_option('wp_restrictions');
		$user_ids = explode(",", $wp_restrictions['excluded']['user_ids']);

		if (in_array($user_id,$user_ids)) {
			return true;
		} else {
			return false;
		}
	}
	static function page($page_id) {
		$wp_restrictions = get_option('wp_restrictions');
		$page_ids = explode(",", $wp_restrictions['excluded']['page_ids']);

		if (in_array($page_id,$page_ids)) {
			return true;
		} else {
			return false;
		}
	}
	static function post($post_id) {
		$wp_restrictions = get_option('wp_restrictions');
		$post_ids = explode(",", $wp_restrictions['excluded']['post_ids']);

		if (in_array($post_id,$post_ids)) {
			return true;
		} else {
			return false;
		}
	}
}

?>