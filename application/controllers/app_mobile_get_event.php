<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_mobile_get_event extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('photo_reportaz_model');
	}

	
	public function index()
	{
		$xml = new SimpleXMLElement('<xml  version="1.0" encoding="UTF-8"/>');

		if(!isset($_GET['userid']) || !isset($_GET['event_id'])){
			$track = $xml->addChild('violation','post values');
			Header('Content-type: application/xml');
			print($xml->asXML());
			return;
		}	
		
		$userid = $_GET['userid'];
		$ev_id = $_GET['event_id'];
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
		
		
		$event = $this->photo_reportaz_model->get_event($ev_id);
		if(count($event)==0){
			$track = $xml->addChild('violation','event id value');
			Header('Content-type: application/xml');
			print($xml->asXML());
			return;
		}
		
		$track = $xml->addChild('event');
		$track->addChild('photo',$event[0]['photo']);
		$track->addChild('description',$event[0]['description']);
		$track->addChild('lat',$event[0]['lat']);
		$track->addChild('lon',$event[0]['lon']);
		$track->addChild('published_date',$event[0]['published_date']);
		
		Header('Content-type: application/xml');
		print($xml->asXML());
		return;
	}
}
