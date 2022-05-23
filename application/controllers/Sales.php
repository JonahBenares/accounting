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
        $id=$this->uri->segment(3);
        $data['sales_id'] = $id;

        if(!empty($id)){
            foreach($this->super_model->select_row_where("sales_transaction_head", "sales_id",$id) AS $h){
                $data['transaction_date']=$h->transaction_date;
                $data['billing_from']=$h->billing_from;
                $data['billing_to']=$h->billing_to;
                $data['reference_number']=$h->reference_number;
                $data['due_date']=$h->due_date;
            }
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/upload_sales',$data);
        $this->load->view('template/footer');
    }

    public function add_sales_head(){
        $tdate=date("Y-m-d", strtotime($this->input->post('transaction_date')));
        $billingf=date("Y-m-d", strtotime($this->input->post('billing_from')));
        $billingt=date("Y-m-d", strtotime($this->input->post('billing_to')));
        $due=date("Y-m-d", strtotime($this->input->post('due_date')));
        $data=array(
            "reference_number"=>$this->input->post('reference_number'),
            "transaction_date"=>$tdate,
            "billing_from"=>$billingf,
            "billing_to"=>$billingt,
            "due_date"=>$due,
            "user_id"=>1,
            "create_date"=>date("Y-m-d H:i:s")
        );
        $sales_id = $this->super_model->insert_return_id("sales_transaction_head",$data);

        echo $sales_id;
    }

    public function upload_sales_process(){
        $sales_id = $this->input->post('sales_id');
          $dest= realpath(APPPATH . '../uploads/excel/');
         $error_ext=0;
        if(!empty($_FILES['doc']['name'])){
            $exc= basename($_FILES['doc']['name']);
            $exc=explode('.',$exc);
            $ext1=$exc[1];
            if($ext1=='php' || $ext1!='xlsx'){
                $error_ext++;
            } 
            else {
                $filename1='wesm_sales.'.$ext1;
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                     $this->readExcel_inv();
                }        
            }
        }
    }

    public function readExcel_inv(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $inputFileName =realpath(APPPATH.'../uploads/excel/wesm_sales.xlsx');
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } 
        catch(Exception $e) {
            die('Error loading file"'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        $objPHPExcel->setActiveSheetIndex(2);
        $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 
        for($x=3;$x<$highestRow;$x++){
            $shortname = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getValue());
            $billing_id = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getValue());
            $company_name = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getValue());
            $fac_type = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getValue());
            $wht_agent = trim($objPHPExcel->getActiveSheet()->getCell('F'.$x)->getValue());
            $ith = trim($objPHPExcel->getActiveSheet()->getCell('G'.$x)->getValue());
            $non_vatable = trim($objPHPExcel->getActiveSheet()->getCell('H'.$x)->getValue());
            $zero_rated = trim($objPHPExcel->getActiveSheet()->getCell('I'.$x)->getValue());
            $vatable_sales = $objPHPExcel->getActiveSheet()->getCell('J'.$x)->getValue();
            $zero_rated_sales = $objPHPExcel->getActiveSheet()->getCell('K'.$x)->getValue();
            $zero_rated_ecozone = $objPHPExcel->getActiveSheet()->getCell('L'.$x)->getValue();
            $vat_on_sales = $objPHPExcel->getActiveSheet()->getCell('M'.$x)->getValue();
            $ewt = trim($objPHPExcel->getActiveSheet()->getCell('N'.$x)->getValue(),'()');
         
            
                $data_items = array(
                    'sales_id'=>$desc,
                    'short_name'=>$cat_id,
                    'billing_id'=>$subcat_id,
                    'company_name'=>$unit,
                    'facility_type'=>$orig_pn,
                    'wht_agent'=>$rack_id,
                    'ith_tag'=>$group_id,
                    'non_vatable'=>$wh_id,
                    'zero_rated'=>$location_id,
                    'vatable_sales'=>$location_id,
                    'zero_rated_sales'=>$location_id,
                    'zero_rated_ecozones'=>$location_id,
                    'zero_rated'=>$location_id,
                );
                //print_r($data_items);//
                $this->super_model->insert_into("items", $data_items);
            
          
        }
        echo "<script>alert('Successfully uploaded!'); window.location = 'import_items';</script>";
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

    public function add_details_BS()
    {
        $this->load->view('template/header');
        $this->load->view('sales/add_details_BS');
        $this->load->view('template/footer');
    }

    public function add_details_OR()
    {
        $this->load->view('template/header');
        $this->load->view('sales/add_details_OR');
        $this->load->view('template/footer');
    }

    public function add_details_wesm()
    {
        $this->load->view('template/header');
        $this->load->view('sales/add_details_wesm');
        $this->load->view('template/footer');
    }
    
}