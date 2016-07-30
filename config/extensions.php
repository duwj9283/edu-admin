<?php
$allowFiles = ['doc', 'docx', 'pdf', 'ppt', 'pptx', 'pps', 'ppsx'];
$allowImage = ['jpg', 'jpeg', 'gif', 'png'];
$allowMusic = ['mp3'];
$allowVideo = ['flv', 'mp4', 'f4v', 'm4v', 'mkv'];
$allowOther = ['rar', 'zip', '7z', 'txt'];

$arr = compact('allowFiles', 'allowImage', 'allowMusic', 'allowVideo', 'allowOther');
$arr['allowExt'] = array_flatten($arr);
return $arr;