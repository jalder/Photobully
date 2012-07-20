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
		$view->images = Model_Image::find()->where('privacy', '<', 1)->order_by('created_at','DESC')->limit(16)->get(); //get group of images for the recent View area
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
		$images = Model_Image::find()->where('privacy', '<', 1)->order_by('created_at','DESC')->limit(64)->get();
		$view->images = $images;
		$view->albums = self::get_albums();
		$files = array();
		$lightbox = array();
		foreach($view->images as $i){
			$extension_pos = strrpos($i->short_name, '.');
			$filename = substr($i->short_name, 0, $extension_pos).'_b'.substr($i->short_name, $extension_pos);
			$files[$i->id] = $filename;
			
			$lightbox[$i->id] = $i->location.'/'.self::append_size($i->short_name,'l');
			
		}
		
		if($this->user_id){
			$view->sidebar = View::forge('default/account/browse_controls',array('albums'=>self::get_albums()));
		}
		else{
			$view->sidebar = View::forge('default/includes/login.form',array('header'=>"Login",'footer'=>''));
		}
		
		$view->files = $files;	
		$view->lightbox = $lightbox;
		$view->active = 'gallery';	
		return $view;
	}
	
	private function get_albums(){
		$albums = Model_Album::find()->where('user_id',$this->user_id)->get();
		
		return $albums;
	}
	
	public function action_rotate(){
		$msg = array();
		
		$image = self::alphaID_to_Image();
		$degrees = Input::post('degrees');
		//return APPPATH.'files/'.$image->short_name;
		$img = Image::load(APPPATH.'files/'.$image->short_name.'')->rotate((int)$degrees);
		//var_dump($img);
		//die();
		if($img->save(APPPATH.'files/'.$image->short_name)){
			self::save_thumbs($img);
			$msg['success'] = 'success';
		}
		else{
			$msg['error'] = 'Error rotating image.';
		}
		
		return Format::forge($msg)->to_json();
		
	}
	
	private function save_thumbs($image){
		if($image->sizes()->width>640||$image->sizes()->height>480){
			$image->resize(640,480,true)->save_pa('','_l');
		}
		else{
			$image->save_pa('','_l');
		}
		$image->crop_resize(160,160)->save_pa('','_b');
		$image->crop_resize(70,70)->save_pa('','_s');
		return true;
	}
	
	public function action_crop(){
		$msg = array();
		$image = self::alphaID_to_Image();
		$img = Image::load(APPPATH.'files/'.$image->short_name.'');
		$coords = json_decode(Input::post('coord_percent'));
		if(isset($coords->x)&&$img){
			$img->crop($coords->x.'%',$coords->y.'%',$coords->x2.'%',$coords->y2.'%');
			$img->save(APPPATH.'files/'.$image->short_name.'');
			if(self::save_thumbs($img)){
				$msg['success'] = 'success';
			}
			else{
				$msg['error'] = 'Error saving thumbs';
			}
		}
		else{
			$msg['error'] = 'Unable to decode coordinates and/or image';
		}
		
		return Format::forge($msg)->to_json();
	}
	
	public function action_filter(){
		
		
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
				$response = $this->response;
				$img = Image::load($path);
				
				// Set image cache
				$response->set_header('Cache-Control', 'private, max-age=10800, pre-check=10800 ');
				$response->set_header('Expires', date(DATE_RFC822,strtotime(" 2 day")));
				$response->set_header('Pragma', 'private');
				$response->set_header('Last-Modified',date(DATE_RFC822,filemtime($path)));
				if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
				   header('HTTP/1.1 304 Not Modified');
				   die();
				}			
				$response->send_headers();
				
				//piwik tracking
				require_once APPPATH.'vendor/piwik/piwikTracker.php';
				PiwikTracker::$URL = 'http://piwik.jalder.com/';
				$piwikTracker = new PiwikTracker(11);
				$piwikTracker->setTokenAuth('e51970deb2dc7ae8f1c867fd03973b5e');
				//$piwikTracker->setIp(Input::ip());
				$piwikTracker->doTrackAction($image->location.'/'.$image->short_name,'download');
								
				//are we sending the headers twice here?
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
			$controls->privacy = $image->privacy;
			$view->controls = $controls;
		}
		else{
			$view->controls = '';
		}
		if(Input::get('s')){
			$view->active = Input::get('s');
		}
		else{
			$view->active = 'l';
		}
		
		//piwik tracking
		//this is a sample call to get stats
		//http://piwik.jalder.com/?module=API&idSite=11&period=week&date=2012-07-19&token_auth=e51970deb2dc7ae8f1c867fd03973b5e&downloadUrl=http://img.jalder.com/g/ns.jpeg&format=json&method=Actions.getDownload
		
		require_once APPPATH.'vendor/piwik/piwikTracker.php';
		PiwikTracker::$URL = 'http://piwik.jalder.com/';
		$piwikTracker = new PiwikTracker(11);
		$piwikTracker->setTokenAuth('e51970deb2dc7ae8f1c867fd03973b5e');

		$stats = $piwikTracker->getUrlTrackAction($image->location.'/'.$image->short_name,'download');
		//var_dump($stats);
		$view->filename = self::append_size($image->short_name,Input::get('s'));
		
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
		return Format::forge($message)->to_json();
	}
	
	public function action_webget(){
		$message = array();
		if(!$this->user_id){
			$message = array(
				'error'=>'Insufficient Permissions'
			);
			return Format::forge($message)->to_json();
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
		}
		return Format::forge($message)->to_json();
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
		return Model_Image::find($id);
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
				}
			}
			else{
				$msg = array('error'=>'Insufficient Permissions');
			}
			
		}

		if((Input::post('privacy_level')!==NULL)&&$image){
			if($image->user_id == $this->user_id){
				$image->privacy = (int)Input::post('privacy_level');		
				if($image->save()){
					$msg = array('success'=>'update was successful');
				}
				else{
					$msg = array('error'=>'Error Saving Image');
				}
			}
			else{
				$msg = array('error'=>'Insufficient Permissions');
			}			
		}

		return Format::forge($msg)->to_json();
	}
	
	public function action_list(){
		$images = array();
		
		switch(Input::get('type')){
			case 'all':
				$imageset = Model_Image::find()->where('user_id',$this->user_id)->order_by('created_at','DESC')->get();
				break;
				
			case 'hidden':
				$imageset = Model_Image::find()->where('privacy','>=',1)->where('user_id',$this->user_id)->order_by('created_at','DESC')->get();
				break;
				
			default:
				$imageset = Model_Image::find()->where('privacy','<',1)->order_by('created_at','DESC')->get();
				break;
		}

		$j = 0;
		foreach($imageset as $image){
			$images[$j] = Format::forge($image)->to_array();
			$images[$j]['big_thumb'] = self::append_size($images[$j]['short_name'],'b');
			$j++;
		}
		
		return Format::forge($images)->to_json();
	}
	
	private function append_size($filename,$size=''){
		
		$extension_pos = strrpos($filename, '.');
	
		switch($size){
			case 's':
				$filename = substr($filename, 0, $extension_pos).'_s'.substr($filename, $extension_pos);
				
				break;
			case 'b':
				$filename = substr($filename, 0, $extension_pos).'_b'.substr($filename, $extension_pos);
				
				break;
			case 'l':
				$filename = substr($filename, 0, $extension_pos).'_l'.substr($filename, $extension_pos);
				
				break;
			case 'o':
				$filename = $filename;
				
				break;
			default:
				$filename = substr($filename, 0, $extension_pos).'_l'.substr($filename, $extension_pos);
				
				break;
		}
		
		return $filename;
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
		$image->privacy = 0;
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
