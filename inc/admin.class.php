<?php

class wp_rest_admin {
	static function menu() {
		add_options_page('WordPress Restrictions', 'Restrictions', 8, basename(__FILE__), 'wp_rest_admin::manage');
	}
	static function manage() {
		if (!current_user_can('manage_options')) {
			wp_die( __('You do not have sufficient permissions to access this page.') );
	 	}

		if (isset($_POST['wp_restrictions_submit'])) {
			$wp_restrictions = array(
					'editor' => array(
						'delete_post' => $_POST['editor_delete_posts'],
						'edit_post' => $_POST['editor_edit_posts'],
						'delete_page' => $_POST['editor_delete_pages'],
						'edit_page' => $_POST['editor_edit_pages'],
						'max_posts' => $_POST['editor_max_posts']
					),
					'author' => array(
						'delete_post' => $_POST['author_delete_posts'],
						'edit_post' => $_POST['author_edit_posts'],
						'max_posts' => $_POST['author_max_posts']
					),
					'excluded' => array(
						'user_ids' => $_POST['excluded_user_ids'],
						'post_ids' => $_POST['excluded_post_ids'],
						'page_ids' => $_POST['excluded_page_ids']
					)
			);

			foreach($wp_restrictions as $var => $key) {
				$wp_restrictions[$var] = preg_replace('/[^0-9,]/', '', $key);
			}

			update_option('wp_restrictions', $wp_restrictions);
		}

		if (isset($_POST['wp_restrictions_uninstall'])) {
			if ($_POST['wp_restrictions_uninstall'] == 'Uninstall') {
				wp_rest_admin::uninstall();
			}
		}

		$wp_restrictions = get_option('wp_restrictions');
?>
	<style type="text/css">
		h2 { font-family: "HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",sans-serif; font-weight: normal; font-size: 23px; color: #464646; }
		.content-left { width: 30%; padding: 10px; border-right: 1px solid #e6e6e6; float:left; position:relative; }
		.content-right { width:63%; padding: 10px; float: left; position: relative; }
		.clear { clear: both }
	</style>

<div class="wrap">
	<h2>WordPress Restrictions</h2>
	<form name="wordpress_restrictions" method="POST" action="">
	<div class="metabox-holder">
		<div class="postbox">
      		<h3>Delete Posts</h3>
				<div class="content-left">
					<p>You can specify a set number of days when authors and editors can delete posts on your WordPress Blog.</p>
					<p><i>A post published on August 1 would be deletable by an editor (or the author) until August 11 with a delete posts timeframe of 10 days.</i></p>
				</div>
				<div class="content-right">
					<p><strong># of Days for Editors:</strong> <input type="text" name="editor_delete_posts" value="<?php echo $wp_restrictions['editor']['delete_post']; ?>" /> <i>leave blank to disable</i></p>
					<p><strong># of Days for Authors:</strong> <input type="text" name="author_delete_posts" value="<?php echo $wp_restrictions['author']['delete_post']; ?>" /> <i>leave blank to disable</i></p>
				</div>
				<div class="clear"></div>
		</div>
	</div>
	<div class="metabox-holder">
		<div class="postbox">
      		<h3>Edit Posts</h3>
				<div class="content-left">
					<p>You can specify a set number of days when authors and editors can edit posts on your WordPress Blog.</p>
					<p><i>A post published on August 1 would be editable by an editor (or the author) until August 11 with an edit posts timeframe of 10 days.</i></p>
				</div>
				<div class="content-right">
					<p><strong># of Days for Editors:</strong> <input type="text" name="editor_edit_posts" value="<?php echo $wp_restrictions['editor']['edit_post']; ?>" /> <i>leave blank to disable</i></p>
					<p><strong># of Days for Authors:</strong> <input type="text" name="author_edit_posts" value="<?php echo $wp_restrictions['author']['edit_post']; ?>" /> <i>leave blank to disable</i></p>
				</div>
				<div class="clear"></div>
		</div>
	</div>
	<div class="metabox-holder">
		<div class="postbox">
      		<h3>Edit Pages</h3>
				<div class="content-left">
					<p>You can specify a set number of days when editors can edit pages on your WordPress Blog.</p>
					<p><i>A page published on January 1 would be editable by an editor until January 31 with an edit pages timeframe of 30 days.</i></p>
				</div>
				<div class="content-right">
					<p><strong>Specify a # of Days:</strong> <input type="text" name="editor_edit_pages" value="<?php echo $wp_restrictions['editor']['edit_page']; ?>" /> <i>leave blank to disable</i></p>
				</div>
				<div class="clear"></div>
		</div>
	</div>
	<div class="metabox-holder">
		<div class="postbox">
      		<h3>Delete Pages</h3>
				<div class="content-left">
					<p>You can specify a set number of days when editors can delete pages on your WordPress Blog.</p>
					<p><i>A page published on January 1 would be deletable by an editor until January 31 with a delete pages timeframe of 30 days.</i></p>
				</div>
				<div class="content-right">
					<p><strong>Specify a # of Days:</strong> <input type="text" name="editor_delete_pages" value="<?php echo $wp_restrictions['editor']['delete_page']; ?>" /> <i>leave blank to disable</i></p>
				</div>
				<div class="clear"></div>
		</div>
	</div>
	<div class="metabox-holder">
		<div class="postbox">
      		<h3>New Posts</h3>
				<div class="content-left">
					<p>You can specify a maximum number of posts an editor or author can make on your WordPress Blog within 24 hours.</p>
					<p><i>If you input the number '5' for editors and authors, then each editor and author can publish up to five posts a day.</i></p>
				</div>
				<div class="content-right">
					<p><strong>Max. # of Posts (Editor):</strong> <input type="text" name="editor_max_posts" value="<?php echo $wp_restrictions['editor']['max_posts']; ?>" /> <i>leave blank to disable</i></p>
					<p><strong>Max. # of Posts (Author):</strong> <input type="text" name="author_max_posts" value="<?php echo $wp_restrictions['author']['max_posts']; ?>" /> <i>leave blank to disable</i></p>
				</div>
				<div class="clear"></div>
		</div>
	</div>
	<div class="metabox-holder">
		<div class="postbox">
      		<h3>Exclude Options</h3>
				<div class="content-left">
					<p>You can specify certain USER IDs, PAGE IDs, and POST IDs to exclude from the functions of WordPress Restrictions.</p>
					<p><i>If you have a post with an ID of '1' (usually Hello World!) and you exclude Post ID '1', then that particular post would be excluded from WordPress Restrictions (so authors and editors will always be able to edit and delete Hello World! or Post ID '1').</i></p>
				</div>
				<div class="content-right">
					<p><strong>User IDs (Comma Separated):</strong> <input type="text" name="excluded_user_ids" value="<?php echo $wp_restrictions['excluded']['user_ids']; ?>" /> <i>leave blank to disable</i></p>
					<p><strong>Post IDs (Comma Separated):</strong> <input type="text" name="excluded_post_ids" value="<?php echo $wp_restrictions['excluded']['post_ids']; ?>" /> <i>leave blank to disable</i></p>
					<p><strong>Page IDs (Comma Separated):</strong> <input type="text" name="excluded_page_ids" value="<?php echo $wp_restrictions['excluded']['page_ids']; ?>" /> <i>leave blank to disable</i></p>
				</div>
				<div class="clear"></div>
		</div>
	</div>
	<input type="submit" name="wp_restrictions_submit" value="Update Options">
	<input type="submit" name="wp_restrictions_uninstall" value="Uninstall">
	</form>
</div>

	<?php }
	static function uninstall() {
		delete_option('wp_restrictions');
		echo '<div style="background-color: lightYellow; border: 1px solid #E6DB55; margin: 10px 10px 10px 0; padding: 6px;">WordPress Restrictions Options have been removed from your WordPress Install. If you\'d like to completely remove WordPress Restrictions, please do so through the Plugin Admin.</div>';
	}
}
?>