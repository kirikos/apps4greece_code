<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_mobile_citizens_notification_event extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('admin_actions_model');
		$this->load->model('photo_reportaz_model');
	}

	
	public function index()
	{
		$xml = new SimpleXMLElement('<xml  version="1.0" encoding="UTF-8"/>');

		if(!isset($_GET['userid']) || !isset($_GET['lat']) || !isset($_GET['lon'])){
		
			$track = $xml->addChild('violation','post values');
			Header('Content-type: application/xml');
			print($xml->asXML());
			return;
		
		}	
		
		$userid = $_GET['userid'];
		if($userid <1){
		
			$track = $xml->addChild('violation','userid value');
			Header('Content-type: application/xml');
			print($xml->asXML());
			return;
		
		}
		
		$user = $this->user_model->get_user($userid);
		
		if(count($user)==0){
		
			$track = $xml->addChild('violation','user doesn\'t exist');
			Header('Content-type: application/xml');
			print($xml->asXML());
			return;
		
		}
		
		$lat=$_GET['lat'];
		$lon=$_GET['lon'];
		
		$create_time = time();
		$create_time = date("Y-m-d H:i:s",$create_time);
		$day_before = date('Y-m-d H:i:s',strtotime($create_time . ' -1 day'));
		
		$events= $this->admin_actions_model->get_citizens_notifications($day_before);
		$length_events = count($events);
		
		$i=0;
		$admin_photo_ids="";
		while($i<$length_events){
			if($i==0){
				$admin_photo_ids=$events[$i]['photo_reportaz_id'];
			}else{
				$admin_photo_ids .= ','.$events[$i]['photo_reportaz_id'];
			}
			$i++;
		}
		
		$photos = array();
		if(strlen($admin_photo_ids)>0){
			$photos =$this->photo_reportaz_model->get_photos_by_ids($admin_photo_ids);
		}
		
		$i=0;
		while($i<$length_events){
			if($events[$i]['whole_area_citizens']==1){
				$track = $xml->addChild('article');
				$track->addChild('id',$events[$i]['id']);
				$track->addChild('photo_reportaz_id	',$events[$i]['photo_reportaz_id']);
				$track->addChild('description',$events[$i]['description_citizens']);
				$track->addChild('published_date',$events[$i]['published_date_citizens']);
			}else{
				
				$distance = $events[$i]['distance_citizens'];
				$distance = 0.00001 * $distance;
				$x1 = $lon + $distance;
				$x2 = $lon - $distance;
				$y1 = $lat + $distance;
				$y2 = $lat - $distance;
					
				if($x1<0){
					$tmp_x = $x1;
					$x1=$x2;
					$x2=$tmp_x;
				}
				if($y1<0){
					$tmp_y = $y1;
					$y1=$y2;
					$y2=$tmp_y;
				}
				
				$ok=0;
				if($x1>=$x2 && $y1>=$y2){
					if($photos[$events[$i]['photo_reportaz_id']]['lon'] <= $x1 && $photos[$events[$i]['photo_reportaz_id']]['lon'] >= $x2 && $photos[$events[$i]['photo_reportaz_id']]['lat'] <= $y1 && $photos[$events[$i]['photo_reportaz_id']]['lat'] >= $y2){
						$ok=1;
					}
				}else if(($x1>=$x2 && $y1<=$y2)){
					if($photos[$events[$i]['photo_reportaz_id']]['lon'] <= $x1 && $photos[$events[$i]['photo_reportaz_id']]['lon'] >= $x2 && $photos[$events[$i]['photo_reportaz_id']]['lat'] >= $y1 && $photos[$events[$i]['photo_reportaz_id']]['lat'] <= $y2){
						$ok=1;
					}
				}else if(($x1<=$x2 && $y1>=$y2)){
					if($photos[$events[$i]['photo_reportaz_id']]['lon'] >= $x1 && $photos[$events[$i]['photo_reportaz_id']]['lon'] <= $x2 && $photos[$events[$i]['photo_reportaz_id']]['lat'] <= $y1 && $photos[$events[$i]['photo_reportaz_id']]['lat'] >= $y2){
						$ok=1;
					}
				}else{
					if($photos[$events[$i]['photo_reportaz_id']]['lon'] >= $x1 && $photos[$events[$i]['photo_reportaz_id']]['lon'] <= $x2 && $photos[$events[$i]['photo_reportaz_id']]['lat'] >= $y1 && $photos[$events[$i]['photo_reportaz_id']]['lat'] <= $y2){
						$ok=1;
					}
				}
				
				if($ok){
					$track = $xml->addChild('article');
					$track->addChild('id',$events[$i]['id']);
					$track->addChild('photo_reportaz_id	',$events[$i]['photo_reportaz_id']);
					$track->addChild('description',$events[$i]['description_citizens']);
					$track->addChild('published_date',$events[$i]['published_date_citizens']);
				}
			}
			
			$i++;
		}
		
		Header('Content-type: application/xml');
		print($xml->asXML());
		return;
	}
}
