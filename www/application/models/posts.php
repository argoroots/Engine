<?php

class Posts extends Model {

	function __construct() {
		parent::Model();
		
		$this->db->simple_query('SET NAMES \'utf8\'');
	}


	function get_by_url($url, $user) {
		
		$result = FALSE;
		
		$url_array = explode('-', $url);
		
		//print_r($url_array);
		
		switch ($url_array[0]) {
		    case 'uudised':
				$result = $this->_uudised($user);
				break;
		    case 'foorum':
				$result = $this->_foorum($user);
				break;
		    case 'kategooria':
				$result = $this->_kategooria($url_array[1], $user);
				break;
		    case 'teema':
				$result = $this->_teema($url_array[1], $user);
				break;
		    case 'info':
				$result = array(
					'id' => NULL,
					'url' => 'info',
					'template' => 'info/info',
					'menu_selected' => 'info',
					'date' => NULL,
					'new' => NULL,
					'user' => NULL,
					'user_id' => NULL,
					'name' => NULL,
					'content' => NULL,
					'last_cild_date' => NULL,
					'last_cild_id' => NULL,
					'last_cild_user_id' => NULL,
					'last_cild_user' => NULL,
					'childs_count' => NULL,
				);
				break;
			default:
				break;
		}
		
		return $result;
		
	}
	
	function get_recent($user) {
		
		$this->db->select('pun_topics.id');
		$this->db->select('pun_topics.subject');
		$this->db->select('pun_topics.last_post');
		$this->db->select('pun_topics.last_post_id');
		$this->db->select('pun_topics.last_poster');
		$this->db->select('pun_topics.num_replies');
		$this->db->from('pun_topics');
		$this->db->where('pun_topics.forum_id NOT IN (SELECT forum_id FROM pun_forum_perms, pun_users WHERE pun_users.group_id = pun_forum_perms.group_id AND pun_forum_perms.read_forum = 0 AND pun_users.id = '. (int) $user .')');
		$this->db->where('pun_topics.forum_id NOT IN (24, 25)');
		$this->db->order_by('pun_topics.last_post DESC');
		$this->db->limit(19);
		
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$result[$row->id] = array(
							'id' => $row->id,
							'url' => 'teema-'. $row->id .'#'. $row->last_post_id,
							'date' => $row->last_post,
							'new' => $this->_is_new_post($row->last_post),
							'user' => $row->last_poster,
							'user_id' => NULL,
							'name' => $row->subject,
							'childs_count' => $row->num_replies,
				);
			}
		}
		
		return $result;

	}
	
	
	
	
	
	
	function _uudised($user) {
		
		$result = array(
			'id' => NULL,
			'url' => 'uudised',
			'template' => 'news/news',
			'menu_selected' => 'uudised',
			'date' => NULL,
			'new' => NULL,
			'user' => NULL,
			'user_id' => NULL,
			'name' => NULL,
			'content' => NULL,
			'last_cild_date' => NULL,
			'last_cild_id' => NULL,
			'last_cild_user_id' => NULL,
			'last_cild_user' => NULL,
			'childs_count' => NULL,
		);
		
		$this->db->select('pun_topics.id');
		$this->db->select('pun_topics.subject');
		$this->db->select('pun_topics.poster');
		$this->db->select('pun_topics.posted');
		$this->db->select('pun_topics.last_post');
		$this->db->select('pun_topics.last_post_id');
		$this->db->select('pun_topics.last_poster');
		$this->db->select('pun_topics.num_replies');
		$this->db->select('pun_topics.sticky');
		$this->db->select('pun_posts.message');
		$this->db->from('pun_posts');
		$this->db->join('pun_topics', 'pun_topics.first_post_id = pun_posts.id');
		$this->db->where('pun_topics.forum_id NOT IN (SELECT forum_id FROM pun_forum_perms, pun_users WHERE pun_users.group_id = pun_forum_perms.group_id AND pun_forum_perms.read_forum = 0 AND pun_users.id = '. (int) $user .')');
		$this->db->where('pun_topics.forum_id', 32);
		$this->db->order_by('pun_topics.posted DESC');
		$this->db->limit(12);

		
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$result['childs'][$row->id] = array(
					'id' => $row->id,
					'url' => 'teema-'. $row->id,
					'date' => $row->posted,
					'new' => $this->_is_new_post($row->last_post),
					'user' => $row->poster,
					'user_id' => NULL,
					'name' => $row->subject,
					'content' => $row->message,
					'sticky' => $row->sticky,
					'last_cild_url' => 'teema-'. $row->id .'#'. $row->last_post_id,
					'last_cild_date' => $row->last_post,
					'last_cild_id' => $row->last_post_id,
					'last_cild_user_id' => NULL,
					'last_cild_user' => $row->last_poster,
					'childs_count' => $row->num_replies,
				);
			}
		}
		
		return $result;
	}

	function _foorum($user) {
		
		$result = array(
			'id' => NULL,
			'url' => 'foorum',
			'template' => 'forum/forums',
			'menu_selected' => 'foorum',
			'date' => NULL,
			'new' => NULL,
			'user' => NULL,
			'user_id' => NULL,
			'name' => 'Foorum',
			'content' => NULL,
			'last_cild_date' => NULL,
			'last_cild_id' => NULL,
			'last_cild_user_id' => NULL,
			'last_cild_user' => NULL,
			'childs_count' => NULL,
		);
		
		$this->db->select('pun_forums.id');
		$this->db->select('pun_forums.cat_id');
		$this->db->select('pun_categories.cat_name');
		$this->db->select('pun_forums.forum_name');
		$this->db->select('pun_forums.forum_desc');
		$this->db->select('pun_forums.last_post');
		$this->db->select('pun_forums.last_post_id');
		$this->db->select('pun_forums.last_poster');
		$this->db->select('pun_forums.num_topics');
		$this->db->from('pun_forums');
		$this->db->join('pun_categories', 'pun_categories.id = pun_forums.cat_id');
		$this->db->where('pun_forums.id NOT IN (SELECT forum_id FROM pun_forum_perms, pun_users WHERE pun_users.group_id = pun_forum_perms.group_id AND pun_forum_perms.read_forum = 0 AND pun_users.id = '. (int) $user .')');
		$this->db->order_by('pun_categories.disp_position');
		$this->db->order_by('pun_forums.disp_position');
		
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {

				if(!isset($result['childs'][$row->cat_id])) $result['childs'][$row->cat_id] = array(
					'id' => $row->cat_id,
					'url' => NULL,
					'date' => NULL,
					'new' => NULL,
					'user' => NULL,
					'user_id' => NULL,
					'name' => $row->cat_name,
					'content' => NULL,
					'sticky' => NULL,
					'last_cild_date' => NULL,
					'last_cild_id' => NULL,
					'last_cild_user_id' => NULL,
					'last_cild_user' => NULL,
					'childs_count' => NULL,
				);
				$result['childs'][$row->cat_id]['childs'][$row->id] = array(
							'id' => $row->id,
							'url' => 'kategooria-'. $row->id,
							'date' => NULL,
							'new' => $this->_is_new_post($row->last_post),
							'user' => NULL,
							'user_id' => NULL,
							'name' => $row->forum_name,
							'content' => $row->forum_desc,
							'sticky' => NULL,
							'last_cild_date' => $row->last_post,
							'last_cild_id' => $row->last_post_id,
							'last_cild_user_id' => NULL,
							'last_cild_user' => $row->last_poster,
							'childs_count' => $row->num_topics,
							'childs' => NULL,
				);
			}
		}
		
		return $result;
	}

	function _kategooria($id, $user) {
		
		$result = array(
			'id' => NULL,
			'url' => 'kategooria-'. $id,
			'template' => 'forum/topics',
			'menu_selected' => 'foorum',
			'date' => NULL,
			'new' => NULL,
			'user' => NULL,
			'user_id' => NULL,
			'name' => NULL,
			'content' => NULL,
			'last_cild_date' => NULL,
			'last_cild_id' => NULL,
			'last_cild_user_id' => NULL,
			'last_cild_user' => NULL,
			'childs_count' => NULL,
		);
		
		$this->db->select('pun_topics.id');
		$this->db->select('pun_topics.subject');
		$this->db->select('pun_topics.poster');
		$this->db->select('pun_topics.posted');
		$this->db->select('pun_topics.last_post');
		$this->db->select('pun_topics.last_post_id');
		$this->db->select('pun_topics.last_poster');
		$this->db->select('pun_topics.num_replies');
		$this->db->select('pun_topics.sticky');
		$this->db->select('pun_forums.forum_name');
		$this->db->from('pun_topics');
		$this->db->join('pun_forums', 'pun_forums.id = pun_topics.forum_id');
		$this->db->where('pun_topics.forum_id NOT IN (SELECT forum_id FROM pun_forum_perms, pun_users WHERE pun_users.group_id = pun_forum_perms.group_id AND pun_forum_perms.read_forum = 0 AND pun_users.id = '. (int) $user .')');
		$this->db->where('pun_topics.forum_id', (int) $id);
		$this->db->order_by('pun_topics.sticky DESC');
		$this->db->order_by('pun_topics.last_post DESC');

		
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$result['name'] = $row->forum_name;
				$result['childs'][$row->id] = array(
					'id' => $row->id,
					'url' => 'teema-'. $row->id,
					'date' => $row->posted,
					'new' => $this->_is_new_post($row->last_post),
					'user' => $row->poster,
					'user_id' => NULL,
					'name' => $row->subject,
					'content' => NULL,
					'sticky' => $row->sticky,
					'last_cild_url' => 'teema-'. $row->id .'#'. $row->last_post_id,
					'last_cild_date' => $row->last_post,
					'last_cild_id' => $row->last_post_id,
					'last_cild_user_id' => NULL,
					'last_cild_user' => $row->last_poster,
					'childs_count' => $row->num_replies,
				);
			}
		} else {
			return FALSE;
		}
		
		return $result;
	}

	function _teema($id, $user) {
		
		$result = array();
		$childs = array();
		
		$this->db->select('pun_posts.id');
		$this->db->select('pun_posts.poster');
		$this->db->select('pun_posts.poster_id');
		$this->db->select('pun_posts.message');
		$this->db->select('pun_posts.posted');
		$this->db->select('pun_topics.subject');
		$this->db->select('pun_posts.topic_id');
		$this->db->from('pun_posts');
		$this->db->join('pun_topics', 'pun_topics.id = pun_posts.topic_id');
		$this->db->join('pun_users', 'pun_users.id = pun_posts.poster_id', 'left');
		$this->db->where('pun_topics.forum_id NOT IN (SELECT forum_id FROM pun_forum_perms, pun_users WHERE pun_users.group_id = pun_forum_perms.group_id AND pun_forum_perms.read_forum = 0 AND pun_users.id = '. (int) $user .')');
		$this->db->where('pun_posts.id <> pun_topics.first_post_id');
		$this->db->where('pun_posts.topic_id', (int) $id);
		$this->db->order_by('pun_posts.posted');
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$childs[$row->id] = array(
					'id' => $row->id,
					'url' => 'teema-'. $row->topic_id .'#'. $row->id,
					'date' => $row->posted,
					'new' => NULL,
					'user' => $row->poster,
					'user_id' =>  $row->poster_id,
					'user_avatar' => NULL,
					'name' => NULL,
					'content' => $row->message,
					'sticky' => NULL,
					'last_cild_url' => NULL,
					'last_cild_date' => NULL,
					'last_cild_id' => NULL,
					'last_cild_user_id' => NULL,
					'last_cild_user' => NULL,
					'childs_count' => NULL,
				);
			}
		}
		
		$this->db->select('pun_posts.id');
		$this->db->select('pun_posts.poster');
		$this->db->select('pun_posts.poster_id');
		$this->db->select('pun_posts.message');
		$this->db->select('pun_posts.posted');
		$this->db->select('pun_topics.subject');
		$this->db->select('pun_posts.topic_id');
		$this->db->select('pun_topics.forum_id');
		$this->db->select('pun_forums.forum_name');
		$this->db->from('pun_posts');
		$this->db->join('pun_topics', 'pun_topics.first_post_id = pun_posts.id');
		$this->db->join('pun_forums', 'pun_forums.id = pun_topics.forum_id');
		$this->db->join('pun_users', 'pun_users.id = pun_posts.poster_id', 'left');
		$this->db->where('pun_topics.forum_id NOT IN (SELECT forum_id FROM pun_forum_perms, pun_users WHERE pun_users.group_id = pun_forum_perms.group_id AND pun_forum_perms.read_forum = 0 AND pun_users.id = '. (int) $user .')');
		$this->db->where('pun_posts.topic_id', (int) $id);
		$this->db->order_by('pun_posts.posted');
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$result = array(
					'id' => $row->id,
					'url' => 'teema-'. $id,
					'template' => 'forum/posts',
					'menu_selected' => 'foorum',
					'date' => $row->posted,
					'new' => NULL,
					'user' => $row->poster,
					'user_id' =>  $row->poster_id,
					'user_avatar' => NULL,
					'name' => $row->subject,
					'content' => $row->message,
					'sticky' => NULL,
					'parent_name' => $row->forum_name,
					'parent_url' => 'kategooria-'. $row->forum_id,
					'last_cild_url' => NULL,
					'last_cild_date' => NULL,
					'last_cild_id' => NULL,
					'last_cild_user_id' => NULL,
					'last_cild_user' => NULL,
					'childs_count' => NULL,
					'childs' => $childs,
				);
			}
		}
		
		return $result;
	}

	function _is_new_post($date) {
		return (!$this->sess->is_guest AND ($this->sess->last_visit - $date < 3600)) ? TRUE : FALSE;
	}

}

?>