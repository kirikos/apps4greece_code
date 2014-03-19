<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_event_upload extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('photo_reportaz_model');
	}

	
	public function index()
	{
		$xml = new SimpleXMLElement('<xml  version="1.0" encoding="UTF-8"/>');
		
		if(!isset($_POST['userid']) || !isset($_POST['image']) || !isset($_POST['long']) || !isset($_POST['lat'])  || !isset($_POST['description']) ){
			$track = $xml->addChild('violiation','Post Data');
			Header('Content-type: application/xml');
			print($xml->asXML());
			return;
		}
		
		$userid=$_POST['userid'];
		if(strlen($_POST['description'])<8 || $userid<1 ){
			$track = $xml->addChild('violiation','Wrong values');
			Header('Content-type: application/xml');
			print($xml->asXML());
			return;
		}
		
		$description = $_POST['description'];
		$long=$_POST['long'];
		$lat=$_POST['lat'];
		
		$userid3 = substr($userid, -3);
		
		if (is_dir("photos/".$userid3."")) {
		} else {
			mkdir("photos/".$userid3."");
		}
		
		$now =time();
		$published_date = date("Y-m-d H:i:s",$now);
		$insert_name = date("Y_m_d_H_i_s",$now);
		$photo_name = $userid.''.$insert_name;
		
		$image_path = 'photos/'.$userid3.'/'.$photo_name.'.jpg';
	
		$data = base64_decode($_POST['image']);
		$new = imagecreatefromstring($data);
		
		imagejpeg($new,$image_path,95);
		
		$insert_data=array("user_id"=>$userid,"photo"=>$image_path,"description"=>urldecode($description),"lat"=>$lat,"lon"=>$long,"published_date"=>$published_date);
		$this->photo_reportaz_model->insert_photo($insert_data);
		echo '1';
	}
}
