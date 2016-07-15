<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Helper_SumVat
 *  вывод числа в ввиде строки
 * @author Viktor Ivanov
 */
class Core_Helper_NumToString extends Zend_View_Helper_Abstract {
    
    /**
     * числа прописью и ден еденицей
     * @param string|Int|Float $number  - число которое нужно написать прописью
     * @param boolean $stripkop         - перевести копейки в "прописную" форму
     * @return string                   -
     *
     * Example
     * $this->view->numToString('54,12')         - пятьдесят четыре рубля двенадцать копеек
     * $this->view->numToString('54,12', true)   - пятьдесят четыре рубля 12 копеек
     */
    public function numToString($number, $stripkop=false)
    {
        $number=(float)$number;
        $formatKop = Core_Container::getService('setting')->getCurrency()->getPluralFractional();
        $formatRb=Core_Container::getService('setting')->getCurrency()->getPlural();
        $out = $tmp = array();
        // Поехали!
        $tmp = explode('.', str_replace(',','.', $number), 2);        
        $o[] = $this->num2str($tmp[0]);
        $o[] = $this->view->plural($tmp[0], $formatRb, false);
        
        // нормализация копеек
        $kop = isset($tmp[1]) ? $tmp[1] : '00';
        $kop = $ri = str_pad((int) $kop, 2, '0', STR_PAD_RIGHT);
        if ($stripkop) {
            $o[] = $this->num2str($kop);
        }else{
            $o[] = $kop;
        }
        $o[] = $this->view->plural($kop, $formatKop, false);
                
        return preg_replace("/\s{2,}/",' ',implode(' ',$o));
    }
    
    
    public function num2str($num)
    {
        $nol = 'ноль';
        $str[10] = array(1=>'десять','двадцать','тридцать','сорок','пятьдесят', 'шестьдесят','семьдесят','восемьдесят','девяносто');
        $str[11] = array(1=>'десять','одиннадцать','двенадцать','тринадцать', 'четырнадцать','пятнадцать','шестнадцать','семнадцать', 'восемнадцать','девятнадцать','двадцать');
        $str[100]= array(1=>'сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот', 'восемьсот','девятьсот');
                
        $sex = array(
            array(0=>null, 1=>'один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),// m
            array(0=>null, 1=>'одна','две','три','четыре','пять','шесть','семь', 'восемь','девять') // f
        );
        $forms = array(
            array(3=>1),
            array(3=>0), 
            array('тысяча', 'тысячи', 'тысяч',1), // 10^ 3
            array('миллион', 'миллиона', 'миллионов',0), // 10^ 6
            array('миллиард', 'миллиарда', 'миллиардов',0), // 10^ 9
            array('триллион', 'триллиона', 'триллионов',0), // 10^12
        );

        $num = (int)$num;
        if ($num== 0) {
            return $nol;
        }

        // разобъем на части
        $rub = number_format((int)$num, 0,'','-');        
        
        $segments = explode('-', $rub);        
        $offset = count($segments);                
        foreach ($segments as $k=>$lev) {
            $ri = (int)$lev;
            if ($ri== 0 && $offset>1) {// если сегмент==0 & не последний уровень(там Units)
                $offset--;
                continue;
            }
            $sexi= (int) $forms[$offset][3]; // определяем род
            // нормализация
            $ri = str_pad((int) $lev, 3, '0', STR_PAD_LEFT);
            // получаем циферки для анализа
            $r1 = (int)substr($ri, 0,1); //первая цифра
            $r2 = (int)substr($ri,1,1); //вторая
            $r3 = (int)substr($ri,2,1); //третья
            $r22= (int)$r2.$r3; //вторая и третья
            // разгребаем порядки
            if ($ri>99){
                $o[] = $str[100][$r1]; // Сотни
            }
            if ($r22>20) {// >20
                $o[] = $str[10][$r2];
                $o[] = $sex[ $sexi ][$r3];
            }else{ // <=20
                if ($r22>9){
                    $o[] = $str[11][$r22-9]; // 10-20
                }elseif($r22> 0){
                    $o[] = $sex[ $sexi ][$r3]; // 1-9
                }
            }
            if (isset($forms[$offset])){
                if ($offset>1)
                $o[] = $this->view->plural($ri, $forms[$offset], false);
            }
            $offset--;
        }
        return preg_replace("/\s{2,}/",' ',implode(' ',$o));
    }
    
    
}

