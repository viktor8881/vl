<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Hidden
 *
 */
class Core_Form_Element_HiddenData extends Zend_Form_Element_Hidden
{
    
    public $helper = 'hiddenData';
    
    public $options=array(
        'data'=>null,
        'tag'=>'div'
        );
    
    public function __construct($spec, $options = null) 
    {        
        parent::__construct($spec, $options);
        $this->setDecorators(array(
                'ViewHelper',
                'Errors',
                'Description',
                array('HtmlTag'=>new Core_Form_Decorator_HtmlTag(array('class'=>'col-sm-9'))),
                array('Label'=>new Core_Form_Decorator_Label(array('class'=>'col-sm-3'))),
                ));
        $this->addDecorator(new Core_Form_Decorator_BootstrapControl());
        $view = $this->getView();
        if ($view) {
            $view->addBasePath('Core/Form', 'Core_Form');
        }
    }

    /**
     * Высчитывает только указанные пользователем опции и их устанавливает
     *
     * @param  array $options
     * @return Core_Form_Element_HiddenData
     */
    public function setOptions(array $options)
    {
        $diff = array_intersect_key($options, $this->options);
        $this->options = array_merge($this->options, $diff);
        foreach ($diff as $key => $option) {
            unset ($options[$key]);
        }        
        parent::setOptions($options);
        return $this;
    }
    
    
    /**
     * установка значения для отображения
     * @param string $data
     * @return \Core_Form_Element_HiddenData
     */
    public function setData($data)
    {
        $this->options['data'] = $data;
        return $this;
    }
    
    
}

