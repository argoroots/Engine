<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Sess {


	var $user_id;
	var $group_id;
	var $name;
	var $email;
	var $last_visit_time;
	var $is_guest;


	function __construct() {
		
		//CI klassi kasutamine
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->library('session');
		$this->CI->load->helper('url');
		
		$this->_load_data();
		
		if($this->user_id == FALSE) $this->login();
		
	}
	


//logib kasutaja sisse ja loeb kasutaja andmed sesioonimuutujatesse
	function login($openid = '') { 
		
		//andmed nulli
		$this->_trunc_data();
		
		//loeme baasist kasutaja andmed
		$this->CI->db->select('users.id');
		$this->CI->db->select('users.group_id');
		$this->CI->db->select('users.username');
		$this->CI->db->select('users.email');
		$this->CI->db->select('users.last_visit_time');
		$this->CI->db->from('users');
		$this->CI->db->join('openids', 'openids.user_id = users.id', 'left');
		$this->CI->db->where('openids.openid', $openid);
		$this->CI->db->or_where('users.id', 1);
		$this->CI->db->order_by('users.id', 'desc');
		$this->CI->db->limit(1);
		$user = $this->CI->db->get()->row();
		
		//lisame useri login countile ühe
		//$this->CI->db->set('last_visit_time', 'UNIX_TIMESTAMP()', FALSE);
		//$this->CI->db->where('id', (int) $user->id);
		//$this->CI->db->update('users');
		
		//useri väärtused klassi ja sessiooni
		$this->user_id = $user->id;
		$this->group_id = $user->group_id;
		$this->username = $user->username;
		$this->email = $user->email;
		$this->last_visit_time = $user->last_visit_time;
		$this->is_guest = ($this->user_id == 1) ? TRUE : FALSE;
		$this->_save_data();
		
		if($user->id != 1) { //sisselogimine õnnestus
			$result = TRUE;
		} else { //sisselogimine ei õnnestunud
			$result = FALSE;
		}
		
		return $result;
		
	}




//logib kasutaja välja
	function logout() { 
		$this->login();
	}



//kontrollib sessiooni
	function protected_page($page) {
		
		$url = '';
		if($this->CI->router->class .'/'. $this->CI->router->method != 'user/login') $url = current_url();
		
		if(isset($this->controllers[$this->CI->router->class .'/'. $this->CI->router->method]) OR isset($this->controllers[$this->CI->router->class])) { //õiguse rida leiti
			//print_r($this->controllers);
			//echo $url;
			
		} else { //formi õiguse rida ei leitud
			$this->CI->session->set_userdata('redirect_url', $url);
			$this->_trunc_data();
			redirect('user/login');
			exit;
		}
		
	}



//loeb kasutaja väärtused sessioonist klassi
	function _load_data() {
		
		$data = $this->CI->session->userdata('current_user');
		
		$this->user_id = $data['user_id'];
		$this->group_id = $data['group_id'];
		$this->username = $data['username'];
		$this->email = $data['email'];
		$this->last_visit_time = $data['last_visit_time'];
		$this->is_guest = $data['is_guest'];
		
	}



//salvestab kasutaja väärtused sessiooni
	function _save_data() { 
		
		$data = array(
			'user_id' => $this->user_id,
			'group_id' => $this->group_id,
			'username' => $this->username,
			'email' => $this->email,
			'last_visit_time' => $this->last_visit_time,
			'is_guest' => $this->is_guest,
		);
		
		//print_r($data);
		
		$this->CI->session->set_userdata('current_user', $data);
		
	}



//kustutab kasutaja väärtused
	function _trunc_data() { 
		
		$this->CI->session->unset_userdata('current_user');
		
	}



}

?>