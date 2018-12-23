<?php
	/*
	 Plugin Name: WP Membership Plugin
	 Plugin URI: https://github.com/sytnei/wp-membership-theme
	 Description: WPMP
	 Version: 1
	 Author: Ciocoiu Ionut Marius
	 Author URI: https://github.com/sytnei/
	 */

	class wp_membership_plugin
	{
		private $postTypes = array();
		private $customFields = array();

		function __construct()
		{
			add_action('init', array(
				$this,
				'custom_post_types_register'
			), 10, 2);

			add_action('init', array(
				$this,
				'custom_taxonomies_register'
			), 10, 2);

			add_action('admin_init', array(
				$this,
				'add_custom_fields_to_team_members'
			), 1, 2);
  
			add_action('save_post', array(
				$this,
				'save_custom_fields'
			), 1, 2);
		}

		function wp_membership_plugin()
		{
			$this -> __construct();
		}

		/**
		 * custom_post_type_register - Method used to register Team Members custom post
		 * type
		 */

		function custom_post_types_register()
		{
			$labels = array(
				'name' => _x('Team Members', 'team-member', 'wp_membership_plugin'),
				'singular_name' => _x('Team Member', 'team-member', 'wp_membership_plugin'),
				'menu_name' => _x('Team Members', 'admin menu', 'wp_membership_plugin'),
				'name_admin_bar' => _x('Team Member', 'add new on admin bar', 'wp_membership_plugin'),
				'add_new' => _x('Add New', 'team-member', 'wp_membership_plugin'),
				'add_new_item' => __('Add New Team Member', 'wp_membership_plugin'),
				'new_item' => __('New Team Member', 'wp_membership_plugin'),
				'edit_item' => __('Edit Team Member', 'wp_membership_plugin'),
				'view_item' => __('View Team Member', 'wp_membership_plugin'),
				'all_items' => __('All Team Members', 'wp_membership_plugin'),
				'search_items' => __('Search Team Members', 'wp_membership_plugin'),
				'parent_item_colon' => __('Parent Team Members:', 'wp_membership_plugin'),
				'not_found' => __('No Team Members found.', 'wp_membership_plugin'),
				'not_found_in_trash' => __('No Team Members found in Trash.', 'wp_membership_plugin')
			);

			$args = array(
				'labels' => $labels,
				'description' => __('Description.', 'wp_membership_plugin'),
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'query_var' => true,
				'rewrite' => array('slug' => 'team-member'),
				'capability_type' => 'post',
				'menu_icon' => 'dashicons-products',
				'has_archive' => true,
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array(
					'title',
					'editor',
					'thumbnail'
				)
			);

			register_post_type('team-member', $args);
		}

		function custom_taxonomies_register()
		{

			$labels = array(
				'name' => _x('Departments', 'taxonomy general name', 'wp_membership_plugin'),
				'singular_name' => _x('Department', 'taxonomy singular name', 'wp_membership_plugin'),
				'search_items' => __('Search Departments', 'wp_membership_plugin'),
				'all_items' => __('All Departments', 'wp_membership_plugin'),
				'parent_item' => __('Parent Department', 'wp_membership_plugin'),
				'parent_item_colon' => __('Parent Department:', 'wp_membership_plugin'),
				'edit_item' => __('Edit Department', 'wp_membership_plugin'),
				'update_item' => __('Update Department', 'wp_membership_plugin'),
				'add_new_item' => __('Add New Department', 'wp_membership_plugin'),
				'new_item_name' => __('New Department Name', 'wp_membership_plugin'),
				'menu_name' => __('Departments', 'wp_membership_plugin'),
			);

			$args = array(
				'hierarchical' => true,
				'labels' => $labels,
				'show_ui' => true,
				'show_admin_column' => true,
				'query_var' => true,
				'rewrite' => array('slug' => 'department'),
			);

			register_taxonomy('department', array('team-member'), $args);
		}

		function add_custom_fields_to_team_members()
		{
			$this -> postTypes = array("team-member");

			$this -> customFields = array(
				array(
					"name" => "_position",
					"title" => "Team member Position",
					"description" => "",
					"type" => "text",
					"scope" => array("team-member"),
					"capability" => "edit_pages"
				),
				array(
					"name" => "_twitter_url",
					"title" => "Twitter url",
					"description" => "",
					"type" => "text",
					"scope" => array("team-member"),
					"capability" => "edit_pages"
				),
				array(
					"name" => "_facebook_url",
					"title" => "Facebook url",
					"description" => "",
					"type" => "text",
					"scope" => array("team-member"),
					"capability" => "edit_pages"
				)
			);

			foreach ($this->postTypes as $postType)
			{
				add_meta_box('wp-membership-plugin-custom-fields', __('Team Members Settings'), array(
					$this,
					'display_custom_fields'
				), $postType, 'normal', 'high');
			}

		}

		function display_custom_fields()
		{

			global $post;

			wp_nonce_field('wp-membership-plugin-custom-fields', 'wp-membership-plugin-custom-fields_wpnonce', false, true);

			echo '<div class="form-wrap">';

			foreach ($this->customFields as $customField)
			{

				$scope = $customField['scope'];
				$output = false;
				foreach ($scope as $scopeItem)
				{
					switch ( $scopeItem )
					{
						default :
						{
							if ($post -> post_type == $scopeItem)
								$output = true;
							break;
						}
					}
					if ($output)
						break;
				}

				// Check capability
				if (!current_user_can($customField['capability'], $post -> ID))
					$output = false;

				// Output if allowed
				if ($output)
				{

					echo '<p>
							<label for="' . $customField['name'] . '"><b>' . $customField['title'] . '</b></label>
							<br>
							<input type="text" name="' . $customField['name'] . '" id="custom_field_' . $customField['name'] . '" class="regular-text" value="' . htmlspecialchars(get_post_meta($post -> ID, $customField['name'], true)) . '">
						</p>';

				}
			}

			echo '</div>';
		}

		function save_custom_fields($post_id, $post)
		{
			if (!isset($_POST['wp-membership-plugin-custom-fields_wpnonce']) || !wp_verify_nonce($_POST['wp-membership-plugin-custom-fields_wpnonce'], 'wp-membership-plugin-custom-fields'))
				return;

			if (!current_user_can('edit_post', $post_id))
				return;

			if (!in_array($post -> post_type, $this -> postTypes))
				return;

			foreach ($this->customFields as $customField)
			{
				if (current_user_can($customField['capability'], $post_id))
				{
					if (isset($_POST[$customField['name']]) && trim($_POST[$customField['name']]))
					{
						$value = $_POST[$customField['name']];
						update_post_meta($post_id, $customField['name'], $value);
					}
					else
					{
						delete_post_meta($post_id, $customField['name']);
					}
				}
			}
		}

	}

	$wp_membership_plugin = new wp_membership_plugin();
