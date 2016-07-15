<?php
/**
 * Factory Setting_Factory
 *
 * Factory Setting_Factory
 */
class Setting_Factory implements Core_Domen_IFactory
{

    /**
     * create Model
     *
     * @param array $model
     * @return Setting_Model
     */
    public function create(array $values = null)
    {
        return new Setting_Model($values);
    }


}
