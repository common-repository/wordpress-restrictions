<?php

class wp_restrictions {
	static function load() {
		wp_rest_define::user_role(); // Defines Current User's Role
		wp_rest_define::user_id(); // Defines Current User's ID
		wp_restrictions::max_posts(); // Restricts # of Posts per Day
	}
	static function mmc($caps, $cap, $user_id, $args) {
		$wp_restrictions = get_option('wp_restrictions');

		if (WP_REST_ROLE == 'author' || WP_REST_ROLE == 'editor' && !wp_rest_excluded::user(WP_REST_UID)) {
			if ($cap == 'delete_post'  || $cap == 'edit_post' && WP_REST_ROLE == 'editor' || WP_REST_ROLE == 'author') {
					if ($wp_restrictions[WP_REST_ROLE][$cap] == '0' || $wp_restrictions[WP_REST_ROLE][$cap] == '' || wp_rest_excluded::post(get_the_ID())) {
						return;
					} elseif ($wp_restrictions[WP_REST_ROLE][$cap] == 1) {
						if (get_the_date('F j, Y') != date("F j, Y")) {
							$caps[] = $cap;
						}
					} else {
						$post_date = get_the_date('F j, Y');
						$num_days = "+" . $wp_restrictions[WP_REST_ROLE][$cap] . " " . "days";
						$delete_until = strtotime(date("F j, Y", strtotime($post_date)) . " $num_days");
						echo $post_date;
						if (strtotime("now") > $delete_until) {
							$caps[] = $cap;
						}
				}
			}
			if ($cap == 'delete_page'  || $cap == 'edit_page' && WP_REST_ROLE == 'editor') {
				$pages = get_pages($args[0]);
				foreach ($pages as $page) {
					if ($wp_restrictions[WP_REST_ROLE][$cap] == '0' || $wp_restrictions[WP_REST_ROLE][$cap] == '' || wp_rest_excluded::page(get_the_ID())) {
						return;
					} elseif ($wp_restrictions[WP_REST_ROLE][$cap] == 1) {
						if (get_the_date() != date("F j, Y")) {
							$caps[] = $cap;
						}
					} else {
						$page_date = get_the_date('F j, Y');
						$num_days = "+" . $wp_restrictions[WP_REST_ROLE][$cap] . " " . "days";
						$delete_until = strtotime(date("F j, Y", strtotime($page_date)) . " $num_days");

						if (strtotime("now") > $delete_until) {
							$caps[] = $cap;
						}
					}
				}
			}
		}
		return $caps;
	}
	static function max_posts() {
		global $wp_query;
		if (WP_REST_ROLE == 'editor' || WP_REST_ROLE == 'author' && !wp_rest_excluded::user(WP_REST_UID)) {
			$wp_query = new WP_Query(array('author' => WP_REST_UID, 'monthnum' => WP_REST_CURR_MONTH, 'day' => WP_REST_CURR_DAY, 'year' => WP_REST_CURR_YEAR));
			while($wp_query->have_posts()) : $wp_query->the_post();
			$post_count = $wp_query->post_count;
			endwhile;
			wp_reset_postdata();

			$wp_restrictions = get_option('wp_restrictions');
			$max_posts = $wp_restrictions[WP_REST_ROLE]['max_posts'];

			if ($max_posts != '' && $post_count >= $wp_restrictions[WP_REST_ROLE]['max_posts']) {
				remove_submenu_page('edit.php', 'post-new.php');
				if (strpos($_SERVER['REQUEST_URI'], 'post-new.php')) {
    					wp_die("You're only allowed to publish $max_posts posts within 24 hours. Please try again tomorrow.");
				}
			}
		}
	}
}

?>