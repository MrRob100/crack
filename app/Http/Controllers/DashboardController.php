<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\DashboardRepository;

class DashboardController extends Controller
{
    protected $repository;

    /**
     * __construct.
     *
     * @param $repository
     */
    public function __construct(Request $request, DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(Request $request, DashboardRepository $repository)
    {
        
        $current_mon = $repository->current_mon();

        //week select buttons (index only)
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
            
            $row = DB::table('gps')->select('daily_stock_purchase', 'intake')->where('day', $day)->where('unique_week_id', $uwid)->get();
            $row_size = sizeof(array($row)[0]);

            if ($row_size !== 0) 
            {
                // db values, return them
                $dsp_sel = DB::table('gps')->select('daily_stock_purchase')->where('day', $day)->where('unique_week_id', $uwid)->limit(1)->get();
                $itk_sel = DB::table('gps')->select('intake')->where('day', $day)->where('unique_week_id', $uwid)->limit(1)->get();
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
        $itks = $repository->itk_aves();
        $itk_mon_ave = $itks[0];
        $itk_tue_ave = $itks[1];
        $itk_wed_ave = $itks[2];
        $itk_thur_ave = $itks[3];
        $itk_fri_ave = $itks[4];
        $itk_sat_ave = $itks[5];
        $itk_sun_ave = $itks[6];

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
            'current_mon', 'itk_mon_ave',
            'itk_tue_ave', 'itk_wed_ave',
            'itk_thur_ave', 'itk_fri_ave',
            'itk_sat_ave', 'itk_sun_ave'
        ));
    }

    public function update(Request $request, DashboardRepository $repository)
    {

        $current_mon = $repository->current_mon();
     
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

            $row = DB::table('gps')->select('daily_stock_purchase', 'intake')->where('day', $day)->where('unique_week_id', $uwid)->get();
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
                $dsp_sel = DB::table('gps')->select('daily_stock_purchase')->where('day', $day)->where('unique_week_id', $uwid)->limit(1)->get();
                $itk_sel = DB::table('gps')->select('intake')->where('day', $day)->where('unique_week_id', $uwid)->limit(1)->get();

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
                $row = DB::table('gps')->select('daily_stock_purchase', 'intake')->where('day', $day)->where('unique_week_id', $uwid)->get();
                $row_size = sizeof(array($row)[0]);
                if ($row_size == 0) //checking if record already there
                {
                    //insert into db as new record
                    DB::table('gps')->insert(['daily_stock_purchase' => $dsp, 'day' => $day, 'unique_week_id' => $uwid, 'date' => $date_to_log]);

                } 
                else 
                {                    
                    //update db
                    DB::table('gps')->where('day', $day)->where('unique_week_id', $uwid)->update(['daily_stock_purchase' => $dsp]);
                    //dd($uwid, $dsp);
                }    
            }
            

            //itk updating / logging
            if ($_GET['itk_'.$day] !== "0") 
            {
                $itk = $_GET['itk_'.$day];
                $row = DB::table('gps')->select('daily_stock_purchase', 'intake')->where('day', $day)->where('unique_week_id', $uwid)->get();
                $row_size = sizeof(array($row)[0]);
                if ($row_size == 0)
                {

                    //insert into db as new record
                    DB::table('gps')->insert(['intake' => $itk, 'day' => $day, 'unique_week_id' => $uwid, 'date' => $date_to_log]);

                } 
                else 
                {                    
                    //update db
                    DB::table('gps')->where('day', $day)->where('unique_week_id', $uwid)->update(['intake' => $itk]);
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

        //calcing averages
        $itks = $repository->itk_aves();

        $itk_mon_ave = $itks[0];
        $itk_tue_ave = $itks[1];
        $itk_wed_ave = $itks[2];
        $itk_thur_ave = $itks[3];
        $itk_fri_ave = $itks[4];
        $itk_sat_ave = $itks[5];
        $itk_sun_ave = $itks[6];

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
            'current_mon', 'itk_mon_ave',
            'itk_tue_ave', 'itk_wed_ave',
            'itk_thur_ave', 'itk_fri_ave',
            'itk_sat_ave', 'itk_sun_ave'
        ));
    }
}
