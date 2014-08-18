<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected $class,$method,$args;


    public function __construct()
    {

        date_default_timezone_set('Asia/Chongqing');
        parent::__construct();


        $this->ci=&get_instance();
        $this->class=$this->router->fetch_class();
        $this->method=$this->router->fetch_method();
        // $this->isGet=isGET();
        // $this->isPost=isPOST();
    }

    public function message($args=array(),$tpl='index')
    {
        $backurl=$this->input->get('backurl');
        $backurl?$args['backurl']=$backurl:'';
        $this->layout->setSlot('content',array('args'=>$args),'message/'.$tpl.'.php');
        $this->layout->view();
        $this->output->_display();
        die;
    }

    public function outJson($args=array())
    {
        header('Content-type: application/json');
        echo json_encode($args);
        die;
    }

    public function module($module,$method,$args=array())
    {
        $classname = "\\Wisprite\\Modules\\{$module}Module";
        $class = new $classname();
        call_user_func_array(array($class, $method), $args);
    }
}
