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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat as number_format;
use PhpOffice\PhpSpreadsheet\Style\Fill as fill; // Instead fill
use PhpOffice\PhpSpreadsheet\Style\Color as color; //Instead PHPExcel_Style_Color
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup as pagesetup; // Instead PHPExcel_Worksheet_PageSetup
use PhpOffice\PhpSpreadsheet\IOFactory as io_factory; // Instead PHPExcel_IOFactory

class SalesMerge extends CI_Controller {

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

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function convertNumber(float $amount){

    $hyphen = ' ';
    $conjunction = ' and ';
    $separator = ' ';
    $negative = 'negative ';
    $decimal = ' and ';
    $dictionary = array(
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Fourty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety',
        100 => 'Hundred',
        1000 => 'Thousand',
        1000000 => 'Million',
    );

    if (!is_numeric($amount)) {
        return false;
    }

    if ($amount < 0) {
        return $negative . $this->convertNumber(abs($amount));
    }

    $string = $fraction = null;

    if (strpos($amount, '.') !== false) {
        list($amount, $fraction) = explode('.', $amount);
    }

    switch (true) {
        case $amount < 20:
            $string = $dictionary[$amount];
            break;
        case $amount < 100:
            $tens = ((int)($amount / 10)) * 10;
            $units = $amount % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $amount < 1000:
            $hundreds = $amount / 100;
            $remainder = $amount % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . $this->convertNumber($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($amount, 1000)));
            $numBaseUnits = (int)($amount / $baseUnit);
            $remainder = $amount % $baseUnit;
            $string = $this->convertNumber($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= $this->convertNumber($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        if($fraction > 0){
            if($amount == 0  && $fraction != 0){
                $string .= "";
            }else{
                $string .= " pesos and ";
            }
            if($fraction < 20){ 
                //$string .= $decones[$fraction]; 
                $string .= $fraction."/100"; 
            }
            elseif($amount != 0  && $fraction == 0){ 
                $string .= $string." pesos only";  
            }
            elseif($amount == 1  && $fraction == 0){ 
                $string .= $string." peso only";  
            }
            elseif($fraction == 1){ 
                $string .= $string." only";  
                //$string .= $fraction."/100" ." centavo only";  
            }
            elseif($fraction < 100){ 
                //$string .= $tens[substr($fraction,0,1)]; 
                //$string .= " ".$ones[substr($fraction,1,1)]; 
                $string .= " ".$fraction."/100"; 
            }
                $string = $string." only"; 
                //$string = $fraction."/100" ." centavos only"; 
        }
    }

        return $string;

    }

    public function upload_sales_merge(){
        $id=$this->uri->segment(3);
        $sub=$this->uri->segment(4);
        $data['sales_id'] = $id;
        $data['sub'] = $sub;
        $data['identifier_code']=$this->generateRandomString();
        $data['count_name'] = $this->super_model->count_custom_where("sales_merge_transaction_details", "company_name = '' AND sales_merge_id ='$id'"); 
        $data['count_empty_actual']=0;
        if(!empty($id)){
            foreach($this->super_model->select_row_where("sales_merge_transaction_head", "sales_merge_id",$id) AS $h){
                $data['transaction_date']=$h->transaction_date;
                $data['billing_from']=$h->billing_from;
                $data['billing_to']=$h->billing_to;
                $data['reference_number']=$h->reference_number;
                $data['due_date']=$h->due_date;
                $data['saved']=$h->saved;
                if($sub==0 ||  $sub=='null'){
                    // foreach($this->super_model->select_row_where("sales_merge_transaction_details","sales_merge_id",$h->sales_merge_id) AS $d){
                    foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details sd INNER JOIN sales_merge_transaction_head sh ON sd.sales_merge_id=sh.sales_merge_id WHERE sh.sales_merge_id = '$h->sales_merge_id' ORDER BY CAST(sd.serial_no AS UNSIGNED) ASC") AS $d) {
                        $data['count_empty_actual']=$this->super_model->count_custom_where('sales_merge_transaction_details',"sales_merge_id='$h->sales_merge_id' AND billing_id IS NULL");
                        $data['details'][]=array(
                            'sales_detail_id'=>$d->sales_merge_detail_id,
                            'sales_id'=>$d->sales_merge_id,
                            'item_no'=>$d->item_no,
                            'reference_no'=>$d->reference_no,
                            'short_name'=>$d->short_name,
                            'actual_billing_id'=>$d->actual_billing_id,
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
                }else if($sub==1){
                    // foreach($this->super_model->select_row_where("sales_merge_transaction_details","sales_id",$h->sales_id ORDER BY serial_no ASC) AS $d){
                    foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details sd INNER JOIN sales_merge_transaction_head sh ON sd.sales_merge_id=sh.sales_merge_id WHERE sh.sales_merge_id = '$h->sales_merge_id' ORDER BY CAST(sd.serial_no AS UNSIGNED) ASC") AS $d) {
                        $participant_id = $this->super_model->select_column_custom_where("participant","participant_id","billing_id='$d->billing_id'");
                        $sub_participant = $this->super_model->count_custom_where("subparticipant","sub_participant='$participant_id'");

                        if($sub_participant==0){
                            $data['details'][]=array(
                                'sales_detail_id'=>$d->sales_merge_detail_id,
                                'sales_id'=>$d->sales_merge_id,
                                'item_no'=>$d->item_no,
                                'reference_no'=>$d->reference_no,
                                'short_name'=>$d->short_name,
                                'actual_billing_id'=>$d->actual_billing_id,
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
            }
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales_merge/upload_sales_merge',$data);
        $this->load->view('template/footer');
    }

    public function add_salesmerge_head(){
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
            "create_date"=>date("Y-m-d H:i:s"),
        );
        $sales_id = $this->super_model->insert_return_id("sales_merge_transaction_head",$data);

        echo $sales_id;
    }

     public function upload_salesmerge_process(){
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
                $filename1='wesm_sales_merge.'.$ext1;
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                    $this->readExcel_inv($sales_id);
                } 
            }
        }
    }

    public function readExcel_inv($sales_id){
        $objPHPExcel = new Spreadsheet();
        $inputFileName =realpath(APPPATH.'../uploads/excel/wesm_sales_merge.xlsx');
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
        $y=1;
        for($x=3;$x<$highestRow;$x++){
            $shortname = str_replace(array(' '), '',$objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue());
        if($shortname!="" || !empty($shortname)){
         
            $actual_billing_id = str_replace(array(' '), '',$objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue());
            $company_name =trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getOldCalculatedValue());
            $tin = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue());
            $fac_type = trim($objPHPExcel->getActiveSheet()->getCell('F'.$x)->getFormattedValue());
            $unique_bill_id = $this->super_model->select_column_custom_where("participant", "billing_id", "actual_billing_id = '$actual_billing_id' AND settlement_id = '$shortname' AND tin = '$tin'");
            $wht_agent = trim($objPHPExcel->getActiveSheet()->getCell('G'.$x)->getFormattedValue());
            $ith = trim($objPHPExcel->getActiveSheet()->getCell('H'.$x)->getFormattedValue());
            $non_vatable = trim($objPHPExcel->getActiveSheet()->getCell('I'.$x)->getFormattedValue());
            $zero_rated = trim($objPHPExcel->getActiveSheet()->getCell('J'.$x)->getFormattedValue());
            $vatable_sales = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('K'.$x)->getFormattedValue());
            $zero_rated_sales = $objPHPExcel->getActiveSheet()->getCell('K'.$x)->getFormattedValue();
            $zero_rated_ecozone = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('L'.$x)->getFormattedValue());
            $vat_on_sales = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('M'.$x)->getFormattedValue());
            $ewt = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('N'.$x)->getFormattedValue());
            $total_amount = ((double)$vatable_sales+(double)$zero_rated_ecozone+(double)$vat_on_sales)-(double)$ewt;
            $series_no = $objPHPExcel->getActiveSheet()->getCell('P'.$x)->getFormattedValue();
            $reference_no = str_replace(array(' '), '',$objPHPExcel->getActiveSheet()->getCell('Q'.$x)->getFormattedValue());
            
            if ($vatable_sales === '') {
                $vatable_checker=0;
            }else{
                $vatable_checker=str_replace(array('-','',' '),'0',$vatable_sales); 
            }
            if ($zero_rated_ecozone === '') {
                $zero_rated_ecozone_checker=0;
            }else{
                $zero_rated_ecozone_checker=str_replace(array('-','',' '),'0',$zero_rated_ecozone);
            }
            if ($vat_on_sales === '') {
                $vat_on_sales_checker=0;
            }else{
                $vat_on_sales_checker=str_replace(array('-','',' '),'0',$vat_on_sales); 
            }
            if ($ewt === '') {
                $ewt_checker=0;
            }else{
                $ewt_checker=str_replace(array('-','',' '),'0',$ewt); 
            }
            $error=array();
            if (!is_numeric($vatable_checker)){
                $error[]='error';
            }
            if(!is_numeric($zero_rated_ecozone_checker)){
                $error[]='error';
            }
            if(!is_numeric($vat_on_sales_checker)){
                $error[]='error';
            }
            if(!is_numeric($ewt_checker)){
                $error[]='error';
            }

            if(in_array('error',$error)){
                echo 'error';
                $this->super_model->delete_where('sales_merge_transaction_details', 'sales_id', $sales_id);
                break;
            }else{
                $data_sales = array(
                    'sales_merge_id'=>$sales_id,
                    'item_no'=>$y,
                    'reference_no'=>$reference_no,
                    'short_name'=>$shortname,
                    'billing_id'=>$unique_bill_id,
                    'actual_billing_id'=>$actual_billing_id,
                    'company_name'=>$company_name,
                    'facility_type'=>$fac_type,
                    'wht_agent'=>$wht_agent,
                    'ith_tag'=>$ith,
                    'non_vatable'=>$non_vatable,
                    'zero_rated'=>$zero_rated,
                    'vatable_sales'=>$vatable_sales,
                    'vat_on_sales'=>$vat_on_sales,
                    'serial_no'=>$series_no,
                    'zero_rated_ecozones'=>$zero_rated_ecozone,
                    'ewt'=>$ewt,
                    'total_amount'=>$total_amount,
                    'balance'=>$total_amount
                );
                $this->super_model->insert_into("sales_merge_transaction_details", $data_sales);
                $y++;
                }
            }
        }
    }

    public function save_all_merge(){
        $sales_id = $this->input->post('sales_id');
        $data_head = array(
            'saved'=>1
        );
        $this->super_model->update_where("sales_merge_transaction_head",$data_head, "sales_merge_id", $sales_id);
        echo $sales_id;
    }

    public function cancel_sales_merge(){
        $sales_id = $this->input->post('sales_id');
        $this->super_model->delete_where("sales_merge_transaction_details", "sales_merge_id", $sales_id);
        $this->super_model->delete_where("sales_merge_transaction_head", "sales_merge_id", $sales_id);
    }

    public function sales_wesm_merge(){
        $ref_no=$this->uri->segment(3);
        $due_date=$this->uri->segment(4);
        $in_ex_sub=$this->uri->segment(5);
        $billfrom=$this->uri->segment(6);
        $billto=$this->uri->segment(7);
        $participants=$this->uri->segment(8);
        $data['ref_no']=$ref_no;
        $data['due_date']=$due_date;
        $data['in_ex_sub']=$in_ex_sub;
        $data['billingfrom']=$billfrom;
        $data['billingto']=$billto;
        $data['part_name']=$participants;
        $data['identifier_code']=$this->generateRandomString();
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT reference_number FROM sales_merge_transaction_head WHERE reference_number!='' AND saved='1' AND deleted='0'");
        $data['date'] = $this->super_model->custom_query("SELECT DISTINCT due_date FROM sales_merge_transaction_head WHERE due_date!='' AND saved='1' AND deleted='0'");
        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant WHERE participant_name != '' GROUP BY tin ORDER BY participant_name");
        $data['participant_name']=$this->super_model->select_column_where('participant','participant_name','tin',$participants);
        $data['count_unsaved'] = $this->super_model->count_custom_where("sales_merge_transaction_head", "saved = '0'");
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        if($in_ex_sub==0 ||  $in_ex_sub=='null'){
            $sql='';
            if($ref_no!='null'){
                $sql.= "sh.reference_number = '$ref_no' AND ";
            }

            if($due_date!='null'){
                $sql.= "sh.due_date = '$due_date' AND ";
            }

            if($billfrom!='null' && $billto!='null'){ 
                $sql.= " ((sh.billing_from BETWEEN '$billfrom' AND '$billto') OR (sh.billing_to BETWEEN '$billfrom' AND '$billto'))  AND ";
            }

            if(!empty($participants) && $participants!='null'){
               $par=array();
               foreach($this->super_model->select_custom_where('participant',"tin='$participants'") AS $p){
                   $par[]="'".$p->settlement_id."'";
               }
               $imp=implode(',',$par);
               $sql.= " sd.short_name IN($imp) AND ";
            }
            $query=substr($sql,0,-4);
            $qu = " WHERE saved='1' AND deleted='0' AND ".$query;
            $total_bs = 0;
            $processed_counts = [];
            foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details sd INNER JOIN sales_merge_transaction_head sh ON sd.sales_merge_id=sh.sales_merge_id $qu ORDER BY serial_no ASC") AS $d){
                $series_number=$this->super_model->select_column_custom_where("merge_collection_details","series_number","reference_no='$d->reference_number' AND settlement_id='$d->short_name'");
                $old_series_no=$this->super_model->select_column_custom_where("merge_collection_details","old_series_no","reference_no='$d->reference_number' AND settlement_id='$d->short_name'");

                if(!empty($d->company_name)){
                    $comp_name=$d->company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $d->billing_id);
                }

                $sid = (int)$d->sales_merge_detail_id;

                // count once per unique sales_detail_id and cache it
                if (!isset($processed_counts[$sid])){
                    $processed_counts[$sid] = $this->super_model->count_custom_where(
                        "bsm_head", "sales_merge_detail_id = '$sid'"
                    );
                    $total_bs += $processed_counts[$sid]; // add to grand total only once
                }

                $data['details'][]=array(
                    'sales_detail_id'=>$d->sales_merge_detail_id,
                    'sales_id'=>$d->sales_merge_id,
                    'item_no'=>$d->item_no,
                    'billing_from'=>$d->billing_from,
                    'billing_to'=>$d->billing_to,
                    'reference_no'=>$d->reference_no,
                    'series_number'=>$series_number,
                    'old_series_no_col'=>$old_series_no,
                    'old_series_no'=>$d->old_series_no,
                    'short_name'=>$d->short_name,
                    'billing_id'=>$d->billing_id,
                    'actual_billing_id'=>$d->actual_billing_id,
                    'company_name'=>$comp_name,
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
                    'due_date'=>$d->due_date,
                    'print_counter'=>$d->print_counter,
                    'ewt_amount'=>$d->ewt_amount,
                    'original_copy'=>$d->original_copy,
                    'scanned_copy'=>$d->scanned_copy
                );
            }
            $data['total_bs'] = $total_bs;
        }else if($in_ex_sub==1){
            $sql='';
            if($ref_no!='null'){
                $sql.= "sh.reference_number = '$ref_no' AND ";
            }

            if($due_date!='null'){
                $sql.= "sh.due_date = '$due_date' AND ";
            }

            if($billfrom!='null' && $billto!='null'){ 
                $sql.= " ((sh.billing_from BETWEEN '$billfrom' AND '$billto') OR (sh.billing_to BETWEEN '$billfrom' AND '$billto'))  AND ";
            }

            if(!empty($participants) && $participants!='null'){
               $par=array();
               foreach($this->super_model->select_custom_where('participant',"tin='$participants'") AS $p){
                   $par[]="'".$p->settlement_id."'";
               }
               $imp=implode(',',$par);
               $sql.= " sd.short_name IN($imp) AND ";
            }
            $query=substr($sql,0,-4);
            $qu = " WHERE saved='1' AND deleted='0' AND ".$query;
                foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details sd INNER JOIN sales_merge_transaction_head sh ON sd.sales_merge_id=sh.sales_merge_id $qu ORDER BY serial_no ASC") AS $d){
                    $participant_id = $this->super_model->select_column_custom_where("participant","participant_id","billing_id='$d->billing_id'");
                    $sub_participant = $this->super_model->count_custom_where("subparticipant","sub_participant='$participant_id'");
                    $series_number=$this->super_model->select_column_custom_where("merge_collection_details","series_number","reference_no='$d->reference_number' AND settlement_id='$d->short_name'");
                    $old_series_no=$this->super_model->select_column_custom_where("merge_collection_details","old_series_no","reference_no='$d->reference_number' AND settlement_id='$d->short_name'");
                    if($sub_participant==0){
                    $data['details'][]=array(
                        'sales_detail_id'=>$d->sales_merge_detail_id,
                        'sales_id'=>$d->sales_merge_id,
                        'item_no'=>$d->item_no,
                        'series_number'=>$series_number,
                        'old_series_no_col'=>$old_series_no,
                        'old_series_no'=>$d->old_series_no,
                        'short_name'=>$d->short_name,
                        'billing_id'=>$d->billing_id,
                        'actual_billing_id'=>$d->actual_billing_id,
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
                        'reference_no'=>$d->reference_no,
                        'transaction_date'=>$d->transaction_date,
                        'billing_from'=>$d->billing_from,
                        'billing_to'=>$d->billing_to,
                        'due_date'=>$d->due_date,
                        'print_counter'=>$d->print_counter,
                        'ewt_amount'=>$d->ewt_amount,
                        'original_copy'=>$d->original_copy,
                        'scanned_copy'=>$d->scanned_copy
                    );
                }
            }
            $data['total_bs'] = 0;
        }
        $this->load->view('sales_merge/sales_wesm_merge', $data);
        $this->load->view('template/footer');
    }

    public function sales_wesm_merge_unsaved(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['details']=array();
        foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_head WHERE saved = '0' AND deleted='0'") AS $d){
            $data['details'][]=array(
                'sales_merge_id'=>$d->sales_merge_id,
                // 'date' => date("Y-m-d", strtotime($d->create_date)),
                'date' => $d->transaction_date,
                'billing_from'=>$d->billing_from,
                'billing_to'=>$d->billing_to,
                'reference_number'=>$d->reference_number,
                'due_date'=>$d->due_date,
            );
        }

        $this->load->view('sales_merge/sales_wesm_merge_unsaved', $data);
        $this->load->view('template/footer');
    }

    public function save_unsaved_merge(){
        $sales_merge_id = $this->input->post('sales_merge_id');
        $data_update = array(
                "saved"=>1,
            );
            $this->super_model->update_custom_where("sales_merge_transaction_head", $data_update, "sales_merge_id='$sales_merge_id'");
    }

    public function reset_bulk_sales_merge(){
        $reference_no = $this->input->post('reference_no');
        $sales_merge_id = $this->super_model->select_column_custom_where("sales_merge_transaction_head","sales_merge_id","reference_number='$reference_no' AND saved='1' AND deleted='0'");
        $data_update = array(
                "bulk_pdf_flag"=>0,
                "filename"=>null,
            );
            $this->super_model->update_custom_where("sales_merge_transaction_details", $data_update, "sales_merge_id='$sales_merge_id'");
    }

    public function delete_saved_sales_merge(){
        $sales_merge_id = $this->input->post('sales_merge_id');
        $data_update = array(
                "deleted"=>1,
                "deleted_by"=>$_SESSION['user_id'],
                "date_deleted"=>date("Y-m-d H:i:s"),
            );
            $this->super_model->update_custom_where("sales_merge_transaction_head", $data_update, "sales_merge_id='$sales_merge_id'");
    }

    public function print_multiple(){
        $identifier=$this->input->post('multiple_print');
        $count=count($identifier);
        $invoice_no_disp='';
        for($x=0;$x<$count;$x++){
            $exp_identifier=explode(",",$identifier[$x]);
            $identifier_code=$exp_identifier[0];
            $invoice_no=$exp_identifier[1];
            $invoice_no_disp.=$exp_identifier[1]."-";
            $data_head = array(
                'print_identifier'=>$identifier_code
            );
            $this->super_model->update_where("sales_merge_transaction_details",$data_head, "serial_no", $invoice_no);
        }
        echo $invoice_no_disp.",".$identifier_code.",".$count;
    }

        public function insert_printbsm(){
        $participant_id = $this->input->post('participant_id');
        $detail_id = $this->input->post('sales_detail_id');
        $company_name = $this->input->post('company_name');
        $address = $this->input->post('address');
        $tin = $this->input->post('tin');
        $settlement = $this->input->post('settlement');
        $serial_no = $this->input->post('serial_no');
        $transaction_date = $this->input->post('transaction_date');
        $billing_from = $this->input->post('billing_from');
        $billing_to = $this->input->post('billing_to');
        $due_date = $this->input->post('due_date');
        $reference_number = $this->input->post('reference_number');
        $prepared_by = $this->input->post('prepared_by');
        $checked_by_emg = $this->input->post('checked_by_emg');
        $checked_by_emg_pos = $this->input->post('checked_by_emg_pos');
        $checked_by_accounting = $this->input->post('checked_by_accounting');
        $checked_by_accounting_pos = $this->input->post('checked_by_accounting_pos');
        $checked_by_finance = $this->input->post('checked_by_finance');
        $checked_by_finance_pos = $this->input->post('checked_by_finance_pos');
        $noted_by = $this->input->post('noted_by');
        $noted_by_pos = $this->input->post('noted_by_pos');
        $vatable = $this->input->post('vatable');
        $zero = $this->input->post('zero');
        $rated_sales = $this->input->post('total_rated_sales');
        $zero_ecozones = $this->input->post('total_rated_ecozones');
        $total_vat = $this->input->post('vat');
        $ewt_arr = $this->input->post('ewt_arr');
        $overall_total = $this->input->post('overall_total'); 
        $count_head=count($serial_no);

        $reference_no = $this->input->post('ref_no');
        $actual_billing_id = $this->input->post('sub_participant');
        $vatable_sales = $this->input->post('vatable_sales');
        $zero_rated_sales = $this->input->post('zero_rated_sales');
        $vat = $this->input->post('vat_on_sales');
        $ewt = $this->input->post('ewt');
        $net_amount = $this->input->post('net_amount');
        $invoice_no = $this->input->post('invoice_no');
        $details_id = $this->input->post('details_id');
        $count_details=count($actual_billing_id);
       
        for($x=0;$x<$count_head;$x++){
            $count_participant = $this->super_model->count_custom_where("bsm_head", "invoice_no = '$serial_no[$x]'");
            if($count_participant == 0){ 
                  $data_head = array(
                        'sales_merge_detail_id' => $detail_id[$x],
                        'participant_id' => $participant_id[$x],
                        'participant_name' => $company_name[$x],
                        'address' => $address[$x],
                        'tin' => $tin[$x],
                        'stl_id' => $settlement[$x],
                        'invoice_no' => $serial_no[$x],
                        'statement_date' => $transaction_date[$x],
                        'billing_from' => $billing_from[$x],
                        'billing_to' => $billing_to[$x],
                        'due_date' => $due_date[$x],
                        'reference_number' => $reference_number[$x],
                        'prepared_by' => $prepared_by[$x],
                        'checked_by_emg' => $checked_by_emg[$x],
                        'checked_by_emg_pos' => $checked_by_emg_pos[$x],
                        'checked_by_accounting' => $checked_by_accounting[$x],
                        'checked_by_accounting_pos' => $checked_by_accounting_pos[$x],
                        'checked_by_finance' => $checked_by_finance[$x],
                        'checked_by_finance_pos' => $checked_by_finance_pos[$x],
                        'noted_by' => $noted_by[$x],
                        'noted_by_pos' => $noted_by_pos[$x],
                        'total_vatable_sales' => $vatable[$x],
                        'total_zero_rated' => $zero[$x],
                        'total_zero_sales' => $rated_sales[$x],
                        'total_zero_ecozones' => $zero_ecozones[$x],
                        'total_vat' => $total_vat[$x],
                        'total_ewt' => $ewt_arr[$x],
                        'total_net_amount' => $overall_total[$x],
                 );
                 $this->super_model->insert_return_id("bsm_head", $data_head);
                }
            }

        for($y=0;$y<$count_details;$y++){
            if($invoice_no[$y] != ''){
            $bsm_head_id = $this->super_model->select_column_custom_where("bsm_head","bsm_head_id","invoice_no='$invoice_no[$y]'");
            $count_bs_details = $this->super_model->count_custom_where("bsm_details", "bsm_head_id = '$bsm_head_id' AND actual_billing_id = '$actual_billing_id[$y]' AND reference_no = '$reference_no[$y]'");

            if($count_bs_details == 0){

                $settlement_id =  $settlement[$y];
                $tin_no =  $tin[$y];

                $unique_bill_id = $this->super_model->select_column_custom_where("participant", "billing_id", "actual_billing_id = '$actual_billing_id[$y]' AND settlement_id = '$settlement_id' AND tin = '$tin_no'");

                   $data_details = array(
                        'bsm_head_id'=>$bsm_head_id,
                        'reference_no' => $reference_no[$y],
                        'billing_id' => $unique_bill_id ?? '',
                        'actual_billing_id' => $actual_billing_id[$y],
                        'vatable_sales' => $vatable_sales[$y],
                        'zero_rated_sales' => $zero_rated_sales[$y],
                        'vat' => $vat[$y],
                        'ewt' => $ewt[$y],
                        'net_amount' => $net_amount[$y],
                   );
                   $this->super_model->insert_into("bsm_details", $data_details);
                        }
                    }
                }
            }

   public function print_BS_merge(){
        $invoice_no = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $data['count']=$count;
        $invoice_no_exp=explode("-",$invoice_no);
        $data['invoice_no']=$invoice_no;
        $data['print_identifier']=$print_identifier;
        $data['address'][]='';
        $data['tin'][]='';
        $data['company_name'][]='';
        $data['settlement'][]='';
        $data['billing_from'][]='';
        $data['billing_to'][]='';
        $data['due_date'][]='';
        $data['reference_number'][]='';
        $data['detail_id'][]='';
        $data['fullname'][]='';
        $data['prepared_by_pos'][]='';
        $data['checked_by_emg'][]='';
        $data['checked_by_emg_pos'][]='';
        $data['checked_by_accounting'][]='';
        $data['checked_by_accounting_pos'][]='';
        $data['checked_by_finance'][]='';
        $data['checked_by_finance_pos'][]='';
        $data['noted_by'][]='';
        $data['noted_by_pos'][]='';
        $data['total_vatable_sales'][]='';
        $data['total_zero_rated'][]='';
        $data['total_zero_sales'][]='';
        $data['total_zero_ecozones'][]='';
        $data['total_vat'][]='';
        $data['total_ewt'][]='';
        $data['total_net_amount'][]='';
        $data['bsm_head_id'][]='';
        $data['total_sub_h'][]='';
        $data['total_sub'][]='';
  
        for($x=0;$x<$count;$x++){
            $invoice[]=$this->super_model->custom_query_single('invoice_no',"SELECT * FROM sales_merge_transaction_details std INNER JOIN bsm_head bh ON bh.invoice_no=std.serial_no WHERE bh.invoice_no='$invoice_no_exp[$x]'");
            if(array_key_exists($invoice_no_exp[$x],$invoice)){
                foreach($this->super_model->select_custom_where("bsm_head","invoice_no='".$invoice_no_exp[$x]."'") AS $p){
                    $data['reference_number'][$x]=$p->reference_number;
                    $data['detail_id'][$x]=$p->sales_merge_detail_id;
                    $data['address'][$x]=$p->address;
                    $address=$p->address;
                    $data['tin'][$x]=$p->tin;
                    $tin=$p->tin;
                    $data['company_name'][$x]=$p->participant_name;
                    $company_name=$p->participant_name;
                    $data['serial_no'][$x]=$p->invoice_no;
                    $serial_no=$p->invoice_no;
                    $data['settlement'][$x]=$p->stl_id;
                    $settlement=$p->stl_id;
                    $data['transaction_date'][$x]=$p->statement_date;
                    $transaction_date=$p->statement_date;
                    $data['billing_from'][$x]=$p->billing_from;
                    $data['billing_to'][$x]=$p->billing_to;
                    $data['due_date'][$x]=$p->due_date;
                    $due_date=$p->due_date;
                    $data['fullname'][$x]=$this->super_model->select_column_where("users","fullname","user_id",$p->prepared_by);
                    $data['prepared_by_pos'][$x]=$this->super_model->select_column_where("users","position","user_id",$p->prepared_by);
                    $data['checked_by_emg'][$x]=$p->checked_by_emg;
                    $data['checked_by_emg_pos'][$x]=$p->checked_by_emg_pos;
                    $data['checked_by_accounting'][$x]=$p->checked_by_accounting;
                    $data['checked_by_accounting_pos'][$x]=$p->checked_by_accounting_pos;
                    $data['checked_by_finance'][$x]=$p->checked_by_finance;
                    $data['checked_by_finance_pos'][$x]=$p->checked_by_finance_pos;
                    $data['noted_by'][$x]=$p->noted_by;
                    $data['noted_by_pos'][$x]=$p->noted_by_pos;
                    $data['total_vatable_sales'][$x]=$p->total_vatable_sales;
                    $data['total_zero_rated'][$x]=$p->total_zero_rated;
                    $data['total_zero_sales'][$x]=$p->total_zero_sales;
                    $data['total_zero_ecozones'][$x]=$p->total_zero_ecozones;
                    $data['total_vat'][$x]=$p->total_vat;
                    $data['total_ewt'][$x]=$p->total_ewt;
                    $data['total_net_amount'][$x]=$p->total_net_amount;
                    $data['bsm_head_id'][$x]=$p->bsm_head_id;
                    $data['participant_id'][$x]=$p->participant_id;
                    $count_sub_hist=$this->super_model->count_custom_where("bsm_details","bsm_head_id='$p->bsm_head_id'");
                    $data['count_sub_hist'][$x]=$this->super_model->count_custom_where("bsm_details","bsm_head_id='$p->bsm_head_id'");
                    $billing_id = $this->super_model->select_column_where("bsm_details","billing_id","bsm_head_id",$p->bsm_head_id);
                    $vatable_sales = $this->super_model->select_column_where("bsm_details","vatable_sales","bsm_head_id",$p->bsm_head_id);
                    $zero_rated_sales = $this->super_model->select_column_where("bsm_details","zero_rated_sales","bsm_head_id",$p->bsm_head_id);
                    $vat = $this->super_model->select_column_where("bsm_details","vat","bsm_head_id",$p->bsm_head_id);
                    $ewt = $this->super_model->select_column_where("bsm_details","ewt","bsm_head_id",$p->bsm_head_id);
                    $overall_total = $this->super_model->select_column_where("bsm_details","net_amount","bsm_head_id",$p->bsm_head_id);
                    $total_amount = $vatable_sales + $zero_rated_sales;

                    $data['head'][]=array(
                        "serial_no"=>$p->invoice_no,
                    );
                     
                    
                    $h=0;
                    $u=1;
                    foreach($this->super_model->select_custom_where("bsm_details","bsm_head_id='$p->bsm_head_id'") AS $s){
                    if($u <= 10){
                            $data['total_sub_h']=$this->super_model->count_custom_where("bsm_details","bsm_head_id='$p->bsm_head_id'");
                            $data['total_sub']='';
                                $data['sub_part'][]=array(
                                    "serial_no"=>$p->invoice_no,
                                    "ref_no"=>$s->reference_no,
                                    "bsm_head_id"=>$p->bsm_head_id,
                                    "sub_participant"=>$s->actual_billing_id,
                                    "vatable_sales"=>$s->vatable_sales,
                                    "rated_sales"=>'',
                                    "zero_rated_ecozones"=>'',
                                    "zero_rated_sales"=>$s->zero_rated_sales,
                                    "vat_on_sales"=>$s->vat,
                                    "ewt"=>$s->ewt,
                                    "overall_total"=>$s->net_amount,
                                );
                               
                            $h++;
                        }
                       $u++; 
                    }

                        $data['sub_second'][]=array(
                            "bsm_head_id"=>$p->bsm_head_id,
                            "serial_no"=>$p->invoice_no,
                            "ref_no"=>$s->reference_no,
                            "sub_participant"=>$actual_billing_id,
                            "vatable_sales"=>$vatable_sales,
                            "rated_sales"=>'',
                            "zero_rated_ecozones"=>'',
                            "zero_rated_sales"=>$zero_rated_sales,
                            "total_amount"=>$total_amount,
                            "vat_on_sales"=>$vat,
                            "ewt"=>$ewt,
                            "overall_total"=>$overall_total,
                        );
                    
                        $z=0;
                        $t=1;
                        foreach($this->super_model->select_custom_where("bsm_details","bsm_head_id='$p->bsm_head_id'") AS $s){
                    if($t>=11){

                                $data['sub_part_second'][]=array(
                                    "counter"=>'',
                                    "counter_h"=>$t,
                                    "bsm_head_id"=>$p->bsm_head_id,
                                    "serial_no"=>$p->invoice_no,
                                    "ref_no"=>$s->reference_no,
                                    "sub_participant"=>$s->actual_billing_id,
                                    "vatable_sales"=>$s->vatable_sales,
                                    "rated_sales"=>'',
                                    "zero_rated_ecozones"=>'',
                                    "zero_rated_sales"=>$s->zero_rated_sales,
                                    "vat_on_sales"=>$s->vat,
                                    "ewt"=>$s->ewt,
                                    "overall_total"=>$s->net_amount,
                                );
                            }
                            $z++;
                            $t++;
                    }
                }
            }else{
                    foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details WHERE print_identifier='$print_identifier' AND serial_no='".$invoice_no_exp[$x]."' GROUP BY serial_no ORDER BY serial_no ASC")AS $p){
                    $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                    $mother_participant_id = $this->super_model->select_column_where("subparticipant","participant_id","sub_participant",$participant_id);
                    if($mother_participant_id != ''){
                            $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                            $mother_billing_id = $this->super_model->select_column_where("participant","actual_billing_id","participant_id",$mother_participant_id);
                            $mother_company_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                            if(!empty($mother_company_name)){
                                $comp_name= $mother_company_name;
                            }else{
                                $comp_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                            }
                            $tin_no = $this->super_model->select_column_where("participant","tin","participant_id",$mother_participant_id);
                            $settlement = $this->super_model->select_column_where("participant","settlement_id","participant_id",$mother_participant_id);
                    }else{
                            $address = $this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                            if(!empty($p->company_name)){
                                $comp_name=$p->company_name;
                            }else{
                                $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $p->billing_id);
                            }
                            $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                            $settlement = $this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
                    }
                    $data['detail_id'][$x]=$p->sales_merge_detail_id;
                    $data['participant_id'][$x]=$participant_id;
                    $data['address'][$x]= $address;
                    $data['company_name'][$x]=$comp_name;
                    $data['tin'][$x]=$tin_no;
                    $tin=$tin_no;
                     $data['reference_number'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","reference_number","sales_merge_id",$p->sales_merge_id);
                    $data['serial_no'][$x]=$this->super_model->select_column_where("sales_merge_transaction_details","serial_no","serial_no",$p->serial_no);
                    $serial_no=$this->super_model->select_column_where("sales_merge_transaction_details","serial_no","serial_no",$p->serial_no);
                    $data['settlement'][$x]=$settlement;
                    $data['transaction_date'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","transaction_date","sales_merge_id",$p->sales_merge_id);
                    $transaction_date=$this->super_model->select_column_where("sales_merge_transaction_head","transaction_date","sales_merge_id",$p->sales_merge_id);
                    $data['billing_from'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","billing_from","sales_merge_id",$p->sales_merge_id);
                    $data['billing_to'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","billing_to","sales_merge_id",$p->sales_merge_id);
                    $data['due_date'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","due_date","sales_merge_id",$p->sales_merge_id);
                    $due_date=$this->super_model->select_column_where("sales_merge_transaction_head","due_date","sales_merge_id",$p->sales_merge_id);
                    $count_sub=$this->super_model->count_custom_where("subparticipant","participant_id='$participant_id'");
                    $zero_rated= $p->zero_rated_sales + $p->zero_rated_ecozones;
                    $total_amount = $p->vatable_sales + $p->zero_rated_sales + $p->zero_rated_ecozones;
                    $overall_total= ($total_amount+$p->vat_on_sales) - $p->ewt;
                    $data['bsm_head_id'][$x]='';
                    $data['count_sub_hist'][$x]='';

                    $data['head'][]=array(
                        "serial_no"=>$p->serial_no,
                    );

                        $h=1;
                            $data['total_sub']=$this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details std INNER JOIN sales_merge_transaction_head sth ON std.sales_merge_id = sth.sales_merge_id WHERE sth.sales_merge_id = '$p->sales_merge_id' AND std.serial_no = '$p->serial_no' AND std.total_amount != '0' AND sth.saved != '0'");
                            $data['total_sub_h']='';
                            foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details std INNER JOIN sales_merge_transaction_head sth ON std.sales_merge_id = sth.sales_merge_id WHERE sth.sales_merge_id = '$p->sales_merge_id' AND std.serial_no = '$p->serial_no' AND std.total_amount != '0' AND sth.saved != '0'") AS $s){
                            if($h<=10){
                                    $vatable_sales=$this->super_model->select_column_where("sales_merge_transaction_details","vatable_sales","sales_merge_detail_id",$s->sales_merge_detail_id);
                                    $zero_rated_sales=$this->super_model->select_column_where("sales_merge_transaction_details","zero_rated_sales","sales_merge_detail_id",$s->sales_merge_detail_id);
                                    $zero_rated_ecozones=$this->super_model->select_column_where("sales_merge_transaction_details","zero_rated_ecozones","sales_merge_detail_id",$s->sales_merge_detail_id);
                                    $vat_on_sales=$this->super_model->select_column_where("sales_merge_transaction_details","vat_on_sales","sales_merge_detail_id",$s->sales_merge_detail_id);
                                    $ewt=$this->super_model->select_column_where("sales_merge_transaction_details","ewt","sales_merge_detail_id",$s->sales_merge_detail_id);

                                    $zero_rated= $zero_rated_sales + $zero_rated_ecozones;
                                    $total_amount = $vatable_sales + $zero_rated_sales + $zero_rated_ecozones;
                                    $overall_total= ($total_amount + $vat_on_sales) - $ewt;
                                    $data['sub_part'][]=array(
                                        "counter"=>$h,
                                        "counter_h"=>'',
                                        "sales_id"=>$s->sales_merge_id,
                                        "ref_no"=>$s->reference_no,
                                        "sub_participant"=>$s->actual_billing_id,
                                        "serial_no"=>$s->serial_no,
                                        "vatable_sales"=>$vatable_sales,
                                        "zero_rated_sales"=>$zero_rated,
                                        "rated_sales"=>$zero_rated_sales,
                                        "zero_rated_ecozones"=>$zero_rated_ecozones,
                                        "total_amount"=>$total_amount,
                                        "vat_on_sales"=>$vat_on_sales,
                                        "ewt"=>$ewt,
                                        "overall_total"=>$overall_total,
                                    );
                                }
                                $h++;
                        }

                        $total_amount = $p->vatable_sales + $p->zero_rated_sales + $p->zero_rated_ecozones;
                        $overall_total= ($total_amount+$p->vat_on_sales) - $p->ewt;
                        $data['sub_second'][]=array(
                            "sub_participant"=>$p->actual_billing_id,
                            "sales_id"=>$p->sales_merge_id,
                            "ref_no"=>$p->reference_no,
                            "serial_no"=>$serial_no,
                            "vatable_sales"=>$p->vatable_sales,
                            "zero_rated_sales"=>$p->zero_rated_sales,
                            "rated_sales"=>$p->zero_rated_sales,
                            "zero_rated_ecozones"=>$p->zero_rated_ecozones,
                            "total_amount"=>$total_amount,
                            "vat_on_sales"=>$p->vat_on_sales,
                            "ewt"=>$p->ewt,
                            "overall_total"=>$overall_total,
                        );

                        $z=1;
                        foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details std INNER JOIN sales_merge_transaction_head sth ON std.sales_merge_id = sth.sales_merge_id WHERE sth.sales_merge_id = '$p->sales_merge_id' AND std.serial_no = '$p->serial_no' AND std.total_amount != '0' AND sth.saved != '0'") AS $s){
                                $reference_number=$this->super_model->select_column_where("sales_merge_transaction_head","reference_number","sales_merge_id",$s->sales_merge_id);
                                $billing_from=$this->super_model->select_column_where("sales_merge_transaction_head","billing_from","sales_merge_id",$s->sales_merge_id);
                                $billing_to=$this->super_model->select_column_where("sales_merge_transaction_head","billing_to","sales_merge_id",$s->sales_merge_id);
                                $vatable_sales=$this->super_model->select_column_where("sales_merge_transaction_details","vatable_sales","sales_merge_detail_id",$s->sales_merge_detail_id);
                                $zero_rated_sales=$this->super_model->select_column_where("sales_merge_transaction_details","zero_rated_sales","sales_merge_detail_id",$s->sales_merge_detail_id);
                                $zero_rated_ecozones=$this->super_model->select_column_where("sales_merge_transaction_details","zero_rated_ecozones","sales_merge_detail_id",$s->sales_merge_detail_id);
                                $vat_on_sales=$this->super_model->select_column_where("sales_merge_transaction_details","vat_on_sales","sales_merge_detail_id",$s->sales_merge_detail_id);
                                $ewt=$this->super_model->select_column_where("sales_merge_transaction_details","ewt","sales_merge_detail_id",$s->sales_merge_detail_id);
                                $zero_rated= $zero_rated_sales + $zero_rated_ecozones;
                                $total_amount = $vatable_sales + $zero_rated_sales + $zero_rated_ecozones;
                                $overall_total= ($total_amount + $vat_on_sales) - $ewt;

                                $total_sub_vatable_sales[] = $vatable_sales;
                                $total_sub_zero_rated_sales[] = $zero_rated_sales;
                                $total_sub_zero_rated_ecozones[] = $zero_rated_ecozones;
                                $total_sub_vat_on_sales[] = $vat_on_sales;
                                $total_sub_ewt[] = $ewt;
                                $total_sub_zero_rated[] = $zero_rated;
                                $total_sub_total_amount[] = $total_amount;
                                $total_sub_overall_total[] = $overall_total;

                                if($z>=11){
                                $data['sub_part_second'][]=array(
                                    "counter"=>$h,
                                    "counter_h"=>'',
                                    "sales_id"=>$s->sales_merge_id,
                                    "sub_participant"=>$s->actual_billing_id,
                                    "ref_no"=>$s->reference_no,
                                    "serial_no"=>$s->serial_no,
                                    "vatable_sales"=>$vatable_sales,
                                    "zero_rated_sales"=>$zero_rated,
                                    "rated_sales"=>$zero_rated_sales,
                                    "zero_rated_ecozones"=>$zero_rated_ecozones,
                                    "total_amount"=>$total_amount,
                                    "vat_on_sales"=>$vat_on_sales,
                                    "ewt"=>$ewt,
                                    "overall_total"=>$overall_total,
                                );
                            }
                            $data['overall_vatable_sales']=array_sum($total_sub_vatable_sales);
                            $data['overall_zero_rated_sales']=array_sum($total_sub_zero_rated_sales);
                            $data['overall_zero_rated_ecozones']=array_sum($total_sub_zero_rated_ecozones);
                            $data['overall_vat_on_sales']=array_sum($total_sub_vat_on_sales);
                            $data['overall_ewt']=array_sum($total_sub_ewt);
                            $data['overall_zero_rated']=array_sum($total_sub_zero_rated);
                            $data['overall_total_amount']=array_sum($total_sub_total_amount);
                            $data['all_total']=array_sum($total_sub_overall_total);
                            $z++;
                    }
                }
            }
        }
        $this->load->view('template/print_head');
        $this->load->view('sales_merge/print_BS_merge',$data);
    }

    public function update_BSeriesno(){
            $sales_detail_id=$this->input->post('sales_detail_id');
            $new_series=$this->input->post('series_number');
            $old_series=$this->input->post('serial_no');
            foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details WHERE sales_merge_detail_id='$sales_merge_detail_id'") AS $check){
                $count=$this->super_model->count_custom_where("sales_merge_transaction_details","sales_merge_detail_id = '$check->sales_merge_detail_id' AND old_series_no!=''");
                if($count==0){
                    $old_series_insert = $old_series;
                }else{
                    $old_series_insert = $old_series.", ".$check->old_series_no;
                }
            }

            $data_update = array(
                'serial_no'=>$new_series,
                'old_series_no'=>$old_series_insert,
            );

            if($this->super_model->update_custom_where("sales_merge_transaction_details", $data_update, "sales_merge_detail_id='$sales_merge_detail_id'")){
                foreach($this->super_model->select_custom_where("sales_merge_transaction_details","sales_merge_detail_id='$sales_merge_detail_id'") AS $latest_data){
                    $return = array('series_number'=>$latest_data->serial_no);
                }
                echo json_encode($return);
        }
    }

    public function print_merge_invoice_small(){
        error_reporting(0);
        $invoice_no = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $invoice_no_exp=explode("-",$invoice_no);
        $data['count']=$count;
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        for($x=0;$x<$count;$x++){
             $invoice[]=$this->super_model->custom_query_single('invoice_no',"SELECT * FROM sales_merge_transaction_details std  INNER JOIN bsm_head bh ON bh.invoice_no=std.serial_no WHERE bh.invoice_no='$invoice_no_exp[$x]'");
            if(array_key_exists($invoice_no_exp[$x],$invoice)){
                foreach($this->super_model->select_custom_where("bsm_head","invoice_no='".$invoice_no_exp[$x]."'") AS $p){
                    $data['address'][$x]=$p->address;
                    $data['tin'][$x]=$p->tin;
                    $data['company_name'][$x]=$p->participant_name;
                    $data['due_date'][$x]=$p->due_date;
                    $data['transaction_date'][$x]=$p->statement_date;
                    $participant_id = $p->participant_id;
                    $data['participant_id'][$x] = $p->participant_id;
                    $data['billing_from'][$x] = $p->billing_from;
                    $data['billing_to'][$x] = $p->billing_to;
                }

            $sum_vs_exp = explode(".", $p->total_vatable_sales);
            $sum_vatable_sales_peso=$sum_vs_exp[0];
            $sum_vatable_sales_cents=$sum_vs_exp[1];

            $sum_vos_exp=explode(".", $p->vat);
            $sum_vat_on_sales_peso=$sum_vos_exp[0];
            $sum_vat_on_sales_cents=$sum_vos_exp[1];

            $sum_e_exp=explode(".", $p->total_ewt);
            $sum_ewt_peso=$sum_e_exp[0];
            $sum_ewt_cents=$sum_e_exp[1];

            $sum_zr_exp=explode(".", $p->total_zero_rated);
            $sum_zero_rated_peso=$sum_zr_exp[0];
            $sum_zero_rated_cents=$sum_zr_exp[1];

            $total_vs=$p->total_vatable_sales;
            $vatable_sales = explode(".",$total_vs);
            $data['vat_sales_peso'][$x] = $vatable_sales[0];
            $data['vat_sales_cents'][$x] = $vatable_sales[1];

            $total_zr=$p->total_zero_sales;
            $data['total_zr'][$x]=$total_zr;
            $zero_rated_sales = explode(".",$total_zr);
            $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            $data['total_zr_sub'][$x]=0;
            $data['zero_rated_peso_sub'][$x] = 0;
            $data['zero_rated_cents_sub'][$x] = 0;

            $total_zra=$p->total_zero_ecozones;
            $data['total_zra'][$x]=$total_zra;
            $zero_rated_ecozones_exp=explode(".", $total_zra);
            $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            $total_vos=$p->total_vat;
            $vat_on_sales = explode(".",$total_vos);
            $data['vat_peso'][$x] = $vat_on_sales[0];
            $data['vat_cents'][$x] = $vat_on_sales[1];

            $total_ewt=$p->total_ewt;
            $ewt_exp=explode(".", $total_ewt);
            $data['ewt_peso'][$x]=$ewt_exp[0];
            $data['ewt_cents'][$x]=$ewt_exp[1];

            $total= $p->total_net_amount;

            $total_amount=str_replace(',','',number_format($total,2));
           
            $total_amount_sub=0;
            $data['total_amount'][$x]=$total_amount;
            $data['amount_words'][$x]=strtoupper($this->convertNumber($total_amount));
            $total_exp=explode(".", $total_amount);
            $data['total_peso'][$x]=$total_exp[0];
            $data['total_cents'][$x]=$total_exp[1];

        }else{
            foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details sad INNER JOIN sales_merge_transaction_head sah ON sad.sales_merge_id = sah.sales_merge_id WHERE print_identifier='$print_identifier' AND serial_no='".$invoice_no_exp[$x]."' AND sah.saved != '0' GROUP by serial_no") AS $p){
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                $mother_participant_id = $this->super_model->select_column_where("subparticipant","participant_id","sub_participant",$participant_id);
                if($mother_participant_id != ''){
                        $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                        $mother_billing_id = $this->super_model->select_column_where("participant","actual_billing_id","participant_id",$mother_participant_id);
                        $mother_company_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                        if(!empty($mother_company_name)){
                            $company_name= $mother_company_name;
                        }else{
                            $company_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                        }
                        $tin_no = $this->super_model->select_column_where("participant","tin","participant_id",$mother_participant_id);
                        $settlement = $this->super_model->select_column_where("participant","settlement_id","participant_id",$mother_participant_id);
                }else{
                        $address = $this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                        if(!empty($p->company_name)){
                            $company_name=$p->company_name;
                        }else{
                            $company_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $p->billing_id);
                        }
                        $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                        $settlement = $this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
                }

                $data['address'][$x]=$address;
                $data['tin'][$x]=$tin_no;
                $data['company_name'][$x]=$company_name;
                $data['due_date'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","due_date","sales_merge_id",$p->sales_merge_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","transaction_date","sales_merge_id",$p->sales_merge_id);
                $data['billing_from'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","billing_from","sales_merge_id",$p->sales_merge_id);
                $data['billing_to'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","billing_to","sales_merge_id",$p->sales_merge_id);

                $vatable_sales = $this->super_model->select_sum_join("vatable_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$p->serial_no' AND saved = '1'","sales_merge_id");
                $zero_rated_sales = $this->super_model->select_sum_join("zero_rated_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$p->serial_no' AND saved = '1'","sales_merge_id");
                $zero_rated_ecozones = $this->super_model->select_sum_join("zero_rated_ecozones","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$p->serial_no' AND saved = '1'","sales_merge_id");
                $vat_on_sales = $this->super_model->select_sum_join("vat_on_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$p->serial_no' AND saved = '1'","sales_merge_id");
                $ewt = $this->super_model->select_sum_join("ewt","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$p->serial_no' AND saved = '1'","sales_merge_id");
            }

            $total_vs=$vatable_sales;
            $vatable_sales = explode(".",$total_vs);
            $data['vat_sales_peso'][$x] = $vatable_sales[0];
            $data['vat_sales_cents'][$x] = $vatable_sales[1];

            $total_zr=$zero_rated_sales;
            $data['total_zr'][$x]=$total_zr;
            $zero_rated_sales = explode(".",$total_zr);
            $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            $total_zra=$zero_rated_ecozones;
            $data['total_zra'][$x]=$total_zra;
            $zero_rated_ecozones_exp=explode(".", $total_zra);
            $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            $total_vos=$vat_on_sales;
            $vat_on_sales = explode(".",$total_vos);
            $data['vat_peso'][$x] = $vat_on_sales[0];
            $data['vat_cents'][$x] = $vat_on_sales[1];

            $total_ewt=$ewt;
            $ewt_exp=explode(".", $total_ewt);
            $data['ewt_peso'][$x]=$ewt_exp[0];
            $data['ewt_cents'][$x]=$ewt_exp[1];

            $total= ($total_vs + $total_vos + $total_zra + $total_zr) - $total_ewt;
            $total_amount=str_replace(',','',number_format($total,2));

            $data['total_amount'][$x]=$total_amount;
            $data['amount_words'][$x]=strtoupper($this->convertNumber($total_amount));
            $total_exp=explode(".", $total_amount);
            $data['total_peso'][$x]=$total_exp[0];
            $data['total_cents'][$x]=$total_exp[1];
            }
        }
        $this->load->view('template/print_head');
        $this->load->view('sales_merge/print_merge_invoice_small',$data);
    }

     public function print_merge_invoice_half(){
        error_reporting(0);
        $invoice_no = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $invoice_no_exp=explode("-",$invoice_no);
        $data['count']=$count;
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        for($x=0;$x<$count;$x++){
             $invoice[]=$this->super_model->custom_query_single('invoice_no',"SELECT * FROM sales_merge_transaction_details std  INNER JOIN bsm_head bh ON bh.invoice_no=std.serial_no WHERE bh.invoice_no='$invoice_no_exp[$x]'");
            if(array_key_exists($invoice_no_exp[$x],$invoice)){
                foreach($this->super_model->select_custom_where("bsm_head","invoice_no='".$invoice_no_exp[$x]."'") AS $p){
                    $data['address'][$x]=$p->address;
                    $data['tin'][$x]=$p->tin;
                    $data['company_name'][$x]=$p->participant_name;
                    $data['due_date'][$x]=$p->due_date;
                    $data['billing_from'][$x]=$p->billing_from;
                    $data['billing_to'][$x]=$p->billing_to;
                    $data['transaction_date'][$x]=$p->statement_date;
                    $participant_id = $p->participant_id;
                    $data['participant_id'][$x] = $p->participant_id;
                }

            $sum_vs_exp = explode(".", $p->total_vatable_sales);
            $sum_vatable_sales_peso=$sum_vs_exp[0];
            $sum_vatable_sales_cents=$sum_vs_exp[1];

            $sum_vos_exp=explode(".", $p->vat);
            $sum_vat_on_sales_peso=$sum_vos_exp[0];
            $sum_vat_on_sales_cents=$sum_vos_exp[1];

            $sum_e_exp=explode(".", $p->total_ewt);
            $sum_ewt_peso=$sum_e_exp[0];
            $sum_ewt_cents=$sum_e_exp[1];

            $sum_zr_exp=explode(".", $p->total_zero_rated);
            $sum_zero_rated_peso=$sum_zr_exp[0];
            $sum_zero_rated_cents=$sum_zr_exp[1];

            $total_vs=$p->total_vatable_sales;
            $vatable_sales = explode(".",$total_vs);
            $data['vat_sales_peso'][$x] = $vatable_sales[0];
            $data['vat_sales_cents'][$x] = $vatable_sales[1];

            $total_zr=$p->total_zero_sales;
            $data['total_zr'][$x]=$total_zr;
            $zero_rated_sales = explode(".",$total_zr);
            $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            $data['total_zr_sub'][$x]=0;
            $data['zero_rated_peso_sub'][$x] = 0;
            $data['zero_rated_cents_sub'][$x] = 0;

            $total_zra=$p->total_zero_ecozones;
            $data['total_zra'][$x]=$total_zra;
            $zero_rated_ecozones_exp=explode(".", $total_zra);
            $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            $total_vos=$p->total_vat;
            $vat_on_sales = explode(".",$total_vos);
            $data['vat_peso'][$x] = $vat_on_sales[0];
            $data['vat_cents'][$x] = $vat_on_sales[1];

            $total_ewt=$p->total_ewt;
            $ewt_exp=explode(".", $total_ewt);
            $data['ewt_peso'][$x]=$ewt_exp[0];
            $data['ewt_cents'][$x]=$ewt_exp[1];

            $total= $p->total_net_amount;

            $total_amount=str_replace(',','',number_format($total,2));
           
            $total_amount_sub=0;
            $data['total_amount'][$x]=$total_amount;
            $data['total_amount_sub'][$x]=0;
            $data['amount_words'][$x]=strtoupper($this->convertNumber($total_amount));
            $data['amount_words_sub'][$x]=strtoupper($this->convertNumber(str_replace(',','',number_format($total_amount_sub,2))));
            $total_exp=explode(".", $total_amount);
            $data['total_peso'][$x]=$total_exp[0];
            $data['total_cents'][$x]=$total_exp[1];

            $total_exp_sub=explode(".", $total_amount_sub);
            $data['total_peso_sub'][$x]=$total_exp_sub[0];
            $data['total_cents_sub'][$x]=$total_exp_sub[1];

        }else{
            foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details std INNER JOIN sales_merge_transaction_head sth ON std.sales_merge_id = sth.sales_merge_id WHERE print_identifier='$print_identifier' AND serial_no='".$invoice_no_exp[$x]."' AND sth.saved != '0' GROUP by serial_no") AS $p){
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                $mother_participant_id = $this->super_model->select_column_where("subparticipant","participant_id","sub_participant",$participant_id);
                if($mother_participant_id != ''){
                        $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                        $mother_billing_id = $this->super_model->select_column_where("participant","actual_billing_id","participant_id",$mother_participant_id);
                        $mother_company_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                        if(!empty($mother_company_name)){
                            $company_name= $mother_company_name;
                        }else{
                            $company_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                        }
                        $tin_no = $this->super_model->select_column_where("participant","tin","participant_id",$mother_participant_id);
                        $settlement = $this->super_model->select_column_where("participant","settlement_id","participant_id",$mother_participant_id);
                }else{
                        $address = $this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                        if(!empty($p->company_name)){
                            $company_name=$p->company_name;
                        }else{
                            $company_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $p->billing_id);
                        }
                        $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                        $settlement = $this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
                }

                $data['address'][$x]=$address;
                $data['tin'][$x]=$tin_no;
                $data['company_name'][$x]=$company_name;
                $data['due_date'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","due_date","sales_merge_id",$p->sales_merge_id);
                $data['billing_from'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","billing_from","sales_merge_id",$p->sales_merge_id);
                $data['billing_to'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","billing_to","sales_merge_id",$p->sales_merge_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","transaction_date","sales_merge_id",$p->sales_merge_id);
                $vatable_sales = $this->super_model->select_sum_join("vatable_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$p->serial_no' AND saved = '1'","sales_merge_id");
                $zero_rated_sales = $this->super_model->select_sum_join("zero_rated_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$p->serial_no' AND saved = '1'","sales_merge_id");
                $zero_rated_ecozones = $this->super_model->select_sum_join("zero_rated_ecozones","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$p->serial_no' AND saved = '1'","sales_merge_id");
                $vat_on_sales = $this->super_model->select_sum_join("vat_on_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$p->serial_no' AND saved = '1'","sales_merge_id");
                $ewt = $this->super_model->select_sum_join("ewt","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$p->serial_no' AND saved = '1'","sales_merge_id");
            }

            $total_vs=$vatable_sales;
            $vatable_sales = explode(".",$total_vs);
            $data['vat_sales_peso'][$x] = $vatable_sales[0];
            $data['vat_sales_cents'][$x] = $vatable_sales[1];

            $total_zr=$zero_rated_sales;
            $data['total_zr'][$x]=$total_zr;
            $zero_rated_sales = explode(".",$total_zr);
            $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            $total_zra=$zero_rated_ecozones;
            $data['total_zra'][$x]=$total_zra;
            $zero_rated_ecozones_exp=explode(".", $total_zra);
            $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            $total_vos=$vat_on_sales;
            $vat_on_sales = explode(".",$total_vos);
            $data['vat_peso'][$x] = $vat_on_sales[0];
            $data['vat_cents'][$x] = $vat_on_sales[1];

            $total_ewt=$ewt;
            $ewt_exp=explode(".", $total_ewt);
            $data['ewt_peso'][$x]=$ewt_exp[0];
            $data['ewt_cents'][$x]=$ewt_exp[1];

            $total= ($total_vs + $total_vos + $total_zra + $total_zr) - $total_ewt;
            $total_amount=str_replace(',','',number_format($total,2));

            $data['total_amount'][$x]=$total_amount;
            $data['amount_words'][$x]=strtoupper($this->convertNumber($total_amount));
            $total_exp=explode(".", $total_amount);
            $data['total_peso'][$x]=$total_exp[0];
            $data['total_cents'][$x]=$total_exp[1];
            }
        }
        $this->load->view('template/print_head');
        $this->load->view('sales_merge/print_merge_invoice_half',$data);
    }

    public function print_merge_invoice_main(){
        error_reporting(0);
        $invoice_no = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $invoice_no_exp=explode("-",$invoice_no);
        $data['count']=$count;
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        for($x=0;$x<$count;$x++){
             $invoice[]=$this->super_model->custom_query_single('invoice_no',"SELECT * FROM sales_merge_transaction_details std  INNER JOIN bsm_head bh ON bh.invoice_no=std.serial_no WHERE bh.invoice_no='$invoice_no_exp[$x]'");
            if(array_key_exists($invoice_no_exp[$x],$invoice)){
                foreach($this->super_model->select_custom_where("bsm_head","invoice_no='".$invoice_no_exp[$x]."'") AS $p){
                    $data['address'][$x]=$p->address;
                    $data['tin'][$x]=$p->tin;
                    $data['company_name'][$x]=$p->participant_name;
                    $data['billing_to'][$x]=$p->billing_to;
                    $data['due_date'][$x]=$p->due_date;
                    $data['transaction_date'][$x]=$p->statement_date;
                    $participant_id = $p->participant_id;
                    $data['participant_id'][$x] = $p->participant_id;
                    $reference_number = $p->reference_number;
                    $data['reference_number'][$x] = $this->super_model->select_column_where("bsm_details","reference_no","bsm_head_id",$p->bsm_head_id);
                    $data['or_no'][$x] = $p->invoice_no;
                }
            $data['total_vs'][$x] = $p->total_vatable_sales;
            $data['total_zra'][$x] = $p->total_zero_ecozones;
            $data['total_vos'][$x] = $p->total_vat;
            $data['total_ewt'][$x] = $p->total_vat;

        }else{
                foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details std INNER JOIN sales_merge_transaction_head sth ON std.sales_merge_id = sth.sales_merge_id WHERE print_identifier='$print_identifier' AND serial_no='".$invoice_no_exp[$x]."' AND sth.saved != '0' GROUP by serial_no") AS $p){
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                $mother_participant_id = $this->super_model->select_column_where("subparticipant","participant_id","sub_participant",$participant_id);
                if($mother_participant_id != ''){
                            $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                        $mother_billing_id = $this->super_model->select_column_where("participant","actual_billing_id","participant_id",$mother_participant_id);
                        $mother_company_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                        if(!empty($mother_company_name)){
                            $company_name= $mother_company_name;
                        }else{
                            $company_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                        }
                        $tin_no = $this->super_model->select_column_where("participant","tin","participant_id",$mother_participant_id);
                        $settlement = $this->super_model->select_column_where("participant","settlement_id","participant_id",$mother_participant_id);
                }else{
                        $address = $this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                        if(!empty($p->company_name)){
                            $company_name=$p->company_name;
                        }else{
                            $company_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $p->billing_id);
                        }
                        $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                        $settlement = $this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
                }
                $data['address'][$x]=$address;
                $data['tin'][$x]=$tin_no;
                $data['company_name'][$x]=$company_name;
                $data['billing_to'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","billing_to","sales_merge_id",$p->sales_merge_id);
                $data['due_date'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","due_date","sales_merge_id",$p->sales_merge_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","transaction_date","sales_merge_id",$p->sales_merge_id);
                $data['reference_number'][$x]=$this->super_model->select_column_where("sales_merge_transaction_head","reference_number","sales_merge_id",$p->sales_merge_id);
                $data['or_no'][$x]=$p->serial_no;
                $vatable_sales = $this->super_model->select_sum_join("vatable_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$p->serial_no' AND saved = '1'","sales_merge_id");
                $zero_rated_sales = $this->super_model->select_sum_join("zero_rated_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$p->serial_no' AND saved = '1'","sales_merge_id");
                $zero_rated_ecozones = $this->super_model->select_sum_join("zero_rated_ecozones","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$p->serial_no' AND saved = '1'","sales_merge_id");
                $vat_on_sales = $this->super_model->select_sum_join("vat_on_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$p->serial_no' AND saved = '1'","sales_merge_id");
                $ewt = $this->super_model->select_sum_join("ewt","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$p->serial_no' AND saved = '1'","sales_merge_id");
            }

            $data['total_vs'][$x]= $vatable_sales;
            $data['total_zra'][$x]= $zero_rated_ecozones;
            $data['total_vos'][$x]= $vat_on_sales;
            $data['total_ewt'][$x]= $ewt;
            }
        }
        $this->load->view('template/print_head');
        $this->load->view('sales_merge/print_merge_invoice_main',$data);
    }

    public function sales_wesm_merge_pdf_si(){
        $id=$this->uri->segment(3);

        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
                foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details sd INNER JOIN sales_merge_transaction_head sh ON sd.sales_merge_id=sh.sales_merge_id WHERE sales_merge_detail_id='$id'") AS $d){
                $data['stl_id']=$d->short_name;
                $data['address']=$this->super_model->select_column_where("participant","registered_address","billing_id",$d->billing_id);
                $data['tin']=$this->super_model->select_column_where("participant","tin","billing_id",$d->billing_id);
                if(!empty($d->company_name)){
                    $data['company_name']=$d->company_name;
                }else{
                    $data['company_name']=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $d->billing_id);
                }
                $data['billing_from']=$this->super_model->select_column_where("sales_merge_transaction_head","billing_from","sales_merge_id",$d->sales_merge_id);
                $data['billing_to']=$this->super_model->select_column_where("sales_merge_transaction_head","billing_to","sales_merge_id",$d->sales_merge_id);
                $data['transaction_date']=$this->super_model->select_column_where("sales_merge_transaction_head","transaction_date","sales_merge_id",$d->sales_merge_id);
                $data['reference_number']=$this->super_model->select_column_where("sales_merge_transaction_head","reference_number","sales_merge_id",$d->sales_merge_id);
                $reference_numbers = $this->super_model->custom_query("SELECT reference_no FROM sales_merge_transaction_details WHERE serial_no = '$d->serial_no' AND serial_no != '0' LIMIT 1");
                if (!empty($reference_numbers)) {
                    $reference_number = $reference_numbers[0]->reference_no;
                    $data['refno'] = preg_replace("/[^0-9]/", "", $reference_number); // Optional: get only numbers
                } else {
                    $data['refno'] = ""; // Default if no reference_no found
                }
                $data['or_no']=$d->serial_no;
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);
                $data['participant_id'] = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);

                $data['billing_month'] = date('my',strtotime($d->transaction_date));
                $data['date_uploaded'] = date('Ymd',strtotime($d->create_date));
                $data['refno'] = preg_replace("/[^0-9]/", "",$reference_number);
                $vatable_sales = $this->super_model->select_sum_join("vatable_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$d->serial_no' AND saved = '1'","sales_merge_id");
                $zero_rated_sales = $this->super_model->select_sum_join("zero_rated_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$d->serial_no' AND saved = '1'","sales_merge_id");
                $zero_rated_ecozones = $this->super_model->select_sum_join("zero_rated_ecozones","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$d->serial_no' AND saved = '1'","sales_merge_id");
                $vat_on_sales = $this->super_model->select_sum_join("vat_on_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$d->serial_no' AND saved = '1'","sales_merge_id");
                $ewt = $this->super_model->select_sum_join("ewt","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$d->serial_no' AND saved = '1'","sales_merge_id");

                    $data['total_vs']=$vatable_sales;
                    $data['total_zr']=$zero_rated_sales;
                    $data['total_zra']=$zero_rated_ecozones;
                    $data['total_vos']=$vat_on_sales;
                    $data['total_ewt']=$ewt;
            }
        $this->load->view('sales_merge/sales_wesm_merge_pdf_si',$data);
    }

     public function sales_wesm_merge_pdf_si_bulk(){
        $ref_no=$this->uri->segment(3);
        $due_date=$this->uri->segment(4);
        $in_ex_sub=$this->uri->segment(5);
        $billfrom=$this->uri->segment(6);
        $billto=$this->uri->segment(7);
        $participants=$this->uri->segment(8);

        $sql='';
        if($ref_no!='null'){
            $sql.= "sh.reference_number = '$ref_no' AND ";
        }

        if($due_date!='null'){
            $sql.= "sh.due_date = '$due_date' AND ";
        }

        if($billfrom!='null' && $billto!='null'){ 
            $sql.= " ((sh.billing_from BETWEEN '$billfrom' AND '$billto') OR (sh.billing_to BETWEEN '$billfrom' AND '$billto'))  AND ";
        }

        if(!empty($participants) && $participants!='null'){
            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participants'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " sd.short_name IN($imp) AND ";
        }
        $query=substr($sql,0,-4);
        $qu = " WHERE sd.bulk_pdf_flag = '0' AND serial_no != '' AND saved = '1' AND deleted='0' AND  ".$query;

        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        
            $vatable_sales_bs=array();
            $vat_on_sales_bs=array();
            $ewt_bs=array();
            $zero_rated_ecozone_bs=array();
            $zero_rated_bs=array();
            $data['details']=array();
            foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details sd INNER JOIN sales_merge_transaction_head sh ON sd.sales_merge_id=sh.sales_merge_id $qu GROUP BY serial_no LIMIT 10") AS $d){
                $stl_id=$d->short_name;
                $address=$this->super_model->select_column_where("participant","registered_address","billing_id",$d->billing_id);
                $tin=$this->super_model->select_column_where("participant","tin","billing_id",$d->billing_id);
                if(!empty($d->company_name)){
                    $comp_name=$d->company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $d->billing_id);
                }
                $billing_from=$this->super_model->select_column_where("sales_merge_transaction_head","billing_from","sales_merge_id",$d->sales_merge_id);
                $billing_to=$this->super_model->select_column_where("sales_merge_transaction_head","billing_to","sales_merge_id",$d->sales_merge_id);
                $transaction_date=$this->super_model->select_column_where("sales_merge_transaction_head","transaction_date","sales_merge_id",$d->sales_merge_id);
                // $reference_number=$this->super_model->select_column_where("sales_merge_transaction_head","reference_number","sales_merge_id",$d->sales_merge_id);
                $reference_numbers = $this->super_model->custom_query("SELECT reference_no FROM sales_merge_transaction_details WHERE serial_no = '$d->serial_no' AND serial_no != '0' LIMIT 1");
                if (!empty($reference_numbers)) {
                    $reference_number = $reference_numbers[0]->reference_no;
                    $data['refno'] = preg_replace("/[^0-9]/", "", $reference_number); // Optional: get only numbers
                } else {
                    $data['refno'] = ""; // Default if no reference_no found
                }
                $or_no=$d->serial_no;
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);

                $billing_month = date('my',strtotime($d->transaction_date));
                $date_uploaded = date('Ymd',strtotime($d->create_date));
                $refno = preg_replace("/[^0-9]/", "",$reference_number);
                $vatable_sales = $this->super_model->select_sum_join("vatable_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$d->serial_no' AND saved = '1'","sales_merge_id");
                $zero_rated_sales = $this->super_model->select_sum_join("zero_rated_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$d->serial_no' AND saved = '1'","sales_merge_id");
                $zero_rated_ecozones = $this->super_model->select_sum_join("zero_rated_ecozones","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$d->serial_no' AND saved = '1'","sales_merge_id");
                $vat_on_sales = $this->super_model->select_sum_join("vat_on_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$d->serial_no' AND saved = '1'","sales_merge_id");
                $ewt = $this->super_model->select_sum_join("ewt","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$d->serial_no' AND saved = '1'","sales_merge_id");

                $data['details'][]=array(
                    'sales_id'=>$d->sales_merge_id,
                    'stl_id'=>$d->short_name,
                    'company_name'=>$comp_name,
                    'date'=>$d->transaction_date,
                    'transaction_date'=>$transaction_date,
                    'address'=>$address,
                    'tin'=>$tin,
                    'or_no'=>$d->serial_no,
                    'ref_no'=>$d->reference_number,
                    'billing_to'=>$billing_to,
                    'billing_month'=>$billing_month,
                    'date_uploaded'=>$date_uploaded,
                    'refno'=>$refno,
                    'total_vs'=>$vatable_sales,
                    'total_zr'=>$zero_rated_sales,
                    'total_zra'=>$zero_rated_ecozones,
                    'total_vos'=>$vat_on_sales,
                    'total_ewt'=>$ewt,
                    // 'total_sales'=>$total_sales,
                    // 'net_of_vat'=>$net_of_vat,
                    // 'total_amount_due'=>$total_amount_due,
                );
            }

        $this->load->view('sales_merge/sales_wesm_merge_pdf_si_bulk',$data);
    }

    public function update_sales_wesm_merge_flag(){
        $serial_no = $this->input->post('serial_no');
        $sales_id = $this->input->post('sales_id');
        $filename = $this->input->post('filename');
        $data_update = array(
                "bulk_pdf_flag"=>1,
                "filename"=>$filename
            );
            $this->super_model->update_custom_where("sales_merge_transaction_details", $data_update, "serial_no='$serial_no' AND sales_merge_id='$sales_id'");
    }

    public function export_not_download_sales_wesm_merge(){
        $ref_no=$this->uri->segment(3);
        $due_date=$this->uri->segment(4);
        $in_ex_sub=$this->uri->segment(5);
        $billfrom=$this->uri->segment(6);
        $billto=$this->uri->segment(7);
        $participants=$this->uri->segment(8);

            $sql='';
            if($ref_no!='null'){
                $sql.= "sh.reference_number = '$ref_no' AND ";
            }

            if($due_date!='null'){
                $sql.= "sh.due_date = '$due_date' AND ";
            }

            if($billfrom!='null' && $billto!='null'){ 
                $sql.= " ((sh.billing_from BETWEEN '$billfrom' AND '$billto') OR (sh.billing_to BETWEEN '$billfrom' AND '$billto'))  AND ";
            }

            if(!empty($participants) && $participants!='null'){
               $par=array();
               foreach($this->super_model->select_custom_where('participant',"tin='$participants'") AS $p){
                   $par[]="'".$p->settlement_id."'";
               }
               $imp=implode(',',$par);
               $sql.= " sd.short_name IN($imp) AND ";
            }
            $query=substr($sql,0,-4);
            $qu = " WHERE sd.bulk_pdf_flag = '0' AND serial_no != '' AND saved = '1' AND deleted='0' AND ".$query;

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
        
       foreach($this->super_model->custom_query("SELECT filename FROM sales_merge_transaction_details sd INNER JOIN sales_merge_transaction_head sh ON sd.sales_merge_id=sh.sales_merge_id $qu GROUP BY serial_no,sd.sales_merge_id") AS $db){
        $db_files[] = $db->filename;
       }

        $data['result'] = array_diff($db_files, $pdffiles);

        $result=array_diff($db_files, $pdffiles);
        $x=1;
       
        $this->load->view('sales_merge/export_not_download_sales_wesm_merge',$data);
        $this->load->view('template/footer');
    }

    public function sales_wesm_merge_pdf_scan_directory(){
        $filenames = $this->input->post('filenames');
        $sql="(";
        foreach($filenames AS $f){
            $sql .= " filename =  '$f'  OR ";
        }
        $query=substr($sql,0,-3) . ")";

        
        $qu = "WHERE serial_no != '' AND saved = '1' AND deleted='0' AND ".$query;
        $data['details']=array();
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        $data['timestamp'] = date('Ymd');

            $data['details']=array();
            foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details sd INNER JOIN sales_merge_transaction_head sh ON sd.sales_merge_id=sh.sales_merge_id $qu GROUP BY serial_no LIMIT 10") AS $d){
                $stl_id=$d->short_name;
                $address=$this->super_model->select_column_where("participant","registered_address","billing_id",$d->billing_id);
                $tin=$this->super_model->select_column_where("participant","tin","billing_id",$d->billing_id);
                if(!empty($d->company_name)){
                    $comp_name=$d->company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $d->billing_id);
                }
                $billing_from=$this->super_model->select_column_where("sales_merge_transaction_head","billing_from","sales_merge_id",$d->sales_merge_id);
                $billing_to=$this->super_model->select_column_where("sales_merge_transaction_head","billing_to","sales_merge_id",$d->sales_merge_id);
                $transaction_date=$this->super_model->select_column_where("sales_merge_transaction_head","transaction_date","sales_merge_id",$d->sales_merge_id);
                // $reference_number=$this->super_model->select_column_where("sales_merge_transaction_head","reference_number","sales_merge_id",$d->sales_merge_id);
                $reference_numbers = $this->super_model->custom_query("SELECT reference_no FROM sales_merge_transaction_details WHERE serial_no = '$d->serial_no' AND serial_no != '0' LIMIT 1");
                if (!empty($reference_numbers)) {
                    $reference_number = $reference_numbers[0]->reference_no;
                    $data['refno'] = preg_replace("/[^0-9]/", "", $reference_number); // Optional: get only numbers
                } else {
                    $data['refno'] = ""; // Default if no reference_no found
                }
                $or_no=$d->serial_no;
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);

                $billing_month = date('my',strtotime($d->transaction_date));
                $date_uploaded = date('Ymd',strtotime($d->create_date));
                $refno = preg_replace("/[^0-9]/", "",$reference_number);
                $vatable_sales_bs = $this->super_model->select_sum_join("vatable_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$d->serial_no' AND saved = '1'","sales_merge_id");
                $vat_on_sales_bs = $this->super_model->select_sum_join("zero_rated_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$d->serial_no' AND saved = '1'","sales_merge_id");
                $ewt_bs = $this->super_model->select_sum_join("zero_rated_ecozones","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$d->serial_no' AND saved = '1'","sales_merge_id");
                $zero_rated_ecozone_bs = $this->super_model->select_sum_join("vat_on_sales","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$d->serial_no' AND saved = '1'","sales_merge_id");
                $zero_rated_bs = $this->super_model->select_sum_join("ewt","sales_merge_transaction_details","sales_merge_transaction_head", "serial_no='$d->serial_no' AND saved = '1'","sales_merge_id");

                $total_vs=$vatable_sales_bs;
                $total_zr=$zero_rated_bs;
                $total_zra=$zero_rated_ecozone_bs;
                $total_vos=$vat_on_sales_bs;
                $total_ewt=$ewt_bs;
                $total_sales = $total_vs + $total_zra + $total_vos;
                $net_of_vat = $total_vs + $total_zra;
                $total_amount_due = ($total_vs + $total_zra + $total_vos) - $total_ewt;

                $data['details'][]=array(
                    'sales_id'=>$d->sales_merge_id,
                    'stl_id'=>$d->short_name,
                    'company_name'=>$comp_name,
                    'date'=>$d->transaction_date,
                    'transaction_date'=>$transaction_date,
                    'address'=>$address,
                    'tin'=>$tin,
                    'or_no'=>$d->serial_no,
                    'ref_no'=>$d->reference_number,
                    'billing_to'=>$billing_to,
                    'billing_month'=>$billing_month,
                    'date_uploaded'=>$date_uploaded,
                    'refno'=>$refno,
                    'total_vs'=>$total_vs,
                    'total_zr'=>$total_zr,
                    'total_zra'=>$total_zra,
                    'total_vos'=>$total_vos,
                    'total_ewt'=>$total_ewt,
                    'total_sales'=>$total_sales,
                    'net_of_vat'=>$net_of_vat,
                    'total_amount_due'=>$total_amount_due,
                );
            }
        $this->load->view('sales_merge/sales_wesm_merge_pdf_scan_directory',$data);
    }

    public function bulk_invoicing_merge(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $identifier_code=$this->generateRandomString();
        $data['identifier_code']=$identifier_code;
        $yeardisp=$this->uri->segment(3);
        $reference=$this->uri->segment(4);
        $due_date=$this->uri->segment(5);
        $identifier=$this->uri->segment(6);
        $data['year_disp'] = $yeardisp;
        $data['reference_number'] = $reference;
        $data['due_date'] = $due_date;
        $data['identifier']=$this->uri->segment(6);
        $sql="";

        if($yeardisp!='null'){
            $sql.= "YEAR(billing_to) = '$yeardisp'  AND ";
        } if($reference!='null'){
             $sql.= "reference_number = '$reference' AND "; 
        } if($due_date!='null'){
            $sql.= "due_date = '$due_date' AND ";
        }
     
        $query=substr($sql,0,-4);
        $qu = "saved='1' AND bulk_invoicing_identifier ='$identifier' AND ".$query;

        $data['saved']=$this->super_model->select_column_where("sales_merge_transaction_details","saved_bulk_invoicing","bulk_invoicing_identifier",$identifier);
        $data['years'] = $this->super_model->custom_query("SELECT DISTINCT YEAR(billing_to) AS year FROM sales_merge_transaction_head WHERE saved='1' ORDER BY year DESC");
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT reference_number FROM sales_merge_transaction_head WHERE reference_number!='' AND saved='1' AND deleted='0' ORDER BY due_date ASC");
        $data['due'] = $this->super_model->custom_query("SELECT DISTINCT due_date FROM sales_merge_transaction_head WHERE saved='1'AND deleted='0' ORDER BY due_date ASC");
        foreach($this->super_model->custom_query("SELECT * FROM sales_merge_transaction_details std INNER JOIN sales_merge_transaction_head sth ON std.sales_merge_id=sth.sales_merge_id WHERE $qu") AS $d){
            $data['details'][]=array(
                'sales_detail_id'=>$d->sales_merge_detail_id,
                'sales_id'=>$d->sales_merge_id,
                'reference_no'=>$d->reference_no,
                'settlement_id'=>$d->short_name,
                'billing_id'=>$d->billing_id,
                'actual_billing_id'=>$d->actual_billing_id,
                'serial_no'=>$d->serial_no,
            );
        }
        $this->load->view('sales_merge/upload_bulk_invoicing_merge', $data);
        $this->load->view('template/footer');
    }

    public function cancel_merge_sales_invoicing(){
        $main_identifier = $this->input->post('main_identifier');
        $data_main = array(
            'serial_no'=>Null,
            'bulk_invoicing_identifier'=>Null,
        );
        $this->super_model->update_custom_where("sales_merge_transaction_details", $data_main, "bulk_invoicing_identifier='$main_identifier'");
    }

    public function upload_bulk_invoicing_merge(){
        $year = $this->input->post('year');
        $reference = $this->input->post('reference');
        $due = $this->input->post('due');
        $dest= realpath(APPPATH . '../uploads/excel/');
        $error_ext=0;
        if(!empty($_FILES['doc']['name'])){
            $exc= basename($_FILES['doc']['name']);
            $exc=explode('.',$exc);
            $ext1=$exc[1];
            if($ext1=='php' || $ext1!='xlsx'){
                $error_ext++;
            }else{
                $filename1='bulk_upload_sales_merge.'.$ext1;
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                     $this->readExcel_bulkinvoicing_merge($year,$reference,$due);
                }
            }
        }
    }

    public function readExcel_bulkinvoicing_merge($year,$reference,$due){
        $objPHPExcel = new Spreadsheet();
        $inputFileName =realpath(APPPATH.'../uploads/excel/bulk_upload_sales_merge.xlsx');
           try {
                $inputFileType = io_factory::identify($inputFileName);
                $objReader = io_factory::createReader($inputFileType);
            
       
                $objPHPExcel = $objReader->load($inputFileName);
            } 
        catch(Exception $e) {
            die('Error loading file"'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow();
        for($x=2;$x<=$highestRow;$x++){
            $identifier = $this->input->post('identifier');
            $reference_no = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue() ?? '');
            $settlement_id = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue() ?? '');
            $billing_id = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue() ?? '');
            $invoice_no = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getFormattedValue() ?? '');

            $sql="";
            if($year!='null'){
                $sql.= "YEAR(billing_to) = '$year'  AND ";
            } if($reference!='null'){
                 $sql.= "reference_number = '$reference' AND "; 
            } if($due!='null'){
                $sql.= "due_date = '$due' AND ";
            }
         
            $query=substr($sql,0,-4);

           $sales_id = array();
            foreach ($this->super_model->select_custom_where('sales_merge_transaction_head', "$query") as $dues) {
                $sales_id[] = $dues->sales_merge_id;
            }

            if (!empty($sales_id)) {
                $salesid_str = "'" . implode("','", $sales_id) . "'";

                $data_main = array(
                    'serial_no' => $invoice_no,
                    'bulk_invoicing_identifier' => $identifier,
                );

                $this->super_model->update_custom_where("sales_merge_transaction_details",$data_main,"sales_merge_id IN ($salesid_str) AND reference_no='$reference_no' AND short_name='$settlement_id' AND billing_id='$billing_id'");
            }
        }
    }

    public function save_bulkinvoicing_merge(){
        $bulk_invoicing_identifier = $this->input->post('main_identifier');
        $data_head = array(
            'saved_bulk_invoicing'=>1
        );
        $this->super_model->update_custom_where("sales_merge_transaction_details", $data_head, "bulk_invoicing_identifier='$bulk_invoicing_identifier'");
    }

    public function upload_merge_collection(){
        $id=$this->uri->segment(3);
        $data['collection_id'] = $id;
        $data['collection']=array();
        if(!empty($id)){
            foreach($this->super_model->select_row_where("merge_collection_head", "merge_collection_id", $id) AS $h){
                $data['saved']=$h->saved;
                $data['collection_date']=$h->collection_date;
            foreach($this->super_model->select_row_where("merge_collection_details","merge_collection_id",$h->merge_collection_id) AS $col){
            $count_series=$this->super_model->count_custom_where("merge_collection_details","series_number='$col->series_number' AND series_number!='' AND settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id'");
            $sum_amount= $this->super_model->select_sum_where("merge_collection_details","amount","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id'");
            $sum_zero_rated= $this->super_model->select_sum_where("merge_collection_details","zero_rated","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id'");
            $sum_zero_rated_ecozone= $this->super_model->select_sum_where("merge_collection_details","zero_rated_ecozone","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id'");
            $sum_vat = $this->super_model->select_sum_where("merge_collection_details","vat","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id'");
            $sum_ewt= $this->super_model->select_sum_where("merge_collection_details","ewt","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id'");
                    $overall_total=($sum_amount + $sum_zero_rated + $sum_zero_rated_ecozone + $sum_vat)-$sum_ewt;
                    $data['collection'][]=array(
                        "count_series"=>$count_series,
                        "collection_details_id"=>$col->merge_collection_details_id,
                        "collection_id"=>$col->merge_collection_id,
                        "settlement_id"=>$col->settlement_id,
                        "series_number"=>$col->series_number,
                        "or_date"=>$col->or_date,
                        "billing_remarks"=>$col->billing_remarks,
                        "particulars"=>$col->particulars,
                        "item_no"=>$col->item_no,
                        "defint"=>$col->defint,
                        "reference_no"=>$col->reference_no,
                        "vat"=>$col->vat,
                        "zero_rated"=>$col->zero_rated,
                        "zero_rated_ecozone"=>$col->zero_rated_ecozone,
                        "ewt"=>$col->ewt,
                        "total"=>$col->total,
                        "company_name"=>$col->buyer_fullname,
                        "amount"=>$col->amount,
                        "or_no_remarks"=>$col->or_no_remarks,
                        "overall_total"=>$overall_total,
                    );
                }
            }
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales_merge/upload_merge_collection', $data);
        $this->load->view('template/footer');
    }

    public function add_merge_collection_head(){
        $data=array(
            "collection_date"=>$this->input->post('collection_date'),
            "user_id"=>$_SESSION['user_id'],
        );
        $collection_id = $this->super_model->insert_return_id("merge_collection_head",$data);

        echo $collection_id;
    }

    public function cancel_merge_collection(){
        $collection_id = $this->input->post('collection_id');
        $this->super_model->delete_where("merge_collection_head", "merge_collection_id", $collection_id);
        $this->super_model->delete_where("merge_collection_details", "merge_collection_id", $collection_id);
    }
    
    public function merge_collection_list(){
        $date=$this->uri->segment(3);
        $ref_no=$this->uri->segment(4);
        $buyer_fullname = urldecode($this->uri->segment(5) ?? '');
        // $stl_id=$this->uri->segment(5);
        $data['date'] = $date;
        $data['ref_no'] = $ref_no;
         $data['buyer_fullname'] = $buyer_fullname;
        // $data['stl_id'] = $stl_id;
        $data['collection_date'] = $this->super_model->custom_query("SELECT DISTINCT collection_date FROM merge_collection_head WHERE saved != '0'");
        $data['reference_no'] = $this->super_model->custom_query("SELECT DISTINCT reference_no FROM merge_collection_head ch INNER JOIN merge_collection_details cd ON ch.merge_collection_id = cd.merge_collection_id WHERE reference_no!='' AND saved != '0'");
        $data['buyer'] = $this->super_model->custom_query("SELECT DISTINCT settlement_id,buyer_fullname FROM merge_collection_head ch INNER JOIN merge_collection_details cd ON ch.merge_collection_id = cd.merge_collection_id WHERE reference_no!='' AND saved != '0' GROUP BY buyer_fullname");
        $data['employees']=$this->super_model->select_all_order_by("users","fullname",'ASC');
        $sql="";
       

        if($date!='null'){
            $sql.= "ch.collection_date = '$date' AND ";
        } if($ref_no!='null'){
             $sql.= "cd.reference_no = '$ref_no' AND ";
        }if ($buyer_fullname != 'null') {
            $normalizedName = strtoupper(str_replace(['-', ',', '.', ' '], '', $buyer_fullname));
            $sql .= "REPLACE(REPLACE(REPLACE(REPLACE(UPPER(cd.buyer_fullname), '-', ''), ',', ''), '.', ''), ' ', '') = '$normalizedName' AND ";
        }
        // } if($stl_id!='null'){
        //      $sql.= "cd.settlement_id = '$stl_id' AND "; 
        // }

        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND ".$query;
        $data['collection']=array();

            foreach($this->super_model->custom_query("SELECT * FROM merge_collection_head ch INNER JOIN merge_collection_details cd ON ch.merge_collection_id = cd.merge_collection_id WHERE $qu") AS $col){
            $count_series=$this->super_model->count_custom_where("merge_collection_details","series_number='$col->series_number' AND series_number!='' AND settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND series_number='$col->series_number'");
            $sum_amount= $this->super_model->select_sum_where("merge_collection_details","amount","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND series_number='$col->series_number'");
            $sum_zero_rated= $this->super_model->select_sum_where("merge_collection_details","zero_rated","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND series_number='$col->series_number'");
            $sum_zero_rated_ecozone= $this->super_model->select_sum_where("merge_collection_details","zero_rated_ecozone","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND series_number='$col->series_number'");
            $sum_vat = $this->super_model->select_sum_where("merge_collection_details","vat","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND series_number='$col->series_number'");
            $sum_ewt= $this->super_model->select_sum_where("merge_collection_details","ewt","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND series_number='$col->series_number'");
            $sum_def_int = $this->super_model->select_sum_where("merge_collection_details","defint","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND series_number='$col->series_number'");
            $overall_total=($sum_amount + $sum_zero_rated + $sum_zero_rated_ecozone + $sum_vat)-$sum_ewt;
            $data['collection'][]=array(
                "count_series"=>$count_series,
                "collection_details_id"=>$col->merge_collection_details_id,
                "collection_date"=>$col->collection_date,
                "collection_id"=>$col->merge_collection_id,
                "settlement_id"=>$col->settlement_id,
                "series_number"=>$col->series_number,
                "or_date"=>$col->or_date,
                "billing_remarks"=>$col->billing_remarks,
                "particulars"=>$col->particulars,
                "item_no"=>$col->item_no,
                "defint"=>$col->defint,
                "reference_no"=>$col->reference_no,
                "vat"=>$col->vat,
                "zero_rated"=>$col->zero_rated,
                "zero_rated_ecozone"=>$col->zero_rated_ecozone,
                "ewt"=>$col->ewt,
                "total"=>$col->total,
                "company_name"=>$col->buyer_fullname,
                "amount"=>$col->amount,
                "or_no_remarks"=>$col->or_no_remarks,
                "overall_total"=>$overall_total,
            );
            $data['details_id']=$col->merge_collection_details_id;
    }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales_merge/merge_collection_list', $data);
        $this->load->view('template/footer');
    }

    public function upload_bulk_merge_collection(){
        $collection_id=$this->input->post('collection_id');
        $dest= realpath(APPPATH . '../uploads/excel/');
        $error_ext=0;
        if(!empty($_FILES['doc']['name'])){
            $exc= basename($_FILES['doc']['name']);
            $exc=explode('.',$exc);
            $ext1=$exc[1];
            
            if($ext1=='php'){
                $error_ext++;
            } else {
                $filename1='bulkmergecollection.'.$ext1;
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                     $this->readBulkMergeCollection($collection_id,$ext1);
                } 
            }
        }
    }

    public function readBulkMergeCollection($collection_id,$doc_type){
        $objPHPExcel = new Spreadsheet();

        if($doc_type=='xlsx'){
            $inputFileName =realpath(APPPATH.'../uploads/excel/bulkmergecollection.xlsx');
        }else if($doc_type=='xlsm'){
            $inputFileName =realpath(APPPATH.'../uploads/excel/bulkmergecollection.xlsm');
        }
       try {
            $inputFileType = io_factory::identify($inputFileName);
            $objReader = io_factory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } 

        catch(Exception $e) {
            die('Error loading file"'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
         
        $objPHPExcel->setActiveSheetIndex(0);
        $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 
      
        $data_head=array(
            "date_uploaded"=>date('Y-m-d H:i:s'),
        );
        $this->super_model->update_where("merge_collection_head", $data_head, "merge_collection_id", $collection_id);
        $a=1;
        echo $highestRow;
            for($x=6;$x<=$highestRow;$x++){
                $remarks = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue() ?? '');
                if($remarks!='' ){
                $particulars = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue() ?? '');
                $stl_id = str_replace(array('_FIT'), '',$objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue());
                $buyer = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getFormattedValue() ?? '');
                $statement_no = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue() ?? '');
                $vatable_sales = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('F'.$x)->getFormattedValue());
                $zero_rated = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('G'.$x)->getFormattedValue());
                $zero_rated_ecozone = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('H'.$x)->getFormattedValue());
                $vat = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('I'.$x)->getFormattedValue());
                $ewt = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('J'.$x)->getFormattedValue());
                $total = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('K'.$x)->getFormattedValue());
             
                 $data_details = array(
                        'merge_collection_id'=>$collection_id,
                        'or_date'=>$this->super_model->select_column_where("merge_collection_head","collection_date","merge_collection_id",$collection_id),
                        'item_no'=>$a,
                        'billing_remarks'=>$remarks,
                        'particulars'=>$particulars,
                        'reference_no'=>rtrim($statement_no,"S"),
                        'settlement_id'=>$stl_id,
                        'buyer_fullname'=>$buyer,
                        'amount'=>$vatable_sales,
                        'vat'=>$vat,
                        'zero_rated'=>$zero_rated,
                        'zero_rated_ecozone'=>$zero_rated_ecozone,
                        'ewt'=>$ewt,
                        'total'=>$total,
                    );
                    $this->super_model->insert_into("merge_collection_details", $data_details);
                    $a++;
            }
        }
    }

    public function save_all_merge_collection(){
        $collection_id = $this->input->post('collection_id');
        $data_head = array(
            'saved'=>1,
        );
        $this->super_model->update_where("merge_collection_head",$data_head, "merge_collection_id", $collection_id);
    }

    public function print_merge_OR(){
               $collection_id=$this->uri->segment(3);
        $settlement_id=$this->uri->segment(4);
        $reference_no=$this->uri->segment(5);
        $data['ref_no'] = $reference_no;
        $data['client']=$this->super_model->select_row_where("participant", "billing_id", $settlement_id);
        $data['sum_amount']=$this->super_model->select_sum_where("merge_collection_details","amount","settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['sum_vat']=$this->super_model->select_sum_where("merge_collection_details","vat","settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['sum_ewt'] =  $this->super_model->select_sum_where("merge_collection_details", "ewt", "settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['sum_zero_rated'] =  $this->super_model->select_sum_where("merge_collection_details", "zero_rated", "settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['sum_zero_rated_ecozone'] =  $this->super_model->select_sum_where("merge_collection_details", "zero_rated_ecozone", "settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['defint'] =  $this->super_model->select_sum_where("merge_collection_details", "defint", "settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['date'] =  $this->super_model->select_column_custom_where("merge_collection_details","or_date","settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no'");
        $this->load->view('template/print_head');
        $this->load->view('sales_merge/print_merge_OR',$data);
    }

    public function update_merge_seriesno(){
        $ref_no=$this->input->post('reference_number');
        $collection_id=$this->input->post('collection_id');
        $new_series=$this->input->post('series_number');
        $old_series=$this->input->post('old_series');
        $settlement_id=$this->input->post('settlement_id');
        $item_no=$this->input->post('item_no');
        foreach($this->super_model->select_custom_where("merge_collection_details","merge_collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $check){
            $count=$this->super_model->count_custom_where("merge_collection_details","merge_collection_id = '$check->collection_id' AND old_series_no!='' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)");
            if($count==0){
                $old_series_insert = $old_series;
            }else{
                $old_series_insert = $old_series.", ".$check->old_series_no;
            }
        }

        $data_update = array(
            'series_number'=>$new_series,
            'old_series_no'=>$old_series_insert,
        );

        if($this->super_model->update_custom_where("merge_collection_details", $data_update, "merge_collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)")){
            foreach($this->super_model->select_custom_where("merge_collection_details","merge_collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $latest_series){
               echo $latest_series->series_number;
            }
        }
    }

    public function update_merge_ordate(){
        $ref_no=$this->input->post('reference_number');
        $collection_id=$this->input->post('collection_id');
        $new_or_date=$this->input->post('or_date');
        $old_or_date=$this->input->post('or_date');
        $settlement_id=$this->input->post('settlement_id');
        $item_no=$this->input->post('item_no');
        foreach($this->super_model->select_custom_where("merge_collection_details","merge_collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $check){
            $count=$this->super_model->count_custom_where("merge_collection_details","merge_collection_id = '$check->collection_id' AND old_or_date!='' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)");
            if($count==0){
                $old_ordate_insert = $old_or_date;
            }else{
                $old_ordate_insert = $old_or_date.", ".$check->old_or_date;
            }
        }

        $data_update = array(
            'or_date'=>$new_or_date,
            'old_or_date'=>$old_ordate_insert,
        );

        if($this->super_model->update_custom_where("merge_collection_details", $data_update, "merge_collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)")){
            foreach($this->super_model->select_custom_where("merge_collection_details","merge_collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $latest_ordate){
               echo $latest_ordate->or_date;
            }
        }
    }

    public function update_merge_defint(){
        $ref_no=$this->input->post('reference_number');
        $collection_id=$this->input->post('collection_id');
        $def_int=$this->input->post('def_int');
        $settlement_id=$this->input->post('settlement_id');
        $item_no=$this->input->post('item_no');
        $data_update = array(
            'defint'=>$def_int,
        );

        if($this->super_model->update_custom_where("merge_collection_details", $data_update, "merge_collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)")){
            foreach($this->super_model->select_custom_where("merge_collection_details","merge_collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $latest_defint){
               echo $latest_defint->defint;
            }
        }
    }

    public function update_merge_orno_remarks(){
        $ref_no=$this->input->post('reference_number');
        $collection_id=$this->input->post('collection_id');
        $orno_remarks=$this->input->post('or_no_remarks');
        $settlement_id=$this->input->post('settlement_id');
        $item_no=$this->input->post('item_no');
        $data_update = array(
            'or_no_remarks'=>$orno_remarks,
        );

        if($this->super_model->update_custom_where("merge_collection_details", $data_update, "merge_collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)")){
            if (strpos($orno_remarks, ',') !== false) {
                $or_no_remarks=explode(",", $orno_remarks);
                $or_no=$or_no_remarks[0];
                $remarks=$or_no_remarks[1];
                $now = date("Y-m-d H:i:s");
                    $or_remarks = array(
                       "or_no"=>$or_no,
                       "remarks"=>$remarks,
                       "create_date"=>$now,
                       "user_id"=>$_SESSION['user_id'],
                    );
                    $this->super_model->insert_into("or_remarks", $or_remarks);
                    }else{
                        $or_no='';
                        $remarks='';
                    }

            foreach($this->super_model->select_custom_where("merge_collection_details","merge_collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $latest_or_remarks){
               echo $latest_or_remarks->or_no_remarks;
            }
        }
    }

    public function PDF_merge_OR(){
        $collection_id=$this->uri->segment(3);
        $settlement_id=str_replace('%20', ' ', $this->uri->segment(4));
        $reference_no=$this->uri->segment(5);
        $series_number=$this->uri->segment(6);
        $signatory= ($this->uri->segment(7)!='' || !empty($this->uri->segment(7))) ? $this->uri->segment(7) : $_SESSION['user_id'];
        $data['ref_no'] = $reference_no;
        $data['refno'] = preg_replace("/[^0-9]/", "",$reference_no);

        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id", $signatory);
        $collection_date = $this->super_model->select_column_where("merge_collection_head", "collection_date", "merge_collection_id", $collection_id);
        $data['billing_month'] = date('my',strtotime($collection_date));
        $data['timestamp'] = date('Ymd');
        $billing_id = $this->super_model->select_column_where("participant", "billing_id", "settlement_id", $settlement_id);
        $data['address']=$this->super_model->select_column_where("participant", "registered_address", "billing_id", $billing_id);
        $data['tin']=$this->super_model->select_column_where("participant", "tin", "billing_id", $billing_id);
        $data['stl_id']=$this->super_model->select_column_custom_where("merge_collection_details","settlement_id","settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['or_no']=$series_number;
        $data['buyer']=$this->super_model->select_column_custom_where("merge_collection_details","buyer_fullname","settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no' ");
        $data['sum_amount']=$this->super_model->select_sum_where("merge_collection_details","amount","settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['sum_vat']=$this->super_model->select_sum_where("merge_collection_details","vat","settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['sum_ewt'] =  $this->super_model->select_sum_where("merge_collection_details", "ewt", "settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['sum_zero_rated'] =  $this->super_model->select_sum_where("merge_collection_details", "zero_rated", "settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['sum_zero_rated_ecozone'] =  $this->super_model->select_sum_where("merge_collection_details", "zero_rated_ecozone", "settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['defint'] =  $this->super_model->select_sum_where("merge_collection_details", "defint", "settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['date']=$this->super_model->select_column_custom_where("merge_collection_details","or_date","settlement_id='$settlement_id' AND merge_collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $this->load->view('sales_merge/PDF_merge_OR',$data);
    }

    public function PDF_merge_OR_bulk(){
        $date=$this->uri->segment(3);
        $ref_no=$this->uri->segment(4);
        $buyer_fullname = urldecode($this->uri->segment(5) ?? '');
        // $stl_id=$this->uri->segment(5);
        $signatory=($this->uri->segment(6)!='' || !empty($this->uri->segment(6))) ? $this->uri->segment(6) : $_SESSION['user_id'];

        $sql="";

        if($date!='null'){
            $sql.= "ch.collection_date = '$date' AND ";
        } if($ref_no!='null'){
             $sql.= "cd.reference_no = '$ref_no' AND ";
        }if ($buyer_fullname != 'null') {
            $normalizedName = strtoupper(str_replace(['-', ',', '.', ' '], '', $buyer_fullname));
            $sql .= "REPLACE(REPLACE(REPLACE(REPLACE(UPPER(cd.buyer_fullname), '-', ''), ',', ''), '.', ''), ' ', '') = '$normalizedName' AND ";
        }
        // } if($stl_id!='null'){
        //      $sql.= "cd.settlement_id = '$stl_id' AND "; 
        // }

        $query=substr($sql,0,-4);
        $qu = "bulk_pdf_flag = '0' AND series_number != '0' AND saved = '1' AND ".$query;

        $data['details']=array();
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$signatory);
        $data['timestamp'] = date('Ymd');

            foreach($this->super_model->custom_query("SELECT * FROM merge_collection_head ch INNER JOIN merge_collection_details cd ON ch.merge_collection_id = cd.merge_collection_id WHERE $qu GROUP BY series_number LIMIT 10") AS $col){
            if($ref_no!='null'){
                $reference_number = $ref_no;
            }else{
                $reference_number = $col->reference_no;
            }

            $billing_month = date('my',strtotime($col->collection_date));
            $refno = preg_replace("/[^0-9]/", "",$reference_number);

            $billing_id = $this->super_model->select_column_where("participant", "billing_id", "settlement_id", $col->settlement_id);
            $sum_amount=$this->super_model->select_sum_where("merge_collection_details","amount","settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_vat=$this->super_model->select_sum_where("merge_collection_details","vat","settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_ewt =  $this->super_model->select_sum_where("merge_collection_details", "ewt", "settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND reference_no='$col->reference_no'");
            $sum_zero_rated =  $this->super_model->select_sum_where("merge_collection_details", "zero_rated", "settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_zero_rated_ecozone =  $this->super_model->select_sum_where("merge_collection_details", "zero_rated_ecozone", "settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $defint =  $this->super_model->select_sum_where("merge_collection_details", "defint", "settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");

            $data['details'][] = array(
                'collection_details_id'=>$col->merge_collection_details_id,
                'collection_id'=>$col->merge_collection_id,
                'billing_id'=>$billing_id,
                'address'=>$this->super_model->select_column_where("participant", "registered_address", "billing_id", $billing_id),
                'tin'=>$this->super_model->select_column_where("participant", "tin", "billing_id", $billing_id),
                'ref_no'=>$col->reference_no,
                'refno'=>$refno,
                'stl_id'=>$col->settlement_id,
                'buyer'=>$col->buyer_fullname,
                'or_no'=>$col->series_number,
                'date'=>$col->or_date,
                'sum_amount'=>$sum_amount,
                'sum_vat'=>$sum_vat,
                'sum_ewt'=>$sum_ewt,
                'sum_zero_rated'=>$sum_zero_rated,
                'sum_zero_rated_ecozone'=>$sum_zero_rated_ecozone,
                'defint'=>$defint,
                'billing_month'=>$billing_month,
            );
        }

        $this->load->view('sales_merge/PDF_merge_OR_bulk',$data);
    }

    public function update_merge_flag(){
        $series_number = $this->input->post('series_no');
        $settlement_id = $this->input->post('stl_id');
        $reference_no = $this->input->post('reference_no');
        $collection_id = $this->input->post('collection_id');
        $filename = $this->input->post('filename');
        $data_update = array(
                "bulk_pdf_flag"=>1,
                "filename"=>$filename
            );
            $this->super_model->update_custom_where("merge_collection_details", $data_update, "series_number='$series_number' AND settlement_id='$settlement_id' AND reference_no='$reference_no' AND merge_collection_id='$collection_id'");
    }

    public function update_seriesno(){
        $ref_no=$this->input->post('reference_number');
        $collection_id=$this->input->post('collection_id');
        $new_series=$this->input->post('series_number');
        $old_series=$this->input->post('old_series');
        $settlement_id=$this->input->post('settlement_id');
        $item_no=$this->input->post('item_no');
        foreach($this->super_model->select_custom_where("merge_collection_details","merge_collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $check){
            $count=$this->super_model->count_custom_where("collection_details","merge_collection_id = '$check->collection_id' AND old_series_no!='' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)");
            if($count==0){
                $old_series_insert = $old_series;
            }else{
                $old_series_insert = $old_series.", ".$check->old_series_no;
            }
        }

        $data_update = array(
            'series_number'=>$new_series,
            'old_series_no'=>$old_series_insert,
        );

        if($this->super_model->update_custom_where("merge_collection_details", $data_update, "merge_collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)")){
            foreach($this->super_model->select_custom_where("merge_collection_details","merge_collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $latest_series){
               echo $latest_series->series_number;
            }
        }
    }

    public function export_not_download_merge_collection(){
        $date=$this->uri->segment(3);
        $ref_no=$this->uri->segment(4);
        $buyer_fullname = urldecode($this->uri->segment(5) ?? '');
        // $stl_id=$this->uri->segment(5);

        $sql="";

        if($date!='null'){
            $sql.= "ch.collection_date = '$date' AND ";
        } if($ref_no!='null'){
             $sql.= "cd.reference_no = '$ref_no' AND "; 
        }if ($buyer_fullname != 'null') {
            $normalizedName = strtoupper(str_replace(['-', ',', '.', ' '], '', $buyer_fullname));
            $sql .= "REPLACE(REPLACE(REPLACE(REPLACE(UPPER(cd.buyer_fullname), '-', ''), ',', ''), '.', ''), ' ', '') = '$normalizedName' AND ";
        }
        // } if($stl_id!='null'){
        //      $sql.= "cd.settlement_id = '$stl_id' AND "; 
        // }
        $query=substr($sql,0,-4);
        $qu = "bulk_pdf_flag = '0' AND series_number != '0' AND saved = '1' AND ".$query;

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
        
       foreach($this->super_model->custom_query("SELECT filename FROM merge_collection_head ch INNER JOIN merge_collection_details cd ON ch.merge_collection_id = cd.merge_collection_id WHERE $qu GROUP BY series_number,settlement_id,reference_no") AS $db){
        $db_files[] = $db->filename;
       }
        
        $data['result'] = array_diff($db_files, $pdffiles);

        $result=array_diff($db_files, $pdffiles);
        $x=1;
       
        $this->load->view('sales_merge/export_not_download_merge_collection',$data);
        $this->load->view('template/footer');
    }

    public function merge_pdf_scan_directory(){
        $filenames = $this->input->post('filenames');
        $sql="(";
        foreach($filenames AS $f){
            $sql .= " filename =  '$f'  OR ";
        }
        $query=substr($sql,0,-3) . ")";

        
        $qu = "series_number != '0' AND saved = '1' AND ".$query;
        
        $data['details']=array();
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        $data['timestamp'] = date('Ymd');

            foreach($this->super_model->custom_query("SELECT * FROM merge_collection_head ch INNER JOIN merge_collection_details cd ON ch.merge_collection_id = cd.merge_collection_id WHERE $qu GROUP BY series_number,settlement_id,reference_no") AS $col){

             
            $reference_number = $col->reference_no;
            $billing_month = date('my',strtotime($col->collection_date));
            $refno = preg_replace("/[^0-9]/", "",$reference_number);

            $billing_id = $this->super_model->select_column_where("participant", "billing_id", "settlement_id", $col->settlement_id);
            $sum_amount=$this->super_model->select_sum_where("merge_collection_details","amount","settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_vat=$this->super_model->select_sum_where("merge_collection_details","vat","settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_ewt =  $this->super_model->select_sum_where("merge_collection_details", "ewt", "settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND reference_no='$col->reference_no'");
            $sum_zero_rated =  $this->super_model->select_sum_where("merge_collection_details", "zero_rated", "settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_zero_rated_ecozone =  $this->super_model->select_sum_where("merge_collection_details", "zero_rated_ecozone", "settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $defint =  $this->super_model->select_sum_where("merge_collection_details", "defint", "settlement_id='$col->settlement_id' AND merge_collection_id='$col->merge_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");

            $data['details'][] = array(
                'collection_details_id'=>$col->merge_collection_details_id,
                'collection_id'=>$col->merge_collection_id,
                'billing_id'=>$billing_id,
                'address'=>$this->super_model->select_column_where("participant", "registered_address", "billing_id", $billing_id),
                'tin'=>$this->super_model->select_column_where("participant", "tin", "billing_id", $billing_id),
                'ref_no'=>$col->reference_no,
                'refno'=>$refno,
                'stl_id'=>$col->settlement_id,
                'buyer'=>$col->buyer_fullname,
                'or_no'=>$col->series_number,
                //'date'=>$col->collection_date,
                'date'=>$col->or_date,
                'sum_amount'=>$sum_amount,
                'sum_vat'=>$sum_vat,
                'sum_ewt'=>$sum_ewt,
                'sum_zero_rated'=>$sum_zero_rated,
                'sum_zero_rated_ecozone'=>$sum_zero_rated_ecozone,
                'defint'=>$defint,
                'billing_month'=>$billing_month,
            );
        }
        $this->load->view('sales_merge/merge_pdf_scan_directory',$data);
    }


    public function check_reference_sales_merge(){
        $reference_number = trim($this->input->post('reference_number'));
        $sales_id     = $this->input->post('sales_id');

        if(empty($reference_number)){
            echo "available";
            return;
        }

        $this->db->where('reference_number', $reference_number);
        $this->db->where('deleted', 0); // ✅ correct column

        // Exclude current record when editing
        if(!empty($sales_id)){
            $this->db->where('sales_id !=', $sales_id);
        }

        $query = $this->db->get('sales_merge_transaction_head');

        if($query->num_rows() > 0){

            $row = $query->row();

            if($row->saved == 1){  // ✅ correct column
                echo "exists_saved";
            } else {
                echo "exists_unsaved";
            }

        } else {
            echo "available";
        }
    }

}