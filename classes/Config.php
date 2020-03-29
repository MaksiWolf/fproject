<?php

class Config
{
    public static function get($path = NULL)
    {
        if($path){
            $config = $GLOBALS['config'];
            $path = explode('.', $path);

            foreach($path as $item){
                if(isset($config[$item])){
                    $config = $config[$item];
                }
            }
            return $config;
        }
        return false;
    }
}