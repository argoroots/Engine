<?php

class Topic_model extends Model {


	var $max_level = NULL;
	var $page = NULL;


	function __construct() {
		parent::Model();
	}



	function get_by_url($topic_url = NULL, $page = 1) {
		
		$this->page = $page;
		$data = $this->_get_topics(NULL, $topic_url);
		return $data;

	}



	function get_by_id($topic_id = NULL, $page = 1) {
		
		$this->page = $page;
		$data = $this->_get_topics($topic_id, NULL);
		return $data;

	}



	function _get_topics($topic_id = NULL, $topic_url = NULL, $level = 0, $hide_fields = NULL) {

		$data = array();
		
		if(!is_array($hide_fields)) $hide_fields = array();
		
		$this->db->select('contents.topic_id AS id');
		$this->db->select('topics.parent_topic_id AS parent_id');
		$this->db->select('topics.url');
		$this->db->select('contents.create_time AS date');
		$this->db->select('contents.name');
		if(!in_array('content', $hide_fields)) $this->db->select('contents.content');
		$this->db->select('(SELECT COUNT(id) FROM '. $this->db->protect_identifiers('topics', TRUE) .' WHERE parent_topic_id = '. $this->db->protect_identifiers('contents.topic_id', TRUE) .') AS child_count', FALSE);
		
		$this->db->from('contents');
		$this->db->join('topics', 'contents.topic_id = topics.id');
		
		$this->db->where($this->db->protect_identifiers('contents.id', TRUE), '(SELECT MAX(`id`) FROM '. $this->db->protect_identifiers('contents', TRUE) .' WHERE `topic_id` = '. $this->db->protect_identifiers('topics.id', TRUE) .')', FALSE);
		if($topic_id) {
			$this->db->where('topics.id', $topic_id);
		} else {
			$this->db->where('topics.url', $topic_url);
		}

		$this->db->order_by('contents.name'); 
		$query = $this->db->get();
		

		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				
				$data = $row;
				
				$template = $this->_get_template($row['id']);
				if(!$this->max_level AND $template['child_level']) $this->max_level = $template['child_level'];
				$data['template_file'] = $template['template_file'];
				
				if(!in_array('level', $hide_fields)) $data['level'] = $level;
				if(!in_array('parents', $hide_fields)) $data['parents'] = $this->_get_parent($row['parent_id']);
				if(!in_array('childs', $hide_fields)) $data['childs'] = $this->_get_child($row['id'], $level, $template['child_level'], $template['child_sort_order']);
				 
				unset($data['child_level']);
			}
		}

		$query->free_result();
		
		return $data;

	}



	function _get_child($parent_id = NULL, $level = 0, $limit = NULL, $order = NULL) {

		$data = array();

		if($level < $this->max_level OR !$this->max_level) {
		
			$this->db->select('id');
			$this->db->from('topics');
			$this->db->where('parent_topic_id', $parent_id);
			if($limit) $this->db->limit($limit, ($limit*($this->page-1)));
			$query = $this->db->get();

			if($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					$data[$row->id] = $this->_get_topics($row->id, NULL, ($level+1), array('parents'));
				}
			}

			$query->free_result();
		}

		return $data;

	}



	function _get_parent($id = NULL) {

		$data = array();
	
		while ($id != NULL) {
			$data[$id] = $this->_get_topics($id, NULL, NULL, array('content', 'childs', 'parents', 'level', 'child_level'));
			$id = $data[$id]['parent_id'];
		}
		
		return $data;

	}



	function _get_template($topic_id) {

		$this->db->select('templates.template_file, templates.child_sort_order, templates.child_count, templates.child_level');
		$this->db->from('templates');
		$this->db->join('topics_templates', 'topics_templates.template_id = templates.id');
		$this->db->where('topics_templates.topic_id', $topic_id);
		$query = $this->db->get();

		$data = $query->row_array();

		$query->free_result();

		return $data;

	}



}

?>