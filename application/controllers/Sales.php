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
                $data['saved']=$h->saved;
                foreach($this->super_model->select_row_where("sales_transaction_details","sales_id",$h->sales_id) AS $d){
                    $data['details'][]=array(
                        'sales_detail_id'=>$d->sales_detail_id,
                        'sales_id'=>$d->sales_id,
                        'short_name'=>$d->short_name,
                        'billing_id'=>$d->billing_id,
                        'company_name'=>$d->company_name,
                        'facility_type'=>$d->facility_type,
                        'wht_agent'=>$d->wht_agent,
                        'ith_tag'=>$d->ith_tag,
                        'non_vatable'=>$d->non_vatable,
                        'zero_rated'=>$d->zero_rated,
                        'vatable_sales'=>$d->vatable_sales,
                        'vat_on_sales'=>$d->vat_on_sales,
                        'zero_rated_sales'=>$d->zero_rated_sales,
                        'zero_rated_ecozones'=>$d->zero_rated_ecozones,
                        'ewt'=>$d->ewt,
                        'serial_no'=>$d->serial_no,
                        'total_amount'=>$d->total_amount,
                        'print_counter'=>$d->print_counter
                    );
                }
            }
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/upload_sales',$data);
        $this->load->view('template/footer');
    }

    public function count_print(){
        $sales_detail_id=$this->input->post('sales_details_id');
        foreach($this->super_model->select_row_where("sales_transaction_details","sales_detail_id",$sales_detail_id) AS $d){
            $new_count = $d->print_counter + 1;
            $data_head = array(
                'print_counter'=>$new_count
            );
            $this->super_model->update_where("sales_transaction_details",$data_head, "sales_detail_id", $sales_detail_id);
            echo $new_count;
        }
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
            "user_id"=>$_SESSION['user_id'],
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
                //echo $filename1;
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                     $this->readExcel_inv($sales_id);
                } 
            }
        }
    }

    public function readExcel_inv($sales_id){

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
          
            $itemno = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getOldCalculatedValue());
          
          
            $shortname = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue());
         
            $billing_id = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue());

            $company_name =trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getOldCalculatedValue());
            $fac_type = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue());
            $wht_agent = trim($objPHPExcel->getActiveSheet()->getCell('F'.$x)->getFormattedValue());
            $ith = trim($objPHPExcel->getActiveSheet()->getCell('G'.$x)->getFormattedValue());
            $non_vatable = trim($objPHPExcel->getActiveSheet()->getCell('H'.$x)->getFormattedValue());
            $zero_rated = trim($objPHPExcel->getActiveSheet()->getCell('I'.$x)->getFormattedValue());
            $vatable_sales = $objPHPExcel->getActiveSheet()->getCell('J'.$x)->getFormattedValue();
            $zero_rated_sales = $objPHPExcel->getActiveSheet()->getCell('K'.$x)->getFormattedValue();
            $zero_rated_ecozone = $objPHPExcel->getActiveSheet()->getCell('L'.$x)->getFormattedValue();
            $vat_on_sales = $objPHPExcel->getActiveSheet()->getCell('M'.$x)->getFormattedValue();
            $ewt = trim($objPHPExcel->getActiveSheet()->getCell('N'.$x)->getFormattedValue(),'()');
            $total_amount = $objPHPExcel->getActiveSheet()->getCell('O'.$x)->getFormattedValue();
         
                $data_sales = array(
                    'sales_id'=>$sales_id,
                    'short_name'=>$shortname,
                    'billing_id'=>$billing_id,
                    'company_name'=>$company_name,
                    'facility_type'=>$fac_type,
                    'wht_agent'=>$wht_agent,
                    'ith_tag'=>$ith,
                    'non_vatable'=>$non_vatable,
                    'zero_rated'=>$zero_rated,
                    'vatable_sales'=>$vatable_sales,
                    'vat_on_sales'=>$vat_on_sales,
                    'zero_rated_sales'=>$zero_rated_sales,
                    'zero_rated_ecozones'=>$zero_rated_ecozone,
                    'ewt'=>$ewt,
                    'total_amount'=>$total_amount,
                );
                $this->super_model->insert_into("sales_transaction_details", $data_sales);
        }
            echo $sales_id;
      
    }

    public function cancel_sales(){
        $sales_id = $this->input->post('sales_id');
        $this->super_model->delete_where("sales_transaction_details", "sales_id", $sales_id);
        $this->super_model->delete_where("sales_transaction_head", "sales_id", $sales_id);
    }

    public function save_all(){
        $sales_id = $this->input->post('sales_id');
        $data_head = array(
            'saved'=>1
        );
        $this->super_model->update_where("sales_transaction_head",$data_head, "sales_id", $sales_id);
        echo $sales_id;
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

    public function sales_wesm(){
        $ref_no=$this->uri->segment(3);
        $data['ref_no']=$ref_no;
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details sd INNER JOIN sales_transaction_head sh ON sd.sales_id=sh.sales_id WHERE saved='1' AND reference_number LIKE '%$ref_no%'") AS $d){
            $data['details'][]=array(
                'sales_detail_id'=>$d->sales_detail_id,
                'sales_id'=>$d->sales_id,
                'short_name'=>$d->short_name,
                'billing_id'=>$d->billing_id,
                'company_name'=>$d->company_name,
                'facility_type'=>$d->facility_type,
                'wht_agent'=>$d->wht_agent,
                'ith_tag'=>$d->ith_tag,
                'non_vatable'=>$d->non_vatable,
                'zero_rated'=>$d->zero_rated,
                'vatable_sales'=>$d->vatable_sales,
                'vat_on_sales'=>$d->vat_on_sales,
                'zero_rated_sales'=>$d->zero_rated_sales,
                'zero_rated_ecozones'=>$d->zero_rated_ecozones,
                'ewt'=>$d->ewt,
                'serial_no'=>$d->serial_no,
                'total_amount'=>$d->total_amount,
                'reference_number'=>$d->reference_number,
                'transaction_date'=>$d->transaction_date,
                'billing_from'=>$d->billing_from,
                'billing_to'=>$d->billing_to,
                'due_date'=>$d->due_date,
                'print_counter'=>$d->print_counter
            );
        }
        $this->load->view('sales/sales_wesm',$data);
        $this->load->view('template/footer');
    }

    public function add_details_BS(){
        $sales_detail_id = $this->uri->segment(3);
        $data['sales_detail_id']=$sales_detail_id;
        $this->load->view('template/header');
        $this->load->view('sales/add_details_BS',$data);
        $this->load->view('template/footer');
    }

    public function save_serialno(){
        $sales_detail_id = $this->input->post('sales_detail_id');
        $serial_no = $this->input->post('serial_no');
        $data_head = array(
            'serial_no'=>$serial_no
        );
        $this->super_model->update_where("sales_transaction_details",$data_head, "sales_detail_id", $sales_detail_id);
        echo $sales_detail_id;
    }

    public function convertNumber(float $amount)
    {


           $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;

   // Check if there is any number after decimal

   $amt_hundred = null;

   $count_length = strlen($num);

   $x = 0;

   $string = array();

   $change_words = array(0 => '', 1 => 'One', 2 => 'Two',

     3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',

     7 => 'Seven', 8 => 'Eight', 9 => 'Nine',

     10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',

     13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',

     16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',

     19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',

     40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',

     70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');

  $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');

  while( $x < $count_length ) {

       $get_divider = ($x == 2) ? 10 : 100;

       $amount = floor($num % $get_divider);

       $num = floor($num / $get_divider);

       $x += $get_divider == 10 ? 1 : 2;

       if ($amount) {

         $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;

         $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;

         $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 

         '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 

         '.$here_digits[$counter].$add_plural.' '.$amt_hundred;

         }else $string[] = null;

       }

   $implode_to_Rupees = implode('', array_reverse($string));

   $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 

   " . $change_words[$amount_after_decimal % 10]) . ' centavos' : '';

   return ($implode_to_Rupees ? $implode_to_Rupees . 'pesos ' : '') . $get_paise;




         $ones = array( 
            0 => "zero", 
            1 => "one", 
            2 => "two", 
            3 => "three", 
            4 => "four", 
            5 => "five", 
            6 => "six", 
            7 => "seven", 
            8 => "eight", 
            9 => "nine", 
            10 => "ten", 
            11 => "eleven", 
            12 => "twelve", 
            13 => "thirteen", 
            14 => "fourteen", 
            15 => "fifteen", 
            16 => "sixteen", 
            17 => "seventeen", 
            18 => "eighteen", 
            19 => "nineteen" 
            ); 
            $tens = array( 
            1 => "ten",
            2 => "twenty", 
            3 => "thirty", 
            4 => "forty", 
            5 => "fifty", 
            6 => "sixty", 
            7 => "seventy", 
            8 => "eighty", 
            9 => "ninety" 
            ); 
            $hundreds = array( 
            "hundred", 
            "thousand", 
            "million", 
            "billion", 
            "trillion", 
            "quadrillion" 
            ); //limit t quadrillion 
            $num = number_format($num,2,".",","); 
            $num_arr = explode(".",$num); 
            $wholenum = $num_arr[0]; 
            $decnum = $num_arr[1]; 
            $whole_arr = array_reverse(explode(",",$wholenum)); 
            krsort($whole_arr); 
            $rettxt = ""; 
            foreach($whole_arr as $key => $i){ 
            if($i < 20){ 
            $rettxt .= $ones[$i]; 
            }elseif($i < 100){ 
            $rettxt .= $tens[substr($i,0,1)]; 
            $rettxt .= " ".$ones[substr($i,1,1)]; 
            }else{ 
            $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
            $rettxt .= " ".$tens[substr($i,1,1)] ; 
            $rettxt .= " ".$ones[substr($i,2,1)]; 
            } 
            if($key > 0){ 
            $rettxt .= " ".$hundreds[$key]. " "; 
            } 
            } 
            if($decnum > 0){ 
            $rettxt .= " and "; 
            if($decnum < 20){ 
            $rettxt .= $ones[$decnum] . " centavos"; 
            }elseif($decnum < 100){ 
            $rettxt .= $tens[substr($decnum,0,1)]; 
            $rettxt .= " ".$ones[substr($decnum,1,1)] . " centavos";  
            } 
            } 

            if (strpos($rettxt, 'centavos') !== false) {
                $rettxt=$rettxt;
            } else {
                $rettxt = $rettxt." PESOS ONLY";
            }

            return $rettxt; 
    }

    public function print_BS(){
        error_reporting(0);
        $sales_detail_id = $this->uri->segment(3);
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        foreach($this->super_model->select_row_where("sales_transaction_details","sales_detail_id",$sales_detail_id) AS $p){
            $data['address']=$this->super_model->select_column_where("participant","office_address","billing_id",$p->billing_id);
            $data['tin']=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
            $data['company_name']=$p->company_name;
            $data['billing_from']=$this->super_model->select_column_where("sales_transaction_head","billing_from","sales_id",$p->sales_id);
            $data['billing_to']=$this->super_model->select_column_where("sales_transaction_head","billing_to","sales_id",$p->sales_id);
            $ewt=str_replace("-", '', $p->ewt);
            $ewt_exp=explode(".", $ewt);
            $data['ewt_peso']=$ewt_exp[0];
            $data['ewt_cents']=$ewt_exp[1];
            $zero_rated_ecozones_exp=explode(".", $p->zero_rated_ecozones);
            $data['zero_rated_ecozones_peso']=$zero_rated_ecozones_exp[0];
            $data['zero_rated_ecozones_cents']=$zero_rated_ecozones_exp[1];
            $total=$p->zero_rated_ecozones - $ewt;
            $data['total_amount']=$total;
            $data['amount_words']=strtoupper($this->convertNumber($total));
            $total_exp=explode(".", $total);
            $data['total_peso']=$total_exp[0];
            $data['total_cents']=$total_exp[1];
        }
        $this->load->view('sales/print_BS',$data);
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