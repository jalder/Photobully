<?php 

namespace Piwik; 

class Controller_Piwik{
	
	public $idSite = 0;
	
	private $url = '';
	
	private $method = '';
	
	private $period = 'week';
	private $date = '2012-07-19';
	private $token_auth = '';
	private $downloadUrl = '';
	private $format = 'json';
	
	public function __construct()
	{
		Config::load('piwik','piwik');
		$this->url = Config::get('piwik.url');
		$this->idSite = Config::get('piwik.site_id');
		$this->token_auth = Config::get('piwik.token_auth');
	}	
	
	public function getDownload($downloadUrl){
		
		$this->method = 'Actions.getDownload';
		$this->downloadUrl = $downloadUrl;
		
		$query = $this->url.'?module=API';
		$query .= '&idSite='.$this->idSite;
		$query .= '&period='.$this->period;
		$query .= '&date='.$this->date;
		$query .= '&token_auth='.$this->token_auth;
		$query .= '&downloadUrl='.$this->downloadUrl;
		$query .= '&format='.$this->format;
		$query .= '&method='.$this->method;
		
		$json = file_get_contents($query);
		$data = json_decode($json);
		
		if(isset($data[0])){
			return $data[0];
		}
		else{
			return false;
		}
		
	}
	
	
	
	
	
	
}
