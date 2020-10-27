<?php

namespace WeblaborMX\InterventionPlus;

use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic as Intervention;

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

    public function backgroundColor($color) 
    {
        $object = $this->copy();
        $insert_object = (string) $object->object->encode($object->object->extension);
        $alpha = Intervention::canvas($this->object->width(), $this->object->height(), $color);
        $alpha->insert($insert_object);
        $this->object = $alpha;
        return $this;
    }

    public function base64($format = null, $quality = 100) 
    {
        if(is_null($format)) {
            $format = $this->object->extension;
        }
        $result = (string) $this->object->encode($format, $quality);
        return base64_encode($result);
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