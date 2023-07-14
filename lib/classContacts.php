<?php
class Contacts{

    protected $error = array();
    public $layout;

    public function ListContacts(){        
        
        if (!$_GET['sort']){$_GET['sort'] = 'asort';}
        $this->sortContacts();

        // рисуем страницу
        $this->render(); 
        
    }

    /**
    * Добавление/редактирование контакта в переменную сессии
    */
    public function AddContact(){
       // валидация параметров формы
       if ($this->validat()){
        // если есть id, то редактируем значение элементов массива сессии с этим индексом
       if (isset($_GET['id'])){
        $_SESSION['contact']['name'][$_GET['id']] = $_GET['name'];
        $_SESSION['contact']['phone'][$_GET['id']] = $_GET['phone'];
       }else{ // если нет id, то добавляем новый элемент
       $_SESSION['contact']['name'][] = $_GET['name'];
       $_SESSION['contact']['phone'][] = $_GET['phone'];
       }
       
       header('Location:http://'.$_SERVER['SERVER_NAME']);
       }

    }

    public function EditContact(){
        // Присваиваем полям формы значения из сессии
        $_GET['name'] = $_SESSION['contact']['name'][$_GET['id']];
        $_GET['phone'] = $_SESSION['contact']['phone'][$_GET['id']];
          
        $this->render(); 
     }

    public function DeleteContact(){
        $id = $_GET['del'];
        //удаляем элемент массива
        unset($_SESSION['contact']['name'][$id]);
        unset($_SESSION['contact']['phone'][$id]);
       
        //пересчитываем индексы
        $_SESSION['contact']['name'] = array_values($_SESSION['contact']['name']);
        $_SESSION['contact']['phone'] = array_values($_SESSION['contact']['phone']);

        if (empty($_SESSION['contact']['name'][0]))
        {
        unset($_SESSION['contact']);        
        }

        //переадрессация на дефолтную страницу
        header('Location:http://'.$_SERVER['SERVER_NAME']);  
        
    }

    function validName($str){
        //проверка имени - только буквы, цифры, пробелы и более 3 символов
	    return mb_strlen($str) >= 3 && !preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/",$str);
        return true;

    }

    function validPhone($str){
        //должно быть не меньше 10 цифр после удаления лишних символов
        return mb_strlen(preg_replace("/[^0-9]/", '', $str)) >= 10;
        return true;

    }

    function existName($str){

    }

    function validat(){
        //последоваельная проверка полей
        if (!$this->validName($_GET['name'])){ $this->error[] = 'имя'; }
        if (!$this->validPhone($_GET['phone'])){ $this->error[] = 'номер телефона'; }
        if (count($this->error)>0){
            //если есть ошибка, вывод вместе с ошибкой
            $this->render($this->error);

        }else{

            return true;

        }

    }

    function existPhone($str){


    }

    function sortContacts(){
        $phone2 = array();
            if (is_array($_SESSION['contact']['name'])){
                
                $name = array_values($_SESSION['contact']['name']);
                $phone = array_values($_SESSION['contact']['phone']);
                $phone2 = $phone;
            
            }else{

                return false;

            }
            $n=0;

            switch($_GET['sort']){

            case'asort':
                asort($name);
            break;

            case'arsort':
                arsort($name);
            break;
            }

            foreach ($name as $key=>$val){
                $phone[$key] = $phone2[$n];
                $n++;
            } 

            $_SESSION['contact']['name'] = $name;
            $_SESSION['contact']['phone'] = $phone;

            $_SESSION['contact']['name'] = array_values($_SESSION['contact']['name']);
            $_SESSION['contact']['phone'] = array_values($_SESSION['contact']['phone']);

        
    }

    // функция вывода шаблона страницы с передачей переменной (ошибка)
    // остальные переменные берем из глобальных массивов
    function render($error=array()){       

        if(count($error)>0){
            $error_str = 'Заполните правильно поля:';
            foreach($error as $e=>$v){ $error_str.= '<br>- '.$v; }
        }

        if (file_exists($this->layout)){

            ob_start();
            include($this->layout);
            $body = ob_get_contents();
            ob_end_clean();

            echo $body;
        }

    }


}


?>