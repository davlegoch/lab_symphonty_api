<?php
// src/Exception/RessourceException.php
namespace App\Exception;

use RuntimeException;

class RessourceException extends RuntimeException
{
	public $type = 'RessourceException';
    private $property;
	
	function __construct($property) {
		$this->property = $property;
		parent::__construct($this->format($property));
	}
	
	protected function format($property) {
		return 'Missing parameter: ' . $property . '.';
	}
}
