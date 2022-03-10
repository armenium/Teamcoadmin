<?php

namespace App\Http\SVG;

class LotColor
{
    public $r = 0;
    public $g = 0;
    public $b = 0;

    public function __construct($r = 0, $g = 0, $b = 0)
    {
        $this->setR($r)
             ->setG($g)
             ->setB($b);

        return $this;
    }

    public function setR($r)
    {
        $this->r = $this->checkColor($r);
        return $this;
    }

    public function setG($g)
    {
        $this->g = $this->checkColor($g);
        return $this;
    }

    public function setB($b)
    {
        $this->b = $this->checkColor($b);
        return $this;
    }

    protected function checkColor($color)
    {
        if ($color < 0) {
            $color = 0;
        } elseif ($color > 255) {
            $color = 255;
        }

        return round($color);
    }

    public function hex2rgb($color)
    {
        if ($color[0] == '#')
            $color = substr($color, 1);

        if (strlen($color) == 6)
            list($r, $g, $b) = array($color[0] . $color[1],
                $color[2] . $color[3],
                $color[4] . $color[5]);
        elseif (strlen($color) == 3)
            list($r, $g, $b) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]); else
            return false;

        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);

        return array($r, $g, $b);
    }

    public function setFromHex($color)
    {
        $colors = $this->hex2rgb($color);
        $this->setR($colors[0])
            ->setG($colors[1])
            ->setB($colors[2]);
    }

    public function rgb2hex()
    {
        $r = dechex($this->r);
        $g = dechex($this->g);
        $b = dechex($this->b);

        $color = (strlen($r) < 2?'0':'').$r;
        $color .= (strlen($g) < 2?'0':'').$g;
        $color .= (strlen($b) < 2?'0':'').$b;
        return mb_strtoupper('#'.$color);
    }

    public function toArray()
    {
        return array($this->r,$this->g,$this->b);
    }

}
