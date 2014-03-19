<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Event Console</title>
  <link rel="stylesheet" href="/css/style.css">
     <style>
  .header_menu a{
	color: #BFBFBF;
display: block;
font-size: 16px;
font-weight: bold;
letter-spacing: 0.02em;
line-height: 19px;
padding: 10px 15px 0 12px;
text-decoration: none;
float: left;
cursor:pointer;
  }
    .header_menu a:hover{
	color: white;
  }
  </style>
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC91LqwgiYemryx2QBqRplDD9TpAkLfRm0&amp;sensor=true">
    </script>
	<script type="text/javascript" src="/script/jquery-1.9.js"></script>
</head>
<body>

<div style="background: url('http://www.e-progress.gr/images/topbar.png') repeat-x scroll 0 0 transparent;
height: 55px;
font-weight: bold;
font-size: 1.1em;
border-bottom: 1px solid #FFF;"><div style="width:375px; margin:0 auto;">
<div class="header_menu"><a  style="color:white;" href="http://www.e-progress.gr/thessaloniki_appsforgreece/admin_console">Γεγονότα</a></div>
<div class="header_menu"><a href="http://www.e-progress.gr/thessaloniki_appsforgreece/show_articles">Άρθρα</a></div>
<div class="header_menu"><a href="http://www.e-progress.gr/thessaloniki_appsforgreece/add_article">Προσθήκη Άρθρου</a></div>

</div>
<div class="header_menu" style="float:right; margin:0 auto;"><a href="http://www.e-progress.gr/thessaloniki_appsforgreece/admin_logout">Αποσύνδεση</a></div>
</div>


<?php
$count_photos = count($photos);
$i=0;
echo '<div id="post_container">';
while($i<$count_photos){
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
<div style="float:left;"><img id="p_'.$i.'" style="width:361px; float:left; max-height:270px;" src="/'.$photos[$i]['photo'].'" />
<div style="width:361px; height:271px; display:none; float:left;" id="map-canvas'.$i.'"></div>

<div style="float: right;">
<div><img onclick="open_close_map('.$i.',\''.$photos[$i]['lat'].'\',\''.$photos[$i]['lon'].'\');" style="width: 41px; height: 41px;float: right;  cursor:pointer;" src="/images/map_icon.png"/></div>

<div style="float: right; width: 300px;word-wrap: break-word;text-align: left;height: 230px;margin-left: 5px;font-size: 14px;">'.$photos[$i]['description'].'</div>

</div>
</div>
<div style="float:left;">Από: '.$users_photos[$photos[$i]['user_id']]['email'].'</div>
<p class="submit" style="margin-top: 22px;float: right;border: 2px solid #A29595;">
<textarea id="delete_reason'.$i.'" style="margin: 0px; float: left; max-height: 41px; max-width: 177px;" placeholder="Αιτία διαγραφής"></textarea>
<input onclick="delete_photo('.$i.','.$photos[$i]['id'].')" style="color:red;" type="submit" name="commit" value="Delete"></p>
<div style="float:left; clear:left; color:black; font-size:13px;">'.$photos[$i]['published_date'].'</div>
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
<div id="not_distance'.$i.'" style="display:none; font-size:14px;"><font style=" float: left;margin-top: 10px;">Αποστολή ειδοποίησης σε ακτίνα απόστασης </font><input id="distance_value'.$i.'" style="width: 33px;height: 30px; float:left;" name="distance" type="text"><font style=" float: left;margin-top: 10px;">μέτρα</font><p class="submit" style="float:left; margin-top:5px;"><input onclick="open_close_distance_map('.$i.',\''.$photos[$i]['lat'].'\',\''.$photos[$i]['lon'].'\');" type="submit" name="commit" value="set"></p></div>
</div>

<p style="margin:0;" class="submit"><input id="s_'.$i.'" onclick="check_save('.$i.','.$photos[$i]['id'].');" style="cursor:pointer;" type="submit" name="commit" value="Save"></p>
    </div>


  </section>';
  $i++;

}
echo '</div>';
echo '<div id="bmore" onclick="more_events()" style="width: 44%;
border: 1px solid silver;
border-radius: 5px;
margin: 20px auto;
cursor: pointer;
cursor: hand;
clear: left;
height: 30px;
text-align: center;
background-color: #eee;">
						<div style="background: url(\'../images/more.png\') no-repeat scroll 0 4px transparent;
display: inline-block;
float: none;
font-size: 15px;
height: 26px;
padding-left: 30px;
padding-top: 5px;
width: auto;
margin: 0 auto;">
						Προβολή περισσοτέρων
						</div>
					</div>';
?>



</body>

<script>

$(document).ready(function() {
var map;
  




});

function open_close_distance_map(i, lat,lon){



var m_id = "map-canvas"+i;
var distance = $(("#distance_value"+i)).val();

if ( $("#"+m_id).is(":visible") ) {
	$(("#p_"+i)).css("display","none");
	$(("#"+m_id)).css("display","block");
	google.maps.event.addDomListener(window, 'load', initialize_distance(m_id,lat,lon,distance));
} else { 
	$(("#p_"+i)).css("display","none");
	$(("#"+m_id)).css("display","block");
	google.maps.event.addDomListener(window, 'load', initialize_distance(m_id,lat,lon,distance));
   
}



}


function open_close_map(i, lat,lon){


var m_id = "map-canvas"+i;


if ( $("#"+m_id).is(":visible") ) {
   
	$(("#"+m_id)).css("display","none");
	$(("#p_"+i)).css("display","block");
} else { 
	$(("#p_"+i)).css("display","none");
	$(("#"+m_id)).css("display","block");
	google.maps.event.addDomListener(window, 'load', initialize(m_id,lat,lon));
   
}

}


function initialize(m_id,lat,lon) {
    var mapOptions = {
        center: new google.maps.LatLng(lat, lon),
        zoom: 12
    };
	 map = new google.maps.Map(document.getElementById(m_id),
            mapOptions);
	 var marker = new google.maps.Marker({
        position : new google.maps.LatLng(lat, lon),
        map: map

      });
}



function initialize_distance(m_id,lat,lon,distance) {



    var mapOptions = {
        center: new google.maps.LatLng(lat, lon),
        zoom: 12
    };
	 map = new google.maps.Map(document.getElementById(m_id),
            mapOptions);
			google.maps.event.trigger(map, 'resize');
	 var marker = new google.maps.Marker({
        position : new google.maps.LatLng(lat, lon),
        map: map

      });
	  marker.setMap(map);
	
	var myLatlng = new google.maps.LatLng(lat,lon);
	var sunCircle = {
        strokeColor: "#04805E",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#04805E",
        fillOpacity: 0.35,
        map: map,
        center: myLatlng,
        radius: parseInt(distance) // in meters
    };
    cityCircle = new google.maps.Circle(sunCircle);
	
	
	
    cityCircle.bindTo('center', marker, 'position');
}


function open_close_distance(i){

var not_dist = "not_distance"+i;
var whole_dist = "whole_distance"+i;
var check = $("input[name='whole_distance"+i+"']:checked").val();

if ( check==1 ) {
	$("#"+not_dist).css("display","none");
	$("#"+whole_dist).css("display","block");
} else { 
	$("#"+not_dist).css("display","block");
   
}
}    

function check_save(i,photo){
var n_officials = '#n_officials'+i;
var n_citizens = '#n_citizens'+i;
var d_officials = '#d_officials'+i;
var d_citizens = '#d_citi'+i;
var check = $("input[name='whole_distance"+i+"']:checked").val();
var distance_value= '#distance_value'+i;

var whole_distance=1;
var distanceOfNot=0;
var descript_citi ='';
var descript_offic ='';

if($(n_officials).is(':checked') || $(n_citizens).is(':checked')){
	if($(n_officials).is(':checked')){
		
	
		if($("textarea"+d_officials).val().length<6){
			alert("Ο τίτλος ειδοποίσης μεγαλύτερος των 5 χαραχτήρων");
			return false;
		}
		descript_offic = $("textarea"+d_officials).val();
	}
	if($(n_citizens).is(':checked')){
		if($("textarea"+d_citizens).val().length<6){
			alert("Ο τίτλος ειδοποίσης μεγαλύτερος των 5 χαραχτήρων");
			return false;
		}
		descript_citi = $("textarea"+d_citizens).val();
	}
	
	if(check==1){
		whole_distance=1;
	}else if(check==0){
		whole_distance=0;
		if($(distance_value).length==0){
			alert("Ορίστε απόσταση ειδοποίησης");
			return false;
		}
		distanceOfNot=$(distance_value).val();
	}else{
		alert("Επέλεξε απόσταση ειδοποίησης");
		return false;
	}
	
	$(('#s_'+i)).attr('disabled', 'disabled');
	$.ajax({
		type: "POST",
        url: "/thessaloniki_appsforgreece/send_notification",
		data: {
            id : photo,
			whole_area: whole_distance,
			distance: distanceOfNot,
			title_officials: descript_offic,
			title_citizens: descript_citi
			
        }
		
		})
		.error(function(response){
			$(('#s_'+i)).removeAttr('disabled');
			console.log('Requesting error. Please try again.');
		})
		.success (function(response) {
			if(response!='problem'){
				window.location = "http://www.e-progress.gr/thessaloniki_appsforgreece/admin_console";
			}else{
				console.log('Requesting error. Please try again.');
			}
			$(('#s_'+i)).removeAttr('disabled');

		});

}else{
	alert("Πρέπει να επιλέξεις Δημότες ή/και συνεργεία.");
	return false;
}

}

function delete_photo(i,photo){
	var delete_reason="#delete_reason"+i;
	
	if($("textarea"+delete_reason).val().length<6){
		alert("Η αιτία διαγραφής μεγαλύτερη των 5 χαραχτήρων.");
		return false;
	}
	//alert($("textarea"+delete_reason).val());
	d_reas = $("textarea"+delete_reason).val();
	$.ajax({
		type: "POST",
        url: "/thessaloniki_appsforgreece/delete_photo",
		data: {
            reason : d_reas,
			id: photo
        }
		
		})
		.error(function(response){
			console.log('Requesting error. Please try again.');
		})
		.success (function(response) {
		
			if(response!='problem'){
				window.location = "http://www.e-progress.gr/thessaloniki_appsforgreece/admin_console";
			}else{
				console.log('Requesting error. Please try again.');
			}

		});
	
}


function more_events(){
	
var offs = $('.events').length;
	$.ajax({
		type: "POST",
        url: "/thessaloniki_appsforgreece/more_events",
		data: {
			offset: offs
        }
		
    })
	.error(function(response){
	console.log('Requesting error. Please try again.')
	})
    .success (function(response) {  
		if(response == 'No Data.'){
			$('#bmore').remove();
			
		}
		else if(response == 'problem'){
			console.log('Requesting error. Please try again.');
		}
		else{
			document.getElementById('post_container').innerHTML += response;
		}
	});
}

</script>
</html>
