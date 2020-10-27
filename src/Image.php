<?php

namespace WeblaborMX\InterventionPlus;

use Intervention\Image\ImageManager;

class Image
{
    public $object;

    public function __construct($object = null)
    {
        if(isset($object)) {
            $this->object = $object;
        } else {
            $this->object = new ImageManager(array('driver' => 'imagick'));    
        }
    }

    /*
     * New functions
     */

    public function resizeWithRatio($width, $height) 
    {
        $this->object->resize($width, $height, function($constraint) {
            $constraint->aspectRatio();
        });
        return $this;
    }

    public function copy() 
    {
        $object = clone $this->object;
        return (new Image($object));
    }

    public function save($file_name, $quality = null) 
    {
        $explode = explode('.', $file_name);
        $format = end($explode);
        $content = $this->object->encode($format, $quality);
        file_put_contents($file_name, $content);
        return true;
    }

    /*
     * Magic functions
     */

    public function __call($name, $arguments)
    {
        $result = call_user_func_array(array($this->object, $name), $arguments);
        if(is_object($result)) {
            $this->object = $result;
            return $this;
        }
        return $result;
    }

    public static function __callStatic($name, $arguments)
    {
        $object = new self;
        return call_user_func_array(array($object, $name), $arguments);
    }
}