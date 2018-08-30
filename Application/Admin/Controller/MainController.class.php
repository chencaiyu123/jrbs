<?php

namespace Admin\Controller;
use Think\Controller;
class MainController extends Controller {


    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $this->display('index');
    }



}