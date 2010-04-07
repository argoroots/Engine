<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Error extends Controller {
	
	function index() {
		$view['page_title'] = 'ERROR';
		$view['page_menu_selected'] = '';
		$view['page_content'] = $this->load->view('error', $view, TRUE);
		$view['page_menu'] = $this->load->view('menu', $view, TRUE);
		$this->load->view('main', $view);
	}
	
}

?>