<?php
namespace Wpm\Components;

use Intervention\Image\ImageManagerStatic as Image;

class Img
{
	protected $id = null;
	protected $image = null;
	protected $manager = null;
	protected $cacheUrl = null;
	protected $cachePath = null;
	protected $imageName = null;
	protected $mediaFilePath = null;
	
	protected $method = null;
    protected $namePrefix = null;
	protected $width = null;
	protected $height = null;
	protected $quality = 95;
	protected $closure = null;
	protected $upscale = false;
	
	protected $buildFileName;
	protected $buildCachedFileNamePath;
	protected $buildCachedFileNameUrl;


	public function __construct($cachePath, $cacheUrl)
	{
		$this->cachePath = $cachePath;
		$this->cacheUrl = $cacheUrl;
	}


	public function id($id)
	{
		return $this->media($id);
	}


	public function src($src)
	{
		return $this->media($src);
	}
	
	
	public function media($media)
	{
		if(is_numeric($media)) {
			$this->id = $media;
			$media = wp_get_attachment_url($media);
		}
		
		$wpUploadDir = wp_upload_dir();
		
		$this->mediaFilePath = str_replace($wpUploadDir['baseurl'], $wpUploadDir['basedir'], $media);
		
		
		if(!$this->mediaFilePath) return false;
		
		$this->imageName = basename($this->mediaFilePath);
		
		return $this;
	}
	
	
	public function upscale($upscale=true)
	{
		$this->upscale = $upscale;
		return $this;
	}
	
	
	public function quality($quality)
	{
		$this->quality = $quality;
		return $this;
	}
	
	
	public function fit($w=null, $h=null)
	{
		$this->method = 'resize';
        $this->namePrefix = 'fit';
		$this->width = $w;
		$this->height = $h;
        $this->closure = function ($constraint) {
            $constraint->aspectRatio();
			if(! $this->upscale) $constraint->upsize();
		};
		return $this;
	}
	
	
	public function resize($w=null, $h=null)
	{
		$this->method = 'fit';
        $this->namePrefix = 'resize';
		$this->width = $w;
		$this->height = $h;
		$this->closure = function ($constraint) {
            $constraint->aspectRatio();
			if(! $this->upscale) $constraint->upsize();
		};
		return $this;
	}
	
	
	public function get($options=[])
	{
		$this->buildImage();
		return $this->buildImageElement($options);
	}
	
	
	public function url()
	{
		$this->buildImage();
		return $this->buildCachedFileNameUrl;
	}
	
	
	protected function buildImage()
	{
		$this->buildFileName = $this->buildFileName();
		$this->buildCachedFileNamePath = $this->cachePath . '/' . $this->buildFileName;
		$this->buildCachedFileNameUrl = $this->cacheUrl . '/' . $this->buildFileName;
		
		if(!file_exists($this->buildCachedFileNamePath)) {
			$this->image = Image::make($this->mediaFilePath);
			
			$this->image->{$this->method}($this->width, $this->height, $this->closure);
			
			$this->image->save($this->buildCachedFileNamePath, $this->quality);
		}
	}
	
	
	protected function buildFileName()
	{
		$upscale = $this->upscale ? 'upscale' : 'noupscale';
		return $this->namePrefix . '-' . $this->width . 'x' . $this->height . '-' . $upscale . '-' . $this->quality . '-' . $this->imageName;
	}
	
	
	protected function buildImageElement($options)
	{
		if($this->id and empty($options['alt'])) {
			$options['alt'] = get_post_meta($this->id, '_wp_attachment_image_alt', true);
		}
		
		$img = '<img src="' . $this->buildCachedFileNameUrl . '" ';
		foreach($options as $attr => $value) $img.= $attr . '="' . $value . '" ';
		$img.= '/>';
		
		return $img;
	}
	
}
