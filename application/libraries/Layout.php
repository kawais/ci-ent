<?php if(! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

/**
 * CodeIgniter Layout Library Class
 *
 *
 * @package CodeIgniter
 * @subpackage Libraries
 * @category Libraries
 * @author Lyndon Wang
 * @link http://blog.lyphp.com
 *
 * 将views目录移动到application之外时，在控制器中指定新目录
 * 如移动到web根目录
 * $this->load->add_package_path(FCPATH.'');    //views父级目录位置
 * $this->layout->setTplPath(FCPATH.'views/');  //views目录位置
 */
class Layout
{
    private $slots = array ();
    private $files = array ();
    private $ci, $vars, $return, $layout, $slotvars, $layoutfile, $tplpath;
    private $cacheFile = 'TPLPathCache.php';
    public $parse = true;
    public $merge = true;
    public $default_template = 'commtpl';//默认tpl
    public $template = 'commtpl';//当前tpl
    public $realfile = '';//真实文件路径
    public $cache = false;
    public $debug = true;

    function __construct($layout = 'layout/layout.php',$template='commtpl')
    {
        $this->ci = &get_instance ();
        $this->APPPATH = APPPATH;
        $this->template=$template;
        $this->tplpath = $this->APPPATH . 'views/';  //模板根目录
        $this->layoutfile = $layout;
        $this->vars = array ();
        $this->debug = true;
        $this->varspath = array();
        // if (ENVIRONMENT==='production') {
        //     $this->debug = false;
        //     $this->cache = true;
        // }
    }

    public function setTplPath($path)
    {
        $this->tplpath=$path;
    }

    public function setLayoutFile($layoutfile = 'layout/layout.php')
    {
        $this->layoutfile = $layoutfile;
    }

    public function setSlot($path, $vars = array(), $file = '')
    {
        $this->slots[] = $path;
        $_path = explode ( '/', $path );
        $filename = $_path[count ( $_path ) - 1];
        $fullpathfile = $this->getFile ( $file, $filename );
        $this->files[$path]=$fullpathfile;
        $this->parseFile($fullpathfile);
        $this->setVars($vars, $path);
    }

    public function view($vars = array(), $return = false)
    {
        $this->layout = $this->getFile ( $this->layoutfile );

        if(! empty ( $vars ))
        {
            $this->vars = array_merge ( $this->vars, ( array ) $vars );
        }
        $this->return = $return;


        // 处理各级模块
        $this->slot ();
        if($this->parse)
        {
            $layout = $this->ci->load->view ( $this->layout, $this->vars, true );
            return $this->ci->parser->_parse ( $layout, empty ( $this->vars ) ? array () : ( array ) $this->vars, $return );
        } else
        {
            return $this->ci->load->view ( $this->layout, $this->vars, $return );
        }
    }

    public function setVars($vars = array(), $path = '', $overwrite=false)
    {
        foreach ($vars as $key => $value) {

            if(!$overwrite && array_key_exists($key, $this->vars))
            {
                show_error('Variable "'.$key.'" has defined in other Slot');
            }
        }

        if($this->merge || !$path)
        {
            $this->vars = array_merge ( $this->vars, $vars );
        }else{
            $this->slotvars[$path] = empty ( $vars ) ? array () : ( array ) $vars;
        }
    }


    private function parseFile($file)
    {
        $file=$this->tplpath.$file;
        if (!is_file($file)) {
            return;
        }
        $str=file_get_contents($file);
        $this->parseLayout($str);
        $this->parseTitle($str);
        // $this->parseInclude($str);
    }

    private function parseLayout($str)
    {
        preg_match("/{layout:(.*?)}/is", $str,$matches);
        if ($matches && $matches[1]) {
            $this->setLayoutFile('layout/'.$matches[1].'.php');
        }
    }

    private function parseInclude($str)
    {
        preg_match_all("/file_(.*?)/is", $str,$matches);
        if ($matches && isset($matches[0]) && $matches[0]) {
            foreach ($matches[0] as $key => $value) {
                $this->setSlot($value,array(),str_replace('_','/',$matches[1][$key]));
            }
        }
    }

    private function parseTitle($str)
    {
        preg_match("/{title:(.*?)}/is", $str,$matches);
        if ($matches && $matches[1]) {
            $this->setVars(array('title'=>$matches[1]),'',true);
        }
    }

    private function getFile($file = '', $filename = '')
    {
        if(! $this->ci) $this->ci = &get_instance ();
        // 默认使用于模块变量相同名称的文件
        if($file == '' && $filename) $file = $filename . '.php';
        if(strpos($file,'.php')===false && strpos($file,'.')===false)$file.='.php';


        $fileKey='';
        // echo $file.'<br>';
        // echo $this->tplpath.'<br>';
        //默认使用template下该controller目录下的文件
        $_path=array();



        //当前模板/当前控制器
        $tpl_ctl_path=$this->tplpath.$this->template.'/' . $this->ci->router->fetch_class () . '/';
        $_path[]=$tpl_ctl_path;

        $fileKey=$tpl_ctl_path;

        //当前模板/默认控制器
        $tpl_defctl_path=$this->tplpath.$this->template.'/' . 'default/';
        $_path[]=$tpl_defctl_path;

        //当前模板根目录
        $tpl_root_path=$this->tplpath.$this->template.'/';
        $_path[]=$tpl_root_path;

        //默认模板/当前控制器
        $deftpl_ctl_path=$this->tplpath.$this->default_template.'/' . $this->ci->router->fetch_class () . '/';
        $_path[]=$deftpl_ctl_path;

        //默认模板/默认控制器
        $deftpl_defctl_path=$this->tplpath.$this->default_template.'/' . 'default/';
        $_path[]=$deftpl_defctl_path;

        //默认模板根目录
        $deftpl_root_path=$this->tplpath.$this->default_template.'/';
        $_path[]=$deftpl_root_path;

        //未发现任何文件，使用空白文件
        $blank_file=$this->tplpath.$this->template.'/' . 'default/blank.php';
        if(!file_exists($blank_file))
        {
            $blank_file=$this->tplpath.$this->default_template.'/' . 'default/blank.php';
        }
        // echo $blank_file."<br>";
        // die;

        $_files=array();
        foreach ($_path as $key => $value) {
            $_files[]=$value.$file;
        }



        $fileKey.=$file;

        $cache=$this->getCacheFile($fileKey);
        $_file='';
        if ($this->cache && $cache!==false) {
            $_file=$cache;
        }else{

            foreach ($_files as $key => $value) {
                if (is_file($value)) {
                    $_file = $value;
                    break;
                }
            }
            // var_dump($_file);echo '<br>';
            //Loader类从application/views/开始读取文件
            $this->realfile=$_file;
            $_file=str_replace ( $this->tplpath, '', $_file );
            $this->setCacheFile($fileKey,$_file);
        }

        if (!$_file) {
            $_file=str_replace ( $this->tplpath, '', $blank_file );
            if($this->debug)
            {
                log_message('error', 'TPL Not Found --> '.$fileKey);
            }

        }
        return $_file;
    }


    private function getArrCache($key,$file)
    {
        $cachePath=$this->APPPATH.'cache'.DIRECTORY_SEPARATOR;
        $cacheFile=$cachePath.$file;
        if (!file_exists($cacheFile)) {
            return false;
        }
        $data=json_decode(file_get_contents($cacheFile),true);
        if (isset($data[$key])) {
            return $data[$key];
        }
        return false;
    }

    private function setArrCache($key,$value,$file)
    {
        $cachePath=$this->APPPATH.'cache'.DIRECTORY_SEPARATOR;
        $cacheFile=$cachePath.$file;
        if (!file_exists($cacheFile)) {
            $data=array();
        }else{
            $data=json_decode(file_get_contents($cacheFile),true);
        }
        $data[$key]=$value;
        return file_put_contents($cacheFile, json_encode($data));
    }


    private function getCacheFile($class)
    {
        $cacheFile=$this->cacheFile;
        return $this->getArrCache($class,$cacheFile);
    }

    private function setCacheFile($class,$file)
    {
        $cacheFile=$this->cacheFile;
        return $this->setArrCache($class, $file,$cacheFile);
    }



    // 按包含层级，由多到少排序
    private function sort(&$array)
    {
        usort ( $array, array (
                'self',
                'cmp'
        ) );
    }

    // 处理各级模块
    private function slot()
    {
        if($this->parse && ! isset ( $this->ci->parser )) $this->ci->load->library ( 'parser' );

        $slots = $this->slots;
        $this->sort ( $slots );
        // 将相同层级模块整合到同一数组中
        $tmp = array ();
        foreach ( $slots as $v )
        {
            $count = substr_count ( $v, '/' );
            $tmp[$count][] = $v;
        }
        unset ( $v );
        foreach ( $tmp as $k => &$v )
        {
            // if ($k == 0)
            // break;
            // 处理最后一级模块
            foreach ( $v as &$_v )
            {
                $path = explode ( '/', $_v );
                $var = $path[count ( $path ) - 1];
                $tmpvar = $this->merge ? array_merge ( $this->vars, (array)$this->slotvars[$_v] ) : $this->slotvars[$_v];
                $$var = $this->ci->load->view ( $this->files[$_v], $tmpvar, true );
                if($this->parse)
                {
                    $this->vars[$var] = $this->ci->parser->_parse ( $$var, empty ( $this->vars ) ? array () : ( array ) $this->vars, true );
                    // $this->vars[$var] = $this->ci->parser->_parse ( $$var, $tmpvar, true );
                } else {
                    $this->vars[$var] = $$var;
                }
                unset ( $tmpvar );
                // 去掉最后一级模块
                array_pop ( $path );
                $_path = implode ( '/', $path );
                // 并将剩余层级移至上一层级中
                if(! isset ( $tmp[$k - 1] ) || ! in_array ( $_path, $tmp[$k - 1] )) $tmp[$k - 1][] = $_path;
            }
            unset ( $tmp[$k] );
        }
    }

    static function cmp($a, $b)
    {
        $x = substr_count ( $a, '/' );
        $y = substr_count ( $b, '/' );
        if($x == $y) return 0;
        return ($x > $y) ? - 1 : 1;
    }

}