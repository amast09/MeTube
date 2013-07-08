<?php
class Comment extends CI_Controller {

	function index(){}

	function create_comment(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('body', 'Comment Body', 'trim|required|max_length[1000]');

		if($this->form_validation->run()){
			$table = $this->input->post('table');
			$url = $this->input->post('url');
			$id = $this->input->post('id');
			$userName = $this->session->userdata('userName');
			$body = $this->input->post('body');
			$parentID = $this->input->post('parentID');
			if($parentID == NULL) $parentID = 0;

			$this->load->model('comment_model');
			$this->comment_model->create_comment($table, $id, $userName, $body, $parentID);
			redirect($url);
		}
		else{
			$data['content']='failure.php';
			$data['errors'] = validation_errors();
			$this->load->view('includes/template',$data);
		}
}

	function delete_comment() {
		$this->load->model('comment_model');

		$list = $this->input->post('list');
		$ID = $this->input->post('commentID');

		$this->comment_model->delete_comment($list, $ID);
	}

}

