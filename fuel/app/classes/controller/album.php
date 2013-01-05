<?php 



class Controller_Album extends Controller{
	
	private $theme = 'default';

	private $user_id = 0;
	
	public function before(){
		$user = Auth::get_user_id();
		$this->user_id = $user[1];
	}
	
	public function action_index(){
		$id = $this->param('id');
		
		$album = Model_Album::find($id);
		if($album){
			$view = View::forge($this->theme.'/album');
			$view->header = View::forge($this->theme.'/includes/header',array('active'=>'gallery'));
			$view->footer = View::forge($this->theme.'/includes/footer');
			$view->album = $album;
			$view->username = Model_User::find($album->user_id)->get_username();
			$view->images = $album->images;

			return $view;
		}
		else{
			
		}
	}
	
	public function action_gallery(){
		$id = $this->param('id');
		$view = View::forge($this->theme.'/gallery');
		$album = Model_Album::find($id);
		if($album){		
			$view->header = View::forge($this->theme.'/includes/header',array('active'=>'gallery'));
			$view->footer = View::forge($this->theme.'/includes/footer');
			$view->images = $album->images;
			$view->albums = '';
			$files = array();
			$lightbox = array();
			
			$view->public_albums = Model_Album::public_albums();
			
			foreach($view->images as $i){
				$extension_pos = strrpos($i->short_name, '.');
				$filename = substr($i->short_name, 0, $extension_pos).'_b'.substr($i->short_name, $extension_pos);
				$files[$i->id] = $filename;
				$lightbox[$i->id] = $i->location.'/'.self::append_size($i->short_name,'l');
			}
			
			if($this->user_id){
				$view->sidebar = View::forge('default/account/browse_controls');
			}
			else{
				$view->sidebar = View::forge('default/includes/login.form',array('header'=>"Login",'footer'=>''));
			}
			
			$view->files = $files;	
			$view->active = 'gallery';	
			$view->lightbox = $lightbox;
			return $view;
		}
		
		return 'album not found';
	}
	
	private function append_size($filename,$size){
		
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
	
	public function action_create(){
		$msg = array();
		if($this->user_id && Input::post('album_name')){
			$album = new Model_Album();
			$album->name = Input::post('album_name');
			$album->user_id = $this->user_id;
			$album->description = Input::post('album_description');
			$album->save();
						
			$msg[] = array(
				'id' => $album->id,
				'name' => $album->name
			);
			
		}
		else{
			$msg['error'] = 'Insufficient Permissions and/or POST data.';
		}
		
		return Format::forge($msg)->to_json();
	}
	
	public function action_add(){
		$msg = array();
		if($this->user_id && Input::post('alphaID')){
			$image_id = Model_Image::alphaID(Input::post('alphaID'),true);
			$album_id = $this->param('id');
			if((int)$image_id&&(int)$album_id){
				$result = self::add_image($album_id,$image_id);
			}
			else{
				$msg['error'] = 'missing parameters';
				$result = false;
			}
			
			if($result){
				$msg['success'] = 'success';
			}
			else{
				$msg['error'] = 'Problem adding image to album.';
			}
		}
		else{
			$msg['error'] = 'Insufficient Permissions and/or POST data.';
		}
		return Format::forge($msg)->to_json();
	}
	
	public function action_remove(){
		$msg = array();
		if($album_id=$this->param('id')){
			//or album_id from post?
			$album = Model_Album::find($album_id);
			if($image_id = (int)Input::post('image_id')){
				unset($album->images[$image_id]);
				$album->save();
				$msg['success'] = 'success';
			}
		}
		return Format::forge($msg)->to_json();
	}
	
	public function action_list(){
		$msg = array();
		if($album_id = $this->param('id')){
			$album = Model_Album::find($album_id);
			foreach($album->images as $i){
				$msg['images'][] = $i;
			}
		}
		else{
			$msg['error'] = 'Could not locate album.';
		}
		return Format::forge($msg)->to_json();
	}
	
	public function add_image($album_id, $image_id){
		
		if((int)$album_id&&(int)$image_id){
			$album = Model_Album::find($album_id);
			$image = Model_Image::find($image_id);

			if($image){
				$album->images[$image_id] = Model_Image::find($image_id);
				if($album->save()){
					return true;
				}
				else{
					return false;
				}
			}
		}
		
		return false;
	}
	
	public function action_delete(){
		$album_id = $this->param('id');
		$msg = array();
		$album = Model_Album::find($album_id);
		if($album){
			if($album->user_id == $this->user_id){
				if(Input::post('album_id')==$album->id){
					$album->delete();
					$msg['success'] = 'success';
				}
				else{
					$msg['error'] = 'post data does not match for delete';
				}
			}
			else{
				$msg['error'] = 'insufficient permissions';
			}
		}
		else{
			$msg['error'] = 'invalid album id';
		}
		return Format::forge($msg)->to_json();
	}
	
}