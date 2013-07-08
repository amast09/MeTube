<?php

class Account_model extends CI_Model{

	// verifies login information
	function validate($userName, $password){
		$this->db->where('userName', $userName);
		$this->db->where('password', $password);
		$query = $this->db->get('Account');
		return($query->num_rows() == 1);
	}

	// make sure the given userName exists
	function valid_account($userName){
		$this->db->where('userName', $userName);
		$query = $this->db->get('Account');
		return($query->num_rows() == 1);
	}

	// create an account and add it to the database
	function create_account($userName, $email, $password, $firstName, $lastName){
		$account_insert_data = array(
			'userName' => $userName,
			'email' => $email,
			'password' => $password,
			'firstName' => $firstName,
			'lastName' => $lastName
		);

		return($this->db->insert('Account', $account_insert_data));
	}


	// update account info
	function update_account($userName, $password, $firstName, $lastName) {
		$account_update_data = array(
			'firstName' => $firstName,
			'lastName' => $lastName,
			'password' => $password);

		$this->db->where('userName', $userName);
		return($this->db->update('Account', $account_update_data));
	} 

	// set session data that can be retrieved with session user data
	function set_session($userName) { 
		$this->db->where('userName', $userName);
		$query = $this->db->get('Account');
		$row = $query->row();
		$session_data = array('userName' => $row->userName,
													'firstName' => $row->firstName,
													'lastName' => $row->lastName,
													'logged_in' => true);
		return($session_data);
	}

	function pagination($userName, $fields, $limit, $offset, $sort_by, $sort_order){

		$sort_order = ($sort_order == 'asc') ? 'asc' : 'desc';
		$sort_columns = array('userName', 'views', 'downloads', 'subs', 'uploads');

		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'views';

		$query = "
			SELECT TT.*, SUM(Media.views) as views, SUM(Media.downloads) as downloads, COUNT(Media.userName) as uploads FROM 
				(SELECT Account.userName as userName, COUNT(SubscriberList.userName2) as subs 
				 FROM `Account` 
				 LEFT JOIN SubscriberList 
				 ON Account.userName = SubscriberList.userName2
				 GROUP BY userName) as TT
			LEFT JOIN Media 
			ON TT.userName = Media.userName
			GROUP BY userName
			ORDER BY $sort_by $sort_order
			LIMIT $limit
			OFFSET $offset";

		$result = $this->db->query($query);

		$data['channels'] = $result;
		$data['total_rows'] = $result->num_rows();

		return($data);
	}

}

?>
