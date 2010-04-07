<?php

class Xxx extends Controller {
	
	var $parents = array();

	function __construct() {
		parent::Controller();
		
		//exit('EEIIIIIIII');
		
		set_time_limit(1600);
		ini_set("memory_limit","192M");
		
		if(!$this->session->userdata('xxx_time')) $this->session->set_userdata('xxx_time', time());
		
		$this->db->save_queries = FALSE;
		
		//$this->output->enable_profiler(TRUE);
		
	}
	
	function pw() {
		exit();
		
		$this->db->select('username');
		$this->db->select('salt');
		$this->db->select('password');
		$this->db->from('pun_users');
		$this->db->where('username', 'test');
		$query = $this->db->get();
		
		$result = array_shift($query->result_array());
		$result['passwor1'] = sha1($result['salt'].sha1('arx123'));
		print_r($result);
	}
	
	function s() {
		
		$this->load->database('xxx');
		
		$this->db->truncate('e_migration');
		$this->db->truncate('e_contents');
		$this->db->truncate('e_topics');
		$this->db->truncate('e_users');
		$this->db->truncate('e_groups');
		
		header('Location: '. site_url('xxx/s1'));
		//echo '<a href="'. site_url('xxx/s1') .'">Import users</a>';
		
	}
		
	function s1() { // users
		
		
		//groups
		$this->db->select('g_id AS id');
		$this->db->select('g_title');
		$this->db->from('pun_groups');
		$this->db->order_by('g_id');
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$this->db->set('name', $row->g_title);
				$this->db->insert('e_groups');
				$newid = $this->db->insert_id();
				
				$this->db->set('pun_id', (int) $row->id);
				$this->db->set('e_id', (int) $newid);
				$this->db->set('type', 'group');
				$this->db->insert('e_migration');
			}
		}
		
		//users
		$this->db->select('pun_users.id');
		$this->db->select('e_migration.e_id group_id');
		$this->db->select('pun_users.username');
		$this->db->select('pun_users.email');
		$this->db->select('IFNULL(pun_users.realname, \'\') realname', FALSE);
		$this->db->select('pun_users.registered');
		$this->db->select('pun_users.last_visit');
		$this->db->from('pun_users');
		$this->db->join('e_migration', 'e_migration.pun_id = pun_users.group_id AND e_migration.type = \'group\'');
		$this->db->order_by('pun_users.id');
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$this->db->set('create_time', (int) $row->registered);
				$this->db->set('group_id', (int) $row->group_id);
				$this->db->set('username', $row->username);
				$this->db->set('email', $row->email);
				$this->db->set('name', $row->realname);
				$this->db->set('last_visit_time', (int) $row->last_visit);
				$this->db->insert('e_users');
				$newid = $this->db->insert_id();
				
				$this->db->set('pun_id', (int) $row->id);
				$this->db->set('e_id', (int) $newid);
				$this->db->set('type', 'user');
				$this->db->insert('e_migration');
			}
		}
		
		header('Location: '. site_url('xxx/s2'));
		//echo '<a href="'. site_url('xxx/s2') .'">Import avatars</a>';
	}
	
	function s2() { // Avatars
		
		$this->db->select('e_migration.e_id');
		$this->db->select('e_migration.pun_id');
		$this->db->from('e_migration');
		$this->db->where('e_migration.type', 'user');
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$image = NULL;
				if(file_exists(APPPATH .'../../foorum/img/avatars/'. $row->pun_id .'.gif')) $image = APPPATH .'../../foorum/img/avatars/'. $row->pun_id .'.gif';
				if(file_exists(APPPATH .'../../foorum/img/avatars/'. $row->pun_id .'.jpg')) $image = APPPATH .'../../foorum/img/avatars/'. $row->pun_id .'.jpg';
				if(file_exists(APPPATH .'../../foorum/img/avatars/'. $row->pun_id .'.png')) $image = APPPATH .'../../foorum/img/avatars/'. $row->pun_id .'.png';
				
				if($image) {
					$im = new Imagick($image);
					$im->setImageFormat('png');
					$im->thumbnailImage(72, 72, FALSE);
					file_put_contents(APPPATH .'../images/avatars/'. $row->e_id .'.png', $im);
				}
			}
		}
		
		header('Location: '. site_url('xxx/s3'));
		//echo '<a href="'. site_url('xxx/s3') .'">Import forum structure</a>';
	}
	
	function s3() { // forum structure
		
		$this->db->simple_query('INSERT INTO e_topics (id, parent_topic_id, template_id, url) VALUES(1, 0, 1, \'startpage\');');
		$this->db->simple_query('INSERT INTO e_topics (id, parent_topic_id, template_id, url) VALUES(2, 1, 2, \'uudised\');');
		$this->db->simple_query('INSERT INTO e_topics (id, parent_topic_id, template_id, url) VALUES(3, 1, 3, \'foorum\');');
		$this->db->simple_query('INSERT INTO e_topics (id, parent_topic_id, template_id, url) VALUES(4, 1, 4, \'wiki\');');
		$this->db->simple_query('INSERT INTO e_topics (id, parent_topic_id, template_id, url) VALUES(5, 1, 5, \'info\');');
		
		$this->db->simple_query('INSERT INTO e_contents (id, topic_id, user_id, name) VALUES(1, 1, 2, \'eMug\');');
		$this->db->simple_query('INSERT INTO e_contents (id, topic_id, user_id, name) VALUES(2, 2, 2, \'Uudised\');');
		$this->db->simple_query('INSERT INTO e_contents (id, topic_id, user_id, name) VALUES(3, 3, 2, \'Foorum\');');
		$this->db->simple_query('INSERT INTO e_contents (id, topic_id, user_id, name) VALUES(4, 4, 2, \'Wiki\');');
		$this->db->simple_query('INSERT INTO e_contents (id, topic_id, user_id, name) VALUES(5, 5, 2, \'Info\');');
		
		//categories
		$this->db->select('id');
		$this->db->select('cat_name');
		$this->db->select('disp_position');
		$this->db->from('pun_categories');
		$this->db->order_by('disp_position');
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$this->db->set('parent_topic_id', 3);
				$this->db->set('template_id', 6);
				$this->db->set('url', $this->_url($row->cat_name));
				$this->db->insert('e_topics');
				$newid = $this->db->insert_id();
				
				$this->db->set('topic_id', (int) $newid);
				$this->db->set('user_id', 2);
				$this->db->set('name', $row->cat_name);
				$this->db->insert('e_contents');
				
				$this->db->set('pun_id', (int) $row->id);
				$this->db->set('e_id', (int) $newid);
				$this->db->set('type', 'category');
				$this->db->insert('e_migration');
				
			}
		}
		
		//forums
		$this->db->select('pun_forums.id');
		$this->db->select('pun_forums.forum_name');
		$this->db->select('pun_forums.forum_desc');
		$this->db->select('e_migration.e_id');
		$this->db->select('pun_forums.disp_position');
		$this->db->from('pun_forums');
		$this->db->join('e_migration', 'e_migration.pun_id = pun_forums.cat_id AND e_migration.type = \'category\'');
		$this->db->order_by('pun_forums.disp_position');
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$url = $this->_url($row->forum_name);
				$this->db->set('parent_topic_id', (int) $row->e_id);
				$this->db->set('template_id', 7);
				$this->db->set('url', $url);
				$this->db->insert('e_topics');
				$newid = $this->db->insert_id();
				
				$this->db->set('topic_id', (int) $newid);
				$this->db->set('user_id', 2);
				$this->db->set('name', $row->forum_name);
				$this->db->set('content', $row->forum_desc);
				$this->db->insert('e_contents');
				
				$this->db->set('pun_id', (int) $row->id);
				$this->db->set('e_id', (int) $newid);
				$this->db->set('type', 'forum');
				$this->db->insert('e_migration');
				
			}
		}
		
		$this->db->simple_query('INSERT INTO e_permissions (topic_id, group_id, view, add_child) VALUES(1, 1, 1, 0);');
		$this->db->simple_query('INSERT INTO e_permissions (topic_id, group_id, view, add_child) VALUES(1, 2, 1, 0);');
		$this->db->simple_query('INSERT INTO e_permissions (topic_id, group_id, view, add_child) VALUES(1, 3, 1, 0);');
		$this->db->simple_query('INSERT INTO e_permissions (topic_id, group_id, view, add_child) VALUES(1, 4, 1, 0);');
		$this->db->simple_query('INSERT INTO e_permissions (topic_id, group_id, view, add_child) VALUES(1, 5, 1, 0);');
		$this->db->simple_query('INSERT INTO e_permissions (topic_id, group_id, view, add_child) VALUES(1, 6, 1, 0);');
		$this->db->simple_query('INSERT INTO e_permissions (topic_id, group_id, view, add_child) VALUES(1, 7, 1, 0);');
		$this->db->simple_query('INSERT INTO e_permissions (topic_id, group_id, view, add_child) VALUES(6, 2, 0, 0);');
		$this->db->simple_query('INSERT INTO e_permissions (topic_id, group_id, view, add_child) VALUES(6, 3, 0, 0);');
		$this->db->simple_query('INSERT INTO e_permissions (topic_id, group_id, view, add_child) VALUES(6, 5, 0, 0);');
		$this->db->simple_query('INSERT INTO e_permissions (topic_id, group_id, view, add_child) VALUES(6, 6, 0, 0);');
		$this->db->simple_query('INSERT INTO e_permissions (topic_id, group_id, view, add_child) VALUES(6, 7, 0, 0);');
		$this->db->simple_query('INSERT INTO e_permissions (topic_id, group_id, view, add_child) VALUES(14, 4, 0, 0);');
		
		header('Location: '. site_url('xxx/s4'));
		//echo '<a href="'. site_url('xxx/s4') .'">Import topics</a>';
	}
	
	function s4() { // topics
		
		$this->db->select('pun_topics.id');
		$this->db->select('pun_topics.subject');
		$this->db->select('pun_posts.message');
		$this->db->select('IFNULL(pun_posts.poster_ip, \'\') poster_ip', FALSE);
		$this->db->select('pun_posts.posted');
		$this->db->select('e_migration.e_id');
		$this->db->select('user.e_id AS user_id');
		$this->db->from('pun_topics');
		$this->db->join('pun_posts', 'pun_posts.id = pun_topics.first_post_id');
		$this->db->join('e_migration', 'e_migration.pun_id = pun_topics.forum_id AND e_migration.type = \'forum\'');
		$this->db->join('e_migration AS user', 'user.pun_id = pun_posts.poster_id AND user.type = \'user\'');
		$this->db->order_by('pun_topics.posted');
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$this->db->set('create_time', (int) $row->posted);
				$this->db->set('parent_topic_id', (int) $row->e_id);
				$this->db->set('template_id', 8);
				$this->db->set('url', 'topic-'. $row->id);
				$this->db->insert('e_topics');
				$newid = $this->db->insert_id();
				
				$this->db->set('create_time', (int) $row->posted);
				$this->db->set('topic_id', (int) $newid);
				$this->db->set('user_id', (int) $row->user_id);
				$this->db->set('name', $row->subject);
				$this->db->set('content', $row->message);
				$this->db->set('ip', $row->poster_ip);
				$this->db->insert('e_contents');
				
				$this->db->set('pun_id', (int) $row->id);
				$this->db->set('e_id', (int) $newid);
				$this->db->set('type', 'topic');
				$this->db->insert('e_migration');
				
			}
		}
		
		header('Location: '. site_url('xxx/s5'));
		//echo '<a href="'. site_url('xxx/s5') .'">Import posts</a>';
	}
	
	function s5() { // posts
		
		$this->db->select('pun_posts.id');
		$this->db->select('pun_topics.subject');
		$this->db->select('pun_posts.message');
		$this->db->select('IFNULL(pun_posts.poster_ip, \'\') poster_ip', FALSE);
		$this->db->select('pun_posts.posted');
		$this->db->select('e_migration.e_id');
		$this->db->select('user.e_id AS user_id');
		$this->db->from('pun_topics');
		$this->db->join('pun_posts', 'pun_posts.topic_id = pun_topics.id AND pun_posts.id <> pun_topics.first_post_id');
		$this->db->join('e_migration', 'e_migration.pun_id = pun_posts.topic_id AND e_migration.type = \'topic\'');
		$this->db->join('e_migration AS user', 'user.pun_id = pun_posts.poster_id AND user.type = \'user\'');
		$this->db->where('pun_posts.id NOT IN (SELECT pun_id FROM e_migration WHERE type = \'post\')');
		$this->db->order_by('pun_posts.posted');
		$this->db->limit(25000);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$this->db->set('create_time', (int) $row->posted);
				$this->db->set('parent_topic_id', (int) $row->e_id);
				$this->db->set('template_id', 9);
				$this->db->set('url', 'post-'. $row->id);
				$this->db->insert('e_topics');
				$newid = $this->db->insert_id();
				
				$this->db->set('create_time', (int) $row->posted);
				$this->db->set('topic_id', (int) $newid);
				$this->db->set('user_id', (int) $row->user_id);
				$this->db->set('name', $row->subject);
				$this->db->set('content', $row->message);
				$this->db->set('ip', $row->poster_ip);
				$this->db->insert('e_contents');
				
				$this->db->set('pun_id', (int) $row->id);
				$this->db->set('e_id', (int) $newid);
				$this->db->set('type', 'post');
				$this->db->insert('e_migration');
				
			}
		}
		
		if($query->num_rows() > 0) {
			header('Location: '. site_url('xxx/s5'));
		} else {
			header('Location: '. site_url('xxx/s6'));
			//echo '<a href="'. site_url('xxx/s6') .'">Finalize import</a>';
		}
	}
	
	function s6() { // set topics paths
		
		$this->db->distinct();
		$this->db->select('parent_topic_id');
		$this->db->from('e_topics');
		
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$this->parents = array();
				$this->_set_parents($row->parent_topic_id);
				
				$this->db->set('path', implode('-', $this->parents) .'-');
				$this->db->where('parent_topic_id', (int) $row->parent_topic_id);
				$this->db->update('e_topics');
			}
		}
		
		header('Location: '. site_url('xxx/s7'));
		//echo '<a href="'. site_url('xxx/s7') .'">Set topics urls</a>';
	}
	
	function s7() { // set urls
		
		$this->db->simple_query('UPDATE e_topics SET url = CONCAT(\'teema-\', id) WHERE id IN (SELECT e_id FROM e_migration WHERE type = \'topic\');');
		$this->db->simple_query('UPDATE e_topics SET url = CONCAT(\'teema-\', parent_topic_id, \'#\', id) WHERE id IN (SELECT e_id FROM e_migration WHERE type = \'post\');');
		
		$time = round((time() - $this->session->userdata('xxx_time')) / 60);
		$this->session->unset_userdata('xxx_time');
		echo 'All done ('. $time .' min)!';
	}
	
	
	
	
	function _set_parents($topic_id) {
		
		$this->db->select('id');
		$this->db->select('parent_topic_id');
		$this->db->from('e_topics');
		$this->db->where('id', (int) $topic_id);
		$this->db->limit(1);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			$row = $query->row();
			if($row->parent_topic_id) $this->_set_parents($row->parent_topic_id);
			$this->parents[] = $row->id;
		}
		
	}
	
	function _url($name) {
		
		$url = strtolower(url_title($name));
		
		$key_ok = FALSE;
		$url2 = $url;
		
		while($key_ok == FALSE) {
			$this->db->select('COUNT(*) AS rows');
			$this->db->from('e_topics');
			$this->db->where('url', $url2);
			$this->db->limit(1);
			
			$query = $this->db->get();
			
			if($query->num_rows() > 0) {
				$row = $query->row();
				if($row->rows == 0) {
					$key_ok = TRUE;
				} else {
					$url2 = $url .'-'. ($row->rows + 1);
				}
			}
		}
		
		
		return $url2;
		
	}

}