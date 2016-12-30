<?php
/**
 *  сколько знаков допустимо в дробной части
 */

class Core_Validate_CountNumFraction extends Core_Validate_Abstract
{

    const INVALID = 'countInvalid';

    protected $_messageTemplates = array(
        self::INVALID => "Допустимо только '%value%' знака в дробной части"
    );
    
    protected $_countNum = 2;

    /**
     * настройка параметров
     * @param array $params - массив параметров
     */
    public function  __construct($params) {
        if (is_array($params) && isset($params['countNum'])){
            $this->_countNum = (int)$params['countNum'];
        }elseif(is_string($params) or is_int($params)) {
            $this->_countNum = (int)$params;
        }
        $this->_setValue($this->_countNum);
    }

    /**
     *  валидация параметра
     * @param <type> $value     - исходное число     
     * @return boolean
     */
    public function  isValid($value) 
    {
        if ($value){
            $value=(float)$value;                        
            $partsNum = explode('.', $value, 2);
            if (isset($partsNum[1]) && strlen($partsNum[1]) > $this->_countNum){
                $this->_error(self::INVALID);
                return false;
            }
        }
        return true;
    }

}
