<?php
class conf {
    /**
     * 获取指定配置项
     */
    public function getConf($name, $conf) {
        if (is_file("../conf/".$name.".php")) {
            $con = include "../conf/".$name.".php";
            if (isset($con[$conf])) {
                return $con[$conf];
            } else {
                return "noconf";
            }
        } else {
            return "nofile";
        }
    }
    /**
     * 获取所有配置
     */
    public function getAllConf($name) {
        $file = "conf/".$name.".php";
        if (is_file($file)) {
            $con = include $file;
            return $con;
        } else {
            return "nofile";
        }
    }
    
}