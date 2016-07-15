<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Element_Captcha
 *
 * @author Victor
 */
class Core_Form_Element_Captcha extends Zend_Form_Element_Captcha {
    

    
    public $options=array(
        'scriptReload'=>'/index/reload-captcha',
        'imgReload'=>"/img/reload.jpg",
        'imgAnimateReload'=>"/img/reload.gif",
        'errorMessage'=>'Произошла ошибка приложения! Перегрузите страницу и попробуйте еще раз'
    );
    
    
    public function __construct($spec, $options = null) {
        $this->options['scriptReload'] = Core_Acr::getUrl(Core_Acr::RELOAD_CAPTCHA);
        $view = $this->getView();
        if (empty($options['imgReload'])) {
            $this->options['imgReload'] = $view->baseUrl()."/img/reload.jpg";
            $options['imgReload'] = $this->options['imgReload'];
        }else{
            $this->options['imgReload'] = $options['imgReload'];
        }
        if (empty($options['imgAnimateReload'])) {
            $this->options['imgAnimateReload'] = $view->baseUrl()."/img/reload.gif";
            $options['imgAnimateReload'] = $this->options['imgAnimateReload'];
        }else{
            $this->options['imgAnimateReload'] = $options['imgAnimateReload'];
        }
        parent::__construct($spec, $options);
    }

    

    public function setOptions(array $options) {            
        $diff = array_intersect_key($options, $this->options);
        $this->options = array_merge($this->options, $diff);
        return parent::setOptions($options);
    }

    
    public function render(Zend_View_Interface $view = null) 
    {
        
        if (!$view){
            $view = $this->getView();
        }
        $view->headScript()->captureStart();
            echo "
                $(function() {
                    $('#".$this->getId()." img').eq(0).after('<img src=\"".$this->options['imgReload']."\" onClick=\"reloadCaptcha();\" id=\"reload-img\" alt=\""._('показать другую картинку')."\" />');													
                });
                function reloadCaptcha()
                {
                    var elBlock = $('#".$this->getId()."');
                    $('#reload-img').removeAttr('onclick');
                    $('#reload-img').attr('src', '".$this->options['imgAnimateReload']."');
                    $.ajax({
                        type: \"POST\",
                        url: '".$this->options['scriptReload']."',
                        data: { form: $(elBlock).find('input[type=\"text\"]:eq(0)').data('form') },
                        dataType: 'JSON',                        
                        success: function(data){
                            $('#reload-img').attr('src', '".$this->options['imgReload']."');                            
                            $(elBlock).find('input[type=\"hidden\"]:eq(0)').attr('value', data.id);
                            $(elBlock).find('img:eq(0)').attr('src', data.src);
                            $(elBlock).find('input[type=\"text\"]:eq(0)').attr('value','');                            
                        },

                        error:  function(xhr, str){
                            $('#reload-img').attr('src', '".$this->options['imgReload']."');
                            $('#reload-img').attr('onclick', 'reloadCaptcha()'); 
                            bootbox.alert('".$this->options['errorMessage']."'); 
                        },
                        complete:  function(){ 
                            $('#reload-img').attr('onclick', 'reloadCaptcha()'); 
                        }
                    });            
                }
                ";
        $view->headScript()->captureEnd();
        $this->removeDecorator('Label');
        $this->removeDecorator('HtmlTag');
        $error = $this->getErrors()?' has-error':'';
        $xhtml = '
            <div class="form-group'.$error.'">
                <label class="control-label col-sm-3" for="'.$this->getId().'">
                    '.$this->getLabel().'
                </label>
                <div class="col-sm-9 captcha" id="'.$this->getId().'">
                    '.parent::render($view).'
                </div>
            </div>';
        return $xhtml;
    }
    
}

?>
