<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Topic extends Controller {
	
	function __construct() {
		parent::Controller();
		
		$this->load->model('Topics');
		
		//$this->output->enable_profiler(TRUE);      
		
	}
	
	function view($url = NULL) {
		
		
        $topic = $this->Topics->get_by_url($url);
		
		//print_r($topic);
		
		$lasttopics = array(
			'1-3-6',
			'1-3-7',
			'1-3-8',
			'1-3-9',
			'1-3-10-26',
			'1-3-10-29',
		);
		
		$this->load->library('bbcode');
        $this->bbcode->SetSmileyDir('/images/smileys/');
        $this->bbcode->SetSmileyURL(site_url('images/smileys'));
        $this->bbcode->SetDetectURLs(TRUE);
        $this->bbcode->SetAllowAmpersand(TRUE);		
        
		//print_r($topic);
		
		if($topic) {
			
			if(!$topic['template_file']) redirect('error');
			
			$view['topic'] = $topic;
			$view['last_topics'] = $this->Topics->get_recent_topics($lasttopics);
			//$view['site_statistics'] = $this->Users->get_count();
			//$view['online_users'] = $this->Users->get_online();
			
			$template = $topic['template_file'];
			$view['page_title'] = $topic['name'];
			$view['page_menu_selected'] = $topic['menu_selected'];
		} else {
			redirect('error');
		}
		
		$view['page_content'] = $this->load->view($template, $view, TRUE);
		$view['page_menu'] = $this->load->view('menu', $view, TRUE);
		$this->load->view('main', $view);
		
	}
	
}

?>