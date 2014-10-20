<?php

class GuestController extends \Phalcon\Mvc\Controller
{
	public function route404Action()
	{

	}

	public function defaultAction($folder_id = 1)
	{
		$folder  = Folder::findFirst($folder_id);

		if(!$folder)
		{
			return $this->dispatcher->forward(['controller' => 'Guest', 'action' => 'route404']);
		}

		if($this->request->isPost() && $this->request->hasFiles())
		{
			// Print the real file names and sizes
            foreach ($this->request->getUploadedFiles() as $file)
            {
            	try{
	            	$image = new Own\Image\Upload($file, $folder_id);
	            	$image->run();

	            	return $this->response->redirect('guest/show/'.$image->getDBImage()->id);
            	}catch(\Own\Image\MIMEException $e){
            		$this->flash->error('Định dạng file không phải là ảnh.');
            	}catch(\Own\Image\MaxSizeException $e){
            		$this->flash->error('Kích thước file quá nặng.');
            	}catch(\Own\Image\DBException $e){
            		$this->flash->error('Có lỗi xảy ra với DB.');
            	}catch(Exception $e){
            		$this->flash->error('Có lỗi xảy ra.');
            	}
            }
		}

		$folders = Folder::find();

		$this->view->folder 	= $folder;
		$this->view->folders 	= $folders;
		$this->view->folder_id 	= $folder_id;
		$this->view->images 	= $folder->getImage();

		$this->view->module    	= 'upload';
	}

	public function showAction($image_id)
	{
		$image = Image::findFirst($image_id);

		if(!$image)
		{
			return $this->dispatcher->forward(['controller' => 'Guest', 'action' => 'route404']);
		}

		$this->view->image 	= $image;
		$this->view->module = 'show';
	}
}