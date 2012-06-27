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
			//die('Not Logged In');
		}
	}
	
	public function action_index(){
		$view = View::forge($this->theme.'/index');
		$view->header = View::forge($this->theme.'/includes/header',array('active'=>'dashboard'));
		$view->footer = View::forge($this->theme.'/includes/footer');
		$view->images = Model_Image::find()->order_by('created_at','DESC')->limit(16)->get(); //get group of images for the recent View area
		$files = array();
		foreach($view->images as $i){
			$extension_pos = strrpos($i->short_name, '.');
			$filename = substr($i->short_name, 0, $extension_pos).'_s'.substr($i->short_name, $extension_pos);
			$files[$i->id] = $filename;
		}
		$view->files = $files;
		$view->active = 'dashboard';
		return $view->render();
	}
	
	public function action_gallery(){
		$view = View::forge($this->theme.'/gallery');
		$view->header = View::forge($this->theme.'/includes/header',array('active'=>'gallery'));
		$view->footer = View::forge($this->theme.'/includes/footer');
		$images = Model_Image::find()->order_by('created_at','DESC')->limit(64)->get();
		$view->images = $images;
		$files = array();
		foreach($view->images as $i){
			$extension_pos = strrpos($i->short_name, '.');
			$filename = substr($i->short_name, 0, $extension_pos).'_b'.substr($i->short_name, $extension_pos);
			$files[$i->id] = $filename;
		}
		$view->files = $files;	
		$view->active = 'gallery';	
		return $view;
	}
	
	public function action_get(){
		$id = 0;
		if(strpos($this->param('id'),'_')){
			$splode = explode('_',$this->param('id'));
			
			$id = Model_Image::alphaID($splode[0],true);
		}
		else{
			$id = Model_Image::alphaID($this->param('id'),true);
		}
		$image = Model_Image::find($id);
		if($image){
			$filename = $image->short_name;
			if(isset($splode[1])){
				$extension_pos = strrpos($filename, '.');
				$filename = substr($filename, 0, $extension_pos).'_'.$splode[1].substr($filename, $extension_pos);
			}
			$path = APPPATH.'files/'.$filename;
			$config = array();
			try{
				$img = Image::load($path);
				$img->output();
			}catch(Exception $e){
				die('image not found');
			}
		}
		die();
		//return '';
	}	
	
	public function action_download(){
		$id = 0;
		if(strpos($this->param('id'),'_')){
			$splode = explode('_',$this->param('id'));
			
			$id = Model_Image::alphaID($splode[0],true);
		}
		else{
			$id = Model_Image::alphaID($this->param('id'),true);
		}
		$image = Model_Image::find($id);
		if($image){
			$filename = $image->short_name;
			if(isset($splode[1])){
				$extension_pos = strrpos($filename, '.');
				$filename = substr($filename, 0, $extension_pos).'_'.$splode[1].substr($filename, $extension_pos);
			}
			$path = APPPATH.'files/'.$filename;
			$config = array();
			try{
				$img = File::download($path);
				
			}catch(Exception $e){
				die('image not found');
			}
		}
		die();		
	}
	
	public function action_single(){
		$id = Model_Image::alphaID($this->param('id'),true);
		$image = Model_Image::find($id);
		
		if(!$image){
			die('Image not found');
		}
		
		$view = View::forge($this->theme.'/single');
		$view->header = View::forge($this->theme.'/includes/header',array('active'=>'gallery'));
		$view->footer = View::forge($this->theme.'/includes/footer');
		$view->image = $image;
		if($image->user_id){
			$user = Model_User::find($image->user_id);
			$view->username = $user->username;
		}
		else{
			$view->username = 'Anon';
		}
		
		if($image->user_id == $this->user_id){
			$controls = View::forge($this->theme.'/includes/controls');
			$controls->alphaID = $image->short_name;
			$view->controls = $controls;
		}
		else{
			$view->controls = '';
		}
		
		//determine image size to display
		$extension_pos = strrpos($image->short_name, '.');
		switch(Input::get('s')){
			case 's':
				$filename = substr($image->short_name, 0, $extension_pos).'_s'.substr($image->short_name, $extension_pos);
				$view->active = 's';
				break;
			case 'b':
				$filename = substr($image->short_name, 0, $extension_pos).'_b'.substr($image->short_name, $extension_pos);
				$view->active = 'b';
				break;
			case 'l':
				$filename = substr($image->short_name, 0, $extension_pos).'_l'.substr($image->short_name, $extension_pos);
				$view->active = 'l';
				break;
			case 'o':
				$filename = $image->short_name;
				$view->active = 'o';
				break;
			default:
				$filename = substr($image->short_name, 0, $extension_pos).'_l'.substr($image->short_name, $extension_pos);
				$view->active = 'l';
				break;
		}
		
		$view->filename = $filename;
		
		return $view;		
	}
	
	public function action_upload(){
		$message = array();
		if(!$this->user_id){
			$message = array(
				'error'=>'Insufficient Permissions'
			);
			return $this->response->body(Format::forge($message)->to_json());
		}
		
		$config = array(
		    'path' => APPPATH.'files',
		    'randomize' => true,
		    'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
		);
		
		if(Input::post('upload')){
			Upload::process($config);
			if(Upload::is_valid()){
				$files = Upload::get_files();
				//var_dump($files);
				foreach($files as $f){
					$prop = array(
						'short_name'=>'',
						'original_name'=>$f['name'],
						'file_type'=>$f['mimetype'],
						'ext'=>$f['extension']
					);
					$short_name = self::store_image($f['file'],$prop);
					$message[] = array(
						'short_name'=>$short_name,
						'original_name'=>$f['name']
					);
					
				}
			}
		}
		return $this->response->body(Format::forge($message)->to_json());
	}
	
	public function action_webget(){
		$message = array();
		if(!$this->user_id){
			$message = array(
				'error'=>'Insufficient Permissions'
			);
			return $this->response->body(Format::forge($message)->to_json());
		}
		if(Input::post('webget')){
			$webget = trim(Input::post('webget'));
			$list = explode("\n",$webget);
			foreach($list as $url){
				$url = trim($url);
				//Validate Image URL 
				$imagesize = getimagesize($url);
				if(is_array($imagesize)){
					//var_dump($imagesize);
					//Get Image
					$prop = array();
					$ch = curl_init($url);
					$tmp = APPPATH.'tmp/'.uniqid();
					$fp = fopen($tmp, 'wb');
					curl_setopt($ch, CURLOPT_FILE, $fp);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_exec($ch);
					curl_close($ch);
					fclose($fp);
					
					$prop['original_name'] = $url;
					$prop['file_type'] = $imagesize['mime'];
					//Validate Image
					self::validate_image($tmp);
					//Store Image
					if($short_name = self::store_image($tmp, $prop)){
						$message[] = array(
							'short_name'=>$short_name,
							'original_name'=>$prop['original_name']
						);
					}
					//Add Meta/Group Details to File Object to Image Model
					//Append JSON message
				}
			}
			//End Loop
		}
		return $this->response->body(Format::forge($message)->to_json());
	}
	
	private function alphaID_to_Image($alpha = ''){
		
		$id = 0;
		if($alpha == ''){
			$alpha = $this->param('id'); //need to replace rest of param with alpha below
		}
		if(strpos($this->param('id'),'_')){
			$splode = explode('_',$this->param('id'));
			
			$id = Model_Image::alphaID($splode[0],true);
		}
		else{
			$id = Model_Image::alphaID($this->param('id'),true);
		}
		$image = Model_Image::find($id);

		
		
		return $image;
	}
	
	public function action_update(){
		$msg = array();
		$image = self::alphaID_to_Image();
		
		if(Input::post('caption')&&$image){
			if($image->user_id == $this->user_id){
				$image->caption = Input::post('caption');		
				if($image->save()){
					$msg = array('success'=>'update was successful');
				}
				else{
					$msg = array('error'=>'Error Saving Image');
					return $msg;
				}
			}
			else{
				$msg = array('error'=>'Insufficient Permissions');
				return $msg;
			}
			
		}

		return Format::forge($msg)->to_json();
	}
	
	//delete an image from an alphaID POST
	public function action_delete(){
		$msg = array();
		//$msg['error'] = '';
		if($this->param('id')){
			$image_id = Model_Image::alphaID($this->param('id'),true);
			$image = Model_Image::find($image_id);
			if($image){
				if($image->user_id == $this->user_id){
					if($image->delete()){
						$msg['success'] = $image->id.' deleted';
					}
					else{
						$msg['error'] = 'Error deleting '.$image->id;
					}
				}
				else{
					$msg['error'] = 'Insufficient Permissions on Object';
				}
			}
			else{
					$msg['error'] = 'Error finding '.$image_id;
			}
			
		}
		
		return Format::forge($msg)->to_json();
		
	}
	
	private function validate_image($loc){
		//Check Allowed Types
		//Check Size
	}
	
	private function store_image($loc,$prop = array()){
		$image = new Model_Image();
		$image->short_name = '';
		$image->original_name = $prop['original_name'];
		$image->user_id = $this->user_id;
		$image->file_type = $prop['file_type'];
		$image->caption = date('m/d/Y');
		$image->location = 'http://img.jalder.com/g';
		$image->save();
		//var_dump($prop['file_type']);
		//die();
		switch($prop['file_type']){
			case 'image/jpeg':
				$ext = '.jpeg';
				break;
			case 'image/png':
				$ext = '.png';
				break;
			case 'image/gif':
				$ext = '.gif';
				break;
			default:
				$ext = '.jpeg';
				break;
		}
		if(isset($prop['ext'])){
			//disregard filetype switch, ext set manually
			$ext = '.'.$prop['ext'];
		}
		$image->short_name = Model_Image::alphaID($image->get_id(),false).strtolower($ext);
		if(rename($loc,APPPATH.'files/'.$image->short_name)){
			$image->save();
			$img = APPPATH.'files/'.$image->short_name;
			$sizer = Image::forge()->load($img);
			if($sizer->sizes()->width>640||$sizer->sizes()->height>480){
				$large_thumb = $sizer->resize(640,480,true);
				$large_thumb->save_pa('','_l');
			}
			else{
				$large_thumb = $sizer;
				$large_thumb->save_pa('','_l');
			}
			$big_square = $sizer->crop_resize(160,160);
			$big_square->save_pa('','_b');
			$small_square = $sizer->crop_resize(70,70);
			$small_square->save_pa('','_s');
		}
		return $image->short_name;
	}
	
	public function action_documentation(){
		$view = View::forge($this->theme.'/documentation');
		$view->header = View::forge($this->theme.'/includes/header',array('active'=>'api'));
		$view->footer = View::forge($this->theme.'/includes/footer');
		return $view->render();		
		
	}
	
}
