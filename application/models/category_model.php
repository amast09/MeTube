<?php
class Category_model extends CI_Model {

	function get_categories(){
		$this->db->order_by('name');
		return($this->db->get('Category'));
	}

	function get_categoryID($category){
		$query = 'SELECT ID
						 	FROM Category
							WHERE name="'.$category.'"';

		$result = mysql_query($query);

		$row = mysql_fetch_array($result);
		return($row['ID']);
	}

	function get_media_category($mediaID){
		$query = $this->db->query("
			SELECT name
			FROM Category
			JOIN CategoryList ON CategoryList.CategoryID = Category.ID
			WHERE CategoryList.mediaID = $mediaID
		");
		return($query->row()->name);
	}
}
