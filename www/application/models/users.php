<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Model {

	function __construct() {
		parent::Model();
		$this->db->simple_query('SET NAMES \'utf8\'');
	}


	function get_by_username($username) {
		
		$result = FALSE;
		
		$this->db->select('id');
		$this->db->select('username');
		$this->db->select('email');
		$this->db->select('activation_key');
		$this->db->from('users');
		$this->db->where('username', $username);
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) $result = $query->row_array();
		
		return $result;
		
	}
	
	function get_by_email($email) {
		
		$result = FALSE;
		
		$this->db->select('id');
		$this->db->select('username');
		$this->db->select('email');
		$this->db->select('activation_key');
		$this->db->from('users');
		$this->db->where('email', $email);
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) $result = $query->row_array();
		
		return $result;
		
	}
	
	function get_by_key($key) {
		
		$result = FALSE;
		
		$this->db->select('id');
		$this->db->select('username');
		$this->db->select('email');
		$this->db->select('activation_key');
		$this->db->from('users');
		$this->db->where('activation_key', $key);
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) $result = $query->row_array();
		
		return $result;
		
	}
	
	function get_by_openid($openid) {
		
		$result = FALSE;
		
		$this->db->select('users.id');
		$this->db->select('users.username');
		$this->db->select('users.email');
		$this->db->from('users');
		$this->db->join('openids', 'openids.user_id = users.id');
		$this->db->where('openids.openid', $openid);
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) $result = $query->row_array();
		
		return $result;
		
	}
	
	function set_activation_key($user_id) {
		
		$key_ok = FALSE;
		
		while($key_ok == FALSE) {
			$key = md5(rand() . time());
			
			$this->db->select('id');
			$this->db->select('username');
			$this->db->select('email');
			$this->db->from('users');
			$this->db->where('activation_key', $key);
			$this->db->limit(1);
			
			$query = $this->db->get();
			
			if($query->num_rows() < 1) $key_ok = TRUE;
		}
		
		$this->db->set('activation_key', $key);
		$this->db->where('id', (int) $user_id);
		$this->db->update('users');
		
		return $key;
		
	}
	
	function create_new($email) {
		
		$this->db->set('group_id', 2);
		$this->db->set('username', $email);
		$this->db->set('email', $email);
		$this->db->set('create_time', 'UNIX_TIMESTAMP()', FALSE);
		$this->db->insert('users');
		
		$newid = $this->db->insert_id();
		$key = $this->set_activation_key($newid);
		
		$this->db->set('username', $key);
		$this->db->where('id', $newid);
		$this->db->update('users');
		
		return $key;
		
	}
	
	function activate($user_id, $key, $open_id) {
		
		$result = FALSE;
		
		if(!is_array($this->get_by_key($key))) return $result;
		if(is_array($this->get_by_openid($open_id))) return $result;
		
		$this->db->set('user_id', (int) $user_id);
		$this->db->set('openid', $open_id);
		$this->db->insert('openids');
		
		$this->db->set('activation_key', '');
		$this->db->where('id', (int) $user_id);
		$this->db->update('users');
		
		return TRUE;
		
	}
	
	function change_name($user_id, $username) {
		
		$result = FALSE;
		
		if(is_array($this->get_by_username($username))) return $result;
		
		$this->db->set('username', $username);
		$this->db->where('id', (int) $user_id);
		$this->db->update('users');
		
		return TRUE;
		
	}
	
	function get_online() {
		
		$result = array();
		
		$this->db->select('user_id');
		$this->db->select('IF(user_id = 1, \'Guest\', ident) AS user', FALSE);
		$this->db->select('count(*) AS count');
		$this->db->from('pun_online');
		$this->db->group_by('user_id');
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$result[$row->user_id] = array(
							'user_id' => $row->user_id,
							'user' => $row->user,
							'count' => $row->count,
				);
			}
		}
		
		return $result;
	}
	
	function get_count() {
		
		$result['total_users'] = 0;
		$result['posted_users'] = 0;
		$result['total_topics'] = 0;
		$result['total_posts'] = 0;
		
		$this->db->select('count(*) AS count');
		$this->db->from('pun_users');
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$row = $query->row();
			$result['total_users'] = $row->count;
		}
		
		$this->db->select('count(DISTINCT poster_id) AS count');
		$this->db->from('pun_posts');
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$row = $query->row();
			$result['posted_users'] = $row->count;
		}
		
		$this->db->select('count(*) AS count');
		$this->db->from('pun_topics');
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$row = $query->row();
			$result['total_topics'] = $row->count;
		}
		
		$this->db->select('count(*) AS count');
		$this->db->from('pun_posts');
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			$row = $query->row();
			$result['total_posts'] = $row->count;
		}
		
		return $result;
	}
	
}