<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends Controller {
	
	function __construct() {
		parent::Controller();
		
		$this->load->library('OpenID/openid');
		$this->load->helper('url');
		$this->load->helper('email');
		$this->load->model('Users');
		
		$this->req = array('nickname', 'fullname', 'email');
		$this->opt = array();
		$this->policy_to = site_url('user/policy');
		$this->request_to = site_url('user/openid_check');
		
		//$this->output->enable_profiler(TRUE);
	}
	
	function login() {
		if($this->input->post('openid_url')) {
			
			if($this->input->post('openid_username')) {
				$user_activation = $this->session->userdata('user_activation');
				$user_activation['username'] = $this->input->post('openid_username');
				$this->session->set_userdata('user_activation', $user_activation);
			}
			
			$this->openid->set_request_to($this->request_to);
			$this->openid->set_trust_root(base_url());
			$this->openid->set_args(null);
			$this->openid->set_sreg(true, $this->req, $this->opt);
			$this->openid->set_pape(true, array());
			$response = $this->openid->authenticate($this->input->post('openid_url'));
			if(is_string($response)) {
				$view['error'] = $response;
			}
		} else {
			$view['error'] = $this->session->flashdata('openid_message');
		}
		
		$view['page_title'] = 'Sisene';
		$view['page_menu_selected'] = 'user/login';
		$view['page_content'] = $this->load->view('user/login', $view, TRUE);
		$view['page_menu'] = $this->load->view('menu', $view, TRUE);
		$this->load->view('main', $view);
		
	}
	
	function logout() {
		$this->sess->logout();
		redirect();
	}
	
	function register() {
		
		if(!$this->input->post('register_email')) redirect('user/login');
		
		$this->load->library('email');
		
		$email = $this->input->post('register_email');
		
		if(valid_email($email)) {
			$user = $this->Users->get_by_email($email);
			if(!is_array($user)) $user = $this->Users->get_by_username($email);
		} else {
			$user = $this->Users->get_by_username($email);
		}
		
		if(is_array($user)) {
			
			$mview['username'] = $user['username'];
			$mview['key'] = $this->Users->set_activation_key($user['id']);
			
			$message = $this->load->view('user/email_key', $mview, TRUE);
			
			$this->email->initialize(array('mailtype' => 'html'));
			$this->email->from('emug@emug.ee', 'eMug');
			$this->email->to($user['email']);
			$this->email->subject('eMug\'i konto uue OpenID tuvastajaga sidumine');
			$this->email->message($message);
			$this->email->send();
			
			$view['page_content'] = $this->load->view('user/key_sent', FALSE, TRUE);
		} else {
			
			if(valid_email($email)) {
				$mview['key'] = $this->Users->create_new($email);
				
				$message = $this->load->view('user/email_new', $mview, TRUE);
				
				$this->email->initialize(array('mailtype' => 'html'));
				$this->email->from('emug@emug.ee', 'eMug');
				$this->email->to($email);
				$this->email->subject('eMug\'i konto loomine');
				$this->email->message($message);
				$this->email->send();
				
				$view['page_content'] = $this->load->view('user/key_sent', FALSE, TRUE);
				
			} else {
				redirect('user/login');
			}
			
		}
		
		$view['page_title'] = 'Sisene';
		$view['page_menu_selected'] = 'user/login';
		$view['page_menu'] = $this->load->view('menu', $view, TRUE);
		$this->load->view('main', $view);
		
	}
	
	function activate($key = NULL) {
		
		if(strlen($key) != 32) redirect();
		
		$user = $this->Users->get_by_key($key);
		
		if(!is_array($user)) redirect();
		
		$user_activation = $this->session->userdata('user_activation');
		$user_activation['key'] = $key;
		$user_activation['user_id'] = $user['id'];
		$this->session->set_userdata('user_activation', $user_activation);
		
		
		if($user['username'] == $user['activation_key']) {
			$view['page_content'] = $this->load->view('user/newuser', FALSE, TRUE);
		} else {
			$view['username'] = $user['username'];
			$view['page_content'] = $this->load->view('user/activate', $view, TRUE);
		}
		
		$view['page_title'] = 'Aktiveeri konto';
		$view['page_menu_selected'] = 'user/login';
		$view['page_menu'] = $this->load->view('menu', $view, TRUE);
		$this->load->view('main', $view);
		
	}
	
	function username_check() {
		if($this->input->post('username')) {
			if(strlen($this->input->post('username')) > 2 AND !is_array($this->Users->get_by_username($this->input->post('username')))) echo 'OK';
		}
	}
	
	function openid_check() {
		
		$this->openid->set_request_to($this->request_to);
		$response = $this->openid->getResponse();
		
		if(is_string($response)) {
			$this->session->set_flashdata('openid_message', $response);
			redirect('user/login');
		} else {
			switch ($response->status) {
				case Auth_OpenID_CANCEL:
					$this->sess->logout();
					redirect('user/login');
					break;
				case Auth_OpenID_FAILURE:
					$this->sess->logout();
					$this->session->set_flashdata('openid_message', $response->message);
					redirect('user/login');
					break;
				case Auth_OpenID_SUCCESS:
					$user_id = htmlspecialchars($response->getDisplayIdentifier(), ENT_QUOTES);
					$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
					$user_data = $sreg_resp->contents();
					
					$user_activation = $this->session->userdata('user_activation');
					$this->session->unset_userdata('user_activation');
					if($user_activation) {
						if($this->Users->activate($user_activation['user_id'], $user_activation['key'], $user_id) == FALSE) redirect();
						if(isset($user_activation['username'])) $this->Users->change_name($user_activation['user_id'], $user_activation['username']);
					}
					
					if($this->sess->login($user_id) == TRUE) {
						redirect();
					} else {
						redirect('user/login');
					}
					break;
			}
		}
		
	}
	
	function xrds() {
		/*
		Some OpenID providers need this file.
		It must be linked in page HEAD also: <meta http-equiv="X-XRDS-Location" content="<?= base_url(); ?>user/xrds" />
		*/
		header("Content-type: application/xrds+xml" );
		echo '<?xml version="1.0" encoding="UTF-8"?>  
<xrds:XRDS  
    xmlns:xrds="xri://$xrds"  
    xmlns:openid="http://openid.net/xmlns/1.0"  
    xmlns="xri://$xrd*($v*2.0)">  
    <XRD>  
        <Service priority="1">  
            <Type>http://specs.openid.net/auth/2.0/return_to</Type>  
            <URI>'. $this->request_to .'</URI>  
        </Service>  
    </XRD>  
</xrds:XRDS>';
	}
	
}
?>