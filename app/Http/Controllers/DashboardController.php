<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $current_mon = strtotime("monday this week"); 
        //next week
        if (isset($_GET['input_sun']))
        {
            //$the_mon = $_GET['input_mon'];
            $mon_unix = $_GET['input_mon_hidden'];
            $the_mon = date('d-m-y', $mon_unix);

            //$the_sun = $_GET['input_sun'];
            $sun_unix = $_GET['input_sun_hidden'];
            $the_sun = date('d-m-y', $sun_unix);

        }
        else
        {
            $mon_unix = strtotime("monday this week"); //FIX AS ON MONDAY IT GOES TO PREV
            $the_mon = date('d-m-y', $mon_unix);

            $sun_unix = strtotime("sunday this week");
            $the_sun = date('d-m-y', $sun_unix);
        }
        //previous week
                    //dd($_GET); //necessary to complete?

        $uwid = intval($mon_unix);

        //values
        $days = ['mon', 'tue', 'wed', 'thur', 'fri', 'sat', 'sun'];

        $values = [
            'mon' => ['dsp' => [],'itk' => [],],
            'tue' => ['dsp' => [],'itk' => [],],
            'wed' => ['dsp' => [],'itk' => [],],
            'thur' => ['dsp' => [],'itk' => [],],
            'fri' => ['dsp' => [],'itk' => [],],
            'sat' => ['dsp' => [],'itk' => [],],
            'sun' => ['dsp' => [],'itk' => [],],
        ];

        foreach ($days as $day) // looping through each day
        
        {
            
            $row = DB::table('gp')->select('daily_stock_purchase', 'intake')->where('day', $day)->where('unique_week_id', $uwid)->get();
            $row_size = sizeof(array($row)[0]);

            if ($row_size !== 0) 
            {
                // db values, return them
                $dsp_sel = DB::table('gp')->select('daily_stock_purchase')->where('day', $day)->where('unique_week_id', $uwid)->limit(1)->get();
                $itk_sel = DB::table('gp')->select('intake')->where('day', $day)->where('unique_week_id', $uwid)->limit(1)->get();
                //array_push($values[$day]['dsp'], 'hello');
                array_push($values[$day]['dsp'], $dsp_sel[0]->daily_stock_purchase);
                array_push($values[$day]['itk'], $itk_sel[0]->intake);
            }
            else
            {
                array_push($values[$day]['dsp'], 0);
                array_push($values[$day]['itk'], 0);
            }
        }

        $dsp_mon = $values['mon']['dsp'][0];
        $dsp_tue = $values['tue']['dsp'][0];
        $dsp_wed = $values['wed']['dsp'][0];
        $dsp_thur = $values['thur']['dsp'][0];
        $dsp_fri = $values['fri']['dsp'][0];
        $dsp_sat = $values['sat']['dsp'][0];
        $dsp_sun = $values['sun']['dsp'][0];

        $itk_mon = $values['mon']['itk'][0];
        $itk_tue = $values['tue']['itk'][0];
        $itk_wed = $values['wed']['itk'][0];
        $itk_thur = $values['thur']['itk'][0];
        $itk_fri = $values['fri']['itk'][0];
        $itk_sat = $values['sat']['itk'][0];
        $itk_sun = $values['sun']['itk'][0];

        //calcing averages
        //mon
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

        return view('dashboard', compact(
            'dsp_mon', 'itk_mon', 
            'dsp_tue', 'itk_tue',
            'dsp_wed', 'itk_wed',
            'dsp_thur', 'itk_thur',
            'dsp_fri', 'itk_fri',
            'dsp_sat', 'itk_sat',
            'dsp_sun', 'itk_sun',
            'the_mon', 'the_sun',
            'mon_unix', 'sun_unix',
            'current_mon', 'intake_mon_ave',
            'intake_tue_ave', 'intake_wed_ave',
            'intake_thur_ave', 'intake_fri_ave',
            'intake_sat_ave', 'intake_sun_ave'
        ));
    }


    public function update()
    {
        $current_mon = strtotime("monday this week"); 
     
        if (isset($_GET['input_sun_hidden_update']))
        {
        //fetch from week input
        $mon_unix = $_GET['input_mon_hidden_update'];
        $the_mon = date('d-m-y', $mon_unix);

        $sun_unix = $_GET['input_sun_hidden_update'];
        $the_sun = date('d-m-y', $sun_unix);
        }
        else

        {
        //current week
        $mon_unix = strtotime("monday this week");
        $the_mon = date('d-m-y', $mon_unix);

        $sun_unix = strtotime("sunday this week");
        $the_sun = date('d-m-y', $sun_unix);
        }

        $uwid = intval($mon_unix);

        $days = ['mon', 'tue', 'wed', 'thur', 'fri', 'sat', 'sun'];

        $values = [
            'mon' => ['dsp' => [],'itk' => [],],
            'tue' => ['dsp' => [],'itk' => [],],
            'wed' => ['dsp' => [],'itk' => [],],
            'thur' => ['dsp' => [],'itk' => [],],
            'fri' => ['dsp' => [],'itk' => [],],
            'sat' => ['dsp' => [],'itk' => [],],
            'sun' => ['dsp' => [],'itk' => [],],
        ];

        foreach ($days as $day) 
        
        {

        switch ($day) 
        {
            case "mon":
                $date_to_log = date('y-m-d', $uwid);
                break; 
            case "tue":
                $date_to_log = date('y-m-d', $uwid + 86400);
                break; 
            case "wed":
                $date_to_log = date('y-m-d', $uwid + (86400 * 2));
                break; 
            case "thur":
                $date_to_log = date('y-m-d', $uwid + (86400 * 3));
                break; 
            case "fri":
                $date_to_log = date('y-m-d', $uwid + (86400 * 4));
                break; 
            case "sat":
                $date_to_log = date('y-m-d', $uwid + (86400 * 5));
                break; 
            case "sun":
                $date_to_log = date('y-m-d', $uwid + (86400 * 6));
                break; 
        }

            //updating uwid (dont update uwid)
            // DB::table('gp')->where('day', $day)->update(['unique_week_id' => $uwid]); //not wanted

            $row = DB::table('gp')->select('daily_stock_purchase', 'intake')->where('day', $day)->where('unique_week_id', $uwid)->get();
            $row_size = sizeof(array($row)[0]);

            //dsp & itk outputting values to table
            if ($row_size == 0) //checking if there is value in db
            {
                //set variable
                $dsp = 0;
                $itk = 0;
            }
            else 
            {
                //get value from db
                $dsp_sel = DB::table('gp')->select('daily_stock_purchase')->where('day', $day)->where('unique_week_id', $uwid)->limit(1)->get();
                $itk_sel = DB::table('gp')->select('intake')->where('day', $day)->where('unique_week_id', $uwid)->limit(1)->get();

                //set variable
                $dsp = $dsp_sel[0]->daily_stock_purchase;
                $itk = $itk_sel[0]->intake;
            }

            //dsp updating / logging
            if ($_GET['dsp_'.$day] !== "0") // checking if there is GET form data
            
            {
                //update variable
                $dsp = $_GET['dsp_'.$day];

                //create a date for it to log so then it can be ordered
                $row = DB::table('gp')->select('daily_stock_purchase', 'intake')->where('day', $day)->where('unique_week_id', $uwid)->get();
                $row_size = sizeof(array($row)[0]);
                if ($row_size == 0) //checking if record already there
                {
                    //insert into db as new record
                    DB::table('gp')->insert(['daily_stock_purchase' => $dsp, 'day' => $day, 'unique_week_id' => $uwid, 'date' => $date_to_log]);

                } 
                else 
                {                    
                    //update db
                    DB::table('gp')->where('day', $day)->where('unique_week_id', $uwid)->update(['daily_stock_purchase' => $dsp]);
                }    
            }
            

            //itk updating / logging
            if ($_GET['itk_'.$day] !== "0") 
            {
                $itk = $_GET['itk_'.$day];
                $row = DB::table('gp')->select('daily_stock_purchase', 'intake')->where('day', $day)->where('unique_week_id', $uwid)->get();
                $row_size = sizeof(array($row)[0]);
                if ($row_size == 0)
                {

                    //insert into db as new record
                    DB::table('gp')->insert(['intake' => $itk, 'day' => $day, 'unique_week_id' => $uwid, 'date' => $date_to_log]);

                } 
                else 
                {                    
                    //update db
                    DB::table('gp')->where('day', $day)->where('unique_week_id', $uwid)->update(['intake' => $itk]);
                }    
            }

                array_push($values[$day]['dsp'], $dsp);
                array_push($values[$day]['itk'], $itk);
        }

        $dsp_mon = $values['mon']['dsp'][0];
        $dsp_tue = $values['tue']['dsp'][0];
        $dsp_wed = $values['wed']['dsp'][0];
        $dsp_thur = $values['thur']['dsp'][0];
        $dsp_fri = $values['fri']['dsp'][0];
        $dsp_sat = $values['sat']['dsp'][0];
        $dsp_sun = $values['sun']['dsp'][0];

        $itk_mon = $values['mon']['itk'][0];
        $itk_tue = $values['tue']['itk'][0];
        $itk_wed = $values['wed']['itk'][0];
        $itk_thur = $values['thur']['itk'][0];
        $itk_fri = $values['fri']['itk'][0];
        $itk_sat = $values['sat']['itk'][0];
        $itk_sun = $values['sun']['itk'][0];

        return view('dashboard', compact(
            'dsp_mon', 'itk_mon', 
            'dsp_tue', 'itk_tue',
            'dsp_wed', 'itk_wed',
            'dsp_thur', 'itk_thur',
            'dsp_fri', 'itk_fri',
            'dsp_sat', 'itk_sat',
            'dsp_sun', 'itk_sun',
            'the_mon', 'the_sun',
            'mon_unix', 'sun_unix',
            'current_mon'
        ));

    }

}