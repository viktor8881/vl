<?php
/**
 * Repository Setting_Repository
 *
 * Repository Setting_Repository
 */
class Setting_Repository extends Core_Domen_Repository_Abstract
{


    
    /**
     * получить по алиасу
     * @param string $alias
     * @return Setting_Model || null
     */
    public function getByAlias($alias)
    {
        return $this->getCollection()->getValue($alias);
    }
    
}
