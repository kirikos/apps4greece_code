<?php
class Notifications_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	
	public function get_new_photo_reportaz($admin_photos =array()){
		if(count($admin_photos)==0){
			$sql = "SELECT *
			FROM photo_reportaz
			WHERE photo_reportaz.deleted=0
			ORDER BY  photo_reportaz.published_date desc
			";
		}else{
			$sql = "SELECT *
			FROM photo_reportaz
			WHERE photo_reportaz.deleted=0 AND photo_reportaz.id NOT IN(".$admin_photos.")
			ORDER BY  photo_reportaz.published_date desc
			";
		}
		
		$return_data = $this->db->query(($sql))->result_array();
		return $return_data;
	}
	
	
	public function delete_photo($id,$data)
	{
		
		$this->db->where('id', $id);
		$this->db->update('photo_reportaz',$data); 
	
	}
	
	public function insert_photo($data)
	{
	
		return $this->db->insert('photo_reportaz', $data);
		
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