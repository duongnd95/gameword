<?php

namespace App;

use Carbon\Carbon;

class Utils
{
	public static function generateDateRange(Carbon $start_date, Carbon $end_date) {
		$dates = [];
		for($date = $start_date; $date->lte($end_date); $date->addDay()) {
			$dates[] = $date->format('Y-m-d');
		}
		return $dates;
	}

	public static function getMonth(){
	    return [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
    }

	public static function getYear($current_year = null){
        $arr = [];
        if (empty($current_year)){//năm hiện tại
            $current_year = date('Y');
        }
        for ($i = 0; $i <= 10; $i++){
            $arr[] = 2018 + $i;
            if (in_array($current_year, $arr)) break;
        }
        return $arr;
    }
}
