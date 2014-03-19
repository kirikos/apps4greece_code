<?php
class Photo_reportaz_model extends CI_Model {

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
			LIMIT 6 OFFSET 0
			";
		}else{
			$sql = "SELECT *
			FROM photo_reportaz
			WHERE photo_reportaz.deleted=0 AND photo_reportaz.id NOT IN(".$admin_photos.")
			ORDER BY  photo_reportaz.published_date desc
			LIMIT 6 OFFSET 0
			";
		}
		
		$return_data = $this->db->query(($sql))->result_array();
		return $return_data;
	}
	
	public function get_more_new_photo_reportaz($admin_photos =array(),$offset){
		if(count($admin_photos)==0){
			$sql = "SELECT *
			FROM photo_reportaz
			WHERE photo_reportaz.deleted=0
			ORDER BY  photo_reportaz.published_date desc
			LIMIT 6 OFFSET ".$offset."
			";
		}else{
			$sql = "SELECT *
			FROM photo_reportaz
			WHERE photo_reportaz.deleted=0 AND photo_reportaz.id NOT IN(".$admin_photos.")
			ORDER BY  photo_reportaz.published_date desc
			LIMIT 6 OFFSET ".$offset."
			";
		}
		
		$return_data = $this->db->query(($sql))->result_array();
		return $return_data;
	}
	
	public function get_event($ev_id){
		
			$sql = "SELECT *
			FROM photo_reportaz
			WHERE photo_reportaz.deleted=0 AND photo_reportaz.id=".$ev_id."";
		$return_data = $this->db->query(($sql))->result_array();
		return $return_data;
	}
	
	public function get_mobile_latest_events($offset=0){
		
			$sql = "SELECT *
			FROM photo_reportaz
			WHERE photo_reportaz.deleted=0
			ORDER BY  photo_reportaz.published_date desc
			LIMIT 6 OFFSET ".$offset."
			";
		$return_data = $this->db->query(($sql))->result_array();
		return $return_data;
	}
	
	public function get_latest_events_by_eventIds($ids,$offset=0){
			$sql = "SELECT *
			FROM photo_reportaz
			WHERE photo_reportaz.id IN(".$ids.")
			AND photo_reportaz.deleted=0
			ORDER BY  photo_reportaz.published_date desc
			LIMIT 6 OFFSET ".$offset."
			";
		
		
		$return_data = $this->db->query(($sql))->result_array();
		
		return $return_data;
	}
	
	public function get_photos_by_ids($ids){
			$sql = "SELECT *
			FROM photo_reportaz
			WHERE photo_reportaz.id IN(".$ids.")
			";
		
		
		$return_data = $this->db->query(($sql))->result_array();
		$return_data2=null;
		$length = count($return_data);
		$i=0;
		while($i<$length){
			$return_data2[$return_data[$i]['id']] = $return_data[$i];
			$i++;
		}
		
		return $return_data2;
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