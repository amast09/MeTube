<?php
class Discussion_model extends CI_Model {

	function create_discussion($groupName, $userName, $subject, $body){	
		$discussionData = array(
			'groupName' => $groupName,
			'userName' => $userName,
			'subject' => $subject,
			'body' => $body
		);
		return($this->db->insert('Discussion', $discussionData));
	}

	function delete_discussion($discussionID){
		$this->db->where('ID', $discussionID);
		$this->db->delete('Discussion');
	}
	
	function get_discussion($groupName, $id){
		$this->db->where('groupName', $groupName);
		$this->db->where('ID', $id);
		return($this->db->get('Discussion'));
	}

	function pagination($groupName, $limit, $offset, $sort_by, $sort_order){
		$sort_order = ($sort_order == 'asc') ? 'asc' : 'desc';
		$sort_columns = array('userName', 'subject', 'dateCreated', 'num');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'dateCreated';

		$this->db->where('groupName', $groupName);
		$data['total_rows'] = $this->db->get('Discussion')->num_rows();
	
		$data['discussions'] = $this->db->query("
			SELECT disc.ID, disc.userName, disc.subject, disc.dateCreated, COUNT(comm.itemID) as num
			FROM Discussion AS disc
			LEFT JOIN DiscussionComment AS comm
			ON comm.itemID = disc.ID
			WHERE disc.groupName = '$groupName'
			GROUP BY disc.ID
			ORDER BY $sort_by $sort_order
			LIMIT $limit
			OFFSET $offset");
		return($data);
	}

	function get_discussions($groupName, $limit){
		

	}
}
