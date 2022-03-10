<?php

namespace App\Http\SVG;
use App\Color;
class arrayUtilities
{
    /**
    * This function make info to set a JS functions to edit/change colors
    * @param array $ProductSvgInfo
    */
    public static function jsonData($ProductSvgInfo)
    {
        return $jsonData = [
            'background'       => self::fillArray($ProductSvgInfo,'background',''),
            'colors'           => self::fillArray($ProductSvgInfo,'colors','{}'),
            'linearGradients'  => self::fillArray($ProductSvgInfo,'linearGradients','{}'),
            'colorToGradient'  => self::fillArray($ProductSvgInfo,'colorToGradient','{}'),
        ];
    }
    /**
    * This function order all info of svg stored in database
    *
    * @param array $svgData
    */
	public static function setInfo($svgData)
    {
        $data = [];
        if(isset($svgData['background']) && is_array($svgData['background']))
        {
            foreach($svgData['background'] as $key => $value)
            {
                $data [] = [
                    'type'      => 'background',
                    'class'     => $key,
                    'colorCode' => $value,
                    'colorName' => self::nameColor($value),
                    'hide'      => self::showOrHide($svgData,$key),
                    'sort'      => self::setPosition($svgData,$key)
                ];  
            }
        }
        if(isset($svgData['colors']) && is_array($svgData['colors']))
        {
            foreach ($svgData['colors'] as $key1 => $value1) {
                $data [] = [
                    'type'      => 'colors',
                    'class'     => $key1,
                    'colorCode' => $value1,
                    'colorName' => self::nameColor($value1),
                    'hide'      => self::showOrHide($svgData,$key1),
                    'sort'      => self::setPosition($svgData,$key1)
                ];
            }
        }
        usort($data,function($a,$b){
            $a = $a['sort'];
            $b = $b['sort'];

            if ($a == $b) return 0;
            return ($a < $b) ? -1 : 1;
        });
        return $data;
    }
    /**
    * This function returns the name of Hex code color inside the database
    *
    * @param array $value
    */
    public static function nameColor($value)
    {
        $Colors = Color::all();
        $data = [];
        foreach ($Colors as $key => $color) {
            $data[$color->value_code] = $color->name;
        }
        return (isset($data[$value])?$data[$value]:'Default');
    }
    /**
    * This function set true/false checking the data of SVG
    *
    * @param array $array
    * @param string $key
    */
    public static function showOrHide($array,$key)
    {
        if(isset($array['hide']) && is_array($array['hide']))
        {
            return (in_array($key,$array['hide']))?'1':null;
        }else
        {
            return null;
        }
        
    }
    /**
    * This function return the position of colors 
    *
    * @param array $array
    * @param string $key
    */
    public static function setPosition($array,$key)
    {
        if(isset($array['sort']) && is_array($array['sort']))
        {
             return (isset($array['sort'][$key])?$array['sort'][$key]:'1');
        }else
        {
            return null;
        }
       
    }
    /**
    * This is a simple function to resolve some 
    * params in the info about the data of SVG
    * @param array $array
    * @param string $param
    * @param string $a
    * @return \Illuminate\Http\Response
    */
    public static function fillArray($array,$param,$a)
    {
        return (isset($array[$param])?$array[$param]:$a);
    }
     /**
    * This function set a format to colors
    *
    * @param array $colors
    */
    public static function converToArray($colors)
    {
       $data = [];
        foreach ($colors as $key => $color) {
            $data[$color->value_code] = $color->name;
        }
        return $data;
    } 

    public static function converToArrayKeys($colors)
    {
       $data = [];
        foreach ($colors as $key => $color) {
            $data[] = $color->value_code;
        }
        return $data;
    }
}