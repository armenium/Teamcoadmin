<?php

namespace App\Http\Controllers;


class LoggerController extends Controller{

	public $shopify;

	public function __construct(){
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 * @return \Illuminate\Http\Response
	 */
	public function index(){

		$logs = $custom_logs = $worker_logs = [];

		if(file_exists(storage_path('logs/custom.log'))){
			$custom_logs = file(storage_path('logs/custom.log'));
		}
		if(file_exists(storage_path('logs/worker.log'))){
			//$worker_logs = file(storage_path('logs/worker.log'));
		}

		$logs = $this->format_logs($custom_logs, $worker_logs);

		return view('logger.index', ['logs' => $logs]);
	}

	public function format_logs($custom_logs, $worker_logs){
		$ret = [];
		$j = 0;
		if(!empty($custom_logs)){
			foreach($custom_logs as $k => $log){
				$phrase = '';
				if(strstr($log, 'local.DEBUG:') !== false){
					$phrase = 'local.DEBUG:';
				}elseif(strstr($log, 'production.DEBUG:') !== false){
					$phrase = 'production.DEBUG:';
				}
				
				if(!empty($phrase)){
					$a = explode($phrase, $log);
					$ret[$j]['time']    = trim($a[0]);
					$ret[$j]['content'] = trim($a[1]);
				}
				
				if(!empty($worker_logs)){
					preg_match_all('/(ids(.*))/', $log, $output_array);

					if(!empty($output_array[2])){
						$s   = str_replace(array('(', ')', ' '), '', $output_array[2][0]);
						$ids = explode(',', $s);
						foreach($ids as $id){
							foreach($worker_logs as $wlog){
								if(strstr($wlog, '['.$id.']') !== false){
									$j++;
									$a                    = explode('['.$id.']', $wlog);
									$ret[($j)]['time']    = trim($a[0]);
									$ret[($j)]['content'] = '['.$id.'] '.trim($a[1]);
								}
							}
						}
					}
				}
				$j++;
			}
		}

		return $ret;
	}

}
