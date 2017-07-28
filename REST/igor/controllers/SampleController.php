<?php

/*
******************************************************************
Geochron IGOR REST API
Sample Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller allows users to manage igor samples
******************************************************************
*/

class SampleController extends MyController
{
    public function getAction($request) {
    
        if(isset($request->url_elements[2])) {
            $igsn = $request->url_elements[2];

            if(isset($request->url_elements[3])) {
				// do nothing, this is not a supported action
				header("Bad Request", true, 400);
				$data = "<results>\n\t<error>yes</error>\n\t<message>Error: ".$request->url_elements[3]." not supported.</message>\n</results>";
            	
            } else {
            	//get the sample from postgres
				$data = $this->grest->getIgorSample($igsn);

            }
        } else {
        	header("Bad Request", true, 400);
            $data = "<results>\n\t<error>yes</error>\n\t<message>Error: IGSN must be provided.</message>\n</results>";
        }
        return $data;
    }

    public function deleteAction($request) {

        if(isset($request->url_elements[2])) {
            $igsn = $request->url_elements[2];
            if(isset($request->url_elements[3])) {
				// do nothing, this is not a supported action
				header("Bad Request", true, 400);
				$data["Error"] = $request->url_elements[3]." not supported.";
				$data = "<results>\n\t<error>yes</error>\n\t<message>Error: ".$request->url_elements[3]." not supported.</message>\n</results>";
            } else {

				//********************************************************************
				// check for sample with userid and igsn; delete if exists
				//********************************************************************
				$data = $this->grest->deleteIgorSample($igsn);

            }
        } else {
        	header("Bad Request", true, 400);
            $data = "<results>\n\t<error>yes</error>\n\t<message>Error: IGSN must be provided.</message>\n</results>";
        }
        return $data;
    }


    public function postAction($request) {

    	$body = file_get_contents("php://input");

		$data = $this->grest->insertIgorSample($body);

		if($data->Error != ""){
			header("Bad Request", true, 400);
		}else{
			header("Sample uploaded", true, 201);
		}
        
        return $data;
    }

    public function putAction($request) {
    	
		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

        return $data;
    }

    public function optionsAction($request) {
    	
		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

        return $data;
    }

    public function patchAction($request) {
    	
		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

        return $data;
    }

    public function copyAction($request) {
    	
		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

        return $data;
    }

    public function searchAction($request) {
    	
		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

        return $data;
    }


}
