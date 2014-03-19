<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_admin_more_events extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('photo_reportaz_model');
		$this->load->model('admin_actions_model');
	}

	
	public function index()
	{
	
		@session_start();
		if(!isset($_SESSION['admin_active'])){
			echo 'problem';
			return;
		}
		
		if($_SESSION['admin_active']!='iAmAdmin'){
			echo 'problem';
			return;
		}
		if(!isset($_POST)){
			echo 'problem';
			return;
		}
		if(!isset($_POST['offset'])){
			echo 'problem';
			return;
		}
		
		$offset = $_POST['offset'];
		if($offset<0){
			echo 'problem';
			return;
		}
		
		$exclude_photos = $this->admin_actions_model->get_photoId_admin_actions_all();
		
		$photos = array();
		$count_exc = count($exclude_photos);
		if($count_exc==0){
			$photos =$this->photo_reportaz_model->get_more_new_photo_reportaz(array(),$offset);
		}else{
			$i=0;
			$ex_id = "";
			while($i<$count_exc){
				
				if($i==0){				
					$ex_id = $exclude_photos[$i]['photo_reportaz_id'];
					
				}else{
					$ex_id .=','.$exclude_photos[$i]['photo_reportaz_id'];
				}
				$i++;
			}
			
			$photos =$this->photo_reportaz_model->get_more_new_photo_reportaz($ex_id,$offset);
		}
		
		$users_by_photo = array();
		if(count($photos)>0){
			$users_id = "";
			$i=0;
			$length_photos = count($photos);
			while($i<$length_photos){
				if($i==0){				
					$users_id = $photos[$i]['user_id'];
				}else{
					$users_id .=','.$photos[$i]['user_id'];
				}
				$i++;
			}
			
			$users_by_photo = $this->user_model->get_users($users_id);
			
		}
		
		$users_photos = $users_by_photo;
		$count_photos = count($photos);
		if($count_photos==0){
			echo 'No Data.';
			return;
		}
		
		$i=$offset;
		$j=0;
		while($j<$count_photos){
			echo '<section class="container events" style="margin: 30px auto; width:700px;">
			<div class="login" style="width:631px;">
			<div style="height:351px; margin: -20px -20px 0px;
			line-height: 40px;
			font-size: 15px;
			font-weight: bold;
			color: #555;
			text-align: center;
			text-shadow: 0 1px white;
			background: #f3f3f3;
			border-bottom: 1px solid #cfcfcf;
			border-radius: 3px 3px 0 0;
			background-image: -webkit-linear-gradient(top, whiteffd, #eef2f5);
			background-image: -moz-linear-gradient(top, whiteffd, #eef2f5);
			background-image: -o-linear-gradient(top, whiteffd, #eef2f5);
			background-image: linear-gradient(to bottom, whiteffd, #eef2f5);
			-webkit-box-shadow: 0 1px whitesmoke;
			box-shadow: 0 1px whitesmoke;">
			<div style="float:left;"><img id="p_'.$i.'" style="width:361px; float:left; max-height:270px;" src="/'.$photos[$j]['photo'].'" />
			<div style="width:361px; height:271px; display:none; float:left;" id="map-canvas'.$i.'"></div>

			<div style="float: right;">
			<div><img onclick="open_close_map('.$i.',\''.$photos[$j]['lat'].'\',\''.$photos[$j]['lon'].'\');" style="width: 41px; height: 41px;float: right; cursor:pointer;" src="/images/map_icon.png"/></div>

			<div style="float: right;width: 300px;word-wrap: break-word;text-align: left;height: 230px;margin-left: 5px;font-size: 14px;">'.$photos[$j]['description'].'</div>

			</div>
			</div>
			<div style="float:left;">Από: '.$users_photos[$photos[$j]['user_id']]['email'].'</div>
			<p class="submit" style="margin-top: 22px;float: right;border: 2px solid #A29595;">
			<textarea id="delete_reason'.$i.'" style="margin: 0px; float: left; max-height: 41px; max-width: 177px;" placeholder="Αιτία διαγραφής"></textarea>
			<input onclick="delete_photo('.$i.','.$photos[$j]['id'].')" style="color:red;" type="submit" name="commit" value="Delete"></p>
			<div style="float:left; clear:left; color:black; font-size:13px;">'.$photos[$j]['published_date'].'</div>
			</div>


			<div style="width: 633px; height:100px;margin: 0 auto;/* float: left; */">
			<div style="
				border-right: 1px solid #cfcfcf;
				width: 316px;
				height: 100px;
				float: left;
			">
			<div style="margin-top: 1px;">
			<input id="n_citizens'.$i.'" type="checkbox" name="category_people" value="citizens"><font style="font-size: 15px;">Δημότες</font>
			</div>
			<div>
			<textarea id="d_citi'.$i.'" name="description_citizens" value="" placeholder="Τίτλος ειδοποίησης" style="
			min-width: 280px;
			max-width: 281px;
			min-height: 65px;
			max-height: 66px;
			margin: 5px;
			padding: 0px 10px;
			width: 302px;
			height: 77px;
			color: rgb(64, 64, 64);
			background-color: white;
			border-width: 1px;
			border-style: solid;
			border-color: rgb(196, 196, 196) rgb(209, 209, 209) rgb(212, 212, 212);
			border-top-left-radius: 2px;
			border-top-right-radius: 2px;
			border-bottom-right-radius: 2px;
			border-bottom-left-radius: 2px;
			outline: rgb(239, 244, 247) solid 5px;
			-webkit-box-shadow: rgba(0, 0, 0, 0.117647) 0px 1px 3px inset;
			box-shadow: rgba(0, 0, 0, 0.117647) 0px 1px 3px inset;"></textarea>
			</div>
			</div>
			<div style="
				width: 316px;
				height: 100px;
				float: right;
			">
			 

			<div style="margin-top: 1px;">
			<input id="n_officials'.$i.'" type="checkbox" name="category_officials" value="officials"><font style="font-size: 15px;">Συνεργεία Δήμου</font>
			</div>
			<div>
			<textarea id="d_officials'.$i.'" name="description_citizens" value="" placeholder="Τίτλος ειδοποίησης" style="
			min-width: 280px;
			max-width: 281px;
			min-height: 65px;
			max-height: 66px;
			margin: 5px;
			padding: 0px 10px;
			width: 302px;
			height: 77px;
			color: rgb(64, 64, 64);
			background-color: white;
			border-width: 1px;
			border-style: solid;
			border-color: rgb(196, 196, 196) rgb(209, 209, 209) rgb(212, 212, 212);
			border-top-left-radius: 2px;
			border-top-right-radius: 2px;
			border-bottom-right-radius: 2px;
			border-bottom-left-radius: 2px;
			outline: rgb(239, 244, 247) solid 5px;
			-webkit-box-shadow: rgba(0, 0, 0, 0.117647) 0px 1px 3px inset;
			box-shadow: rgba(0, 0, 0, 0.117647) 0px 1px 3px inset;"></textarea>
			</div> 

			</div>
			</div>
			<div style="width:632px; height:65px; border-top: 1px solid #cfcfcf;">
			<div style="font-size:14px;" id="whole_distance'.$i.'">Αποστολή ειδοποίησης σε όλη την περιοχή του δήμου?
			<input id="whole_distane'.$i.'" onclick="open_close_distance('.$i.');" type="radio" name="whole_distance'.$i.'" value="1">ΝΑΙ<input id="specify_distane'.$i.'" onclick="open_close_distance('.$i.');" type="radio" name="whole_distance'.$i.'" value="0">ΟΧΙ</div>
			<div id="not_distance'.$i.'" style="display:none; font-size:14px;"><font style=" float: left;margin-top: 10px;">Αποστολή ειδοποίησης σε ακτίνα απόστασης </font><input id="distance_value'.$i.'" style="width: 33px;height: 30px; float:left;" name="distance" type="text"><font style=" float: left;margin-top: 10px;">μέτρα</font><p class="submit" style="float:left; margin-top:5px;"><input onclick="open_close_distance_map('.$i.',\''.$photos[$j]['lat'].'\',\''.$photos[$j]['lon'].'\');" type="submit" name="commit" value="set"></p></div>
			</div>

			<p style="margin:0;" class="submit"><input id="s_'.$i.'" onclick="check_save('.$i.','.$photos[$j]['id'].');" style="cursor:pointer;" type="submit" name="commit" value="Save"></p>
				</div>


			  </section>';
			  $i++;
			  $j++;

		}
		return;
	}
}
