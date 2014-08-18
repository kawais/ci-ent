<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class welcome extends MY_Frontendcontroller
{
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $s = new \Wisprite\Services\AuthServices();
        var_dump($s);
        $s->test();



        //如将views移到application之外，开启下列语句
        // $this->load->add_package_path(FCPATH.'');    //views父级目录位置
        // $this->layout->setTplPath(FCPATH.'views/');  //views目录位置


        // $this->layout->cache=true;

        $this->layout->setSlot('meta');
        $this->layout->setSlot('libraries');
        $this->layout->setSlot('header');
        $this->layout->setSlot('content', array('hi' => 'hello'), 'index.php');
        $this->layout->setSlot('footer');

        $this->layout->view();

    }

    public function cachetest()
    {
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        if (!$foo = $this->cache->get('foo')) {
             echo 'Saving to the cache!<br />';
             $foo = time();

             // Save into the cache for 5 minutes
             $this->cache->save('foo', $foo, 10);
        }
        var_dump($foo);
    }

    public function moduletest()
    {
        $this->module('User', 'sayHi', array('hi' => 'hello', array('name' => 'ci')));
    }




}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
