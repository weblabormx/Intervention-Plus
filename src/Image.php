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

    public function foregroundColor($color) 
    {
        $this->contrast2(-100);
        $this->object->colorize(100, 100, 100);
        $this->colorizeWhite($color);
        return $this;
    }

    public function colorizeWhite($color) 
    {
        $tmp_file="testimage".rand().".png";
        file_put_contents($tmp_file, $this->get());

        list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
        $rgb = array($r,$g,$b);
        $rgb = array(255-$rgb[0],255-$rgb[1],255-$rgb[2]);

        $im = imagecreatefrompng($tmp_file);

        imagefilter($im, IMG_FILTER_NEGATE); 
        imagefilter($im, IMG_FILTER_COLORIZE, $rgb[0], $rgb[1], $rgb[2]); 
        imagefilter($im, IMG_FILTER_NEGATE); 

        imagealphablending( $im, false );
        imagesavealpha( $im, true );
        imagepng($im, $tmp_file);
        imagedestroy($im);

        $content = file_get_contents($tmp_file);
        unlink($tmp_file);

        $this->object = Intervention::make($content);
        return $this;
    }

    public function contrast2($value) 
    {
        $tmp_file="testimage".rand().".png";
        file_put_contents($tmp_file, $this->get());

        $im = imagecreatefrompng($tmp_file);
        imagefilter($im, IMG_FILTER_CONTRAST, $value); 
        imagealphablending( $im, false );
        imagesavealpha( $im, true );
        imagepng($im, $tmp_file);
        imagedestroy($im);

        $content = file_get_contents($tmp_file);
        unlink($tmp_file);

        $this->object = Intervention::make($content);
        return $this;
    }

    public function get($format = null, $quality = 100) 
    {
        if(is_null($format)) {
            $format = $this->object->extension;
        }
        return (string) $this->object->encode($format, $quality);
    }

    public function base64($format = null, $quality = 100) 
    {
        return base64_encode($this->get($format, $quality));
    }

    public function path() 
    {
        return trim($this->object->dirname.'/'.$this->object->basename, '/');
    }

    public function transparentCoords()
    {
        $tmp_file="testimage".rand().".png";
        file_put_contents($tmp_file, $this->get());

        $img = imagecreatefrompng($tmp_file);

        unlink($tmp_file);
        
        $width = imagesx($img);
        $height = imagesy($img);

        $aryTest = array(-1,-1,-1,-1);

        // Height for
        $left = [];
        for ($y = 0; $y < $height; $y++) { 
            // Width for
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($img, $x, $y) >> 24;
                if ($color < 64) {
                    $left[$y] = $x;
                    break;
                }
            }
        }
        $left = collect($left)->min();
        
        // Height for
        $right = [];
        for ($y = 0; $y < $height; $y++) { 
            // Width for
            for ($x = $width-1; $x > 0; $x--) {
                $color = imagecolorat($img, $x, $y) >> 24;
                if ($color < 64) {
                    $right[$y] = $x;
                    break;
                }
            }
        }
        $right = collect($right)->max();

        // Width for
        $top = [];
        for ($x = 0; $x < $width; $x++) { 
            // Height for
            for ($y = 0; $y < $height; $y++) {
                $color = imagecolorat($img, $x, $y) >> 24;
                if ($color < 64) {
                    $top[$x] = $y;
                    break;
                }
            }
        }
        $top = collect($top)->min();

        // Width for
        $bottom = [];
        for ($x = 0; $x < $width; $x++) { 
            // Height for
            for ($y = $height-1; $y > 0; $y--) {
                $color = imagecolorat($img, $x, $y) >> 24;
                if ($color < 64) {
                    $bottom[$x] = $y;
                    break;
                }
            }
        }
        $bottom = collect($bottom)->max();
        
        imagedestroy($img);
        return[
            'x' => $left,
            'x2' => $right,
            'y' => $top,
            'y2' => $bottom
        ];
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