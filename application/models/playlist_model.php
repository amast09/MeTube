<?php
class Playlist_model extends CI_Model {

	function get_playlists($userName){
		$this->db->where('userName', $userName);
		$this->db->order_by('name', 'ASC'); 
		return($this->db->get('Playlist'));
	}

	function get_playlist($userName, $name){
		$this->db->where('userName', $userName);
		$this->db->where('name', $name);
		return($this->db->get('Playlist'));
	}

	function create_playlist($userName, $name){
		if($this->get_playlist($userName, $name)->num_rows()) return(0);
		$data = array('userName' => $userName, 'name' => $name);
		return($this->db->insert('Playlist', $data));
	}

	function delete_playlist($playlistID) {
		$this->db->where('ID', $playlistID);
		return($this->db->delete('Playlist'));
	}

	// returns the name of the playlist with the given ID
	function get_playlist_name($playlistID) {
		$this->db->where('ID', $playlistID);
		return($this->db->get('Playlist')->row()->name);
	}

	function add_to_playlist($mediaID, $playlistID){
		if($this->media_present($mediaID, $playlistID)) return(1);
		else{
			$data = array('mediaID' => $mediaID, 'playlistID' => $playlistID);
			return($this->db->insert('PlaylistMedia', $data));
		}
	}

	function media_present($mediaID, $playlistID){
		$this->db->where('mediaID', $mediaID);
		$this->db->where('playlistID', $playlistID);
		$query = $this->db->get('PlaylistMedia');
		return($query->num_rows() == 1);
	}

	// returns true if the playlist exists, false if it doesn't
	function playlist_exists($playlistID) {
		$this->db->where('ID', $playlistID);
		return($this->db->get('Playlist')->num_rows() == 1);
	}

	// returns the userName of the person who made the playlist
	function get_owner($playlistID) {
		$this->db->where('ID', $playlistID);
		return($this->db->get('Playlist')->row()->userName);
	}

	// returns the userName of the person who made the playlist
	function is_owner($playlistID, $userName) {
		$this->db->where('ID', $playlistID);
		return($this->db->get('Playlist')->row()->userName == $userName);
	}

	// deletes the given media from the playlist
	function delete_media_by_id($playlistID, $mediaID) {
		$this->db->where('playlistID', $playlistID);
		$this->db->where('mediaID', $mediaID);

		return($this->db->delete('PlaylistMedia'));
	}

	// deletes the given media from the favorite list of the given user
	function delete_favorite_media_by_userName($userName, $mediaID) {
		$this->db->where('userName', $userName);
		$this->db->where('mediaID', $mediaID);

		return($this->db->delete('FavoriteList'));
	}

	// deletes the media in the passed array from the playlist
	function delete_media($playlistID, $media) {
		foreach($media as $m)
			if(!$this->delete_media_by_id($playlistID, $m)) return 0;
		return 1;
	}

	// deletes media from the favorite list of the given user
	function delete_favorite_media($userName, $media) {
		foreach($media as $m)
			if(!$this->delete_favorite_media_by_userName($userName, $m)) return 0;
		return 1;
	}

	function edit_playlist($userName, $playlistID, $name) {
		if($this->get_playlist($userName, $name)->num_rows()) return(0);
		$this->db->where('ID', $playlistID);
		$this->db->where('userName', $userName);
		return($this->db->update('Playlist', array('name' => $name)));
	}

}

?>
