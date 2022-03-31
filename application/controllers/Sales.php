<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        date_default_timezone_set("Asia/Manila");
        $this->load->model('super_model');
        $this->load->database();
 
       
        function arrayToObject($array){
            if(!is_array($array)) { return $array; }
            $object = new stdClass();
            if (is_array($array) && count($array) > 0) {
                foreach ($array as $name=>$value) {
                    $name = strtolower(trim($name));
                    if (!empty($name)) { $object->$name = arrayToObject($value); }
                }
                return $object;
            } 
            else {
                return false;
            }
        }
    } 

    public function upload_sales()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/upload_sales');
        $this->load->view('template/footer');
    }

    public function collection_list()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/collection_list');
        $this->load->view('template/footer');
    }

    public function print_OR()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/print_OR');
        $this->load->view('template/footer');
    }

    public function print_BS()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/print_BS');
        $this->load->view('template/footer');
    }

    public function sales_wesm()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/sales_wesm');
        $this->load->view('template/footer');
    }

    public function add_details()
    {
        $this->load->view('template/header');
        $this->load->view('sales/add_details');
        $this->load->view('template/footer');
    }
    
}