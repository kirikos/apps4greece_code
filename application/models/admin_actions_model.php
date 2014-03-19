<?php
class Admin_actions_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	
	public function get_photoId_admin_actions_all(){
		
		$sql = "SELECT admin_actions.photo_reportaz_id
		FROM admin_actions
		";
		$return_data = $this->db->query(($sql))->result_array();
		return $return_data;
	}
	
	public function get_photoId_admin_actions($role){
		if($role==0){
			$sql = "SELECT admin_actions.photo_reportaz_id
			FROM admin_actions WHERE citizens=1
			";
		}else{
			$sql = "SELECT admin_actions.photo_reportaz_id
			FROM admin_actions WHERE officials=1
			";
		}
		$return_data = $this->db->query(($sql))->result_array();
		return $return_data;
	}
	
	public function get_citizens_admin_repotazId(){
		
		$sql = "SELECT admin_actions.photo_reportaz_id
		FROM admin_actions
		WHERE admin_actions.citizens=1
		";
		$return_data = $this->db->query(($sql))->result_array();
		return $return_data;
	}
	
	public function get_citizens_notifications($pub_date){
		
		$sql = "SELECT *
		FROM admin_actions
		WHERE admin_actions.citizens=1 AND admin_actions.published_date_citizens>='".$pub_date."'
		";
		$return_data = $this->db->query(($sql))->result_array();
		return $return_data;
	}
	
	
	public function get_officials_notifications($pub_date){
		
		$sql = "SELECT *
		FROM admin_actions
		WHERE admin_actions.officials=1 AND admin_actions.published_date_officials>='".$pub_date."'
		";
		$return_data = $this->db->query(($sql))->result_array();
		return $return_data;
	}
	
	
	public function insert_admin_actions($data)
	{
	
		return $this->db->insert('admin_actions', $data);
		
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