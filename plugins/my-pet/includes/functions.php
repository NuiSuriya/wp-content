<?php
function create_pet_table() {
  global $wpdb;

  $charset_collate = $wpdb->get_charset_collate();
  $table_name = $wpdb->prefix . 'pets';

  $sql = "CREATE TABLE $table_name (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    species VARCHAR(255) NOT NULL,
    age INT DEFAULT 0,
    energy INT DEFAULT 1,
    heart INT DEFAULT 0,
    alive BOOLEAN DEFAULT true
  ) $charset_collate;";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}

register_activation_hook(MY_PET_PLUGIN_FILE, 'create_pet_table');
