<?php
/**
 * Service Setting_Manager
 *
 * Service Setting_Manager
 */
class Setting_Manager extends Core_Domen_Manager_Abstract
{
    
    
    const PERCENT_FRACT=2;
    const MONEY_FRACT=2;
    const METAL_FRACT=3;
    const MONEY_UNIT='руб.';
    

    /**
     * получить значение по алиасу
     * @param type $alias
     * @return Setting_Model
     * @throws RuntimeException
     */
    public function getValueByAlias($alias) {
        $setting = parent::getRepository()->getByAlias($alias);
        if ($setting) {
            return $setting->getValue();
        }
        throw new RuntimeException('Параметр '.$alias.' не задан! Проверьте настройки системы');
    }
        
    /**
     * кол-во знаков после запятой для ден величин
     * @return int
     */
    public function fractMoney() {
        return self::MONEY_FRACT;
    }
                    
    /**
     * кол-во знаков после запятой для денежных величин
     * @return int
     */
    public function getRoundMoney() {
        return $this->fractMoney();
    }
    
    public function getRoundMetal() {
        return self::METAL_FRACT;
    }
    
    public function getRoundPercent() {
        return self::PERCENT_FRACT;
    }
    
    /**
     * название денежной еденицы
     * @return type
     */
    public function getMoneyUnit() {
        return self::MONEY_UNIT;
    }
    

}
