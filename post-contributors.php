<?php
/**
 * Post Contributors
 *
 * @package       DR13DEV
 * @author        Dr13Dev
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Post Contributors
 * Plugin URI:    https://github.com/dr13dev
 * Description:   This plugin is for adding extra meta-box to post for assigning multiple contributors.
 * Version:       1.0.0
 * Author:        Dr13Dev
 * Author URI:    https://github.com/dr13dev
 * Text Domain:   post-contributors
 * Domain Path:   #
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Include your custom code here.
require('admin/post-contributors-metabox.php');
require('includes/show-post-contributors-metabox.php');
