<?php 


class Controller_Cloud extends Controller{

	private $user_id = 0;
	
	public function before(){
		
		$user = Auth::get_user_id();
		$this->user_id = $user[1];
		
	}
	
	public function action_index(){
		

		
		
		
	}


	public function action_send(){
		
		$msg = '';
		
		if(Input::post('alphaID')){
			$alpha = explode('.',Input::post('alphaID'));
			$image_id = Model_Image::alphaID($alpha[0],true);
			$image = Model_Image::find($image_id);
			$msg .= $this->user_id;
			if((int)$image->user_id === (int)$this->user_id){
				$rack = new Controller_Rackspace($this->request,$this->response);
				$rack->before();
				$image->location = $rack->store_object($image->short_name,'photobully');
				$extension_pos = strrpos($image->short_name, '.');
				$filename = substr($image->short_name, 0, $extension_pos).'_b'.substr($image->short_name, $extension_pos);
				$rack->store_object($filename,'photobully');
				$filename = substr($image->short_name, 0, $extension_pos).'_s'.substr($image->short_name, $extension_pos);
				$rack->store_object($filename,'photobully');
				$filename = substr($image->short_name, 0, $extension_pos).'_l'.substr($image->short_name, $extension_pos);
				$rack->store_object($filename,'photobully');								
				$msg .= 'iamhere';
				$image->save();
			}
			
		}
		
		return $this->response->body($msg);
		
	}
	

}