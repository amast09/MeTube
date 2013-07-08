<?php

class Media extends CI_Controller{

	function index(){
		redirect('media/browse');
	}

	function browse($mediaType = 'all', $category = 'all', $sort_by = 'dateCreated', $sort_order = 'desc', $offset = 0) {
		// load the needed models
		$this->load->model('media_model');
		$this->load->model('category_model');

		// format the category string from %20 to spaces
		$category = str_replace('%20', ' ', $category);
		$userName = $this->session->userdata('userName');

		$data['content'] = 'browse_media_view';
		$data['header'] = 'Browse Media';

		$data['url'] = "media/browse";

		// used in media pagination for playlist deletion.
		$data['isOwner'] = false;
		
		$mediaTypeList = array('all', 'audio', 'image', 'video');

		$data['mediaType'] = $mediaType;
		$data['mediaTypes'] = $mediaTypeList;
		
		// make sure a correct type of media is being searched for
		if(!in_array($mediaType, $mediaTypeList))
			$mediaType = 'all';

		// create an array that contains all available categories
		$categoryList = $this->category_model->get_categories();
		$categories[] = 'all';
		foreach($categoryList->result() as $c)
			$categories[] = $c->name;

		if(!in_array($category, $categories))
			$category = 'all';

		$data['category'] = $category;
		$data['categories'] = $categories;

		$data['pag'] = $this->paginate_media($userName, $mediaType, $category, $sort_by, $sort_order, $offset);

		$this->load->view('includes/template', $data);
	}

	function feed($mediaType = 'all', $category = 'all', $sort_by = 'dateCreated', $sort_order = 'desc', $offset = 0) {
		if(!$this->session->userdata('logged_in')) redirect('media/browse');
		// load the needed models
		$this->load->model('media_model');
		$this->load->model('category_model');

		// format the category string from %20 to spaces
		$category = str_replace('%20', ' ', $category);
		$userName = $this->session->userdata('userName');

		$data['content'] = 'browse_media_view';
		$data['header'] = 'Subscription Uploads';

		$data['url'] = "media/feed";

		// used in media pagination for playlist deletion.
		$data['isOwner'] = false;	
		
		$mediaTypeList = array('all', 'audio', 'image', 'video');

		$data['mediaType'] = $mediaType;
		$data['mediaTypes'] = $mediaTypeList;
		
		// make sure a correct type of media is being searched for
		if(!in_array($mediaType, $mediaTypeList))
			$mediaType = 'all';

		// create an array that contains all available categories
		$categoryList = $this->category_model->get_categories();
		$categories[] = 'all';
		foreach($categoryList->result() as $c)
			$categories[] = $c->name;

		if(!in_array($category, $categories))
			$category = 'all';

		$data['category'] = $category;
		$data['categories'] = $categories;

		$data['pag'] = $this->paginate_media($userName, $mediaType, $category, $sort_by, $sort_order, $offset, true);

		$this->load->view('includes/template', $data);
	}

	function search($mediaType = 'all', $category = 'all', $sort_by = 'dateCreated', $sort_order = 'desc', $offset = 0) {
		// load the needed models
		$this->load->model('media_model');
		$this->load->model('category_model');

		// format the category string from %20 to spaces
		$category = str_replace('%20', ' ', $category);
		$userName = $this->session->userdata('userName');

		$data['content'] = 'browse_media_view';
		$data['header'] = 'Search Results';

		$data['url'] = "media/search";

		// used in media pagination for playlist deletion.
		$data['isOwner'] = false;
		
		$mediaTypeList = array('all', 'audio', 'image', 'video');

		$data['mediaType'] = $mediaType;
		$data['mediaTypes'] = $mediaTypeList;
		
		// make sure a correct type of media is being searched for
		if(!in_array($mediaType, $mediaTypeList))
			$mediaType = 'all';

		// create an array that contains all available categories
		$categoryList = $this->category_model->get_categories();
		$categories[] = 'all';
		foreach($categoryList->result() as $c)
			$categories[] = $c->name;

		if(!in_array($category, $categories))
			$category = 'all';

		$data['category'] = $category;
		$data['categories'] = $categories;

		if($this->input->post('searchFields') != '') {
			$keywords = explode(" ", $this->input->post('searchFields'));	
			$this->session->set_userdata('kw', $keywords);
		} else {
			$keywords = $this->session->userdata('kw');
			if($keywords == '') $keywords[] = 'test';
		}

		$data['pag'] = $this->paginate_search_results($userName, $mediaType, $category, $sort_by, $sort_order, $offset, $keywords);

		$this->load->view('includes/template', $data);
	}

	function upload_media() {
		if($this->session->userdata('logged_in')){
			$this->load->model('category_model');
			$data['categories'] = $this->category_model->get_categories();
			$data['content'] = 'upload_view';
			$this->load->view('includes/template', $data);
		}
		else echo "You must be logged in to upload media.";
	}

	// edit media that has already been uploaded
	function edit_media($mediaID) {
		// load the needed models
		$this->load->model('media_model');
		$this->load->model('keyword_list_model');

		// make sure someone is logged in
		if($this->session->userdata('logged_in')){
			// make sure the media belongs to the logged in user
			$userName = $this->session->userdata('userName');
			$mediaData = $this->media_model->get_media_by_id($mediaID)->row();

			// if the logged in user doesn't own the media redirect them away from the page
			if($mediaData->userName != $userName) redirect('dashboard');

			$keywordString = '';

			// get the initial keywords and format the string appropriately
			$keywords = $this->keyword_list_model->get_keywords_by_mediaID($mediaID);
			foreach($keywords->result() as $keyword) {
				$keywordString .= $keyword->keyword.'-';
			}
			
			$keywordString = rtrim($keywordString, '-');

			$data['mediaID'] = $mediaID;
			$data['initialTitle'] = $mediaData->title;
			$data['initialDescription'] = $mediaData->description;
			$data['initialCV'] = $mediaData->commentVisibility;
			$data['initialRV'] = $mediaData->ratingVisibility;
			$data['initialMV'] = $mediaData->mediaVisibility;
			$data['initialCat'] = $mediaData->categoryID;
			$data['initialKW'] = $keywordString;

			$this->load->model('category_model');
			$data['categories'] = $this->category_model->get_categories();
			$data['content'] = 'edit_media';
			$this->load->view('includes/template', $data);
		}
		else redirect('browse');
	}

	function delete_media() {
		// load the needed models
		$this->load->model('media_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('media[]', 'Media', 'required');

		if($this->form_validation->run())
			$this->media_model->delete_media($this->input->post('media'));

		redirect('dashboard');
	}

	function delete_media_by_id($id) {
		// load the needed models
		$this->load->model('media_model');
		$query = $this->media_model->get_media_by_ID($id);

		if($query->row()->userName == $this->session->userdata('userName'))
			$this->media_model->delete_media_by_id($id);

		redirect('dashboard');
	}

	function download_media($mediaID){
		$this->load->model('media_model');
		$this->load->helper('download');

		$media = $this->media_model->get_media_by_id($mediaID)->row();

		$userName = $this->session->userdata('userName');

		$this->media_model->increment_downloads($media->ID);

		$type = $media->mediaType;
		$fileName = $media->fileName;
		$data = file_get_contents(base_url()."/application/uploads/".$type."/".$fileName);

		force_download($fileName, $data); 

		$url = 'media/view/'.$mediaID;
		redirect($url);
	}

	function view($id, $sort_by = 'dateCreated', $sort_order = 'desc', $offset = '0'){
		$this->load->model('media_model');
		$query = $this->media_model->get_media_by_id($id);
		$userName = $this->session->userdata('userName');

		if($query->num_rows != 1){
			echo "Media not found.";
		}
		else{
			$result = $query->row();

			$this->media_model->increment_views($id);

			$this->load->model('list_model');
			$this->load->model('rating_model');

			// if the video is private the person viewing must be the owner of the video.
			if($result->mediaVisibility == 2 && $result->userName != $userName)
				redirect('dashboard');

			// if the video is friends only, the person viewing it must be a friend of the owner or be the owner.
			if($result->mediaVisibility == 1 && 
				!($this->list_model->user_present('FriendList', 'userName2', $result->userName, $userName) ||
				  $result->userName == $userName) ) 
				redirect('dashboard');

			$rating = $this->rating_model->get_rating($userName, $id);

			$liked = 2;
			if($rating->num_rows() == 1) $liked = $rating->row()->rating;

			$mediaID = $query->row()->ID;

			$total = $this->rating_model->count_ratings($id);
			if($total->row()->total == 0){
				$data['likes'] = 0;
				$data['dislikes'] = 0;
				$data['total'] = 0;
			}
			else{
				$data['likes'] = $total->row()->sum;
				$data['dislikes'] = $total->row()->total - $total->row()->sum;
				$data['total'] = $total->row()->total;
			}

			$data['foe'] = $this->list_model->user_present('FoeList', 'userName2', $result->userName, $userName);
			$data['liked'] = $liked;
			$data['fav'] = $this->list_model->user_present('FavoriteList', 'mediaID', $userName, $mediaID);
			$data['keywords'] = $this->media_model->get_keywords($id);
			$data['media'] = $query;
			$data['content'] = 'media_view';
			$data['pag'] = $this->paginate_comments($mediaID, $sort_by, $sort_order, $offset);
			$data['url'] = "media/view/$mediaID";
			$this->load->view('includes/template', $data);
		}
	}

	function upload(){
		$this->load->model('media_model');
		$this->load->model('category_model');
		$this->load->library('form_validation');

		// set the form validation requirements
		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[30]');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('cv', 'Commment Visibility', 'trim|required');
		$this->form_validation->set_rules('rv', 'Rating Visibility', 'trim|required');
		$this->form_validation->set_rules('mv', 'Media Visbility', 'trim|required');
		$this->form_validation->set_rules('category', 'Category', 'trim|required');
		$this->form_validation->set_rules('tags', 'Tags', 'trim|alpha_dash|max_length[160]');

		if($this->form_validation->run()){
			$userName = $this->session->userdata('userName');
			$title = $this->input->post('title');
			$description = $this->input->post('description');
			$commentVisibility = $this->input->post('cv');
			$ratingVisibility = $this->input->post('rv');
			$mediaVisibility = $this->input->post('mv');
			$categoryID = $this->input->post('category');
			$tags = $this->input->post('tags');
			$mediaType = $this->get_media_type($_FILES["userfile"]["name"]);
			$fileName = $this->upload_file($mediaType);
			if($this->media_model->create_media($userName,
																					$fileName, 
																					$title,
																					$description,
																					$commentVisibility,
																					$ratingVisibility, 
																					$mediaVisibility,  
																					$mediaType,
																					$categoryID,
																					$tags)){
   			redirect('dashboard');
			}
			$data['content']='failure.php';
			$data['errors'] = 'Failed Database Entry';
			$this->load->view('includes/template', $data);
		}
			$data['content']='failure.php';
			$data['errors'] = validation_errors();
			$this->load->view('includes/template', $data);
	}

	// edit media that has already been uploaded
	function edit(){
		// load the needed models and libraries
		$this->load->model('media_model');
		$this->load->model('category_model');
		$this->load->library('form_validation');

		// set the form validation requirements
		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[30]');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[500]');
		$this->form_validation->set_rules('cv', 'Commment Visibility', 'trim|required');
		$this->form_validation->set_rules('rv', 'Rating Visibility', 'trim|required');
		$this->form_validation->set_rules('mv', 'Media Visbility', 'trim|required');
		$this->form_validation->set_rules('category', 'Category', 'trim|required');
		$this->form_validation->set_rules('tags', 'Tags', 'trim|alpha_dash|max_length[160]');

		// make sure the form was filled out correctly and edit the media
		if($this->form_validation->run()){
			$userName = $this->session->userdata('userName');
			$title = $this->input->post('title');
			$mediaID = $this->input->post('mediaID');
			$description = $this->input->post('description');
			$commentVisibility = $this->input->post('cv');
			$ratingVisibility = $this->input->post('rv');
			$mediaVisibility = $this->input->post('mv');
			$categoryID = $this->input->post('category');
			$tags = $this->input->post('tags');
			if($this->media_model->edit_media($userName,
																				$mediaID,
																				$title,
																				$description,
																				$commentVisibility,
																				$ratingVisibility, 
																				$mediaVisibility,  
																				$categoryID,
																				$tags)){
   			redirect('dashboard');
			}
			$data['content']='failure.php';
			$data['errors'] = 'Failed Database Entry';
			$this->load->view('includes/template', $data);
		}
			$data['content']='failure.php';
			$data['errors'] = validation_form();
			$this->load->view('includes/template', $data);
	}

	function get_media_type($fileName){
		$ext = pathinfo($fileName, PATHINFO_EXTENSION);
		if($ext == 'mp3') return("audio");
		else if($ext == 'mp4') return("video");
		else return("image");	
	}

	function upload_file($mediaType){
		$config['upload_path'] = '/var/www/spring13/u6/MeTube/application/uploads/'.$mediaType;
    $config['allowed_types'] = 'gif|jpg|png|mp4|mp3|jpeg';
		$config['max_filename']  = '50';

		$this->load->library('upload', $config);

		if(!$this->upload->do_upload()){
			$ud = $this->upload->data();
			echo $ud['file_ext'];
			echo $this->upload->display_errors();
		}
		else{
			$ud = $this->upload->data();
			return($ud['file_name']);
		}
	}

	// paginate the media returned by the query
	function paginate_media($userName, $mediaType, $category, $sort_by, $sort_order, $offset, $feed = false) {
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
														'downloads' => 'Downloads',
														'rating' => 'Rating',
														'dateCreated' => 'Date');

		$searchFields = array('mediaType' => $mediaType,
													'category' => $category,
													'mediaVisibility' => 0);

		// paginate the media
		$model_data = $this->media_model->pagination($userName, $searchFields, $limit, $offset, $sort_by, $sort_order, $feed);

		// set the pagination configuration variables
		if($feed) $config['base_url'] = site_url("media/feed/$mediaType/$category/$sort_by/$sort_order");
		else $config['base_url'] = site_url("media/browse/$mediaType/$category/$sort_by/$sort_order");
		$config['per_page'] = $limit;
    $config['num_links'] = 5;
		$config['total_rows'] = $model_data['total_rows'];
		$config['uri_segment'] = 7;
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['media'] = $model_data['media'];
		return($data);	
	}

	// paginate the media matching the keyword search results
	function paginate_search_results($userName, $mediaType, $category, $sort_by, $sort_order, $offset, $keywords) {
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
														'downloads' => 'Downloads',
														'rating' => 'Rating',
														'relevancy' => 'Relevancy',
														'dateCreated' => 'Date');

		// paginate the media
		$model_data = $this->media_model->paginate_search_results($userName, $category, $mediaType, $limit, $offset, $sort_by, $sort_order, $keywords);

		// set the pagination configuration variables
		$config['base_url'] = site_url("media/search/$mediaType/$category/$sort_by/$sort_order");
		$config['per_page'] = $limit;
    $config['num_links'] = 5;
		$config['total_rows'] = $model_data['total_rows'];
		$config['uri_segment'] = 7;
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['media'] = $model_data['media'];
		return($data);	
	}

	function paginate_comments($mediaID, $sort_by, $sort_order, $offset) {
		$this->load->model('comment_model');

		$limit = 10;
		$data['fields'] = array('dateCreated' => 'Date');

		$data['sort_by'] = 'dateCreated';
		$data['sort_order'] = $sort_order;

		$model_data = $this->comment_model->pagination($mediaID, 'MediaComment', $limit, 
																									 $offset, 'dateCreated', $sort_order);

		$this->load->library('pagination');
			
		$config['base_url'] = site_url("media/view/$mediaID");
    $config['per_page'] = $limit;
    $config['num_links'] = 5;
		$config['total_rows'] = $model_data['total_rows'];
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['comments'] = $model_data['comments'];

		return($data);
	}
}
