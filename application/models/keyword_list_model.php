<?php

class Keyword_list_model extends CI_Model {

	// delete a mediaID-keywordID pair from the keywordList
	function delete_keywords($mediaID, $keywordID) {
		$this->db->where('mediaID', $mediaID);
		$this->db->where('keywordID', $keywordID);

		return($this->db->delete('KeywordList'));
	}

	// delete all keywords associated with a specific media
	function delete_all_keywords($mediaID) {
		$this->db->where('mediaID', $mediaID);

		return($this->db->delete('KeywordList'));
	}

	// get a list of all keywordIDs associated with the given media
	function get_keywordIDs_by_mediaID($mediaID) {
		$this->db->where('mediaID', $mediaID);

		return($this->db->get('KeywordList'));
	}

	// get the name of all keywords associated with the given media
	function get_keywords_by_mediaID($mediaID) {
		$this->db->select('Keyword.keyword');
		$this->db->from('KeywordList');
		$this->db->join('Keyword', 'KeywordList.keywordID = Keyword.ID');
		$this->db->where('mediaID', $mediaID);

		return($this->db->get());
	}
}

?>
