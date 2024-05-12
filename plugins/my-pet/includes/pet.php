<?php

class Pet {
    // Declare the public properties of the class
  public $name;
  public $species;
  public $age;
  public $energy;
  public $heart;
  public $alive;

  // Contructor Method
  // Initialize the properties of the class
  public function __construct($name, $species, $age = 0, $energy = 1, $heart = 0, $alive = true) {
    $this->name = $name;
    $this->species = $species;
    $this->age = $age;
    $this->energy = $energy;
    $this->heart = $heart;
    $this->alive = $alive;
  }

  // Create
  public static function create($name, $species, $age = 0, $energy = 1, $heart = 0, $alive = true) {
    $pet = new Pet($name, $species, $age, $energy, $heart, $alive);
    $pet->save();
    return $pet;
  }

  // Save
  public function save() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pets';
    $wpdb->insert(
      $table_name,
      array(
        'name' => $this->name,
        'species' => $this->species,
        'age' => $this->age,
        'energy' => $this->energy,
        'heart' => $this->heart,
        'alive' => $this->alive
      )
    );
    $this->id = $wpdb->insert_id;
  }
}

if (!class_exists('WP_List_Table')) {
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Pets_List_Table extends WP_List_Table {
  function get_columns() {
    $column = array(
      'name' => 'Name',
      'species' => 'Species',
      'age' => 'Age',
      'energy' => 'Energy',
      'heart' => 'Heart',
      'alive' => 'Alive'
    );
    return $column;
  }

  function prepare_items() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pets';
    $query = "SELECT * FROM $table_name";
    $data = $wpdb->get_results($query, ARRAY_A);
    $columns = $this->get_columns();
    $hidden = array();
    $sortable = array();
    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->items = $data;
  }

  function column_default($item, $column_name) {
    if ($column_name === 'alive') {
      return $item[$column_name] ? 'Yes' : 'No';
    }
    return $item[$column_name];
  }
}

function pet_admin_menu() {
  add_menu_page(
    'My Pet', // Page Title
    'Pet', // Menu Title
    'manage_options', // capability
    'my-pets', // slug
    'pet_admin_page',
    'dashicons-pets',
    20
  );

  add_submenu_page(
    'my-pets',
    'Add New Pet',
    'Add New',
    'manage_options',
    'create_pet',
    'create_pet_page'
  );
}

add_action ('admin_menu', 'pet_admin_menu');


function create_pet_page() {
  ob_start();  // Start output buffering
  // Check if the form is submitted
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a new pet
    Pet::create(
      $_POST['name'],
      $_POST['species']
    );
    wp_redirect(admin_url('admin.php?page=my-pets'));
    ob_end_flush();  // Send the output and stop buffering
    exit;
  }

  // Output the form
  echo '<h1>Add New Pet</h1>';
  echo '<a href="' . admin_url('admin.php?page=my-pets') . '">Back to Pet List</a>';
  echo '<form method="post">';
  echo '<label>Name
          <input type="text" name="name" required>
        </label>';
  echo '<label>Species
          <select name="species">
            <option value="dog">Dog</option>
            <option value="cat">Cat</option>
          </select>
        </label>';
  echo '<button type="submit">Add Pet</button>';
  echo '</form>';
}


function pet_admin_page() {
  // global $wpdb;
  echo '<h1>My Pet</h1>';
  echo '<a href="' . admin_url('admin.php?page=create_pet') . '" class="page-title-action">Add New Pet</a>';
  $petsListTable = new Pets_List_Table();
  $petsListTable->prepare_items();
  $petsListTable->display();
}
