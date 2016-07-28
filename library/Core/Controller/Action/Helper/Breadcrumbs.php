<?php


/**
 * хелпер установок хлебных крошек
 * заменяет некоторые лейблы
 */
class Core_Controller_Action_Helper_Breadcrumbs extends Zend_Controller_Action_Helper_Url {

    
    
            
    
    public function direct()
    {
        return $this->setAllAdverts(Zend_Registry::get('city'));
    }
    
    /**
     * получить объект вида
     * @return Zend_View
     */
    public function getView()
    {
        return $this->getActionController()->view;
    }

    
    /**
     * получить объект хлебной крошки по ИД  
     * @param type $resId
     * @return type
     */
    private function _getNav($resId)
    {
        $navMain = Zend_Registry::get('Zend_Navigation');
        $res = Core_Acr::getArrayMCA($resId);
        return $navMain->findOneByParams($res);
    }


    
    /**
     * 
     * @return \Core_Controller_Action_Helper_Breadcrumbs
     */
    public function setAllAdverts(City_Model $city)
    {
        $nav = $this->_getNav(Core_Acr::ADVERT_LIST);
        $nav->setLabel('Все объявления '.$city->getNameGenetive())
                ->setTitle($this->getView()->city_Title($city))
                ->setParam('city', $city->getAlias());
        return $this;
    }
    
    
    
    /**
     * установка хлебных крошек для названия объявления
     * @param type $id
     * @param type $name
     * @return \Core_Controller_Action_Helper_Breadcrumbs
     */
    public function setAdvert($id, $name, City_Model $city=null)
    {
        if (!$city) {
            $city = Zend_Registry::get('city');
        }
        $nav = $this->_getNav(Core_Acr::ADVERT_INDEX);
        $nav->setLabel($name)
                ->setParam('alias', $id)
                ->setParam('city', $city->getAlias());        
        return $this;
    }
    
    
    /**
     * установка хлебных крошек для секции по модели
     * @param Category_Model $category
     * @return type
     */
    public function setSectionModel(Category_Model $category, City_Model $city=null)
    {
        if (!$city) {
            $city = Zend_Registry::get('city');
        }
        $nav = $this->_getNav(Core_Acr::ADVERT_SECTION);
        $nav->setLabel($category->getName())
                ->setParam('alias', $category->getAlias())
                ->setParam('city', $city->getAlias())
                ->setTitle($this->getView()->category_Title($category))
                ->setActive(true);
        return $this;
    }
    
    
    /**
     * установка хлебных крошек для категории по модели
     * @param Category_Model $category
     * @return type
     */
    public function setCategoryModel(Category_Model $category, City_Model $city=null)
    {
        if (!$city) {
            $city = Zend_Registry::get('city');
        }
        $section = $category->getParent();
        if ($section) {
            $this->setSectionModel($section, $city);
        }
        $nav = $this->_getNav(Core_Acr::ADVERT_CATEGORY);
        $nav->setLabel($category->getName())
                ->setParam('alias', $category->getAlias())
                ->setParam('city', $city->getAlias())
                ->setTitle($this->getView()->category_Title($category))
                ->setActive(true);
        return $this;
    }
    
    /**
     * установка хлебных крошек для типа по модели
     * @param Type_Model $type
     * @return \Core_Controller_Action_Helper_Breadcrumbs
     */
    public function setTypeModel(Type_Model $type, Category_Model $category, City_Model $city=null)
    {
        if (!$city) {
            $city = Zend_Registry::get('city');
        }
        $this->setCategoryModel($category, $city);
        $nav = $this->_getNav(Core_Acr::ADVERT_TYPE);
        $nav->setLabel($type->getName())
                ->setParam('type', $type->getAlias())
                ->setParam('alias', $category->getAlias())
                ->setParam('city', $city->getAlias())
                ->setTitle($this->getView()->type_Title($type, $category))
                ->setActive(true);
        
        return $this;
    }
    
    /**
     * установка хлебных крошек для типа по модели
     * @param Advert_Model $model
     * @return \Core_Controller_Action_Helper_Breadcrumbs
     */
    public function setAdvertModel(Advert_Model $model)
    {
        $this->setAllAdverts($model->getCity())
                ->setTypeModel($model->getType(), $model->getCategory(), $model->getCity())
                ->setAdvert($model->getAlias(), $model->getName());
        return $this;
    }
            
}