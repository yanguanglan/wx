<?php 

//print_r(RSS::Parse('http://news.163.com/special/00011K6L/rss_newstop.xml'));
//echo Lunar::today();
$rp = base_convert (substr(md5(md5('郭山')),26),36,10);

$rp = (2*($rp%100))%100;
echo $rp;
//echo strval($rp);

$keyword = '火车北京到上海';
$keyword = str_replace('火车', '', $keyword);
$keyword = explode('到', $keyword);
if(count($keyword)!=2){
	die('123');
	return;
}

$res = HttpClient::quickGet("http://www.twototwo.cn/train/Service.aspx?format=json&action=QueryTrainScheduleByTwoStation&key=5da453b2-b154-4ef1-8f36-806ee58580f6&startStation=" . urlencode($keyword[0]) . "&arriveStation=" . urlencode($keyword[1]) . "&startDate=" . date('Y') . "&ignoreStartDate=0&like=1&more=0",3);
//die($res);
$str = '';
if ($res) {
	$data = json_decode($res);
	$main = $data->Response->Main->Item;
	if (count($main) > 10) {
		$conunt = 10;
	} else {
		$conunt = count($main);
	}
	for ($i = 0; $i < $conunt; $i++) {
		$str .= "\n【车次】" . $main[$i]->CheCiMingCheng . "\n【类型】" . $main[$i]->CheXingMingCheng . "\n【发车时间】:　" . $main[$i]->FaShi . "\n【耗时】" . $main[$i]->LiShi . ' 小时';
		$str .= "\n----------------------";
	}
} else {
	$str = '没有找到 ' . $keyword[0] . ' 至 ' . $keyword[1] . ' 的列车';
}
die($str);
return;
die();