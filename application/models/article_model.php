<?php
class Article_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	
	public function insert_article($data)
	{
	
		return $this->db->insert('article', $data);
		
	}
	
	
	public function get_mobile_news($category=0,$offset=0){
		if($category==0){
			$sql = "SELECT *
			FROM article
			ORDER BY  article.published_date desc
			LIMIT 6 OFFSET ".$offset."
			";
		}else{
			$sql = "SELECT *
			FROM article
			WHERE article.category=".$category."
			ORDER BY  article.published_date desc
			LIMIT 6 OFFSET ".$offset."
			";
		}
		$return_data = $this->db->query(($sql))->result_array();
		return $return_data;
	}
	
	public function delete_article($id)
	{
	
		$this->db->where('id', $id);
		$this->db->delete('article'); 
		
	}
	
	
	
	
	public function check_exist_email($email){
		$sql = "SELECT *
        FROM user
		WHERE user.email='".$email."' AND user.active=1
		";
		
		$return_data = $this->db->query(($sql))->result_array();
		return $return_data;
	}
	
	public function check_login_authentication($email, $password){
		$sql = "SELECT *
        FROM user
		WHERE user.email='".$email."' AND user.password='".$password."' AND user.active=1
		";
		
		$return_data = $this->db->query(($sql))->result_array();
		return $return_data;
	}
	
	
	public function check_admin_login_authentication($email, $password){
		$sql = "SELECT *
        FROM user
		WHERE user.email='".$email."' AND user.password='".$password."' AND user.role=9 AND user.active=1
		";
		
		$return_data = $this->db->query(($sql))->result_array();
		return $return_data;
	}
	
	public function check_exist_inactive_user($email,$password){
		
		$check_time = time();
		$check_time = date("Y-m-d H:i:s",$check_time);
		$sql = "SELECT *
        FROM user
		WHERE user.email='".$email."' AND user.password='".$password."'  AND user.expire_date>='".$check_time."' AND user.active=0
		";
		
		$return_data = $this->db->query(($sql))->result_array();
		return $return_data;
	}
	
	public function activate_user($id)
	{
		$last_login = time();
		$last_login = date("Y-m-d H:i:s",$last_login);
		$this->db->where('id', $id);
		$this->db->update('user',array('active'=>1,'last_login'=>$last_login)); 
	
	}
	public function update_user($id,$data)
	{
		
		$this->db->where('id', $id);
		$this->db->update('user',$data); 
	
	}
	public function insert_user($data)
	{
	
		return $this->db->insert('user', $data);
		
	}
	
}
?>