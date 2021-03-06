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

    public function print_multiple(){
        $identifier=$this->input->post('multiple_print');
        //$count=$this->input->post('count');
        $count=count($identifier);
        $sales_detail_disp='';
        for($x=0;$x<$count;$x++){
            //echo $x;
            //echo $identifier[$x];
            $exp_identifier=explode(",",$identifier[$x]);
            $identifier_code=$exp_identifier[0];
            $sales_detail_id=$exp_identifier[1];
            $sales_detail_disp.=$exp_identifier[1]."-";
            $reference_number=$exp_identifier[2];
            $data_head = array(
                'print_identifier'=>$identifier_code
            );
            $this->super_model->update_where("sales_transaction_details",$data_head, "sales_detail_id", $sales_detail_id);
        }
        echo $sales_detail_disp.",".$identifier_code.",".$count;
    }

    public function upload_sales()
    {
        $id=$this->uri->segment(3);
        $data['sales_id'] = $id;
        $data['identifier_code']=$this->generateRandomString();
        if(!empty($id)){
            foreach($this->super_model->select_row_where("sales_transaction_head", "sales_id",$id) AS $h){
                $data['transaction_date']=$h->transaction_date;
                $data['billing_from']=$h->billing_from;
                $data['billing_to']=$h->billing_to;
                $data['reference_number']=$h->reference_number;
                $data['due_date']=$h->due_date;
                $data['saved']=$h->saved;
                //$data['adjustment']=$h->adjustment;
                foreach($this->super_model->select_row_where("sales_transaction_details","sales_id",$h->sales_id) AS $d){
                    $data['details'][]=array(
                        'sales_detail_id'=>$d->sales_detail_id,
                        'sales_id'=>$d->sales_id,
                        'item_no'=>$d->item_no,
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

    public function print_BS_multiple(){
        /*$sales_detail_id = $this->uri->segment(3);
        $data['sales_detail_id']=$sales_detail_id;*/
        $sales_details_id = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $data['count']=$count;
        $sales_det_exp=explode("-",$sales_details_id);
        $data['sales_detail_id']=$print_identifier;
        $data['address']='';
        $data['tin']='';
        $data['company_name']='';
        $data['settlement']='';
        $data['billing_from']='';
        $data['billing_to']='';
        $data['due_date']='';
        $data['reference_number']='';
        for($x=0;$x<$count;$x++){
            foreach($this->super_model->select_custom_where("sales_transaction_details","print_identifier='$print_identifier' AND sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                $data['address'][$x]=$this->super_model->select_column_where("participant","office_address","billing_id",$p->billing_id);
                $address=$this->super_model->select_column_where("participant","office_address","billing_id",$p->billing_id);
                $data['tin'][$x]=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                $tin=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                $data['company_name'][$x]=$p->company_name;
                $company_name=$p->company_name;
                $data['serial_no'][$x]=$p->serial_no;
                $serial_no=$p->serial_no;
                $data['settlement'][$x]=$this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
                $settlement=$this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("sales_transaction_head","transaction_date","sales_id",$p->sales_id);
                $transaction_date=$this->super_model->select_column_where("sales_transaction_head","transaction_date","sales_id",$p->sales_id);
                $data['billing_from'][$x]=$this->super_model->select_column_where("sales_transaction_head","billing_from","sales_id",$p->sales_id);
                $billing_from=$this->super_model->select_column_where("sales_transaction_head","billing_from","sales_id",$p->sales_id);
                $data['billing_to'][$x]=$this->super_model->select_column_where("sales_transaction_head","billing_to","sales_id",$p->sales_id);
                $billing_to=$this->super_model->select_column_where("sales_transaction_head","billing_to","sales_id",$p->sales_id);
                $data['due_date'][$x]=$this->super_model->select_column_where("sales_transaction_head","due_date","sales_id",$p->sales_id);
                $due_date=$this->super_model->select_column_where("sales_transaction_head","due_date","sales_id",$p->sales_id);
                $data['reference_number'][$x]=$this->super_model->select_column_where("sales_transaction_head","reference_number","sales_id",$p->sales_id);
                $reference_number=$this->super_model->select_column_where("sales_transaction_head","reference_number","sales_id",$p->sales_id);
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                $count_sub=$this->super_model->count_custom_where("subparticipant","participant_id='$participant_id'");
                $zero_rated= $p->zero_rated_sales + $p->zero_rated_ecozones;
                $total_amount = $p->vatable_sales + $p->zero_rated_sales + $p->zero_rated_ecozones;
                $overall_total= ($total_amount+$p->vat_on_sales) - $p->ewt;

                $data['sub_participant'][$x]=$p->billing_id;
                $data['vatable_sales'][$x]=$p->vatable_sales;
                $data['vat_on_sales'][$x]=$p->vat_on_sales;
                $data['zero_rated_sales'][$x]=$zero_rated;
                $data['total_amount'][$x]=$total_amount;
                $data['ewt'][$x]=$p->ewt;
                $data['overall_total'][$x]=$overall_total;
                $data['participant_id'][$x]=$participant_id;

                $data['sub'][]=array(
                    "participant_id"=>$participant_id,
                    "sub_participant"=>$p->billing_id,
                    "vatable_sales"=>$p->vatable_sales,
                    "zero_rated_sales"=>$zero_rated,
                    "total_amount"=>$total_amount,
                    "vat_on_sales"=>$p->vat_on_sales,
                    "ewt"=>$p->ewt,
                    "overall_total"=>$overall_total,
                );
                if($count_sub >=1 || $count_sub>=4){
                    $h=0;
                    foreach($this->super_model->select_custom_where("subparticipant","participant_id='$participant_id'") AS $s){
                        $subparticipant=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);
                        $billing_id=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);

                        $vatable_sales=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                        $zero_rated_sales=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                        $zero_rated_ecozones=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                        //$total_amount=$this->super_model->select_column_custom_where("sales_transaction_details","total_amount","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                        $vat_on_sales=$this->super_model->select_column_custom_where("sales_transaction_details","vat_on_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                        $ewt=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                        $zero_rated= $zero_rated_sales + $zero_rated_ecozones;
                        $total_amount = $vatable_sales + $zero_rated_sales + $zero_rated_ecozones;
                        $overall_total= ($total_amount + $vat_on_sales) - $ewt;
                        $data['sub_participant_sub'][$h]=$subparticipant;
                        $data['vatable_sales_sub'][$h]=$vatable_sales;
                        $data['vat_on_sales_sub'][$h]=$vat_on_sales;
                        $data['zero_rated_sales_sub'][$h]=$zero_rated;
                        $data['total_amount_sub'][$h]=$total_amount;
                        $data['ewt_s'][$h]=$ewt;
                        $data['overall_total_sub'][$h]=$overall_total;
                        $data['participant_id_sub'][$h]=$s->participant_id;
                        //if($participant_id==$s->participant_id){
                            $data['sub_part'][]=array(
                                "participant_id"=>$s->participant_id,
                                "sub_participant"=>$subparticipant,
                                "vatable_sales"=>$vatable_sales,
                                "zero_rated_sales"=>$zero_rated,
                                "zero_rated_ecozones"=>$zero_rated_ecozones,
                                "total_amount"=>$total_amount,
                                "vat_on_sales"=>$vat_on_sales,
                                "ewt"=>$ewt,
                                "overall_total"=>$overall_total,
                                //"zero_rated"=>$zero_rated,
                            );
                        //}
                        $h++;
                    }
                }


                if($count_sub>=5){
                    $total_amount = $p->vatable_sales + $p->zero_rated_sales + $p->zero_rated_ecozones;
                    $overall_total= ($total_amount+$p->vat_on_sales) - $p->ewt;

                    $data['sub_participant'][$x]=$p->billing_id;
                    $data['vatable_sales'][$x]=$p->vatable_sales;
                    $data['vat_on_sales'][$x]=$p->vat_on_sales;
                    $data['zero_rated_sales'][$x]=$zero_rated;
                    $data['total_amount'][$x]=$total_amount;
                    $data['ewt'][$x]=$p->ewt;
                    $data['overall_total'][$x]=$overall_total;
                    $data['participant_id'][$x]=$participant_id;

                    $data['sub_second'][]=array(
                        "participant_id"=>$participant_id,
                        "sub_participant"=>$p->billing_id,
                        "vatable_sales"=>$p->vatable_sales,
                        "zero_rated_sales"=>$p->zero_rated_sales,
                        "total_amount"=>$total_amount,
                        "vat_on_sales"=>$p->vat_on_sales,
                        "ewt"=>$p->ewt,
                        "overall_total"=>$overall_total,
                    );
                    $z=0;
                    foreach($this->super_model->select_custom_where("subparticipant","participant_id='$participant_id'") AS $s){

                        $subparticipant=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);
                        $billing_id=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);
                        $vatable_sales=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                        $zero_rated_sales=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                        $zero_rated_ecozones=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                        //$total_amount=$this->super_model->select_column_custom_where("sales_transaction_details","total_amount","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                        $vat_on_sales=$this->super_model->select_column_custom_where("sales_transaction_details","vat_on_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                        $ewt=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                        $zero_rated= $zero_rated_sales + $zero_rated_ecozones;
                        $total_amount = $vatable_sales + $zero_rated_sales + $zero_rated_ecozones;
                        //$zero_rated= $vat_on_sales - $ewt;
                        $overall_total= ($total_amount + $vat_on_sales) - $ewt;
                        $data['sub_participant_sub'][$z]=$subparticipant;
                        $data['vatable_sales_sub'][$z]=$vatable_sales;
                        $data['vat_on_sales_sub'][$z]=$vat_on_sales;
                        $data['zero_rated_sales_sub'][$z]=$zero_rated;
                        $data['total_amount_sub'][$z]=$total_amount;
                        $data['ewt_s'][$z]=$ewt;
                        $data['overall_total_sub'][$z]=$overall_total;
                        $data['participant_id_sub'][$z]=$s->participant_id;
                        $data['sub_part_second'][]=array(
                            "participant_id"=>$s->participant_id,
                            "sub_participant"=>$subparticipant,
                            "vatable_sales"=>$vatable_sales,
                            "zero_rated_sales"=>$zero_rated,
                            "total_amount"=>$total_amount,
                            "vat_on_sales"=>$vat_on_sales,
                            "ewt"=>$ewt,
                            "overall_total"=>$overall_total,
                            //"zero_rated"=>$zero_rated,
                        );
                        $z++;
                    }
                }
            }
        }
        $this->load->view('template/print_head');
        $this->load->view('sales/print_BS_multiple',$data);
        
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
        /*if(!empty($this->input->post('adjustment'))){
            $adjustment=$this->input->post('adjustment');
        }else{
            $adjustment=0;
        }*/
        $data=array(
            "reference_number"=>$this->input->post('reference_number'),
            "transaction_date"=>$tdate,
            "billing_from"=>$billingf,
            "billing_to"=>$billingt,
            "due_date"=>$due,
            "user_id"=>$_SESSION['user_id'],
            "create_date"=>date("Y-m-d H:i:s"),
            //"adjustment"=>$adjustment

        );
        $sales_id = $this->super_model->insert_return_id("sales_transaction_head",$data);

        echo $sales_id;
    }

    public function upload_sales_process(){
        $sales_id = $this->input->post('sales_id');
        //echo $sales_id;
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
        $y=1;
        for($x=3;$x<$highestRow;$x++){
          

          
          
            $shortname = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue());

            if($shortname!="" || !empty($shortname)){
         
            $billing_id = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue());

            $company_name =trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getOldCalculatedValue());
             $tin = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getOldCalculatedValue());
            $fac_type = trim($objPHPExcel->getActiveSheet()->getCell('F'.$x)->getFormattedValue());

             
            $wht_agent = trim($objPHPExcel->getActiveSheet()->getCell('G'.$x)->getFormattedValue());
           

            $ith = trim($objPHPExcel->getActiveSheet()->getCell('H'.$x)->getFormattedValue());
            $non_vatable = trim($objPHPExcel->getActiveSheet()->getCell('I'.$x)->getFormattedValue());
              
             $zero_rated = trim($objPHPExcel->getActiveSheet()->getCell('J'.$x)->getFormattedValue());
             
            $vatable_sales = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('K'.$x)->getFormattedValue());

         

            
            $zero_rated_sales = $objPHPExcel->getActiveSheet()->getCell('K'.$x)->getFormattedValue();


           $zero_rated_ecozone = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('L'.$x)->getFormattedValue());
          

            $vat_on_sales = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('M'.$x)->getFormattedValue());

           
            $ewt = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('N'.$x)->getFormattedValue());
          

            $total_amount = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('O'.$x)->getOldCalculatedValue());
            $series_no = $objPHPExcel->getActiveSheet()->getCell('P'.$x)->getFormattedValue();
            
  
   /*
          if($vatable_sales!=''){
                $vatable_sales_disp=$vatable_sales;
            }else{
                $vatable_sales_disp=0;
            }

            if($zero_rated_sales!=''){
                $zero_rated_sales_disp=$zero_rated_sales;
            }else{
                $zero_rated_sales_disp=0;
            }

            if($zero_rated_ecozone!=''){
                $zero_rated_ecozone_disp=$zero_rated_ecozone;
            }else{
                $zero_rated_ecozone_disp=0;
            }

            if($vat_on_sales!=''){
                $vat_on_sales_disp=$vat_on_sales;
            }else{
                $vat_on_sales_disp=0;
            }

            if($ewt!=''){
                $ewt_disp=$ewt;
            }else{
                $ewt_disp=0;
            }

            $total_amount = ($vatable_sales + $zero_rated_sales + $vat_on_sales) - $ewt;
            $total_amount = ($vatable_sales_disp + $zero_rated_ecozone_disp + $vat_on_sales_disp) - $ewt_disp;
         */
                $data_sales = array(
                    'sales_id'=>$sales_id,
                    'item_no'=>$y,
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
                    'serial_no'=>$series_no,
                    'zero_rated_ecozones'=>$zero_rated_ecozone,
                    'ewt'=>$ewt,
                    'total_amount'=>$total_amount,
                    'balance'=>$total_amount
                );
                $this->super_model->insert_into("sales_transaction_details", $data_sales);
                $y++;
                
            }
        }
            //echo $sales_id;
      
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
        $ref_no=$this->uri->segment(3);
        $participant=$this->uri->segment(4);
        $data['ref_no'] = $ref_no;
        $ref_exp=explode("-", $ref_no);
        /*$data['reference'] = $this->super_model->custom_query("SELECT DISTINCT reference_number FROM sales_transaction_head WHERE reference_number!=''");*/
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT reference_no FROM collection_details WHERE reference_no!=''");
        $data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");

        $sql="";
        if($ref_no!='null' && strpos($ref_exp[0], 'saved') === false){
           $sql.= " WHERE reference_no = '$ref_no' AND";
        }else if($ref_no!='null' && strpos($ref_exp[0], 'saved') !== false){
            $sql.= " WHERE collection_id = '$ref_exp[1]' AND";
        }else {
            $sql.= "";
        }

        $query=substr($sql,0,-3);
        //echo $query;
        $data['collection']=array();
        foreach($this->super_model->custom_query("SELECT * FROM collection_details $query") AS $col){
            $company_name=$this->super_model->select_column_where("participant","participant_name","settlement_id",$col->settlement_id);
            $count_series=$this->super_model->count_custom_where("collection_details","series_number='$col->series_number' AND series_number!='' AND settlement_id='$col->settlement_id'");
            $sum_amount= $this->super_model->select_sum_where("collection_details","amount","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id'");
            $sum_zero_rated= $this->super_model->select_sum_where("collection_details","zero_rated","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id'");
            $sum_zero_rated_ecozone= $this->super_model->select_sum_where("collection_details","zero_rated_ecozone","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id'");
            $sum_vat = $this->super_model->select_sum_where("collection_details","vat","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id'");
            $sum_ewt= $this->super_model->select_sum_where("collection_details","ewt","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id'");
            $sum_def_int = $this->super_model->select_sum_where("collection_details","defint","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id'");
            $total=($col->amount + $col->zero_rated + $col->zero_rated_ecozone + $col->vat)-$col->ewt; 
            if($count_series>=1){
                $overall_total=($sum_amount + $sum_zero_rated + $sum_zero_rated_ecozone + $sum_vat)-$sum_ewt;
            }if($count_series<=2){
                $overall_total=($sum_amount + $sum_zero_rated + $sum_zero_rated_ecozone + $sum_vat)-$sum_ewt;
            }else{
                $overall_total=($col->amount + $col->zero_rated + $col->zero_rated_ecozone + $col->vat)-$col->ewt; 
            }
            $data['collection'][]=array(
                "count_series"=>$count_series,
                "collection_details_id"=>$col->collection_details_id,
                "collection_id"=>$col->collection_id,
                "settlement_id"=>$col->settlement_id,
                "series_number"=>$col->series_number,
                "billing_remarks"=>$col->billing_remarks,
                "particulars"=>$col->particulars,
                "item_no"=>$col->item_no,
                "defint"=>$sum_def_int,
                "reference_no"=>$col->reference_no,
                "vat"=>$col->vat,
                "zero_rated"=>$col->zero_rated,
                "zero_rated_ecozone"=>$col->zero_rated_ecozone,
                "ewt"=>$col->ewt,
                "total"=>$total,
                "company_name"=>$company_name,
                "amount"=>$col->amount,
                "overall_total"=>$overall_total,
            );
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/collection_list', $data);
        $this->load->view('template/footer');
    }

    public function collected_list(){
        $ref_no=$this->uri->segment(3);
        $participant=$this->uri->segment(4);
        $data['ref_no'] = $ref_no;
        $data['participant'] = $participant;
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT reference_number FROM sales_transaction_head WHERE reference_number!=''");
        $data['participant_list']=$this->super_model->custom_query("SELECT * FROM participant GROUP BY participant_name");
        $sql="";
        if($ref_no!='null' && $participant=='null'){
           $sql.= " WHERE cd.reference_no = '$ref_no' AND";
        }else if($ref_no!='null' && $participant!='null'){
            $sql.= " WHERE cd.reference_no = '$ref_no' AND cd.settlement_id = '$participant' AND";
        }else if($ref_no=='null' && $participant!='null'){
            $sql.= " WHERE cd.settlement_id = '$participant' AND";
        }else {
            $sql.= "";
        }
        $query=substr($sql,0,-3);
        //$data['sales'] = $this->super_model->custom_query("SELECT cd.old_series_no,cd.series_number,cd.collection_id,cd.collection_details_id,ch.collection_date,cd.reference_no,cd.settlement_id,cd.amount,cd.vat,cd.zero_rated_ecozone,cd.ewt,cd.total,cd.zero_rated,std.company_name,std.short_name,std.billing_id, sh.sales_id FROM collection_details cd INNER JOIN collection_head ch ON cd.collection_id=ch.collection_id INNER JOIN sales_transaction_head sh ON cd.reference_no=sh.reference_number INNER JOIN sales_transaction_details std ON cd.settlement_id=std.short_name WHERE sh.saved='1' $query");
        $data['sales']=array();
        foreach($this->super_model->custom_query("SELECT cd.old_series_no,cd.series_number,cd.collection_id,cd.collection_details_id,ch.collection_date,cd.reference_no,cd.settlement_id,cd.amount,cd.vat,cd.zero_rated_ecozone,cd.ewt,cd.total,cd.zero_rated,cd.settlement_id FROM collection_details cd INNER JOIN collection_head ch ON cd.collection_id=ch.collection_id $query") AS $c){
            $company_name=$this->super_model->select_column_custom_where("sales_transaction_details","company_name","short_name='$c->settlement_id'");
            $billing_id=$this->super_model->select_column_custom_where("sales_transaction_details","billing_id","short_name='$c->settlement_id'");
            $data['sales'][]=array(
                "collection_id"=>$c->collection_id,
                "collection_details_id"=>$c->collection_details_id,
                "collection_date"=>$c->collection_date,
                "series_number"=>$c->series_number,
                "company_name"=>$company_name,
                //"billing_id"=>$billing_id,
                "short_name"=>$c->settlement_id,
                "amount"=>$c->amount,
                "vat"=>$c->vat,
                "zero_rated"=>$c->zero_rated,
                "zero_rated_ecozone"=>$c->zero_rated_ecozone,
                "ewt"=>$c->ewt,
                "total"=>$c->total,
            );

        }
        $data['sales_head'] = $this->super_model->select_row_where("sales_transaction_head", "reference_number", $ref_no);
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/collected_list', $data);
        $this->load->view('template/footer');
    }

    public function update_seriesno(){
        $ref_no=$this->input->post('reference_number');
        $collection_id=$this->input->post('collection_id');
        $new_series=$this->input->post('series_number');
        $old_series=$this->input->post('old_series');
        $settlement_id=$this->input->post('settlement_id');
        foreach($this->super_model->select_custom_where("collection_details","collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no'") AS $check){
            $count=$this->super_model->count_custom_where("collection_details","collection_id = '$check->collection_id' AND old_series_no!='' AND settlement_id='$settlement_id' AND reference_no='$ref_no'");
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

        if($this->super_model->update_custom_where("collection_details", $data_update, "collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no'")){
            foreach($this->super_model->select_custom_where("collection_details","collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no'") AS $latest_series){
               echo $latest_series->series_number;
            }
        }
    }

    public function save_collection(){

         if(empty($this->input->post('vat'))){
            $vat = 0;
        } else {
             $vat = $this->input->post('vat');
        }

        if(empty($this->input->post('zero_rated'))){
            $zero_rated = 0;
        } else {
             $zero_rated = $this->input->post('zero_rated');
        }

        if(empty($this->input->post('zero_rated_ecozone'))){
            $zero_rated_ecozone = 0;
        } else {
             $zero_rated_ecozone = $this->input->post('zero_rated_ecozone');
        }

        if(empty($this->input->post('ewt'))){
            $ewt = 0;
        } else {
             $ewt = $this->input->post('ewt');
        }

        $total = ($this->input->post('amount') + $vat + $zero_rated + $zero_rated_ecozone) - $ewt;
        //$total=0;

        $sales_detail_id=$this->input->post('sales_detail_id');
        $data_headc=array(
            'collection_date'=>$this->input->post('date_collected'),
            'user_id'=>$_SESSION['user_id'],
            'date_uploaded'=>date("Y-m-d H:i:s"),
        );
        $collection_id=$this->super_model->insert_return_id("collection_head", $data_headc);
        $sales_id = $this->input->post('sales_id');
        $sales_detail_id = $this->input->post('sales_detail_id');
        $reference_number=$this->super_model->select_column_where("sales_transaction_head","reference_number","sales_id",$sales_id);
        $settlement_id=$this->super_model->select_column_where("sales_transaction_details","short_name","sales_detail_id",$sales_detail_id);
        $data = array(
            'collection_id'=>$collection_id,
            'series_number'=>$this->input->post('series_number'),
            'reference_no'=>$reference_number,
            'settlement_id'=>$settlement_id,
            'amount'=>$this->input->post('amount'),
            'vat'=>$vat,
            'zero_rated'=>$zero_rated,
            'zero_rated_ecozone'=>$zero_rated_ecozone,
            'ewt'=>$ewt,
            'total'=>$total,
        );
        $collection_details_id = $this->super_model->insert_return_id("collection_details",$data);
        $balance = $this->super_model->select_column_where('sales_transaction_details', 'balance', 'sales_detail_id', $sales_detail_id);
        $new_balance = $balance - $total;
        $data_update = array(
            'balance'=>$new_balance
        );
        $this->super_model->update_where("sales_transaction_details", $data_update, "sales_detail_id", $sales_detail_id);
        echo $collection_id;
    }


    public function print_OR()
    {
        $collection_id=$this->uri->segment(3);
        $settlement_id=$this->uri->segment(4);
        $reference_no=$this->uri->segment(5);
        $data['ref_no'] = $reference_no;
        //$reference_no = $this->super_model->select_column_where("collection_details", "reference_no", "collection_id", $collection_id);
        //$settlement_id = $this->super_model->select_column_where("collection_details", "settlement_id", "collection_id", $collection_id);
        $billing_id = $this->super_model->select_column_where("sales_transaction_details", "billing_id", "short_name", $settlement_id);
        
        $data['client']=$this->super_model->select_row_where("participant", "billing_id", $billing_id);
        $data['sum_amount']=$this->super_model->select_sum_where("collection_details","amount","settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        /*$data['amount'] =  $this->super_model->select_column_custom_where("collection_details", "amount", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['vat'] =  $this->super_model->select_column_custom_where("collection_details", "vat", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");*/
        $data['sum_vat']=$this->super_model->select_sum_where("collection_details","vat","settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['sum_ewt'] =  $this->super_model->select_sum_where("collection_details", "ewt", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['sum_zero_rated'] =  $this->super_model->select_sum_where("collection_details", "zero_rated", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['sum_zero_rated_ecozone'] =  $this->super_model->select_sum_where("collection_details", "zero_rated_ecozone", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['defint'] =  $this->super_model->select_sum_where("collection_details", "defint", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['date'] = $this->super_model->select_column_where("collection_head", "collection_date", "collection_id", $collection_id);
        //$data['ref_no'] = $this->super_model->select_column_where("collection_details", "reference_no", "collection_id", $collection_id);
        $this->load->view('template/print_head');
        $this->load->view('sales/print_OR',$data);
    }

    public function print_collected_OR()
    {
        $collection_details_id=$this->uri->segment(3);
        $collection_id = $this->super_model->select_column_where("collection_details", "collection_id", "collection_details_id", $collection_details_id);
        $reference_no = $this->super_model->select_column_where("collection_details", "reference_no", "collection_id", $collection_id);
        $settlement_id = $this->super_model->select_column_where("collection_details", "settlement_id", "collection_id", $collection_id);
        $billing_id = $this->super_model->select_column_where("sales_transaction_details", "billing_id", "short_name", $settlement_id);
        foreach($this->super_model->select_row_where("participant", "billing_id", $billing_id) AS $p){
            $data['client'][] = array(
                "client_name"=>$p->participant_name,
                "address"=>$p->registered_address,
                "tin"=>$p->tin
            );
        }
        $data['amount'] =  $this->super_model->select_column_where("collection_details", "amount", "collection_details_id", $collection_details_id);
        $data['vat'] =  $this->super_model->select_column_where("collection_details", "vat", "collection_details_id", $collection_details_id);
        $data['collection'] = $this->super_model->select_row_where("collection_details","collection_details_id",$collection_details_id);

        $data['date'] = $this->super_model->select_column_where("collection_head", "collection_date", "collection_id", $collection_id);
        $data['ref_no'] = $this->super_model->select_column_where("sales_transaction_head", "reference_number", "reference_number", $reference_no);
        $this->load->view('template/print_head');
        $this->load->view('sales/print_collected_OR',$data);
    }

    public function sales_wesm(){
        $ref_no=$this->uri->segment(3);
        $data['ref_no']=$ref_no;
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT reference_number FROM sales_transaction_head WHERE reference_number!=''");
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details sd INNER JOIN sales_transaction_head sh ON sd.sales_id=sh.sales_id WHERE saved='1' AND reference_number LIKE '%$ref_no%'") AS $d){
            $series_number=$this->super_model->select_column_custom_where("collection_details","series_number","reference_no='$d->reference_number' AND settlement_id='$d->short_name'");
            $old_series_no=$this->super_model->select_column_custom_where("collection_details","old_series_no","reference_no='$d->reference_number' AND settlement_id='$d->short_name'");
            $data['details'][]=array(
                'sales_detail_id'=>$d->sales_detail_id,
                'sales_id'=>$d->sales_id,
                'item_no'=>$d->item_no,
                'series_number'=>$series_number,
                'old_series_no_col'=>$old_series_no,
                'old_series_no'=>$d->old_series_no,
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

    public function update_BSeriesno(){
        $ref_no=$this->input->post('ref_no');
        $sales_detail_id=$this->input->post('sales_detail_id');
        $new_series=$this->input->post('series_number');
        $old_series=$this->input->post('old_series_no');
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details WHERE sales_detail_id='$sales_detail_id'") AS $check){
            $count=$this->super_model->count_custom_where("sales_transaction_details","sales_detail_id = '$check->sales_detail_id' AND old_series_no!=''");
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

        $this->super_model->update_custom_where("sales_transaction_details", $data_update, "sales_detail_id='$sales_detail_id'");
        echo $ref_no;
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

    public function convertNumber(float $amount){
        $decones = array( 
                    01 => "One", 
                    02 => "Two", 
                    03 => "Three", 
                    04 => "Four", 
                    05 => "Five", 
                    06 => "Six", 
                    07 => "Seven", 
                    08 => "Eight", 
                    09 => "Nine", 
                    10 => "Ten", 
                    11 => "Eleven", 
                    12 => "Twelve", 
                    13 => "Thirteen", 
                    14 => "Fourteen", 
                    15 => "Fifteen", 
                    16 => "Sixteen", 
                    17 => "Seventeen", 
                    18 => "Eighteen", 
                    19 => "Nineteen" 
                    );
        $ones = array( 
                    0 => "Zero",
                    1 => "One",     
                    2 => "Two", 
                    3 => "Three", 
                    4 => "Four", 
                    5 => "Five", 
                    6 => "Six", 
                    7 => "Seven", 
                    8 => "Eight", 
                    9 => "Nine", 
                    10 => "Ten", 
                    11 => "Eleven", 
                    12 => "Twelve", 
                    13 => "Thirteen", 
                    14 => "Fourteen", 
                    15 => "Fifteen", 
                    16 => "Sixteen", 
                    17 => "Seventeen", 
                    18 => "Eighteen", 
                    19 => "Nineteen" 
                    ); 
        $tens = array( 
                    0 => "",
                    2 => "Twenty", 
                    3 => "Thirty", 
                    4 => "Forty", 
                    5 => "Fifty", 
                    6 => "Sixty", 
                    7 => "Seventy", 
                    8 => "Eighty", 
                    9 => "Ninety" 
                    ); 
        $hundreds = array( 
                    "Hundred", 
                    "Thousand", 
                    "Million", 
                    "Billion", 
                    "Trillion", 
                    "Quadrillion" 
                    ); //limit t quadrillion 
        $amount = number_format($amount,2,".",","); 
        $num_arr = explode(".",$amount); 
        $wholenum = $num_arr[0]; 
        $decnum = $num_arr[1]; 
        $whole_arr = array_reverse(explode(",",$wholenum)); 
        krsort($whole_arr); 
        $rettxt = ""; 
        foreach($whole_arr as $key => $i){ 

            if($i < 20){ 
                $rettxt .= $ones[$i]; 
            }
            elseif($i < 100){ 
                if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
                if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 
            }
            else{ 
                //$rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]." ".$ones[substr($i,1)]; 
                if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0];
                if(substr($i,1)!="0")$rettxt .= " ".$ones[substr($i,1)];
                if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)];
                if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 

               /* $r = substr($i,0,1);
                    echo $r;*/
                  
            } 
            if($key > 0 && $i > 0){ 
                $rettxt .= " ".$hundreds[$key]." "; 
            } 

        }
        if($decnum > 0 && $i > 1){ 
        $rettxt = $rettxt." pesos";
        }elseif($decnum > 0 && $i == 1){
        $rettxt = $rettxt." peso";
        }elseif($decnum == 0 && $i != 1){
        $rettxt = $rettxt." pesos only";
        }elseif($decnum == 0 && $i == 1){
        $rettxt = $rettxt." peso only";
    }

        if($decnum > 0 && $i != 1){
            $rettxt .= " and ";
            if($decnum < 20){ 
                $rettxt .= $decones[$decnum]; 
            }
            elseif($decnum < 100){ 
                $rettxt .= $tens[substr($decnum,0,1)]; 
                $rettxt .= " ".$ones[substr($decnum,1,1)]; 
            }
                $rettxt = $rettxt." centavos only"; 
        }elseif($decnum > 0 && $i == 0){
            if($decnum < 20){ 
                $rettxt .= $decones[$decnum]; 
            }
            elseif($decnum < 100){ 
                $rettxt .= $tens[substr($decnum,0,1)]; 
                $rettxt .= " ".$ones[substr($decnum,1,1)]; 
            }
                $rettxt = $rettxt." centavos only";
        }


        return $rettxt;
        }

    public function print_BS(){
        $sales_detail_id = $this->uri->segment(3);
        $data['sales_detail_id']=$sales_detail_id;
        $data['address']='';
        $data['tin']='';
        $data['company_name']='';
        $data['settlement']='';
        $data['billing_from']='';
        $data['billing_to']='';
        $data['due_date']='';
        $data['reference_number']='';
        foreach($this->super_model->select_row_where("sales_transaction_details","sales_detail_id",$sales_detail_id) AS $p){
            $data['address']=$this->super_model->select_column_where("participant","office_address","billing_id",$p->billing_id);
            $data['tin']=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
            $data['company_name']=$p->company_name;
            $data['serial_no']=$p->serial_no;
            $data['settlement']=$this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
            $data['transaction_date']=$this->super_model->select_column_where("sales_transaction_head","transaction_date","sales_id",$p->sales_id);
            $data['billing_from']=$this->super_model->select_column_where("sales_transaction_head","billing_from","sales_id",$p->sales_id);
            $data['billing_to']=$this->super_model->select_column_where("sales_transaction_head","billing_to","sales_id",$p->sales_id);
            $data['due_date']=$this->super_model->select_column_where("sales_transaction_head","due_date","sales_id",$p->sales_id);
            $data['reference_number']=$this->super_model->select_column_where("sales_transaction_head","reference_number","sales_id",$p->sales_id);
            $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
            $count_sub=$this->super_model->count_custom_where("subparticipant","participant_id='$participant_id'");
            $zero_rated= $p->zero_rated_sales + $p->zero_rated_ecozones;
            $total_amount = $p->vatable_sales + $p->zero_rated_sales + $p->zero_rated_ecozones;
            $data['sub'][]=array(
                "sub_participant"=>$p->billing_id,
                "vatable_sales"=>$p->vatable_sales,
                "zero_rated_sales"=>$zero_rated,
                "total_amount"=>$total_amount,
                "vat_on_sales"=>$p->vat_on_sales,
                "ewt"=>$p->ewt,
                //"zero_rated"=>$zero_rated,
            );
            if($count_sub >=1 || $count_sub>=4){
                foreach($this->super_model->select_custom_where("subparticipant","participant_id='$participant_id'") AS $s){
                    $subparticipant=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);
                    $billing_id=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);

                    $vatable_sales=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                    $zero_rated_sales=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                    $zero_rated_ecozones=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                    //$total_amount=$this->super_model->select_column_custom_where("sales_transaction_details","total_amount","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                    $vat_on_sales=$this->super_model->select_column_custom_where("sales_transaction_details","vat_on_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                    $ewt=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                   $zero_rated= $zero_rated_sales + $zero_rated_ecozones;
                    $total_amount = $vatable_sales + $zero_rated_sales + $zero_rated_ecozones;
                    $data['sub'][]=array(
                        "sub_participant"=>$subparticipant,
                        "vatable_sales"=>$vatable_sales,
                        "zero_rated_sales"=>$zero_rated,
                        "zero_rated_ecozones"=>$zero_rated_ecozones,
                        "total_amount"=>$total_amount,
                        "vat_on_sales"=>$vat_on_sales,
                        "ewt"=>$ewt,
                        //"zero_rated"=>$zero_rated,
                    );
                }
            }

            if($count_sub>=5){
                $total_amount = $p->vatable_sales + $p->zero_rated_sales + $p->zero_rated_ecozones;
                $data['sub_second'][]=array(
                    "sub_participant"=>$p->billing_id,
                    "vatable_sales"=>$p->vatable_sales,
                    "zero_rated_sales"=>$p->zero_rated_sales,
                    "total_amount"=>$total_amount,
                    "vat_on_sales"=>$p->vat_on_sales,
                    "ewt"=>$p->ewt,
                );
                foreach($this->super_model->select_custom_where("subparticipant","participant_id='$participant_id'") AS $s){
                    $subparticipant=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);
                    $billing_id=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);
                    $vatable_sales=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                    $zero_rated_sales=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                    $zero_rated_ecozones=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                    //$total_amount=$this->super_model->select_column_custom_where("sales_transaction_details","total_amount","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                    $vat_on_sales=$this->super_model->select_column_custom_where("sales_transaction_details","vat_on_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                    $ewt=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                       $total_amount = $vatable_sales + $zero_rated_sales + $zero_rated_ecozones;
                    //$zero_rated= $vat_on_sales - $ewt;
                       $zero_rated= $zero_rated_sales + $zero_rated_ecozones;
                    $data['sub_second'][]=array(
                        "sub_participant"=>$subparticipant,
                        "vatable_sales"=>$vatable_sales,
                        "zero_rated_sales"=>$zero_rated,
                        "total_amount"=>$total_amount,
                        "vat_on_sales"=>$vat_on_sales,
                        "ewt"=>$ewt,
                        //"zero_rated"=>$zero_rated,
                    );
                }
            }
        }
        $this->load->view('template/print_head');
        $this->load->view('sales/print_BS',$data);
        
    }

    public function print_invoice(){
        error_reporting(0);
        $sales_detail_id = $this->uri->segment(3);
        // $this->load->view('template/header');
        //$this->load->view('template/navbar');
        foreach($this->super_model->select_row_where("sales_transaction_details","sales_detail_id",$sales_detail_id) AS $p){
            $data['address']=$this->super_model->select_column_where("participant","office_address","billing_id",$p->billing_id);
            $data['tin']=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
            $data['company_name']=$p->company_name;
            $data['billing_from']=$this->super_model->select_column_where("sales_transaction_head","billing_from","sales_id",$p->sales_id);
            $data['billing_to']=$this->super_model->select_column_where("sales_transaction_head","billing_to","sales_id",$p->sales_id);

            $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
            foreach($this->super_model->select_custom_where("subparticipant","participant_id='$participant_id'") AS $s){
                $billing_id=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);


                $vatable_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vat_on_sales","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                $ewt_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","billing_id='$billing_id' AND sales_id='$p->sales_id'");

              
                $zero_rated_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                $sum_vatable_sales=array_sum($vatable_sales_bs);
                $sum_vs_exp = explode(".", $sum_vatable_sales);
                $sum_vatable_sales_peso=$sum_vs_exp[0];
                $sum_vatable_sales_cents=$sum_vs_exp[1];

                $sum_zero_rated_ecozone=array_sum($zero_rated_ecozone_bs);
                $sum_zre_exp=explode(".", $sum_zero_rated_ecozone);
                $sum_zero_rated_ecozone_peso=$sum_zre_exp[0];
                $sum_zero_rated_ecozone_cents=$sum_zre_exp[1];

                $sum_vat_on_sales=array_sum($vat_on_sales_bs);
                $sum_vos_exp=explode(".", $sum_vat_on_sales);
                $sum_vat_on_sales_peso=$sum_vos_exp[0];
                $sum_vat_on_sales_cents=$sum_vos_exp[1];

                $sum_ewt=array_sum($ewt_bs);
                $sum_e_exp=explode(".", $sum_ewt);
                $sum_ewt_peso=$sum_e_exp[0];
                $sum_ewt_cents=$sum_e_exp[1];

                $sum_zero_rated=array_sum($zero_rated_bs);
                $sum_zr_exp=explode(".", $sum_zero_rated);
                $sum_zero_rated_peso=$sum_zr_exp[0];
                $sum_zero_rated_cents=$sum_zr_exp[1];
            }

                //$ewt=str_replace("-", '', $p->ewt);
                
                $total_vs=$p->vatable_sales + $sum_vatable_sales;
                $vatable_sales = explode(".",$total_vs);
                $data['vat_sales_peso'] = $vatable_sales[0];
                $data['vat_sales_cents'] = $vatable_sales[1];

                $total_zr=$p->zero_rated_sales + $sum_zero_rated;
                $zero_rated_sales = explode(".",$total_zr);
                $data['zero_rated_peso'] = $zero_rated_sales[0];
                $data['zero_rated_cents'] = $zero_rated_sales[1];

                $total_vos=$p->vat_on_sales + $sum_vat_on_sales;
                $vat_on_sales = explode(".",$total_vos);
                $data['vat_peso'] = $vat_on_sales[0];
                $data['vat_cents'] = $vat_on_sales[1];

                $total_ewt=$p->ewt + $sum_ewt;
                $ewt_exp=explode(".", $total_ewt);
                $data['ewt_peso']=$ewt_exp[0];
                $data['ewt_cents']=$ewt_exp[1];

                $total_zra=$p->zero_rated_ecozones + $sum_zero_rated_ecozone;
                $zero_rated_ecozones_exp=explode(".", $total_zra);
                $data['zero_rated_ecozones_peso']=$zero_rated_ecozones_exp[0];
                $data['zero_rated_ecozones_cents']=$zero_rated_ecozones_exp[1];

                $total= ($p->vatable_sales + $p->vat_on_sales + $p->zero_rated_ecozones + $p->zero_rated_sales) - $p->ewt;
                $total_sub= ($sum_vatable_sales + $sum_vat_on_sales + $sum_zero_rated_ecozone + $sum_zero_rated) - $sum_ewt;
                $total_amount=$total + $total_sub;
                $data['total_amount']=$total_amount;
                $data['amount_words']=strtoupper($this->convertNumber($total_amount));
                $total_exp=explode(".", $total_amount);
                $data['total_peso']=$total_exp[0];
                $data['total_cents']=$total_exp[1];
           
        }
        $this->load->view('template/print_head');
        $this->load->view('sales/print_invoice',$data);
    }

    public function add_details_OR()
    {
        $sales_id = $this->uri->segment(3);
        $sales_detail_id = $this->uri->segment(4);
        $data['sales_id']=$sales_id;
        $data['sales_detail_id']=$sales_detail_id;

        $data['amount_due'] = $this->super_model->select_column_where("sales_transaction_details", "balance", "sales_detail_id", $sales_detail_id);
        $this->load->view('template/header');
        $this->load->view('sales/add_details_OR',$data);
        $this->load->view('template/footer');
    }

    public function add_details_wesm()
    {
        $this->load->view('template/header');
        $this->load->view('sales/add_details_wesm');
        $this->load->view('template/footer');
    }

    public function upload_bulk_collection(){
        $coldate=$this->input->post('col_date');
        $dest= realpath(APPPATH . '../uploads/excel/');
        $error_ext=0;
        if(!empty($_FILES['doc']['name'])){

            

            $exc= basename($_FILES['doc']['name']);
            $exc=explode('.',$exc);
            $ext1=$exc[1];
          
            
            if($ext1=='php' || $ext1!='xlsm'){
                $error_ext++;

            } 
            else {
                $filename1='bulkcollection.'.$ext1;
              
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                     $this->readBulkCollection($coldate);
                } 
            }
        }
    }

    public function readBulkCollection($coldate){



        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();

        $inputFileName =realpath(APPPATH.'../uploads/excel/bulkcollection.xlsm');

       

       try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        
   
            $objPHPExcel = $objReader->load($inputFileName);
        } 

        catch(Exception $e) {
            die('Error loading file"'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
         
        $objPHPExcel->setActiveSheetIndex(1);

       
        $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 
      
        $data_head=array(
            "collection_date"=>$coldate,
            "user_id"=>$_SESSION['user_id'],
            "date_uploaded"=>date('Y-m-d H:i:s')
        );
        $collection_id = $this->super_model->insert_return_id("collection_head", $data_head);
        $a=1;
       for($x=7;$x<=$highestRow;$x++){

           
                $no = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue());
            
            if($no!='' ){

               
                  /* if($a==1){*/
                    $itemno = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue());
              /*   } else {
                     $itemno = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getOldCalculatedValue());
                     }*/
               
                $remarks = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue());
                $particulars = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue());
                $stl_id = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getFormattedValue());
                $buyer = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue());
                $statement_no = trim($objPHPExcel->getActiveSheet()->getCell('F'.$x)->getFormattedValue());

                $vatable_sales = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('G'.$x)->getFormattedValue());
               
                $zero_rated = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('H'.$x)->getFormattedValue());
                $zero_rated_ecozone = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('I'.$x)->getFormattedValue());
                $vat = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('J'.$x)->getFormattedValue());
                $ewt = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('K'.$x)->getFormattedValue());
                $total = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('L'.$x)->getFormattedValue());
                $defint = trim($objPHPExcel->getActiveSheet()->getCell('N'.$x)->getFormattedValue());
                $series = trim($objPHPExcel->getActiveSheet()->getCell('O'.$x)->getFormattedValue());
             
                 $data_details = array(
                        'collection_id'=>$collection_id,
                        'item_no'=>$itemno,
                        'billing_remarks'=>$remarks,
                        'particulars'=>$particulars,
                        'series_number'=>$series,
                        'defint'=>$defint,
                        'reference_no'=>$statement_no,
                        'settlement_id'=>$stl_id,
                        'amount'=>$vatable_sales,
                        'vat'=>$vat,
                        'zero_rated'=>$zero_rated,
                        'zero_rated_ecozone'=>$zero_rated_ecozone,
                        'ewt'=>$ewt,
                        'total'=>$total,
                    );
                    $this->super_model->insert_into("collection_details", $data_details);
              
            } 

            $a++;
        }

       
        echo "saved-".$collection_id;

           
      
              /*  $data_sales = array(
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
                    'balance'=>$total_amount
                );
                $this->super_model->insert_into("sales_transaction_details", $data_sales);*/
        
            //echo $sales_id;
      
    }
    public function sample_table(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/sample_table');
        $this->load->view('template/footer');
    }

    public function save_all_adjust(){
        $adjust_identifier = $this->input->post('adjust_identifier');
        $data_head = array(
            'saved'=>1,
        );
        $this->super_model->update_where("sales_adjustment_head",$data_head, "adjust_identifier", $adjust_identifier);
        echo $adjust_identifier;
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

    public function upload_sales_adjustment(){
        $identifier_code=$this->generateRandomString();
        $data['identifier_code']=$identifier_code;
        $data['identifier']=$this->uri->segment(3);
        $identifier=$this->uri->segment(3);
        $data['saved']=$this->super_model->select_column_where("sales_adjustment_head","saved","adjust_identifier",$identifier);
        $data['head']=$this->super_model->select_row_where("sales_adjustment_head","adjust_identifier",$identifier);
        //$ref_no=$this->super_model->select_column_where("sales_adjustment_head","reference_number", "adjust_identifier" ,$identifier);
            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id=sah.sales_adjustment_id WHERE adjust_identifier='$identifier'") AS $d){
                    $data['details'][]=array(
                        // 'transaction_date'=>$h->transaction_date,
                        // 'billing_from'=>$h->billing_from,
                        // 'billing_to'=>$h->billing_to,
                        // 'reference_number'=>$h->reference_number,
                        // 'due_date'=>$h->due_date,
                        // 'saved'=>$h->saved,
                        'adjustment_detail_id'=>$d->adjustment_detail_id,
                        'sales_adjustment_id'=>$d->sales_adjustment_id,
                        'item_no'=>$d->item_no,
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
                        'print_counter'=>$d->print_counter,
                        'reference_number'=>$d->reference_number
                    );
                }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/upload_sales_adjustment',$data);
        $this->load->view('template/footer');
    }

        public function upload_sales_adjust(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $count = $this->input->post('count');
        $adjust_identifier = $this->input->post('adjust_identifier');
        for($x=0;$x<$count;$x++){
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
                    $filename1='wesm_sales_adjust'.$x.".".$ext1;
                    if(move_uploaded_file($_FILES["fileupload"]['tmp_name'][$x], $dest.'/'.$filename1)){
                        //for($a=0;$a<$count;$a++){
                            $inputFileName =realpath(APPPATH.'../uploads/excel/wesm_sales_adjust'.$x.'.xlsx');
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
                                "create_date"=>date("Y-m-d H:i:s"),
                                'adjust_identifier'=>$adjust_identifier,
                                'remarks'=>$remarks,
                            );
                            $this->super_model->insert_into("sales_adjustment_head", $data_insert);

                            $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 
                            $highestRow = $highestRow-1;
                            $y=1;
                            for($z=4;$z<$highestRow;$z++){
                                
                                $itemno = trim($objPHPExcel->getActiveSheet()->getCell('A'.$z)->getOldCalculatedValue());
                                $shortname = trim($objPHPExcel->getActiveSheet()->getCell('B'.$z)->getFormattedValue());
                                if($shortname!="" || !empty($shortname)){
                                $billing_id = trim($objPHPExcel->getActiveSheet()->getCell('C'.$z)->getFormattedValue());   
                                $company_name =trim($objPHPExcel->getActiveSheet()->getCell('D'.$z)->getFormattedValue());
                                $tin = trim($objPHPExcel->getActiveSheet()->getCell('E'.$z)->getFormattedValue());
                                $fac_type = trim($objPHPExcel->getActiveSheet()->getCell('F'.$z)->getFormattedValue());
                                $wht_agent = trim($objPHPExcel->getActiveSheet()->getCell('G'.$z)->getFormattedValue());
                                $ith = trim($objPHPExcel->getActiveSheet()->getCell('H'.$z)->getFormattedValue());
                                $non_vatable = trim($objPHPExcel->getActiveSheet()->getCell('I'.$z)->getFormattedValue());
                                $zero_rated = trim($objPHPExcel->getActiveSheet()->getCell('J'.$z)->getFormattedValue());
                                $vatable_sales = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('K'.$z)->getFormattedValue());
                                $zero_rated_sales = trim($objPHPExcel->getActiveSheet()->getCell('K'.$z)->getFormattedValue(),'()');
                                $zero_rated_sales = trim($zero_rated_sales,"-");
                                $zero_rated_ecozone = trim($objPHPExcel->getActiveSheet()->getCell('L'.$z)->getFormattedValue(),'()');
                                $zero_rated_ecozone = trim($zero_rated_ecozone,"-");
                                $vat_on_sales = trim($objPHPExcel->getActiveSheet()->getCell('M'.$z)->getFormattedValue(),'()');
                                $vat_on_sales = trim($vat_on_sales,"-");
                                $ewt = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('N'.$z)->getFormattedValue());
                                $total_amount = trim($objPHPExcel->getActiveSheet()->getCell('O'.$z)->getOldCalculatedValue(),'()');
                                $total_amount = trim($total_amount,"-");

                                $count_max=$this->super_model->count_rows("sales_adjustment_head");
                                if($count_max==0){
                                    $sales_adjustment_id=1;
                                }else{
                                    $sales_adjustment_id = $this->super_model->get_max("sales_adjustment_head", "sales_adjustment_id");
                                }
                                $data_sales = array(
                                    'sales_adjustment_id'=>$sales_adjustment_id,
                                    'item_no'=>$y,
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
                                    'zero_rated_ecozones'=>$zero_rated_ecozone,
                                    'ewt'=>$ewt,
                                    'total_amount'=>$total_amount,
                                    'balance'=>$total_amount,
                                );
                                $this->super_model->insert_into("sales_adjustment_details", $data_sales);
                                $y++;
                            }
                        }
                    } 
                }
            }
        }
    echo $adjust_identifier;
}



    
}