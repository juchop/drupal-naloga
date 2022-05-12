<?php

namespace Drupal\drupalup_block;

class DateCalculator {
    
    //forms the block's output based on given date
    public function daysUntilEventStarts($date){
        //get difference in days between now and the given date
        $difference = $this->getDifferenceInDaysFromCurrentDate($date);
        return $difference;
    }
    
    /**
     * calculates difference in days between now and given date
     * returns an integer value
     * -pozitive integer represents days until the date passed as parameter
     * -returns 0 if the date passed is the current date
     * -negative integer means the passed date is in the past
     */
    public function getDifferenceInDaysFromCurrentDate($date){
        //current time
        $now = strtotime(date("Y-m-d"));
        //event datetime field converted to timestamp
        $event_date = strtotime(substr($date,0,10));
        //timestamp difference
        $difference = $event_date - $now;
        //timestamp difference rounded up to days
        return ceil($difference / (60*60*24));
    }
}