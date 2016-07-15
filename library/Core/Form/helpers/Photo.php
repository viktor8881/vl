<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Helper_FormPhoto
 *
 * @author Viktor Ivanov
 */


class Core_Form_Helper_Photo extends Zend_View_Helper_FormElement
{
    
    private $_urlUpload = '/';
    private $_pathImg = '/img/';
    
    

    public function photo($name, $value = '', $attribs = null, $options = null) {
        $xhtml = '';
        if (isset($options['url-upload'])) {
            $this->_urlUpload = $options['url-upload'];
        }
        if (isset($options['path-img'])) {
            $this->_pathImg = $options['path-img'];
        }
        
        $info    = $this->_getInfo($name, $value, $attribs);        
        extract($info); // name, id, value, attribs, options, listsep, disable, escape
        
        $xhtml .= '<div id="photo" class="row">';
            if ($value && is_array($value)) {
                foreach ($value as $img) {
//                    $img = '/'.Advert_Image_Model::FOLDER_IMGS.'/'.Advert_Image_Model::LARGE.'/'.$img;
                    $img = $this->_pathImg.$img;
                    $xhtml .= $this->_blockThrumbnail($img, $name);
                }
            }else{
            }
        $xhtml .= '</div>
                <div class="clear"></div>
                <p class="hidden text-center" id="download">идет загрузка <img src="'.$this->view->baseUrl().'/img/spinner.gif"/></p>
                <label class="btn btn-info btn-sm btn-block" >
                    <span class="glyphicon glyphicon-download-alt"></span> Загрузить фото
                    
                        <input class="" style="position:absolute; left:-1900px;" id="fileupload" type="file" name="file" data-url="'.$this->_urlUpload.'">
                </label>
            ';
//        $xhtml .= '<div class="text-center help-block small" id="text-default">Добавьте фотографии.</div>';
        $this->_script($name);
        return $xhtml;        
    }
    
    private function _blockThrumbnail($pathImg, $name) {
        $img = basename($pathImg);
        $xhtml = '<div class="thrumbnail">
                    <img src="'.$pathImg.'"  />
                    <button class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> Удалить</button>
                    <input type="hidden" onclick="return false;" name="'.$name.'" value="'.$img.'">
                </div>';
        return $xhtml;
    }
    
    
    private function _script($name)
    {
        $this->view->headScript()->appendFile($this->view->baseUrl().'/js/vendor/jquery.ui.widget.js');
        $this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.iframe-transport.js');
        $this->view->headScript()->appendFile($this->view->baseUrl().'/js/jquery.fileupload.js');
        $this->view->headScript()->captureStart();
        echo "
            $(function () {
                $('#fileupload').fileupload({
                    dataType: 'json',
                    //singleFileUploads: false,                    
                    add: function (e, data) {
                        $('#download').removeClass('hidden');
                        data.submit();
                        return false;
                    },
                    done: function (e, data) {
                        $('#download').removeClass('hidden');
                        $('#text-default').addClass('hidden');
                        $('#download').addClass('hidden');
                        $.each(data.result.files, function (index, file) {
                            if (file.error == false) {
                                var content = $('<div/>').addClass('thrumbnail').
                                        html('<img src=\"'+file.url+'\" /><button class=\"btn btn-danger btn-xs\"><span class=\"glyphicon glyphicon-remove\"></span> Удалить</button><input type=\"hidden\" name=\"".$name."\" value=\"'+file.name+'\">');
                                $(content).appendTo('#photo');
                            }else if (file.error == true && file.message!=undefined){
                                bootbox.alert(file.message);
                            }else{
                                bootbox.alert('При загрузке файла произошла ошибка.');
                            }
                        });
                        checkDefaultMesage();
                    },
                    fail: function (e, data) {                            
                            $('#text-default').addClass('hidden');
                            $('#download').addClass('hidden');
                            bootbox.alert('При загрузке файла произошла ошибка.');
                            checkDefaultMesage();
                        }
                });

                $('#photo').on('click', '.thrumbnail .btn-danger', function(){ $(this).parent().remove(); checkDefaultMesage(); });
            });
            
            function checkDefaultMesage() {
                if ($('#photo .thrumbnail').length == 0) {
                    $('#text-default').removeClass('hidden');
                }
            }
            ";
        $this->view->headScript()->captureEnd();
        return $this;
    }
    
}
