<?php
	/*
	 Plugin Name: WP Membership Plugin
	 Plugin URI: https://github.com/sytnei/wp-membership-theme
	 Description: WPMP
	 Version: 1.03
	 Author: Ciocoiu Ionut Marius
	 Author URI: https://github.com/sytnei/
	 */

	/**
	 * Globals used in the plugin,
	 * @global float $GLOBALS['WPMP_VERSION']
	 * @global float $GLOBALS['WPMP_REQUIRED_WP_VERSION']
	 * @global string $GLOBALS['WPMP_PLUGIN']
	 * @global string $GLOBALS['WPMP_PLUGIN_BASENAME']
	 * @global string $GLOBALS['WPMP_PLUGIN_NAME']
	 * @global string $GLOBALS['WPMP_PLUGIN_DIR']
	 * @global string $GLOBALS['WPMP_PLUGIN_URI']
	 */

	define('WPMP_VERSION', 1.03);
	define('WPMP_REQUIRED_WP_VERSION', '4.7');
	define('WPMP_PLUGIN', __FILE__);
	define('WPMP_PLUGIN_BASENAME', plugin_basename(WPMP_PLUGIN));
	define('WPMP_PLUGIN_NAME', trim(dirname(WPMP_PLUGIN_BASENAME), '/'));
	define('WPMP_PLUGIN_DIR', untrailingslashit(dirname(WPMP_PLUGIN)));
	define('WPMP_PLUGIN_URI', plugins_url(WPMP_PLUGIN_NAME));

	/**
	 * Class used to generate a list of users setup in a custom post type called
	 * members.
	 *
	 * @category   WP Membership Plugin
	 * @package    WP Membership Plugin
	 * @author     Ciocoiu Ionut Marius <author@example.com>
	 * @copyright  2019 Ciocoiu Ionut Marius
	 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
	 * @version    1.03
	 * @link       https://github.com/sytnei/wp-membership-theme
	 * @see        NetOther, Net_Sample::Net_Sample()
	 * @since      File available since Release 1.0.3
	 */

	class wp_membership_plugin
	{
		/**
		 * $postTypes - stores a list of post types, when saving in the databased is made
		 * @var array
		 * @access private
		 */

		private $postTypes = array();

		/**
		 * $customFields - stores a list of fields, it is used to store the members
		 * attributes
		 * @var array
		 * @access private
		 */
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

			add_shortcode('wpmplist', array(
				$this,
				'wp_membership_plugin_shortcode'
			));

			add_action('wp_enqueue_scripts', array(
				$this,
				'add_scripts'
			));

			add_action('wp_enqueue_scripts', array(
				$this,
				'add_stylesheet'
			));

			add_action('wp_ajax_nopriv_list_members', array(
				$this,
				'list_members'
			), 1000);

			add_action('wp_ajax_list_members', array(
				$this,
				'list_members'
			), 1000);

		}

		/**
		 * custom_post_type_register - Method used to register Team Members custom post
		 * type
		 * @access public
		 * @var none
		 * @return empty
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

		/**
		 * custom_taxonomies_register - Method used to register Department Taxonomy
		 * associated  with the Team Members custom post type
		 * @access public
		 * @var none
		 * @return empty
		 */

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

		/**
		 * add_custom_fields_to_team_members - Method used to add custom fields to Team
		 * Members post type - it creates a metabox for team mebers
		 * @access public
		 * @var none
		 * @return empty
		 */

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
				add_meta_box('wp-membership-plugin-custom-fields', __('Team Members Settings', 'wp_membership_plugin'), array(
					$this,
					'display_custom_fields'
				), $postType, 'normal', 'high');
			}

		}

		/**
		 * display_custom_fields - Method used to display the custom fields that are
		 * added to the custom post types
		 * @access public
		 * @var none
		 * @return empty
		 */

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

				if (!current_user_can($customField['capability'], $post -> ID))
					$output = false;

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

		/**
		 * save_custom_fields - Method used to save custom fields for custom post types
		 * @access public
		 * @var integer $post_id
		 * @var object $post
		 * @return empty
		 */

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
						update_post_meta($post_id, $customField['name'], sanitize_text_field($value));
					}
					else
					{
						delete_post_meta($post_id, $customField['name']);
					}
				}
			}
		}

		/**
		 * add_stylesheet - Method used to add css to the plugin in front end
		 * @access public
		 * @var none
		 * @return empty
		 */

		function add_stylesheet()
		{
			wp_enqueue_style('wpmp-styles', WPMP_PLUGIN_URI . '/assets/css/styles.css', array(), WPMP_VERSION);
		}

		/**
		 * add_scripts - Method used to add javascript for the plugin in front end
		 * @access public
		 * @var none
		 * @return empty
		 */

		function add_scripts()
		{

			wp_enqueue_script('wpmp-scripts', WPMP_PLUGIN_URI . '/assets/js/scripts.js', array("jquery"), WPMP_VERSION, true);
		}

		/**
		 * list_members - Method used to display the list of members, it is also used for
		 * the ajax call, and also in the shortcode when are loaded the firs members
		 * if the variable $_GET['wpmp_page'] is set up, when the ajax call is made, we
		 * print the content, otherwise we return a string with the list of the members
		 * @access public
		 * @var none
		 * @return string - the list of team members
		 */

		function list_members()
		{

			$args = array(
				"post_type" => "team-member",
				"posts_per_page" => 3, //this is hardcoded, normaly should get the limit of posts
				// per page from admin options settings
				'paged' => isset($_GET['wpmp_page']) ? $_GET['wpmp_page'] : 1
			);

			$teammembers = new WP_Query($args);

			$content = "";

			if ($teammembers -> have_posts())
			{

				while ($teammembers -> have_posts())
				{

					$teammembers -> the_post();

					$image = get_the_post_thumbnail(get_the_ID(), 'thumbnail');
					$title = get_the_title();
					$description = get_the_content();
					$position = get_post_meta(get_the_ID(), "_position", true);
					$twitter_url = get_post_meta(get_the_ID(), "_twitter_url", true);
					$facebook_url = get_post_meta(get_the_ID(), "_facebook_url", true);

					$content .= '<div class="col-md-4 text-center">
	                					<center>' . $image . '
		                					<br /> 
		                					<h3>' . $title . '</h3>
		                					<h4>' . $position . '</h4>';

					if ($facebook_url != "")
					{
						$content .= '<a href="' . $facebook_url . '" target="_blank"><i class="fa fa-facebook"></i></a> &nbsp;&nbsp;';
					}

					if ($twitter_url != "")
					{
						$content .= '<a href="' . $twitter_url . '" target="_blank"><i class="fa fa-twitter"></i></a>';
					}

					$content .= '<br />	 
		                			 <a href="#" class="btn btn-primary btn-small btn--see-description">' . __("Read more", 'wp_membership_plugin') . '</a>
		                		     <br />
		                		     <div style="display:none">
		                			      ' . $description . '
		                		     </div>
		                		
		                	</center>
		                	
	                	</div>';

				}

				wp_reset_postdata();
			}

			if (isset($_GET['wpmp_page']))
			{
				echo $content;
				exit ;
			}
			else
			{
				return $content;
			}
		}

		/**
		 * wp_membership_plugin_shortcode - Method used to register the shortcode that
		 * will generate the list of members
		 * @access public
		 * @var none
		 * @return string - the content of the shortcode
		 */

		function wp_membership_plugin_shortcode()
		{
			$content = '';

			$content .= '<div class="container">
		
							<div class="row">
								<div class="col-md-12">
									<h1>' . __("Team Members", 'wp_membership_plugin') . '</h2>
								</div>	
							</div>	
							<div class="row" id="wpmp-ajax-container">';

			$content .= $this -> list_members();

			$content .= '</div>
					</div>';

			$content .= '<script>
			 				    var wpmp_ajax_url = "' . admin_url('admin-ajax.php') . '";
			               </script>
			              <center><a href="javascript:void(0);" class="btn btn--wpmp-more-items"><span class="down">&darr;</span></a></center>';

			return $content;

		}

	}

	/**
	 * Initialise the plugin
	 */

	$wp_membership_plugin = new wp_membership_plugin();
