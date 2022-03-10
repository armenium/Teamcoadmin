<?php

namespace App\Http\SVG;

use App\Http\SVG\lotColor; 

class LotSVGHelper
{
    public static function getGradients(LotColor $colorFrom, LotColor $colorTo)
    {
        $gradients = array();
        for ($i = 0; $i < 256; $i++) {
            $gradients[$i][] = round($colorFrom->r + ($colorTo->r - $colorFrom->r) * $i / 255);//red
            $gradients[$i][] = round($colorFrom->g + ($colorTo->g - $colorFrom->g) * $i / 255);//green
            $gradients[$i][] = round($colorFrom->b + ($colorTo->b - $colorFrom->b) * $i / 255);//blue
        }

        return $gradients;
    }

    public static function getIndexOfNearestColor(LotColor $color, $gradients)
    {
        $colorArray = $color->toArray();
        $index = 0;
        $minSum = 200000; // > 3*(pow(255,2)
        foreach ($gradients as $i => $gradient) {
            if ($gradient == $colorArray) {
                return $i;
            }
            $sum = pow(($gradient[0] - $colorArray[0]), 2) + pow(($gradient[1] - $colorArray[1]), 2)
                + pow(($gradient[2] - $colorArray[2]), 2);
            if ($sum < $minSum) {
                $minSum = $sum;
                $index = $i;
            }
        }

        return $index;
    }

    public static function generateSVGCss($result)
    {
        $style = '';//'<![CDATA['."\n";
        if(isset($result['background'])){
            foreach($result['background'] as $className => $color){
                $style.= '.'.$className.'{'.'fill:'.$color.';'.'}'."\n";
            }
        }
        if(isset($result['colors'])){
            foreach($result['colors'] as $className=>$color){
                $style.= '.'.$className.'{'.'fill:'.$color.';'.'}'."\n";
            }
        }
        //$style .= ']]>';
        return $style;
    }

    public static function changeGradientColor($gradient,$color,$idxRGB)
    {
        foreach($gradient as &$gradData){
            if($gradData['idxRGB'] == $idxRGB){
                $gradData['color'] = $color;
            }
        }
        return $gradient;
    }

    public static function convertRgbGradientsToHex($gradients)
    {
        foreach($gradients as $idxRGB => $colorsRGB){
            $colorRGB = new LotColor($colorsRGB[0],$colorsRGB[1],$colorsRGB[2]);
            $gradients[$idxRGB]= $colorRGB->rgb2hex();
        }
        return $gradients;
    }

    public static function getGradientsInHex(LotColor $colorFrom, LotColor $colorTo)
    {
        $gradients = self::convertRgbGradientsToHex(self::getGradients($colorFrom,$colorTo));
        return $gradients;
    }

    public static function updateGradientsColors($gradient, $newGradient)
    {
        foreach($gradient as &$gradientData)
        {
            $gradientData['color'] = $newGradient[$gradientData['idxRGB']];
        }
        return $gradient;
    }

    public static function changeSvgClass($svgData,$className,$color)
    {
        if(isset($svgData['background'],$svgData['background'][$className])){
            $svgData['background'][$className] = $color;
        }
        if(isset($svgData['colors'],$svgData['colors'][$className])){
            $svgData['colors'][$className] = $color;
        }
        if(isset($svgData['colorToGradient'],$svgData['colorToGradient'][$className])){
            foreach($svgData['colorToGradient'][$className] as $gradient){
                $svgData['linearGradients'][$gradient['gradientClass']] =
                    self::changeGradientColor($svgData['linearGradients'][$gradient['gradientClass']],
                        $color,$gradient['point']);

                reset($svgData['linearGradients'][$gradient['gradientClass']]);
                $tmpColor = current($svgData['linearGradients'][$gradient['gradientClass']]);//PHP 5.4 will get this simplest))
                $startColor = new LotColor();
                $startColor->setFromHex($tmpColor['color']);

                $tmpColor = end($svgData['linearGradients'][$gradient['gradientClass']]);
                $endColor = new LotColor();
                $endColor->setFromHex($tmpColor['color']);

                $gradients = self::getGradientsInHex($startColor, $endColor);
                $svgData['linearGradients'][$gradient['gradientClass']] =
                    self::updateGradientsColors($svgData['linearGradients'][$gradient['gradientClass']],$gradients);

            }
        }

        return $svgData;
    }

}
