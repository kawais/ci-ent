<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected $class,$method,$args;


    function __construct()
    {

        date_default_timezone_set('Asia/Chongqing');
        parent::__construct();


        $this->ci=&get_instance();
        $this->class=$this->router->fetch_class();
        $this->method=$this->router->fetch_method();
        // $this->isGet=isGET();
        // $this->isPost=isPOST();

        // $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        // if ( ! $foo = $this->cache->get('foo'))
        // {
        //      echo 'Saving to the cache!<br />';
        //      $foo = time();

        //      // Save into the cache for 5 minutes
        //      $this->cache->save('foo', $foo, 10);
        // }
        // var_dump($foo);




        // $this->load->add_package_path(FCPATH.'');
        // $this->layout->setTplPath(FCPATH.'views/');

        // $_tpl=$this->input->get('tpl');
        // if ($_tpl) {
        //     $this->layout->template=$_tpl;
        // }

        // $this->layout->template='mi';

        // $this->layout->merge=TRUE;
        // //$this->layout->parse=TRUE;不支持数组

        // if (ENVIRONMENT=='production') {
        //     $this->layout->cache=TRUE;
        //     $this->layout->debug=FALSE;
        // }

        // $this->layout->setSlot('meta');
        // $this->layout->setSlot('libraries');
        // $this->layout->setSlot('header');
        // $this->layout->setSlot('content',array('class'=>$this->class,'method'=>$this->method));
        // $this->layout->setSlot('footer');

        // $staticpath='/static';
        // //$staticpath='http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'static';

        // $this->layout->setVars(array('staticpath'=>$staticpath));

        // $this->args=$this->uri->uri_to_assoc();

        // $this->org=OrgServices::getInstance()->getOrg();


        // $this->user=AuthServices::getInstance()->checkLogin();
        // $this->manager=AuthServices::getInstance()->checkManagerLogin();

        // $this->layout->setVars(array('user'=>$this->user,'manager'=>$this->manager,'org'=>$this->org));



    }

    function message($args=array(),$tpl='index')
    {
        $backurl=$this->input->get('backurl');
        $backurl?$args['backurl']=$backurl:'';
        $this->layout->setSlot('content',array('args'=>$args),'message/'.$tpl.'.php');
        $this->layout->view();
        $this->output->_display();
        die;
    }

    function outJson($args=array())
    {
        header('Content-type: application/json');
        echo json_encode($args);
        die;
    }
}