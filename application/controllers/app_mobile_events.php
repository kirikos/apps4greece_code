<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_mobile_events extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('article_model');
		$this->load->model('photo_reportaz_model');
		$this->load->model('admin_actions_model');
	}

	
	public function index()
	{
		$xml = new SimpleXMLElement('<xml  version="1.0" encoding="UTF-8"/>');

		if(!isset($_GET['userid'])){
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
		
		$role=0;
		if($user[0]['role']==1){
			$role=1;
		}else{
			$role=0;
		}
		$event_reportazIds = $this->admin_actions_model->get_photoId_admin_actions($role);
		$length_event_reportazIds = count($event_reportazIds);
		if($length_event_reportazIds ==0 ){
			Header('Content-type: application/xml');
			print($xml->asXML());
			return;
		}
		$i=0;
		$g_ids ='';
		
		while($i<$length_event_reportazIds){
			if($i==0){
				$g_ids=''.$event_reportazIds[$i]['photo_reportaz_id'];
			}else{
				$g_ids.=','.$event_reportazIds[$i]['photo_reportaz_id'];
			}
			$i++;
		}
		
		$news =$this->photo_reportaz_model->get_latest_events_by_eventIds($g_ids,0);
		$length_news = count($news);
		
		$i=0;
		while($i<$length_news){
			$track = $xml->addChild('article');
			$track->addChild('id',$news[$i]['id']);
			$track->addChild('photo',$news[$i]['photo']);
			$track->addChild('lat',$news[$i]['lat']);
			$track->addChild('lon',$news[$i]['lon']);
			$track->addChild('description',$news[$i]['description']);
			$track->addChild('published_date',$news[$i]['published_date']);
			$i++;
		}
		
		Header('Content-type: application/xml');
		print($xml->asXML());
		return;
	}
}
