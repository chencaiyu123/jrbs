<?php

namespace Admin\Controller;
use Think\Controller;
class InvoiceController extends Controller {


    public function __construct(){
        parent::__construct();
    }

    public function invoiceList(){
        echo 'invoice list';
//        $this->display('formslist');
    }

    public function add(){
        echo 'invoice add';
//        $this->display('add');
    }

}