<?php
class Discussion extends CI_Controller {

	function index() {
		redirect('group');
	}

	function create_discussion() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('subject', 'Subject', 
				'trim|required|min_length[3]|max_length[20]|callback_unique_discussion');
		$this->form_validation->set_rules('body', 'Discussion Body', 'trim|required|max_length[1000]');

		$groupName = $this->input->post('group');

		if($this->form_validation->run()) {
			$this->load->model('discussion_model');

			$userName = $this->session->userdata('userName');
			$subject = $this->input->post('subject');
			$body = $this->input->post('body');

			if($this->discussion_model->create_discussion($groupName, $userName, $subject, $body)) {
				$discussionID = $this->db->insert_id();
				$url = "discussion/display/$groupName/$discussionID";
				redirect($url);
			} else {
				$data['errors'] = "Database Error.";
				$this->load->view('failur', $data);
			}
		} else {
			$data['content'] = 'failure.php';
			$data['errors'] = validation_errors();
			$this->load->view('includes/template', $data);
		}
	}

	function unique_discussion($subject) {
		$this->load->model('discussion_model');
		$groupName =  $this->input->post('group');
		$rows = $this->discussion_model->get_discussion($groupName, $subject)->num_rows(); 
		return($rows == 0);
	}

	function delete_discussion() {
		$this->load->model('discussion_model');

		$discussionID = $this->input->post('discussionID');

		$this->discussion_model->delete_discussion($discussionID);
	}

	function display($groupName, $id, $sort_by = 'dateCreated', $sort_order = 'desc', $offset = 0) {
		// if the user isn't logged in they shouldn't be able to view the discussion
		if(!$this->session->userdata('logged_in'))
			redirect('group');

		// load the needed models
		$this->load->model('discussion_model');

		$discussion = $this->discussion_model->get_discussion($groupName, $id);
		if($discussion->num_rows() == 0) {
			$url = site_url()."/group/display/".$groupName;
			redirect($url);
		}

		$data['content'] = 'discussion_view';
		$data['subject'] = $discussion->row()->subject;
		$data['groupName'] = $groupName;
		$data['body'] = $discussion->row()->body;
		$data['url'] = 'discussion/display/'.$groupName.'/'.$id;
		$data['pag'] = $this->pagination($groupName, $id, $sort_by, $sort_order, $offset);

		$this->load->view('includes/template', $data);
	}

	function pagination($groupName, $itemID, $sort_by, $sort_order, $offset) {
		$this->load->model('comment_model');

		$limit = 10;
		$data['fields'] = array('dateCreated' => 'Date');
		$data['sort_by'] = $sort_by;
		$data['sort_order'] = $sort_order;

		$model_data = $this->comment_model->pagination($itemID, 'DiscussionComment', $limit, 
																									 $offset, $sort_by, $sort_order);


    $this->load->library('pagination');
		$config['base_url'] = site_url("group/display/$groupName/$itemID/$sort_by/$sort_order");
    $config['per_page'] = $limit;
    $config['num_links'] = 5;
		$config['total_rows'] = $model_data['total_rows'];
		$config['uri_segment'] = 7;
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['comments'] = $model_data['comments'];

		return($data);
	}

}

