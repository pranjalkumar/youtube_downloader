<?php
$obj= array(
    'http'=>array(
                'method'=>'GET',
                'proxy'=>'tcp://192.168.1.107:3128',
                'request_fulluri'=>true,
                'timeout'=>60,
                'header'=> array(
                    'Proxy-Authorization : Basic '.base64_encode("userid:pass"),
                )
    ),
    'ftp'=>array(
        'method'=>'GET',
        'proxy'=>'ftp://192.168.1.107',
        'request_fulluri'=>true,
        'timeout'=>60,
        'header'=> array(
            'Proxy-Authorization : Basic '.base64_encode("userid:pass"),
        )

)


);
$context=stream_context_create($obj);
//$format='video/mp4';
if(isset($_GET['addr'])&&!empty($_GET['addr'])&&!empty($_GET['format']))
{   $format=$_GET['format'];
//    echo $format;
    $addr=$_GET['addr'];
//    echo $addr;
    $pos=stripos($addr,'=');
//    echo $pos;
    $vid=substr($addr,$pos+1);
//    echo $vid;
//    $formate=$_GET['vformat'];
    $file=file_get_contents("http://youtube.com/get_video_info?video_id=".$vid,false,$context);
//    echo $file;

    parse_str($file,$info);
 //   foreach ($info as $x)
   // {
      //  echo $x .'\n';
 //  }
    $title=$info['title'];
//    echo $title;
    $streams = $info['url_encoded_fmt_stream_map'];
//    echo $streams;
    $stream=explode(',',$streams);
//    foreach ($stream as $x)
//    {echo $x;}

    foreach ($stream as $item) {
        parse_str($item,$data);
//        foreach ($data as $x)
//       {echo $x.'<br>';}
//        echo $data['type'];
//        echo $data['url'];
//        parse_str($data['url'],$new);
//        foreach ($new as $item) {
//            echo $item;
//        }

        if(stripos($data['type'],$format)!== false)
        {
           $source=fopen($data['url'],'r',false,$context);
            $newfile=fopen($title.'.'.substr($format,6),'w');
            stream_copy_to_stream($source,$newfile);
            fclose($source);
            fclose($newfile);
    }

    }

}
else
{
    echo 'please fill the details first';
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Youtube downloader</title>
    <meta charset="UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div id="form">
    <form action="index.php" method="get">
    <input type="text" name="addr" id="addr">
        <input type="radio" name="format" value="video/mp4" >video/mp4
        <input type="radio" name="format" value="video/mp4a" >video/mp4a
        <input type="radio" name="format" value="video/mkv" >video/mkv
        <input type="radio" name="format" value="video/3gp" >video/3gp
    <button type="submit">Submit</button>
</form>
</div>
</body>
</html>