<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require FCPATH.'vendor\autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as writerxlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as readerxlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing as drawing; // Instead PHPExcel_Worksheet_Drawing
use PhpOffice\PhpSpreadsheet\Style\Alignment as alignment; // Instead alignment
use PhpOffice\PhpSpreadsheet\Style\Border as border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat as numberformat;
use PhpOffice\PhpSpreadsheet\Style\Fill as fill; // Instead fill
use PhpOffice\PhpSpreadsheet\Style\Color as color; //Instead PHPExcel_Style_Color
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup as pagesetup; // Instead PHPExcel_Worksheet_PageSetup
use PhpOffice\PhpSpreadsheet\IOFactory as io_factory; // Instead PHPExcel_IOFactory
class Reserve extends CI_Controller {

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

    public function upload_reserve(){
        $reserve_id=$this->uri->segment(3);
        $data['reserve_id'] = $reserve_id;
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        if(!empty($reserve_id)){
            foreach($this->super_model->select_row_where("reserve_transaction_head", "reserve_id",$reserve_id) AS $h){
                $data['transaction_date']=$h->transaction_date;
                $data['billing_from']=$h->billing_from;
                $data['billing_to']=$h->billing_to;
                $data['reference_number']=$h->reference_number;
                $data['due_date']=$h->due_date;
                $data['saved']=$h->saved;
                $data['adjustment']=$h->adjustment;
                foreach($this->super_model->select_row_where("reserve_transaction_details","reserve_id",$h->reserve_id) AS $d){
                    $unique_bill_id = $this->super_model->select_column_custom_where("reserve_participant", "res_billing_id", "res_actual_billing_id = '$d->billing_id' AND res_settlement_id = '$d->short_name'");
                    $data['details'][]=array(
                        'reserve_detail_id'=>$d->reserve_detail_id,
                        'reserve_id'=>$d->reserve_id,
                        'item_no'=>$d->item_no,
                        'short_name'=>$d->short_name,
                        'actual_billing_id'=>$d->billing_id,
                        'billing_id'=>$unique_bill_id,
                        'facility_type'=>$d->facility_type,
                        'wht_agent'=>$d->wht_agent,
                        'ith_tag'=>$d->ith_tag,
                        'non_vatable'=>$d->non_vatable,
                        'zero_rated'=>$d->zero_rated,
                        'vatables_purchases'=>$d->vatables_purchases,
                        'vat_on_purchases'=>$d->vat_on_purchases,
                        'zero_rated_purchases'=>$d->zero_rated_purchases,
                        'zero_rated_ecozones'=>$d->zero_rated_ecozones,
                        'ewt'=>$d->ewt,
                        'serial_no'=>$d->serial_no,
                        'total_amount'=>$d->total_amount,
                        'print_counter'=>$d->print_counter,
                        'or_no'=>$d->or_no,
                        'total_update'=>$d->total_update,
                        'original_copy'=>$d->original_copy,
                        'scanned_copy'=>$d->scanned_copy,
                    );
                }
            }
        }
        // echo $purchase_id;
        $this->load->view('reserve/upload_reserve',$data);
        $this->load->view('template/footer');
    }

    public function count_print(){
        $reserve_detail_id=$this->input->post('reserve_detail_id');
        foreach($this->super_model->select_row_where("reserve_transaction_details","reserve_detail_id",$reserve_detail_id) AS $d){
            $new_count = $d->print_counter + 1;
            $data_head = array(
                'print_counter'=>$new_count
            );
            $this->super_model->update_where("reserve_transaction_details",$data_head, "reserve_detail_id", $reserve_detail_id);
            echo $new_count;
        }
    }

    public function add_reserve_head(){
        $tdate=date("Y-m-d", strtotime($this->input->post('transaction_date')));
        $billingf=date("Y-m-d", strtotime($this->input->post('billing_from')));
        $billingt=date("Y-m-d", strtotime($this->input->post('billing_to')));
        $due=date("Y-m-d", strtotime($this->input->post('due_date')));
        if(!empty($this->input->post('adjustment'))){
            $adjustment=$this->input->post('adjustment');
        }else{
            $adjustment=0;
        }
        $data=array(
            "reference_number"=>$this->input->post('reference_number'),
            "transaction_date"=>$tdate,
            "billing_from"=>$billingf,
            "billing_to"=>$billingt,
            "due_date"=>$due,
            "user_id"=>$_SESSION['user_id'],
            "create_date"=>date("Y-m-d H:i:s"),
            "adjustment"=>$adjustment
        );
        $reserve_id = $this->super_model->insert_return_id("reserve_transaction_head",$data);
        echo $reserve_id;
    }

    public function cancel_reserve(){
        $reserve_id = $this->input->post('reserve_id');
        $this->super_model->delete_where("reserve_transaction_head", "reserve_id", $reserve_id);
        $this->super_model->delete_where("reserve_transaction_details", "reserve_id", $reserve_id);
    }

     public function save_all(){
        $reserve_id = $this->input->post('reserve_id');
        $data_head = array(
            'saved'=>1
        );
        $this->super_model->update_where("reserve_transaction_head",$data_head, "reserve_id", $reserve_id);
        echo $reserve_id;
    }

    public function upload_reserve_process(){
        $reserve_id = $this->input->post('reserve_id');
        $dest= realpath(APPPATH . '../uploads/excel/');
        $error_ext=0;
        if(!empty($_FILES['doc']['name'])){
            $exc= basename($_FILES['doc']['name']);
            $exc=explode('.',$exc);
            $ext1=$exc[1];
            if($ext1=='php' || $ext1!='xlsx'){
                $error_ext++;
            }else {
                $filename1='wesm_reserve.'.$ext1;
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                     $this->readExcel_inv($reserve_id);
                } 
            }
        }
    }

    public function readExcel_inv($reserve_id){
        $objPHPExcel = new Spreadsheet();
        $inputFileName =realpath(APPPATH.'../uploads/excel/wesm_reserve.xlsx');
        try {
            $inputFileType = io_factory::identify($inputFileName);
            $objReader = io_factory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } 
        catch(Exception $e) {
            die('Error loading file"'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        $objPHPExcel->setActiveSheetIndex(2);
        $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 
        $highestRow = $highestRow;
        $y=1;
        for($x=3;$x<=$highestRow;$x++){
            $itemno = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue() ?? '');
            $shortname = str_replace(' ','',$objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue() ?? '');
            if($shortname!="" || !empty($shortname)){ 
                $actual_billing_id = str_replace(' ','',$objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue());  
                $company_name = $objPHPExcel->getActiveSheet()->getCell('D'.$x)->getOldCalculatedValue();   
                $unique_bill_id = $this->super_model->select_column_custom_where("reserve_participant", "res_billing_id", "res_actual_billing_id = '$actual_billing_id' AND res_settlement_id = '$shortname'");
                $tin = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue() ?? '');
                $fac_type = trim($objPHPExcel->getActiveSheet()->getCell('F'.$x)->getFormattedValue() ?? '');
                $wht_agent = trim($objPHPExcel->getActiveSheet()->getCell('G'.$x)->getFormattedValue() ?? '');
                $ith = trim($objPHPExcel->getActiveSheet()->getCell('H'.$x)->getFormattedValue() ?? '');
                $non_vatable = trim($objPHPExcel->getActiveSheet()->getCell('I'.$x)->getFormattedValue() ?? '');
                $zero_rated = trim($objPHPExcel->getActiveSheet()->getCell('J'.$x)->getFormattedValue() ?? '');
                $vatables_purchases = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('K'.$x)->getFormattedValue());
                $zero_rated_purchases = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('L'.$x)->getFormattedValue());
                $zero_rated_ecozone = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('M'.$x)->getFormattedValue());
                $vat_on_purchases = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('N'.$x)->getFormattedValue());
                $ewt = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('O'.$x)->getFormattedValue());
                // $total_amount = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('P'.$x)->getOldCalculatedValue());
                $total_amount = ($vatables_purchases+$zero_rated_purchases+$zero_rated_ecozone+$vat_on_purchases)-$ewt;
                $data_reserve = array(
                    'reserve_id'=>$reserve_id,
                    'item_no'=>$y,
                    'short_name'=>$shortname,
                    'billing_id'=>$unique_bill_id,
                    'actual_billing_id'=>$actual_billing_id,
                    'facility_type'=>$fac_type,
                    'wht_agent'=>$wht_agent,
                    'ith_tag'=>$ith,
                    'non_vatable'=>$non_vatable,
                    'zero_rated'=>$zero_rated,
                    'vatables_purchases'=>$vatables_purchases,
                    'vat_on_purchases'=>$vat_on_purchases,
                    'zero_rated_purchases'=>$zero_rated_purchases,
                    'zero_rated_ecozones'=>$zero_rated_ecozone,
                    'ewt'=>$ewt,
                    'total_amount'=>$total_amount,
                    'balance'=>$total_amount,
                    'company_name'=>$company_name,
                    'tin'=>$tin,
                );
                $this->super_model->insert_into("reserve_transaction_details", $data_reserve);
                $y++;
            }
        }
           
      
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

   /*$get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 

   " . $change_words[$amount_after_decimal % 10]) . ' centavos' : '';*/

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
            );

    if($amount_after_decimal > 0){
    $Dn = floor($amount_after_decimal / 10);
    /* Tens (deca) */
    $n = $amount_after_decimal % 10;
            /* Ones */
                
                if ($Dn || $n) {
        if (!empty($res)) {
            $res .= " And ";
        }
        if ($Dn < 2) {
            $res .= $ones[$Dn * 10 + $n];
        } else {
            $res .= $tens[$Dn];
            if ($n) {
                $res .= "-" . $ones[$n];
            }
        }
                    $res .= " centavos";
    }
            
            }

   $get_peso = ($amount == 1) ? 'peso ' : 'pesos ';

   return ($implode_to_Rupees ? $implode_to_Rupees .''.$get_peso : '') .'And '. $res;




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
            if($decnum == 1){ 
            $rettxt .= $ones[$decnum] . " centavos";
            if($decnum < 20){ 
            $rettxt .= $ones[$decnum] . " centavos"; 
            }elseif($decnum < 100){ 
            $rettxt .= $tens[substr($decnum,0,1)]; 
            $rettxt .= " ".$ones[substr($decnum,1,1)] . " centavos";  
            } 
            }
            } 

            if (strpos($rettxt, 'centavos') !== false) {
                $rettxt=$rettxt;
            } else {
                $rettxt = $rettxt." PESOS ONLY";
            }

            return $rettxt; 
    }

    public function payment_reserve_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $due_date=$this->uri->segment(3);
        $data['due']=$due_date;
        $ref_no=$this->uri->segment(4);
        $data['ref_no']=$ref_no;
        $data['reserve_id'] =$this->super_model->select_column_where("reserve_transaction_head","reserve_id","reference_number",$ref_no);
        $data['head'] = $this->super_model->custom_query("SELECT DISTINCT reference_number,pth.reserve_id,pth.due_date FROM reserve_transaction_details ptd INNER JOIN reserve_transaction_head pth ON ptd.reserve_id=pth.reserve_id WHERE reference_number!='' AND balance!='0' AND due_date='$due_date' AND saved='1'");
        $data['due_date']=$this->super_model->custom_query("SELECT * FROM reserve_transaction_head WHERE reserve_id NOT IN (SELECT reserve_id FROM payment_reserve_head) GROUP BY due_date");
        foreach($this->super_model->custom_query("SELECT * FROM reserve_transaction_details pd INNER JOIN reserve_transaction_head ph ON pd.reserve_id=ph.reserve_id WHERE saved='1' AND reference_number LIKE '%$ref_no%'") AS $d){
            $company_name=$this->super_model->select_column_where("reserve_participant","res_participant_name","res_billing_id",$d->billing_id);
            $data['details'][]=array(
                'reserve_detail_id'=>$d->reserve_detail_id,
                'reserve_id'=>$d->reserve_id,
                'company_name'=>$company_name,
                'short_name'=>$d->short_name,
                'billing_id'=>$d->billing_id,
                'facility_type'=>$d->facility_type,
                'wht_agent'=>$d->wht_agent,
                'ith_tag'=>$d->ith_tag,
                'non_vatable'=>$d->non_vatable,
                'zero_rated'=>$d->zero_rated,
                'vatables_purchases'=>$d->vatables_purchases,
                'vat_on_purchases'=>$d->vat_on_purchases,
                'zero_rated_purchases'=>$d->zero_rated_purchases,
                'zero_rated_ecozones'=>$d->zero_rated_ecozones,
                'ewt'=>$d->ewt,
                'serial_no'=>$d->serial_no,
                'total_amount'=>$d->total_amount,
                'balance'=>$d->balance,
                'reference_number'=>$d->reference_number,
                'transaction_date'=>$d->transaction_date,
                'billing_from'=>$d->billing_from,
                'billing_to'=>$d->billing_to,
                'due_date'=>$d->due_date,
                'print_counter'=>$d->print_counter
            );
        }
        $this->load->view('reserve/payment_reserve_list',$data);
        $this->load->view('template/footer');
    }

    public function getpayment(){
        $reference_number=$this->input->post('reference_number');
        $ref_exp=explode(".",$reference_number);
        $reserve_id=$ref_exp[0];
        $ref_no=$ref_exp[1];
        $total_amount= $this->super_model->select_sum("reserve_transaction_details", "balance", "reserve_id", $reserve_id);
        $total_purchase= $this->super_model->select_sum("reserve_transaction_details", "vatables_purchases", "reserve_id", $reserve_id);
        $zero_rated= $this->super_model->select_sum("reserve_transaction_details", "zero_rated_purchases", "reserve_id", $reserve_id);
        $ecozone= $this->super_model->select_sum("reserve_transaction_details", "zero_rated_ecozones", "reserve_id", $reserve_id);
        $total_vatable_purchase = $total_purchase + $zero_rated + $ecozone;
        $total_vat= $this->super_model->select_sum("reserve_transaction_details", "vat_on_purchases", "reserve_id", $reserve_id);
        $total_ewt= $this->super_model->select_sum("reserve_transaction_details", "ewt", "reserve_id", $reserve_id);
        $data['list'] = array(
            'reserve_id'=>$reserve_id,
            'reference_number'=>$ref_no,
            'total_amount'=>$total_amount,
            'total_vatable_purchase'=>$total_vatable_purchase,
            'total_vat'=>$total_vat,
            'total_ewt'=>$total_ewt,
            'count'=>$this->input->post('count'),
        );
            
        $this->load->view('reserve/row_reserve_payment',$data);
    }

    public function paid_reserve_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $ref_no=$this->uri->segment(3);
        $participant=$this->uri->segment(4);
        $due_date=$this->uri->segment(5);
        $data['ref_no']=$ref_no;
        $data['head'] = $this->super_model->custom_query("SELECT DISTINCT reference_number FROM reserve_transaction_head WHERE reference_number!=''");
        $data['date'] = $this->super_model->custom_query("SELECT DISTINCT due_date FROM reserve_transaction_head WHERE due_date!=''");
        $data['participant']=$this->super_model->custom_query("SELECT * FROM reserve_participant GROUP BY res_billing_id");
        // $data['participant']=$this->super_model->custom_query("SELECT * FROM reserve_participant GROUP BY res_settlement_id");
        $sql="";
        if($participant!='null'){
            $sql.= "pd.short_name = '$participant' AND ";
        } 
        if($ref_no!='null'){
            $sql.= "pth.reference_number = '$ref_no' AND ";
        }
        if($due_date!='null'){
            $sql.= "pth.due_date = '$due_date' AND ";
        }
        $query=substr($sql,0,-4);
        $qu = " WHERE saved='1' AND ".$query;
        foreach($this->super_model->custom_query("SELECT pd.reserve_details_id,ph.reserve_id,ph.payment_date,ph.payment_mode,pd.purchase_mode,pd.purchase_amount,pd.vat,pd.ewt,pd.total_amount,ptd.company_name,pth.create_date FROM payment_reserve_head ph INNER JOIN payment_reserve_details pd ON ph.reserve_payment_id=pd.reserve_payment_id INNER JOIN reserve_transaction_head pth ON ph.reserve_id=pth.reserve_id INNER JOIN reserve_transaction_details ptd ON pd.reserve_details_id=ptd.reserve_detail_id $qu") AS $d){
            $billing_id=$this->super_model->select_column_where("reserve_transaction_details","billing_id","reserve_detail_id",$d->reserve_details_id);
            $company_name=$this->super_model->select_column_where("reserve_participant","res_participant_name","res_billing_id",$billing_id);
            $data['details'][]=array(
                'reserve_id'=>$d->reserve_id,
                'payment_date'=>$d->payment_date,
                //'company_name'=>$company_name,
                'company_name'=>(!empty($d->company_name)) ? $d->company_name : $company_name,
                'payment_mode'=>$d->payment_mode,
                'purchase_mode'=>$d->purchase_mode,
                'purchase_amount'=>$d->purchase_amount,
                'vat'=>$d->vat,
                'ewt'=>$d->ewt,
                'total_amount'=>$d->total_amount,
            );
        }
        $this->load->view('reserve/paid_reserve_list',$data);
        $this->load->view('template/footer');
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function save_payment_all(){
        $reserve_id=$this->input->post('reserve_id');
        $counter=$this->input->post('counter');
        $payment_date=$this->input->post('payment_date');
        $particulars=$this->input->post('particulars');
        $total_vatable_purchase=$this->input->post('total_vatable_purchase');
        $total_vat=$this->input->post('total_vat');
        $total_ewt=$this->input->post('total_ewt');
        $total_amount=$this->input->post('total_amount');
        $payment_mode=$this->input->post('customRadioInline1');
        $check_no=$this->input->post('check_no');
        $cv_no=$this->input->post('cv_no');
        $check_date=$this->input->post('check_date');
        $pcv=$this->input->post('pcv');
        $payment_identifier=$this->generateRandomString();
        for($a=0;$a<$counter;$a++){
            $data_insert=array(
                'reserve_id'=>$reserve_id[$a],
                'payment_date'=>$payment_date,
                'particulars'=>$particulars,
                'payment_identifier'=>$payment_identifier,
                'total_purchase'=>$total_vatable_purchase[$a],
                'total_vat'=>$total_vat[$a],
                'total_ewt'=>$total_ewt[$a],
                'total_amount'=>$total_amount[$a],
                'payment_mode'=>$payment_mode,
                'pcv'=>$pcv,
                'check_no'=>$check_no,
                'cv_no'=>$cv_no,
                'check_date'=>$check_date,
                'create_date'=>date("Y-m-d h:i:s"),
                'user_id'=>$_SESSION['user_id'],
            );
            $reserve_payment_id = $this->super_model->insert_return_id("payment_reserve_head", $data_insert);
            foreach($this->super_model->select_custom_where("reserve_transaction_details", "reserve_id= '".$reserve_id[$a]."' AND balance != '0'") AS $det ){
                if($det->vatables_purchases!=0){
                    $mode= "Vatable Purchase";
                    $amount = $det->vatables_purchases;
                } else if($det->zero_rated_purchases!=0){
                    $mode = "Zero Rated Purchase";
                    $amount = $det->zero_rated_purchases;
                } else if($det->zero_rated_ecozones!=0){
                    $mode = "Zero Rated Ecozones";
                    $amount = $det->zero_rated_ecozones;
                }
                $data_details = array(
                    'reserve_payment_id'=>$reserve_payment_id,
                    'reserve_details_id'=>$det->reserve_detail_id,
                    'short_name'=>$det->short_name,
                    'purchase_mode'=>$mode,
                    'purchase_amount'=>$amount,
                    'vat'=>$det->vat_on_purchases,
                    'ewt'=>$det->ewt,
                    'total_amount'=>$det->balance,
                );
                $this->super_model->insert_into("payment_reserve_details", $data_details);
                $new_balance = $det->balance - $det->total_amount;
                $update=array(
                    'balance'=>$new_balance
                );
                $this->super_model->update_where("reserve_transaction_details", $update, "reserve_detail_id", $det->reserve_detail_id);
            }
        }
        echo $payment_identifier;
    }

    public function reserve_wesm(){
        $ref_no=$this->uri->segment(3);
        $due_date=$this->uri->segment(4);
        $or_no=$this->uri->segment(5);
        $original_copy=$this->uri->segment(6);
        $scanned_copy=$this->uri->segment(7);
        $billfrom=$this->uri->segment(8);
        $billto=$this->uri->segment(9);
        $participants=$this->uri->segment(10);
        $ors=str_replace("%5E","",$or_no ?? '');
        $data['or_nos']=$or_no;
        $data['original_copy']=$original_copy;
        $data['scanned_copy']=$scanned_copy;
        $data['ref_no']=$ref_no;
        $data['due_date']=$due_date;
        $data['billfrom']=$billfrom;
        $data['billto']=$billto;
        $data['participants']=$participants;
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT reference_number FROM reserve_transaction_head WHERE reference_number!='' AND adjustment='0'");
        $data['date'] = $this->super_model->custom_query("SELECT DISTINCT due_date FROM reserve_transaction_head WHERE due_date!='' AND adjustment='0'");
        $data['participant']=$this->super_model->custom_query("SELECT * FROM reserve_participant WHERE res_participant_name != '' GROUP BY res_tin ORDER BY res_participant_name");
        $data['participant_name']=$this->super_model->select_column_where('reserve_participant','res_participant_name','res_tin',$participants);
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $sql='';
        if($ref_no!='null'){
            $sql.= "ph.reference_number = '$ref_no' AND ";
        }

        if($due_date!='null'){
            $sql.= "ph.due_date = '$due_date' AND ";
        }

        if($billfrom!='null' && $billto!='null'){ 
            $sql.= " ((ph.billing_from BETWEEN '$billfrom' AND '$billto') OR (ph.billing_to BETWEEN '$billfrom' AND '$billto'))  AND ";
        }

        if(!empty($participants) && $participants!='null'){
            //$sql.= " tin = '$participant' AND "; 
           $par=array();
           foreach($this->super_model->select_custom_where('reserve_participant',"res_tin='$participants'") AS $p){
               $par[]="'".$p->res_settlement_id."'";
           }
           $imp=implode(',',$par);
           $sql.= " pd.short_name IN($imp) AND ";
        }

        if($or_no!='null' && !empty($or_no) && $or_no!="%5E"){
            $sql.= "pd.or_no = '$ors' AND ";
        }else if($or_no=="%5E"){
            $sql.= "(pd.or_no='' OR pd.or_no IS NULL) AND ";
        }

        if($original_copy!='null' && isset($original_copy)){
            $sql.= "pd.original_copy = '$original_copy' AND ";
        }

        if($scanned_copy!='null' && isset($scanned_copy)){
            $sql.= "pd.scanned_copy = '$scanned_copy' AND ";
        }
        $query=substr($sql,0,-4);
        $qu = " WHERE adjustment='0' AND saved='1' AND ".$query;
        foreach($this->super_model->custom_query("SELECT * FROM reserve_transaction_details pd INNER JOIN reserve_transaction_head ph ON pd.reserve_id=ph.reserve_id $qu") AS $d){
            $data['or_no'] = $this->super_model->custom_query("SELECT DISTINCT ptd.or_no FROM reserve_transaction_head pth INNER JOIN reserve_transaction_details ptd  WHERE pth.reference_number='$d->reference_number' AND ptd.reserve_id='$d->reserve_id' AND saved='1' AND adjustment='0' ORDER BY or_no ASC");
            $create_date = $this->super_model->select_column_where("reserve_transaction_head", "create_date", "reserve_id", $d->reserve_id);
            $company_name=$this->super_model->select_column_where("reserve_transaction_details", "company_name", "reserve_detail_id", $d->reserve_detail_id);
            if(!empty($company_name)){
                $comp_name=$company_name;
            }else{
                $comp_name=$this->super_model->select_column_where("reserve_participant", "res_participant_name", "res_billing_id", $d->billing_id);
            }
            $data['details'][]=array(
                'reserve_detail_id'=>$d->reserve_detail_id,
                'reserve_id'=>$d->reserve_id,
                'item_no'=>$d->item_no,
                'company_name'=>$comp_name,
                'billing_from'=>$d->billing_from,
                'billing_to'=>$d->billing_to,
                'reference_number'=>$d->reference_number,
                'short_name'=>$d->short_name,
                'billing_id'=>$d->billing_id,
                'actual_billing_id'=>$d->actual_billing_id,
                'facility_type'=>$d->facility_type,
                'wht_agent'=>$d->wht_agent,
                'ith_tag'=>$d->ith_tag,
                'non_vatable'=>$d->non_vatable,
                'zero_rated'=>$d->zero_rated,
                'vatables_purchases'=>$d->vatables_purchases,
                'vat_on_purchases'=>$d->vat_on_purchases,
                'zero_rated_purchases'=>$d->zero_rated_purchases,
                'zero_rated_ecozones'=>$d->zero_rated_ecozones,
                'ewt'=>$d->ewt,
                'serial_no'=>$d->serial_no,
                'total_amount'=>$d->total_amount,
                'reference_number'=>$d->reference_number,
                'transaction_date'=>$d->transaction_date,
                'billing_from'=>$d->billing_from,
                'billing_to'=>$d->billing_to,
                'due_date'=>$d->due_date,
                'print_counter'=>$d->print_counter,
                'or_no'=>$d->or_no,
                'total_update'=>$d->total_update,
                'original_copy'=>$d->original_copy,
                'scanned_copy'=>$d->scanned_copy,
            );
        }
        $this->load->view('reserve/reserve_wesm',$data);
        $this->load->view('template/footer');
    }

    public function export_not_download_purchase_reserve(){
        $ref_no=$this->uri->segment(3);
        $due_date=$this->uri->segment(4);
        $billfrom=$this->uri->segment(5);
        $billto=$this->uri->segment(6);
        $participants=$this->uri->segment(7);

            $sql='';
             if($ref_no!='null'){
            $sql.= "ph.reference_number = '$ref_no' AND ";
            }

            if($due_date!='null'){
                $sql.= "ph.due_date = '$due_date' AND ";
            }

            if($billfrom!='null' && $billto!='null'){ 
                $sql.= " ((ph.billing_from BETWEEN '$billfrom' AND '$billto') OR (ph.billing_to BETWEEN '$billfrom' AND '$billto'))  AND ";
            }

            if(!empty($participants) && $participants!='null'){
                //$sql.= " tin = '$participant' AND "; 
               $par=array();
               foreach($this->super_model->select_custom_where('participant',"tin='$participants'") AS $p){
                   $par[]="'".$p->settlement_id."'";
               }
               $imp=implode(',',$par);
               $sql.= " pd.short_name IN($imp) AND ";
            }
            $query=substr($sql,0,-4);
            $qu = " WHERE pd.bulk_print_flag = '1' AND saved = '1' AND ".$query;

        $dir=realpath(APPPATH . '../uploads/excel/');
        $files = scandir($dir,1);

       
        $db_files = array();
        $pdffiles = array();
        $valid_files = array('pdf');
        if(is_dir($dir)){
        foreach(scandir($dir) as $file){
            $ext = pathinfo($file, PATHINFO_EXTENSION);
                if(in_array($ext, $valid_files)){
                    array_push($pdffiles, $file);
                }      
             }
        }
       foreach($this->super_model->custom_query("SELECT filename FROM reserve_transaction_details pd INNER JOIN reserve_transaction_head ph ON pd.reserve_id=ph.reserve_id $qu") AS $db){
        $db_files[] = $db->filename;
       }

        $data['result'] = array_diff($db_files, $pdffiles);

        $result=array_diff($db_files, $pdffiles);
        $x=1;
       
        $this->load->view('reserve/export_not_download_purchase_reserve',$data);
        $this->load->view('template/footer');
    }

    public function print_2307_reserve(){
        $reserve_id = $this->uri->segment(3);
        $reserve_detail_id = $this->uri->segment(4);
        $data['reserve_detail_id']=$reserve_detail_id;
        $data['reserve_id']=$reserve_id;
        $data['prev_reserve_details_id'] = $this->super_model->custom_query("SELECT reserve_detail_id FROM reserve_transaction_details WHERE reserve_detail_id < $reserve_detail_id AND reserve_id='$reserve_id' ORDER BY reserve_detail_id DESC LIMIT 1");
        $data['next_reserve_details_id'] = $this->super_model->custom_query("SELECT reserve_detail_id FROM reserve_transaction_details WHERE reserve_detail_id > $reserve_detail_id AND reserve_id='$reserve_id' ORDER BY reserve_detail_id ASC LIMIT 1");
        $data['short_name'] = $this->super_model->select_column_where("reserve_transaction_details", "short_name", "reserve_detail_id", $reserve_detail_id);
        $adjustment_flag = $this->super_model->select_column_where("reserve_transaction_head", "adjustment", "reserve_id", $reserve_id);
        $reference_number = $this->super_model->select_column_where("reserve_transaction_head", "reference_number", "reserve_id", $reserve_id);
        if($adjustment_flag==0){
            $date_ref = $this->super_model->select_column_where("reserve_transaction_head", "billing_to", "reserve_id", $reserve_id);
        }else{ 
            $date_ref = $this->super_model->select_column_where("reserve_transaction_head", "due_date", "reserve_id", $reserve_id);
        }
        $date_ref_year = date("Y",strtotime($date_ref));
        $data['billing_month'] = date('my',strtotime($date_ref));
        $data['refno'] =preg_replace("/[^0-9]/", "", $reference_number);
        $billing_id=$this->super_model->select_column_where("reserve_transaction_details", "billing_id", "reserve_detail_id", $reserve_detail_id);
        $data['tin']=$this->super_model->select_column_where("reserve_participant", "res_tin", "res_billing_id", $billing_id);
        $create_date = $this->super_model->select_column_where("reserve_transaction_head", "create_date", "reserve_id", $reserve_id);
        $company_name=$this->super_model->select_column_where("reserve_transaction_details", "company_name", "reserve_detail_id", $reserve_detail_id);
        if(!empty($company_name)){
            $data['name']=$company_name;
        }else{
            $data['name']=$this->super_model->select_column_where("reserve_participant", "res_participant_name", "res_billing_id", $billing_id);
        }
        $data['address']=$this->super_model->select_column_where("reserve_participant", "res_registered_address", "res_billing_id", $billing_id);
        $data['zip']=$this->super_model->select_column_where("reserve_participant", "res_zip_code", "res_billing_id", $billing_id);
        $data['reference_no']=$this->super_model->select_column_where("reserve_transaction_head", "reference_number", "reserve_id", $reserve_id);
        $data['item_no']=$this->super_model->select_column_where("reserve_transaction_details", "item_no", "reserve_detail_id", $reserve_detail_id);
        $month= date("n",strtotime($date_ref));
        $yearQuarter = ceil($month / 3);
        $first = array(1,4,7,10);
        $second = array(2,5,8,11);
        $third = array(3,6,9,12);
        $vatable_purchase = $this->super_model->select_column_where("reserve_transaction_details", "vatables_purchases", "reserve_detail_id", $reserve_detail_id);
        $zero_rated = $this->super_model->select_column_where("reserve_transaction_details", "zero_rated_purchases", "reserve_detail_id", $reserve_detail_id);
        $zero_rated_ecozone = $this->super_model->select_column_where("reserve_transaction_details", "zero_rated_ecozones", "reserve_detail_id", $reserve_detail_id);
        $amount = 0;
        if($vatable_purchase != 0){
            $amount=$vatable_purchase;
        }
        if($zero_rated != 0){
            $amount=$zero_rated;
        }
        if($zero_rated_ecozone != 0){
            $amount=$zero_rated_ecozone;
        }

        $data['total'] = $amount;
        if(in_array($month, $first)){
            $data['firstmonth'] = $amount; 
        } else {
            $data['firstmonth'] = "-"; 
        }

        if(in_array($month, $second)){
            $data['secondmonth'] = $amount; 
        } else {
            $data['secondmonth'] = "-"; 
        }

        if(in_array($month, $third)){
            $data['thirdmonth'] = $amount; 
        } else {
            $data['thirdmonth'] = "-"; 
        }
        $data['ewt'] = $this->super_model->select_column_where("reserve_transaction_details", "ewt", "reserve_detail_id", $reserve_detail_id);
        if($yearQuarter ==1){
            $period_from = "0101".$date_ref_year;
            $period_to = "0331".$date_ref_year;
        } else if($yearQuarter == 2){
            $period_from = "0401".$date_ref_year;
            $period_to = "0630".$date_ref_year;
        } else if($yearQuarter == 3){
            $period_from = "0701".$date_ref_year;
            $period_to = "0930".$date_ref_year;
        } else if($yearQuarter == 4){
            $period_from = "1001".$date_ref_year;
            $period_to = "1231".$date_ref_year;
        }
        $data['period_from'] = $period_from;
        $data['period_to'] = $period_to; 
        $this->load->view('reserve/print_2307_reserve',$data);
    }

    public function payment_reserve_form(){
        $payment_identifier = $this->uri->segment(3);
        $this->load->view('template/print_head');
        foreach($this->super_model->custom_query("SELECT * FROM payment_reserve_head WHERE payment_identifier='$payment_identifier' GROUP BY reserve_id") AS $p){
            $vatable_purchase= $this->super_model->select_sum("payment_reserve_head", "total_purchase", "reserve_id", $p->reserve_id);
            $energy=$vatable_purchase;
            $energy_total= $this->super_model->select_sum("payment_reserve_head", "total_purchase", "payment_identifier", $p->payment_identifier);
            $vat_on_purchases= $this->super_model->select_sum("payment_reserve_head", "total_vat", "reserve_id", $p->reserve_id);
            $vat_on_purchases_total= $this->super_model->select_sum("payment_reserve_head", "total_vat", "payment_identifier", $p->payment_identifier);
            $ewt= $this->super_model->select_sum("payment_reserve_head", "total_ewt", "reserve_id", $p->reserve_id);
            $ewt_total= $this->super_model->select_sum("payment_reserve_head", "total_ewt", "payment_identifier", $p->payment_identifier);
            $reference_number=$this->super_model->select_column_where("reserve_transaction_head","reference_number","reserve_id",$p->reserve_id);
            $total_amount= $this->super_model->select_sum("payment_reserve_head", "total_amount", "reserve_id", $p->reserve_id);
            $total_amount_disp= $this->super_model->select_sum("payment_reserve_head", "total_amount", "payment_identifier", $p->payment_identifier);
            $data['count']=$this->super_model->count_custom_where("payment_reserve_head","payment_identifier='$payment_identifier'");
            $data['total']=$total_amount_disp;
            $data['energy']=$energy_total;
            $data['vat_on_purchases']=$vat_on_purchases_total;
            $data['ewt']=$ewt_total;
            $data['payment'][]=array(
                "transaction_date"=>$p->payment_date,
                "reference_number"=>$reference_number,
                "energy"=>$energy,
                "vat_on_purchases"=>$vat_on_purchases,
                "ewt"=>$ewt,
                "total_amount"=>$total_amount,
            );
        }
        $this->load->view('reserve/payment_reserve_form',$data);
    }

    public function download_reserve_bulk(){
        $refno =  $this->uri->segment(3);
        $due_date =  $this->uri->segment(4);
        $in_ex_sub =  $this->uri->segment(5);
        $billfrom =  $this->uri->segment(6);
        $billto =  $this->uri->segment(7);
        $participants =  $this->uri->segment(8);
        $sql='';
        if($due_date!='null'){
            $sql.= "due_date = '$due_date' AND ";
        }
        if($refno!='null'){
            $sql.= "reference_number = '$refno' AND ";
        }
        if($billfrom!='null' && $billto!='null'){ 
            $sql.= " ((pth.billing_from BETWEEN '$billfrom' AND '$billto') OR (pth.billing_to BETWEEN '$billfrom' AND '$billto'))  AND ";
        }
        if($refno!='null'){
            $sql.= "reference_number = '$refno' AND ";
        }
        if(!empty($participants) && $participants!='null'){
           $par=array();
           foreach($this->super_model->select_custom_where('reserve_participant',"res_tin='$participants'") AS $p){
               $par[]="'".$p->res_settlement_id."'";
           }
           $imp=implode(',',$par);
           $sql.= "ptd.short_name IN($imp) AND ";
        }
        $query=substr($sql,0,-4);
        $data['details']=array();
        $x=1;
        foreach($this->super_model->custom_query("SELECT * FROM reserve_transaction_details ptd INNER JOIN reserve_transaction_head pth ON ptd.reserve_id=pth.reserve_id WHERE $query AND saved='1' AND adjustment='0' AND bulk_print_flag = '0' AND ewt > '0' ORDER BY ptd.reserve_detail_id LIMIT 10") AS $det){
          $month= date("n",strtotime($det->billing_to ?? ''));
            $yearQuarter = ceil($month / 3);
            $first = array(1,4,7,10);
            $second = array(2,5,8,11);
            $third = array(3,6,9,12);
            if($det->adjustment==0){
                $date_ref_year = date("Y",strtotime($det->billing_to));
            }else{   
                $date_ref_year = date("Y",strtotime($det->due_date));
            }
            if($yearQuarter ==1){
                $period_from = "0101".$date_ref_year;
                $period_to = "0331".$date_ref_year;
            } else if($yearQuarter == 2){
                $period_from = "0401".$date_ref_year;
                $period_to = "0630".$date_ref_year;
            } else if($yearQuarter == 3){
                $period_from = "0701".$date_ref_year;
                $period_to = "0930".$date_ref_year;
            } else if($yearQuarter == 4){
                $period_from = "1001".$date_ref_year;
                $period_to = "1231".$date_ref_year;
            }

            $data['period_from']=$period_from;
            $data['period_to'] = $period_to;
            if($det->vatables_purchases != 0){
                $amount=$det->vatables_purchases;
            }
            if($det->zero_rated_purchases != 0){
                $amount=$det->zero_rated_purchases;
            }
            if($det->zero_rated_ecozones != 0){
                $amount=$det->zero_rated_ecozones;
            }
            $total = $amount;
            if(in_array($month, $first)){
                $firstmonth = $amount; 
            } else {
                $firstmonth = "-"; 
            }

            if(in_array($month, $second)){
                $secondmonth = $amount; 
            } else {
                $secondmonth = "-"; 
            }

            if(in_array($month, $third)){
                $thirdmonth = $amount; 
            } else {
                $thirdmonth = "-"; 
            }

            $data_update = array(
                "bulk_print_flag"=>1
            );
            $this->super_model->update_where("reserve_transaction_details", $data_update, "reserve_detail_id", $det->reserve_detail_id);
            $data['billing_month'] = date('my',strtotime($det->billing_to));
            $data['timestamp']=date('Ymd');
            $count=$this->super_model->count_custom_where("reserve_participant","res_billing_id='$det->billing_id'");
            if($count>0){
                $tin=$this->super_model->select_column_where("reserve_participant", "res_tin", "res_billing_id", $det->billing_id);
            } else {
                $tin='000-000-000';
            }
            if($in_ex_sub==0 || $in_ex_sub=='null'){
                $data['details'][] = array(
                    'reserve_detail_id'=>$det->reserve_detail_id,
                    'due_date'=>'',
                    'tin'=>$tin,
                    'name'=>$this->super_model->select_column_where("reserve_participant", "res_participant_name", "res_billing_id", $det->billing_id),
                    'address'=>$this->super_model->select_column_where("reserve_participant", "res_registered_address", "res_billing_id", $det->billing_id),
                    'zip'=>$this->super_model->select_column_where("reserve_participant", "res_zip_code", "res_billing_id", $det->billing_id),
                    'total'=>$amount,
                    'ewt'=>$det->ewt,
                    'firstmonth'=>$firstmonth,
                    'secondmonth'=>$secondmonth,
                    'thirdmonth'=>$thirdmonth,
                    'item_no'=>$det->item_no,
                    'shortname'=>$det->short_name,
                    'reference_no'=>$det->reference_number,
                    'ref_no'=>preg_replace("/[^0-9]/", "", $det->reference_number),
                );
            }else if($in_ex_sub==1){
                $participant_id = $this->super_model->select_column_custom_where("reserve_participant","res_participant_id","res_billing_id='$det->billing_id'");
                $sub_participant = $this->super_model->count_custom_where("reserve_subparticipant","res_sub_participant='$participant_id'");
                if($sub_participant==0){
                    $data['details'][] = array(
                        'reserve_detail_id'=>$det->reserve_detail_id,
                        'due_date'=>'',
                        'tin'=>$tin,
                        'name'=>$this->super_model->select_column_where("reserve_participant", "res_participant_name", "res_billing_id", $det->billing_id),
                        'address'=>$this->super_model->select_column_where("reserve_participant", "res_registered_address", "res_billing_id", $det->billing_id),
                        'zip'=>$this->super_model->select_column_where("reserve_participant", "res_zip_code", "res_billing_id", $det->billing_id),
                        'total'=>$amount,
                        'ewt'=>$det->ewt,
                        'firstmonth'=>$firstmonth,
                        'secondmonth'=>$secondmonth,
                        'thirdmonth'=>$thirdmonth,
                        'item_no'=>$det->item_no,
                        'shortname'=>$det->short_name,
                        'reference_no'=>$det->reference_number,
                        'ref_no'=>preg_replace("/[^0-9]/", "", $det->reference_number),
                    );
                }
            }
        }
        $this->load->view('reserve/download_reserve_bulk',$data);
    }

    public function download_reserve_bulk_zoomed(){
        $refno =  $this->uri->segment(3);
        $due_date =  $this->uri->segment(4);
        $in_ex_sub =  $this->uri->segment(5);
        $billfrom=$this->uri->segment(6);
        $billto=$this->uri->segment(7);
        $participants=$this->uri->segment(8);
        $sql='';
        if($due_date!='null'){
            $sql.= "due_date = '$due_date' AND ";
        }
        if($billfrom!='null' && $billto!='null'){ 
            $sql.= " ((pth.billing_from BETWEEN '$billfrom' AND '$billto') OR (pth.billing_to BETWEEN '$billfrom' AND '$billto'))  AND ";
        }
        if($refno!='null'){
            $sql.= "reference_number = '$refno' AND ";
        }
        if(!empty($participants) && $participants!='null'){
           $par=array();
           foreach($this->super_model->select_custom_where('reserve_participant',"res_tin='$participants'") AS $p){
               $par[]="'".$p->res_settlement_id."'";
           }
           $imp=implode(',',$par);
           $sql.= "ptd.short_name IN($imp) AND ";
        }
        $query=substr($sql,0,-4);
        $data['details']=array();
        $x=1;
        foreach($this->super_model->custom_query("SELECT * FROM reserve_transaction_details ptd INNER JOIN reserve_transaction_head pth ON ptd.reserve_id=pth.reserve_id WHERE $query AND saved='1' AND adjustment='0' AND bulk_print_flag = '0' AND ewt > '0' ORDER BY ptd.reserve_detail_id LIMIT 10") AS $det){
            $month= date("n",strtotime($det->billing_to ?? ''));
            $yearQuarter = ceil($month / 3);
            $first = array(1,4,7,10);
            $second = array(2,5,8,11);
            $third = array(3,6,9,12);
            if($det->adjustment==0){
                $date_ref_year = date("Y",strtotime($det->billing_to));
            }else{ 
                $date_ref_year = date("Y",strtotime($det->due_date));
            }
            if($yearQuarter ==1){
                $period_from = "0101".$date_ref_year;
                $period_to = "0331".$date_ref_year;
            } else if($yearQuarter == 2){
                $period_from = "0401".$date_ref_year;
                $period_to = "0630".$date_ref_year;
            } else if($yearQuarter == 3){
                $period_from = "0701".$date_ref_year;
                $period_to = "0930".$date_ref_year;
            } else if($yearQuarter == 4){
                $period_from = "1001".$date_ref_year;
                $period_to = "1231".$date_ref_year;
            }

            $data['period_from']=$period_from;
            $data['period_to'] = $period_to;
            if($det->vatables_purchases != 0){
                $amount=$det->vatables_purchases;
            }
            if($det->zero_rated_purchases != 0){
                $amount=$det->zero_rated_purchases;
            }
            if($det->zero_rated_ecozones != 0){
                $amount=$det->zero_rated_ecozones;
            }
            $total = $amount;
            if(in_array($month, $first)){
                $firstmonth = $amount; 
            } else {
                $firstmonth = "-"; 
            }

            if(in_array($month, $second)){
                $secondmonth = $amount; 
            } else {
                $secondmonth = "-"; 
            }

            if(in_array($month, $third)){
                $thirdmonth = $amount; 
            } else {
                $thirdmonth = "-"; 
            }

            $data_update = array(
                "bulk_print_flag"=>1
            );
            
            $this->super_model->update_where("reserve_transaction_details", $data_update, "reserve_detail_id",$det->reserve_detail_id);
            $data['billing_month'] = date('my',strtotime($det->billing_to));
            $data['due_date'] = '';
            $data['timestamp']=date('Ymd');
            $count=$this->super_model->count_custom_where("reserve_participant","res_billing_id='$det->billing_id'");
            if($count>0){
                $tin=$this->super_model->select_column_where("reserve_participant", "res_tin", "res_billing_id", $det->billing_id);
            } else {
                $tin='000-000-000';
            }
            if($in_ex_sub==0 || $in_ex_sub=='null'){
                $data['details'][] = array(
                    'reserve_detail_id'=>$det->reserve_detail_id,
                    'due_date'=>'',
                    'tin'=>$tin,
                    'name'=>$this->super_model->select_column_where("reserve_participant", "res_participant_name", "res_billing_id", $det->billing_id),
                    'address'=>$this->super_model->select_column_where("reserve_participant", "res_registered_address", "res_billing_id", $det->billing_id),
                    'zip'=>$this->super_model->select_column_where("reserve_participant", "res_zip_code", "res_billing_id", $det->billing_id),
                    'total'=>$amount,
                    'ewt'=>$det->ewt,
                    'firstmonth'=>$firstmonth,
                    'secondmonth'=>$secondmonth,
                    'thirdmonth'=>$thirdmonth,
                    'item_no'=>$det->item_no,
                    'shortname'=>$det->short_name,
                    'reference_no'=>$det->reference_number,
                    'ref_no'=>preg_replace("/[^0-9]/", "", $det->reference_number),
                );
            }else if($in_ex_sub==1){
                $participant_id = $this->super_model->select_column_custom_where("reserve_participant","res_participant_id","res_billing_id='$det->billing_id'");
                $sub_participant = $this->super_model->count_custom_where("reserve_subparticipant","res_sub_participant='$participant_id'");
                if($sub_participant==0){
                    $data['details'][] = array(
                        'reserve_detail_id'=>$det->reserve_detail_id,
                        'due_date'=>'',
                        'tin'=>$tin,
                        'name'=>$this->super_model->select_column_where("reserve_participant", "res_participant_name", "res_billing_id", $det->billing_id),
                        'address'=>$this->super_model->select_column_where("reserve_participant", "res_registered_address", "res_billing_id", $det->billing_id),
                        'zip'=>$this->super_model->select_column_where("reserve_participant", "res_zip_code", "res_billing_id", $det->billing_id),
                        'total'=>$amount,
                        'ewt'=>$det->ewt,
                        'firstmonth'=>$firstmonth,
                        'secondmonth'=>$secondmonth,
                        'thirdmonth'=>$thirdmonth,
                        'item_no'=>$det->item_no,
                        'shortname'=>$det->short_name,
                        'reference_no'=>$det->reference_number,
                        'ref_no'=>preg_replace("/[^0-9]/", "", $det->reference_number),
                    );
                }
            }
        }
        $this->load->view('reserve/download_reserve_bulk_zoomed',$data);
    }

    public function purchases_reserve_pdf_scan_directory(){

        $filenames = $this->input->post('filenames');
        $sql="(";
        foreach($filenames AS $f){
            $sql .= " filename =  '$f'  OR ";
        }
        $query=substr($sql,0,-3) . ")";

        $data['details']=array();
        $x=1;
        foreach($this->super_model->custom_query("SELECT * FROM reserve_transaction_details ptd INNER JOIN reserve_transaction_head pth ON ptd.reserve_id=pth.reserve_id WHERE $query AND saved='1' AND adjustment='0' AND  ewt > '0' ORDER BY ptd.reserve_detail_id LIMIT 10") AS $det){
            $month= date("n",strtotime($det->billing_to ?? ''));
            $yearQuarter = ceil($month / 3);
            $first = array(1,4,7,10);
            $second = array(2,5,8,11);
            $third = array(3,6,9,12);
            if($det->adjustment==0){
                $date_ref_year = date("Y",strtotime($det->billing_to));
            }else{ 
                $date_ref_year = date("Y",strtotime($det->due_date));
            }
            if($yearQuarter ==1){
                $period_from = "0101".$date_ref_year;
                $period_to = "0331".$date_ref_year;
            } else if($yearQuarter == 2){
                $period_from = "0401".$date_ref_year;
                $period_to = "0630".$date_ref_year;
            } else if($yearQuarter == 3){
                $period_from = "0701".$date_ref_year;
                $period_to = "0930".$date_ref_year;
            } else if($yearQuarter == 4){
                $period_from = "1001".$date_ref_year;
                $period_to = "1231".$date_ref_year;
            }

            $data['period_from']=$period_from;
            $data['period_to'] = $period_to;
            if($det->vatables_purchases != 0){
                $amount=$det->vatables_purchases;
            }
            if($det->zero_rated_purchases != 0){
                $amount=$det->zero_rated_purchases;
            }
            if($det->zero_rated_ecozones != 0){
                $amount=$det->zero_rated_ecozones;
            }
            $total = $amount;
            if(in_array($month, $first)){
                $firstmonth = $amount; 
            } else {
                $firstmonth = "-"; 
            }

            if(in_array($month, $second)){
                $secondmonth = $amount; 
            } else {
                $secondmonth = "-"; 
            }

            if(in_array($month, $third)){
                $thirdmonth = $amount; 
            } else {
                $thirdmonth = "-"; 
            }

            $data_update = array(
                "bulk_print_flag"=>1
            );
            
            $this->super_model->update_where("reserve_transaction_details", $data_update, "reserve_detail_id",$det->reserve_detail_id);
            $data['billing_month'] = date('my',strtotime($det->billing_to));
            $data['due_date'] = '';
            $data['timestamp']=date('Ymd');
            $count=$this->super_model->count_custom_where("reserve_participant","res_billing_id='$det->billing_id'");
            if($count>0){
                $tin=$this->super_model->select_column_where("reserve_participant", "res_tin", "res_billing_id", $det->billing_id);
            } else {
                $tin='000-000-000';
            }
            if($in_ex_sub==0 || $in_ex_sub=='null'){
                $data['details'][] = array(
                    'reserve_detail_id'=>$det->reserve_detail_id,
                    'due_date'=>'',
                    'tin'=>$tin,
                    'name'=>$this->super_model->select_column_where("reserve_participant", "res_participant_name", "res_billing_id", $det->billing_id),
                    'address'=>$this->super_model->select_column_where("reserve_participant", "res_registered_address", "res_billing_id", $det->billing_id),
                    'zip'=>$this->super_model->select_column_where("reserve_participant", "res_zip_code", "res_billing_id", $det->billing_id),
                    'total'=>$amount,
                    'ewt'=>$det->ewt,
                    'firstmonth'=>$firstmonth,
                    'secondmonth'=>$secondmonth,
                    'thirdmonth'=>$thirdmonth,
                    'item_no'=>$det->item_no,
                    'shortname'=>$det->short_name,
                    'reference_no'=>$det->reference_number,
                    'ref_no'=>preg_replace("/[^0-9]/", "", $det->reference_number),
                );
            }else if($in_ex_sub==1){
                $participant_id = $this->super_model->select_column_custom_where("reserve_participant","res_participant_id","res_billing_id='$det->billing_id'");
                $sub_participant = $this->super_model->count_custom_where("reserve_subparticipant","res_sub_participant='$participant_id'");
                if($sub_participant==0){
                    $data['details'][] = array(
                        'reserve_detail_id'=>$det->reserve_detail_id,
                        'due_date'=>'',
                        'tin'=>$tin,
                        'name'=>$this->super_model->select_column_where("reserve_participant", "res_participant_name", "res_billing_id", $det->billing_id),
                        'address'=>$this->super_model->select_column_where("reserve_participant", "res_registered_address", "res_billing_id", $det->billing_id),
                        'zip'=>$this->super_model->select_column_where("reserve_participant", "res_zip_code", "res_billing_id", $det->billing_id),
                        'total'=>$amount,
                        'ewt'=>$det->ewt,
                        'firstmonth'=>$firstmonth,
                        'secondmonth'=>$secondmonth,
                        'thirdmonth'=>$thirdmonth,
                        'item_no'=>$det->item_no,
                        'shortname'=>$det->short_name,
                        'reference_no'=>$det->reference_number,
                        'ref_no'=>preg_replace("/[^0-9]/", "", $det->reference_number),
                    );
                }
            }
        }
        $this->load->view('reserve/purchases_reserve_pdf_scan_directory',$data);
    }

    public function update_filename(){
        $reserve_detail_id = $this->input->post('reserve_detail_id');
        $filename = $this->input->post('filename');
        $data_update = array(
                "filename"=>$filename
            );
            $this->super_model->update_custom_where("reserve_transaction_details", $data_update, "reserve_detail_id='$reserve_detail_id'");
    }

    public function update_reserve_details(){
        $reserve_detail_id=$this->input->post('reserve_detail_id');
        $reserve_id=$this->input->post('reserve_id');
        $billing_id=$this->input->post('billing_id');
        $or_no=$this->input->post('or_no');
        $total_update=$this->input->post('total_update');
        $original_copy=$this->input->post('original_copy');
        $scanned_copy=$this->input->post('scanned_copy');
        $data_update=array(
            "or_no"=>$or_no,
            "total_update"=>$total_update,
            "original_copy"=>$original_copy,
            "scanned_copy"=>$scanned_copy,
        );
        if($this->super_model->update_custom_where("reserve_transaction_details", $data_update, "reserve_detail_id='$reserve_detail_id' AND reserve_id='$reserve_id' AND billing_id='$billing_id'")){
            foreach($this->super_model->select_custom_where("reserve_transaction_details","reserve_detail_id='$reserve_detail_id' AND reserve_id='$reserve_id' AND billing_id='$billing_id'") AS $latest_data){
                $return = array('or_no'=>$latest_data->or_no, 'total_update'=>$latest_data->total_update, 'original_copy'=>$latest_data->original_copy, 'scanned_copy'=>$latest_data->scanned_copy);
            }
            echo json_encode($return);
        }
    }

    public function save_reserve_bulk_or(){
        $reserve_id = $this->input->post('reserve_id');
        $or_identifier = $this->input->post('or_identifier');
        $data_head = array(
            'saved_or_bulk'=>1
        );
        $this->super_model->update_custom_where("reserve_transaction_details", $data_head, "reserve_id='$reserve_id' AND or_bulk_identifier='$or_identifier'");
    }

    public function cancel_reserve_bulk_or(){
        $reserve_id = $this->input->post('reserve_id');
        $or_identifier = $this->input->post('or_identifier');
        $data_or = array(
            'or_no'=>Null,
            'total_update'=>'',
            'original_copy'=>0,
            'scanned_copy'=>0,
            'or_bulk_identifier'=>Null,
        );
        $this->super_model->update_custom_where("reserve_transaction_details", $data_or, "reserve_id='$reserve_id' AND or_bulk_identifier='$or_identifier'");
    }


    public function or_reserve_bulk(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $identifier_code=$this->generateRandomString();
        $data['identifier_code']=$identifier_code;
        $reserveid=$this->uri->segment(3);
        $data['reserve_id'] = $reserveid;
        $identifier=$this->uri->segment(4);
        $data['identifier']=$this->uri->segment(4);
        $ref_no=$this->super_model->select_column_where("reserve_transaction_head","reference_number","reserve_id",$reserveid);
        $data['refno']=$ref_no;
        $data['saved']=$this->super_model->select_column_where("reserve_transaction_details","saved_or_bulk","or_bulk_identifier",$identifier);
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT reference_number,reserve_id FROM reserve_transaction_head WHERE reference_number!='' AND adjustment='0' ");
        foreach($this->super_model->custom_query("SELECT * FROM reserve_transaction_details ptd INNER JOIN reserve_transaction_head pth ON ptd.reserve_id=pth.reserve_id WHERE reference_number='$ref_no' AND adjustment='0' AND or_bulk_identifier ='$identifier'") AS $d){
            $data['details'][]=array(
                'reserve_detail_id'=>$d->reserve_detail_id,
                'reserve_id'=>$d->reserve_id,
                'total_update'=>$d->total_update,
                'original_copy'=>$d->original_copy,
                'scanned_copy'=>$d->scanned_copy,
                'or_no'=>$d->or_no,
                'billing_id'=>$d->billing_id,
            );
        }
        $this->load->view('reserve/or_reserve_bulk', $data);
        $this->load->view('template/footer');
    }

    public function upload_reserve_or_bulk(){
        $reserve_id = $this->input->post('reserve_id');
        $dest= realpath(APPPATH . '../uploads/excel/');
        $error_ext=0;
        if(!empty($_FILES['doc']['name'])){
            $exc= basename($_FILES['doc']['name']);
            $exc=explode('.',$exc);
            $ext1=$exc[1];
            if($ext1=='php' || $ext1!='xlsx'){
                $error_ext++;
            }else {
                $filename1='bulk_reserve_updates_of_OR.'.$ext1;
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                     $this->readExcel_or($reserve_id);
                } 
            }
        }
    }

    public function readExcel_or($reserve_id){
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $inputFileName =realpath(APPPATH.'../uploads/excel/bulk_reserve_updates_of_OR.xlsx');
       try {
            $inputFileType = io_factory::identify($inputFileName);
            $objReader = io_factory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } 
        catch(Exception $e) {
            die('Error loading file"'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        //$objPHPExcel->setActiveSheetIndex(0);
        $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 
        //$highestRow = $highestRow-1;
        for($x=2;$x<=$highestRow;$x++){
            $identifier = $this->input->post('identifier');
            $billing_id = str_replace(' ','',$objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue());   
            $or_no = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue() ?? '');
            //$total_amount = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('C'.$x)->getOldCalculatedValue());
            $total_amount = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue());
            $original_copy = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getFormattedValue() ?? '');
            $scanned_copy = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue() ?? '');
     
            $data_or = array(
                'or_no'=>$or_no,
                'total_update'=>$total_amount,
                'original_copy'=>$original_copy,
                'scanned_copy'=>$scanned_copy,
                'or_bulk_identifier'=>$identifier,
            );
           $this->super_model->update_custom_where("reserve_transaction_details", $data_or, "reserve_id='$reserve_id' AND billing_id='$billing_id'");
        }
    }
    
    public function export_reservetrans(){
        $reference_number=$this->uri->segment(3);
        $due_date=$this->uri->segment(4);
        $or_no=$this->uri->segment(5);
        $original_copy=$this->uri->segment(6);
        $scanned_copy=$this->uri->segment(7);
        $billfrom=$this->uri->segment(8);
        $billto=$this->uri->segment(9);
        $participants=$this->uri->segment(10);
        $ors=str_replace("%5E","",$or_no ?? '');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Reserve Wesm Transcation.xlsx";
        $sql='';
        $sql1='';
        if($reference_number!='null'){
            $sql.= "reference_number = '$reference_number' AND ";
        }
        if($due_date!='null'){
            $sql.= "due_date = '$due_date' AND ";
        }
        
        if($billfrom!='null' && $billto!='null'){ 
            $sql.= " ((billing_from BETWEEN '$billfrom' AND '$billto') OR (billing_to BETWEEN '$billfrom' AND '$billto'))  AND ";
        }

        if(!empty($participants) && $participants!='null'){
           $par=array();
           foreach($this->super_model->select_custom_where('reserve_participant',"res_tin='$participants'") AS $p){
               $par[]="'".$p->res_settlement_id."'";
           }
           $imp=implode(',',$par);
           $sql1.= "short_name IN($imp) AND ";
        }

        if($or_no!='null' && !empty($or_no) && $or_no!="%5E"){
            $sql1.= "or_no = '$ors' AND ";
        }else if($or_no=="%5E"){
            $sql1.= "(or_no='' OR pd.or_no IS NULL) AND ";
        }
        if($original_copy!='null' && isset($original_copy)){
            $sql1.= "original_copy = '$original_copy' AND ";
        }
        if($scanned_copy!='null' && isset($scanned_copy)){
            $sql1.= "scanned_copy = '$scanned_copy' AND ";
        }
        $query=substr($sql,0,-4);
        if($query!=''){
            $qu = " WHERE adjustment='0' AND saved='1' AND ".$query;
        }else{
            $qu = " WHERE adjustment='0' AND saved='1'";
        }
        $query_filter=substr($sql1,0,-4);
        $qufilt='';
        if($query_filter!=''){
            $qufilt = " AND ".$query_filter;
        }
        $num=6;
        $x=1;
        $participant_name=$this->super_model->select_column_where('reserve_participant','res_participant_name','res_tin',$participants);
        foreach($this->super_model->custom_query("SELECT * FROM reserve_transaction_head $qu") AS $head){
            $transaction_date=date("F d,Y",strtotime($head->transaction_date));
            $billing_from=date("F d,Y",strtotime($head->billing_from));
            $billing_to=date("F d,Y",strtotime($head->billing_to));
            $due_dates=date("F d,Y",strtotime($head->due_date));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "Reference Number: $head->reference_number");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "Date: $transaction_date");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', "Due Date: $due_dates");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', "Billing Period (From): $billing_from");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', "Billing Period (To): $billing_to");
            $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
            $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
            $objPHPExcel->getActiveSheet()->mergeCells('G1:J1');
            $objPHPExcel->getActiveSheet()->mergeCells('G2:J2');
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    )
                )
            );
            foreach(range('A','R') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', "Item No.");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B5', "STL ID/TPShort Name");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5', "Billing ID");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D5', "Facility Type");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E5', "WHT Agent Tag");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F5', "ITH Tag");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G5', "Non Vatable Tag");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H5', "Zero-rated Tag");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I5', "Vatable Purchases");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J5', "Zero Rated Purchases");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K5', "Zero Rated EcoZones Purchases");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L5', "Vat On Purchases");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M5', "EWT");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N5', "Total Amount");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O5', "OR Number");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P5', "Total Amount");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q5', "Original Copy");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R5', "Scanned Copy");
            $objPHPExcel->getActiveSheet()->getStyle("A5:R5")->applyFromArray($styleArray);
            foreach($this->super_model->select_custom_where("reserve_transaction_details","reserve_id='$head->reserve_id' $qufilt") AS $re){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $x);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $re->short_name);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $re->actual_billing_id);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $re->facility_type);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $re->wht_agent);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, $re->ith_tag);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, $re->non_vatable);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, $re->zero_rated);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, $re->vatables_purchases);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, $re->zero_rated_purchases);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, $re->zero_rated_ecozones);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, $re->vat_on_purchases);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, $re->ewt);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, $re->total_amount);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, $re->or_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, $re->total_update);
                if($re->original_copy==1){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "Yes");
                }else if($re->original_copy==0){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "No");
                }else{
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "");
                }
                if($re->scanned_copy==1){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, "Yes");
                }else if($re->scanned_copy==0){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, "No");
                }else{
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, "");
                }
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":R".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":R".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num.":N".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);$objPHPExcel->getActiveSheet()->getStyle('P'.$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $num++;
                $x++;
            }
            $objPHPExcel->getActiveSheet()->getStyle('A5:R5')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A5:R5')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reserve Wesm Transcation.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
    }
}