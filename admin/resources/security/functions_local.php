<?
//hàm này viết riêng cho local
function get_config_install(&$api_download_url, &$api_get_url, &$admin, &$password, &$domain_server_url)
{
    if (!file_exists('config')) {
        die('Thiếu file config hệ thống! Vui lòng liên hệ nhà cung cấp');
    }
    $config_str = file_get_contents('config');
    $config_array = explode("\n", $config_str);
    $api_download_url = trim($config_array[0]);
    $api_get_url = trim($config_array[1]);
    $admin_str = trim($config_array[2]);
    list($admin, $password) = explode(' ', $admin_str);
    $domain_server_url = trim($config_array[3]);
}
function import_server_config() {
    if (!file_exists('config')) {
        die('Thiếu file config hệ thống! Vui lòng liên hệ nhà cung cấp');
    }
    $config_str = file_get_contents('config');
    $config_array = explode("\n", $config_str);

    $domain_server_url = trim($config_array[3]);
    $synchronize_url = trim($config_array[4]);
    $version_check_url = trim($config_array[5]);
    //lưu 1 vài thông tin config vào database
    $db = new db_execute('TRUNCATE TABLE server_config',1,0);
    $db = new db_execute('INSERT INTO server_config(server_domain,synchronize_url,version_check_url)
                          VALUES("'.encode_combine($domain_server_url).'",
                                 "'.encode_combine($synchronize_url).'",
                                 "'.encode_combine($version_check_url).'")',1,0);
    unset($db);
}
function truncate_database() {
    $list_table = new db_query('SHOW TABLES');
    while($row = mysqli_fetch_assoc($list_table->result)) {
        foreach($row as $k=>$tableName) {
            $db = new db_execute('TRUNCATE TABLE '.$tableName,1,0);
            unset($db);
        }
    }
    unset($list_table);
}
//hàm này viết riêng cho local
function synchronize_data_table($array_data) {
    foreach($array_data as $table_name=>$table_list_record) {
        //kiểm tra xem table name có không
        $check_table = new db_query('SHOW TABLES LIKE "'.$table_name.'"');
        if(mysqli_num_rows($check_table->result) > 0) {
            unset($check_table);
            $db_list_field = new db_query('SHOW FIELDS FROM '. $table_name);
            $array_type_field = array();
            while ($row = mysqli_fetch_assoc($db_list_field->result)) {
                $array_type_field[$row['Field']] = $row['Type'];
            }
            $sql = 'INSERT INTO '.$table_name.' (';
            foreach($table_list_record as $k=>$value) {
                if(!is_array($value)) {
                    $table_list_record = array(0=>$table_list_record);
                    break;
                }
            }
            foreach($table_list_record as $record) {
                foreach($record as $k=>$v) {
                    //neu ton tai field trong csdl thi moi them vao sql
                    if(isset($array_type_field[$k])) {
                        $sql .= $k . ',';
                    }
                }
                $sql = rtrim($sql,',');
                break;
            }
            $sql .= ')';
            $sql_value = ' VALUES';
            foreach ($table_list_record as $record) {
                $sql_value .= '(';
                foreach($record as $k=>$value) {
                    if (isset($array_type_field[$k])) {
                        if (strpos($array_type_field[$k], 'int')) {
                            $sql_value .= intval($value) . ',';
                        } else {
                            $sql_value .= '"' . $value . '",';
                        }
                    }
                }
                $sql_value = rtrim($sql_value,',');
                $sql_value .= '),';
            }
            $sql_value = rtrim($sql_value,',');
            $db_insert = new db_execute($sql . $sql_value,1,0);unset($db_insert);
        }
    }
}
//hàm viết riêng cho local
function read_logs() {
    $array_query = array();
    $db = new db_query('SELECT * FROM synchronize_trigger LIMIT 1');
    $last_sync = mysqli_fetch_assoc($db->result);unset($db);
    if(!$last_sync) {
        $last_sync = 0;
    }else{
        $last_sync = $last_sync['syn_time'];
    }
    $time_string = getdate($last_sync);
    $sync_year = $time_string['year'];
    $sync_mon = $time_string['mon'];
    $sync_mday = $time_string['mday'];
    $sync_hours = $time_string['hours'];
    $link_log_sync = $_SERVER['DOCUMENT_ROOT'] . "/log/sync/";

    if($last_sync == 0) {
        //quét toàn bộ file trong thư mục log
        $list_file = directoryToArray($link_log_sync);
    }else {
        //lấy ra các file cần đọc log
        $list_file = array();
        $from_date = $last_sync;
        $to_date = time();
        $datePeriod = returnDates($from_date,$to_date);
        foreach($datePeriod as $date) {
            $date = getdate($date->getTimestamp());
            for($i = 0; $i < 24; $i++) {
                if(file_exists($link_log_sync . $date['year'] . '/' . $date['mon'] . '/' . $date['mday'] . '/' . $i . '.log')) {
                    $list_file[] = $link_log_sync . $date['year'] . '/' . $date['mon'] . '/' . $date['mday'] . '/' . $i . '.log';
                }
            }
        }
    }

    foreach($list_file as $file_log) {
        if(file_exists($file_log)) {
            $content = file_get_contents($file_log);
            $content = explode(PHP_EOL,$content);
            foreach($content as $line) {
                list($time, $query) = explode('|',$line);
                //$query = decode_combine($query);
                if($last_sync > $time) {
                    continue;
                }
                $array_query[$time][] = $query;
            }
        }
    }
    return $array_query;
}
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
function directoryToArray($directory, $recursive = true, $listDirs = false, $listFiles = true, $exclude = '') {
    $arrayItems = array();
    $skipByExclude = false;
    $handle = opendir($directory);
    if ($handle) {
        while (false !== ($file = readdir($handle))) {
            preg_match("/(^(([\.]){1,2})$|(\.(svn|git|md))|(Thumbs\.db|\.DS_STORE))$/iu", $file, $skip);
            if($exclude){
                preg_match($exclude, $file, $skipByExclude);
            }
            if (!$skip && !$skipByExclude) {
                if (is_dir($directory. DIRECTORY_SEPARATOR . $file)) {
                    if($recursive) {
                        $arrayItems = array_merge($arrayItems, directoryToArray($directory. DIRECTORY_SEPARATOR . $file, $recursive, $listDirs, $listFiles, $exclude));
                    }
                    if($listDirs){
                        $file = $directory . DIRECTORY_SEPARATOR . $file;
                        $arrayItems[] = $file;
                    }
                } else {
                    if($listFiles){
                        $file = $directory . DIRECTORY_SEPARATOR . $file;
                        $arrayItems[] = $file;
                    }
                }
            }
        }
        closedir($handle);
    }
    return $arrayItems;
}

function read_server_config() {
    $db_query = new db_query('SELECT * FROM server_config LIMIT 1');
    $array_return = array();
    $config = mysqli_fetch_assoc($db_query->result);unset($db_query);
    foreach($config as $k=>$v) {
        $array_return[$k] = $v ? decode_combine($v) : '';
    }
    return $array_return;
}
function checkSystemUpdate() {
    $db_query = new db_query('SELECT * FROM system_update_log ORDER BY update_time DESC LIMIT 1');
    $update_info = mysqli_fetch_assoc($db_query->result);unset($db_query);
    if(!$update_info) {
        $update_info['update_time'] = 0;
        $update_info['update_version'] = 0;
    }

}