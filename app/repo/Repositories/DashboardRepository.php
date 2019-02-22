<?php

namespace App\Repositories;
use DB;
//use Carbon\Carbon;
use App\Models\gp;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
//use Illuminate\Database\Eloquent\Model;

class DashboardRepository extends BaseRepository
{
    public function current_mon()
    {
        $current_mon = strtotime("monday this week"); 
        return $current_mon;
    }

    public function aves() 
    {    
        $current_mon = strtotime("monday this week"); 

        $intake_mon_arr = DB::table('gp')->select('intake')->where('day', 'mon')->where('unique_week_id', '<', $current_mon)->get();
        $intake_mon_sum = 0;
        foreach ($intake_mon_arr as $val)
        {
            $intake_mon_sum += $val->intake;
        }
        $intake_mon_ave = $intake_mon_sum / (sizeof($intake_mon_arr) + 0.00001);
        //tue
        $intake_tue_arr = DB::table('gp')->select('intake')->where('day', 'tue')->where('unique_week_id', '<', $current_mon)->get();
        $intake_tue_sum = 0;
        foreach ($intake_tue_arr as $val)
        {
            $intake_tue_sum += $val->intake;
        }
        $intake_tue_ave = $intake_tue_sum / (sizeof($intake_tue_arr) + 0.00001);
        //wed
        $intake_wed_arr = DB::table('gp')->select('intake')->where('day', 'wed')->where('unique_week_id', '<', $current_mon)->get();
        $intake_wed_sum = 0;
        foreach ($intake_wed_arr as $val)
        {
            $intake_wed_sum += $val->intake;
        }
        $intake_wed_ave = $intake_wed_sum / (sizeof($intake_wed_arr) + 0.00001);
        //thur
        $intake_thur_arr = DB::table('gp')->select('intake')->where('day', 'thur')->where('unique_week_id', '<', $current_mon)->get();
        $intake_thur_sum = 0;
        foreach ($intake_thur_arr as $val)
        {
            $intake_thur_sum += $val->intake;
        }
        $intake_thur_ave = $intake_thur_sum / (sizeof($intake_thur_arr) + 0.00001);
        //fri
        $intake_fri_arr = DB::table('gp')->select('intake')->where('day', 'fri')->where('unique_week_id', '<', $current_mon)->get();
        $intake_fri_sum = 0;
        foreach ($intake_fri_arr as $val)
        {
            $intake_fri_sum += $val->intake;
        }
        $intake_fri_ave = $intake_fri_sum / (sizeof($intake_fri_arr) + 0.00001);
        //sat
        $intake_sat_arr = DB::table('gp')->select('intake')->where('day', 'sat')->where('unique_week_id', '<', $current_mon)->get();
        $intake_sat_sum = 0;
        foreach ($intake_sat_arr as $val)
        {
            $intake_sat_sum += $val->intake;
        }
        $intake_sat_ave = $intake_sat_sum / (sizeof($intake_sat_arr) + 0.00001);
        //sun
        $intake_sun_arr = DB::table('gp')->select('intake')->where('day', 'sun')->where('unique_week_id', '<', $current_mon)->get();
        $intake_sun_sum = 0;
        foreach ($intake_sun_arr as $val)
        {
            $intake_sun_sum += $val->intake;
        }
        $intake_sun_ave = $intake_sun_sum / (sizeof($intake_sun_arr) + 0.00001);

        return $intake_mon_arr;
    }

    public function all()
    {
        $record = GP::find(90);
        return $record;
    }
}