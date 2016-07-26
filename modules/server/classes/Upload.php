<?php

class Upload {
   public $uploaddir;//设置文件保存目录 注意包含/  
   public $type;//设置允许上传文件的类型
//   public $patch="http://localhost/win_mojavi2/htdocs/files/";//程序所在路径
//   $patch="http://127.0.0.1/cr_downloadphp/upload/files/";//程序所在路径
  
    function __construct($path) {
         $this->uploaddir = $path;
         $this->type = array("jpg","gif","bmp","jpeg","png");
         
         if(!file_exists($this->uploaddir)){
         mkdir($this->uploaddir,0777,true);}
    }
    //获取文件后缀名函数
      function fileext($filename)
    {
        return substr(strrchr($filename, '.'), 1);
    }
   //生成随机文件名函数  
    function random($length)
    {
        $hash = '';
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $max = strlen($chars) - 1;
        mt_srand((double)microtime() * 1000000);
            for($i = 0; $i < $length; $i++)
            {
                $hash .= $chars[mt_rand(0, $max)];
            }
        return $hash;
    }
function upload($file){
   $a=strtolower($this->fileext($file['file']['name']));
   //判断文件类型
   if(!in_array(strtolower($this->fileext($file['file']['name'])),$this->type))
     {
        $text=implode(",",$this->type);
        $msg = "file type is not validate";
     }
   //生成目标文件的文件名  
   else{
    $filename=explode(".",$file['file']['name']);
        do
        {
            $filename[0]=$this->random(10); //设置随机数长度
            $name=implode(".",$filename);
            //$name1=$name.".Mcncc";
            $uploadfile=$this->uploaddir.$name;
        } while(file_exists($uploadfile));

        if (move_uploaded_file($file['file']['tmp_name'],$uploadfile)){
                $msg = array(true,$uploadfile);
        }else{
            $msg = false;
        }
   }
   return $msg;
}

function mutipleUpload($file){
   $a=strtolower($this->fileext($file['file']['name']));
   //判断文件类型
   if(!in_array(strtolower($this->fileext($file['file']['name'])),$this->type))
     {
        $text=implode(",",$this->type);
        $msg = "file type is not validate";
     }
   //生成目标文件的文件名  
   else{
    $filename=explode(".",$file['file']['name']);
        do
        {
            $filename[0]=$this->random(10); //设置随机数长度
            $name=implode(".",$filename);
            //$name1=$name.".Mcncc";
            $uploadfile=$this->uploaddir.$name;
        } while(file_exists($uploadfile));

        if (move_uploaded_file($file['file']['tmp_name'],$uploadfile)){
                $msg = array(true,$uploadfile);
        }else{
            $msg = false;
        }
   }
   return $msg;
}

}