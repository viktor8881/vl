<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Date
 *
 * @author Viktor Ivanov
 */
class Core_Date extends DateTime {

    const DB = "Y-m-d H:i:s";
    const DMY = 'd.m.Y';
    const DMYHI = 'd.m.Y H:i';

    protected static $_month = array(1=>'январь','февраль','март','апрель','май','июнь',
                        'июль','август','сентябрь','октябрь','ноябрь','декабрь');
    /**
     * название месяцев в родительном падеже
     * @var array
     */
    protected static $_monthGenetive = array(1=>'января','февраля','марта','апреля','мая','июня',
                        'июля','августа','сентября','октября','ноября','декабря');
    
    /**
     * название дней недели
     * @var array 
     */
    protected static $_daysWeek = array('воскресенье', 'понедельник','вторник','среда','четверг','пятница','суббота');
    /**
     *короткие названия дней недели
     * @var array
     */
    protected static $_daysWeekShort = array('вс', 'пн','вт','ср','чт','пт','сб');

       
    /**
     * получить список всех дней недели
     * @return array
     */
    public static function getDaysWeek()
    {
        return self::$_daysWeek;
    }

    /**
     * полное название дня недели по ид
     * @param int $id
     * @return string || null
     */
    public static function getNameDayWeekById($id)
    {
        return isset(self::$_daysWeek[$id])?self::$_daysWeek[$id]:null;
    }

    
    /**
     * краткое название дня недели по ид
     * @param int $id
     * @return string || null
     */
    public static function getNameShortDayWeekById($id)
    {
        return isset(self::$_daysWeekShort[$id])?self::$_daysWeekShort[$id]:null;
    }



    public function __construct($time="now") {
        parent::__construct($time);
    }
    
    
    /**
     * название месяца
     * @return string
     */
    public function nameMonth()
    {
        return isset(self::$_month[$this->format('n')])?self::$_month[$this->format('n')]:null;
    }

    

    /**
     * название месяца в родительном падеже
     * нужен для вывода полных дат Пр. 10 января, а не январь
     * @return string
     */
    public function nameMonthGenitive()
    {
        return isset(self::$_monthGenetive[$this->format('n')])?self::$_monthGenetive[$this->format('n')]:null;
    }


    /**
     *  дней в месяце
     * @return integer
     */
    public function getMonthDays()
    {        
        return date("d",(mktime(0, 0, 0, (int)$this->format('m')+1, 0, $this->format('Y'))));
        //cal_days_in_month
    }
    
    /**
     * названия дня недели
     * @return string
     */
    public function getNameDayWeek()
    {
        return self::getNameDayWeekById($this->format('w'));
    }
    
    /**
     * краткое названия дня недели
     * @return string
     */
    public function getNameShortDayWeek()
    {
        return self::getNameShortDayWeekById($this->format('w'));
    }

    /**
     * установить день
     * @param integer $day
     */
    public function setDay($day)
    {
        $this->setDate( $this->format("Y"),  $this->format("m"), (int)$day);
        return $this;
    }
    
    public function setYear($year)
    {
        $this->setDate( $year,  $this->format("m"), $this->format("d"));
        return $this;
    }

    /**
     *  разница между датами ==1месяц
     * @param Core_Date $time
     */
    public function isMonthDiffPeriod(Core_Date $time)
    {        
        if ((int)$this->format('d')==1 && $this->format('m')==$time->format('m') &&
                $this->format('Y')==$time->format('Y') &&
                $this->getMonthDays()==$time->format('d')){
            return true;
        }else{
            return false;
        }
    }

    /**
     * вернуть формат в виде 'd.m.Y'
     * @return 'd.m.Y'
     */
    public function formatDMY()
    {
        return $this->format(self::DMY);
    }

    /**
     * вернуть формат в виде 'd.m.Y H:i'
     * @return 'd.m.Y H:i'
     */
    public function formatDMYHI()
    {
        return $this->format(self::DMYHI);
    }

    public function formatDb()
    {
        return $this->format('Y-m-d H:i:s');
    }
    
    public function formatDbDate()
    {
        return $this->format('Y-m-d');
    }
    
    /**
     *  сравнение дат на равенство
     * @param DateTime $date - дата с которой сравнить
     * @return integer <br />
     *      1  - переданная дата старше (раньше)  <br />
     *      -1 - переданная дата младше (позднее)     <br />
     *      0  = даты равны
     */
    public function compareDate(DateTime $date)
    {
        if ($this->format('U') > $date->format('U')){
            return 1;
        }elseif($this->format('U') < $date->format('U')){
            return -1;            
        }else{
            return 0;
        }
    }
    
    
    
    
    public static function sort($a, $b)
    {
        $a = strtotime($a);
        $b = strtotime($b);
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
    
    
}
