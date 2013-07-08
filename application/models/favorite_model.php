<?php
class Favorite_model extends CI_Model {

	function toggle_favorite($userName, $mediaID){
		if($this->favorite_present($userName, $mediaID)){
			$this->remove_favorite($userName, $mediaID);
			return(0);
		}
		else{
			$this->add_favorite($userName, $mediaID);
			return(1);
		}
	}

	function favorite_present($userName, $mediaID){
		$this->db->where('userName', $userName);
		$this->db->where('mediaID', $mediaID);
		$query = $this->db->get('FavoriteList');
		return($query->num_rows() == 1);
	}

	function remove_favorite($userName, $mediaID){
		$this->db->where('userName', $userName);
		$this->db->where('mediaID', $mediaID);
		return($this->db->delete('FavoriteList'));
	}

	function add_favorite($userName, $mediaID){
		return($this->db->insert('FavoriteList', array('userName' => $userName, 'mediaID' => $mediaID)));
	}

}
