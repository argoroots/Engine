<?php

class Topic_model extends Model {

	function __construct() {
		parent::Model();
	}



	function get_by_url($url = NULL) {
		
		$this->db->select('id');
		$this->db->from('topics');
		$this->db->where('url', $url);
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			$row = $query->row();
			$data = $this->get_by_id($row->id);
		} else {
			$data = array();
		}
	
		return $data;

	}



	function get_by_id($id = NULL) {

		$this->db->select('contents.topic_id, topics.parent_topic_id, topics.url, contents.create_time, contents.name, contents.content');
		$this->db->from('contents');
		$this->db->join('topics', 'contents.topic_id = topics.id');
		$this->db->where($this->db->protect_identifiers('contents.id', TRUE), '(SELECT MAX(`id`) FROM '. $this->db->protect_identifiers('contents', TRUE) .' WHERE `topic_id` = '. $this->db->protect_identifiers('topics.id', TRUE) .')', FALSE);
		$this->db->where('topics.id', $id);
		$this->db->order_by('contents.name'); 
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result_array() as $row) {
				$data = $row;
				$data['childs'] = $this->_get_child($row['topic_id']);
			}
		} else {
			$data = $this->_get_child();
		}
		
		return $data;

	}



	function _get_child($parent_id = NULL) {

		$this->db->select('id');
		$this->db->from('topics');
		$this->db->where('parent_topic_id', $parent_id);
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[$row->id] = $this->get_by_id($row->id);
			}
		} else {
			$data = array();
		}
	
		return $data;

	}



}

?>