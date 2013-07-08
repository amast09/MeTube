<?php
class Rating_model extends CI_Model {

	function rate_media($userName, $mediaID, $rating){
		if($this->get_rating($userName, $mediaID)->num_rows() == 1){
			if($rating == 0 || $rating == 1)
				return($this->update_rating($userName, $mediaID, $rating));
			else return($this->delete_rating($userName, $mediaID, $rating));
		}
		else if($rating == 0 || $rating == 1){
			return($this->set_rating($userName, $mediaID, $rating));
		}
	}

	function get_rating($userName, $mediaID){
		$this->db->where('userName', $userName);
		$this->db->where('mediaID', $mediaID);
		return($this->db->get('Rating'));
	}

	function update_rating($userName, $mediaID, $rating){
		$this->db->where('userName', $userName);
		$this->db->where('mediaID', $mediaID);

		if(!$this->db->update('Rating', array('rating' => $rating))) return 0;;

		$this->update_media_ratings($mediaID);
		return 1;
	}

	function set_rating($userName, $mediaID, $rating){
		$data = array('userName' => $userName, 'mediaID' => $mediaID, 'rating' => $rating);

		if(!$this->db->insert('Rating', $data)) return 0;
		$this->update_media_ratings($mediaID);
		return 1;
	}

	function delete_rating($userName, $mediaID){
		$this->db->where('userName', $userName);
		$this->db->where('mediaID', $mediaID);

		if(!$this->db->delete('Rating')) return 0;
		$this->update_media_ratings($mediaID);
		return 1;
	}

	function count_ratings($mediaID){
		$query = $this->db->query("
			SELECT COUNT(*) AS total, SUM(rating) AS sum
			FROM Rating
			WHERE Rating.mediaID = $mediaID
		");
		return($query);
	}

	function update_media_ratings($mediaID) {
		// update the rating colum in the media database
		$ratingResult = $this->count_ratings($mediaID)->row();
		$sum = $ratingResult->sum;
		$total = $ratingResult->total;
		$overallRating = ((2 * $sum) - $total);

		$this->db->query("UPDATE Media SET Rating = $overallRating WHERE ID = $mediaID");
	}

}
?>
