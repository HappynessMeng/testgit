<?php
namespace Coct\Controller;
use Think\Controller;
class EmptyController extends Controller {

    public function _empty(){
       $this->display('Notice/404'); 
    }
    
    public function index(){
       $this->display('Notice/404'); 
    }

}