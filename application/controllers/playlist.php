<?php

class Playlist extends CI_Controller {

	function index() {
		redirect('dashboard');
	}

	// view all media in a given playlist - paginated
	function view($playlistID, $mediaType = 'all', $category = 'all', $sort_by = 'dateCreated', $sort_order = 'desc', $offset = 0) {
		// load the needed models
		$this->load->model('media_model');
		$this->load->model('playlist_model');

		if(!$this->playlist_model->playlist_exists($playlistID)) redirect('dashboard');

		// format the category string from %20 to spaces
		$userName = $this->session->userdata('userName');
		$data['userName'] = $userName;

		// the view to load
		$data['content'] = 'playlist_view';

		// the header of the view
		$data['header'] = $this->playlist_model->get_playlist_name($playlistID);
		$data['isOwner'] = $this->playlist_model->is_owner($playlistID, $userName);

		// data used in media pagination
		$data['mediaType'] = 'all';
		$data['category'] = 'all';

		// the url used when reordering sort by during pagination
		$data['url'] = "playlist/view/$playlistID";
		
		// paginate the media
		$data['pag'] = $this->paginate_media($userName, $playlistID, $sort_by, $sort_order, $offset);

		// load the view
		$this->load->view('includes/template', $data);
	}

	// view all media in a given playlist - paginated
	function favorite($userName2, $mediaType = 'all', $category = 'all', $sort_by = 'dateCreated', $sort_order = 'desc', $offset = 0) {
		// load the needed models
		$this->load->model('account_model');
		$this->load->model('media_model');
		$this->load->model('playlist_model');

		if(!$this->account_model->valid_account($userName2)) redirect('dashboard');

		// format the category string from %20 to spaces
		$userName = $this->session->userdata('userName');
		$data['userName'] = $userName;

		// the view to load
		$data['content'] = 'playlist_view';

		// the header of the view
		$data['header'] = "$userName2's Favorite Media";
		$data['isOwner'] = ($userName == $userName2);

		// data used in media pagination
		$data['mediaType'] = 'all';
		$data['category'] = 'all';

		// the url used when reordering sort by during pagination
		$data['url'] = "playlist/favorite/$userName2";
		
		// paginate the media
		$data['pag'] = $this->paginate_media($userName2, -1, $sort_by, $sort_order, $offset);

		// load the view
		$this->load->view('includes/template', $data);
	}

	function delete_media() {
		// load the needed models and libraries
		$this->load->model('playlist_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('media[]', 'Media', 'required');

		$userName = $this->session->userdata('userName');
		$playlistID = $this->input->post('playlistID');
	
		if($this->form_validation->run()) {
			if($playlistID == $userName)
				$this->playlist_model->delete_favorite_media($userName, $this->input->post('media'));
			else
				$this->playlist_model->delete_media($playlistID, $this->input->post('media'));
		}

		redirect($this->input->post('redirect'));
	}

	function delete_playlist() {
		// load the needed models
		$this->load->model('playlist_model');

		$playlistID = $this->input->post('playlistID');

		$this->playlist_model->delete_playlist($playlistID);
	
		redirect('dashboard');
	}

	// paginate the media in the playlist
	function paginate_media($userName, $playlistID, $sort_by, $sort_order, $offset) {
		// load the needed models and libraries
		$this->load->model('media_model');
		$this->load->library('pagination');

		// set the pagination variables
		$limit = 20;
		$data['sort_by'] = $sort_by;
		$data['sort_order'] = $sort_order;
		$data['fields'] = array('title' => 'Title',
														'userName' => 'uploader',
														'mediaType' => 'Type', 
														'views' => 'Views',
														'rating' => 'Rating',
														'dateCreated' => 'Date');

		// paginate the media
		$model_data = $this->media_model->paginate_playlist_media($userName, $playlistID, $limit, $offset, $sort_by, $sort_order);

		// set the pagination configuration variables
		if($playlistID == -1)
			$config['base_url'] = site_url("playlist/favorite/$userName/$sort_by/$sort_order");
		else
			$config['base_url'] = site_url("playlist/view/$playlistID/$sort_by/$sort_order");
		$config['per_page'] = $limit;
    $config['num_links'] = 5;
		$config['total_rows'] = $model_data['total_rows'];
		$config['uri_segment'] = 8;
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['media'] = $model_data['media'];
		return($data);	
	}

	function toggle_favorite(){
		$userName = $this->session->userdata('userName');
		$mediaID = $this->input->post('mediaID');
		$this->load->model('favorite_model');
		$result = array('added' => $this->favorite_model->toggle_favorite($userName, $mediaID));
		echo json_encode($result);
	}

	function get_playlists(){
		$this->load->model('playlist_model');
		$userName = $this->session->userdata('userName');
		$query = $this->playlist_model->get_playlists($userName);
		$result = array();
		$i = 0;

		foreach($query->result() as $row){
			$result[$i]['name'] = $row->name;
			$result[$i]['ID'] = $row->ID;
			$i++;
		}

		echo json_encode($result);
	}

	function create_playlist(){
		$this->load->model('playlist_model');
		$userName = $this->session->userdata('userName');
		$name = $this->input->post('name');
		if($name == NULL) return(0);
		$query = $this->playlist_model->get_playlist($userName, $name);
		if($query->num_rows() != 0) json_encode(array('insert' => 0));
		$result = array('insert' => $this->playlist_model->create_playlist($userName, $name));
		echo json_encode(array('success', $result));
	}

	function add_to_playlist(){
		$this->load->model('playlist_model');
		$userName = $this->session->userdata('userName');
		$playlists = $this->input->post('playlists');
		$mediaID = $this->input->post('mediaID');;
		for($i = 0; $i < count($playlists); $i++){
			if(!$this->playlist_model->add_to_playlist($mediaID, $playlists[$i])){
				$result = array('success' => 0);
				json_encode($result);
				return(0);
			}
		}
		
		$result = array('success' => 1);
		json_encode($result);
		return(1);
	}

	function edit_playlist(){
		$this->load->model('playlist_model');
		$userName = $this->session->userdata('userName');
		$name = $this->input->post('name');
		if($name == NULL) return(0);
		$playlistID = $this->input->post('playlistID');
		$result = array('edit' => $this->playlist_model->edit_playlist($userName, $playlistID, $name));
		echo json_encode($result);
	}
}
?>
