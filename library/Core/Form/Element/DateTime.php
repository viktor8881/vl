<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Элемент даты и времени
 *
 * @author Viktor Ivanov
 */
class Core_Form_Element_DateTime extends Zend_Form_Element_Xhtml{
    
    /**
     * Имя хелпера
     * @var string
     */
    public $helper = 'dateTime';
    
    const FORMAT_DATE_TIME = 'format_date_time';
    const FORMAT_DATE = 'format_date';
    const FORMAT_TIME = 'format_time';
    
    /**
     * Формат даты для JS
     * @var type 
     */
    protected static $_formatsJS = array(
        self::FORMAT_DATE_TIME=>'DD.MM.YYYY HH:mm',
        self::FORMAT_DATE=>'DD.MM.YYYY',
        self::FORMAT_TIME=>'HH:mm',
    );
    
    /**
     * формат даты в DateTime
     * @var type 
     */
    protected static $_formatsFilter = array(
        self::FORMAT_DATE_TIME=>'d.m.Y H:i',
        self::FORMAT_DATE=>'d.m.Y',
        self::FORMAT_TIME=>'H:i',
    );
    
    /**
     * формат даты Zend_Date
     * @var type 
     */
    protected static $_formatsValidator = array(
        self::FORMAT_DATE_TIME=>'dd.MM.YYYY HH:mm',
        self::FORMAT_DATE=>'dd.MM.YYYY',
        self::FORMAT_TIME=>'HH:mm',
    );
    
    private $_defaultFormat = self::FORMAT_DATE_TIME;
    
    
    protected $options=array(
            'pickDate'=>true,
            'pickTime'=>true,
            'useMinutes'=>true,
            'useSeconds'=>false,
            'minuteStepping'=>1,
            'maxDate'=>null,
            'showToday'=>true,
            'language'=>'ru',
            'defaultDate'=>null,
            'useStrict'=>null,
            'sideBySide'=>null,
        );
//$.fn.datetimepicker.defaults = {
//    pickDate: true,                 //en/disables the date picker
//    pickTime: true,                 //en/disables the time picker
//    useMinutes: true,               //en/disables the minutes picker
//    useSeconds: true,               //en/disables the seconds picker
//    useCurrent: true,               //when true, picker will set the value to the current date/time     
//    minuteStepping:1,               //set the minute stepping
//    minDate:`1/1/1900`,               //set a minimum date
//    maxDate: ,     //set a maximum date (defaults to today +100 years)
//    showToday: true,                 //shows the today indicator
//    language:'en',                  //sets language locale
//    defaultDate:"",                 //sets a default date, accepts js dates, strings and moment objects
//    disabledDates:[],               //an array of dates that cannot be selected
//    enabledDates:[],                //an array of dates that can be selected
//    icons = {
//        time: 'glyphicon glyphicon-time',
//        date: 'glyphicon glyphicon-calendar',
//        up:   'glyphicon glyphicon-chevron-up',
//        down: 'glyphicon glyphicon-chevron-down'
//    }
//    useStrict: false,               //use "strict" when validating dates  
//    sideBySide: false,              //show the date and time picker side by side
//    daysOfWeekDisabled:[]          //for example use daysOfWeekDisabled: [0,6] to disable weekends 
//};
        

    public function __construct($spec, $options = null) {
        $this->options['format']=self::$_formatsJS[self::FORMAT_DATE_TIME];
        $options['class'] = isset($options['class'])?$options['class'].' form-control':'form-control';
        parent::__construct($spec, $options);
        if (!isset($options['decorators'])) {
            $this->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    'Description',
                    array('HtmlTag'=>new Core_Form_Decorator_HtmlTag(array('class'=>'col-sm-9'))),
                    array('Label'=>new Core_Form_Decorator_Label(array('class'=>'col-sm-3'))),
                    ));
            $this->addDecorator(new Core_Form_Decorator_BootstrapControl());
        }
        
        // установка формата
        if (isset($options['format'])) {
            $this->setDefaultFormat($options['format']);
        }
        // установка мин даты
        if (isset($options['minDate'])) {
            $this->setMinDate($options['minDate']);
        }
        // установка макс даты
        if (isset($options['maxDate'])) {
            $this->setMaxDate($options['maxDate']);
        }
        // показывать секунды
        if (isset($options['useSeconds'])) {
            $this->setUseSeconds($options['useSeconds']);
        }
        // скрыть время
        if (isset($options['pickTime'])) {
            $this->setPickTime($options['pickTime']);
        }
        // скрыть дату
        if (isset($options['pickDate'])) {
            $this->setPickDate($options['pickDate']);
        }
        // дата по умолчанию
        if (isset($options['defaultDate'])) {
            $this->setDefaultDate($options['defaultDate']);
        }
        
        // фильтрация
        if (!isset($options['filters'])) {
            $this->setFilters(array('StringTrim', new Core_Filter_DateTime(self::$_formatsFilter[$this->getDefaultFormat()])));
        }
        // валидация
        if (!isset($options['validators'])) {
            $this->setValidators(array(new Core_Validate_DateTime(self::$_formatsValidator[$this->getDefaultFormat()])));
        }
        
        // иконка 
        if (isset($options['icon'])) {
            $this->setAttrib('icon', $options['icon']);
        }
        $this->setAttrib('readonly', 'readonly');
        $view = $this->getView();
        if ($view) {
            $view->addBasePath('Core/Form', 'Core_Form');
        }
    }
    
    
    
    /**
     * установка мин и макс даты
     * @todo - форматы дат должны соотв $this->options['data']['format']
     * @param type $minDate
     * @param type $maxDate
     * @return \Core_Form_Element_DateBootsrap
     */
    public function setAttribDate($minDate=null, $maxDate=null)
    {
        if ($minDate) {
            $this->setMinDate($minDate);
        }
        if ($maxDate) {
            $this->setMaxDate($maxDate);
        }
        return $this;
    }
    
    public function setMinDate($date)
    {
        if ($date instanceof Core_Date) {
            $date = $date->format(self::$_formatsFilter[$this->getDefaultFormat()]);
        }
        $this->options['minDate'] = (string)$date;
        return $this;
    }
    
    
    public function getMinDate() 
    { 
        return $this->options['minDate']; 
    }
    
    
    public function setMaxDate($date)
    {
        if ($date instanceof Core_Date) {
            $date = $date->format(self::$_formatsFilter[$this->getDefaultFormat()]);
        }
        $this->options['maxDate'] = (string)$date;
        return $this;
    }
    
    public function getMaxDate() 
    { 
        return $this->options['maxDate']; 
    }
    
    protected function _setFormat($format)
    {
        $this->options['format'] = isset(self::$_formatsJS[$format])?self::$_formatsJS[$format]:null;
        return $this;
    }
    
    protected function _getFormat()
    {
        return $this->options['format'];
    }
    
    /**
     * вернуть установленный формат даты JS
     * @return type
     */
    public function getFormatJS()
    {
        return $this->options['format'];
    }
    
    public function setUseSeconds($value)
    {
        $this->options['useSeconds'] = (bool)$value;
        return $this;
    }
    
    public function getUseSeconds()
    {
        return $this->options['useSeconds'];
    }
    
    
    public function setPickTime($value)
    {
        $this->options['pickTime'] = (bool)$value;
        return $this;
    }
    
    public function getPickTime()
    {
        return $this->options['pickTime'];
    }
    
    public function setPickDate($value)
    {
        $this->options['pickDate'] = (bool)$value;
        return $this;
    }
    
    public function getPickDate()
    {
        return $this->options['pickDate'];
    }
    
    public function setDefaultDate($value)
    {
        if ($value instanceof Core_Date) {
            $value = $value->format(self::$_formatsFilter[$this->getDefaultFormat()]);
        }
        $this->setValue($value);
        return $this;
    }
    
    public function getDefaultFormat()
    {
        return $this->_defaultFormat;
    }
    
    public function setDefaultFormat($format)
    {
        if (array_key_exists($format, self::$_formatsJS)) {
            $this->_defaultFormat = $format;
        }
        return $this;
    }
    
}
