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

    public function itk_aves()
    {
        $days = ['mon', 'tue', 'wed', 'thur', 'fri', 'sat', 'sun'];

        $current_mon = strtotime("monday this week"); 

        $itk_aves = [];
        foreach ($days as $day)
        {
            $record = GP::all()->where('unique_week_id', '<', $current_mon)->where('day', $day);
            
            if (sizeof($record) !== 0)
            { 
            
            $itk_sum = 0;
            foreach ($record as $item) 
            {
                $itk_sum += $item['intake']; 
                $itk_ave = $itk_sum / sizeof($record) + 0.00001;
            }
            array_push($itk_aves, $itk_ave);
            }
        }
        return $itk_aves; //array
    }

    public function dsp_aves()
    {
        $days = ['mon', 'tue', 'wed', 'thur', 'fri', 'sat', 'sun'];

        $current_mon = strtotime("monday this week"); 

        $dsp_aves = [];
        foreach ($days as $day)
        {
            $record = GP::all()->where('unique_week_id', '<', $current_mon)->where('day', $day);
            
            if (sizeof($record) !== 0) 
            {
                $dsp_sum = 0;
                foreach ($record as $item) 
                {
                    $dsp_sum += $item['daily_stock_purchase']; 
                    $dsp_ave = $dsp_sum / sizeof($record) + 0.00001;
                }
                array_push($dsp_aves, $dsp_ave);
            }
        }
        return $dsp_aves; //array
    }

    public function create()
    {
        
    }

    public function read()
    {

    }

    public function update()
    {

    }


}