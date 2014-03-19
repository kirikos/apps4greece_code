<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_mobile_news extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('article_model');
	}

	
	public function index()
	{
		$xml = new SimpleXMLElement('<xml  version="1.0" encoding="UTF-8"/>');

		if(!isset($_GET['userid']) || !isset($_GET['category'])){
		
			$track = $xml->addChild('violation','post values');
			Header('Content-type: application/xml');
			print($xml->asXML());
			return;
		
		}	
		
		$category= $_GET['category'];
		if($category!=0 && $category!=1 && $category!=2 && $category!=3 && $category!=4){
		
			$track = $xml->addChild('violation','category value');
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
		
		$news = $this->article_model->get_mobile_news($category,0);
		$length_news = count($news);
		
		$i=0;
		while($i<$length_news){
		
			$track = $xml->addChild('article');
			$track->addChild('id',$news[$i]['id']);
			$track->addChild('photo',$news[$i]['photo']);
			$track->addChild('title',$news[$i]['title']);
			$track->addChild('description',$news[$i]['description']);
			$track->addChild('category',$news[$i]['category']);
			$track->addChild('published_date',$news[$i]['published_date']);
			$i++;
		
		}
		
		Header('Content-type: application/xml');
		print($xml->asXML());
		return;
	}
}
