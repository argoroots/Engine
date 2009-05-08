<?php

class Topic extends Controller {

	function __construct() {
		parent::Controller();

		$this->load->model('Topic_model', 'topic');

		//$this->output->enable_profiler(TRUE);      

	}

	function index() {

		$this->view();

	}

	function view($url = NULL) {
		
		$view['data'] = $this->topic->get_by_url($url);
		echo '<pre>';
		print_r($view['data']);
		echo '</pre>';

		/*
		$view['page_menu_code'] = 'screen';
		$view['page_submenu'] = array($this->router->class .'/add'=>'Add New '. humanize($this->router->class));
		$view['page_content'] = $this->load->view('screen/screen_list', $view, True);
		$this->load->view('main_page_view', $view);
		*/
	}
}	
	
?>