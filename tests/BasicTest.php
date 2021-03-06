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
    public function colorizeWhite()
    {
        Image::make('tests/images/ball.png')->colorizeWhite('#ca262c')->save('tests/results/colorize-white.png');
        $this->assertTrue(file_exists('tests/results/colorize-white.png'));
    }

    /** @test */
    public function addBackgroundColor()
    {
        Image::make('tests/images/ball.png')->backgroundColor('#ca262c')->save('tests/results/background.jpg');
        $this->assertTrue(file_exists('tests/results/background.jpg'));
    }

    /** @test */
    public function addForegroundColor()
    {
        Image::make('tests/images/person.png')->foregroundColor('#ca262c')->save('tests/results/foreground.png');
        $this->assertTrue(file_exists('tests/results/foreground.png'));
    }

    /** @test */
    public function getBase54()
    {
        $base_64 = Image::make('tests/images/ball.png')->base64();
        $this->assertTrue(is_string($base_64));
    }

    /** @test */
    public function transparentCoords()
    {
        $base_64 = Image::make('tests/images/person.png')->transparentCoords();
        $this->assertEquals(0, $base_64['x']);
        $this->assertEquals(181, $base_64['y']);
        $this->assertEquals(1113, $base_64['x2']);
        $this->assertEquals(1113, $base_64['y2']);
    }
}
