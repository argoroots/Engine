<?php

class Topics extends Model {
	
	function __construct() {
		parent::Model();
	}

	function get_by_url($topic_url = NULL) {
		$result = $this->_get_one(NULL, $topic_url);
		return $result;
	}
	
	function _get_one($topic_id = NULL, $topic_url = NULL) {
		
		$result = FALSE;
		
		$this->db->select('topics.id');
		$this->db->select('topics.create_time');
		$this->db->select('contents.create_time AS edit_time');
		$this->db->select('topics.url');
		$this->db->select('contents.name');
		$this->db->select('contents.content');
		$this->db->select('topics.path');
		$this->db->select('users.username');
		$this->db->select('users.id AS user_id');
		$this->db->select('templates.template_file');
		$this->db->select('templates.menu_selected');
		$this->db->select('templates.child_sort_order');
		$this->db->select('templates.child_level');
		$this->db->from('topics');
		$this->db->join('contents', 'contents.topic_id = topics.id');
		$this->db->join('templates', 'templates.id = topics.template_id');
		$this->db->join('users', 'users.id = contents.user_id');
		if($topic_id) $this->db->where('topics.id', (int) $topic_id);
		if($topic_url) $this->db->where('topics.url', $topic_url);
		$this->db->order_by('contents.create_time DESC');
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			
			$result = $query->row_array();
			$result['path'] = $this->_get_parents($result['path'] . $result['id']);
			$result['rights'] = $this->_get_rights($result['path']);
			
			if($result['rights']['view'] == 0) return FALSE;
			
			$result['childs'] = $this->_get_childs($result['id'], $result['child_level'], $result['child_sort_order']);
			$result['childs_count'] = count($result['childs']);
			
			unset($result['parent_topic_id']);
			unset($result['child_sort_order']);
			unset($result['child_level']);
			
		}
		
		return $result;
		
	}
	
	function _get_parents($path) {
		
		$result = NULL;
		
		$this->db->select('topics.url');
		$this->db->select('contents.name');
		$this->db->select('LENGTH('. $this->db->protect_identifiers('topics.path', TRUE) .') - LENGTH(REPLACE('. $this->db->protect_identifiers('topics.path', TRUE) .', \'-\', \'\')) AS level', FALSE);
		$this->db->select('permissions.*');
		$this->db->from('topics');
		$this->db->join('contents', 'contents.topic_id = topics.id');
		$this->db->join('permissions', 'permissions.topic_id = topics.id AND '. $this->db->protect_identifiers('permissions.group_id', TRUE) .' = '. $this->sess->group_id, 'left');
		$this->db->where_in('topics.id', explode('-', $path));
		$this->db->order_by('level');

		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			$result = $query->result_array();
		}
		
		return $result;
		
	}
	
	function _get_childs($parent_id, $current_level, $sort_order = 'DATE_DESC') {
		
		$result = array();
		
		if($current_level > 0) {
			$this->db->select('topics.id');
			$this->db->select('topics.create_time');
			$this->db->select('contents.create_time AS edit_time');
			$this->db->select('topics.url');
			$this->db->select('topics.path');
			$this->db->select('contents.name');
			$this->db->select('contents.content');
			$this->db->select('users.username');
			$this->db->select('users.id AS user_id');
			$this->db->from('topics');
			$this->db->join('contents', 'contents.topic_id = topics.id');
			$this->db->join('users', 'users.id = contents.user_id');
			$this->db->where('topics.parent_topic_id', (int) $parent_id);
			if($sort_order == 'DATE') $this->db->order_by('contents.create_time');
			if($sort_order == 'DATE_DESC') $this->db->order_by('contents.create_time DESC');
			if($sort_order == 'ORDINAR') $this->db->order_by('topics.ordinar');
			if($sort_order == 'ORDINAR_DESC') $this->db->order_by('topics.ordinar DESC');
			$this->db->limit(100);
			
			$query = $this->db->get();
			
			if($query->num_rows() > 0) {
				foreach($query->result_array() as $row) {
					$rights = $this->_get_rights($this->_get_parents($row['path'] . $row['id']));
					
					if($rights['view'] != 0) {
						$result[$row['id']] = $row;
						$result[$row['id']]['new'] = FALSE;
						$result[$row['id']]['sticky'] = FALSE;
						$result[$row['id']]['last_child'] = $this->_get_last_topic($row['path'] . $row['id']);
						$result[$row['id']]['childs_count'] = $this->_get_childs_count($row['id']);
						$result[$row['id']]['childs'] = $this->_get_childs($row['id'], $current_level - 1);
					}
				}
			}
		}
		
		return $result;
		
	}
	
	function _get_last_topic($path) {
		
		$result = NULL;
		
		$this->db->select('topics.url');
		$this->db->select('users.username');
		$this->db->select('topics.create_time');
		$this->db->from('topics');
		$this->db->join('contents', 'contents.topic_id = topics.id');
		$this->db->join('users', 'users.id = contents.user_id');
		$this->db->like('topics.path', $path .'-', 'after');
		$this->db->order_by('topics.create_time DESC');
		$this->db->limit(1);

		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			$result = $query->row_array();
		}
		
		return $result;
		
	}
	
	function _get_childs_count($parent_id) {
		
		$result = NULL;
		
		$this->db->select('COUNT(*) rowcount');
		$this->db->from('topics');
		$this->db->where('parent_topic_id', $parent_id);
		$this->db->limit(1);

		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			$row = $query->row();
			$result = $row->rowcount;
		}
		
		return $result;
		
	}
	
	function _get_rights($parents) {
		
		$result = NULL;
		
		foreach($parents as $parent) {
			if($parent['view'] != NULL) $result['view'] = $parent['view'];
			if($parent['add_child'] != NULL) $result['add_child'] = $parent['add_child'];
		}
		
		return $result;
		
	}
	
	function get_recent_topics($paths) {
		
		$result = array();
		
		$this->db->select('topics.id');
		$this->db->select('topics.parent_topic_id');
		$this->db->select('topics.create_time');
		$this->db->select('contents.create_time AS edit_time');
		$this->db->select('topics.url');
		$this->db->select('topics.path');
		$this->db->select('contents.name');
		$this->db->select('users.username');
		$this->db->select('users.id AS user_id');
		$this->db->from('topics');
		$this->db->join('contents', 'contents.topic_id = topics.id');
		$this->db->join('users', 'users.id = contents.user_id');
		foreach($paths as $path) {
			$this->db->or_like('topics.path', $path .'-', 'after');
		}
		$this->db->group_by('topics.parent_topic_id');
		$this->db->order_by('contents.create_time DESC');
		$this->db->limit(21);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$rights = $this->_get_rights($this->_get_parents($row['path'] . $row['id']));
				
				if($rights['view'] != 0) {
					$result[$row['id']] = $row;
					$result[$row['id']]['new'] = FALSE;
					$result[$row['id']]['childs_count'] = FALSE;
				}
			}
		}
		
		return $result;
		
	}
}

?>