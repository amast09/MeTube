<?php

class Dashboard extends CI_Controller{

	function index() {
		redirect('dashboard/view');
	}

	function view($mediaType = 'all', $category = 'all', $sort_by = 'dateCreated', $sort_order = 'desc', $offset = 0) {

		if($this->session->userdata('logged_in')) {
			// load the needed models
			$this->load->model('media_model');
			$this->load->model('category_model');

			// format the category string from %20 to spaces
			$category = str_replace('%20', ' ', $category);

			$userName = $this->session->userdata('userName');
			$data['content'] = 'dashboard_view';

			// set up the lists queries
			$data['lists']['friends'] = array('name' => 'Friends',
																				'query' => $this->get_users('FriendList'),
																				'link' => '/channel/user/',
																				'field' => 'userName2');

			$data['lists']['foes'] = array('name' => 'Foes',
																		 'query' => $this->get_users('FoeList'),
																				'link' => '/channel/user/',
																				'field' => 'userName2');

			$data['lists']['subs'] = array('name' => 'Subscriptions', 
																		 'query' => $this->get_users('SubscriberList'),
																		 'link' => '/channel/user/',
																		 'field' => 'userName2');

			$data['lists']['contacts'] = array('name' => 'Contacts',
																				 'query' => $this->get_users('ContactList'),
																				 'link' => '/channel/user/',
																				 'field' => 'userName2');
			
			$data['lists']['groups'] = array('name' => 'Groups',
																			 'query' => $this->get_users('MemberList'),
																			 'link' => '/group/display/',
																			 'field' => 'groupName');

			$data['url'] = "dashboard/view";

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

			$this->load->model('playlist_model');
			$data['playlists'] = $this->playlist_model->get_playlists($userName);
			$data['userName'] = $userName;
			$data['userName2'] = $userName;

			$data['category'] = $category;
			$data['categories'] = $categories;
			$data['pag'] = $this->pagination($userName, $mediaType, $category, $sort_by, $sort_order, $offset);

			$this->load->view('includes/template', $data);
		}
		else redirect('account');
	}

	// returns all members on a certain list ('friends', 'foes', etc.)
	function get_users($list) {
		// load the needed models
		$this->load->model('list_model');

		$userName = $this->session->userdata('userName');
		return($this->list_model->get_users($list, 'userName', $userName));
	}

	// returns all media of a certain type belonging to the loggged in user
	function get_media($mediaType) {
		// load the needed models
		$this->load->model('media_model');

		$userName = $this->session->userdata('userName');
		
		// set the fields for the query
		$fields = array('userName' => $userName, 
										'mediaType' => $mediaType);

		return($this->media_model->get_media($fields));
	}

	// paginate the media returned by the query
	function pagination($userName, $mediaType, $category, $sort_by, $sort_order, $offset) {
		// load the needed models and libraries
		$this->load->model('media_model');
		$this->load->library('pagination');

		// set the pagination variables
		$limit = 10;
		$data['sort_by'] = $sort_by;
		$data['sort_order'] = $sort_order;
		$data['fields'] = array('title' => 'Title',
														'mediaType' => 'Type', 
														'views' => 'Views',
														'downloads' => 'Downloads',
														'rating' => 'Rating',
														'dateCreated' => 'Date');

		$searchFields = array('userName' => $userName, 
													'mediaType' => $mediaType,
													'category' => $category);

		// paginate the media
		$model_data = $this->media_model->pagination($userName, $searchFields, $limit, $offset, $sort_by, $sort_order);

		// set the pagination configuration variables
		$config['base_url'] = site_url("dashboard/view/$mediaType/$category/$sort_by/$sort_order");
		$config['per_page'] = $limit;
    $config['num_links'] = 5;
		$config['total_rows'] = $model_data['total_rows'];
		$config['uri_segment'] = 7;
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['media'] = $model_data['media'];
		return($data);	
	}

}
