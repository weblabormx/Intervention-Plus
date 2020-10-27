<?php

namespace WeblaborMX\InterventionPlus\Tests;

use PHPUnit\Framework\TestCase;
use WeblaborMX\InterventionPlus\Image;

class BasicTest extends TestCase
{
    /** @test */
    public function NormalUse()
    {
        $image = Image::make('tests/images/picture.jpg')->resize(300, 200);
        $this->assertEquals(300, $image->getWidth());
        $this->assertEquals(200, $image->getHeight());
    }

    /** @test */
    public function resizeWithRatio()
    {
        $image = Image::make('tests/images/picture.jpg')->resizeWithRatio(500, 500);
        $this->assertEquals(500, $image->getWidth());
        $this->assertEquals(333, $image->getHeight());
    }

    /** @test */
    public function copy()
    {
        $image = Image::make('tests/images/picture.jpg');
        $new_image = $image->copy()->resizeWithRatio(500, 500);
        $this->assertEquals(1100, $image->getWidth());
        $this->assertEquals(733, $image->getHeight());

        $this->assertEquals(500, $new_image->getWidth());
        $this->assertEquals(333, $new_image->getHeight());
    }

    /** @test */
    public function addBackgroundToTransparentImage()
    {
        Image::make('tests/images/ball.png')->backgroundColor('#ca262c')->save('tests/results/test.jpg');
        $this->assertTrue(file_exists('tests/results/test.jpg'));
    }

    /** @test */
    public function getBase54()
    {
        $base_64 = Image::make('tests/images/ball.png')->base64();
        $this->assertTrue(is_string($base_64));
    }
}
