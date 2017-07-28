<?php

/*
******************************************************************
Geochron REST API
My Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This is the base controller for the Geochron REST API.
				All other controllers stem from this class.
******************************************************************
*/

class MyController
{
 	public function setGeochronRestHandler($grest){
 		$this->grest=$grest;
 	}
 	
 	public function foobar($value){
 	
 		echo "$value";exit();
 	
 	}
 	
}

?>