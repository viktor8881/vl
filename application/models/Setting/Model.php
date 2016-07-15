<?php
/**
 * Model Setting_Model
 *
 * Model Setting_Model
 */
class Setting_Model extends Core_Domen_Model_Abstract
{

    protected $_id = null;
    protected $_alias = null;
    protected $_value = null;

    protected $_aliases = null;

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
        return array('id'=>$this->getId(),
        	'alias'=>$this->getAlias(),
        	'value'=>$this->getValue(),
        );
    }

    /**
     * Set property id
     *
     * @param int $id
     * @return
     */
    public function setId($id)
    {
        $this->_id = (int)$id;
        return $this;
    }

    /**
     * Get the id property
     *
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set property alias
     *
     * @param string $alias
     * @return
     */
    public function setAlias($alias)
    {
        $this->_alias = (string)$alias;
        return $this;
    }

    /**
     * Get the alias property
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->_alias;
    }

    /**
     * Set property value
     *
     * @param string $value
     * @return
     */
    public function setValue($value)
    {
        $this->_value = (string)$value;
        return $this;
    }

    /**
     * Get the value property
     *
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }


}
