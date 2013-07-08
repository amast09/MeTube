<?php
class Channel extends CI_Controller {

	// site_url()/channel/
	function index(){
		redirect('channel/browse');
	}

	function browse($sort_by = 'views', $sort_order = 'desc', $offset = 0) {
		$data['content'] = 'browse_channels_view';

		$userName = $this->session->userdata('userName');

		$data['pag'] = $this->paginate_channels($userName, $sort_by, $sort_order, $offset);
		$this->load->view('includes/template', $data);
	}

	// displays a user's channel
	function user($userName2, $mediaType = 'all', $category = 'alll', $sort_by = 'dateCreated', $sort_order = 'desc', $offset = 0) {

		// load the needed models
		$this->load->model('account_model');
		$this->load->model('playlist_model');

		// retrieve the username of the currenly logged in user
		$userName = $this->session->userdata('userName');

		if(!$this->account_model->valid_account($userName2)) {
			// user doesn't exist
			echo "404: Not a valid account";
		} else{

			$pending = false;
			$friend = $this->user_present('FriendList', $userName, $userName2) &&
								$this->user_present('FriendList', $userName2, $userName);
			if(!$friend)
				$pending = $this->user_present('FriendList', $userName, $userName2);
			$foe = $this->user_present('FoeList', $userName, $userName2);
			$sub = $this->user_present('SubscriberList', $userName, $userName2);
			$contact = $this->user_present('ContactList', $userName, $userName2);

			// display the page
			$data['content'] = 'channel_view';
		
			// format the category string from %20 to spaces
			$category = str_replace('%20', ' ', $category);
			$this->load->model('category_model');
			// create an array that contains all available categories
			$categoryList = $this->category_model->get_categories();
			$categories[] = 'all';
			foreach($categoryList->result() as $c)
				$categories[] = $c->name;
			if(!in_array($category, $categories))
				$category = 'all';
			$data['category'] = $category;
			$data['categories'] = $categories;

			$data['friend'] = $friend;
			$data['foe'] = $this->user_present('FoeList', $userName, $userName2);
			$data['sub'] = $this->user_present('SubscriberList', $userName, $userName2);
			$data['contact'] = $this->user_present('ContactList', $userName, $userName2);
			$data['userName'] = $userName;
			$data['userName2'] = $userName2;

			$data['lists'] = array();
			$data['playlists'] = $this->playlist_model->get_playlists($userName2);

			$data['buttonData']['friend'] = 
				array(
					'list' => 'FriendList',
					'bool' => $sub,
					'text' => 'Friend',
					'url' => (($friend || $pending) ? '1' : '0'),
					'button' => $friend ? 'btn-danger' : ($pending ? 'btn-success' : 'btn-primary'),
					'icon' => $friend ? 'icon-remove' : ($pending ? 'icon-refresh' : 'icon-ok'),

				);
			$data['buttonData']['foe'] = 
				array(
					'list' => 'FoeList',
					'bool' => $foe,
					'text' => 'Foe',
					'url' => ($foe ? '1' : '0'),
					'button' => $foe ? 'btn-danger' : 'btn-primary',
					'icon' => $foe ? 'icon-remove' : 'icon-ok'
				);
			$data['buttonData']['subscriber'] = 
				array(
					'list' => 'SubscriberList',
					'bool' => $sub,
					'text' => 'Subscribe',
					'url' => ($sub ? '1' : '0'),
					'button' => $sub ? 'btn-danger' : 'btn-primary',
					'icon' => $sub ? 'icon-remove' : 'icon-ok'
				);
			$data['buttonData']['contact'] = 
				array(
					'list' => 'ContactList',
					'bool' => $contact,
					'text' => 'Contact',
					'url' => ($contact ? '1' : '0'),
					'button' => $contact ? 'btn-danger' : 'btn-primary',
					'icon' => $contact ? 'icon-remove' : 'icon-ok'
				);

			$data['url'] = "channel/user/$userName2";

			// used in media pagination for playlist deletion
			$data['isOwner'] = false;
			$data['pending'] = $pending;
			$data['mediaType'] = $mediaType;
			$data['mediaTypes'] = array('all', 'audio', 'image', 'video');
			$data['pag'] = $this->pagination($friend, $userName2, $mediaType, $category, $sort_by, $sort_order, $offset);

			$this->load->view('includes/template', $data);
		}

	}

	// add a user to a list
	function add_user(){
		// load the needed models
		$this->load->model('list_model');
		
		// retrieve post and session variables
		$list = $this->input->post('list');
		$userName = $this->session->userdata('userName');
		$userName2 = $this->input->post('userName2');

		// 
		$this->list_model->user_add($list, "userName2", $userName, $userName2);
		$url = 'channel/user/'.$userName2;
//		redirect($url);
	}

	function delete_user(){
		$this->load->model('list_model');

		$list = $this->input->post('list');
		$userName = $this->session->userdata('userName');
		$userName2 = $this->input->post('userName2');

		$this->list_model->user_delete($list, "userName2", $userName, $userName2);
		$url = 'channel/user/'.$userName2;

//	redirect($url);
	}

	function user_present($list, $userName, $userName2){
		$this->load->model('list_model');
		return($this->list_model->user_present($list, "userName2", $userName, $userName2));
	}

	function pagination($friend, $userName2, $mediaType, $category, $sort_by, $sort_order, $offset) {
		// load the needed models and libraries
		$this->load->model('media_model');
		$this->load->library('pagination');

		$userName = $this->session->userdata('userName');

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

		if(!($mediaType == 'audio' || $mediaType == 'video' || $mediaType == 'image'))
			$mediaType = 'all';

		$searchFields = array('userName' => $userName2, 
												 'mediaType' => $mediaType,
													'category' => $category);

		if($userName == $userName2)
			$searchFields += array('mediaVisibility <' => 2);
		else if($friend)
			$searchFields += array('mediaVisibility <' => 1);
		else
			$searchFields += array('mediaVisibility' => 0);

			

		// paginate the media
		$model_data = $this->media_model->pagination($userName, $searchFields, $limit, $offset, $sort_by, $sort_order);

		// set the pagination configuration variables
		$config['base_url'] = site_url("channel/user/$userName2/$mediaType/$category/$sort_by/$sort_order");
		$config['per_page'] = $limit;
    $config['num_links'] = 5;
		$config['total_rows'] = $model_data['total_rows'];
		$config['uri_segment'] = 8;
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['media'] = $model_data['media'];
		return($data);	
	}

	// paginate the channels to display a list of all channels
	function paginate_channels($userName, $sort_by, $sort_order, $offset) {
		// load the needed models
		$this->load->model('account_model');

		// set the pagination variables
		$limit = 10;
		$fields = array('userName' => 'UserName',
										'views' => 'Total Views',
										'downloads' => 'Total Downloads',
										'subs' => 'Subscribers',
										'uploads' => 'Total Uploads');

		$data['fields'] = $fields;
		$data['sort_by'] = $sort_by;
		$data['sort_order'] = $sort_order;

		$model_data = $this->account_model->pagination($userName, $fields, $limit, $offset, $sort_by, $sort_order);

		// paginate!
    $this->load->library('pagination');
		$config['base_url'] = site_url("channel/browse/$sort_by/$sort_order");
    $config['per_page'] = $limit;
    $config['num_links'] = 5;
		$config['total_rows'] = $model_data['total_rows'];
		$config['uri_segment'] = 5;
		$this->pagination->initialize($config);

		$data['pagination'] = $this->pagination->create_links();
		$data['channels'] = $model_data['channels'];
		return($data);
	}
}
