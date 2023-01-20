<?php
header("Access-Control-Allow-Origin:*"); //接受所有访问
$s=$_REQUEST["source"];
$type=$_REQUEST["type"];
$r=explode(",",$_REQUEST["random"]);
if(!$_REQUEST["source"]||!$s)
exit;

if(is_Mobile()&&$_REQUEST["pesearch"])
$q=urlencode($_REQUEST["pesearch"]);
else
$q=urlencode($_REQUEST["search"]);


if(is_numeric($r[0])&&is_numeric($r[1])&&$r[0]<=$r[1]) //判断随机数是否合法
$p=rand($r[0],$r[1]); //随机页数
else
$p=rand(1,100); //随机页数

if($s=="baidu"){  //百度
    $data=GET("https://image.baidu.com/search/acjson?tn=resultjson_com&logid=11741898039855196337&ipn=rj&ct=201326592&is=&fp=result&fr=&word=$q&queryWord=$q&cl=2&lm=-1&ie=utf-8&oe=utf-8&adpicid=&st=&z=&ic=&hd=&latest=&copyright=&s=&se=&tab=&width=&height=&face=&istype=&qc=&nc=1&expermode=&nojc=&isAsync=&pn=$p&rn=1&gsm=96&1673141974733="); //请求百度图片搜索API
    $json=json_decode($data[0],true); //json转数组
    $imgurl=$json["data"][0]["replaceUrl"][0]["ObjURL"]; //获取原站图片链接
    $thumburl=$json["data"][0]["thumbURL"]; //获取百度图片链接
}else if($s=="360"){ //360
    $data=GET("https://image.so.com/j?callback=&q=$q&qtag=&pd=1&pn=1&correct=$q&adstar=0&tab=all&sid=ae937c69147d31ebf776f17fa3c6c716&ras=6&cn=0&gn=0&kn=0&crn=0&bxn=0&cuben=0&pornn=0&manun=48&i=0&cmg=15484592.2876385731182988300.1659968464607.0461&src=360pic_normal&sn=$p&ps=206&pc=47&_=1674215572231");
    $json=json_decode($data[0],true);
    $imgurl=$json["list"][0]["img"];
    $thumburl=$json["list"][0]["_thumb"];
} else if($s=="sogou"){ //搜狗
    $data=GET("https://pic.sogou.com/napi/pc/searchList?mode=1&start=$p&query=$q");
    $json=json_decode($data[0],true);
    $size=rand(0,count($json["data"]["items"]));//搜狗不能控制返回数量
    $imgurl=$json["data"]["items"][$size]["picUrl"];
    $thumburl=$json["data"]["items"][$size]["thumbUrl"];
} else if($s=="shenma"){ //神马
    $data=GET("https://vt.sm.cn/api/pic/list?query=$q&tag=&limit=1&start=$p&databucket=new2&ad=on&hid=hg3oWMDU7jGT1RPlCDIF3lunWqtlDTr3&from=wm850032&fr=&uid=f5d58f6649b62ffa13b9c41db003dada%257C%257C%257C1672064887"); 
    $json=json_decode($data[0],true);
    $imgurl=$json["data"]["hit"]["imgInfo"]["item"][0]["bigPicUrl"];
    $thumburl=$json["data"]["hit"]["imgInfo"]["item"][0]["img"];
}

if($type=="img") {
    $img=GET($imgurl); //请求原站图片
    if($img[1]["http_code"]!=200) //判断原站图片是否有效(判断http状态码是不是200)
    $img=GET($thumburl); //请求搜索引擎保存的图片链接
    header("content-type:".$img[1]["content_type"]); //获取原站响应数据类型
    echo $img[0]; //返回原站响应内容
} else if($type=="thumburl")
header("location:$thumburl");
else if($type=="redirect")
header("location:$imgurl");
else {
    header('Content-Type: application/json');
    echo json_encode(array("url" => $imgurl,"thumburl" => $thumburl,"download" => "https://image.baidu.com/search/down?tn=download&ipn=dwnl&word=download&ie=utf8&fr=result&url=".urlencode($imgurl)."&thumburl=".urlencode($thumburl),"p"=>$p),JSON_UNESCAPED_UNICODE);
} 

function GET($url) {
$headers[]  =  "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9";
$headers[]  =  "Accept-Encoding: gzip, deflate, br";
$headers[]  =  "Accept-Language: zh-CN,zh;q=0.9,zh-HK;q=0.8,zh-TW;q=0.7";
$headers[]  =  "Cache-Control: max-age=0";
$headers[]  =  "Connection: keep-alive";
$headers[]  =  'sec-ch-ua: "Not?A_Brand";v="8", "Chromium";v="108", "Google Chrome";v="108"';
$headers[]  =  "User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36";
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL,$url);
curl_setopt($curl, CURLOPT_HEADER,0);
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
curl_setopt($curl, CURLOPT_ENCODING, '');
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($curl,CURLOPT_NOBODY,0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$data[0] = curl_exec($curl);
$data[1] = curl_getinfo($curl);
curl_close($curl);
    return $data;
}

function is_Mobile()
{
    if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], "wap")) {
        return true;
    } elseif (isset($_SERVER['HTTP_ACCEPT']) && strpos(strtoupper($_SERVER['HTTP_ACCEPT']), "VND.WAP.WML")) {
        return true;
    } elseif (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
        return true;
    } elseif (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i',$_SERVER['HTTP_USER_AGENT'])) {
        return true;
    } else {
        return false;
    }
}

?>