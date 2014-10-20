<?php

class Image extends \Phalcon\Mvc\Model
{
	public function getSource()
	{
		return 'images';
	}

	public function getUrl()
	{
		$baseUrl = 'http://img.clan.vn/'.str_replace('\\', '/', $this->path).$this->name;

		return $baseUrl;
	}
}