<?php namespace Own\Image;

use Image as OwnImage;
use Phalcon\Image\Adapter\GD;

class Upload
{
	protected $gd;
	protected $mime_allowed = ['jpeg', 'png', 'gif', 'jpg'];
	protected $maxSize 		= 10000000; // 10mb
	protected $folder_id;

	public function __construct($file, $folder_id)
	{
		$this->folder_id = $folder_id;
		$this->file  = $file;

		$this->checkMime();
		$this->checkSize();

		$this->gd    = new GD($file->getPathname());
	}

	public function checkMime()
	{
		# Kiểm tra extension
    	$file_name = $this->file->getName();
    	$mime 	   = $this->getPathByName($file_name);

    	if(!in_array($mime, $this->mime_allowed))
    	{
    		throw new MIMEException;
    	}
	}

	public function checkSize()
	{
		$size = $this->file->getSize();

		if($size > $this->maxSize)
		{
			throw new MaxSizeException;
		}
	}

	protected function getPathByName($name)
	{
		$explode = explode('.', $name);
		$path    = end($explode);

		return $path;
	}

	public function run()
	{
		$file_name = $this->file->getName();
		$file_encrypt_name = md5($file_name).'.'.$this->getPathByName($file_name);

		# Move file to disk with many size
		$listSize = [
			'Original'
		];

		$baseWidth  = $this->gd->getWidth();
		$baseHeight = $this->gd->getHeight();

		$win = false;

		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		{
		    $win = true;
		}

		foreach($listSize as $size)
		{
			$gd = $this->gd;

			if($win)
			{
				$disk = 'C:/Temp/'.$size.'/';
			}else{
				$disk = '/data/upanh/host/';
			}

			$path = date('Y').'/'.date('m').'/'.date('d').'/';
			$dir  = $disk.$path;

			if(!is_dir($dir))
			{
				mkdir($dir, 0755, true);
			}

			$destination = $dir.$file_encrypt_name;

			switch($size)
			{
				case 'perHalf':
					$gd->resize($baseWidth / 2, $baseHeight / 2);
				break;

				case 'perQuater':
					$gd->resize($baseWidth / 4, $baseHeight / 4);
				break;
			}

			$gd->save($destination);
		}

		# Insert file to DB
		$db = new OwnImage;
		$db->folder_id  = $this->folder_id;
		$db->real_name  = $file_name;
		$db->name 		= $file_encrypt_name;
		$db->mime 		= $this->gd->getMime();
		$db->size 		= $this->file->getSize();
		$db->path 		= $path;
		$db->created_at = date("Y-m-d H:i:s");
		$db->updated_at = date("Y-m-d H:i:s");

		if(!$db->save())
		{
			throw new DBException;
		}

		$this->dbImage = $db;
	}

	public function getDBImage()
	{
		return $this->dbImage;
	}
}