<?php
class Group extends CI_Controller {

	function index(){
		redirect('group/browse');
	}

	function create_group(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Group Name', 
			'trim|required|alpha_dash|min_length[3]|max_length[20]|callback_unique_group');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[1000]');

		if($this->form_validation->run()){
			$this->load->model('group_model');
			$this->load->model('list_model');

			$groupName = str_replace(' ', '_', $this->input->post('name'));
			$userName = $this->session->userdata('userName');
			$description = $this->input->post('description');

			if($this->group_model->create_group($groupName, $description)){
				$this->list_model->user_add('MemberList', 'groupName', $userName, $groupName);
				$url = 'group/display/'.$groupName;
				redirect($url);
			}
		}
		else {
			$data['content']='failure.php';
			$data['errors'] = validation_errors();
			$this->load->view('includes/template', $data);
		}
	}

	// delete a group from the database
	function delete_group($groupName) {
		$this->load->model('group_model');
		$this->group_model->delete_group($groupName);

		redirect('group');
	}

	function browse($sort_by = 'dateCreated', $sort_order = 'desc', $offset = 0) {
		// load the required models
		$this->load->model("group_model");

		$userName = $this->session->userdata('userName');

		$data['content'] = 'browse_groups_view';
		$data['logged_in'] = $this->session->userdata('logged_in');
		$data['pag'] = $this->paginate_groups($sort_by, $sort_order, $offset);
		$this->load->view('includes/template', $data);
	}

	function display($groupName, $sort_by = 'dateCreated', $sort_order = 'desc', $offset = 0){
		// load the required models
		$this->load->model("group_model");
		$this->load->model("list_model");

		$userName = $this->session->userdata('userName');

		// Make sure group exists
		if($this->group_model->get_group($groupName)->num_rows() == 0){
			echo "404: Not a valid group.";
			return;
		}

		$isMember = $this->group_model->is_member($userName, $groupName);
		$data['button'] = ($isMember) ? 'btn-danger' : 'btn-primary';
		$data['icon'] = ($isMember) ? 'icon-remove' : 'icon-ok';
		$data['text'] = ($isMember) ? 'Leave' : 'Join';
		$data['function'] = ($isMember) ? 'delete_member' : 'add_member';
		$data['isMember'] = $isMember;
		$data['logged_in'] = $this->session->userdata('logged_in');
		$data['content'] = 'group_view';
		$data['groupName'] = $groupName;
		$data['description'] = $this->group_model->get_group($groupName)->row()->description;
		$data['members'] = $this->list_model->get_users('MemberList', 'groupName', $groupName);
		$data['pag'] = $this->pagination($groupName, $sort_by, $sort_order, $offset);
		$this->load->view('includes/template', $data);
	}

	function unique_group($groupName){
		$this->load->model('group_model');
		$groupName = str_replace(' ', '_', $groupName);
		$rows = $this->group_model->get_group($groupName)->num_rows();
		return($rows == 0);
	}

	function add_member(){
		$this->load->model("list_model");

		$groupName = $this->input->post('groupName');
		$userName = $this->session->userdata('userName');	

		$this->list_model->user_add("MemberList", "groupName", $userName, $groupName);
		$url = site_url()."/group/display/".$groupName;
		redirect($url);
	}

	function delete_member(){
		$this->load->model("list_model");

		$groupName = $this->input->post('groupName');
		$userName = $this->session->userdata('userName');

		$this->list_model->user_delete("MemberList", "groupName", $userName, $groupName);

		// if the group has no members, delete it
		if($this->list_model->get_users('MemberList', 'groupName', $groupName)->num_rows() == 0)
			$this->delete_group($groupName);

		echo $this->list_model->get_users('MemberList', 'groupName', $groupName)->num_rows();

		$url = site_url()."/group/display/".$groupName;
		redirect($url);
	}

	// paginate the groups to display a list of all groups
	function paginate_groups($sort_by, $sort_order, $offset) {
		// load the needed models
		$this->load->model('group_model');

		// set the pagination variables
		$limit = 10;
		$data['fields'] = array('name' => 'Name',
														'description' => 'Description',
														'numMembers' => 'Members',
														'dateCreated' => 'DateCreated');
		$data['sort_by'] = $sort_by;
		$data['sort_order'] = $sort_order;

		$model_data = $this->group_model->pagination($limit, $offset, $sort_by, $sort_order);

		// paginate!
    $this->load->library('pagination');
		$config['base_url'] = site_url("group/browse/$sort_by/$sort_order");
    $config['per_page'] = $limit;
    $config['num_links'] = 5;
		$config['total_rows'] = $model_data['total_rows'];
		$config['uri_segment'] = 5;
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['groups'] = $model_data['groups'];
		return($data);
	}

	function pagination($groupName, $sort_by, $sort_order, $offset){
		$this->load->model('discussion_model');
		$limit = 10;
		$data['fields'] = array(
														'userName'  => 'Username',
														'subject'   => 'Subject',
														'dateCreated' => 'Date',
														'num' => 'Comments'
														);

		$data['sort_by'] = $sort_by;
		$data['sort_order'] = $sort_order;

		$model_data = $this->discussion_model->pagination($groupName, $limit, $offset, $sort_by, $sort_order);

    $this->load->library('pagination');
		$config['base_url'] = site_url("group/display/$groupName/$sort_by/$sort_order");
    $config['per_page'] = $limit;
    $config['num_links'] = 5;
		$config['total_rows'] = $model_data['total_rows'];
		$config['uri_segment'] = 6;
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['discussions'] = $model_data['discussions'];
		return($data);
	}
}

?>
