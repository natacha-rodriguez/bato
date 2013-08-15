<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Calendar
 *
 * @author naty
 */
class Calendar {

    protected static $calendars = array();

    /**
     * devuelve el principio del mes en curso
     * @param type $timestamp 
     */
    public static function getPrincipioAnio($timestamp = 'now', $asDateTime=false) {
        //$timestamp = ($timestamp == 'now') ? date() : $timestamp;
        $dateStamp = new DateTime($timestamp);
        $yearMonth = $dateStamp->format('Y-m');
        $string = $yearMonth . '-01';
        if (!$asDateTime) {
            return $string;
        } else {
            $time = strtotime($string);
            $dt = DateTime::createFromFormat('Y-m-d', $string);
            return $dt;
        }
    }

    /**
     * Devuelve el ultimo dia del 12avo mes a partir de hoy
     * @param string|DateTime $timestamp 
     */
    public static function getFinAnio($timestamp = 'now', $asDateTime = false) {
        $interval = new DateInterval('P1Y');
        $date = new DateTime($timestamp);
        $date->add($interval);
        $temptime = $date->format('Y-m');
        $date = DateTime::createFromFormat('Y-m-d', $temptime . '-01');
        $date->sub(new DateInterval('P1D'));

        $string = $date->format('Y-m-d');
        if (!$asDateTime) {
            return $string;
        } else {
            $time = strtotime($string);
            $dt = DateTime::createFromFormat('Y-m-d', $string);
            return $dt;
        }
    }

    /**
     *
     * @param DateTime $start
     * @param DateTime $end
     * @return array 
     */
    public static function getCalendarMatrix($start, $end, $spanish=false) {
        $end->add(new DateInterval('P1D'));
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);
        $index = $start->format('Y-m-d') . '_' . $end->format('Y-m-d');
        if (isset(self::$calendars[$index])) {
            return self::$calendars[$index];
        }


        $matrix = array();

        /* @var DateTime */
        foreach ($period as $day) {
            //$matrix[$day->format('Y')][$day->format('n')][$day->format('W')][$day->format('w')] = $day->format('j');
            // [2011][1][01][1] para 02/01/2011
            $matrix[$day->format('Y')][$day->format('n')][$day->format('W')][$day->format('w')] = $day;
        }

        $calendar = array();
        foreach ($matrix as $y => $year) {
            foreach ($year as $m => $month) {
                $weeks = array();

                foreach ($month as $wn => $wRow) {
                    //Logger::log(__METHOD__.'... week is....'. $wn);
                    $days = array();
                    for ($i = 0; $i < 7; $i++) {
                        $days[$i] = isset($wRow[$i]) ? $wRow[$i] : 'null';
                    }
                    $days[7] = $days[0];
                    unset($days[0]);
                    $weeks[] = $days;
                }

                $calendar[self::monthToSpanish($m) . ' ' . $y] = $weeks;
                //$calendar[$m] = $weeks;
            }
        }
        // var_export($calendar);
        self::$calendars[$index] = $calendar;
        return $calendar;
    }

    /**
     *
     * @param DateTime $day 
     * @return array
     */
    public static function getPositionInMatrix($day) {
        $start = Calendar::getPrincipioAnio('now', true);
        $end = Calendar::getFinAnio('now', true);
        $calendar = Calendar::getCalendarMatrix($start, $end);

        $position = array();
        $key = self::monthToSpanish($day->format('n')) . ' ' . $day->format('Y');

        foreach ($calendar[$key] as $wm => $week) {

            foreach ($week as $wd => $calDay) {
                if ($calDay != 'null') {
                    //Logger::log(__METHOD__ . '... esto es lo que hay...' . var_export($calDay, true));
                    $formatDay = $day->format('Y-m-d');
                    $formatCalDay = $calDay->format('Y-m-d');
                    if ($formatDay == $formatCalDay) {
                        $position['my'] = $key;
                        $position['mw'] = $wm;
                        $position['wd'] = $wd;
                        break;
                    }
                }
            }
            //  }
        }
        return $position;
    }

    public static function monthToSpanish($monthNumber) {
        $months = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        //Logger::log(__METHOD__ . " got $monthNumber corresponds to {$months[$monthNumber - 1]}");
        return $months[$monthNumber - 1];
    }

}

?>
