<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ApiDocsController extends Controller{

	function apiData(){
        // 获取网站根目录
        $root_dir = $_SERVER['DOCUMENT_ROOT'];
        //锁定目标json文件夹
        $dir = $root_dir.'/api-docs-json/';
        // 打开文件夹
        $json = array();
        if (is_dir($dir)){
            if ($dh = opendir($dir)){
                while (($file = readdir($dh)) !== false){
                    //判断文件名是否以.json结尾
                    $arr = explode(".",$file);
                    if($arr[1] == "json"){
                        //开始读取文件
                        $myfile = fopen($dir.$file, "r") or die("Unable to open file!");
                        //如果文件是api-config.json,用变量$apiConfig保存
                        if($arr[0] == "api-config"){
                            $json['apiConfig'] = json_decode(fread($myfile,filesize($dir.$file)));
                        }else{
                            // 其余文件用apiRest保存
                            $json['apiRest'][$arr[0]] = json_decode(fread($myfile,filesize($dir.$file)));
                        }
                        fclose($myfile);
                    }
                }
            closedir($dh);
            }
        }
		return view('api-docs/index',$json);
	}
}
