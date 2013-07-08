<?php
class Comment_model extends CI_Model {

	function create_comment($table, $ID, $userName, $body){	
		$commentData = array(
			'userName' => $userName,
			'itemID' => $ID,
			'body' => $body
		);
		
		return(!$this->db->insert($table, $commentData));
	}

	function delete_comment($table, $ID){
		$this->db->where('ID', $ID);
		$this->db->delete($table);
	}
	
	function get_comment($table, $ID){
		$this->db->where('ID', $ID); 
		return($this->db->get($table));
	}

	function pagination($itemID, $table, $limit, $offset, $sort_by, $sort_order){
		$sort_order = ($sort_order == 'asc') ? 'asc' : 'desc';
		$sort_columns = array('dateCreated');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'dateCreated';

		$this->db->where('itemID', $itemID);
		$data['total_rows'] = $this->db->get($table)->num_rows();
	
		$this->db->where('itemID', $itemID);
		$this->db->select('userName, body, dateCreated, ID');
		$this->db->order_by($sort_by, $sort_order);
		$data['comments'] = $this->db->get($table, $limit, $offset);
		return($data);
	}

}
