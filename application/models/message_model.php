<?php

class Message_model extends CI_Model{

	function send_message($senderName, $receiverName, $subject, $body, $parentID, $reply, $messageState){

    $this->load->model('message_model');

		if($reply){
			$this->db->where('ID', $parentID);
			$this->db->update('Message', array('vis' => 3));
		}

		$message_insert_data = array(
			'senderName' => $senderName,
			'receiverName' => $receiverName,
			'subject' => $subject,
			'body' => $body,
	    'messageState' => $messageState
		);

		if(!$this->db->insert('Message', $message_insert_data)) return(0);
		$childID = $this->db->insert_id();

		$query = $this->db->query("SELECT length, parentID
															 FROM MTree
															 WHERE childID = $parentID
															");

		foreach($query->result() as $row){
			$insert = array(
				'parentID' => $row->parentID, 
				'childID' => $childID,
				'length' => ($row->length + 1));
				

			if(!$this->db->insert('MTree', $insert)) return(0);
		}

		$insert = array(
			'parentID' => $childID, 
			'childID' => $childID,
			'length' => 0);

		return($this->db->insert('MTree', $insert));
	}

	function box_pagination($limit, $offset, $sort_by, $sort_order, $userName){
		$sort_order = ($sort_order == 'asc') ? 'asc' : 'desc';
		$sort_columns = array('senderName','receiverName', 'dateCreated', 'subject', 'body');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'dateCreated';

		$query = "SELECT * FROM
								(SELECT *, COUNT(*) AS cnt
								 FROM Message
								 LEFT JOIN MTree ON Message.ID = MTree.childID
								 WHERE 1
								 GROUP BY MTree.childID) as testTable
							WHERE 
								(( (receiverName = '$userName') AND (vis=3 OR vis=1) ) 
								OR
								( (senderName = '$userName') AND (vis=3 OR vis=2) ))
							AND cnt = 1
							ORDER BY $sort_by $sort_order
							LIMIT $limit
							OFFSET $offset";

		$result = $this->db->query($query);

		$data['total_rows'] = $result->num_rows();
		$data['messages'] = $result;

		return($data);
	}

	function get_message_by_id($id){
		$this->db->where('ID', $id);
		$this->db->select('ID, senderName, receiverName, dateCreated, subject, body, vis, messageState');
		$query = $this->db->get('Message');
		if($query->num_rows() != 1) return(FALSE);
		else return($query);
	}

	function get_thread($message_id, $offset){
		$query = $this->db->query("
			SELECT Message.*
			FROM Message
			LEFT JOIN MTree ON (Message.ID = MTree.childID)
			WHERE MTree.parentID = $message_id
			ORDER BY dateCreated DESC 
			LIMIT 5 OFFSET $offset
		");
		$count = $this->db->query("
			SELECT Message.*
			FROM Message
			LEFT JOIN MTree ON (Message.ID = MTree.childID)
			WHERE MTree.parentID = $message_id
			ORDER BY dateCreated  
		");

		$data['query'] = $query;
		$data['total'] = $count->num_rows();
		return($data);
	}

	function remove_messages($messages, $userName){
		foreach($messages as $m){
			$query = $this->get_message_by_id($m);


			if($query->row()->vis != 3 || ($query->row()->senderName == $userName && $query->row()->receiverName == $userName)){
				$q = 'SELECT childID FROM MTree WHERE parentID = '.$query->row()->ID;
				$r = $this->db->query($q);

				foreach($r->result() as $r2)
					$deleteMessages[] = $r2->childID; 
				
				$q = 'DELETE FROM Message WHERE ID IN (';
				
				foreach($deleteMessages as $r3)
					$q .= $r3.',';
				
				$q = rtrim($q, ",");
				$q .= ')';
				$thread = $this->db->query($q);
			}
			else if($query->row()->senderName == $userName){
				$this->db->where('ID', $m);
				$this->db->update('Message', array('vis' => 1));
			}
			else{
				$this->db->where('ID', $m);
				$this->db->update('Message', array('vis' => 2));
			}
		}
	}

	function delete_message($fields) {
		foreach($fields as $field) {
			$this->db->where(key($fields), $field);
			next($fields);
		}
		
		$this->db->delete('Message');
	}

  function set_state($messageID, $messageState){
		$this->db->where('ID', $messageID);
		$this->db->update('Message', array('messageState' => $messageState)); 
	}

	function get_message($fields) {
		foreach($fields as $field) {
			$this->db->where(key($fields), $field);
			next($fields);
		}
		
		return ($this->db->get('Message'));
	}

}

