<?php
class Group_model extends CI_Model {

	function create_group($groupName, $description){	
		$groupData = array(
			'name' => $groupName,
			'description' => $description
		);
		return($this->db->insert('Group', $groupData));
	}

	function delete_group($name){
		$this->db->where('name', $name);
		$this->db->delete('Group');
	}

	function get_group($groupName){
		$this->db->where('name', $groupName);
		return($this->db->get('Group'));	
	}

	function get_num_members($groupName) {
		$this->db->where('groupName', $groupName);
		return($this->db->get('MemberList')->num_rows());
	}

	function is_member($userName, $groupName){
		$this->db->where('userName', $userName);
		$this->db->where('groupName', $groupName);
		return($this->db->get('MemberList')->num_rows() == 1);
	}

	function pagination($limit, $offset, $sort_by, $sort_order){
		$sort_order = ($sort_order == 'asc') ? 'asc' : 'desc';
		$sort_columns = array('name', 'description', 'numMembers', 'dateCreated');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'dateCreated';

		$data['total_rows'] = $this->db->get('Discussion')->num_rows();

		$query = "
			SELECT `Group`.*, COUNT(MemberList.groupName) as numMembers
			FROM `Group`
			LEFT JOIN MemberList ON `Group`.name = MemberList.groupName
			GROUP BY `Group`.name
			ORDER BY $sort_by $sort_order
			LIMIT $limit
			OFFSET $offset";

		$data['groups'] = $this->db->query($query);

		return($data);
	}
}
