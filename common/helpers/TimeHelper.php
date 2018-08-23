<?php

namespace common\helpers;


class TimeHelper
{

    /**
     * 获取本月初和末的时间戳
     *
     * @param $time int
     *
     * @return array
     */
    public static function get_month_start_end_time($time = 0)
    {
        if (empty($time)) {
            $time = time();
        }
        //开始时间
        $start_str = date('Y-m-01', strtotime(date('Y-m-d', $time)));
        $start     = strtotime($start_str);
        //结束时间
        $end = strtotime($start_str . '+1 month -1 day') - 1;
        return [$start, $end];
    }

    /**
     * 获取季度
     *
     * @param int $time
     *
     * @return array
     */
    public static function get_quarter_time($time = 0)
    {
        if (empty($time))
            $t = time();
        else
            $t = $time;

        $times = [];
        $arr   = [1, 4, 7, 10];
        foreach ($arr as $k => $n) {
            $start_date = date("Y-{$n}-1 00:00:00", $t);
            $end_date   = date('Y-m-d H:i:s', strtotime('+3 month', strtotime($start_date)) - 1);

            $times[ $k + 1 ] = [strtotime($start_date), strtotime($end_date)];
        }

        return $times;
    }

    /**
     * 根据时间获取是第几季
     *
     * @param int $time
     *
     * @return int
     */
    public static function get_quarter_index_by_time($time = 0)
    {
        if (empty($time))
            $t = time();
        else
            $t = $time;

        $month = substr(date('Y-m', $t), -2);
        $Q     = ceil($month / 3);
        return $Q;
    }

    /**
     * 获取指定季度时间
     *
     * @param int $time
     *
     * @return array
     */
    public static function get_quarter_one_time($time = 0)
    {
        if (empty($time))
            $t = time();
        else
            $t = $time;

        $times = get_quarter_time($t);
        $index = get_quarter_index_by_time($t);
        return $times[ $index ];
    }

    /**
     * 获取一天的开始时间和结束时间
     *
     * @param $time
     *
     * @return array
     */
    public static function get_day_start_end($time)
    {
        $start_time = mktime(0, 0, 0, date("m", $time), date("d", $time), date("Y", $time));  //当天开始时间
        $end_time   = mktime(23, 59, 59, date("m", $time), date("d", $time), date("Y", $time)); //当天结束时间
        $arr        = [
            'start_time' => $start_time,
            'end_time'   => $end_time
        ];
        return $arr;
    }

    /**
     * * 获取两个日期的时间差
     *
     * @param      $star_date
     * @param      $end_date
     * @param bool $is_string
     *
     * @return string
     */
    public static function diffDate($star_date, $end_date, $is_string = true)
    {
        if ($is_string) {
            $diff_time = strtotime($end_date) - strtotime($star_date);
        } else {
            $diff_time = $end_date - $star_date;
        }
        $res = '';
        if ($diff_time > 0) {
            list($year, $month, $day) = explode('/', date('Y/n/j', $diff_time));
            $year -= 1970;
            if ($year > 0) $res .= ($year . '年');
            $month -= 1;
            if ($month) $res .= ($month . '个月');
            $day -= 1;
            if ($day) $res .= ($day . '天');
        }
        return $res;
    }

    /**
     * 格式化时间
     *
     * @param $time
     *
     * @return false|string
     */
    public static function formateDate($time)
    {
        if ($time > 0) {
            return date('Y/m/d', $time);
        }
        return '';
    }

    /**
     * 获取前一个月的开始和结束时间戳 $result=['start'=>54,'end'=>626]
     *
     * @return array
     */
    public static function last_month()
    {
        $last_month_start = mktime(0, 0, 0, date('m') - 1, 1, date('y'));
        $last_month_end   = mktime(0, 0, 0, date('m'), 1, date('y')) - 1;
        return ['start' => $last_month_start, 'end' => $last_month_end];
    }

    /**
     * 获得昨天的开始和结束时间戳 $result = ['start'=>56465,'end'=>54654]
     *
     * @return array
     */
    public static function last_day()
    {
        $last_day_start = mktime(0, 0, 0, date('m'), date('d') - 1, date('y'));
        $last_day_end   = mktime(23, 59, 59, date('m'), date('d') - 1, date('y'));
        return ['start' => $last_day_start, 'end' => $last_day_end];
    }

    /**
     * 获取开始时间到结束时间的实际年份
     *
     * @param mixed $start 开始时间
     * @param mixed $end   结束时间
     *
     * @return false|int 输入时间错误时返回false，否则返回两个实际相差实际年份
     */
    public static function getAges($start, $end = null)
    {
        if (is_null($end)) {
            $end = time();
        }
        if (is_string($start)) {
            $start = strtotime($start);
        }
        if (is_string($end)) {
            $end = strtotime($end);
        }
        if (empty($start) || empty($end)) {
            return false;
        }

        $seconds = $end - $start;
        if ($seconds < 0) {
            return false;
        }

        list($yS, $mdS) = explode('-', date('Y-md', $start));
        list($yE, $mdE) = explode('-', date('Y-md', $end));
        $age = $yE - $yS;
        if ((int)($mdE) < (int)($mdS)) {
            $age -= 1;
        }

        return $age;
    }
}