<?php
/**
 * Plugin Update Checker
 *
 * @package PluginUpdateChecker
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Include the PluginUpdateChecker class.
require_once(plugin_dir_path(__FILE__) . 'plugin-update-checker/plugin-update-checker.php');

// Check for updates.
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://example.com/plugins/incident-report-form-elementor/info.json', // URL to your JSON file containing plugin metadata
    __FILE__, // Current plugin file
    'incident-report-form-elementor' // Plugin slug
);

// Optional: Set the plugin's update metadata.
$myUpdateChecker->setBranch('master'); // Set the branch to check for updates. Default is 'master'.
$myUpdateChecker->setAuthentication('username', 'password'); // Set authentication credentials if your update server requires them.
