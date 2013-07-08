<?php
class Rating extends CI_Controller {

	function index(){
		redirect('dashboard');
	}

	function rate_media(){
		$userName = $this->session->userdata('userName');
		$mediaID = $this->input->post('mediaID');
		$rating = $this->input->post('rating');

		$this->load->model('rating_model');
		$result = array('success' => $this->rating_model->rate_media($userName, $mediaID, $rating));

		echo json_encode($result);
	}

}

?>
