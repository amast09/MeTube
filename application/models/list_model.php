<?php
class List_model extends CI_Model {

	function user_add($list, $field, $userName, $fieldName){	
		// If a person is being added to the foe list, remove them from all other lists
		// If they're being added to any other list, remove them from the foe list
		if($list=='FoeList') {
			$this->user_delete('FriendList', $field, $userName, $fieldName);
			$this->user_delete('SubscriberList', $field, $userName, $fieldName);
			$this->user_delete('ContactList', $field, $userName, $fieldName);
		} else if($list == 'FriendList' || 
							$list == 'SubscriberList' || 
							$list == 'ContactList') {
			$this->user_delete('FoeList', $field, $userName, $fieldName);
		} 

		// If a person is being added to a friends list, send them a message asking them to accept
		if($list=='FriendList') {
			if(!$this->user_present($list, 'userName2', $fieldName, $userName)) {

				$this->load->model('message_model');
				$senderName = $userName;
				$receiverName = $fieldName;
				$subject = 'Friend Request';
				$body = 'Hey, I added you as a friend. You can accept the request on my channel.';

				$this->message_model->send_message($senderName, $receiverName, $subject, $body, 0, 0, 2);
			}
		}

		$listData = array(
			'userName' => $userName,
			$field => $fieldName
		);
		return($this->db->insert($list, $listData));
	}

	function user_delete($list, $field, $userName, $fieldName){
		$this->db->where($field, $fieldName);
		$this->db->where('userName', $userName);
		$this->db->delete($list);

		// delete any friend request messages to avoid spam
		if($list == 'FriendList') {
			$this->load->model('message_model');

			$fields = array('senderName' => $userName,
											'receiverName' => $fieldName,
											'subject' => 'Friend Request',
											'body' => 
												'Hey, I added you as a friend. You can accept the request on my channel.');
			$this->message_model->delete_message($fields);
		}
	}

	function user_present($list, $field, $userName, $fieldName){
		$this->db->where($field, $fieldName);
		$this->db->where('userName', $userName);
		return($this->db->get($list)->num_rows() == 1);
	}

	function get_users($list, $field, $fieldName) {
		$this->db->where($field, $fieldName);
		return($this->db->get($list));
	}
}
