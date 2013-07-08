<?php

class Media_model extends CI_Model{

	// create media and add it to the database
	function create_media($userName, $fileName, $title, $description, 
												$commentVisibility,$ratingVisibility, $mediaVisibility,  
												$mediaType, $categoryID, $tags){
		
  $media_insert_data = array(
			'userName' => $userName,
			'fileName' => $fileName,
			'title' => $title,
			'description' => $description,
      'rating' => 0,
			'views' => 0,
			'categoryID' => $categoryID,
			'commentVisibility' => $commentVisibility,
		  'ratingVisibility' => $ratingVisibility,
	  	'mediaVisibility' => $mediaVisibility,
			'mediaType' => $mediaType
		);

		if(!$this->db->insert('Media', $media_insert_data)) return(0);

		$mediaID = $this->db->insert_id(); 
		$tagArray = explode("-", $tags);

		for($i = 0; $i < count($tagArray); $i++){
			$query = $this->get_keyword_by_name($tagArray[$i]);

			if($query->num_rows() == 1){
				if(!$this->db->insert('KeywordList', array('mediaID' => $mediaID, 'keywordID' => $query->row()->ID))) return(0);
			}
			else{
				$this->create_keyword($tagArray[$i]);
				$keywordID = $this->db->insert_id(); 
				if(!$this->db->insert('KeywordList', array('mediaID' => $mediaID, 'keywordID' => $keywordID))) return(0);
			}
		}
	
		return(1);
	}
	
	function edit_media($username, $mediaID, $title, $description, 
											$commentVisibility,$ratingVisibility, $mediaVisibility,  
											$categoryID, $tags){
	
		// load the needed models
		$this->load->model('keyword_list_model');

	  $media_update_data = array(
			'title' => $title,
			'description' => $description,
			'categoryID' => $categoryID,
			'commentVisibility' => $commentVisibility,
		  'ratingVisibility' => $ratingVisibility,
	  	'mediaVisibility' => $mediaVisibility,
		);

		// delete all current keywords so we can add the new ones.
		$this->keyword_list_model->delete_all_keywords($mediaID);

		$tagArray = explode("-", $tags);

		if(!$this->update_media($mediaID, $media_update_data)) return(0);

		for($i = 0; $i < count($tagArray); $i++){
			$query = $this->get_keyword_by_name($tagArray[$i]);

			if($query->num_rows() == 1){
				if(!$this->db->insert('KeywordList', array('mediaID' => $mediaID, 'keywordID' => $query->row()->ID))) return(0);
			}
			else{
				$this->create_keyword($tagArray[$i]);
				$keywordID = $this->db->insert_id(); 
				if(!$this->db->insert('KeywordList', array('mediaID' => $mediaID, 'keywordID' => $keywordID))) return(0);
			}
		}
	
		return(1);
	}

	// update media info
	function update_media($mediaID, $media_update_data) {
		$this->db->where('ID', $mediaID);
		return($this->db->update('Media', $media_update_data));
	}

	function increment_views($mediaID) {
		$query = "UPDATE Media SET `views` = `views` + 1 WHERE `ID` = $mediaID";

		mysql_query($query);
	}

	function decrement_views($mediaID) {
		$query = "UPDATE Media SET `views` = `views` + 1 WHERE `ID` = $mediaID";

		mysql_query($query);
	}

	// add an entry into the download table to represent a user downloading a video
	function increment_downloads($mediaID) {
		$query = "UPDATE Media SET `downloads` = `downloads` + 1 WHERE Media.ID = $mediaID";

		return($this->db->query($query));
	}
 
	function get_keyword_by_name($keyword){
		$this->db->where('keyword', $keyword);
		$query = $this->db->get('Keyword');
		return($query);
	}

	function create_keyword($keyword){
		return($this->db->insert('Keyword', array('keyword' => $keyword)));
	}

	function get_keywords($mediaID){
		$query = $this->db->query("
			SELECT Keyword.keyword
			FROM Keyword
			LEFT JOIN KeywordList ON KeywordList.keywordID = Keyword.ID
			WHERE KeywordList.MediaID = $mediaID
		");
		return($query);
	}

  function get_media_by_userName($userName){
		$this->db->where('userName', $userName);
		return($this->db->get('Media'));
	}

  function get_media_by_ID($id){
		return($this->db->query("
			SELECT Media.*, Category.name as category
			FROM Media
			LEFT JOIN Category
			ON Category.id = Media.categoryID
			WHERE Media.id = $id
		"));
	}

  function delete_media_by_id($id){
		$this->db->where('ID', $id);
		$query = $this->db->get('Media');
		$full_path = "/var/www/spring13/u6/MeTube/application/uploads/".$query->row()->mediaType."/".$query->row()->fileName;
		$result = unlink($full_path);
		$this->db->where('ID', $id);
		return($this->db->delete('Media'));
	}

	function delete_media($media) {
		foreach($media as $m)
			$this->delete_media_by_id($m);	
	}

	// returns a query of media files meeting an arbitrary amount of requirements
	function get_media($fields) {
		foreach($fields as $field) {
			$this->db->where(key($fields), $field);
			next($fields);
		}	

		return($this->db->get('Media'));
	}

	function pagination($userName, $fields, $limit, $offset, $sort_by, $sort_order, $feed=false) {
		// load the needed models
		$this->load->model('category_model');
		$this->load->model('list_model');

		// If we're viewing the feed, only return videos from people we are subscribed to
		if($feed) {
			$subs[] = '';

			$subscriptions = $this->list_model->get_users('SubscriberList', 'userName', $userName);
			foreach($subscriptions->result() as $sub) {
				$subs[] = $sub->userName2;
			}

			$subsString = "('";
			$subsString .= implode("','", $subs);
			$subsString .= "')";
		}


		// make sure the sort order is either asc or desc
		$sort_order = ($sort_order == 'asc') ? 'asc' : 'desc';

		// make sure the sort_by variable is a sortable column
		$sort_columns = array('title', 'mediaType', 'views', 'downloads', 'rating', 'dateCreated');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'dateCreated';

		$query = "SELECT Media.*
							FROM Media
							WHERE 1 ";

		// each field is a where clause
		foreach($fields as $field) {
			if(key($fields) == 'category' && $field != 'all') {
				$categoryID = $this->category_model->get_categoryID($field);
				//$this->db->where('categoryID', $categoryID);
				$query .= "AND categoryID = $categoryID ";
			// if the field is mediaType make sure it isn't set to all
			} elseif((key($fields) != 'mediaType' && key($fields) != 'category') || $field != 'all') {
				$key = key($fields);
				//$this->db->where(key($fields), $field);
				if($key == 'userName') {
					$key = 'Media.userName';
					$field = "'$field'";
				}
				if($key == 'mediaType')
					$field = "'$field'";
				$query .= "AND $key=$field ";
			}
			next($fields);
		}

		if($feed) {
			$query .= "AND Media.userName IN $subsString ";
		}			

		$query .= "GROUP BY Media.ID
							 ORDER BY $sort_by $sort_order
							 LIMIT $limit";

		//$this->db->order_by($sort_by, $sort_order);
		//$result = $this->db->get('Media');
		$result = $this->db->query($query);
		$data['total_rows'] = $result->num_rows();
		$data['media'] = $result;

		return($data);
	}

	function paginate_search_results($userName, $category, $mediaType, $limit, $offset, $sort_by, $sort_order, $keywords) {
		// load the needed models
		$this->load->model('category_model');
		$this->load->model('list_model');

		// make sure the sort order is either asc or desc
		$sort_order = ($sort_order == 'asc') ? 'asc' : 'desc';

		// make sure the sort_by variable is a sortable column
		$sort_columns = array('title', 'mediaType', 'views', 'downloads', 'rating', 'relevancy', 'dateCreated');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'dateCreated';

		$keys = '(';
		// setup the keywords so we can use them in the 'in' statement in the following sql call
		foreach($keywords as $keyword) {
			$keys .= "'".$keyword."'".',';
		}

		$keys = rtrim($keys, ',');
		$keys .= ')';

		// The worst sql query in the universe...
		$query = 
			 		"SELECT Media.*, (COUNT(kw.mediaID) / ".count($keywords)." * 100) as relevancy
			 		FROM Media
			 		LEFT JOIN 
					 ((
				 		SELECT KeywordList.mediaID, Keyword.keyword
  		   		FROM KeywordList
  			 		LEFT JOIN Keyword ON KeywordList.keywordID = Keyword.ID
  			 		WHERE Keyword.keyword IN ".$keys." 
					 ) AS kw ) 
			 		ON kw.mediaID = Media.ID
			 		WHERE Media.mediaVisibility = 0 ";

		if($category != 'all') {
			$categoryID = $this->category_model->get_categoryID($category);
			$query .= "AND categoryID = $categoryID ";
		}
		if($mediaType != 'all') {
			$query .= "AND mediaType = '$mediaType' ";
		}

		$query .= "GROUP BY Media.ID
							 HAVING relevancy > 0
							 ORDER BY $sort_by $sort_order";

		$result = $this->db->query($query);
		$data['total_rows'] = $result->num_rows();
		$data['media'] = $result;

		return($data);
	}

	function paginate_playlist_media($userName, $playlistID, $limit, $offset, $sort_by, $sort_order) {
		// load the needed models
		$this->load->model('list_model');

		// make sure the sort order is either asc or desc
		$sort_order = ($sort_order == 'asc') ? 'asc' : 'desc';

		// make sure the sort_by variable is a sortable column
		$sort_columns = array('title', 'mediaType', 'views', 'rating', 'relevancy', 'dateCreated');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'dateCreated';

		if($playlistID == -1) {
			$query = "SELECT Media.*
								FROM FavoriteList
								JOIN Media on FavoriteList.mediaID = Media.ID
								WHERE FavoriteList.userName = '$userName'
								ORDER BY $sort_by $sort_order
								LIMIT $limit
								OFFSET $offset";
		} else {
			$query = "SELECT Media.* 
								FROM PlaylistMedia
								JOIN Media on PlaylistMedia.mediaID = Media.ID 
								WHERE PlaylistMedia.playlistID = $playlistID
								ORDER BY $sort_by $sort_order
								LIMIT $limit
								OFFSET $offset"; 
		}

		$result = $this->db->query($query);
		$data['total_rows'] = $result->num_rows();
		$data['media'] = $result;

		return($data);
	}
}

?>
