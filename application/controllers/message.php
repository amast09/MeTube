<?php
class Message extends CI_Controller{

	function __construct(){   
		parent::__construct();
		if(!$this->session->userdata('logged_in'))
		{   
			redirect('dashboard');
		}  
	}

	 function index(){
		redirect('message/conversations');
	}

	 function compose(){
		$data['content'] = 'compose';
		$this->load->view('includes/template', $data);
	}

	 function conversations($sort_by = 'dateCreated', $sort_order = 'desc', $offset = 0){
		$this->box_pag($sort_by, $sort_order, $offset);
	}

	 function box_pag($sort_by, $sort_order, $offset){	
		// load the needed models
		$this->load->model('message_model');
		$this->load->model('list_model');

		$limit = 10;
		$data['fields'] = array(
														'senderName'  => 'From',
														'receiverName'  => 'To',
														'subject'   => 'Subject',
														'dateCreated'    => 'Date'
														);
		$data['sort_by'] = $sort_by;
		$data['sort_order'] = $sort_order;
		$userName = $this->session->userdata('userName');

		$model_data = $this->message_model->box_pagination($limit, $offset, $sort_by, $sort_order, $userName);

    $this->load->library('pagination');
		$config['base_url'] = site_url("message/conversations/$sort_by/$sort_order");
    $config['per_page'] = $limit;
    $config['num_links'] = 5;
		$config['total_rows'] = $model_data['total_rows'];
		$config['uri_segment'] = 5;
		$this->pagination->initialize($config);

		$data['userName'] = $userName;
		$data['content'] = 'box';
		$data['contacts'] = $this->list_model->get_users('ContactList', 'userName', $userName);
		$data['pagination'] = $this->pagination->create_links();
		$data['messages'] = $model_data['messages'];
		$this->load->view('includes/template', $data);
	}

	function create_message(){
		// load the needed libraries and models
		$this->load->library('form_validation');
		$this->load->model('list_model');

		$this->form_validation->set_rules('receiverName', 'Recipient', 'trim|required|min_length[4]|max_lenth[20]|callback_valid_account');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|max_length[80]');
		$this->form_validation->set_rules('body', 'Body', 'trim|required');

		if($this->form_validation->run()){
			$this->load->model('message_model');
			$senderName = $this->session->userdata('userName');
			$receiverName= $this->input->post('receiverName');
			$subject = $this->input->post('subject');
			$parentID = $this->input->post('parentID');
			$body = $this->input->post('body');
			$reply = ($this->input->post('reply') == 1) ? 1 : 0;

			// if the sender or receiver have the other person on their foe list, don't send the message
			if($this->list_model->user_present('FoeList', 'userName2', $senderName, $receiverName) || 
				 $this->list_model->user_present('FoeList', 'userName2', $receiverName, $senderName)) return;
 
			if($reply == 1){
				$messageState = 0;
				$parent = $this->message_model->get_message_by_id($parentID);
				
				if($parent->row()->senderName == $senderName)
					$this->message_model->set_state($parentID, 2);

				else 
					$this->message_model->set_state($parentID, 1);
			  
			}
			else $messageState = 2;

			if($parentID == NULL) $parentID = 0;

			if($this->message_model->send_message($senderName, $receiverName, $subject, $body, $parentID, $reply, $messageState)){
				if($reply){
					$url = 'message/view_message/'.$parentID;
					 redirect($url);
				}
				else redirect('message');
			}
			$data['content']='failure.php';
			$data['errors'] = 'Database Error';
			$this->load->view('includes/template',$data);
		}
		else {
			$data['content']='failure.php';
			$data['errors'] = validation_errors();
			$this->load->view('includes/template',$data);
	  }
	}

	function valid_account($userName){
		$this->load->model('account_model');
		return($this->account_model->valid_account($userName));
	}

	function view_message($messageID, $offset = 0){
  	$data['content'] = 'message_view';
		$this->load->model('message_model');
	  $userName = $this->session->userdata('userName');
		$parent = $this->message_model->get_message_by_id($messageID);

		if($parent->row()->senderName == $userName && $parent->row()->messageState == 1){
			$this->message_model->set_state($messageID,0);
		}
		else if($parent->row()->receiverName == $userName && $parent->row()->messageState == 2){
			$this->message_model->set_state($messageID,0);
		}

		$data['oldest'] = $parent->row_array();
		$url = uri_string();
		$messages = $this->message_model->get_thread($messageID, $offset);
		if(!$messages) redirect($url);
		$data['messages'] = $messages['query'];
		$data['total'] = $messages['total'];
		$this->load->view('includes/template', $data);
	}


	function delete_message($messageID){
		$this->load->model('message_model');
		$msg = $this->message_model->get_message_by_id($messageID);
		$userName = $this->session->userdata('userName');		
		if($msg->row()->senderName == $userName || $msg->row()->receiverName == $userName){
			$box =	$this->message_model->remove_messages(array('message' => $messageID), $userName);
		}
		redirect('message/conversations');
	}

	function delete_messages(){
		$this->load->model('message_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('msg[]', 'Messages', 'required');
		$userName = $this->session->userdata('userName');		
		if($this->form_validation->run())
			$box =	$this->message_model->remove_messages($this->input->post('msg'), $userName);
		redirect('message/conversations');
	}
}

