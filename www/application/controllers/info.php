<?php

class Info extends Controller {

	function __construct() {
		parent::Controller();

		//$this->output->enable_profiler(TRUE);      

	}

	function index() {

		echo 'OK';

	}

	function php() {
		phpinfo();
	}

}	
	
?>
