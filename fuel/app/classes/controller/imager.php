<?php
/**
 * Imager manages the core of the app functionality.
 * 
 * @author John Alder http://jalder.com
 *
 */

class Controller_Imager extends Controller{
	
	private $theme = 'default';
	private $user_id = 0;
	
	public function before(){
		$auth = Auth::get_user_id();
		$this->user_id = $auth[1];
		if(!$this->user_id){
			//not logged in, do something about it
		}
	}
	
	public function action_index(){
		$view = View::forge($this->theme.'/index');
		$view->images = array(); //get group of images for the recent View area
		return $view->render();
	}
	
	public function action_gallery(){
		$view = View::forge($this->theme.'/gallery');
		$images = Model_Image::find()->limit(20);
		$view->images = $images;
		return $view;
	}
	
	public function action_upload(){
		$message = array();
		if(Input::post('upload')){
			//Loop Files:
			//Validate Image
			//Store Image
			//Add Meta/Group Details to File Object to Image Model
			//Append JSON message
			//End Loop
		}
		return $this->response->body(Format::forge($message)->to_json());
	}
	
	public function action_webget(){
		$message = array();
		if(Input::post('webget')){
			//Loop URLs:
			//Validate Image URL 
			//Get Image
			//Validate Image
			//Store Image
			//Add Meta/Group Details to File Object to Image Model
			//Append JSON message
			//End Loop
		}
		return $this->response->body(Format::forge($message)->to_json());
	}
	
	private function validate_image($loc){
		//Check Allowed Types
		//Check Size
	}
	
	private function store_image($loc,$gal = 0){
		//Move From Tmp to Dest
		//Make Random ID String
		//Store Model Image Details
	}
}