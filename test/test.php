<?
require_once 'config.php';
function returnDates($from_timestamp, $to_timestamp) {
    $fromdate = new DateTime();
    $fromdate->setTimestamp($from_timestamp);
    $todate = new DateTime();
    $todate->setTimestamp($to_timestamp);
    return new \DatePeriod(
        $fromdate,
        new \DateInterval('P1D'),
        $todate->modify('+1 day')
    );
}
$to_date = time();
$from_date = time() - 86400*20;
//lấy ra các file cần đọc log
$list_file = array();
$datePeriod = returnDates($from_date,$to_date);
$list_date = array();
foreach($datePeriod as $date) {
    $date = getdate($date->getTimestamp());
    for($i = 0; $i < 24; $i++) {
        $list_file[] = $date['year'] . '/' . $date['mon'] . '/' . $date['mday'] . '/' . $i . '.log';
    }
}
var_dump($list_file);