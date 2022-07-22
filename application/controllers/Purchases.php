<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchases extends CI_Controller {

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

    public function upload_purchases(){
        $purchase_id=$this->uri->segment(3);
        $data['purchase_id'] = $purchase_id;
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        if(!empty($purchase_id)){
            foreach($this->super_model->select_row_where("purchase_transaction_head", "purchase_id",$purchase_id) AS $h){
                $data['transaction_date']=$h->transaction_date;
                $data['billing_from']=$h->billing_from;
                $data['billing_to']=$h->billing_to;
                $data['reference_number']=$h->reference_number;
                $data['due_date']=$h->due_date;
                $data['saved']=$h->saved;
                $data['adjustment']=$h->adjustment;
                foreach($this->super_model->select_row_where("purchase_transaction_details","purchase_id",$h->purchase_id) AS $d){
                    $data['details'][]=array(
                        'purchase_detail_id'=>$d->purchase_detail_id,
                        'purchase_id'=>$d->purchase_id,
                        'item_no'=>$d->item_no,
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
                        'print_counter'=>$d->print_counter
                    );
                }
            }
        }
        // echo $purchase_id;
        $this->load->view('purchases/upload_purchases',$data);
        $this->load->view('template/footer');
    }

    public function count_print(){
        $purchase_detail_id=$this->input->post('purchase_details_id');
        foreach($this->super_model->select_row_where("purchase_transaction_details","purchase_detail_id",$purchase_detail_id) AS $d){
            $new_count = $d->print_counter + 1;
            $data_head = array(
                'print_counter'=>$new_count
            );
            $this->super_model->update_where("purchase_transaction_details",$data_head, "purchase_detail_id", $purchase_detail_id);
            echo $new_count;
        }
    }

    public function add_purchase_head(){
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
        $purchase_id = $this->super_model->insert_return_id("purchase_transaction_head",$data);
        echo $purchase_id;
    }

    public function cancel_purchase(){
        $purchase_id = $this->input->post('purchase_id');
        $this->super_model->delete_where("purchase_transaction_details", "purchase_id", $purchase_id);
        $this->super_model->delete_where("purchase_transaction_head", "purchase_id", $purchase_id);
    }

     public function save_all(){
        $purchase_id = $this->input->post('purchase_id');
        $data_head = array(
            'saved'=>1
        );
        $this->super_model->update_where("purchase_transaction_head",$data_head, "purchase_id", $purchase_id);
        echo $purchase_id;
    }

    public function upload_purchase_process(){
        $purchase_id = $this->input->post('purchase_id');
        $dest= realpath(APPPATH . '../uploads/excel/');
        $error_ext=0;
        if(!empty($_FILES['doc']['name'])){
            $exc= basename($_FILES['doc']['name']);
            $exc=explode('.',$exc);
            $ext1=$exc[1];
            if($ext1=='php' || $ext1!='xlsx'){
                $error_ext++;
            }else {
                $filename1='wesm_purchases.'.$ext1;
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                     $this->readExcel_inv($purchase_id);
                } 
            }
        }
    }

    public function readExcel_inv($purchase_id){

        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();

        $inputFileName =realpath(APPPATH.'../uploads/excel/wesm_purchases.xlsx');

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
        $highestRow = $highestRow-1;
        $y=1;
        for($x=3;$x<$highestRow;$x++){
            $itemno = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getOldCalculatedValue());
            $shortname = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue());
            $billing_id = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue());   
            $fac_type = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getFormattedValue());
            $wht_agent = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue());
            $ith = trim($objPHPExcel->getActiveSheet()->getCell('F'.$x)->getFormattedValue());
            $non_vatable = trim($objPHPExcel->getActiveSheet()->getCell('G'.$x)->getFormattedValue());
            $zero_rated = trim($objPHPExcel->getActiveSheet()->getCell('H'.$x)->getFormattedValue());
            $vatables_purchases = trim($objPHPExcel->getActiveSheet()->getCell('I'.$x)->getFormattedValue(),'()');
            $vatables_purchases = trim($vatables_purchases,"-");
            $zero_rated_purchases = trim($objPHPExcel->getActiveSheet()->getCell('J'.$x)->getFormattedValue(),'()');
             $zero_rated_purchases = trim($zero_rated_purchases,"-");
            $zero_rated_ecozone = trim($objPHPExcel->getActiveSheet()->getCell('K'.$x)->getFormattedValue(),'()');
             $zero_rated_ecozone = trim($zero_rated_ecozone,"-");
            $vat_on_purchases = trim($objPHPExcel->getActiveSheet()->getCell('L'.$x)->getFormattedValue(),'()');
            $vat_on_purchases = trim($vat_on_purchases,"-");
            $ewt = trim($objPHPExcel->getActiveSheet()->getCell('M'.$x)->getFormattedValue(),'()');
            $total_amount = trim($objPHPExcel->getActiveSheet()->getCell('N'.$x)->getOldCalculatedValue(),'()');
            $total_amount = trim($total_amount,"-");
            //$total_amount = ($vatables_purchases + $zero_rated + $zero_rated_purchases + $vat_on_purhcases) - $ewt;
         
                $data_purchase = array(
                    'purchase_id'=>$purchase_id,
                    'item_no'=>$y,
                    'short_name'=>$shortname,
                    'billing_id'=>$billing_id,
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
                    'balance'=>$total_amount
                    //'balance'=>$total_amount
                );
                $this->super_model->insert_into("purchase_transaction_details", $data_purchase);
                $y++;
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

    public function print_BS(){
        $purchase_detail_id = $this->uri->segment(3);
        $data['purchase_detail_id']=$purchase_detail_id;
        foreach($this->super_model->select_row_where("purchase_transaction_details","purchase_detail_id",$purchase_detail_id) AS $p){
            $data['address']=$this->super_model->select_column_where("participant","office_address","billing_id",$p->billing_id);
            $data['tin']=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
            $data['settlement']=$this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
            $data['company_name']=$this->super_model->select_column_where("participant","participant_name","billing_id",$p->billing_id);
            $data['billing_from']=$this->super_model->select_column_where("purchase_transaction_head","billing_from","purchase_id",$p->purchase_id);
            $data['billing_to']=$this->super_model->select_column_where("purchase_transaction_head","billing_to","purchase_id",$p->purchase_id);
            $data['due_date']=$this->super_model->select_column_where("purchase_transaction_head","due_date","purchase_id",$p->purchase_id);
            $data['reference_number']=$this->super_model->select_column_where("purchase_transaction_head","reference_number","purchase_id",$p->purchase_id);
            $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
            foreach($this->super_model->select_row_where("subparticipant","participant_id",$participant_id) AS $p){
                $data['sub'][]=array(
                    
                );
            }
        }
        $this->load->view('purchases/print_BS',$data);
        $this->load->view('template/print_head');
        
    }

    public function print_invoice(){
        error_reporting(0);
        $purchase_detail_id = $this->uri->segment(3);
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        foreach($this->super_model->select_row_where("purchase_transaction_details","purchase_detail_id",$purchase_detail_id) AS $p){
            $data['address']=$this->super_model->select_column_where("participant","office_address","billing_id",$p->billing_id);
            $data['tin']=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
            $data['billing_from']=$this->super_model->select_column_where("purchase_transaction_head","billing_from","purchase_id",$p->purchase_id);
            $data['company_name']=$this->super_model->select_column_where("participant","participant_name","billing_id",$p->billing_id);
            $data['billing_to']=$this->super_model->select_column_where("purchase_transaction_head","billing_to","purchase_id",$p->purchase_id);
            $ewt=str_replace("-", '', $p->ewt);
            $ewt_exp=explode(".", $ewt);
            $vatables_purchases = explode(".",$p->vatables_purchases);
            $data['vat_purchase_peso'] = $vatables_purchases[0];
            $data['vat_purchase_cents'] = $vatables_purchases[1];

            $zero_rated_purchases = explode(".",$p->zero_rated_purchases);
            $data['zero_rated_peso'] = $zero_rated_purchases[0];
            $data['zero_rated_cents'] = $zero_rated_purchases[1];

            $vat_on_purchases = explode(".",$p->vat_on_purchases);
            $data['vat_peso'] = $vat_on_purchases[0];
            $data['vat_cents'] = $vat_on_purchases[1];

            $data['ewt_peso']=$ewt_exp[0];
            $data['ewt_cents']=$ewt_exp[1];
            $zero_rated_ecozones_exp=explode(".", $p->zero_rated_ecozones);
            $data['zero_rated_ecozones_peso']=$zero_rated_ecozones_exp[0];
            $data['zero_rated_ecozones_cents']=$zero_rated_ecozones_exp[1];
            $total= ($p->vatables_purchases + $p->vat_on_purchases + $p->zero_rated_ecozones + $p->zero_rated_purchases) - $p->ewt;
            $data['total_amount']=$total;
            $data['amount_words']=strtoupper($this->convertNumber($total));
            $total_exp=explode(".", $total);
            $data['total_peso']=$total_exp[0];
            $data['total_cents']=$total_exp[1];
        }
        $this->load->view('purchases/print_invoice',$data);
        $this->load->view('template/footer');
    }

    public function payment_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $ref_no=$this->uri->segment(3);
        $data['ref_no']=$ref_no;
        $data['purchase_id'] =$this->super_model->select_column_where("purchase_transaction_head","purchase_id","reference_number",$ref_no);
       /* $data['head'] = $this->super_model->custom_query("SELECT DISTINCT reference_number,pth.purchase_id FROM purchase_transaction_head pth INNER JOIN purchase_transaction_details ptd WHERE reference_number!='' AND balance!='0'");*/
        $data['head'] = $this->super_model->custom_query("SELECT DISTINCT reference_number,pth.purchase_id FROM purchase_transaction_details ptd INNER JOIN purchase_transaction_head pth ON ptd.purchase_id=pth.purchase_id WHERE reference_number!='' AND balance!='0'");
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_details pd INNER JOIN purchase_transaction_head ph ON pd.purchase_id=ph.purchase_id WHERE saved='1' AND reference_number LIKE '%$ref_no%'") AS $d){
            $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$d->billing_id);
        
            $data['details'][]=array(
                'purchase_detail_id'=>$d->purchase_detail_id,
                'purchase_id'=>$d->purchase_id,
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
        $this->load->view('purchases/payment_list',$data);
        $this->load->view('template/footer');
    }

    public function getpayment(){
        $reference_number=$this->input->post('reference_number');
        $ref_exp=explode(".",$reference_number);
        $purchase_id=$ref_exp[0];
        $ref_no=$ref_exp[1];
        $total_amount= $this->super_model->select_sum("purchase_transaction_details", "balance", "purchase_id", $purchase_id);
        $total_purchase= $this->super_model->select_sum("purchase_transaction_details", "vatables_purchases", "purchase_id", $purchase_id);
        $zero_rated= $this->super_model->select_sum("purchase_transaction_details", "zero_rated_purchases", "purchase_id", $purchase_id);
        $ecozone= $this->super_model->select_sum("purchase_transaction_details", "zero_rated_ecozones", "purchase_id", $purchase_id);
        $total_vatable_purchase = $total_purchase + $zero_rated + $ecozone;
        $total_vat= $this->super_model->select_sum("purchase_transaction_details", "vat_on_purchases", "purchase_id", $purchase_id);
        $total_ewt= $this->super_model->select_sum("purchase_transaction_details", "ewt", "purchase_id", $purchase_id);
        $data['list'] = array(
            'purchase_id'=>$purchase_id,
            'reference_number'=>$ref_no,
            'total_amount'=>$total_amount,
            'total_vatable_purchase'=>$total_vatable_purchase,
            'total_vat'=>$total_vat,
            'total_ewt'=>$total_ewt,
            'count'=>$this->input->post('count'),
        );
            
        $this->load->view('purchases/row_payment',$data);
    }

    public function paid_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $ref_no=$this->uri->segment(3);
        $participant=$this->uri->segment(4);
        $data['ref_no']=$ref_no;
        $data['head'] = $this->super_model->custom_query("SELECT DISTINCT reference_number FROM purchase_transaction_head WHERE reference_number!=''");
        //$data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant GROUP BY settlement_id");
        $sql="";
        if($participant!='null'){
            $sql.= "pd.short_name = '$participant' AND ";
        } 
        if($ref_no!='null'){
            $sql.= "pth.reference_number = '$ref_no' AND ";
        }

        $query=substr($sql,0,-4);
        $qu = " WHERE saved='1' AND ".$query;
        //$query=substr($sql,0,-3);
        foreach($this->super_model->custom_query("SELECT pd.purchase_details_id,ph.purchase_id,ph.payment_date,ph.payment_mode,pd.purchase_mode,pd.purchase_amount,pd.vat,pd.ewt,pd.total_amount FROM payment_head ph INNER JOIN payment_details pd ON ph.payment_id=pd.payment_id INNER JOIN purchase_transaction_head pth ON ph.purchase_id=pth.purchase_id INNER JOIN purchase_transaction_details ptd ON pd.purchase_details_id=ptd.purchase_detail_id $qu") AS $d){
            $billing_id=$this->super_model->select_column_where("purchase_transaction_details","billing_id","purchase_detail_id",$d->purchase_details_id);
            $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$billing_id);
            $data['details'][]=array(
                'purchase_id'=>$d->purchase_id,
                'payment_date'=>$d->payment_date,
                'company_name'=>$company_name,
                'payment_mode'=>$d->payment_mode,
                'purchase_mode'=>$d->purchase_mode,
                'purchase_amount'=>$d->purchase_amount,
                'vat'=>$d->vat,
                'ewt'=>$d->ewt,
                'total_amount'=>$d->total_amount,
            );
        }
        $this->load->view('purchases/paid_list',$data);
        $this->load->view('template/footer');
    }
    
    public function add_payment(){
        $purchase_id=$this->uri->segment(3);
        $purchase_detail_id=$this->uri->segment(4);
        $data['purchase_id']=$purchase_id;
        $data['purchase_detail_id']=$purchase_detail_id;
        $this->load->view('template/header');
        foreach($this->super_model->select_custom_where("purchase_transaction_details","purchase_detail_id='$purchase_detail_id' AND purchase_id='$purchase_id'") AS $p){
            if($p->vatables_purchases!=0){
                $mode_name='Vatable Purchase';
            }else if($p->zero_rated!=0){
                $mode_name='Zero-Rated Purchase';
            }else if($p->zero_rated_ecozones!=0){
                $mode_name='Zero-Rated Ecozones Purchase';
            }
            $data['mode_name']=$mode_name;
            $data['amount_due']=$p->total_amount;
            /*$data['payment'][]=array(
                "vat_on_purchases"=>$p->vat_on_purchases,
                "company_name"=>$company_name,
                "ewt"=>$p->ewt,
                "mode_name"=>$mode_name,
            );*/
        }
        $this->load->view('purchases/add_payment',$data);
        $this->load->view('template/footer');
    }

    public function save_payment(){

       
       $purchase_id=$this->input->post('purchase_id');
        $purchase_detail_id=$this->input->post('purchase_detail_id');
        $short_name = $this->super_model->select_column_where("purchase_transaction_details", "short_name", "purchase_detail_id",$purchase_detail_id);
        $payment_date=$this->input->post('payment_date');
        $particulars=$this->input->post('particulars');
        $purchase_mode=$this->input->post('purchase_mode');
        $purchase_amount=$this->input->post('purchase_amount');
        $vat=$this->input->post('vat');
        $ewt=$this->input->post('ewt');
        $total_amount=$this->input->post('total_amount');

        $payment_mode=$this->input->post('customRadioInline1');
        $check_no=$this->input->post('check_no');
        $cv_no=$this->input->post('cv_no');
       
        $check_date=$this->input->post('check_date');
        $pcv=$this->input->post('pcv');
        $reference_number=$this->super_model->select_column_where("purchase_transaction_head","reference_number","purchase_id",$purchase_id);
        $data_insert=array(
            'purchase_id'=>$purchase_id,
            'payment_date'=>$payment_date,
            'particulars'=>$particulars,
            'total_purchase'=>$purchase_amount,
            'total_vat'=>$vat,
            'total_ewt'=>$ewt,
            'total_amount'=>$total_amount,
            'payment_mode'=>$payment_mode,
            'pcv'=>$pcv,
            'check_no'=>$check_no,
            'cv_no'=>$cv_no,
            'check_date'=>$check_date,
            'create_date'=>date("Y-m-d h:i:s"),
            'user_id'=>$_SESSION['user_id'],
        );
        $payment_id = $this->super_model->insert_return_id("payment_head", $data_insert);


        $data_details = array(
            'payment_id'=>$payment_id,
            'purchase_details_id'=>$purchase_detail_id,
            'short_name'=>$short_name,
            'purchase_mode'=>$purchase_mode,
            'purchase_amount'=>$purchase_amount,
            'vat'=>$vat,
            'ewt'=>$ewt,
            'total_amount'=>$total_amount,

        );

        $this->super_model->insert_into("payment_details", $data_details);

        $balance = $this->super_model->select_column_where("purchase_transaction_details", "balance", "purchase_detail_id", $purchase_detail_id);
        $new_balance = $balance - $total_amount;
        $update=array(
            'balance'=>$new_balance
        );

        $this->super_model->update_where("purchase_transaction_details", $update, "purchase_detail_id", $purchase_detail_id);
        echo $reference_number;
    }

    /*public function save_payment_all(){

       
        $purchase_id=$this->input->post('purchase_id');
        $count=$this->input->post('count');
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
        $reference_number=$this->super_model->select_column_where("purchase_transaction_head","reference_number","purchase_id",$purchase_id);
        $data_insert=array(
            'purchase_id'=>$purchase_id,
            'payment_date'=>$payment_date,
            'particulars'=>$particulars,
            'total_purchase'=>$total_vatable_purchase,
            'total_vat'=>$total_vat,
            'total_ewt'=>$total_ewt,
            'total_amount'=>$total_amount,
            'payment_mode'=>$payment_mode,
            'pcv'=>$pcv,
            'check_no'=>$check_no,
            'cv_no'=>$cv_no,
            'check_date'=>$check_date,
            'create_date'=>date("Y-m-d h:i:s"),
            'user_id'=>$_SESSION['user_id'],
        );
        $payment_id = $this->super_model->insert_return_id("payment_head", $data_insert);
        
        
        foreach($this->super_model->select_custom_where("purchase_transaction_details", "purchase_id= '$purchase_id' AND balance != '0'") AS $det ){


            //echo "hi";
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
            //echo $mode . " " . $amount;

            $data_details = array(
                'payment_id'=>$payment_id,
                'purchase_details_id'=>$det->purchase_detail_id,
                'short_name'=>$det->short_name,
                'purchase_mode'=>$mode,
                'purchase_amount'=>$amount,
                'vat'=>$det->vat_on_purchases,
                'ewt'=>$det->ewt,
                'total_amount'=>$det->balance,

            );
           

            $this->super_model->insert_into("payment_details", $data_details);

            
            $new_balance = $det->balance - $det->total_amount;
            $update=array(
                'balance'=>$new_balance
            );

            $this->super_model->update_where("purchase_transaction_details", $update, "purchase_detail_id", $det->purchase_detail_id);

        }
        
      
        //echo $reference_number;
        echo $purchase_id;
    }*/

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
        $purchase_id=$this->input->post('purchase_id');
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
                'purchase_id'=>$purchase_id[$a],
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
            $payment_id = $this->super_model->insert_return_id("payment_head", $data_insert);
            foreach($this->super_model->select_custom_where("purchase_transaction_details", "purchase_id= '".$purchase_id[$a]."' AND balance != '0'") AS $det ){
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
                    'payment_id'=>$payment_id,
                    'purchase_details_id'=>$det->purchase_detail_id,
                    'short_name'=>$det->short_name,
                    'purchase_mode'=>$mode,
                    'purchase_amount'=>$amount,
                    'vat'=>$det->vat_on_purchases,
                    'ewt'=>$det->ewt,
                    'total_amount'=>$det->balance,

                );
                $this->super_model->insert_into("payment_details", $data_details);
                $new_balance = $det->balance - $det->total_amount;
                $update=array(
                    'balance'=>$new_balance
                );
                $this->super_model->update_where("purchase_transaction_details", $update, "purchase_detail_id", $det->purchase_detail_id);
            }
        }
        echo $payment_identifier;
    }

    public function purchases_wesm(){
        $ref_no=$this->uri->segment(3);
        $data['ref_no']=$ref_no;
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT reference_number FROM purchase_transaction_head WHERE reference_number!=''");
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_details pd INNER JOIN purchase_transaction_head ph ON pd.purchase_id=ph.purchase_id WHERE saved='1' AND reference_number LIKE '%$ref_no%'") AS $d){
            $data['details'][]=array(
                'purchase_detail_id'=>$d->purchase_detail_id,
                'purchase_id'=>$d->purchase_id,
                'item_no'=>$d->item_no,
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
                'reference_number'=>$d->reference_number,
                'transaction_date'=>$d->transaction_date,
                'billing_from'=>$d->billing_from,
                'billing_to'=>$d->billing_to,
                'due_date'=>$d->due_date,
                'print_counter'=>$d->print_counter
            );
        }
        $this->load->view('purchases/purchases_wesm',$data);
        $this->load->view('template/footer');
    }
    public function add_details_wesm()
    {
        $purchase_detail_id = $this->uri->segment(3);
        $data['purchase_detail_id']=$purchase_detail_id;
        $this->load->view('template/header');
        $this->load->view('purchases/add_details_wesm',$data);
        $this->load->view('template/footer');
    }

    public function save_serialno(){
        $purchase_detail_id = $this->input->post('purchase_detail_id');
        $serial_no = $this->input->post('serial_no');
        $data_head = array(
            'serial_no'=>$serial_no
        );
        $this->super_model->update_where("purchase_transaction_details",$data_head, "purchase_detail_id", $purchase_detail_id);
        echo $purchase_detail_id;
    }


    public function print_2307()
    {
        $purchase_id = $this->uri->segment(3);
        $purchase_detail_id = $this->uri->segment(4);
        $data['purchase_detail_id']=$purchase_detail_id;

        $data['short_name'] = $this->super_model->select_column_where("purchase_transaction_details", "short_name", "purchase_detail_id", $purchase_detail_id);

        $reference_number = $this->super_model->select_column_where("purchase_transaction_head", "reference_number", "purchase_id", $purchase_id);
        $billing_to = $this->super_model->select_column_where("purchase_transaction_head", "billing_to", "purchase_id", $purchase_id);
        $data['billing_month'] = date('my',strtotime($billing_to));
        $data['refno'] =preg_replace("/[^0-9]/", "", $reference_number);
        

        $billing_id=$this->super_model->select_column_where("purchase_transaction_details", "billing_id", "purchase_detail_id", $purchase_detail_id);

        $data['tin']=$this->super_model->select_column_where("participant", "tin", "billing_id", $billing_id);
        $data['name']=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $billing_id);
        $data['address']=$this->super_model->select_column_where("participant", "registered_address", "billing_id", $billing_id);
        $data['zip']=$this->super_model->select_column_where("participant", "zip_code", "billing_id", $billing_id);
        $billing_to=$this->super_model->select_column_where("purchase_transaction_head", "billing_to", "purchase_id", $purchase_id);
        $data['reference_no']=$this->super_model->select_column_where("purchase_transaction_head", "reference_number", "purchase_id", $purchase_id);
        $data['item_no']=$this->super_model->select_column_where("purchase_transaction_details", "item_no", "purchase_detail_id", $purchase_detail_id);

        $month= date("n",strtotime($billing_to));
        $yearQuarter = ceil($month / 3);
        $first = array(1,4,7,10);
        $second = array(2,5,8,11);
        $third = array(3,6,9,12);

        $vatable_purchase = $this->super_model->select_column_where("purchase_transaction_details", "vatables_purchases", "purchase_detail_id", $purchase_detail_id);
        $zero_rated = $this->super_model->select_column_where("purchase_transaction_details", "zero_rated_purchases", "purchase_detail_id", $purchase_detail_id);
        $zero_rated_ecozone = $this->super_model->select_column_where("purchase_transaction_details", "zero_rated_ecozones", "purchase_detail_id", $purchase_detail_id);

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

         $data['ewt'] = $this->super_model->select_column_where("purchase_transaction_details", "ewt", "purchase_detail_id", $purchase_detail_id);

        if($yearQuarter ==1){
            $period_from = "0101".date("Y");
            $period_to = "0331".date("Y");
        } else if($yearQuarter == 2){
            $period_from = "0401".date("Y");
            $period_to = "0630".date("Y");
        } else if($yearQuarter == 3){
            $period_from = "0701".date("Y");
            $period_to = "0930".date("Y");
        } else if($yearQuarter == 4){
            $period_from = "1001".date("Y");
            $period_to = "1231".date("Y");
        }

        $data['period_from'] = $period_from;
        $data['period_to'] = $period_to;

 
        $this->load->view('purchases/print_2307',$data);
    }


    public function bulk_print_2307()
    {
        $purchase_id = $this->uri->segment(3);
        $purchase_detail_id = $this->uri->segment(4);
        $data['purchase_detail_id']=$purchase_detail_id;

        $data['short_name'] = $this->super_model->select_column_where("purchase_transaction_details", "short_name", "purchase_detail_id", $purchase_detail_id);

        $reference_number = $this->super_model->select_column_where("purchase_transaction_head", "reference_number", "purchase_id", $purchase_id);
        $billing_to = $this->super_model->select_column_where("purchase_transaction_head", "billing_to", "purchase_id", $purchase_id);
        $data['billing_month'] = date('my',strtotime($billing_to));
        $data['refno'] =preg_replace("/[^0-9]/", "", $reference_number);
        

        $billing_id=$this->super_model->select_column_where("purchase_transaction_details", "billing_id", "purchase_detail_id", $purchase_detail_id);

        $data['tin']=$this->super_model->select_column_where("participant", "tin", "billing_id", $billing_id);
        $data['name']=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $billing_id);
        $data['address']=$this->super_model->select_column_where("participant", "registered_address", "billing_id", $billing_id);
        $data['zip']=$this->super_model->select_column_where("participant", "zip_code", "billing_id", $billing_id);
        $billing_to=$this->super_model->select_column_where("purchase_transaction_head", "billing_to", "purchase_id", $purchase_id);
        $data['reference_no']=$this->super_model->select_column_where("purchase_transaction_head", "reference_number", "purchase_id", $purchase_id);
        $data['item_no']=$this->super_model->select_column_where("purchase_transaction_details", "item_no", "purchase_detail_id", $purchase_detail_id);

        $month= date("n",strtotime($billing_to));
        $yearQuarter = ceil($month / 3);
        $first = array(1,4,7,10);
        $second = array(2,5,8,11);
        $third = array(3,6,9,12);

        $vatable_purchase = $this->super_model->select_column_where("purchase_transaction_details", "vatables_purchases", "purchase_detail_id", $purchase_detail_id);
        $zero_rated = $this->super_model->select_column_where("purchase_transaction_details", "zero_rated_purchases", "purchase_detail_id", $purchase_detail_id);
        $zero_rated_ecozone = $this->super_model->select_column_where("purchase_transaction_details", "zero_rated_ecozones", "purchase_detail_id", $purchase_detail_id);

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

         $data['ewt'] = $this->super_model->select_column_where("purchase_transaction_details", "ewt", "purchase_detail_id", $purchase_detail_id);

        if($yearQuarter ==1){
            $period_from = "0101".date("Y");
            $period_to = "0331".date("Y");
        } else if($yearQuarter == 2){
            $period_from = "0401".date("Y");
            $period_to = "0630".date("Y");
        } else if($yearQuarter == 3){
            $period_from = "0701".date("Y");
            $period_to = "0930".date("Y");
        } else if($yearQuarter == 4){
            $period_from = "1001".date("Y");
            $period_to = "1231".date("Y");
        }

        $data['period_from'] = $period_from;
        $data['period_to'] = $period_to;

 
        $this->load->view('purchases/bulk_print_2307',$data);
    }


    public function print_2307sample()
    {   
        $this->load->view('purchases/print_2307sample');
    }
    public function print_2307test()
    {   
        $this->load->view('purchases/print_2307test');
    }

    public function pay_all(){

        $purchase_id = $this->uri->segment(3);
        $data['purchase_id'] = $purchase_id;
        $data['total_amount']= $this->super_model->select_sum("purchase_transaction_details", "balance", "purchase_id", $purchase_id);
        $total_purchase= $this->super_model->select_sum("purchase_transaction_details", "vatables_purchases", "purchase_id", $purchase_id);
        $zero_rated= $this->super_model->select_sum("purchase_transaction_details", "zero_rated_purchases", "purchase_id", $purchase_id);
        $ecozone= $this->super_model->select_sum("purchase_transaction_details", "zero_rated_ecozones", "purchase_id", $purchase_id);

        $data['total_vatable_purchase'] = $total_purchase + $zero_rated + $ecozone;
        $data['total_vat']= $this->super_model->select_sum("purchase_transaction_details", "vat_on_purchases", "purchase_id", $purchase_id);
        $data['total_ewt']= $this->super_model->select_sum("purchase_transaction_details", "ewt", "purchase_id", $purchase_id);

        $this->load->view('template/header');
        $this->load->view('purchases/pay_all',$data);
        $this->load->view('template/footer');
    }

    public function payment_form(){
        $payment_identifier = $this->uri->segment(3);
        $this->load->view('template/print_head');
        foreach($this->super_model->custom_query("SELECT * FROM payment_head WHERE payment_identifier='$payment_identifier' GROUP BY purchase_id") AS $p){
           /* $vatable_purchase= $this->super_model->select_sum("purchase_transaction_details", "vatables_purchases", "purchase_id", $p->purchase_id);
            $zero_rated= $this->super_model->select_sum("purchase_transaction_details", "zero_rated_purchases", "purchase_id", $p->purchase_id);
            $zero_rated_ecozone= $this->super_model->select_sum("purchase_transaction_details", "zero_rated_ecozones", "purchase_id", $p->purchase_id);
            $energy=$vatable_purchase+$zero_rated+$zero_rated_ecozone;
            $vat_on_purchases= $this->super_model->select_sum("purchase_transaction_details", "vat_on_purchases", "purchase_id", $p->purchase_id);
            $ewt= $this->super_model->select_sum("purchase_transaction_details", "ewt", "purchase_id", $p->purchase_id);*/
            //$zero_rated= $this->super_model->select_sum("payment_details", "zero_rated_purchases", "payment_id", $p->payment_id);
            //$zero_rated_ecozone= $this->super_model->select_sum("payment_details", "zero_rated_ecozones", "payment_id", $p->payment_id);
            //$energy=$vatable_purchase+$zero_rated+$zero_rated_ecozone;
            $vatable_purchase= $this->super_model->select_sum("payment_head", "total_purchase", "purchase_id", $p->purchase_id);
            $energy=$vatable_purchase;
            $energy_total= $this->super_model->select_sum("payment_head", "total_purchase", "payment_identifier", $p->payment_identifier);
            $vat_on_purchases= $this->super_model->select_sum("payment_head", "total_vat", "purchase_id", $p->purchase_id);
            $vat_on_purchases_total= $this->super_model->select_sum("payment_head", "total_vat", "payment_identifier", $p->payment_identifier);
            $ewt= $this->super_model->select_sum("payment_head", "total_ewt", "purchase_id", $p->purchase_id);
            $ewt_total= $this->super_model->select_sum("payment_head", "total_ewt", "payment_identifier", $p->payment_identifier);
            $reference_number=$this->super_model->select_column_where("purchase_transaction_head","reference_number","purchase_id",$p->purchase_id);
            $total_amount= $this->super_model->select_sum("payment_head", "total_amount", "purchase_id", $p->purchase_id);
            $total_amount_disp= $this->super_model->select_sum("payment_head", "total_amount", "payment_identifier", $p->payment_identifier);
            //$total_amount=($energy + $vat_on_purchases) - $ewt;
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
        $this->load->view('purchases/payment_form',$data);
    }

    public function download_bulk(){
        $refno = $this->input->post('refno');
        $purchase_id = $this->super_model->select_column_where('purchase_transaction_head', 'purchase_id', 'reference_number', $refno);
        $billing_to = $this->super_model->select_column_where('purchase_transaction_head', 'billing_to', 'reference_number', $refno);
          $month= date("n",strtotime($billing_to));
            $yearQuarter = ceil($month / 3);
            $first = array(1,4,7,10);
            $second = array(2,5,8,11);
            $third = array(3,6,9,12);

            if($yearQuarter ==1){
                $period_from = "0101".date("Y");
                $period_to = "0331".date("Y");
            } else if($yearQuarter == 2){
                $period_from = "0401".date("Y");
                $period_to = "0630".date("Y");
            } else if($yearQuarter == 3){
                $period_from = "0701".date("Y");
                $period_to = "0930".date("Y");
            } else if($yearQuarter == 4){
                $period_from = "1001".date("Y");
                $period_to = "1231".date("Y");
            }

           
        $x=0;
        foreach($this->super_model->select_row_where('purchase_transaction_details', 'purchase_id', $purchase_id) AS $det){ 
            $tin=$this->super_model->select_column_where("participant", "tin", "billing_id", $det->billing_id);
            if(!empty($tin)){
              $tin=explode("-",$tin);
            } else {
              $tin2='000-000-000-000';
              $tin=explode("-",$tin2);
            }


            $name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $det->billing_id);
            $address=$this->super_model->select_column_where("participant", "registered_address", "billing_id", $det->billing_id);
            $zip=$this->super_model->select_column_where("participant", "zip_code", "billing_id", $det->billing_id);

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
            /**/
            $billing_month = date('my',strtotime($billing_to));
            $timestamp=date('Ymd');
            $str= '<script src="'.base_url().'assets/js/jquery-1.12.4.js"></script><script src="'.base_url().'assets/js/jspdf.min.js"></script><script src="'.base_url().'assets/js/html2canvas.js"></script><script src="'.base_url().'assets/js/purchases.js"></script> <link rel="stylesheet" href="'.base_url().'assets/css/print2307-style.css"><input type="hidden" class="shortname" value="'.$det->short_name.'" id="shortname'.$x.'"><input type="hidden" class="ref_no" id="ref_no'.$x.'" value="'.$refno.'"><input type="hidden" class="billing_month" id="billing_month'.$x.'" value="'.$billing_month.'"><input type="hidden" class="timestamp"  id="timestamp'.$x.'" value="'.$timestamp.'"><div id="contentPDF" ><page size="Long" id="printableArea'.$x.'" class="canvas_div_pdf"><img class="img2307" src="'.base_url().'assets/img/form2307.jpg" style="width: 100%;"><label class="period_from ">'.$period_from.'</label><label class="period_to">'. $period_to.'</label>';
                if(!empty($tin[1])){ 
                    $str.='<div class="tin1"><label class="">'.$tin[0].'</label><label class="">'.$tin[1].'</label><label class="">'.$tin[2].'</label><label class="last1">0000</label> </div>';
                } else {

                   $str.='<div class="tin1"><label class=""></label><label class=""></label><label class=""></label><label class="last1">0000</label></div>';
                }

                $str.='<label class="payee">'.$name.'</label><label class="address1">'.$address.'</label><label class="zip1">'.$zip.'</label><label class="address2"></label><div class="tin2"><label class="">008</label><label class="">691</label><label class="">287</label><label class="last1">0000</label></div><label class="payor">CENTRAL NEGROS POWER RELIABILITY, INC.</label><label class="address3">COR. RIZAL - MABINI STREETS, BACOLOD CITY</label><label class="zip2">6100</label><label class="row1-col1">Income payment made by top withholding agents to their local/resident supplier of services other than those covered by other rates of withholding tax</label><label class="row1-col2">WC160</label><label class="row1-col3">'. (($firstmonth=="-") ? "-" : number_format($firstmonth,2)).'</label><label class="row1-col4">'. (($secondmonth=="-") ? "-" : number_format($secondmonth,2)).'</label><label class="row1-col5">'.(($thirdmonth=="-") ? "-" : number_format($thirdmonth,2)).'</label><label class="row1-col6">'. number_format($total,2).'</label><label class="row1-col7">'.number_format($det->ewt,2) .'<span class="hey">&nbsp;&nbsp;</span></label><label class="row2-col3">'.(($firstmonth=="-") ? "-" : number_format($firstmonth,2)).'</label><label class="row2-col4">'. (($secondmonth=="-") ? "-" : number_format($secondmonth,2)).'</label><label class="row2-col5">'. (($thirdmonth=="-") ? "-" : number_format($thirdmonth,2)).'</label><label class="row2-col6">'. number_format($total,2).'</label><label class="row2-col7">'. number_format($det->ewt,2) .'<span>&nbsp;&nbsp;</span></label><label class="row2-col8"> Reference Number: <b>'. $refno.'</b></label><label class="row2-col9"> Item Number: <b>'. $det->item_no.'</b></label></page></div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';

            echo $str;
            $str_download='<script> document.getElementsByClassName("button_click")[0].click();</script><button onclick=getDownload('.$x.') type="button" class="button_click" hidden>DONWLOAD</button>';
            echo $str_download;
            $x++;
        }

    }

    public function upload_purchases_adjustment(){

        $identifier_code=$this->generateRandomString();
        $data['identifier_code']=$identifier_code;
        $data['identifier']=$this->uri->segment(3);
        $identifier=$this->uri->segment(3);
        $data['saved']=$this->super_model->select_column_where("purchase_transaction_head","saved","adjust_identifier",$identifier);
        $data['head']=$this->super_model->select_row_where("purchase_transaction_head","adjust_identifier",$identifier);
        $ref_no=$this->super_model->select_column_where("purchase_transaction_head","reference_number", "adjust_identifier" ,$identifier);
        //echo $ref_no;
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_details ptd INNER JOIN purchase_transaction_head pth ON ptd.purchase_id=pth.purchase_id WHERE adjust_identifier='$identifier' AND adjustment='1'") AS $d){

            $data['details'][]=array(
                'purchase_detail_id'=>$d->purchase_detail_id,
                'purchase_id'=>$d->purchase_id,
                'item_no'=>$d->item_no,
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
                'print_counter'=>$d->print_counter,
                'reference_number'=>$d->reference_number,
                'billing_from'=>$d->billing_from,
                'billing_to'=>$d->billing_to,
                'transaction_date'=>$d->transaction_date,
                'due_date'=>$d->due_date,
                'remarks'=>$d->adjustment_remarks,
            );
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('purchases/upload_purchases_adjustment',$data);
        $this->load->view('template/footer');
    }

    public function upload_purchase_adjust(){
        
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $count = $this->input->post('count');
        $adjust_identifier = $this->input->post('adjust_identifier');
        for($x=0;$x<$count;$x++){
            //echo $x;
            //$remarks = $this->input->post('remarks['.$x.']');
            $fileupload = $this->input->post('fileupload['.$x.']');
            $dest= realpath(APPPATH . '../uploads/excel/');
            $error_ext=0;
            if(!empty($_FILES['fileupload']['name'][$x])){
                $exc= basename($_FILES['fileupload']['name'][$x]);
                $exc=explode('.',$exc);
                $ext1=$exc[1];
                if($ext1=='php' || $ext1!='xlsx'){
                    $error_ext++;
                }else {
                    $filename1='wesm_purchases_adjust'.$x.".".$ext1;
                    if(move_uploaded_file($_FILES["fileupload"]['tmp_name'][$x], $dest.'/'.$filename1)){
                        //for($a=0;$a<$count;$a++){
                            $inputFileName =realpath(APPPATH.'../uploads/excel/wesm_purchases_adjust'.$x.'.xlsx');
                            try {
                                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                            
                       
                                $objPHPExcel = $objReader->load($inputFileName);
                            } 
                            catch(Exception $e) {
                                die('Error loading file"'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
                            }
                            $objPHPExcel->setActiveSheetIndex(2);

                            $reference_number = trim($objPHPExcel->getActiveSheet()->getCell('A2')->getFormattedValue());
                            $transaction_date = trim($objPHPExcel->getActiveSheet()->getCell('B2')->getFormattedValue());
                            $billing_from = trim($objPHPExcel->getActiveSheet()->getCell('C2')->getFormattedValue());
                            $billing_to = trim($objPHPExcel->getActiveSheet()->getCell('D2')->getFormattedValue());
                            $due_date = trim($objPHPExcel->getActiveSheet()->getCell('E2')->getFormattedValue());
                            $remarks = $this->input->post('remarks['.$x.']');
                            $data_insert=array(
                                'reference_number'=>$reference_number,
                                'transaction_date'=>$transaction_date,
                                'billing_from'=>$billing_from,
                                'billing_to'=>$billing_to,
                                'due_date'=>$due_date,
                                'user_id'=>$_SESSION['user_id'],
                                'saved'=>0,
                                'create_date'=>date('Y-m-d H:i:s'),
                                'adjustment'=>1,
                                'adjust_identifier'=>$adjust_identifier,
                                'adjustment_remarks'=>$remarks,
                            );
                            $this->super_model->insert_into("purchase_transaction_head", $data_insert);

                            $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 
                            $highestRow = $highestRow-1;
                            $y=1;
                            for($z=4;$z<$highestRow;$z++){
                                $itemno = trim($objPHPExcel->getActiveSheet()->getCell('A'.$z)->getOldCalculatedValue());
                                $shortname = trim($objPHPExcel->getActiveSheet()->getCell('B'.$z)->getFormattedValue());
                                $billing_id = trim($objPHPExcel->getActiveSheet()->getCell('C'.$z)->getFormattedValue());   
                                $fac_type = trim($objPHPExcel->getActiveSheet()->getCell('D'.$z)->getFormattedValue());
                                $wht_agent = trim($objPHPExcel->getActiveSheet()->getCell('E'.$z)->getFormattedValue());
                                $ith = trim($objPHPExcel->getActiveSheet()->getCell('F'.$z)->getFormattedValue());
                                $non_vatable = trim($objPHPExcel->getActiveSheet()->getCell('G'.$z)->getFormattedValue());
                                $zero_rated = trim($objPHPExcel->getActiveSheet()->getCell('H'.$z)->getFormattedValue());
                                $vatables_purchases = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('I'.$z)->getFormattedValue());
                                $zero_rated_purchases = trim($objPHPExcel->getActiveSheet()->getCell('J'.$z)->getFormattedValue(),'()');
                                 $zero_rated_purchases = trim($zero_rated_purchases,"-");
                                $zero_rated_ecozone = trim($objPHPExcel->getActiveSheet()->getCell('K'.$z)->getFormattedValue(),'()');
                                 $zero_rated_ecozone = trim($zero_rated_ecozone,"-");
                                $vat_on_purchases = trim($objPHPExcel->getActiveSheet()->getCell('L'.$z)->getFormattedValue(),'()');
                                $vat_on_purchases = trim($vat_on_purchases,"-");
                                $ewt = trim($objPHPExcel->getActiveSheet()->getCell('M'.$z)->getFormattedValue(),'()');
                                $total_amount = trim($objPHPExcel->getActiveSheet()->getCell('N'.$z)->getOldCalculatedValue(),'()');
                                $total_amount = trim($total_amount,"-");
                                //$total_amount = ($vatables_purchases + $zero_rated + $zero_rated_purchases + $vat_on_purhcases) - $ewt;
                                $count_max=$this->super_model->count_rows("purchase_transaction_head");
                                if($count_max==0){
                                    $purchase_id=1;
                                }else{
                                    $purchase_id = $this->super_model->get_max("purchase_transaction_head", "purchase_id");
                                }
                                $data_purchase = array(
                                    'purchase_id'=>$purchase_id,
                                    'item_no'=>$y,
                                    'short_name'=>$shortname,
                                    'billing_id'=>$billing_id,
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
                                    'balance'=>$total_amount
                                    //'balance'=>$total_amount
                                );
                                $this->super_model->insert_into("purchase_transaction_details", $data_purchase);
                                $y++;
                            }
                        //}
                        //$this->readExcel_adjust($adjust_identifier,$x,$remarks);
                    } 
                }
            }
        }
        echo $adjust_identifier;
    }

    public function save_alladjust(){
        $adjust_identifier = $this->input->post('adjust_identifier');
        $data_head = array(
            'saved'=>1
        );
        $this->super_model->update_where("purchase_transaction_head",$data_head, "adjust_identifier", $adjust_identifier);
        echo $adjust_identifier;
    }
}