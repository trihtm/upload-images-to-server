<?php

class Folder extends Phalcon\Mvc\Model
{
	public function getSource()
	{
		return 'folder';
	}

	public function initialize()
	{
		$this->hasMany("id", "Image", "folder_id");
	}
}