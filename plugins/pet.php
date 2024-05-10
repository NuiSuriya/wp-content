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
}
