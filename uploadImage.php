<?php
header("Content-Type:application/json;charset=utf-8");
// 允许上传的图片后缀
$uname=$_REQUEST["uname"];
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["upfile"]["name"]);
$extension = end($temp);     // 获取文件后缀名
if ((($_FILES["upfile"]["type"] == "image/gif")
|| ($_FILES["upfile"]["type"] == "image/jpeg")
|| ($_FILES["upfile"]["type"] == "image/jpg")
|| ($_FILES["upfile"]["type"] == "image/pjpeg")
|| ($_FILES["upfile"]["type"] == "image/x-png")
|| ($_FILES["upfile"]["type"] == "image/png"))
&& ($_FILES["upfile"]["size"] < 819200)   // 小于 800kb
&& in_array($extension, $allowedExts)){
    if ($_FILES["upfile"]["error"] > 0){
        echo '{"code":-2,"msg":"文件上传错误"}';
    }else{
        if (file_exists("../upload/".$_FILES["upfile"]["name"])){
            echo '{"code":1,"msg":"文件已经存在"}';
        }else{
      move_uploaded_file($_FILES["upfile"]['tmp_name'],"../upload/".$uname.".".$extension);
            echo '{"code":2,"msg":"文件上传成功"}';
        }
    }
}else{
    echo '{"code":-1,"msg":"非法的文件格式"}';
}
?>
