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
class Reports extends CI_Controller {

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

    public function dateDifference($date_1 , $date_2)
    {
        $datetime2 = date_create($date_2);
        $datetime1 = date_create($date_1 );
        $interval = date_diff($datetime2, $datetime1);
       
        return $interval->format('%R%a');
       
    }

    public function sales_summary()
    {
        $ref_no=$this->uri->segment(3);
        $participant=$this->uri->segment(4);
        $from=$this->uri->segment(5);
        $to=$this->uri->segment(6);
        $data['from'] = $from;
        $data['to'] = $to;
        $data['ref_no'] = $ref_no;
        //$data['part'] = $participant;
        $part=$this->super_model->select_column_where("participant","participant_name","settlement_id",$participant);
        $data['part'] = $part;
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['reference_no']=$this->super_model->custom_query("SELECT DISTINCT reference_number FROM sales_transaction_head WHERE reference_number!=''");
        //$data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant GROUP BY settlement_id");
        $sql="";
       /* $sales=array();*/

        if($from!='null' && $to != 'null'){
            $sql.= "billing_from >= '$from' AND billing_to <= '$to' AND ";
        } if($participant!='null'){
             $sql.= "short_name = '$participant' AND "; 
        } if($ref_no!='null'){
            $sql.= "reference_number = '$ref_no' AND ";
        }

        //echo $sql;
     
        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND ".$query;
        $total_am = $this->super_model->select_sum_join("total_amount","sales_transaction_details","sales_transaction_head", $qu,"sales_id");
        $data['total_amount'] = $total_am;
        $data['total_collection']=0;
        $data['total_balance']=0;
        $total_col=array();
        $ref_no =array();

     /*   echo "SELECT DISTINCT(reference_number) as refno FROM sales_transaction_head sh INNER JOIN sales_transaction_details sd ON sh.sales_id = sd.sales_id WHERE $qu GROUP BY reference_number";
        foreach($this->super_model->custom_query("SELECT DISTINCT(reference_number) as refno FROM sales_transaction_head sh INNER JOIN sales_transaction_details sd ON sh.sales_id = sd.sales_id WHERE $qu GROUP BY reference_number") AS $sl){
            
        }*/

        foreach($this->super_model->select_innerjoin_where("sales_transaction_details","sales_transaction_head", $qu,"sales_id","reference_number") AS $sales){

            $ref_no[] = $sales->reference_number;
            $count_collection = $this->super_model->count_custom_where("collection_details", "reference_no='$sales->reference_number' AND settlement_id ='$sales->short_name'");

            /*ECHO "reference_no='$sales->reference_number' AND settlement_id ='$sales->short_name'";*/
           
            $tin=$this->super_model->select_column_where("participant","tin","billing_id",$sales->billing_id);
            $registered_address=$this->super_model->select_column_where("participant","registered_address","billing_id",$sales->billing_id);

            //$company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$sales->billing_id);

            $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $sales->sales_id);
            $company_name=$this->super_model->select_column_where("sales_transaction_details", "company_name", "sales_detail_id", $sales->sales_detail_id);
            if(!empty($company_name) && date('Y',strtotime($create_date))==date('Y')){
                $comp_name=$company_name;
            }else{
                $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $sales->billing_id);
            }
            /*$amount=$this->super_model->select_column_where("collection_details","amount","settlement_id",$sales->short_name);
            $total_col[] = $amount;*/
            if($count_collection>0){

         
                foreach($this->super_model->select_custom_where("collection_details", "reference_no='$sales->reference_number' AND settlement_id ='$sales->short_name'") AS $col){

                    //if($col->reference_no==$sales->reference_number && $col->settlement_id ==$sales->short_name ){
                     //$total_col[] = $col->amount;
                     //echo $col->collection_details_id . "<br>"
   
                     $data['sales'][] = array( 
                        //'collection_details_id'=>$col->collection_details_id,
                        'transaction_date'=>$sales->transaction_date,
                        'tin'=>$tin,
                        'participant_name'=>$comp_name,
                        'address'=>$registered_address,
                        //'vatable_sales'=>$this->super_model->select_column_custom_where("collection_details","amount","reference_no='$sales->reference_number' AND settlement_id ='$sales->short_name'"),
                        'vatable_sales'=>$col->amount,
                        'zero_rated_sales'=>$col->zero_rated,
                        'vat_on_sales'=>$col->vat,
                        'billing_from'=>$sales->billing_from,
                        'billing_to'=>$sales->billing_to,
                        'ewt'=>$col->ewt,
                    );
                 //}
                    //$total_c = array_sum($total_col);
                    //$data['total_collection'] = $total_c;
                    //$data['total_balance'] = $total_am - $total_c;
                }

        } 
        
      
           //$data['sales']= array_map("unserialize", array_unique(array_map("serialize", $sales)));
    }

        $ref_no = array_unique($ref_no);
        $sq = "";
        foreach($ref_no AS $rn){
            $sq .= " reference_no = '". $rn . "' OR ";
        }

        $sq = substr($sq, 0, -3);

        $total_c = ($sq!='') ? $this->super_model->select_sum_where("collection_details", "amount", $sq) : 0;
        $data['total_collection'] = $total_c;
       /* $total_c = array_sum($total_col);*/
       // $data['total_collection'] = $total_c;
        $data['total_balance'] = $total_am - $total_c;

        $this->load->view('reports/sales_summary',$data);
        $this->load->view('template/footer');
    }

    public function purchases_summary()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $ref_no=$this->uri->segment(3);
        $participant=$this->uri->segment(4);
        $from=$this->uri->segment(5);
        $to=$this->uri->segment(6);
        $data['from'] = $from;
        $data['to'] = $to;
        $data['ref_no'] = $ref_no;
        $part=$this->super_model->select_column_where("participant","participant_name","settlement_id",$participant);
        $data['part'] = $part;
        $this->load->view('template/header');
        $data['reference_no']=$this->super_model->custom_query("SELECT DISTINCT reference_number FROM purchase_transaction_head WHERE reference_number!=''");
        //$data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant GROUP BY settlement_id");
        $sql="";

        if($from!='null' && $to != 'null'){
            $sql.= "billing_from >= '$from' AND billing_to <= '$to' AND ";
        } if($participant!='null'){
             $sql.= "short_name = '$participant' AND "; 
        } if($ref_no!='null'){
            $sql.= "reference_number = '$ref_no' AND ";
        }

        $query=substr($sql,0,-4);
        $pur = "saved = '1' AND ".$query;
        $total_am = $this->super_model->select_sum_join("total_amount","purchase_transaction_details","purchase_transaction_head", $pur,"purchase_id");
        $data['total_amount'] = $total_am;
        $data['total_paid']=0;
        $data['total_balance']=0;
        $total_pay=array();
        $data['head']=array();
        foreach($this->super_model->select_innerjoin_where("purchase_transaction_details","purchase_transaction_head", $pur,"purchase_id","purchase_detail_id") AS $purchase){
            $tin=$this->super_model->select_column_where("participant","tin","billing_id",$purchase->billing_id);
            $registered_address=$this->super_model->select_column_where("participant","registered_address","billing_id",$purchase->billing_id);
            $create_date = $this->super_model->select_column_where("purchase_transaction_head", "create_date", "purchase_id", $purchase->purchase_id);
            $company_name=$this->super_model->select_column_where("purchase_transaction_details", "company_name", "purchase_detail_id", $purchase->purchase_detail_id);
            if(!empty($company_name) && date('Y',strtotime($create_date))==date('Y')){
                $comp_name=$company_name;
            }else{
                $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $purchase->billing_id);
            }
            //$company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$purchase->billing_id);
            /*$total_amount=$this->super_model->select_column_where("payment_details","total_amount","purchase_details_id",$purchase->purchase_detail_id);
            $total_pay[] = $total_amount;*/
            /*foreach($this->super_model->custom_query("SELECT * FROM payment_head ph INNER JOIN payment_details pd ON ph.payment_id=pd.payment_id AND ph.purchase_id='$head->purchase_id' AND pd.purchase_details_id='$head->purchase_detail_id'") AS $c){*/

            $count_payment = $this->super_model->count_custom_where("payment_details", "purchase_details_id ='$purchase->purchase_detail_id'");
            if($count_payment>0){

            foreach($this->super_model->select_custom_where("payment_details", "purchase_details_id ='$purchase->purchase_detail_id'") AS $c){

            $total_pay[] = $c->total_amount;

            if($c->purchase_mode=='Vatable Purchase'){
                $vatable_purchases=$c->purchase_amount;
                $zero_rated='0.00';
                $rated_ecozones='0.00';
            }else if($c->purchase_mode=='Zero Rated Purchase'){
                $vatable_purchases='0.00';
                $zero_rated=$c->purchase_amount;
                $rated_ecozones='0.00';
            }else if($c->purchase_mode=='Zero Rated Ecozones'){
                $vatable_purchases='0.00';
                $zero_rated='0.00';
                $rated_ecozones=$c->purchase_amount;
            }

            $data['purchases'][] = array( 
            'transaction_date'=>$purchase->transaction_date,
            'tin'=>$tin,
            'participant_name'=>$comp_name,
            'address'=>$registered_address,
            'vatable_purchases'=>$vatable_purchases,
            'zero_rated_purchases'=>$zero_rated,
            'zero_rated_ecozones'=>$rated_ecozones,
            'wht_agent'=>$purchase->wht_agent,
            'vat_on_purchases'=>$c->vat,
            'billing_from'=>$purchase->billing_from,
            'billing_to'=>$purchase->billing_to,
            'ewt'=>$c->ewt,
                );
            
            }

        /*} else {
            $total_pay[] = $purchase->total_amount;

            $data['purchases'][] = array( 
            'transaction_date'=>$purchase->transaction_date,
            'tin'=>$tin,
            'participant_name'=>$company_name,
            'address'=>$registered_address,
            'vatable_purchases'=>$purchase->vatables_purchases,
            'zero_rated_purchases'=>$purchase->zero_rated_purchases,
            'zero_rated_ecozones'=>$purchase->zero_rated_ecozones,
            'wht_agent'=>$purchase->wht_agent,
            'vat_on_purchases'=>$purchase->vat_on_purchases,
            'billing_from'=>$purchase->billing_from,
            'billing_to'=>$purchase->billing_to,
            'ewt'=>$purchase->ewt,
                );*/
            }

        }
     
        $total_p = array_sum($total_pay);
        $data['total_paid'] = $total_p;
        $data['total_balance'] = (abs($total_am - $total_p)); 
        $this->load->view('reports/purchases_summary',$data);
        $this->load->view('template/footer');
    }


    public function ewt_summary(){
        $ref_no=$this->uri->segment(3);
        $participant=$this->uri->segment(4);
        $data['ref_no'] = $ref_no;
        $part=$this->super_model->select_column_where("participant","participant_name","settlement_id",$participant);
        $data['part'] = $part;
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['reference_no']=$this->super_model->custom_query("SELECT DISTINCT reference_number FROM purchase_transaction_head WHERE reference_number!=''");
         $data['participant']=$this->super_model->custom_query("SELECT * FROM participant GROUP BY settlement_id");
        //$data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
        $sql='';
        if($participant!='null'){
            $sql.= "short_name = '$participant' AND ";
        } 
        if($ref_no!='null'){
            $sql.= "reference_number = '$ref_no' AND ";
        }

        $query=substr($sql,0,-4);
        $qu = " WHERE saved='1' AND ewt!='0' AND ".$query;
        $data['total']=0;
        //foreach($this->super_model->select_innerjoin_where("purchase_transaction_details","purchase_transaction_head", $qu,"purchase_id","short_name") AS $s){
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_details ptd INNER JOIN purchase_transaction_head pth ON ptd.purchase_id=pth.purchase_id $qu") AS $s){
            $tin=$this->super_model->select_column_where("participant","tin","billing_id",$s->billing_id);
            $registered_address=$this->super_model->select_column_where("participant","registered_address","billing_id",$s->billing_id);
            //$company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$s->billing_id);
            $create_date = $this->super_model->select_column_where("purchase_transaction_head", "create_date", "purchase_id", $s->purchase_id);
            $company_name=$this->super_model->select_column_where("purchase_transaction_details", "company_name", "purchase_detail_id", $s->purchase_detail_id);
            if(!empty($company_name) && date('Y',strtotime($create_date))==date('Y')){
                $comp_name=$company_name;
            }else{
                $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $s->billing_id);
            }
            //$total_amount[]=$s->ewt;
            //$data['total']=array_sum($total_amount);
            $data['total']=$this->super_model->select_sum_join("ewt","purchase_transaction_details","purchase_transaction_head","purchase_transaction_head.purchase_id='$s->purchase_id' AND $query","purchase_id");
            $data['purchase'][]=array(
                'transaction_date'=>$s->transaction_date,
                'tin'=>$tin,
                'participant_name'=>$comp_name,
                'address'=>$registered_address,
                'ewt'=>$s->ewt,
                'billing_from'=>$s->billing_from,
                'billing_to'=>$s->billing_to,
            );

        }
        $this->load->view('reports/ewt_summary',$data);
        $this->load->view('template/footer');
    }

    public function cwht_summary(){
        $ref_no=$this->uri->segment(3);
        $participant=$this->uri->segment(4);
        $data['ref_no'] = $ref_no;
        $part=$this->super_model->select_column_where("participant","participant_name","billing_id",$participant);
        $data['part'] = $part;
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['reference_no']=$this->super_model->custom_query("SELECT DISTINCT reference_number FROM sales_transaction_head WHERE reference_number!=''");
        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant ORDER BY participant_name ASC");
        $sql='';
        if($participant!='null'){
            $sql.= " billing_id = '$participant' AND ";
        } 
        if($ref_no!='null'){
            $sql.= " reference_number = '$ref_no' AND ";
        }

        $query=substr($sql,0,-4);
        $qu = " WHERE saved='1' AND ewt!='0' AND ".$query;
        $data['total']=0;
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON std.sales_id=sth.sales_id $qu") AS $s){
            $tin=$this->super_model->select_column_where("participant","tin","settlement_id",$s->short_name);
            $registered_address=$this->super_model->select_column_where("participant","registered_address","settlement_id",$s->short_name);
            $company_name=$this->super_model->select_column_where("participant","participant_name","settlement_id",$s->short_name);
            $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $s->sales_id);
            $company_name=$this->super_model->select_column_where("sales_transaction_details", "company_name", "sales_detail_id", $s->sales_detail_id);
            if(!empty($company_name) && date('Y',strtotime($create_date))==date('Y')){
                $comp_name=$company_name;
            }else{
                $comp_name=$this->super_model->select_column_where("participant", "participant_name", "settlement_id", $s->short_name);
            }
            //$billing_from=$this->super_model->select_column_where("sales_transaction_head","billing_from","reference_number",$s->reference_no);
            //$billing_to=$this->super_model->select_column_where("sales_transaction_head","billing_to","reference_number",$s->reference_no);
            //$total_amount[]=$s->ewt;
            //$data['total']=array_sum($total_amount);
            $data['total']=$this->super_model->select_sum_join("ewt","sales_transaction_details","sales_transaction_head","sales_transaction_head.sales_id='$s->sales_id' AND $query", 'sales_id');
            $data['sales'][]=array(
                'transaction_date'=>$s->transaction_date,
                'tin'=>$tin,
                'participant_name'=>$comp_name,
                'address'=>$registered_address,
                'ewt'=>$s->ewt,
                'billing_from'=>$s->billing_from,
                'billing_to'=>$s->billing_to,
            );

        }
        //foreach($this->super_model->select_innerjoin_where("collection_details","collection_head", $qu,"collection_id","settlement_id") AS $s){
        /*foreach($this->super_model->custom_query("SELECT * FROM collection_details ptd INNER JOIN collection_head pth ON ptd.collection_id=pth.collection_id $qu") AS $s){
            $tin=$this->super_model->select_column_where("participant","tin","settlement_id",$s->settlement_id);
            $registered_address=$this->super_model->select_column_where("participant","registered_address","settlement_id",$s->settlement_id);
            $company_name=$this->super_model->select_column_where("participant","participant_name","settlement_id",$s->settlement_id);
            $billing_from=$this->super_model->select_column_where("sales_transaction_head","billing_from","reference_number",$s->reference_no);
            $billing_to=$this->super_model->select_column_where("sales_transaction_head","billing_to","reference_number",$s->reference_no);
            //$total_amount[]=$s->ewt;
            //$data['total']=array_sum($total_amount);
            $data['total']=$this->super_model->select_sum_where("collection_details","ewt","collection_id='$s->collection_id' AND $query");
            $data['sales'][]=array(
                'transaction_date'=>$s->collection_date,
                'tin'=>$tin,
                'participant_name'=>$company_name,
                'address'=>$registered_address,
                'ewt'=>$s->ewt,
                'billing_from'=>$billing_from,
                'billing_to'=>$billing_to,
            );

        }*/
        $this->load->view('reports/cwht_summary',$data);
        $this->load->view('template/footer');
    }

    public function sales_ledger(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['reference_no']=$this->super_model->custom_query("SELECT DISTINCT reference_number FROM sales_transaction_head WHERE reference_number!=''");
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $referenceno=str_replace("%60","",$this->uri->segment(5) ?? '');
        $data['refno'] = $referenceno;
        $data['year'] = $year;
        $data['month'] = $month;
        $sql='';

        if($month!='null' && !empty($month)){
            $sql.= " MONTH(billing_to) IN($month) AND "; 
        } 

        if($year!='null' && !empty($year)){
            $sql.= " YEAR(billing_to) = '$year' AND ";
        }
        
        if($referenceno!='null' && !empty($referenceno)){
            $sql.= " reference_number IN($referenceno) AND ";
        }

        $query=substr($sql,0,-4);
        $qu = " saved = '1' AND ".$query;
        $data['bill']=array();
        $data['total_vatable_balance']=0;
        $total_vatable_balance=array();
        $data['total_zero_rated_balance']=0;
        $total_zero_rated_balance=array();
        $data['total_zero_ecozones_balance']=0;
        $total_zero_ecozones_balance=array();
        $data['total_vat_balance']=0;
        $total_vat_balance=array();
        $data['total_ewt_balance']=0;
        $total_ewt_balance=array();
        if(!empty($query)){
        //foreach($this->super_model->select_inner_join_where("sales_transaction_details","sales_transaction_head", $qu,"sales_id","short_name,transaction_date") AS $b){
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON std.sales_id=sth.sales_id INNER JOIN participant p ON std.billing_id=p.billing_id WHERE $qu GROUP BY sth.sales_id, p.tin ORDER BY billing_from ASC, std.short_name ASC, sth.transaction_date ASC") AS $b){

                $par=array();
                foreach($this->super_model->select_custom_where('participant',"tin='$b->tin'") AS $p){
                    $par[]="'".$p->settlement_id."'";
                }
                $imp=implode(',',$par);

                $vatable_sales = $this->super_model->select_sum_where("sales_transaction_details","vatable_sales","sales_id='$b->sales_id' AND short_name IN($imp)");
                $zero_rated_sales = $this->super_model->select_sum_where("sales_transaction_details","zero_rated_sales","sales_id='$b->sales_id' AND short_name IN($imp)");
                $zero_rated_ecozones = $this->super_model->select_sum_where("sales_transaction_details","zero_rated_ecozones","sales_id='$b->sales_id' AND short_name IN($imp)");
                $vat_on_sales = $this->super_model->select_sum_where("sales_transaction_details","vat_on_sales","sales_id='$b->sales_id' AND short_name IN($imp)");
                $ewt_sales = $this->super_model->select_sum_where("sales_transaction_details","ewt","sales_id='$b->sales_id' AND short_name IN($imp)");

                /*$vatable_sales = $this->super_model->select_sum_join("vatable_sales","sales_transaction_details","participant","sales_id='$b->sales_id' AND tin='$b->tin'",'billing_id');
                $zero_rated_sales = $this->super_model->select_sum_join("zero_rated_sales","sales_transaction_details","participant","sales_id='$b->sales_id' AND tin='$b->tin'",'billing_id');
                $zero_rated_ecozones = $this->super_model->select_sum_join("zero_rated_ecozones","sales_transaction_details","participant","sales_id='$b->sales_id' AND tin='$b->tin'",'billing_id');
                $vat_on_sales = $this->super_model->select_sum_join("vat_on_sales","sales_transaction_details","participant","sales_id='$b->sales_id' AND tin='$b->tin'",'billing_id');
                $ewt_sales = $this->super_model->select_sum_join("ewt","sales_transaction_details","participant","sales_id='$b->sales_id' AND tin='$b->tin'",'billing_id');*/

                $amount=$this->super_model->select_sum_where("collection_details","amount","settlement_id IN($imp) AND reference_no='$b->reference_number'");
                $zero_rated=$this->super_model->select_sum_where("collection_details","zero_rated","settlement_id IN($imp) AND reference_no='$b->reference_number'");
                $zero_rated_ecozone=$this->super_model->select_sum_where("collection_details","zero_rated_ecozone","settlement_id IN($imp) AND reference_no='$b->reference_number'");
                $vat=$this->super_model->select_sum_where("collection_details","vat","settlement_id IN($imp) AND reference_no='$b->reference_number'");
                $ewt=$this->super_model->select_sum_where("collection_details","ewt","settlement_id IN($imp) AND reference_no='$b->reference_number'");

                /*$amount = $this->super_model->select_sum_join("amount","collection_details","participant","tin='$b->tin' AND reference_no='$b->reference_number'",'settlement_id');
                $zero_rated = $this->super_model->select_sum_join("zero_rated","collection_details","participant","tin='$b->tin' AND reference_no='$b->reference_number'",'settlement_id');
                $zero_rated_ecozone = $this->super_model->select_sum_join("zero_rated_ecozone","collection_details","participant","tin='$b->tin' AND reference_no='$b->reference_number'",'settlement_id');
                $vat = $this->super_model->select_sum_join("vat","collection_details","participant","tin='$b->tin' AND reference_no='$b->reference_number'",'settlement_id');
                $ewt = $this->super_model->select_sum_join("ewt","collection_details","participant","tin='$b->tin' AND reference_no='$b->reference_number'",'settlement_id');*/

                $vatablebalance=$vatable_sales - $amount;
                $zerobalance=$zero_rated_sales - $zero_rated;
                $zeroecobalance=$zero_rated_ecozones - $zero_rated_ecozone;
                $vatbalance=$vat_on_sales - $vat;
                $ewtbalance=$ewt_sales - $ewt;

                $total_vatable_balance[]=$vatablebalance;
                $total_zero_rated_balance[]=$zerobalance;
                $total_zero_ecozones_balance[]=$zeroecobalance;
                $total_vat_balance[]=$vatbalance;
                $total_ewt_balance[]=$ewtbalance;

                $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $b->sales_id);
                $company_name=$this->super_model->select_column_where("sales_transaction_details", "company_name", "sales_detail_id", $b->sales_detail_id);
                if(!empty($company_name) && date('Y',strtotime($create_date))==date('Y')){
                    $comp_name=$company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $b->billing_id);
                }

                $data['bill'][]=array(
                    "date"=>$b->transaction_date,
                    "company_name"=>$comp_name,
                    "billing_from"=>$b->billing_from,
                    "billing_to"=>$b->billing_to,
                    "reference_number"=>$b->reference_number,
                    "vatable_sales"=>$vatable_sales,
                    "zero_rated_sales"=>$zero_rated_sales,
                    "zero_rated_ecozones"=>$zero_rated_ecozones,
                    "vat_on_sales"=>$vat_on_sales,
                    "ewt"=>$ewt_sales,
                    "vatablebalance"=>$vatablebalance,
                    "zerobalance"=>$zerobalance,
                    "zeroecobalance"=>$zeroecobalance,
                    "vatbalance"=>$vatbalance,
                    "ewtbalance"=>$ewtbalance,
                    "cvatable_sales"=>$amount,
                    "czero_rated_sales"=>$zero_rated,
                    "czero_rated_ecozone"=>$zero_rated_ecozone,
                    "cvat_on_sales"=>$vat,
                    "cewt"=>$ewt,
                );
            }
            $data['total_vatable_balance']=array_sum($total_vatable_balance);
            $data['total_zero_rated_balance']=array_sum($total_zero_rated_balance);
            $data['total_zero_ecozones_balance']=array_sum($total_zero_ecozones_balance);
            $data['total_vat_balance']=array_sum($total_vat_balance);
            $data['total_ewt_balance']=array_sum($total_ewt_balance);
        }
        $this->load->view('reports/sales_ledger',$data);
        $this->load->view('template/footer');
    }

    public function export_sales_ledger(){
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $referenceno=str_replace("%60","",$this->uri->segment(5) ?? '');
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Sales Ledger.xlsx";
        $sql='';

        if($month!='null' && !empty($month)){
            $sql.= " MONTH(billing_to) IN($month) AND "; 
        } 

        if($year!='null' && !empty($year)){
            $sql.= " YEAR(billing_to) = '$year' AND ";
        }
        
        if($referenceno!='null' && !empty($referenceno)){
            $sql.= " reference_number IN($referenceno) AND ";
        }

        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND ".$query;
        $sheetno=0;
            //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            //$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    )
                )
            );
            //foreach($this->super_model->select_inner_join_where("sales_transaction_details","sales_transaction_head", $qu,"sales_id","reference_number") AS $head){
            foreach($this->super_model->custom_query("SELECT reference_number FROM sales_transaction_head WHERE $qu ORDER BY reference_number ASC") AS $head){
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($head->reference_number);
            foreach(range('A','AM') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A1', "Date");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C1', "Participant Name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G1', "Description");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J1', "Reference Number");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M1', "Vatable Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S1', "Zero-Rated Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y1', "Zero-Rated Ecozone");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AE1', "Vat");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AE2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AG2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AI2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AK1', "EWT");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AK2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AM2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AO2', "Balance");
            $objPHPExcel->getActiveSheet()->getStyle("A1:AP1")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("A2:AP2")->applyFromArray($styleArray);

            $num=3;
            $total_vatable_billing = array();
            $total_vatable_collection = array();
            $total_vatable_balance = array();
            $total_zerosales_billing = array();
            $total_zerosales_collection = array(); 
            $total_zerosales_balance = array();
            $total_zeroecozones_billing = array();
            $total_zeroecozones_collection = array();
            $total_zeroecozones_balance = array();
            $total_vat_billing = array();
            $total_vat_collection = array(); 
            $total_vat_balance = array(); 
            $total_ewt_billing = array();
            $total_ewt_collection = array();
            $total_ewt_balance = array();
            // foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id = std.sales_id WHERE reference_number='$head->reference_number' GROUP BY short_name ORDER BY billing_from ASC, short_name ASC") AS $details){
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON std.sales_id=sth.sales_id INNER JOIN participant p ON std.short_name=p.settlement_id WHERE reference_number='$head->reference_number' GROUP BY p.tin ORDER BY billing_from ASC, std.short_name ASC, sth.transaction_date ASC") AS $details){

                $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$details->billing_id);
                if(!empty($details->company_name) && date('Y',strtotime($details->create_date))==date('Y')){
                    $comp_name=$details->company_name;
                }else{
                    $comp_name=$company_name;
                }
                $billing_date = date("M. d, Y",strtotime($details->billing_from))." - ".date("M. d, Y",strtotime($details->billing_to));

                $par=array();
                foreach($this->super_model->select_custom_where('participant',"tin='$details->tin'") AS $p){
                    $par[]="'".$p->settlement_id."'";
                }
                $imp=implode(',',$par);

                /*$vatable_sales = $this->super_model->select_sum_join("vatable_sales","sales_transaction_details","participant","sales_id='$details->sales_id' AND tin='$details->tin'",'billing_id');
                $zero_rated_sales = $this->super_model->select_sum_join("zero_rated_sales","sales_transaction_details","participant","sales_id='$details->sales_id' AND tin='$details->tin'",'billing_id');
                $zero_rated_ecozones = $this->super_model->select_sum_join("zero_rated_ecozones","sales_transaction_details","participant","sales_id='$details->sales_id' AND tin='$details->tin'",'billing_id');
                $vat_on_sales = $this->super_model->select_sum_join("vat_on_sales","sales_transaction_details","participant","sales_id='$details->sales_id' AND tin='$details->tin'",'billing_id');
                $ewt_sales = $this->super_model->select_sum_join("ewt","sales_transaction_details","participant","sales_id='$details->sales_id' AND tin='$details->tin'",'billing_id');

                $amount = $this->super_model->select_sum_join("amount","collection_details","participant","tin='$details->tin' AND reference_no='$details->reference_number'",'settlement_id');
                $zero_rated = $this->super_model->select_sum_join("zero_rated","collection_details","participant","tin='$details->tin' AND reference_no='$details->reference_number'",'settlement_id');
                $zero_rated_ecozone = $this->super_model->select_sum_join("zero_rated_ecozone","collection_details","participant","tin='$details->tin' AND reference_no='$details->reference_number'",'settlement_id');
                $vat = $this->super_model->select_sum_join("vat","collection_details","participant","tin='$details->tin' AND reference_no='$details->reference_number'",'settlement_id');
                $ewt = $this->super_model->select_sum_join("ewt","collection_details","participant","tin='$details->tin' AND reference_no='$details->reference_number'",'settlement_id');*/

                $vatable_sales = $this->super_model->select_sum_where("sales_transaction_details","vatable_sales","sales_id='$details->sales_id' AND short_name IN($imp)");
                $zero_rated_sales = $this->super_model->select_sum_where("sales_transaction_details","zero_rated_sales","sales_id='$details->sales_id' AND short_name IN($imp)");
                $zero_rated_ecozones = $this->super_model->select_sum_where("sales_transaction_details","zero_rated_ecozones","sales_id='$details->sales_id' AND short_name IN($imp)");
                $vat_on_sales = $this->super_model->select_sum_where("sales_transaction_details","vat_on_sales","sales_id='$details->sales_id' AND short_name IN($imp)");
                $ewt_sales = $this->super_model->select_sum_where("sales_transaction_details","ewt","sales_id='$details->sales_id' AND short_name IN($imp)");

                $amount=$this->super_model->select_sum_where("collection_details","amount","settlement_id IN($imp) AND reference_no='$details->reference_number'");
                $zero_rated=$this->super_model->select_sum_where("collection_details","zero_rated","settlement_id IN($imp) AND reference_no='$details->reference_number'");
                $zero_rated_ecozone=$this->super_model->select_sum_where("collection_details","zero_rated_ecozone","settlement_id IN($imp) AND reference_no='$details->reference_number'");
                $vat=$this->super_model->select_sum_where("collection_details","vat","settlement_id IN($imp) AND reference_no='$details->reference_number'");
                $ewt=$this->super_model->select_sum_where("collection_details","ewt","settlement_id IN($imp) AND reference_no='$details->reference_number'");


                $vatablebalance=$vatable_sales - $amount;
                $zerobalance=$zero_rated_sales - $zero_rated;
                $zeroecobalance=$zero_rated_ecozones - $zero_rated_ecozone;
                $vatbalance=$vat_on_sales - $vat;
                $ewtbalance=$ewt_sales - $ewt;

            if($head->reference_number==$details->reference_number){
                $total_vatable_billing[] = $vatable_sales;
                $total_vatable_collection[] = $amount;
                $total_vatable_balance[] = $vatablebalance;

                $total_zerosales_billing[] = $zero_rated_sales;
                $total_zerosales_collection[] = $zero_rated;
                $total_zerosales_balance[] = $zerobalance;

                $total_zeroecozones_billing[] = $zero_rated_ecozones;
                $total_zeroecozones_collection[] = $zero_rated_ecozone;
                $total_zeroecozones_balance[] = $zeroecobalance;

                $total_vat_billing[] = $vat_on_sales;
                $total_vat_collection[] = $vat;
                $total_vat_balance[] = $vatbalance;

                $total_ewt_billing[] = $ewt_sales;
                $total_ewt_collection[] = $ewt;
                $total_ewt_balance[] = $ewtbalance;

                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $details->transaction_date);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $comp_name);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G'.$num, $billing_date);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J'.$num, $details->reference_number);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M'.$num, ($vatable_sales!='') ? $vatable_sales : '0.00');
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$num, ($amount!='') ? $amount : '0.00');
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$num, ($vatablebalance!='') ? $vatablebalance : '0.00');
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S'.$num, ($zero_rated_sales!='') ? $zero_rated_sales : '0.00');
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U'.$num, ($zero_rated!='') ? $zero_rated : '0.00');
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W'.$num, ($zerobalance!='') ? $zerobalance : '0.00');
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y'.$num, ($zero_rated_ecozones!='') ? $zero_rated_ecozones : '0.00');
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA'.$num, ($zero_rated_ecozone!='') ? $zero_rated_ecozone : '0.00');
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC'.$num, ($zeroecobalance!='') ? $zeroecobalance : '0.00');
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AE'.$num, ($vat_on_sales!='') ? $vat_on_sales : '0.00');
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AG'.$num, ($vat!='') ? $vat : '0.00');
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AI'.$num, ($vatbalance!='') ? $vatbalance : '0.00');
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AK'.$num, ($ewt_sales!='') ? $ewt_sales : '0.00');
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AM'.$num, ($ewt!='') ? $ewt : '0.00');
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AO'.$num, ($ewtbalance!='') ? $ewtbalance : '0.00');

                 $objPHPExcel->getActiveSheet()->mergeCells('A1:B2');
                 $objPHPExcel->getActiveSheet()->mergeCells('A'.$num.":B".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('C1:F2');
                 $objPHPExcel->getActiveSheet()->mergeCells('C'.$num.":F".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('G1:I2');
                 $objPHPExcel->getActiveSheet()->mergeCells('G'.$num.":I".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('J1:L2');
                 $objPHPExcel->getActiveSheet()->mergeCells('J'.$num.":L".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('M1:R1');
                 $objPHPExcel->getActiveSheet()->mergeCells('M2:N2');
                 $objPHPExcel->getActiveSheet()->mergeCells('M'.$num.":N".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('O2:P2');
                 $objPHPExcel->getActiveSheet()->mergeCells('O'.$num.":P".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('Q2:R2');
                 $objPHPExcel->getActiveSheet()->mergeCells('Q'.$num.":R".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('S1:X1');
                 $objPHPExcel->getActiveSheet()->mergeCells('S2:T2');
                 $objPHPExcel->getActiveSheet()->mergeCells('S'.$num.":T".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('U2:V2');
                 $objPHPExcel->getActiveSheet()->mergeCells('U'.$num.":V".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('W2:X2');
                 $objPHPExcel->getActiveSheet()->mergeCells('W'.$num.":X".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('Y1:AD1');
                 $objPHPExcel->getActiveSheet()->mergeCells('Y2:Z2');
                 $objPHPExcel->getActiveSheet()->mergeCells('Y'.$num.":Z".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('AA2:AB2');
                 $objPHPExcel->getActiveSheet()->mergeCells('AA'.$num.":AB".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('AC2:AD2');
                 $objPHPExcel->getActiveSheet()->mergeCells('AC'.$num.":AD".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('AE1:AJ1');
                 $objPHPExcel->getActiveSheet()->mergeCells('AE2:AF2');
                 $objPHPExcel->getActiveSheet()->mergeCells('AE'.$num.":AF".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('AG2:AH2');
                 $objPHPExcel->getActiveSheet()->mergeCells('AG'.$num.":AH".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('AI2:AJ2');
                 $objPHPExcel->getActiveSheet()->mergeCells('AI'.$num.":AJ".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('AK1:AP1');
                 $objPHPExcel->getActiveSheet()->mergeCells('AK2:AL2');
                 $objPHPExcel->getActiveSheet()->mergeCells('AK'.$num.":AL".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('AM2:AN2');
                 $objPHPExcel->getActiveSheet()->mergeCells('AM'.$num.":AN".$num);
                 $objPHPExcel->getActiveSheet()->mergeCells('AO2:AP2');
                 $objPHPExcel->getActiveSheet()->mergeCells('AO'.$num.":AP".$num);
                 $objPHPExcel->getActiveSheet()->getStyle('A1:AP2')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('G'.$num.":I".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('J'.$num.":L".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('M'.$num.":N".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('M'.$num.":N".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":P".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":P".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $objPHPExcel->getActiveSheet()->getStyle('Q'.$num.":R".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('Q'.$num.":R".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $objPHPExcel->getActiveSheet()->getStyle('S'.$num.":T".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('S'.$num.":T".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $objPHPExcel->getActiveSheet()->getStyle('U'.$num.":V".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('U'.$num.":V".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $objPHPExcel->getActiveSheet()->getStyle('W'.$num.":X".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('W'.$num.":X".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $objPHPExcel->getActiveSheet()->getStyle('Y'.$num.":Z".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('Y'.$num.":Z".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $objPHPExcel->getActiveSheet()->getStyle('AA'.$num.":AB".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('AA'.$num.":AB".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $objPHPExcel->getActiveSheet()->getStyle('AC'.$num.":AD".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('AC'.$num.":AD".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $objPHPExcel->getActiveSheet()->getStyle('AE'.$num.":AF".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('AE'.$num.":AF".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $objPHPExcel->getActiveSheet()->getStyle('AG'.$num.":AH".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('AG'.$num.":AH".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $objPHPExcel->getActiveSheet()->getStyle('AI'.$num.":AJ".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('AI'.$num.":AJ".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $objPHPExcel->getActiveSheet()->getStyle('AK'.$num.":AL".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('AK'.$num.":AL".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $objPHPExcel->getActiveSheet()->getStyle('AM'.$num.":AN".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('AM'.$num.":AN".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $objPHPExcel->getActiveSheet()->getStyle('AO'.$num.":AP".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                 $objPHPExcel->getActiveSheet()->getStyle('AO'.$num.":AP".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                 $objPHPExcel->getActiveSheet()->getStyle('A1:AP2')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('F1F1F1');
                 $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                // $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->getColor()->setRGB ('FFFFFF');
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":AP".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A1:AP1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A2:AP2')->getFont()->setBold(true);
                $num++;
            }
        }

                    $a = $num;
                         $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AP".$a)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('FFFCA5');
                         $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AP".$a)->applyFromArray($styleArray);
                         $objPHPExcel->getActiveSheet()->mergeCells('A'.$a.":L".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('M'.$a.":N".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('O'.$a.":P".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('Q'.$a.":R".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('S'.$a.":T".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('U'.$a.":V".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('W'.$a.":X".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('Y'.$a.":Z".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('AA'.$a.":AB".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('AC'.$a.":AD".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('AE'.$a.":AF".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('AG'.$a.":AH".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('AI'.$a.":AJ".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('AK'.$a.":AL".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('AM'.$a.":AN".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('AO'.$a.":AP".$a);
                         $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AP".$a)->getFont()->setBold(true);
                         $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AP".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                         $objPHPExcel->getActiveSheet()->getStyle('M'.$a.":N".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('M'.$a.":N".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":P".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":P".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('Q'.$a.":R".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('Q'.$a.":R".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('S'.$a.":T".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('S'.$a.":T".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('U'.$a.":V".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('U'.$a.":V".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('W'.$a.":X".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('W'.$a.":X".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('Y'.$a.":Z".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('Y'.$a.":Z".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('AA'.$a.":AB".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('AA'.$a.":AB".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('AC'.$a.":AD".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('AC'.$a.":AD".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('AE'.$a.":AF".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('AE'.$a.":AF".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('AG'.$a.":AH".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('AG'.$a.":AH".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('AI'.$a.":AJ".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('AI'.$a.":AJ".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('AK'.$a.":AL".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('AK'.$a.":AL".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('AM'.$a.":AN".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('AM'.$a.":AN".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('AO'.$a.":AP".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('AO'.$a.":AP".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        // $objPHPExcel->getActiveSheet()->getStyle('E'.$a.":H".$a)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        // $objPHPExcel->getActiveSheet()->getStyle('J'.$a)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        // $objPHPExcel->getActiveSheet()->getStyle("E".$a.':H'.$a)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        // $objPHPExcel->getActiveSheet()->getStyle("J".$a)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$a, "TOTAL: ");
                        $objPHPExcel->getActiveSheet()->setCellValue('M'.$a, array_sum($total_vatable_billing));
                        $objPHPExcel->getActiveSheet()->setCellValue('O'.$a, array_sum($total_vatable_collection));
                        $objPHPExcel->getActiveSheet()->setCellValue('Q'.$a, array_sum($total_vatable_balance));
                        $objPHPExcel->getActiveSheet()->setCellValue('S'.$a, array_sum($total_zerosales_billing));
                        $objPHPExcel->getActiveSheet()->setCellValue('U'.$a, array_sum($total_zerosales_collection));
                        $objPHPExcel->getActiveSheet()->setCellValue('W'.$a, array_sum($total_zerosales_balance));
                        $objPHPExcel->getActiveSheet()->setCellValue('Y'.$a, array_sum($total_zeroecozones_billing));
                        $objPHPExcel->getActiveSheet()->setCellValue('AA'.$a, array_sum($total_zeroecozones_collection));
                        $objPHPExcel->getActiveSheet()->setCellValue('AC'.$a, array_sum($total_zeroecozones_balance));
                        $objPHPExcel->getActiveSheet()->setCellValue('AE'.$a, array_sum($total_vat_billing));
                        $objPHPExcel->getActiveSheet()->setCellValue('AG'.$a, array_sum($total_vat_collection));
                        $objPHPExcel->getActiveSheet()->setCellValue('AI'.$a, array_sum($total_vat_balance));
                        $objPHPExcel->getActiveSheet()->setCellValue('AK'.$a, array_sum($total_ewt_billing));
                        $objPHPExcel->getActiveSheet()->setCellValue('AM'.$a, array_sum($total_ewt_collection));
                        $objPHPExcel->getActiveSheet()->setCellValue('AO'.$a, array_sum($total_ewt_balance));
                    $num--;
            $sheetno++;
            }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Sales Ledger.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Sales Ledger.xlsx"');
        // readfile($exportfilename);
    }

    public function getSalesLedgerRef(){
        $month=$this->input->post('month');
        $year=$this->input->post('year');
        $sql='';
        if($month!='null' && !empty($month)){
            $sql.= " MONTH(billing_to) IN ($month) AND ";
        }

        if($year!='null' && !empty($year)){
            $sql.= " YEAR(billing_to) = '$year' AND ";
        }
        $query=substr($sql,0,-4);
        $sales_qu = " saved = '1' AND ".$query;
        echo "<option value=''>--Select Reference Number--</option>";
        foreach($this->super_model->select_custom_where('sales_transaction_head',"$sales_qu") AS $slct){
            echo "<option value=`"."'".$slct->reference_number."'"."`>".$slct->reference_number."</option>";
        }
    }


    public function purchases_ledger()
    {
        $this->load->view('template/header');
       $this->load->view('template/navbar');
        $data['reference_no']=$this->super_model->custom_query("SELECT DISTINCT reference_number FROM purchase_transaction_head WHERE reference_number!=''");
        $ref_no=$this->uri->segment(3);
        $date_from=$this->uri->segment(4);
        $date_to=$this->uri->segment(5);
        $data['ref_no'] = $ref_no;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $sql='';

        if($date_from!='null' && $date_to != 'null'){
            $sql.= "billing_from >= '$date_from' AND billing_to <= '$date_to' AND "; 
        } if($ref_no!='null'){
            $sql.= "reference_number = '$ref_no' AND ";
        }

        $query=substr($sql,0,-4);
        $purchases = "saved = '1' AND ".$query;
        $data['bill']=array();

        $data['total_vatable_purchases']=0;
        $data['total_purchase_amount']=0;
        $total_purchase_amount=array();
        $data['total_vatable_balance']=0;
        $total_vatable_balance=array();

        $data['total_zero_rated']=0;
        $data['total_p_zero_rated']=0;
        $total_p_zero_rated=array();
        $data['total_zero_rated_balance']=0;
        $total_zero_rated_balance=array();

        $data['total_zero_ecozones']=0;
        $data['total_p_zero_ecozones']=0;
        $total_p_zero_ecozones=array();
        $data['total_zero_ecozones_balance']=0;
        $total_zero_ecozones_balance=array();

        $data['total_vat']=0;
        $data['total_p_vat']=0;
        $data['total_vat_balance']=0;
        $total_vat_balance=array();

        $data['total_ewt']=0;
        $data['total_p_ewt']=0;
        $data['total_ewt_balance']=0;
        $total_p_vat=array();
        $vat_balance=array();
        $total_p_ewt_sum =array();
        $total_ewt_balance=array();
        /*foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pth INNER JOIN purchase_transaction_details ptd ON pth.purchase_id=ptd.purchase_id WHERE saved='1' $query") AS $b){*/
        foreach($this->super_model->select_inner_join_where("purchase_transaction_details","purchase_transaction_head", $purchases,"purchase_id","purchase_detail_id") AS $b){
                /*foreach($this->super_model->custom_query("SELECT * FROM payment_head ph INNER JOIN payment_details pd ON ph.payment_id=pd.payment_id AND ph.purchase_id='$b->purchase_id' AND pd.purchase_details_id='$b->purchase_detail_id'") AS $c){*/
            $count_payment = $this->super_model->count_custom_where("payment_details", "purchase_details_id ='$b->purchase_detail_id'");
             if($count_payment>0){
                foreach($this->super_model->select_custom_where("payment_details", "purchase_details_id ='$b->purchase_detail_id'") AS $c){
            if($c->purchase_mode=='Vatable Purchase'){
                $vatable_purchases=$c->purchase_amount;
                $zero_rated='0.00';
                $rated_ecozones='0.00';
            }else if($c->purchase_mode=='Zero Rated Purchase'){
                $vatable_purchases='0.00';
                $zero_rated=$c->purchase_amount;
                $rated_ecozones='0.00';
            }else if($c->purchase_mode=='Zero Rated Ecozones'){
                $vatable_purchases='0.00';
                $zero_rated='0.00';
                $rated_ecozones=$c->purchase_amount;
            }
                $vatable_balance=$b->vatables_purchases - $vatable_purchases;
                $zerorated_balance=$b->zero_rated_purchases - $zero_rated;
                $ratedecozones_balance=$b->zero_rated_ecozones - $rated_ecozones;
                $vat_balance=$b->vat_on_purchases - $c->vat;
                $total_vat_balance[] = $vat_balance;
                $ewt_balance=$b->ewt - $c->ewt;
                //$company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$b->billing_id);
                $create_date = $this->super_model->select_column_where("purchase_transaction_head", "create_date", "purchase_id", $b->purchase_id);
                $company_name=$this->super_model->select_column_where("purchase_transaction_details", "company_name", "purchase_detail_id", $b->purchase_detail_id);
                if(!empty($company_name) && date('Y',strtotime($create_date))==date('Y')){
                    $comp_name=$company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $b->billing_id);
                }

                $data['bill'][]=array(
                    "date"=>$b->transaction_date,
                    "company_name"=>$comp_name,
                    "billing_from"=>$b->billing_from,
                    "billing_to"=>$b->billing_to,
                    "vatables_purchases"=>$b->vatables_purchases,
                    "purchase_amount"=>$vatable_purchases,
                    "zero_rated_purchases"=>$b->zero_rated_purchases,
                    "zero_rated"=>$zero_rated,
                    "zero_rated_ecozones"=>$b->zero_rated_ecozones,
                    "rated_ecozones"=>$rated_ecozones,
                    "vat_on_purchases"=>$b->vat_on_purchases,
                    "vat"=>$c->vat,
                    "ewt"=>$b->ewt,
                    "p_ewt"=>$c->ewt,
                    "vatable_balance"=>$vatable_balance,
                    "zerorated_balance"=>$zerorated_balance,
                    "ratedecozones_balance"=>$ratedecozones_balance,
                    "vat_balance"=>$vat_balance,
                    "ewt_balance"=>$ewt_balance,
                );

                $total_vatable_purchases= $this->super_model->select_sum_where("purchase_transaction_details","vatables_purchases","purchase_id='$b->purchase_id'");
                $data['total_vatable_purchases']=$total_vatable_purchases;
                $total_purchase_amount[] = $vatable_purchases;
                $total_vatable_balance[]=$vatable_balance;

                $total_zero_rated= $this->super_model->select_sum_where("purchase_transaction_details","zero_rated_purchases","purchase_id='$b->purchase_id'");
                $data['total_zero_rated']=$total_zero_rated;
                $total_p_zero_rated[] = $zero_rated;
                $total_zero_rated_balance[]=$zerorated_balance;
                

                $total_zero_ecozones= $this->super_model->select_sum_where("purchase_transaction_details","zero_rated_ecozones","purchase_id='$b->purchase_id'");
                $data['total_zero_ecozones']=$total_zero_ecozones;
                $total_p_zero_ecozones[]=$rated_ecozones;
                $total_zero_ecozones_balance[]=$ratedecozones_balance;
                
                $total_vat= $this->super_model->select_sum_where("purchase_transaction_details","vat_on_purchases","purchase_id='$b->purchase_id'");
                $data['total_vat']=$total_vat;
                $total_b_vat[] = $total_vat;
                $total_p_vat[]= $this->super_model->select_sum_where("payment_details","vat","purchase_details_id ='$b->purchase_detail_id'");
               
            
                
               // $total_vat_balance[]=$vat_balance;
                
                $total_ewt= $this->super_model->select_sum_where("purchase_transaction_details","ewt","purchase_id='$b->purchase_id'");
                $data['total_ewt']=$total_ewt;
                $total_p_ewt= $this->super_model->select_sum_where("payment_details","ewt","purchase_details_id ='$b->purchase_detail_id'");
                //$data['total_p_ewt']=$total_p_ewt;
                $total_p_ewt_sum[] = $total_p_ewt;
                $total_ewt_balance[]=$ewt_balance;
                
                }
            }
        }
        $data['total_purchase_amount']=array_sum($total_purchase_amount);
        $data['total_vatable_balance']=array_sum($total_vatable_balance);

        $data['total_p_zero_rated']=array_sum($total_p_zero_rated);
        $data['total_zero_rated_balance']=array_sum($total_zero_rated_balance);

        $data['total_p_zero_ecozones']=array_sum($total_p_zero_ecozones);
        $data['total_zero_ecozones_balance']=array_sum($total_zero_ecozones_balance);

        $data['total_p_vat']=array_sum($total_p_vat);
        $data['total_vat_balance']=array_sum($total_vat_balance);
        
        $data['total_p_ewt']=array_sum($total_p_ewt_sum);

        $data['total_ewt_balance']=array_sum($total_ewt_balance);

        $this->load->view('reports/purchases_ledger',$data);
        $this->load->view('template/footer');
    }

//     public function cs_ledger()
//     {
//         $this->load->view('template/header');
//         //$this->load->view('template/navbar');
//         //$data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
//         //$data['participant']=$this->super_model->custom_query("SELECT * FROM participant GROUP BY settlement_id");
//         //$data['participant']=$this->super_model->custom_query("SELECT * FROM participant WHERE participant_name != '' GROUP BY tin ORDER BY participant_name ASC");
//         $year=$this->uri->segment(3);
//         $month=$this->uri->segment(4);
//         $referenceno=str_replace("%60","",$this->uri->segment(5));
//         //$part=$this->super_model->select_column_where("participant","participant_name","settlement_id",$participant);
//         $data['refno'] = $referenceno;
//         $data['year'] = $year;
//         $data['month'] = $month;
//         $sql='';

//         if($month!='null' && !empty($month)){
//             //$sql.= " (MONTH(billing_from) BETWEEN '$date_from' AND '$date_to') OR (MONTH(billing_to) BETWEEN '$date_from' AND '$date_to') AND "; 
//             $sql.= " MONTH(transaction_date)='$month' AND "; 
//         } 

//         if($year!='null' && !empty($year)){
//             $sql.= " YEAR(transaction_date) = '$year' AND ";
//         }
        
//         if($referenceno!='null' && !empty($referenceno)){
//             $sql.= " reference_number IN($referenceno) AND ";
//         }

//         $query=substr($sql,0,-4);
//         //echo $query;
//         $cs_qu = " saved = '1' AND ".$query;
//         $data['csledger']=array();

//         $data['total_vatable_sales']=0;
//         $total_vatable_sales=array();
//         $data['total_amount']=0;
//         $total_amount=array();
//         $data['total_vatable_balance']=0;
//         $total_vatable_balance=array();

//         $data['total_zero_rated']=0;
//         $total_zero_rated=array();
//         $data['total_c_zero_rated']=0;
//         $total_c_zero_rated=array();
//         $data['total_zero_rated_balance']=0;
//         $total_zero_rated_balance=array();

//         $data['total_zero_ecozones']=0;
//         $total_zero_ecozones=array();
//         $data['total_c_zero_ecozones']=0;
//         $total_c_zero_ecozones=array();
//         $data['total_zero_ecozones_balance']=0;
//         $total_zero_ecozones_balance=array();

//         $data['total_vat']=0;
//         $total_vat=array();
//         $data['total_c_vat']=0;
//         $total_c_vat=array();
//         $data['total_vat_balance']=0;
//         $total_vat_balance=array();

//         $data['total_ewt']=0;
//         $total_ewt=array();
//         $data['total_c_ewt']=0;
//         $total_c_ewt=array();
//         $data['total_ewt_balance']=0;
//         $total_ewt_balance=array();
// /*        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id=std.sales_id WHERE saved='1' $query") AS $b){
//             $reference_number=$this->super_model->select_column_where("collection_details","reference_no",'settlement_id',$b->short_name);*/
//         //foreach($this->super_model->select_innerjoin_where("sales_transaction_details","sales_transaction_head", $cs_qu,"sales_id","short_name") AS $cs){

//             //echo $cs_qu;
//         //foreach($this->super_model->select_inner_join_where("sales_transaction_details","sales_transaction_head", $cs_qu,"sales_id"," short_name") AS $cs){
//         if(!empty($query)){
//             $x=0;
//             foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON std.sales_id=sth.sales_id WHERE $cs_qu ORDER BY transaction_date ASC") AS $cs){
//                 //$vatable_sales = $this->super_model->select_sum_where("sales_transaction_details","vatable_sales","sales_id='$cs->sales_id' AND short_name='$cs->short_name'");
//                 $vatable_sales = $this->super_model->select_sum_join("vatable_sales","sales_transaction_details","sales_transaction_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'sales_id');
//                 $zero_rated_sales = $this->super_model->select_sum_join("zero_rated_sales","sales_transaction_details","sales_transaction_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'sales_id');
//                 $zero_rated_ecozones = $this->super_model->select_sum_join("zero_rated_ecozones","sales_transaction_details","sales_transaction_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'sales_id');
//                 $vat_on_sales = $this->super_model->select_sum_join("vat_on_sales","sales_transaction_details","sales_transaction_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'sales_id');
//                 $ewt_sales = $this->super_model->select_sum_join("ewt","sales_transaction_details","sales_transaction_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'sales_id');
//                 $count_collection = $this->super_model->count_custom_where("collection_details", "reference_no='$cs->reference_number' AND settlement_id ='$cs->short_name'");
//                 //echo $cs->reference_number ." - ". $cs->short_name ."<br>";
//                     $id=array();
//                 if($count_collection>0){
//                     $amount=$this->super_model->select_sum_where("collection_details","amount","settlement_id='$cs->short_name' AND reference_no='$cs->reference_number'");
//                     $zero_rated=$this->super_model->select_sum_where("collection_details","zero_rated","settlement_id='$cs->short_name' AND reference_no='$cs->reference_number'");
//                     $zero_rated_ecozone=$this->super_model->select_sum_where("collection_details","zero_rated_ecozone","settlement_id='$cs->short_name' AND reference_no='$cs->reference_number'");
//                     $vat=$this->super_model->select_sum_where("collection_details","vat","settlement_id='$cs->short_name' AND reference_no='$cs->reference_number'");
//                     $ewt=$this->super_model->select_sum_where("collection_details","ewt","settlement_id='$cs->short_name' AND reference_no='$cs->reference_number'");

//                     $vatablebalance=$vatable_sales - $amount;
//                     $zerobalance=$zero_rated_sales - $zero_rated;
//                     $zeroecobalance=$zero_rated_ecozones - $zero_rated_ecozone;
//                     $vatbalance=$vat_on_sales - $vat;
//                     $ewtbalance=$ewt - $ewt;


//                     $total_vatable_sales[]=$vatable_sales;
//                     $total_amount[]=$amount;

//                 // $total_amount = array_unique($total_amount);



//                 // $total_vatable_balance[]=$vatablebalance;

//                     $total_zero_rated[]=$zero_rated_sales;         
//                     $total_c_zero_rated[]=$zero_rated;
                
//                     //$total_zero_rated_balance[]=$zerobalance;

//                     $total_zero_ecozones[]=$zero_rated_ecozones;
//                     $total_c_zero_ecozones[]=$zero_rated_ecozone;

                
//                     //$total_zero_ecozones_balance[]=$zeroecobalance;

//                     $total_vat[]=$vat_on_sales;
//                     $total_c_vat[]=$vat;
                
//                     //$total_vat_balance[]=$vatbalance;

//                     $total_ewt[]=$ewt_sales;
//                     $total_c_ewt[]=$ewt;
                
//                     //$total_ewt_balance[]=$ewtbalance;
//                     $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $cs->sales_id);
//                     $company_name=$this->super_model->select_column_where("sales_transaction_details", "company_name", "sales_detail_id", $cs->sales_detail_id);
//                     if(!empty($company_name) && date('Y',strtotime($create_date))==date('Y')){
//                         $comp_name=$company_name;
//                     }else{
//                         $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $cs->billing_id);
//                     }
//                     $data['csledger'][]=array(
//                         "sales_id"=>$cs->sales_id,
//                         "item_no"=>$cs->item_no,
//                         "date"=>$cs->transaction_date,
//                         "due_date"=>$cs->due_date,
//                         "short_name"=>$cs->short_name,
//                         "reference_no"=>$cs->reference_number,
//                         "company_name"=>$cs->company_name,
//                         "billing_from"=>$cs->billing_from,
//                         "billing_to"=>$cs->billing_to,
//                         "vatable_sales_sum"=>$vatable_sales,
//                         "vatable_sales"=>$cs->vatable_sales,
//                         "zero_rated_sales_sum"=>$zero_rated_sales,
//                         "zero_rated_sales"=>$cs->zero_rated_sales,
//                         "zero_rated_ecozones"=>$cs->zero_rated_ecozones,
//                         "zero_rated_ecozones_sum"=>$zero_rated_ecozones,
//                         "vat_on_sales"=>$cs->vat_on_sales,
//                         "vat_on_sales_sum"=>$vat_on_sales,
//                         "ewt"=>$cs->ewt,
//                         "ewt_sum"=>$ewt_sales,
//                         "vatablebalance"=>$vatablebalance,
//                         "zerobalance"=>$zerobalance,
//                         "zeroecobalance"=>$zeroecobalance,
//                         "vatbalance"=>$vatbalance,
//                         "ewtbalance"=>$ewtbalance,
//                         "cvatable_sales"=>$amount,
//                         "czero_rated_sales"=>$zero_rated,
//                         "czero_rated_ecozone"=>$zero_rated_ecozone,
//                         "cvat_on_sales"=>$vat,
//                         "cewt"=>$ewt,
//                     );
//                     $x++;
//                 }
//             }

        
//             $data['total_vatable_sales']=array_sum($total_vatable_sales);
//             $data['total_amount']=array_sum($total_amount);
//             $data['total_vatable_balance']=array_sum($total_vatable_sales) - array_sum($total_amount);

//             $data['total_zero_rated']=array_sum($total_zero_rated);
//             $data['total_c_zero_rated']=array_sum($total_c_zero_rated);
//             $data['total_zero_rated_balance']=array_sum($total_zero_rated) -array_sum($total_c_zero_rated);

//             $data['total_zero_ecozones']=array_sum($total_zero_ecozones);
//             $data['total_c_zero_ecozones']=array_sum($total_c_zero_ecozones);
//             $data['total_zero_ecozones_balance']=array_sum($total_zero_ecozones) - array_sum($total_c_zero_ecozones);

//             $data['total_vat']=array_sum($total_vat);
//             $data['total_c_vat']=array_sum($total_c_vat);
//             $data['total_vat_balance']=array_sum($total_vat)- array_sum($total_c_vat);

//             $data['total_ewt']=array_sum($total_ewt);
//             $data['total_c_ewt']=array_sum($total_c_ewt);
//             $data['total_ewt_balance']=array_sum($total_ewt) - array_sum($total_c_ewt);
//         }
//         $this->load->view('reports/cs_ledger', $data);
//         $this->load->view('template/footer');
//     }

    public function cs_ledger()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        //$data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
        //$data['participant']=$this->super_model->custom_query("SELECT * FROM participant GROUP BY settlement_id");
        //$data['participant']=$this->super_model->custom_query("SELECT * FROM participant WHERE participant_name != '' GROUP BY tin ORDER BY participant_name ASC");
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $referenceno=str_replace("%60","",$this->uri->segment(5) ?? '');
        $participant=$this->uri->segment(6);
        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant WHERE participant_name != '' GROUP BY tin ORDER BY participant_name ASC");
        //$part=$this->super_model->select_column_where("participant","participant_name","settlement_id",$participant);
        $data['part'] = $this->super_model->select_column_where("participant","participant_name","tin",$participant);
        $data['refno'] = $referenceno;
        $data['year'] = $year;
        $data['month'] = $month;
        $sql='';

        if($month!='null' && !empty($month)){
            //$sql.= " (MONTH(billing_from) BETWEEN '$date_from' AND '$date_to') OR (MONTH(billing_to) BETWEEN '$date_from' AND '$date_to') AND "; 
            //$sql.= " MONTH(transaction_date)='$month' AND "; 
            $sql.= " MONTH(billing_to) IN($month) AND "; 
        } 

        if($year!='null' && !empty($year)){
            $sql.= " YEAR(billing_to) = '$year' AND ";
        }
        
        if($referenceno!='null' && !empty($referenceno)){
            $sql.= " reference_number IN($referenceno) AND ";
        }

        if($participant!='null' && !empty($participant)){
            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";
        }
        $query=substr($sql,0,-4);
        $cs_qu = " saved = '1' AND ".$query;
        $data['csledger']=array();
        $shortlast="";
        $data['bal_amountarr']=0;
        $bal_amountarr=array();
        $data['bal_zeroratedarr']=0;
        $bal_zeroratedarr=array();
        $data['bal_zeroratedecoarr']=0;
        $bal_zeroratedecoarr=array();
        $data['bal_vatonsalesarr']=0;
        $bal_vatonsalesarr=array();
        $data['bal_ewtarr']=0;
        $bal_ewtarr=array();
        $data['bal_camountarr']=0;
        $bal_camountarr=array();
        $data['bal_czerorated_amountarr']=0;
        $bal_czerorated_amountarr=array();
        $data['bal_czeroratedeco_amountarr']=0;
        $bal_czeroratedeco_amountarr=array();
        $data['bal_cvatonsal_amountarr']=0;
        $bal_cvatonsal_amountarr=array();
        $data['bal_cewt_amountarr']=0;
        $bal_cewt_amountarr=array();
        $data['balance_vatsalarr']=0;
        $balance_vatsalarr=array();
        $data['balance_zeroratedarr']=0;
        $balance_zeroratedarr=array();
        $data['balance_zeroratedecoarr']=0;
        $balance_zeroratedecoarr=array();
        $data['balance_vatonsalesarr']=0;
        $balance_vatonsalesarr=array();
        $data['balance_ewtarr']=0;
        $balance_ewtarr=array();
        if(!empty($query)){
            $x=0;
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON std.sales_id=sth.sales_id WHERE $cs_qu GROUP BY short_name,reference_number ORDER BY transaction_date ASC") AS $cs){
                //$vatable_sales = $this->super_model->select_sum_where("sales_transaction_details","vatable_sales","sales_id='$cs->sales_id' AND short_name='$cs->short_name'");
                $vatable_sales = $this->super_model->select_sum_join("vatable_sales","sales_transaction_details","sales_transaction_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'sales_id');
                $zero_rated_sales = $this->super_model->select_sum_join("zero_rated_sales","sales_transaction_details","sales_transaction_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'sales_id');
                $zero_rated_ecozones = $this->super_model->select_sum_join("zero_rated_ecozones","sales_transaction_details","sales_transaction_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'sales_id');
                $vat_on_sales = $this->super_model->select_sum_join("vat_on_sales","sales_transaction_details","sales_transaction_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'sales_id');
                $ewt_sales = $this->super_model->select_sum_join("ewt","sales_transaction_details","sales_transaction_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'sales_id');
                $count_collection = $this->super_model->count_custom_where("collection_details", "reference_no='$cs->reference_number' AND settlement_id ='$cs->short_name'");
                $cshortname_count = $this->super_model->count_custom_where("collection_details","reference_no='$cs->reference_number' AND settlement_id ='$cs->short_name'");
                //echo $cs->reference_number ." - ". $cs->short_name ."<br>";
               
                $amount=$this->sales_display($cs->short_name,$cs->reference_number,'vatable_sales')."<span class='td-30 td-yellow'> Total: ".number_format($this->sales_sum($cs->short_name,$cs->reference_number,'vatable_sales'),2)."</span>";
                $zerorated=$this->sales_display($cs->short_name,$cs->reference_number,'zero_rated_sales')."<span class='td-30 td-yellow'> Total: ".number_format($this->sales_sum($cs->short_name,$cs->reference_number,'zero_rated_sales'),2)."</span>";
                $zeroratedeco=$this->sales_display($cs->short_name,$cs->reference_number,'zero_rated_ecozones')."<span class='td-30 td-yellow'> Total: ".number_format($this->sales_sum($cs->short_name,$cs->reference_number,'zero_rated_ecozones'),2)."</span>";
                $vatonsales=$this->sales_display($cs->short_name,$cs->reference_number,'vat_on_sales')."<span class='td-30 td-yellow'> Total: ".number_format($this->sales_sum($cs->short_name,$cs->reference_number,'vat_on_sales'),2)."</span>";
                $ewt=$this->sales_display($cs->short_name,$cs->reference_number,'ewt')."<span class='td-30 td-yellow'> Total: ".number_format($this->sales_sum($cs->short_name,$cs->reference_number,'ewt'),2)."</span>";
                $id=array();

                //Sales Balance
                $bal_amount=$this->sales_sum($cs->short_name,$cs->reference_number,'vatable_sales');
                $bal_amountarr[]=$this->sales_sum($cs->short_name,$cs->reference_number,'vatable_sales');
                $bal_zerorated=$this->sales_sum($cs->short_name,$cs->reference_number,'zero_rated_sales');
                $bal_zeroratedarr[]=$this->sales_sum($cs->short_name,$cs->reference_number,'zero_rated_sales');
                $bal_zeroratedeco=$this->sales_sum($cs->short_name,$cs->reference_number,'zero_rated_ecozones');
                $bal_zeroratedecoarr[]=$this->sales_sum($cs->short_name,$cs->reference_number,'zero_rated_ecozones');
                $bal_vatonsales=$this->sales_sum($cs->short_name,$cs->reference_number,'vat_on_sales');
                $bal_vatonsalesarr[]=$this->sales_sum($cs->short_name,$cs->reference_number,'vat_on_sales');
                $bal_ewt=$this->sales_sum($cs->short_name,$cs->reference_number,'ewt');
                $bal_ewtarr[]=$this->sales_sum($cs->short_name,$cs->reference_number,'ewt');
                if($count_collection>0){
                    $camount='';
                    $czerorated='';
                    $czeroratedeco='';
                    $cvat='';
                    $cewt='';
                    foreach($this->super_model->select_custom_where("collection_details","reference_no='$cs->reference_number' AND settlement_id ='$cs->short_name'") AS $c){
                        $camount.=$this->collection_display($c->collection_details_id,$c->settlement_id,$c->reference_no,'amount');
                        $czerorated.=$this->collection_display($c->collection_details_id,$c->settlement_id,$c->reference_no,'zero_rated');
                        $czeroratedeco.=$this->collection_display($c->collection_details_id,$c->settlement_id,$c->reference_no,'zero_rated_ecozone');
                        $cvat.=$this->collection_display($c->collection_details_id,$c->settlement_id,$c->reference_no,'vat');
                        $cewt.=$this->collection_display($c->collection_details_id,$c->settlement_id,$c->reference_no,'ewt');
                    }
                    //Collection Balance
                    $bal_camount=$this->collection_sum($cs->short_name,$cs->reference_number,'amount');
                    $bal_camountarr[]=$this->collection_sum($cs->short_name,$cs->reference_number,'amount');
                    $bal_czerorated_amount=$this->collection_sum($cs->short_name,$cs->reference_number,'zero_rated');
                    $bal_czerorated_amountarr[]=$this->collection_sum($cs->short_name,$cs->reference_number,'zero_rated');
                    $bal_czeroratedeco_amount=$this->collection_sum($cs->short_name,$cs->reference_number,'zero_rated_ecozone');
                    $bal_czeroratedeco_amountarr[]=$this->collection_sum($cs->short_name,$cs->reference_number,'zero_rated_ecozone');
                    $bal_cvatonsal_amount=$this->collection_sum($cs->short_name,$cs->reference_number,'vat');
                    $bal_cvatonsal_amountarr[]=$this->collection_sum($cs->short_name,$cs->reference_number,'vat');
                    $bal_cewt_amount=$this->collection_sum($cs->short_name,$cs->reference_number,'ewt');
                    $bal_cewt_amountarr[]=$this->collection_sum($cs->short_name,$cs->reference_number,'ewt');
                    
                    //Balance
                    $balance_vatsal=$bal_amount-$bal_camount;
                    $balance_vatsalarr[]=$balance_vatsal;
                    $balance_zerorated=$bal_zerorated-$bal_czerorated_amount;
                    $balance_zeroratedarr[]=$balance_zerorated;
                    $balance_zeroratedeco=$bal_zeroratedeco-$bal_czeroratedeco_amount;
                    $balance_zeroratedecoarr[]=$balance_zeroratedeco;
                    $balance_vatonsales=$bal_vatonsales-$bal_cvatonsal_amount;
                    $balance_vatonsalesarr[]=$balance_vatonsales;
                    $balance_ewt=$bal_ewt-$bal_cewt_amount;
                    $balance_ewtarr[]=$balance_ewt;

                    $cvatsal_amount=$camount." <span class='td-30 td-yellow'> Total: ".number_format(floatval($this->collection_sum($cs->short_name,$cs->reference_number,'amount')),2)."</span>";
                    $czerorated_amount=$czerorated." <span class='td-30 td-yellow'> Total: ".number_format(floatval($this->collection_sum($cs->short_name,$cs->reference_number,'zero_rated')),2)."</span>";
                    $czeroratedeco_amount=$czeroratedeco." <span class='td-30 td-yellow'> Total: ".number_format(floatval($this->collection_sum($cs->short_name,$cs->reference_number,'zero_rated_ecozone')),2)."</span>";
                    $cvatonsal_amount=$cvat." <span class='td-30 td-yellow'> Total: ".number_format(floatval($this->collection_sum($cs->short_name,$cs->reference_number,'vat')),2)."</span>";
                    $cewt_amount=$cewt." <span class='td-30 td-yellow'> Total: ".number_format(floatval($this->collection_sum($cs->short_name,$cs->reference_number,'ewt')),2)."</span>";
                    $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $cs->sales_id);
                    $company_name=$this->super_model->select_column_where("sales_transaction_details", "company_name", "sales_detail_id", $cs->sales_detail_id);
                    if(!empty($company_name) && date('Y',strtotime($create_date))==date('Y')){
                        $comp_name=$company_name;
                    }else{
                        $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $cs->billing_id);
                    }
                    $data['csledger'][]=array(
                        "sales_id"=>$cs->sales_id,
                        "count_collection"=>$count_collection,
                        "item_no"=>$cs->item_no,
                        "date"=>$cs->transaction_date,
                        "due_date"=>$cs->due_date,
                        "short_name"=>$cs->short_name,
                        "reference_no"=>$cs->reference_number,
                        "company_name"=>$comp_name,
                        "billing_from"=>$cs->billing_from,
                        "billing_to"=>$cs->billing_to,
                        "vatable_sales_sum"=>$vatable_sales,
                        "vatable_sales"=>$amount,
                        "cvatsal_amount"=>$cvatsal_amount,
                        "balance_vatsal"=>number_format($balance_vatsal,2),
                        "zero_rated_sales_sum"=>$zero_rated_sales,
                        "zero_rated_sales"=>$zerorated,
                        "czerorated_amount"=>$czerorated_amount,
                        "balance_zerorated"=>number_format($balance_zerorated,2),
                        "zero_rated_ecozones"=>$zeroratedeco,
                        "zero_rated_ecozones_sum"=>$zero_rated_ecozones,
                        "czeroratedeco_amount"=>$czeroratedeco_amount,
                        "balance_zeroratedeco"=>number_format($balance_zeroratedeco,2),
                        "vat_on_sales"=>$vatonsales,
                        "vat_on_sales_sum"=>$vat_on_sales,
                        "cvatonsal_amount"=>$cvatonsal_amount,
                        "balance_vatonsales"=>number_format($balance_vatonsales,2),
                        "ewt"=>$ewt,
                        "ewt_sum"=>$ewt_sales,
                        "cewt_amount"=>$cewt_amount,
                        "balance_ewt"=>number_format($balance_ewt,2),
                    );
                    $x++;
                }
            }
            // $result_amountarr = array_unique($bal_amountarr);
            // $data['bal_amountarr']=array_sum($result_amountarr);
            // $result_zeroratedarr = array_unique($bal_zeroratedarr);
            // $data['bal_zeroratedarr']=array_sum($result_zeroratedarr);
            // $result_zeroratedecoarr = array_unique($bal_zeroratedecoarr);
            // $data['bal_zeroratedecoarr']=array_sum($result_zeroratedecoarr);
            // $result_vatonsalesarr = array_unique($bal_vatonsalesarr);
            // $data['bal_vatonsalesarr']=array_sum($result_vatonsalesarr);
            // $result_ewtarr = array_unique($bal_ewtarr);
            // $data['bal_ewtarr']=array_sum($result_ewtarr);
            // $result_camountarr = array_unique($bal_camountarr);
            // $data['bal_camountarr']=array_sum($result_camountarr);
            // $result_czerorated_amountarr = array_unique($bal_czerorated_amountarr);
            // $data['bal_czerorated_amountarr']=array_sum($result_czerorated_amountarr);
            // $result_czeroratedeco_amountarr = array_unique($bal_czeroratedeco_amountarr);
            // $data['bal_czeroratedeco_amountarr']=array_sum($result_czeroratedeco_amountarr);
            // $result_cvatonsal_amountarr = array_unique($bal_cvatonsal_amountarr);
            // $data['bal_cvatonsal_amountarr']=array_sum($result_cvatonsal_amountarr);
            // $result_cewt_amountarr = array_unique($bal_cewt_amountarr);
            // $data['bal_cewt_amountarr']=array_sum($result_cewt_amountarr);

            $result_balance_vatsalarr = array_unique($balance_vatsalarr);
            $data['balance_vatsalarr']=array_sum($result_balance_vatsalarr);
            $result_balance_zeroratedarr = array_unique($balance_zeroratedarr);
            $data['balance_zeroratedarr']=array_sum($result_balance_zeroratedarr);
            $result_balance_zeroratedecoarr = array_unique($balance_zeroratedecoarr);
            $data['balance_zeroratedecoarr']=array_sum($result_balance_zeroratedecoarr);
            $result_balance_vatonsalesarr = array_unique($balance_vatonsalesarr);
            $data['balance_vatonsalesarr']=array_sum($result_balance_vatonsalesarr);
            $result_balance_ewtarr = array_unique($balance_ewtarr);
            $data['balance_ewtarr']=array_sum($result_balance_ewtarr);
        }
        $this->load->view('reports/cs_ledger', $data);
        $this->load->view('template/footer');
    }

    public function export_cs_ledger(){
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $referenceno=str_replace("%60","",$this->uri->segment(5) ?? '');

        //echo $referenceno;
        $participant=$this->uri->segment(6);
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Customer Sudsidiary Ledger.xlsx";
        $sql='';

        if($month!='null' && !empty($month)){ 
            $sql.= " MONTH(billing_to) IN($month) AND "; 
        } 

        if($year!='null' && !empty($year)){
            $sql.= " YEAR(billing_to) = '$year' AND ";
        }
        
        if($referenceno!='null' && !empty($referenceno)){
            $sql.= " reference_number IN($referenceno) AND ";
        }

        if($participant!='null' && !empty($participant)){
            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";
        }

        $query=substr($sql,0,-4);
        if($month !='null' || $year != 'null' || $referenceno != 'null' || $participant != 'null'){
            $cs_qu = " saved = '1' AND ".$query;
        }else{
             $cs_qu = " saved = '1'";
        }

        $sheetno=0;
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN
                    )
                )
            );

           
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON std.sales_id=sth.sales_id WHERE $cs_qu GROUP BY short_name ORDER BY short_name ASC") AS $head){
            $count_collection = $this->super_model->count_custom_where("collection_details", "reference_no='$head->reference_number' AND settlement_id ='$head->short_name'");
            if($count_collection>0){
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($head->short_name);
            foreach(range('A','AC') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A1', "Date");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C1', "Due Date");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E1', "Transaction No");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H1', "Participant Name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L1', "Description");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O1', "Vatable Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R1', "Zero-Rated Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U1', "Zero-Rated Ecozone");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X1', "Vat");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA1', "EWT");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC2', "Balance");
            $objPHPExcel->getActiveSheet()->getStyle("A1:AC1")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("A2:AC2")->applyFromArray($styleArray);

            $num=3;
            $num2=3;
            $total_vatsales = array();
            $total_zrs = array();
            $total_zre = array();
            $total_vat = array();
            $total_ewt = array(); 

            $total_vatsales_c = array();
            $total_zrs_c = array();
            $total_zre_c = array();
            $total_vat_c = array();
            $total_ewt_c = array(); 
          

            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON std.sales_id=sth.sales_id WHERE $cs_qu AND short_name = '$head->short_name' GROUP BY transaction_date,reference_number ORDER BY transaction_date ASC") AS $details){
                $or_no=$this->super_model->select_column_custom_where("collection_details","series_number","reference_no='$details->reference_number' AND settlement_id='$details->short_name'");
                $participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$details->billing_id);
                if(!empty($details->company_name) && date('Y',strtotime($details->create_date))==date('Y')){
                    $comp_name=$details->company_name;
                }else{
                    $comp_name=$participant_name;
                }

            $billing_date = date("M. d, Y",strtotime($details->billing_from))." - ".date("M. d, Y",strtotime($details->billing_to));
            
            $short_name=$this->super_model->select_column_where("sales_transaction_details","short_name","sales_detail_id",$details->sales_detail_id);
            
            if($details->short_name==$short_name){
                $sales_array_count = $this->get_count_sales_row($head->short_name,$details->reference_number);
                $collection_array_count= $this->get_count_collection_row($head->short_name,$details->reference_number);
    
                
                $max_merge_count = max(array_merge($sales_array_count,$collection_array_count));

                $sales_details = $this->get_sales($head->short_name,$details->reference_number,$max_merge_count);
                $collection_details = $this->get_collection($head->short_name,$details->reference_number,$max_merge_count);
               
              
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $details->transaction_date);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $details->due_date);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, $details->reference_number);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, $comp_name);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, $billing_date);
                $o=$num;
                $p=$num;
                $r=$num;
                $r1=$num;
                $r2=$num;
                $r3=$num;
                $r4=$num;
                $sum_bill_vatsales = array();
                $sum_col_vatsales = array();
                $sum_bill_zrs = array();
                $sum_col_zrs = array();
                $sum_bill_zre = array();
                $sum_col_zre = array();
                $sum_bill_vat = array();
                $sum_col_vat = array();
                $sum_bill_ewt = array();
                $sum_col_ewt = array();
                foreach($sales_details AS $d){
                   
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$o,  $d['vatable_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R'.$o, $d['zero_rated_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U'.$o, $d['zero_rated_ecozones']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X'.$o, $d['vat_on_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA'.$o, $d['ewt']);

                    $total_vatsales[]=$d['vatable_sales'];
                    $total_zre[] = $d['zero_rated_sales'];
                    $total_zrs[] = $d['zero_rated_ecozones'];
                    $total_vat[] = $d['vat_on_sales'];
                    $total_ewt[] = $d['ewt'];

                    $sum_bill_vatsales[] = $d['vatable_sales'];
                    $sum_bill_zrs[] = $d['zero_rated_sales'];
                    $sum_bill_zre[] = $d['zero_rated_ecozones'];
                    $sum_bill_vat[] = $d['vat_on_sales'];
                    $sum_bill_ewt[] = $d['ewt'];


                    $billing_details[] = array(
                        'short_name'=>$head->short_name,
                        'reference_no'=>$details->reference_number,
                        'vatable_sales'=>$d['vatable_sales'],
                        'zero_rated_sales'=>$d['zero_rated_sales'],
                        'zero_rated_ecozones'=>$d['zero_rated_ecozones'],
                        'vat_on_sales'=>$d['vat_on_sales'],
                        'ewt'=>$d['ewt'],
                    );

                     $objPHPExcel->getActiveSheet()->getStyle('A'.$o.":AC".$o)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$o.":B".$o);
                $objPHPExcel->getActiveSheet()->mergeCells('C'.$o.":D".$o);
                $objPHPExcel->getActiveSheet()->mergeCells('E'.$o.":G".$o);
                $objPHPExcel->getActiveSheet()->mergeCells('H'.$o.":K".$o);
                $objPHPExcel->getActiveSheet()->mergeCells('L'.$o.":N".$o);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$o.":B".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$o.":D".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$o.":G".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$o.":N".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$o.":AC".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$o.":AC".$o)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$o.":L".$o)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');

                    $o++;
                }
               
                foreach($collection_details AS $c){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$p, $c['vatable_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S'.$p, $c['zero_rated_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V'.$p, $c['zero_rated_ecozones']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y'.$p, $c['vat_on_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB'.$p, $c['ewt']);
                    $total_vatsales_c[]=$c['vatable_sales'];
                    $total_zre_c[] = $c['zero_rated_sales'];
                    $total_zrs_c[] = $c['zero_rated_ecozones'];
                    $total_vat_c[] = $c['vat_on_sales'];
                    $total_ewt_c[] = $c['ewt'];

                    $sum_col_vatsales[]=$c['vatable_sales'];
                    $sum_col_zrs[] = $c['zero_rated_sales'];
                    $sum_col_zre[] = $c['zero_rated_ecozones'];
                    $sum_col_vat[] = $c['vat_on_sales'];
                    $sum_col_ewt[] = $c['ewt'];

                    
                    $collection_details[] = array(
                        'short_name'=>$head->short_name,
                        'reference_no'=>$details->reference_number,
                        'vatable_sales'=>$c['vatable_sales'],
                        'zero_rated_sales'=>$c['zero_rated_sales'],
                        'zero_rated_ecozones'=>$c['zero_rated_ecozones'],
                        'vat_on_sales'=>$c['vat_on_sales'],
                        'ewt'=>$c['ewt'],
                    );

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p.":AC".$p)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$p.":B".$p);
                $objPHPExcel->getActiveSheet()->mergeCells('C'.$p.":D".$p);
                $objPHPExcel->getActiveSheet()->mergeCells('E'.$p.":G".$p);
                $objPHPExcel->getActiveSheet()->mergeCells('H'.$p.":K".$p);
                $objPHPExcel->getActiveSheet()->mergeCells('L'.$p.":N".$p);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$p.":B".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$p.":D".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p.":G".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$p.":N".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$p.":AC".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$p.":AC".$p)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$p.":L".$p)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');

                $p++;
                
                }

                 $sum_vatsales = $this->get_balance($sum_bill_vatsales, $sum_col_vatsales,$max_merge_count);
                 $sum_zrs = $this->get_balance($sum_bill_zrs, $sum_col_zrs,$max_merge_count);
                 $sum_zre = $this->get_balance($sum_bill_zre, $sum_col_zre,$max_merge_count);
                 $sum_vat = $this->get_balance($sum_bill_vat, $sum_col_vat,$max_merge_count);
                 $sum_ewt = $this->get_balance($sum_bill_ewt, $sum_col_ewt,$max_merge_count);

                foreach($sum_vatsales AS $b){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$r, $b['balance']);
                    $r++;
                }
                foreach($sum_zrs AS $b1){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T'.$r1, $b1['balance']);
                    $r1++;
                }
                foreach($sum_zre AS $b2){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W'.$r2, $b2['balance']);
                    $r2++;
                }
                foreach($sum_vat AS $b3){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z'.$r3, $b3['balance']);
                    $r3++;
                }
                foreach($sum_ewt AS $b4){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC'.$r4, $b4['balance']);
                    $r4++;
                }
              
                    $objPHPExcel->getActiveSheet()->mergeCells('C'.$num.":D".$num);
                    $objPHPExcel->getActiveSheet()->mergeCells('E'.$num.":G".$num);
                    $objPHPExcel->getActiveSheet()->mergeCells('H'.$num.":K".$num);
                    $objPHPExcel->getActiveSheet()->mergeCells('L'.$num.":N".$num);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$num.":D".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":G".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$num.":N".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                
              
                     $num+=$max_merge_count;

            }
        }

            $balance_vatsales= array_sum($total_vatsales) - array_sum($total_vatsales_c);
            $balance_zre= array_sum($total_zre) - array_sum($total_zre_c);
            $balance_zrs= array_sum($total_zrs) - array_sum($total_zrs_c);
            $balance_vat= array_sum($total_vat) - array_sum($total_vat_c);
            $balance_ewt= array_sum($total_ewt) - array_sum($total_ewt_c);

                $a = $num;
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AC".$a)->getFont()->setBold(true);
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AC".$a)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('FFFCA5');
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AC".$a)->applyFromArray($styleArray);
                     $objPHPExcel->getActiveSheet()->mergeCells('A'.$a.":N".$a);
                     $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":AC".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                     $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":AC".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$a, "TOTAL: ");
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$a, array_sum($total_vatsales));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$a, array_sum($total_vatsales_c));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$a, $balance_vatsales);
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R'.$a, array_sum($total_zrs));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S'.$a, array_sum($total_zrs_c));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T'.$a, $balance_zrs);
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U'.$a, array_sum($total_zre));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V'.$a, array_sum($total_zre_c));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W'.$a, $balance_zre);
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X'.$a, array_sum($total_vat));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y'.$a, array_sum($total_vat_c));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z'.$a, $balance_vat);
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA'.$a, array_sum($total_ewt));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB'.$a, array_sum($total_ewt_c));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC'.$a, $balance_ewt);
            //$num--;
            $sheetno++;
            }
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle('A1:AC2')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:AC2')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('F1F1F1');
            $objPHPExcel->getActiveSheet()->getStyle('A1:AC1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A2:AC2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->mergeCells('A1:B2');
            $objPHPExcel->getActiveSheet()->mergeCells('C1:D2');
            $objPHPExcel->getActiveSheet()->mergeCells('E1:G2');
            $objPHPExcel->getActiveSheet()->mergeCells('H1:K2');
            $objPHPExcel->getActiveSheet()->mergeCells('L1:N2');
            $objPHPExcel->getActiveSheet()->mergeCells('O1:Q1');
            $objPHPExcel->getActiveSheet()->mergeCells('R1:T1');
            $objPHPExcel->getActiveSheet()->mergeCells('U1:W1');
            $objPHPExcel->getActiveSheet()->mergeCells('X1:Z1');
            $objPHPExcel->getActiveSheet()->mergeCells('AA1:AC1');
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Customer Sudsidiary Ledger.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
       
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Customer Sudsidiary Ledger.xlsx"');
        // readfile($exportfilename);
    }

    public function get_count_sales_row($short_name, $reference_no){
        $count_vat=0;
        $count_zrs=0;
        $count_zre=0;
        $count_vos=0;
        $count_ewt=0;
        $count_array_sales=array();
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON sth.sales_id=std.sales_id WHERE short_name = '$short_name' AND reference_number='$reference_no'  AND saved!=0") AS $col){
            if($col->vatable_sales!=0){
                $count_vat++;
            }
            if($col->zero_rated_sales!=0){
                $count_zrs++;
            }
            if($col->zero_rated_ecozones!=0){
                $count_zre++;
            }
            if($col->vat_on_sales!=0){
                $count_vos++;
            }
            if($col->ewt!=0){
                $count_ewt++;
            }
          
        }

        $count_array_sales=array(
            "count_vat"=>$count_vat,
            "count_zrs"=>$count_zrs,
            "count_zre"=>$count_zre,
            "count_vos"=>$count_vos,
            "count_ewt"=>$count_ewt,
        );

        return $count_array_sales;
    }

    public function get_count_collection_row($short_name, $reference_no){
        $count_col_vat=0;
        $count_col_zrs=0;
        $count_col_zre=0;
        $count_col_vos=0;
        $count_col_ewt=0;
        $count_array_sales=array();
        foreach($this->super_model->custom_query("SELECT cd.* FROM collection_details cd INNER JOIN collection_head ch ON ch.collection_id=cd.collection_id WHERE settlement_id = '$short_name' AND reference_no='$reference_no' AND saved!=0 AND (amount!=0 OR zero_rated!=0 OR zero_rated_ecozone!=0 OR vat!=0 OR ewt!=0)") AS $col){
            if($col->amount!=0){
                $count_col_vat++;
            }
            if($col->zero_rated!=0){
                $count_col_zrs++;
            }
            if($col->zero_rated_ecozone!=0){
                $count_col_zre++;
            }
            if($col->vat=0){
                $count_col_vos++;
            }
            if($col->ewt!=0){
                $count_col_ewt++;
            }
          
        }

        $count_array_collection=array(
            "count_col_vat"=>$count_col_vat,
            "count_col_zrs"=>$count_col_zrs,
            "count_col_zre"=>$count_col_zre,
            "count_col_vos"=>$count_col_vos,
            "count_col_ewt"=>$count_col_ewt,
        );

        return $count_array_collection;
    }


    public function get_sales($short_name, $reference_no,$max){
       
        $array_sales=array();

       
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON sth.sales_id=std.sales_id WHERE short_name = '$short_name' AND reference_number='$reference_no'  AND saved!=0 AND (vatable_sales!=0 OR zero_rated_sales!=0 OR zero_rated_ecozones!=0  OR vat_on_sales!=0 OR ewt!=0)") AS $col){
                $array_sales[] = array(
                    "short_name"=>$short_name,
                    "reference_no"=>$reference_no,
                    "vatable_sales"=>$col->vatable_sales,
                    "zero_rated_sales"=>$col->zero_rated_sales,
                    "zero_rated_ecozones"=>$col->zero_rated_ecozones,
                    "vat_on_sales"=>$col->vat_on_sales,
                    "ewt"=>$col->ewt,
                );
            
            }
            $add = $max - count($array_sales);
            for($x=1;$x<=$add;$x++){
                $array_sales[] = array(
                    "short_name"=>"",
                    "reference_no"=>"",
                    "vatable_sales"=>"",
                    "zero_rated_sales"=>"",
                    "zero_rated_ecozones"=>"",
                    "vat_on_sales"=>"",
                    "ewt"=>"",
                );
            }
        

        return $array_sales;
    }

    public function get_collection($short_name, $reference_no,$max){
       
        $array_collection=array();

       
            foreach($this->super_model->custom_query("SELECT cd.* FROM collection_details cd INNER JOIN collection_head ch ON ch.collection_id=cd.collection_id WHERE settlement_id = '$short_name' AND reference_no='$reference_no' AND saved!=0 AND (amount != 0)") AS $col){
                $array_collection[] = array(
                    "short_name"=>$short_name,
                    "reference_no"=>$reference_no,
                    "vatable_sales"=>$col->amount,
                    "zero_rated_sales"=>$col->zero_rated,
                    "zero_rated_ecozones"=>$col->zero_rated_ecozone,
                    "vat_on_sales"=>$col->vat,
                    "ewt"=>$col->ewt,
                );
            
            }
            $add = $max - count($array_collection);
            for($x=1;$x<=$add;$x++){
                $array_collection[] = array(
                    "short_name"=>"",
                    "reference_no"=>"",
                    "vatable_sales"=>"",
                    "zero_rated_sales"=>"",
                    "zero_rated_ecozones"=>"",
                    "vat_on_sales"=>"",
                    "ewt"=>"",
                );
            }
        

        return $array_collection;
    }

    public function get_balance($total_bill, $total_collect,$max){

        $balance=  array_sum($total_bill) -  array_sum($total_collect);
        $array_balance[] = array(
            "balance"=>$balance,
        );

        $add = $max - 1;
        for($x=1;$x<=$add;$x++){
            $array_balance[] = array(
                "balance"=>"",
            );
        }

        return $array_balance;
    }

    
    public function getReference(){
        $month=$this->input->post('month');
        $year=$this->input->post('year');
        $sql='';
        if($month!='null' && !empty($month)){
            $sql.= " MONTH(billing_to) IN ($month) AND ";
        }

        if($year!='null' && !empty($year)){
            $sql.= " YEAR(billing_to) = '$year' AND ";
        }
        $query=substr($sql,0,-4);
        $cs_qu = " saved = '1' AND ".$query;
        echo "<option value=''>--Select Reference Number--</option>";
        foreach($this->super_model->select_custom_where('sales_transaction_head',"$cs_qu") AS $slct){
            echo "<option value=`"."'".$slct->reference_number."'"."`>".$slct->reference_number."</option>";
        }
    }

    public function getReferenceAdj(){
        $participant=$this->input->post('participant');
        $year=$this->input->post('year');
        $date_from=$this->input->post('date_from');
        $date_to=$this->input->post('date_to');
        $sql='';
        if(!empty($participant)){
            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";
        }

        if(!empty($date_from) && !empty($date_to)){
            $sql.= " DATE(due_date) BETWEEN '$date_from' AND '$date_to' AND ";
        }

        if(!empty($year)){
            $sql.= " YEAR(due_date) = '$year' AND ";
        }
        $query=substr($sql,0,-4);
        $cs_qu = " saved = '1' AND ".$query;
        echo "<option value=''>--Select Reference Number--</option>";
        foreach($this->super_model->select_inner_join_where('sales_adjustment_details','sales_adjustment_head',"$cs_qu",'sales_adjustment_id','reference_number') AS $slct){
            echo "<option value=".$slct->reference_number.">".$slct->reference_number."</option>";
        }
    }

    public function getReferenceAdjExport(){
        $participant=$this->input->post('participant_export');
        $year=$this->input->post('year_export');
        $sql='';
        if(!empty($participant)){
            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";
        }

        if(!empty($year)){
            $sql.= " YEAR(due_date) = '$year' AND ";
        }
        $query=substr($sql,0,-4);
        $cs_qu = " saved = '1' AND ".$query;
        echo "<option value=''>--Select Reference Number--</option>";
        foreach($this->super_model->select_inner_join_where('sales_adjustment_details','sales_adjustment_head',"$cs_qu",'sales_adjustment_id','reference_number') AS $slct){
            echo "<option value=".$slct->reference_number.">".$slct->reference_number."</option>";
        }
    }

    public function getReferencePurchAdj(){
        $participant=$this->input->post('participant');
        $year=$this->input->post('year');
        $date_from=$this->input->post('date_from');
        $date_to=$this->input->post('date_to');
        $sql='';
        if(!empty($participant)){
            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";
        }

        if(!empty($date_from) && !empty($date_to)){
            $sql.= " DATE(due_date) BETWEEN '$date_from' AND '$date_to' AND ";
        }

        if(!empty($year)){
            $sql.= " YEAR(due_date) = '$year' AND ";
        }
        $query=substr($sql,0,-4);
        $cs_qu = " saved = '1' AND adjustment='1' AND ".$query;
        echo "<option value=''>--Select Reference Number--</option>";
        foreach($this->super_model->select_inner_join_where('purchase_transaction_details','purchase_transaction_head',"$cs_qu",'purchase_id','reference_number') AS $slct){
            echo "<option value=".$slct->reference_number.">".$slct->reference_number."</option>";
        }
    }

    public function sales_display($short_name,$reference_no,$type){
        //foreach($this->super_model->custom_query("SELECT $type FROM collection_details WHERE settlement_id = '$short_name' AND reference_no IN($reference_no)") AS $col){
        $amount='';
        foreach($this->super_model->custom_query("SELECT $type FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON sth.sales_id=std.sales_id WHERE short_name = '$short_name' AND reference_number='$reference_no' AND $type!=0  AND saved!=0 ORDER BY $type ASC") AS $col){
            $amount.=number_format($col->$type,2)."<br>";
        }
        return $amount;
    }

    public function sales_display_export($short_name,$reference_no,$type){
        //foreach($this->super_model->custom_query("SELECT $type FROM collection_details WHERE settlement_id = '$short_name' AND reference_no IN($reference_no)") AS $col){
        $amount='';
        $count=0;
        foreach($this->super_model->custom_query("SELECT $type,short_name,reference_number,saved FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON sth.sales_id=std.sales_id WHERE short_name = '$short_name' AND reference_number='$reference_no' AND $type!=0 AND saved!=0 ORDER BY $type ASC") AS $col){
            $amount.=number_format($col->$type,2)."\n";
            $count=$this->super_model->count_custom("SELECT $type FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON sth.sales_id=std.sales_id WHERE short_name = '$col->short_name' AND reference_number='$col->reference_number' AND $type!=0 AND saved!=0");
        }
        return $amount."-".$count;
    }

    public function sales_sum($short_name,$reference_no,$type){
        $sum=$this->super_model->select_sum_join("$type","sales_transaction_details","sales_transaction_head","short_name = '$short_name' AND reference_number='$reference_no' AND saved!=0",'sales_id');
        return $sum;
    }

    public function sales_adjustment_display($short_name,$reference_no,$type){
        //foreach($this->super_model->custom_query("SELECT $type FROM collection_details WHERE settlement_id = '$short_name' AND reference_no IN($reference_no)") AS $col){
        $amount='';
        foreach($this->super_model->custom_query("SELECT $type FROM sales_adjustment_details std INNER JOIN sales_adjustment_head sth ON sth.sales_adjustment_id=std.sales_adjustment_id WHERE short_name = '$short_name' AND reference_number='$reference_no' AND $type!=0 AND saved!=0 ORDER BY $type ASC") AS $col){
            $amount.=number_format($col->$type,2)."<br>";
        }
        return $amount;
    }

    public function sales_adjustment_display_export($short_name,$reference_no,$type){
        $amount='';
        foreach($this->super_model->custom_query("SELECT $type FROM sales_adjustment_details std INNER JOIN sales_adjustment_head sth ON sth.sales_adjustment_id=std.sales_adjustment_id WHERE short_name = '$short_name' AND reference_number='$reference_no' AND $type!=0 AND saved!=0 ORDER BY $type ASC") AS $col){
            $amount.=number_format($col->$type,2)."\n";
        }
        return $amount;
    }

    public function sales_adjustment_sum($short_name,$reference_no,$type){
        $sum=$this->super_model->select_sum_join("$type","sales_adjustment_details","sales_adjustment_head","short_name = '$short_name' AND reference_number='$reference_no' AND saved!=0",'sales_adjustment_id');
        return $sum;
    }

    public function collection_display($collection_details_id,$short_name,$reference_no,$type){
        //foreach($this->super_model->custom_query("SELECT $type FROM collection_details WHERE settlement_id = '$short_name' AND reference_no IN($reference_no)") AS $col){
        foreach($this->super_model->custom_query("SELECT $type FROM collection_details WHERE collection_details_id='$collection_details_id' AND settlement_id = '$short_name' AND reference_no='$reference_no' AND $type!=0 ORDER BY $type ASC") AS $col){
            return number_format($col->$type,2)."<br>";
        }
    }

    public function collection_display_export($collection_details_id,$short_name,$reference_no,$type){
        //foreach($this->super_model->custom_query("SELECT $type FROM collection_details WHERE settlement_id = '$short_name' AND reference_no IN($reference_no)") AS $col){
        foreach($this->super_model->custom_query("SELECT $type FROM collection_details WHERE collection_details_id='$collection_details_id' AND settlement_id = '$short_name' AND reference_no='$reference_no' AND $type!=0 ORDER BY $type ASC") AS $col){
            return number_format($col->$type,2)."\n";
        }
    }

    public function collection_sum($short_name,$reference_no,$type){
        $sum=$this->super_model->select_sum_where('collection_details',"$type","settlement_id='$short_name' AND reference_no = '$reference_no' AND $type!=0");
        return $sum;
    }

    public function purchase_adjustment_display($short_name,$reference_no,$type){
        //foreach($this->super_model->custom_query("SELECT $type FROM collection_details WHERE settlement_id = '$short_name' AND reference_no IN($reference_no)") AS $col){
        $amount='';
        foreach($this->super_model->custom_query("SELECT $type FROM purchase_transaction_details std INNER JOIN purchase_transaction_head sth ON sth.purchase_id=std.purchase_id WHERE short_name = '$short_name' AND reference_number='$reference_no' AND $type!=0 AND saved!=0 ORDER BY $type ASC") AS $col){
            $amount.=number_format($col->$type,2)."<br>";
        }
        return $amount;
    }

    public function purchase_adjustment_display_export($short_name,$reference_no,$type){
        $amount='';
        foreach($this->super_model->custom_query("SELECT $type FROM purchase_transaction_details std INNER JOIN purchase_transaction_head sth ON sth.purchase_id=std.purchase_id WHERE short_name = '$short_name' AND reference_number='$reference_no' AND $type!=0 AND saved!=0 ORDER BY $type ASC") AS $col){
            $amount.=number_format($col->$type,2)."\n";
        }
        return $amount;
    }

    public function purchase_adjustment_sum($short_name,$reference_no,$type){
        $sum=$this->super_model->select_sum_join("$type","purchase_transaction_details","purchase_transaction_head","short_name = '$short_name' AND reference_number='$reference_no' AND saved!=0",'purchase_id');
        return $sum;
    }

    public function payment_display($payment_id,$short_name,$type){
        //foreach($this->super_model->custom_query("SELECT $type FROM collection_details WHERE settlement_id = '$short_name' AND reference_no IN($reference_no)") AS $col){
        foreach($this->super_model->custom_query("SELECT $type FROM payment_details WHERE payment_id='$payment_id' AND short_name = '$short_name' AND $type!=0 ORDER BY $type ASC") AS $col){
            return number_format($col->$type,2)."<br>";
        }
    }

    public function payment_display_export($payment_id,$short_name,$type){
        //foreach($this->super_model->custom_query("SELECT $type FROM collection_details WHERE settlement_id = '$short_name' AND reference_no IN($reference_no)") AS $col){
        foreach($this->super_model->custom_query("SELECT $type FROM payment_details WHERE payment_id='$payment_id' AND short_name = '$short_name' AND $type!=0 ORDER BY $type ASC") AS $col){
            return number_format($col->$type,2)."\n";
        }
    }

    public function payment_sum($short_name,$payment_id,$type){
        $sum=$this->super_model->select_sum_where('payment_details',"$type","short_name='$short_name' AND payment_id = '$payment_id' AND $type!=0");
        return $sum;
    }

    public function cs_ledger_salesadj()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        //$data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
        //$data['participant']=$this->super_model->custom_query("SELECT * FROM participant GROUP BY settlement_id");
        $data['reference_no']=$this->super_model->custom_query("SELECT DISTINCT reference_number FROM sales_adjustment_head WHERE reference_number!='' AND saved='1'");
        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant WHERE participant_name != '' GROUP BY tin ORDER BY participant_name ASC");
        $participant=$this->uri->segment(3);
        $referenceno=$this->uri->segment(4);
        $years=$this->uri->segment(5);
        $from=$this->uri->segment(6);
        $to=$this->uri->segment(7);
        $part=$this->super_model->select_column_where("participant","participant_name","tin",$participant);
        $data['participants'] = $part;
        $data['referenceno'] = $referenceno;
        $data['year'] = $years;
        $data['from'] = $from;
        $data['to'] = $to;
        $sql='';
        
        if($participant!='null' && !empty($participant)){
            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";
        }
        
        if($referenceno!='null' && !empty($referenceno)){
            $sql.= " reference_number='$referenceno' AND ";
        }

        if($years!='null' && !empty($years)){
            $sql.= " YEAR(due_date)='$years' AND ";
        }

        if(($from!='null' && !empty($from)) && ($to!='null' && !empty($to))){
            $sql.= " due_date BETWEEN '$from' AND '$to' AND "; 
        } 

        $query=substr($sql,0,-4);
        //echo $participant;
        $cs_qu = " saved = '1' AND ".$query;
        $data['csledger']=array();
        $shortlast="";
        $data['bal_amountarr']=0;
        $bal_amountarr=array();
        $data['bal_zeroratedarr']=0;
        $bal_zeroratedarr=array();
        $data['bal_zeroratedecoarr']=0;
        $bal_zeroratedecoarr=array();
        $data['bal_vatonsalesarr']=0;
        $bal_vatonsalesarr=array();
        $data['bal_ewtarr']=0;
        $bal_ewtarr=array();
        $data['bal_camountarr']=0;
        $bal_camountarr=array();
        $data['bal_czerorated_amountarr']=0;
        $bal_czerorated_amountarr=array();
        $data['bal_czeroratedeco_amountarr']=0;
        $bal_czeroratedeco_amountarr=array();
        $data['bal_cvatonsal_amountarr']=0;
        $bal_cvatonsal_amountarr=array();
        $data['bal_cewt_amountarr']=0;
        $bal_cewt_amountarr=array();
        $data['balance_vatsalarr']=0;
        $balance_vatsalarr=array();
        $data['balance_zeroratedarr']=0;
        $balance_zeroratedarr=array();
        $data['balance_zeroratedecoarr']=0;
        $balance_zeroratedecoarr=array();
        $data['balance_vatonsalesarr']=0;
        $balance_vatonsalesarr=array();
        $data['balance_ewtarr']=0;
        $balance_ewtarr=array();
        if(!empty($query)){
            $x=0;
            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details std INNER JOIN sales_adjustment_head sth ON std.sales_adjustment_id=sth.sales_adjustment_id WHERE $cs_qu GROUP BY short_name,reference_number ORDER BY transaction_date ASC") AS $cs){
                //$vatable_sales = $this->super_model->select_sum_where("sales_transaction_details","vatable_sales","sales_id='$cs->sales_id' AND short_name='$cs->short_name'");
                $vatable_sales = $this->super_model->select_sum_join("vatable_sales","sales_adjustment_details","sales_adjustment_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'sales_adjustment_id');
                $zero_rated_sales = $this->super_model->select_sum_join("zero_rated_sales","sales_adjustment_details","sales_adjustment_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'sales_adjustment_id');
                $zero_rated_ecozones = $this->super_model->select_sum_join("zero_rated_ecozones","sales_adjustment_details","sales_adjustment_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'sales_adjustment_id');
                $vat_on_sales = $this->super_model->select_sum_join("vat_on_sales","sales_adjustment_details","sales_adjustment_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'sales_adjustment_id');
                $ewt_sales = $this->super_model->select_sum_join("ewt","sales_adjustment_details","sales_adjustment_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'sales_adjustment_id');
                $count_collection = $this->super_model->count_custom_where("collection_details", "reference_no='$cs->reference_number' AND settlement_id ='$cs->short_name'");
                $cshortname_count = $this->super_model->count_custom_where("collection_details","reference_no='$cs->reference_number' AND settlement_id ='$cs->short_name'");
                //echo $cs->reference_number ." - ". $cs->short_name ."<br>";
               
                $amount=$this->sales_adjustment_display($cs->short_name,$cs->reference_number,'vatable_sales')."<span class='td-30 td-yellow'> Total: ".number_format($this->sales_adjustment_sum($cs->short_name,$cs->reference_number,'vatable_sales'),2)."</span>";
                $zerorated=$this->sales_adjustment_display($cs->short_name,$cs->reference_number,'zero_rated_sales')."<span class='td-30 td-yellow'> Total: ".number_format($this->sales_adjustment_sum($cs->short_name,$cs->reference_number,'zero_rated_sales'),2)."</span>";
                $zeroratedeco=$this->sales_adjustment_display($cs->short_name,$cs->reference_number,'zero_rated_ecozones')."<span class='td-30 td-yellow'> Total: ".number_format($this->sales_adjustment_sum($cs->short_name,$cs->reference_number,'zero_rated_ecozones'),2)."</span>";
                $vatonsales=$this->sales_adjustment_display($cs->short_name,$cs->reference_number,'vat_on_sales')."<span class='td-30 td-yellow'> Total: ".number_format($this->sales_adjustment_sum($cs->short_name,$cs->reference_number,'vat_on_sales'),2)."</span>";
                $ewt=$this->sales_adjustment_display($cs->short_name,$cs->reference_number,'ewt')."<span class='td-30 td-yellow'> Total: ".number_format($this->sales_adjustment_sum($cs->short_name,$cs->reference_number,'ewt'),2)."</span>";
                $id=array();

                //Sales Balance
                $bal_amount=$this->sales_adjustment_sum($cs->short_name,$cs->reference_number,'vatable_sales');
                $bal_amountarr[]=$this->sales_adjustment_sum($cs->short_name,$cs->reference_number,'vatable_sales');
                $bal_zerorated=$this->sales_adjustment_sum($cs->short_name,$cs->reference_number,'zero_rated_sales');
                $bal_zeroratedarr[]=$this->sales_adjustment_sum($cs->short_name,$cs->reference_number,'zero_rated_sales');
                $bal_zeroratedeco=$this->sales_adjustment_sum($cs->short_name,$cs->reference_number,'zero_rated_ecozones');
                $bal_zeroratedecoarr[]=$this->sales_adjustment_sum($cs->short_name,$cs->reference_number,'zero_rated_ecozones');
                $bal_vatonsales=$this->sales_adjustment_sum($cs->short_name,$cs->reference_number,'vat_on_sales');
                $bal_vatonsalesarr[]=$this->sales_adjustment_sum($cs->short_name,$cs->reference_number,'vat_on_sales');
                $bal_ewt=$this->sales_adjustment_sum($cs->short_name,$cs->reference_number,'ewt');
                $bal_ewtarr[]=$this->sales_adjustment_sum($cs->short_name,$cs->reference_number,'ewt');
                if($count_collection>0){
                    $camount='';
                    $czerorated='';
                    $czeroratedeco='';
                    $cvat='';
                    $cewt='';
                    foreach($this->super_model->select_custom_where("collection_details","reference_no='$cs->reference_number' AND settlement_id ='$cs->short_name'") AS $c){
                        $camount.=$this->collection_display($c->collection_details_id,$c->settlement_id,$c->reference_no,'amount');
                        $czerorated.=$this->collection_display($c->collection_details_id,$c->settlement_id,$c->reference_no,'zero_rated');
                        $czeroratedeco.=$this->collection_display($c->collection_details_id,$c->settlement_id,$c->reference_no,'zero_rated_ecozone');
                        $cvat.=$this->collection_display($c->collection_details_id,$c->settlement_id,$c->reference_no,'vat');
                        $cewt.=$this->collection_display($c->collection_details_id,$c->settlement_id,$c->reference_no,'ewt');
                    }
                    //Collection Balance
                    $bal_camount=$this->collection_sum($cs->short_name,$cs->reference_number,'amount');
                    $bal_camountarr[]=$this->collection_sum($cs->short_name,$cs->reference_number,'amount');
                    $bal_czerorated_amount=$this->collection_sum($cs->short_name,$cs->reference_number,'zero_rated');
                    $bal_czerorated_amountarr[]=$this->collection_sum($cs->short_name,$cs->reference_number,'zero_rated');
                    $bal_czeroratedeco_amount=$this->collection_sum($cs->short_name,$cs->reference_number,'zero_rated_ecozone');
                    $bal_czeroratedeco_amountarr[]=$this->collection_sum($cs->short_name,$cs->reference_number,'zero_rated_ecozone');
                    $bal_cvatonsal_amount=$this->collection_sum($cs->short_name,$cs->reference_number,'vat');
                    $bal_cvatonsal_amountarr[]=$this->collection_sum($cs->short_name,$cs->reference_number,'vat');
                    $bal_cewt_amount=$this->collection_sum($cs->short_name,$cs->reference_number,'ewt');
                    $bal_cewt_amountarr[]=$this->collection_sum($cs->short_name,$cs->reference_number,'ewt');
                    
                    //Balance
                    $balance_vatsal=$bal_amount-$bal_camount;
                    $balance_vatsalarr[]=$balance_vatsal;
                    $balance_zerorated=$bal_zerorated-$bal_czerorated_amount;
                    $balance_zeroratedarr[]=$balance_zerorated;
                    $balance_zeroratedeco=$bal_zeroratedeco-$bal_czeroratedeco_amount;
                    $balance_zeroratedecoarr[]=$balance_zeroratedeco;
                    $balance_vatonsales=$bal_vatonsales-$bal_cvatonsal_amount;
                    $balance_vatonsalesarr[]=$balance_vatonsales;
                    $balance_ewt=$bal_ewt-$bal_cewt_amount;
                    $balance_ewtarr[]=$balance_ewt;

                    $cvatsal_amount=$camount." <span class='td-30 td-yellow'> Total: ".number_format((float)$this->collection_sum($cs->short_name,$cs->reference_number,'amount'),2)."</span>";
                    $czerorated_amount=$czerorated." <span class='td-30 td-yellow'> Total: ".number_format((float)$this->collection_sum($cs->short_name,$cs->reference_number,'zero_rated'),2)."</span>";
                    $czeroratedeco_amount=$czeroratedeco." <span class='td-30 td-yellow'> Total: ".number_format((float)$this->collection_sum($cs->short_name,$cs->reference_number,'zero_rated_ecozone'),2)."</span>";
                    $cvatonsal_amount=$cvat." <span class='td-30 td-yellow'> Total: ".number_format((float)$this->collection_sum($cs->short_name,$cs->reference_number,'vat'),2)."</span>";
                    $cewt_amount=$cewt." <span class='td-30 td-yellow'> Total: ".number_format((float)$this->collection_sum($cs->short_name,$cs->reference_number,'ewt'),2)."</span>";
                    $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id", $cs->sales_adjustment_id);
                    $company_name=$this->super_model->select_column_where("sales_adjustment_details", "company_name", "adjustment_detail_id", $cs->adjustment_detail_id);
                    if(!empty($company_name) && date('Y',strtotime($create_date))==date('Y')){
                        $comp_name=$company_name;
                    }else{
                        $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $cs->billing_id);
                    }
                    $data['csledger'][]=array(
                        "sales_adjustment_id"=>$cs->sales_adjustment_id,
                        "count_collection"=>$count_collection,
                        "item_no"=>$cs->item_no,
                        "date"=>$cs->transaction_date,
                        "due_date"=>$cs->due_date,
                        "short_name"=>$cs->short_name,
                        "reference_no"=>$cs->reference_number,
                        "company_name"=>$comp_name,
                        "billing_from"=>$cs->billing_from,
                        "billing_to"=>$cs->billing_to,
                        "vatable_sales_sum"=>$vatable_sales,
                        "vatable_sales"=>$amount,
                        "cvatsal_amount"=>$cvatsal_amount,
                        "balance_vatsal"=>number_format($balance_vatsal,2),
                        "zero_rated_sales_sum"=>$zero_rated_sales,
                        "zero_rated_sales"=>$zerorated,
                        "czerorated_amount"=>$czerorated_amount,
                        "balance_zerorated"=>number_format($balance_zerorated,2),
                        "zero_rated_ecozones"=>$zeroratedeco,
                        "zero_rated_ecozones_sum"=>$zero_rated_ecozones,
                        "czeroratedeco_amount"=>$czeroratedeco_amount,
                        "balance_zeroratedeco"=>number_format($balance_zeroratedeco,2),
                        "vat_on_sales"=>$vatonsales,
                        "vat_on_sales_sum"=>$vat_on_sales,
                        "cvatonsal_amount"=>$cvatonsal_amount,
                        "balance_vatonsales"=>number_format($balance_vatonsales,2),
                        "ewt"=>$ewt,
                        "ewt_sum"=>$ewt_sales,
                        "cewt_amount"=>$cewt_amount,
                        "balance_ewt"=>number_format($balance_ewt,2),
                    );
                    $x++;
                }
            }
            $result_balance_vatsalarr = array_unique($balance_vatsalarr);
            $data['balance_vatsalarr']=array_sum($result_balance_vatsalarr);
            $result_balance_zeroratedarr = array_unique($balance_zeroratedarr);
            $data['balance_zeroratedarr']=array_sum($result_balance_zeroratedarr);
            $result_balance_zeroratedecoarr = array_unique($balance_zeroratedecoarr);
            $data['balance_zeroratedecoarr']=array_sum($result_balance_zeroratedecoarr);
            $result_balance_vatonsalesarr = array_unique($balance_vatonsalesarr);
            $data['balance_vatonsalesarr']=array_sum($result_balance_vatonsalesarr);
            $result_balance_ewtarr = array_unique($balance_ewtarr);
            $data['balance_ewtarr']=array_sum($result_balance_ewtarr);
        }
        $this->load->view('reports/cs_ledger_salesadj', $data);
        $this->load->view('template/footer');
    }

    public function export_cs_ledger_saledadj_pn(){
        $participant=$this->uri->segment(3);
        $referenceno=$this->uri->segment(4);
        $years=$this->uri->segment(5);
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Customer Sudsidiary Ledger (Sales Adjustment per Participant Name).xlsx";
        $sql='';

        if($participant!='null' && !empty($participant)){
            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";
        }
        
        if($referenceno!='null' && !empty($referenceno)){
            $sql.= " reference_number='$referenceno' AND ";
        }

        if($years!='null' && !empty($years)){
            $sql.= " YEAR(due_date)='$years' AND ";
        }

        $query=substr($sql,0,-4);
        $cssa_qu = " saved = '1' AND ".$query;
        $sheetno=0;
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => border::BORDER_THIN
                )
            )
        );

        foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id=sah.sales_adjustment_id WHERE $cssa_qu GROUP BY short_name ORDER BY short_name ASC") AS $head){
            $count_collection = $this->super_model->count_custom_where("collection_details", "reference_no='$head->reference_number' AND settlement_id ='$head->short_name'");
            if($count_collection>0){
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($head->short_name);
            foreach(range('A','AC') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A1', "Date");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C1', "Due Date");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E1', "Transaction No");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H1', "Participant Name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L1', "Description");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O1', "Vatable Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R1', "Zero-Rated Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U1', "Zero-Rated Ecozone");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X1', "Vat");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA1', "EWT");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC2', "Balance");
            $objPHPExcel->getActiveSheet()->getStyle("A1:AC1")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("A2:AC2")->applyFromArray($styleArray);

            $num=3;
            $total_vatsales = array();
            $total_zrs = array();
            $total_zre = array();
            $total_vat = array();
            $total_ewt = array(); 
            $total_vatsales_c = array();
            $total_zrs_c = array();
            $total_zre_c = array();
            $total_vat_c = array();
            $total_ewt_c = array(); 
            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id=sah.sales_adjustment_id WHERE $cssa_qu AND short_name = '$head->short_name' GROUP BY reference_number,transaction_date ORDER BY transaction_date ASC") AS $details){
            $or_no=$this->super_model->select_column_custom_where("collection_details","series_number","reference_no='$details->reference_number' AND settlement_id='$details->short_name'");
            $participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$details->billing_id);
            if(!empty($details->company_name) && date('Y',strtotime($details->create_date))==date('Y')){
                $comp_name=$details->company_name;
            }else{
                $comp_name=$participant_name;
            }
            $billing_date = date("M. d, Y",strtotime($details->billing_from))." - ".date("M. d, Y",strtotime($details->billing_to));
            $short_name=$this->super_model->select_column_where("sales_adjustment_details","short_name","adjustment_detail_id",$details->adjustment_detail_id);
            if($details->short_name==$short_name){
                $sales_array_count = $this->get_count_salesadj_row($head->short_name,$details->reference_number);
                $collection_array_count= $this->get_count_collection_row($head->short_name,$details->reference_number);
                $max_merge_count = max(array_merge($sales_array_count,$collection_array_count));
                $sales_details = $this->get_salesadj($head->short_name,$details->reference_number,$max_merge_count);
                $collection_details = $this->get_collection($head->short_name,$details->reference_number,$max_merge_count);

                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $details->transaction_date);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $details->due_date);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, $details->reference_number);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, $comp_name);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, $billing_date);
                $o=$num;
                $p=$num;
                $r=$num;
                $r1=$num;
                $r2=$num;
                $r3=$num;
                $r4=$num;
                $sum_bill_vatsales = array();
                $sum_col_vatsales = array();
                $sum_bill_zrs = array();
                $sum_col_zrs = array();
                $sum_bill_zre = array();
                $sum_col_zre = array();
                $sum_bill_vat = array();
                $sum_col_vat = array();
                $sum_bill_ewt = array();
                $sum_col_ewt = array();
                foreach($sales_details AS $d){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$o,  $d['vatable_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R'.$o, $d['zero_rated_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U'.$o, $d['zero_rated_ecozones']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X'.$o, $d['vat_on_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA'.$o, $d['ewt']);
                    $total_vatsales[]=$d['vatable_sales'];
                    $total_zre[] = $d['zero_rated_sales'];
                    $total_zrs[] = $d['zero_rated_ecozones'];
                    $total_vat[] = $d['vat_on_sales'];
                    $total_ewt[] = $d['ewt'];
                    $sum_bill_vatsales[] = $d['vatable_sales'];
                    $sum_bill_zrs[] = $d['zero_rated_sales'];
                    $sum_bill_zre[] = $d['zero_rated_ecozones'];
                    $sum_bill_vat[] = $d['vat_on_sales'];
                    $sum_bill_ewt[] = $d['ewt'];
                    $billing_details[] = array(
                        'short_name'=>$head->short_name,
                        'reference_no'=>$details->reference_number,
                        'vatable_sales'=>$d['vatable_sales'],
                        'zero_rated_sales'=>$d['zero_rated_sales'],
                        'zero_rated_ecozones'=>$d['zero_rated_ecozones'],
                        'vat_on_sales'=>$d['vat_on_sales'],
                        'ewt'=>$d['ewt'],
                    );

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$o.":AC".$o)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.$o.":B".$o);
                    $objPHPExcel->getActiveSheet()->mergeCells('C'.$o.":D".$o);
                    $objPHPExcel->getActiveSheet()->mergeCells('E'.$o.":G".$o);
                    $objPHPExcel->getActiveSheet()->mergeCells('H'.$o.":K".$o);
                    $objPHPExcel->getActiveSheet()->mergeCells('L'.$o.":N".$o);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$o.":B".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$o.":D".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$o.":G".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$o.":N".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$o.":AC".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$o.":AC".$o)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$o.":L".$o)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                    $o++;
                }

                foreach($collection_details AS $c){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$p, $c['vatable_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S'.$p, $c['zero_rated_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V'.$p, $c['zero_rated_ecozones']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y'.$p, $c['vat_on_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB'.$p, $c['ewt']);
                    $total_vatsales_c[]=$c['vatable_sales'];
                    $total_zre_c[] = $c['zero_rated_sales'];
                    $total_zrs_c[] = $c['zero_rated_ecozones'];
                    $total_vat_c[] = $c['vat_on_sales'];
                    $total_ewt_c[] = $c['ewt'];
                    $sum_col_vatsales[]=$c['vatable_sales'];
                    $sum_col_zrs[] = $c['zero_rated_sales'];
                    $sum_col_zre[] = $c['zero_rated_ecozones'];
                    $sum_col_vat[] = $c['vat_on_sales'];
                    $sum_col_ewt[] = $c['ewt'];
                    $collection_details[] = array(
                        'short_name'=>$head->short_name,
                        'reference_no'=>$details->reference_number,
                        'vatable_sales'=>$c['vatable_sales'],
                        'zero_rated_sales'=>$c['zero_rated_sales'],
                        'zero_rated_ecozones'=>$c['zero_rated_ecozones'],
                        'vat_on_sales'=>$c['vat_on_sales'],
                        'ewt'=>$c['ewt'],
                    );
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p.":AC".$p)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.$p.":B".$p);
                    $objPHPExcel->getActiveSheet()->mergeCells('C'.$p.":D".$p);
                    $objPHPExcel->getActiveSheet()->mergeCells('E'.$p.":G".$p);
                    $objPHPExcel->getActiveSheet()->mergeCells('H'.$p.":K".$p);
                    $objPHPExcel->getActiveSheet()->mergeCells('L'.$p.":N".$p);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p.":B".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$p.":D".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$p.":G".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$p.":N".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$p.":AC".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$p.":AC".$p)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p.":L".$p)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                    $p++;
                }

                $sum_vatsales = $this->get_balance($sum_bill_vatsales, $sum_col_vatsales,$max_merge_count);
                $sum_zrs = $this->get_balance($sum_bill_zrs, $sum_col_zrs,$max_merge_count);
                $sum_zre = $this->get_balance($sum_bill_zre, $sum_col_zre,$max_merge_count);
                $sum_vat = $this->get_balance($sum_bill_vat, $sum_col_vat,$max_merge_count);
                $sum_ewt = $this->get_balance($sum_bill_ewt, $sum_col_ewt,$max_merge_count);
                foreach($sum_vatsales AS $b){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$r, $b['balance']);
                    $r++;
                }
                foreach($sum_zrs AS $b1){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T'.$r1, $b1['balance']);
                    $r1++;
                }
                foreach($sum_zre AS $b2){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W'.$r2, $b2['balance']);
                    $r2++;
                }
                foreach($sum_vat AS $b3){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z'.$r3, $b3['balance']);
                    $r3++;
                }
                foreach($sum_ewt AS $b4){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC'.$r4, $b4['balance']);
                    $r4++;
                }
                $objPHPExcel->getActiveSheet()->mergeCells('C'.$num.":D".$num);
                $objPHPExcel->getActiveSheet()->mergeCells('E'.$num.":G".$num);
                $objPHPExcel->getActiveSheet()->mergeCells('H'.$num.":K".$num);
                $objPHPExcel->getActiveSheet()->mergeCells('L'.$num.":N".$num);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$num.":D".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":G".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num.":N".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                $num+=$max_merge_count;
            }
        }
                $balance_vatsales= array_sum($total_vatsales) - array_sum($total_vatsales_c);
                $balance_zre= array_sum($total_zre) - array_sum($total_zre_c);
                $balance_zrs= array_sum($total_zrs) - array_sum($total_zrs_c);
                $balance_vat= array_sum($total_vat) - array_sum($total_vat_c);
                $balance_ewt= array_sum($total_ewt) - array_sum($total_ewt_c);
                $a = $num;
                $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AC".$a)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AC".$a)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('FFFCA5');
                $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AC".$a)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$a.":N".$a);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":AC".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":AC".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$a, "TOTAL: ");
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$a, array_sum($total_vatsales));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$a, array_sum($total_vatsales_c));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$a, $balance_vatsales);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R'.$a, array_sum($total_zrs));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S'.$a, array_sum($total_zrs_c));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T'.$a, $balance_zrs);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U'.$a, array_sum($total_zre));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V'.$a, array_sum($total_zre_c));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W'.$a, $balance_zre);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X'.$a, array_sum($total_vat));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y'.$a, array_sum($total_vat_c));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z'.$a, $balance_vat);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA'.$a, array_sum($total_ewt));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB'.$a, array_sum($total_ewt_c));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC'.$a, $balance_ewt);
                $num--;
                $sheetno++;
            }
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle('A1:AC2')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:AC2')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('F1F1F1');
            $objPHPExcel->getActiveSheet()->getStyle('A1:AC1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A2:AC2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->mergeCells('A1:B2');
            $objPHPExcel->getActiveSheet()->mergeCells('C1:D2');
            $objPHPExcel->getActiveSheet()->mergeCells('E1:G2');
            $objPHPExcel->getActiveSheet()->mergeCells('H1:K2');
            $objPHPExcel->getActiveSheet()->mergeCells('L1:N2');
            $objPHPExcel->getActiveSheet()->mergeCells('O1:Q1');
            $objPHPExcel->getActiveSheet()->mergeCells('R1:T1');
            $objPHPExcel->getActiveSheet()->mergeCells('U1:W1');
            $objPHPExcel->getActiveSheet()->mergeCells('X1:Z1');
            $objPHPExcel->getActiveSheet()->mergeCells('AA1:AC1');
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Customer Sudsidiary Ledger (Sales Adjustment per Participant Name).xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Customer Sudsidiary Ledger (Sales Adjustment per Participant Name).xlsx"');
        // readfile($exportfilename);
    }

    public function export_cs_ledger_saledadj_rn(){
        $participant=$this->uri->segment(3);
        $referenceno=$this->uri->segment(4);
        $years=$this->uri->segment(5);
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Customer Sudsidiary Ledger (Sales Adjustment per Reference No).xlsx";
        $sql='';

        if($participant!='null' && !empty($participant)){
            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";
        }
        
        if($referenceno!='null' && !empty($referenceno)){
            $sql.= " reference_number='$referenceno' AND ";
        }

        if($years!='null' && !empty($years)){
            $sql.= " YEAR(due_date)='$years' AND ";
        }

        $query=substr($sql,0,-4);
        $cssa_qu = " saved = '1' AND ".$query;
        $sheetno=0;
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => border::BORDER_THIN
                )
            )
        );
        foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id=sah.sales_adjustment_id WHERE $cssa_qu GROUP BY reference_number ORDER BY reference_number ASC") AS $head){
            $count_collection = $this->super_model->count_custom_where("collection_details", "reference_no='$head->reference_number' AND settlement_id ='$head->short_name'");
            if($count_collection>0){
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($head->reference_number);
            foreach(range('A','AC') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A1', "Date");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C1', "Due Date");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E1', "Transaction No");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H1', "Participant Name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L1', "Description");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O1', "Vatable Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R1', "Zero-Rated Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U1', "Zero-Rated Ecozone");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X1', "Vat");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA1', "EWT");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB2', "Collection");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC2', "Balance");
            $objPHPExcel->getActiveSheet()->getStyle("A1:AC1")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("A2:AC2")->applyFromArray($styleArray);


            $num=3;
            $total_vatsales = array();
            $total_zrs = array();
            $total_zre = array();
            $total_vat = array();
            $total_ewt = array(); 

            $total_vatsales_c = array();
            $total_zrs_c = array();
            $total_zre_c = array();
            $total_vat_c = array();
            $total_ewt_c = array(); 

            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id=sah.sales_adjustment_id WHERE short_name = '$head->short_name' AND reference_number = '$head->reference_number' GROUP BY short_name,reference_number ORDER BY transaction_date ASC") AS $details){
            $or_no=$this->super_model->select_column_custom_where("collection_details","series_number","reference_no='$details->reference_number' AND settlement_id='$details->short_name'");
            $participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$details->billing_id);
            if(!empty($details->company_name) && date('Y',strtotime($details->create_date))==date('Y')){
                $comp_name=$details->company_name;
            }else{
                $comp_name=$participant_name;
            }
            $billing_date = date("M. d, Y",strtotime($details->billing_from))." - ".date("M. d, Y",strtotime($details->billing_to));
            //$tin=$this->super_model->select_column_where("participant","tin","billing_id",$details->billing_id);
            $refno=$this->super_model->select_column_where("sales_adjustment_head","reference_number","sales_adjustment_id",$details->sales_adjustment_id);
            if($details->reference_number==$refno){
                $sales_array_count = $this->get_count_salesadj_row($head->short_name,$details->reference_number);
                $collection_array_count= $this->get_count_collection_row($head->short_name,$details->reference_number);
                $max_merge_count = max(array_merge($sales_array_count,$collection_array_count));
                $sales_details = $this->get_salesadj($head->short_name,$details->reference_number,$max_merge_count);
                $collection_details = $this->get_collection($head->short_name,$details->reference_number,$max_merge_count);

                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $details->transaction_date);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $details->due_date);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, $details->reference_number);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, $comp_name);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, $billing_date);

                $o=$num;
                $p=$num;
                $r=$num;
                $r1=$num;
                $r2=$num;
                $r3=$num;
                $r4=$num;
                $sum_bill_vatsales = array();
                $sum_col_vatsales = array();
                $sum_bill_zrs = array();
                $sum_col_zrs = array();
                $sum_bill_zre = array();
                $sum_col_zre = array();
                $sum_bill_vat = array();
                $sum_col_vat = array();
                $sum_bill_ewt = array();
                $sum_col_ewt = array();
                foreach($sales_details AS $d){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$o,  $d['vatable_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R'.$o, $d['zero_rated_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U'.$o, $d['zero_rated_ecozones']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X'.$o, $d['vat_on_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA'.$o, $d['ewt']);
                    $total_vatsales[]=$d['vatable_sales'];
                    $total_zre[] = $d['zero_rated_sales'];
                    $total_zrs[] = $d['zero_rated_ecozones'];
                    $total_vat[] = $d['vat_on_sales'];
                    $total_ewt[] = $d['ewt'];
                    $sum_bill_vatsales[] = $d['vatable_sales'];
                    $sum_bill_zrs[] = $d['zero_rated_sales'];
                    $sum_bill_zre[] = $d['zero_rated_ecozones'];
                    $sum_bill_vat[] = $d['vat_on_sales'];
                    $sum_bill_ewt[] = $d['ewt'];
                    $billing_details[] = array(
                        'short_name'=>$head->short_name,
                        'reference_no'=>$details->reference_number,
                        'vatable_sales'=>$d['vatable_sales'],
                        'zero_rated_sales'=>$d['zero_rated_sales'],
                        'zero_rated_ecozones'=>$d['zero_rated_ecozones'],
                        'vat_on_sales'=>$d['vat_on_sales'],
                        'ewt'=>$d['ewt'],
                    );
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$o.":AC".$o)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.$o.":B".$o);
                    $objPHPExcel->getActiveSheet()->mergeCells('C'.$o.":D".$o);
                    $objPHPExcel->getActiveSheet()->mergeCells('E'.$o.":G".$o);
                    $objPHPExcel->getActiveSheet()->mergeCells('H'.$o.":K".$o);
                    $objPHPExcel->getActiveSheet()->mergeCells('L'.$o.":N".$o);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$o.":B".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$o.":D".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$o.":G".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$o.":N".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$o.":AC".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$o.":AC".$o)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$o.":L".$o)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                    $o++;
                }

                foreach($collection_details AS $c){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$p, $c['vatable_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S'.$p, $c['zero_rated_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V'.$p, $c['zero_rated_ecozones']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y'.$p, $c['vat_on_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB'.$p, $c['ewt']);
                    $total_vatsales_c[]=$c['vatable_sales'];
                    $total_zre_c[] = $c['zero_rated_sales'];
                    $total_zrs_c[] = $c['zero_rated_ecozones'];
                    $total_vat_c[] = $c['vat_on_sales'];
                    $total_ewt_c[] = $c['ewt'];
                    $sum_col_vatsales[]=$c['vatable_sales'];
                    $sum_col_zrs[] = $c['zero_rated_sales'];
                    $sum_col_zre[] = $c['zero_rated_ecozones'];
                    $sum_col_vat[] = $c['vat_on_sales'];
                    $sum_col_ewt[] = $c['ewt'];
                    $collection_details[] = array(
                        'short_name'=>$head->short_name,
                        'reference_no'=>$details->reference_number,
                        'vatable_sales'=>$c['vatable_sales'],
                        'zero_rated_sales'=>$c['zero_rated_sales'],
                        'zero_rated_ecozones'=>$c['zero_rated_ecozones'],
                        'vat_on_sales'=>$c['vat_on_sales'],
                        'ewt'=>$c['ewt'],
                    );
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p.":AC".$p)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.$p.":B".$p);
                    $objPHPExcel->getActiveSheet()->mergeCells('C'.$p.":D".$p);
                    $objPHPExcel->getActiveSheet()->mergeCells('E'.$p.":G".$p);
                    $objPHPExcel->getActiveSheet()->mergeCells('H'.$p.":K".$p);
                    $objPHPExcel->getActiveSheet()->mergeCells('L'.$p.":N".$p);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p.":B".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$p.":D".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$p.":G".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$p.":N".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$p.":AC".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$p.":AC".$p)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p.":L".$p)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                    $p++;
                }
                $sum_vatsales = $this->get_balance($sum_bill_vatsales, $sum_col_vatsales,$max_merge_count);
                $sum_zrs = $this->get_balance($sum_bill_zrs, $sum_col_zrs,$max_merge_count);
                $sum_zre = $this->get_balance($sum_bill_zre, $sum_col_zre,$max_merge_count);
                $sum_vat = $this->get_balance($sum_bill_vat, $sum_col_vat,$max_merge_count);
                $sum_ewt = $this->get_balance($sum_bill_ewt, $sum_col_ewt,$max_merge_count);
                foreach($sum_vatsales AS $b){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$r, $b['balance']);
                    $r++;
                }
                foreach($sum_zrs AS $b1){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T'.$r1, $b1['balance']);
                    $r1++;
                }
                foreach($sum_zre AS $b2){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W'.$r2, $b2['balance']);
                    $r2++;
                }
                foreach($sum_vat AS $b3){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z'.$r3, $b3['balance']);
                    $r3++;
                }
                foreach($sum_ewt AS $b4){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC'.$r4, $b4['balance']);
                    $r4++;
                }
                $objPHPExcel->getActiveSheet()->mergeCells('C'.$num.":D".$num);
                $objPHPExcel->getActiveSheet()->mergeCells('E'.$num.":G".$num);
                $objPHPExcel->getActiveSheet()->mergeCells('H'.$num.":K".$num);
                $objPHPExcel->getActiveSheet()->mergeCells('L'.$num.":N".$num);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$num.":D".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":G".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num.":N".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                $num+=$max_merge_count;
            }
        }
                $balance_vatsales= array_sum($total_vatsales) - array_sum($total_vatsales_c);
                $balance_zre= array_sum($total_zre) - array_sum($total_zre_c);
                $balance_zrs= array_sum($total_zrs) - array_sum($total_zrs_c);
                $balance_vat= array_sum($total_vat) - array_sum($total_vat_c);
                $balance_ewt= array_sum($total_ewt) - array_sum($total_ewt_c);
                $a = $num;
                $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AC".$a)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AC".$a)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('FFFCA5');
                $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AC".$a)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$a.":N".$a);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":AC".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":AC".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$a, "TOTAL: ");
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$a, array_sum($total_vatsales));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$a, array_sum($total_vatsales_c));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$a, $balance_vatsales);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R'.$a, array_sum($total_zrs));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S'.$a, array_sum($total_zrs_c));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T'.$a, $balance_zrs);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U'.$a, array_sum($total_zre));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V'.$a, array_sum($total_zre_c));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W'.$a, $balance_zre);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X'.$a, array_sum($total_vat));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y'.$a, array_sum($total_vat_c));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z'.$a, $balance_vat);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA'.$a, array_sum($total_ewt));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB'.$a, array_sum($total_ewt_c));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC'.$a, $balance_ewt);
                //$num--;
                $sheetno++;
            }
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle('A1:AC2')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:AC2')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('F1F1F1');
            $objPHPExcel->getActiveSheet()->getStyle('A1:AC1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A2:AC2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->mergeCells('A1:B2');
            $objPHPExcel->getActiveSheet()->mergeCells('C1:D2');
            $objPHPExcel->getActiveSheet()->mergeCells('E1:G2');
            $objPHPExcel->getActiveSheet()->mergeCells('H1:K2');
            $objPHPExcel->getActiveSheet()->mergeCells('L1:N2');
            $objPHPExcel->getActiveSheet()->mergeCells('O1:Q1');
            $objPHPExcel->getActiveSheet()->mergeCells('R1:T1');
            $objPHPExcel->getActiveSheet()->mergeCells('U1:W1');
            $objPHPExcel->getActiveSheet()->mergeCells('X1:Z1');
            $objPHPExcel->getActiveSheet()->mergeCells('AA1:AC1');
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Customer Sudsidiary Ledger (Sales Adjustment per Reference No).xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Customer Sudsidiary Ledger (Sales Adjustment per Reference No).xlsx"');
        // readfile($exportfilename);
    }

    public function get_count_salesadj_row($short_name, $reference_no){
        $count_vat=0;
        $count_zrs=0;
        $count_zre=0;
        $count_vos=0;
        $count_ewt=0;
        $count_array_sales=array();
        foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sah.sales_adjustment_id=sad.sales_adjustment_id WHERE short_name = '$short_name' AND reference_number='$reference_no'  AND saved!=0") AS $col){
            if($col->vatable_sales!=0){
                $count_vat++;
            }
            if($col->zero_rated_sales!=0){
                $count_zrs++;
            }
            if($col->zero_rated_ecozones!=0){
                $count_zre++;
            }
            if($col->vat_on_sales!=0){
                $count_vos++;
            }
            if($col->ewt!=0){
                $count_ewt++;
            }
          
        }

        $count_array_sales=array(
            "count_vat"=>$count_vat,
            "count_zrs"=>$count_zrs,
            "count_zre"=>$count_zre,
            "count_vos"=>$count_vos,
            "count_ewt"=>$count_ewt,
        );

        return $count_array_sales;
    }

    public function get_salesadj($short_name, $reference_no,$max){
       
        $array_sales=array();

       
            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sah.sales_adjustment_id=sad.sales_adjustment_id WHERE short_name = '$short_name' AND reference_number='$reference_no'  AND saved!=0 AND (vatable_sales!=0 OR zero_rated_sales!=0 OR zero_rated_ecozones!=0  OR vat_on_sales!=0 OR ewt!=0)") AS $col){
                $array_sales[] = array(
                    "short_name"=>$short_name,
                    "reference_no"=>$reference_no,
                    "vatable_sales"=>$col->vatable_sales,
                    "zero_rated_sales"=>$col->zero_rated_sales,
                    "zero_rated_ecozones"=>$col->zero_rated_ecozones,
                    "vat_on_sales"=>$col->vat_on_sales,
                    "ewt"=>$col->ewt,
                );
            
            }
            $add = $max - count($array_sales);
            for($x=1;$x<=$add;$x++){
                $array_sales[] = array(
                    "short_name"=>"",
                    "reference_no"=>"",
                    "vatable_sales"=>"",
                    "zero_rated_sales"=>"",
                    "zero_rated_ecozones"=>"",
                    "vat_on_sales"=>"",
                    "ewt"=>"",
                );
            }
        

        return $array_sales;
    }

    public function cs_ledger_purchaseadj()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        //$data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
        //$data['participant']=$this->super_model->custom_query("SELECT * FROM participant GROUP BY settlement_id");
        $data['reference_no']=$this->super_model->custom_query("SELECT DISTINCT reference_number FROM purchase_transaction_head WHERE reference_number!='' AND adjustment='1' AND saved='1'");
        $data['due_date']=$this->super_model->custom_query("SELECT DISTINCT due_date FROM purchase_transaction_head WHERE reference_number!='' AND adjustment='1' AND saved='1' ORDER BY due_date ASC");
        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant WHERE participant_name != '' GROUP BY tin ORDER BY participant_name ASC");
        $participant=$this->uri->segment(3);
        $referenceno=$this->uri->segment(4);
        $years=$this->uri->segment(5);
        $from=$this->uri->segment(6);
        $to=$this->uri->segment(7);
        $part=$this->super_model->select_column_where("participant","participant_name","tin",$participant);
        $data['participants'] = $part;
        $data['referenceno'] = $referenceno;
        $data['year'] = $years;
        $data['from'] = $from;
        $data['to'] = $to;
        $sql='';
        
        if($participant!='null' && !empty($participant)){
            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant' GROUP BY settlement_id") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";
        }
        
        if($referenceno!='null' && !empty($referenceno)){
            $sql.= " reference_number='$referenceno' AND ";
        }

        if($years!='null' && !empty($years)){
            $sql.= " YEAR(due_date)='$years' AND ";
        }

        if(($from!='null' && !empty($from)) && ($to!='null' && !empty($to))){
            $sql.= " due_date BETWEEN '$from' AND '$to' AND "; 
        } 

        $query=substr($sql,0,-4);
        $cs_qu = " saved = '1' AND adjustment='1' AND ".$query;
        $data['csledger']=array();
        $shortlast="";
        $data['bal_amountarr']=0;
        $bal_amountarr=array();
        $data['bal_vatonsalesarr']=0;
        $bal_vatonsalesarr=array();
        $data['bal_ewtarr']=0;
        $bal_ewtarr=array();
        $data['bal_camountarr']=0;
        $bal_camountarr=array();
        $data['bal_cvatonsal_amountarr']=0;
        $bal_cvatonsal_amountarr=array();
        $data['bal_cewt_amountarr']=0;
        $bal_cewt_amountarr=array();
        $data['balance_vatsalarr']=0;
        $balance_vatsalarr=array();
        $data['balance_vatonsalesarr']=0;
        $balance_vatonsalesarr=array();
        $data['balance_ewtarr']=0;
        $balance_ewtarr=array();
        if(!empty($query)){
            $x=0;
            foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_details std INNER JOIN purchase_transaction_head sth ON std.purchase_id=sth.purchase_id WHERE $cs_qu GROUP BY short_name,reference_number ORDER BY transaction_date ASC") AS $cs){
                //$vatable_sales = $this->super_model->select_sum_where("sales_transaction_details","vatable_sales","sales_id='$cs->sales_id' AND short_name='$cs->short_name'");
                $vatable_sales = $this->super_model->select_sum_join("vatables_purchases","purchase_transaction_details","purchase_transaction_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'purchase_id');
                $vat_on_sales = $this->super_model->select_sum_join("vat_on_purchases","purchase_transaction_details","purchase_transaction_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'purchase_id');
                $ewt_sales = $this->super_model->select_sum_join("ewt","purchase_transaction_details","purchase_transaction_head","transaction_date='$cs->transaction_date' AND short_name='$cs->short_name'",'purchase_id');
                $payment_id=$this->super_model->select_column_where('payment_head','payment_id','purchase_id',$cs->purchase_id);
                $count_payment = $this->super_model->count_custom_where("payment_details", "purchase_details_id='$cs->purchase_detail_id' AND short_name='$cs->short_name'");
                $amount=$this->purchase_adjustment_display($cs->short_name,$cs->reference_number,'vatables_purchases')."<span class='td-30 td-yellow'> Total: ".number_format($this->purchase_adjustment_sum($cs->short_name,$cs->reference_number,'vatables_purchases'),2)."</span>";
                $vatonsales=$this->purchase_adjustment_display($cs->short_name,$cs->reference_number,'vat_on_purchases')."<span class='td-30 td-yellow'> Total: ".number_format($this->purchase_adjustment_sum($cs->short_name,$cs->reference_number,'vat_on_purchases'),2)."</span>";
                $ewt=$this->purchase_adjustment_display($cs->short_name,$cs->reference_number,'ewt')."<span class='td-30 td-yellow'> Total: ".number_format($this->purchase_adjustment_sum($cs->short_name,$cs->reference_number,'ewt'),2)."</span>";
                $id=array();

                //Purchases Balance
                $bal_amount=$this->purchase_adjustment_sum($cs->short_name,$cs->reference_number,'vatables_purchases');
                $bal_amountarr[]=$this->purchase_adjustment_sum($cs->short_name,$cs->reference_number,'vatables_purchases');
                $bal_vatonsales=$this->purchase_adjustment_sum($cs->short_name,$cs->reference_number,'vat_on_purchases');
                $bal_vatonsalesarr[]=$this->purchase_adjustment_sum($cs->short_name,$cs->reference_number,'vat_on_purchases');
                $bal_ewt=$this->purchase_adjustment_sum($cs->short_name,$cs->reference_number,'ewt');
                $bal_ewtarr[]=$this->purchase_adjustment_sum($cs->short_name,$cs->reference_number,'ewt');
                if($count_payment>0){
                    $camount='';
                    $czerorated='';
                    $czeroratedeco='';
                    $cvat='';
                    $cewt='';
                    foreach($this->super_model->select_custom_where("payment_details","payment_id='$payment_id' AND short_name ='$cs->short_name'") AS $c){
                        $camount.=$this->payment_display($c->payment_id,$c->short_name,'purchase_amount');
                        $cvat.=$this->payment_display($c->payment_id,$c->short_name,'vat');
                        $cewt.=$this->payment_display($c->payment_id,$c->short_name,'ewt');
                    }
                    //Payment Balance
                    $bal_camount=$this->payment_sum($cs->short_name,$payment_id,'purchase_amount');
                    $bal_camountarr[]=$this->payment_sum($cs->short_name,$payment_id,'purchase_amount');
                    $bal_cvatonsal_amount=$this->payment_sum($cs->short_name,$payment_id,'vat');
                    $bal_cvatonsal_amountarr[]=$this->payment_sum($cs->short_name,$payment_id,'vat');
                    $bal_cewt_amount=$this->payment_sum($cs->short_name,$payment_id,'ewt');
                    $bal_cewt_amountarr[]=$this->payment_sum($cs->short_name,$payment_id,'ewt');
                    
                    //Balance
                    $balance_vatsal=$bal_amount-$bal_camount;
                    $balance_vatsalarr[]=$balance_vatsal;
                    $balance_vatonsales=$bal_vatonsales-$bal_cvatonsal_amount;
                    $balance_vatonsalesarr[]=$balance_vatonsales;
                    $balance_ewt=$bal_ewt-$bal_cewt_amount;
                    $balance_ewtarr[]=$balance_ewt;

                    $cvatsal_amount=$camount." <span class='td-30 td-yellow'> Total: ".number_format((float)$this->payment_sum($cs->short_name,$payment_id,'purchase_amount'),2)."</span>";
                    $cvatonsal_amount=$cvat." <span class='td-30 td-yellow'> Total: ".number_format((float)$this->payment_sum($cs->short_name,$payment_id,'vat'),2)."</span>";
                    $cewt_amount=$cewt." <span class='td-30 td-yellow'> Total: ".number_format((float)$this->payment_sum($cs->short_name,$payment_id,'ewt'),2)."</span>";
                    $create_date = $this->super_model->select_column_where("purchase_transaction_head", "create_date", "purchase_id", $cs->purchase_id);
                    $company_name=$this->super_model->select_column_where("purchase_transaction_details", "company_name", "purchase_detail_id", $cs->purchase_detail_id);
                    if(!empty($company_name) && date('Y',strtotime($create_date))==date('Y')){
                        $comp_name=$company_name;
                    }else{
                        $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $cs->billing_id);
                    }
                    $data['csledger'][]=array(
                        "purchase_id"=>$cs->purchase_id,
                        "payment_id"=>$payment_id,
                        "count_payment"=>$count_payment,
                        "item_no"=>$cs->item_no,
                        "date"=>$cs->transaction_date,
                        "due_date"=>$cs->due_date,
                        "short_name"=>$cs->short_name,
                        "reference_no"=>$cs->reference_number,
                        "company_name"=>$comp_name,
                        "billing_from"=>$cs->billing_from,
                        "billing_to"=>$cs->billing_to,
                        "vatable_sales_sum"=>$vatable_sales,
                        "vatable_sales"=>$amount,
                        "cvatsal_amount"=>$cvatsal_amount,
                        "balance_vatsal"=>number_format($balance_vatsal,2),
                        "vat_on_sales"=>$vatonsales,
                        "vat_on_sales_sum"=>$vat_on_sales,
                        "cvatonsal_amount"=>$cvatonsal_amount,
                        "balance_vatonsales"=>number_format($balance_vatonsales,2),
                        "ewt"=>$ewt,
                        "ewt_sum"=>$ewt_sales,
                        "cewt_amount"=>$cewt_amount,
                        "balance_ewt"=>number_format($balance_ewt,2),
                    );
                    $x++;
                }
            }
            $result_balance_vatsalarr = array_unique($balance_vatsalarr);
            $data['balance_vatsalarr']=array_sum($result_balance_vatsalarr);
            $result_balance_vatonsalesarr = array_unique($balance_vatonsalesarr);
            $data['balance_vatonsalesarr']=array_sum($result_balance_vatonsalesarr);
            $result_balance_ewtarr = array_unique($balance_ewtarr);
            $data['balance_ewtarr']=array_sum($result_balance_ewtarr);
        }
        $this->load->view('reports/cs_ledger_purchaseadj', $data);
        $this->load->view('template/footer');
    }

    public function export_cs_ledger_purchaseadj(){
        $due_date=$this->uri->segment(3);
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Customer Sudsidiary Ledger (Purchase Adjustment).xlsx";
        $sql='';

        if($due_date!='null' && !empty($due_date)){
            $sql.= " due_date = '$due_date' AND ";
        }

        $query=substr($sql,0,-4);
        $cslpa_qu = " saved = '1' AND adjustment='1' AND ".$query;
        $sheetno=0;
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN
                    )
                )
            );
            foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_details ptd INNER JOIN purchase_transaction_head pth ON ptd.purchase_id=pth.purchase_id WHERE $cslpa_qu GROUP BY reference_number ORDER BY reference_number ASC") AS $head){
            $count_payment = $this->super_model->count_custom_where("payment_details", "purchase_details_id='$head->purchase_detail_id' AND short_name='$head->short_name'");
            if($count_payment>0){
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($head->reference_number);
            foreach(range('A','W') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A1', "Date");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C1', "Due Date");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E1', "Transaction No");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H1', "Participant Name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L1', "Description");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O1', "Vatable Purchase");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P2', "Payment");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R1', "Vat");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S2', "Payment");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T2', "Balance");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U1', "EWT");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U2', "Billing");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V2', "Payment");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W2', "Balance");
            // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X1', "Vat");
            // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X2', "Billing");
            // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y2', "Collection");
            // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z2', "Balance");
            // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA1', "EWT");
            // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA2', "Billing");
            // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB2', "Collection");
            // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC2', "Balance");
            $objPHPExcel->getActiveSheet()->getStyle("A1:W1")->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle("A2:W2")->applyFromArray($styleArray);


            $num=3;
            $total_vatsales = array();
            $total_vat = array();
            $total_ewt = array(); 

            $total_vatsales_c = array();
            $total_vat_c = array();
            $total_ewt_c = array(); 
            foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_details ptd INNER JOIN purchase_transaction_head pth ON ptd.purchase_id=pth.purchase_id WHERE reference_number = '$head->reference_number' AND reference_number = '$head->reference_number' GROUP BY short_name,reference_number ORDER BY transaction_date ASC") AS $details){
           
            $participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$details->billing_id);
            if(!empty($details->company_name) && date('Y',strtotime($details->create_date))==date('Y')){
                $comp_name=$details->company_name;
            }else{
                $comp_name=$participant_name;
            }
            $billing_date = date("M. d, Y",strtotime($details->billing_from))." - ".date("M. d, Y",strtotime($details->billing_to));
            $reference_no=$this->super_model->select_column_where("purchase_transaction_head","reference_number","purchase_id",$details->purchase_id);
            if($details->reference_number==$reference_no){
                $payment_id=$this->super_model->select_column_where('payment_details','payment_id','purchase_details_id',$details->purchase_detail_id);
                echo $payment_id."<br>";
                $purchase_array_count = $this->get_count_purchase_row($details->short_name,$details->reference_number);
                $payment_array_count= $this->get_count_payment_row($details->short_name,$payment_id);
                $max_merge_count = max(array_merge($purchase_array_count,$payment_array_count));
                $sales_details = $this->get_purchase($details->short_name,$details->reference_number,$max_merge_count);
                $payment_details = $this->get_payment($details->short_name,$payment_id,$max_merge_count);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $details->transaction_date);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $details->due_date);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, $head->reference_number);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, $comp_name);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, $billing_date);
                $o=$num;
                $p=$num;
                $r=$num;
                $r1=$num;
                $r2=$num;
                $r3=$num;
                $r4=$num;
                $sum_bill_vatsales = array();
                $sum_col_vatsales = array();
                $sum_bill_vat = array();
                $sum_col_vat = array();
                $sum_bill_ewt = array();
                $sum_col_ewt = array();
                foreach($sales_details AS $d){
                    
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$o,  $d['vatables_purchases']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R'.$o, $d['vat_on_purchases']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U'.$o, $d['ewt']);

                    $total_vatsales[]=$d['vatables_purchases'];
                    $total_vat[] = $d['vat_on_purchases'];
                    $total_ewt[] = $d['ewt'];

                    $sum_bill_vatsales[] = $d['vatables_purchases'];
                    $sum_bill_vat[] = $d['vat_on_purchases'];
                    $sum_bill_ewt[] = $d['ewt'];


                    $billing_details[] = array(
                        'short_name'=>$head->short_name,
                        'reference_no'=>$details->reference_number,
                        'vatables_purchases'=>$d['vatables_purchases'],
                        'vat_on_purchases'=>$d['vat_on_purchases'],
                        'ewt'=>$d['ewt'],
                    );

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$o.":W".$o)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.$o.":B".$o);
                    $objPHPExcel->getActiveSheet()->mergeCells('C'.$o.":D".$o);
                    $objPHPExcel->getActiveSheet()->mergeCells('E'.$o.":G".$o);
                    $objPHPExcel->getActiveSheet()->mergeCells('H'.$o.":K".$o);
                    $objPHPExcel->getActiveSheet()->mergeCells('L'.$o.":N".$o);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$o.":B".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$o.":D".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$o.":G".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$o.":N".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$o.":W".$o)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$o.":W".$o)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$o.":L".$o)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                    $o++;
                }

                foreach($payment_details AS $c){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$p, $c['vatables_purchases']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S'.$p, $c['vat_on_purchases']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V'.$p, $c['ewt']);
                    $total_vatsales_c[]=$c['vatables_purchases'];
                    $total_vat_c[] = $c['vat_on_purchases'];
                    $total_ewt_c[] = $c['ewt'];
                    $sum_col_vatsales[]=$c['vatables_purchases'];
                    $sum_col_vat[] = $c['vat_on_purchases'];
                    $sum_col_ewt[] = $c['ewt'];                    
                    $payment_details[] = array(
                        'short_name'=>$head->short_name,
                        'reference_no'=>$details->reference_number,
                        'vatables_purchases'=>$c['vatables_purchases'],
                        'vat_on_purchases'=>$c['vat_on_purchases'],
                        'ewt'=>$c['ewt'],
                    );
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p.":W".$p)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.$p.":B".$p);
                    $objPHPExcel->getActiveSheet()->mergeCells('C'.$p.":D".$p);
                    $objPHPExcel->getActiveSheet()->mergeCells('E'.$p.":G".$p);
                    $objPHPExcel->getActiveSheet()->mergeCells('H'.$p.":K".$p);
                    $objPHPExcel->getActiveSheet()->mergeCells('L'.$p.":N".$p);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p.":B".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$p.":D".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$p.":G".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$p.":N".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$p.":W".$p)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$p.":W".$p)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p.":L".$p)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                    $p++;
                }
                $sum_vatsales = $this->get_balance($sum_bill_vatsales, $sum_col_vatsales,$max_merge_count);
                $sum_vat = $this->get_balance($sum_bill_vat, $sum_col_vat,$max_merge_count);
                $sum_ewt = $this->get_balance($sum_bill_ewt, $sum_col_ewt,$max_merge_count);

                foreach($sum_vatsales AS $b){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$r, $b['balance']);
                    $r++;
                }
                foreach($sum_vat AS $b1){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T'.$r1, $b1['balance']);
                    $r1++;
                }
                foreach($sum_ewt AS $b2){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W'.$r2, $b2['balance']);
                    $r2++;
                }
              
                $objPHPExcel->getActiveSheet()->mergeCells('C'.$num.":D".$num);
                $objPHPExcel->getActiveSheet()->mergeCells('E'.$num.":G".$num);
                $objPHPExcel->getActiveSheet()->mergeCells('H'.$num.":K".$num);
                $objPHPExcel->getActiveSheet()->mergeCells('L'.$num.":N".$num);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$num.":D".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":G".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num.":N".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                $num+=$max_merge_count;
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$num, $amount);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$num, $cvatsal_amount);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$num, $balance_vatsal);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R'.$num, $vatonsales);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S'.$num, $cvatonsal_amount);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T'.$num, $balance_vatonsales);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U'.$num, $ewt);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V'.$num, $cewt_amount);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W'.$num, $balance_ewt);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X'.$num, $vatonsales);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y'.$num, $cvatonsal_amount);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z'.$num, $balance_vatonsales);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA'.$num, $ewt);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB'.$num, $cewt_amount);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC'.$num, $balance_ewt);

                //  $objPHPExcel->getActiveSheet()->mergeCells('A1:B2');
                //  $objPHPExcel->getActiveSheet()->mergeCells('A'.$num.":B".$num);
                //  $objPHPExcel->getActiveSheet()->mergeCells('C1:D2');
                //  $objPHPExcel->getActiveSheet()->mergeCells('C'.$num.":D".$num);
                //  $objPHPExcel->getActiveSheet()->mergeCells('E1:G2');
                //  $objPHPExcel->getActiveSheet()->mergeCells('E'.$num.":G".$num);
                //  $objPHPExcel->getActiveSheet()->mergeCells('H1:K2');
                //  $objPHPExcel->getActiveSheet()->mergeCells('H'.$num.":K".$num);
                //  $objPHPExcel->getActiveSheet()->mergeCells('L1:N2');
                //  $objPHPExcel->getActiveSheet()->mergeCells('L'.$num.":N".$num);
                //  $objPHPExcel->getActiveSheet()->mergeCells('O1:Q1');
                //  $objPHPExcel->getActiveSheet()->mergeCells('R1:T1');
                //  $objPHPExcel->getActiveSheet()->mergeCells('U1:W1');
                //  $objPHPExcel->getActiveSheet()->mergeCells('X1:Z1');
                //  $objPHPExcel->getActiveSheet()->mergeCells('AA1:AC1');
                //  $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getAlignment()->setWrapText(true);
                //  $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('P'.$num)->getAlignment()->setWrapText(true);
                //  $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('Q'.$num)->getAlignment()->setWrapText(true);
                //  $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('R'.$num)->getAlignment()->setWrapText(true);
                //  $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('S'.$num)->getAlignment()->setWrapText(true);
                //  $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('T'.$num)->getAlignment()->setWrapText(true);
                //  $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('U'.$num)->getAlignment()->setWrapText(true);
                //  $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('V'.$num)->getAlignment()->setWrapText(true);
                //  $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('W'.$num)->getAlignment()->setWrapText(true);
                //  $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
                 // $objPHPExcel->getActiveSheet()->getStyle('X'.$num)->getAlignment()->setWrapText(true);
                 // $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
                 // $objPHPExcel->getActiveSheet()->getStyle('Y'.$num)->getAlignment()->setWrapText(true);
                 // $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
                 // $objPHPExcel->getActiveSheet()->getStyle('Z'.$num)->getAlignment()->setWrapText(true);
                 // $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
                 // $objPHPExcel->getActiveSheet()->getStyle('AA'.$num)->getAlignment()->setWrapText(true);
                 // $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
                 // $objPHPExcel->getActiveSheet()->getStyle('AB'.$num)->getAlignment()->setWrapText(true);
                 // $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
                 // $objPHPExcel->getActiveSheet()->getStyle('AC'.$num)->getAlignment()->setWrapText(true);
                 // $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('A1:W2')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                //  $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                //  $objPHPExcel->getActiveSheet()->getStyle('C'.$num.":D".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                //  $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":G".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                //  $objPHPExcel->getActiveSheet()->getStyle('L'.$num.":N".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                //  $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":W".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                //  $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":W".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                //  $objPHPExcel->getActiveSheet()->getStyle('A1:W2')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('F1F1F1');
                //  $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                //  $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->applyFromArray($styleArray);
                //  $objPHPExcel->getActiveSheet()->getStyle('A1:W1')->getFont()->setBold(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('A2:W2')->getFont()->setBold(true);
                //$num++;
            }
        }
                $balance_vatsales= array_sum($total_vatsales) - array_sum($total_vatsales_c);
                $balance_vat= array_sum($total_vat) - array_sum($total_vat_c);
                $balance_ewt= array_sum($total_ewt) - array_sum($total_ewt_c);
                $a = $num;
                $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":W".$a)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":W".$a)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('FFFCA5');
                $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":W".$a)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$a.":N".$a);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":W".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":W".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$a, "TOTAL: ");
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$a, array_sum($total_vatsales));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$a, array_sum($total_vatsales_c));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$a, $balance_vatsales);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R'.$a, array_sum($total_vat));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S'.$a, array_sum($total_vat_c));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T'.$a, $balance_vat);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U'.$a, array_sum($total_ewt));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V'.$a, array_sum($total_ewt_c));
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W'.$a, $balance_ewt);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X'.$a, array_sum($bal_vatonsalesarr));
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y'.$a, array_sum($bal_cvatonsal_amountarr));
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z'.$a, array_sum($balance_vatonsalesarr));
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA'.$a, array_sum($bal_ewtarr));
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB'.$a, array_sum($bal_cewt_amountarr));
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC'.$a, array_sum($balance_ewtarr));
                //$num--;
                $sheetno++;
            }
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle('A1:W2')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:W2')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('F1F1F1');
            $objPHPExcel->getActiveSheet()->getStyle('A1:W1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A2:W2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->mergeCells('A1:B2');
            $objPHPExcel->getActiveSheet()->mergeCells('C1:D2');
            $objPHPExcel->getActiveSheet()->mergeCells('E1:G2');
            $objPHPExcel->getActiveSheet()->mergeCells('H1:K2');
            $objPHPExcel->getActiveSheet()->mergeCells('L1:N2');
            $objPHPExcel->getActiveSheet()->mergeCells('O1:Q1');
            $objPHPExcel->getActiveSheet()->mergeCells('R1:T1');
            $objPHPExcel->getActiveSheet()->mergeCells('U1:W1');
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Customer Sudsidiary Ledger (Purchase Adjustment).xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        ob_get_clean();
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Customer Sudsidiary Ledger (Purchase Adjustment).xlsx"');
        // readfile($exportfilename);
    }

    public function get_count_purchase_row($short_name, $reference_no){
        $count_vat=0;
        $count_zrs=0;
        $count_zre=0;
        $count_vos=0;
        $count_ewt=0;
        $count_array_sales=array();
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_details ptd INNER JOIN purchase_transaction_head pth ON pth.purchase_id=ptd.purchase_id WHERE short_name = '$short_name' AND reference_number='$reference_no'  AND saved!=0 AND adjustment='1'") AS $col){
            if($col->vatables_purchases!=0){
                $count_vat++;
            }
            if($col->vat_on_purchases!=0){
                $count_vos++;
            }
            if($col->ewt!=0){
                $count_ewt++;
            }
          
        }

        $count_array_sales=array(
            "count_vat"=>$count_vat,
            "count_vos"=>$count_vos,
            "count_ewt"=>$count_ewt,
        );

        return $count_array_sales;
    }

    public function get_count_payment_row($short_name, $payment_id){
        $count_col_vat=0;
        $count_col_vos=0;
        $count_col_ewt=0;
        $count_array_sales=array();
        foreach($this->super_model->custom_query("SELECT pd.* FROM payment_details pd INNER JOIN payment_head ph ON ph.payment_id=pd.payment_id WHERE short_name = '$short_name' AND pd.payment_id = '$payment_id' AND (purchase_amount!=0 OR vat!=0 OR ewt!=0)") AS $col){
            if($col->purchase_amount!=0){
                $count_col_vat++;
            }
            if($col->vat=0){
                $count_col_vos++;
            }
            if($col->ewt!=0){
                $count_col_ewt++;
            }
          
        }

        $count_array_collection=array(
            "count_col_vat"=>$count_col_vat,
            "count_col_vos"=>$count_col_vos,
            "count_col_ewt"=>$count_col_ewt,
        );

        return $count_array_collection;
    }


    public function get_purchase($short_name, $reference_no,$max){
        $array_sales=array();
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_details ptd INNER JOIN purchase_transaction_head pth ON pth.purchase_id=ptd.purchase_id WHERE short_name = '$short_name' AND reference_number='$reference_no'  AND saved!=0 AND adjustment='1' AND (vatables_purchases!=0 OR vat_on_purchases!=0 OR ewt!=0)") AS $col){
            $array_sales[] = array(
                "short_name"=>$short_name,
                "reference_no"=>$reference_no,
                "vatables_purchases"=>$col->vatables_purchases,
                "vat_on_purchases"=>$col->vat_on_purchases,
                "ewt"=>$col->ewt,
            );
        
        }
        $add = $max - count($array_sales);
        for($x=1;$x<=$add;$x++){
            $array_sales[] = array(
                "short_name"=>"",
                "reference_no"=>"",
                "vatables_purchases"=>"",
                "vat_on_purchases"=>"",
                "ewt"=>"",
            );
        }
        return $array_sales;
    }

    public function get_payment($short_name, $payment_id,$max){      
        $array_collection=array();
        foreach($this->super_model->custom_query("SELECT pd.* FROM payment_details pd INNER JOIN payment_head ph ON pd.payment_id=ph.payment_id WHERE short_name = '$short_name' AND pd.payment_id = '$payment_id' AND (purchase_amount!=0)") AS $col){
            $array_collection[] = array(
                "short_name"=>$short_name,
                "vatables_purchases"=>$col->purchase_amount,
                "vat_on_purchases"=>$col->vat,
                "ewt"=>$col->ewt,
            );
        }
        $add = $max - count($array_collection);
        for($x=1;$x<=$add;$x++){
            $array_collection[] = array(
                "short_name"=>"",
                "vatables_purchases"=>"",
                "vat_on_purchases"=>"",
                "ewt"=>"",
            );
        }
        return $array_collection;
    }

    public function ss_ledger(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        //$data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant GROUP BY settlement_id");
        $participant=$this->uri->segment(3);
        $date_from=$this->uri->segment(4);
        $date_to=$this->uri->segment(5);
        $part=$this->super_model->select_column_where("participant","participant_name","settlement_id",$participant);
        $data['part'] = $part;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $sql='';
        if($date_from!='null' && $date_to != 'null'){
            $sql.= "billing_from >= '$date_from' AND billing_to <= '$date_to' AND "; 
        } if($participant!='null'){
            $sql.= "billing_id = '$participant' AND ";
        }

        $query=substr($sql,0,-4);
        $ss_qu = "saved = '1' AND ".$query;
        $data['ssledger']=array();

        $data['total_vatable_purchases']=0;
        $total_vatable_purchases=array();
        $data['total_purchase_amount']=0;
        $total_purchase_amount=array();
        $data['total_vatable_balance']=0;
        $total_vatable_balance=array();

        $data['total_zero_rated']=0;
        $total_zero_rated=array();
        $data['total_p_zero_rated']=0;
        $total_p_zero_rated=array();
        $data['total_zero_rated_balance']=0;
        $total_zero_rated_balance=array();

        $data['total_zero_ecozones']=0;
        $total_zero_ecozones=array();
        $data['total_p_zero_ecozones']=0;
        $total_p_zero_ecozones=array();
        $data['total_zero_ecozones_balance']=0;
        $total_zero_ecozones_balance=array();

        $data['total_vat']=0;
        $total_vat=array();
        $data['total_p_vat']=0;
        $total_p_vat=array();
        $data['total_vat_balance']=0;
        $total_vat_balance=array();

        $data['total_ewt']=0;
        $total_ewt=array();
        $data['total_p_ewt']=0;
        $total_p_ewt=array();
        $data['total_ewt_balance']=0;
        $total_ewt_balance=array();
        /*foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pth INNER JOIN purchase_transaction_details ptd ON pth.purchase_id=ptd.purchase_id WHERE saved='1' $query") AS $b){
                foreach($this->super_model->custom_query("SELECT * FROM payment_head ph INNER JOIN payment_details pd ON ph.payment_id=pd.payment_id AND ph.purchase_id='$b->purchase_id' AND pd.purchase_details_id='$b->purchase_detail_id'") AS $c){*/
       foreach($this->super_model->select_innerjoin_where("purchase_transaction_details","purchase_transaction_head", $ss_qu,"purchase_id","short_name") AS $ss){
                $vatables_purchases = $this->super_model->select_sum_where("purchase_transaction_details","vatables_purchases","purchase_id='$ss->purchase_id' AND short_name='$ss->short_name'");
                $zero_rated_purchases = $this->super_model->select_sum_where("purchase_transaction_details","zero_rated_purchases","purchase_id='$ss->purchase_id' AND short_name='$ss->short_name'");
                $zero_rated_ecozones = $this->super_model->select_sum_where("purchase_transaction_details","zero_rated_ecozones","purchase_id='$ss->purchase_id' AND short_name='$ss->short_name'");
                $vat_on_purchases = $this->super_model->select_sum_where("purchase_transaction_details","vat_on_purchases","purchase_id='$ss->purchase_id' AND short_name='$ss->short_name'");
                $ewt_purchases = $this->super_model->select_sum_where("purchase_transaction_details","ewt","purchase_id='$ss->purchase_id' AND short_name='$ss->short_name'");

                $payment_id=$this->super_model->select_column_where("payment_head","payment_id","purchase_id",$ss->purchase_id);
                $purchase_mode=$this->super_model->select_column_where("payment_details","purchase_mode","purchase_details_id",$ss->purchase_detail_id);
                $purchase_amount=$this->super_model->select_sum_where("payment_details","purchase_amount","payment_id ='$payment_id' AND short_name='$ss->short_name'");
                $vat= $this->super_model->select_sum_where("payment_details","vat","payment_id ='$payment_id' AND short_name='$ss->short_name'");
                $ewt= $this->super_model->select_sum_where("payment_details","ewt","payment_id ='$payment_id' AND short_name='$ss->short_name'");

                //$company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$ss->billing_id);
                if(!empty($ss->company_name) && date('Y',strtotime($ss->create_date))==date('Y')){
                    $comp_name=$ss->company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $ss->billing_id);
                }
                $count_payment = $this->super_model->count_custom_where("payment_details", "purchase_details_id ='$ss->purchase_detail_id'");

            if($count_payment>0){

            if($purchase_mode=='Vatable Purchase'){
                $vatable_purchase=$purchase_amount;
                $zero_rated='0.00';
                $rated_ecozones='0.00';
            }else if($purchase_mode=='Zero Rated Purchase'){
                $vatable_purchase='0.00';
                $zero_rated=$purchase_amount;
                $rated_ecozones='0.00';
            }else if($purchase_mode=='Zero Rated Ecozones'){
                $vatable_purchase='0.00';
                $zero_rated='0.00';
                $rated_ecozones=$purchase_amount;
            }

                $vatablebalance=$vatables_purchases - $vatable_purchase;
                $zerobalance=$zero_rated_purchases - $zero_rated;
                $zeroecobalance=$zero_rated_ecozones - $rated_ecozones;
                $vatbalance=$vat_on_purchases - $vat;
                $ewtbalance=$ewt_purchases - $ewt;

                $total_vatable_purchases[]=$vatables_purchases;
                $total_purchase_amount[]=$vatable_purchase;
                $total_vatable_balance[]=$vatablebalance;

                $total_zero_rated[]=$zero_rated_purchases;
                $total_p_zero_rated[]=$zero_rated;
                $total_zero_rated_balance[]=$zerobalance;

                $total_zero_ecozones[]=$zero_rated_ecozones;
                $total_p_zero_ecozones[]=$rated_ecozones;
                $total_zero_ecozones_balance[]=$zeroecobalance;
                
                $total_vat[]=$vat_on_purchases;
                $total_p_vat[]=$vat;
                $total_vat_balance[]=$vatbalance;

                $total_ewt[]=$ewt_purchases;
                $total_p_ewt[]=$ewt;
                $total_ewt_balance[]=$ewtbalance;

                $data['ssledger'][]=array(
                    "date"=>$ss->transaction_date,
                    "company_name"=>$comp_name,
                    "billing_from"=>$ss->billing_from,
                    "billing_to"=>$ss->billing_to,
                    "vatables_purchases"=>$vatables_purchases,
                    "purchase_amount"=>$vatable_purchase,
                    "zero_rated_purchases"=>$zero_rated_purchases,
                    "zero_rated"=>$zero_rated,
                    "zero_rated_ecozones"=>$zero_rated_ecozones,
                    "rated_ecozones"=>$rated_ecozones,
                    "vat_on_purchases"=>$vat_on_purchases,
                    "vat"=>$vat,
                    "ewt"=>$ewt_purchases,
                    "p_ewt"=>$ewt,
                    "vatable_balance"=>$vatablebalance,
                    "zerorated_balance"=>$zerobalance,
                    "ratedecozones_balance"=>$zeroecobalance,
                    "vat_balance"=>$vatbalance,
                    "ewt_balance"=>$ewtbalance,
                );
            }
        }
        $data['total_vatable_purchases']=array_sum($total_vatable_purchases);
        $data['total_purchase_amount']=array_sum($total_purchase_amount);
        $data['total_vatable_balance']=array_sum($total_vatable_balance);

        $data['total_zero_rated']=array_sum($total_zero_rated);
        $data['total_p_zero_rated']=array_sum($total_p_zero_rated);
        $data['total_zero_rated_balance']=array_sum($total_zero_rated_balance);

        $data['total_zero_ecozones']=array_sum($total_zero_ecozones);
        $data['total_p_zero_ecozones']=array_sum($total_p_zero_ecozones);
        $data['total_zero_ecozones_balance']=array_sum($total_zero_ecozones_balance);

        $data['total_vat']=array_sum($total_vat);
        $data['total_p_vat']=array_sum($total_p_vat);
        $data['total_vat_balance']=array_sum($total_vat_balance);

        $data['total_ewt']=array_sum($total_ewt);
        $data['total_p_ewt']=array_sum($total_p_ewt);
        $data['total_ewt_balance']=array_sum($total_ewt_balance);

        $this->load->view('reports/ss_ledger',$data);
        $this->load->view('template/footer');
    }


    public function ignore_or(){
        $or_no=$this->input->post('or_no');
        $participant=$this->input->post('participant');
        $date_from=$this->input->post('date_from');
        $date_to=$this->input->post('date_to');
        $now = date("Y-m-d H:i:s");
        $ignore_or = array(
           "or_no"=>$or_no,
           "remarks"=>'Ignored',
           "create_date"=>$now,
           "user_id"=>$_SESSION['user_id'],
        );
        $this->super_model->insert_into("or_remarks", $ignore_or);
    }

    public function cancel_or(){
        $or_no=$this->input->post('or_no');
        $participant=$this->input->post('participant');
        $date_from=$this->input->post('date_from');
        $date_to=$this->input->post('date_to');
        $now = date("Y-m-d H:i:s");
        $cancel_or = array(
           "or_no"=>$or_no,
           "remarks"=>'Cancelled',
           "create_date"=>$now,
           "user_id"=>$_SESSION['user_id'],
        );
        $this->super_model->insert_into("or_remarks", $cancel_or);
    }


    public function or_summary(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $series_number=array();
        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant GROUP BY settlement_id");
        $date_from=$this->uri->segment(3);
        $date_to=$this->uri->segment(4);
        $participant=$this->uri->segment(5);
        $settlement_id=$this->super_model->select_column_where("participant","settlement_id","settlement_id",$participant);
        $part=$this->super_model->select_column_where("participant","participant_name","settlement_id",$participant);
        $data['settlement_id'] = $settlement_id;
        $data['part'] = $part;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $sql='';
        if($date_from != 'null' && $date_to != 'null'){
            //$sql.= "collection_date BETWEEN '$date_from' AND '$date_to' AND ";
             $sql.= "collection_date >= '$date_from' AND collection_date <= '$date_to' AND ";
        } 
        if($participant!='null'){
             $sql.= "settlement_id = '$participant' AND ";
        }
        $query=substr($sql,0,-4);

        //echo $query;
        $data['or_summary']=array();
        $data['min'] = $this->super_model->custom_query_single("series_number","SELECT MIN(series_number) AS series_number FROM collection_details cd INNER JOIN collection_head ch ON ch.collection_id=cd.collection_id WHERE cd.series_number != '' AND saved='1' AND ".$query."");

        $data['max']= $this->super_model->custom_query_single("series_number","SELECT MAX(series_number) AS series_number FROM collection_details cd INNER JOIN collection_head ch ON ch.collection_id=cd.collection_id WHERE cd.series_number != '' AND saved='1' AND ".$query."");

       

        foreach($this->super_model->custom_query("SELECT DISTINCT series_number FROM collection_details cd INNER JOIN collection_head ch ON cd.collection_id = ch.collection_id WHERE cd.series_number!='' AND saved='1' AND ".$query." ORDER BY cd.series_number ASC") AS $or){

            $series_number[] = $or->series_number;
        }

        

       foreach($series_number AS $or){
            $stl_id="";
            $company_name="";
            $amount="";
            $remarks="";

          

                foreach($this->super_model->select_row_where("collection_details", "series_number", $or) AS $o){
                    $settle=$o->settlement_id;
                    $date_uploaded = $this->super_model->select_column_where("collection_head", "date_uploaded", "collection_id", $o->collection_id);
                    if(!empty($o->company_name) && date('Y',strtotime($date_uploaded))==date('Y')){
                        $name=$o->company_name;
                    }else{
                        $name=$this->super_model->select_column_where("participant", "participant_name", "settlement_id", $settle);
                    }
                    //$name=$this->super_model->select_column_where("participant","participant_name","settlement_id",$settle);
                    
                   
                    $or_date = $this->super_model->select_column_where("collection_head","collection_date","collection_id",$o->collection_id);
                    $or_no = $o->series_number;
                    $stl_id .= $settle."<br>";
                    $company_name .= $name."<br>";
                    $amount .= number_format($o->total,2)."<br>";
                    $remarks .= $this->super_model->select_column_where("or_remarks","remarks","or_no",$o->series_number)."<br>";
                    

                }

                 $data['or_summary'][]=array(
                    "date"=>$or_date,
                    "or_no"=>$or_no,
                    "stl_id"=>$stl_id,
                    "amount"=>$amount,
                    "remarks"=>$remarks,
                    "company_name"=>$company_name,
                );
       }


        $this->load->view('reports/or_summary',$data);
        $this->load->view('template/footer');
    
    }

    public function adjustment_sales(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        //$transaction_date=$this->uri->segment(3);
        //$data['transaction_date']=$transaction_date;
        $due_date=$this->uri->segment(3);
        $data['due_date']=$due_date;
        // $year=date("Y",strtotime($billing_month));
        // $month=date("m",strtotime($billing_month));
        $total_sum[]=0;
        //$data['date']=$this->super_model->custom_query("SELECT * FROM sales_adjustment_head WHERE saved='1' GROUP BY MONTH(billing_to), YEAR(billing_to)");
        $data['date']=$this->super_model->custom_query("SELECT * FROM sales_adjustment_head WHERE saved='1' GROUP BY due_date");
        //foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_head WHERE transaction_date = '$transaction_date' AND YEAR(transaction_date)='$year'") AS $ads){
        //foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_head WHERE YEAR(billing_to) = '$year' AND MONTH(billing_to) = '$month' AND saved='1'") AS $ads){
        foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_head WHERE due_date='$due_date' AND saved='1' ORDER BY billing_to ASC") AS $ads){
            $vatable_sales=$this->super_model->select_sum_where("sales_adjustment_details","vatable_sales","sales_adjustment_id='$ads->sales_adjustment_id'");
            $zero_rated_sales=$this->super_model->select_sum_where("sales_adjustment_details","zero_rated_sales","sales_adjustment_id='$ads->sales_adjustment_id'");
            $zero_rated_ecozones=$this->super_model->select_sum_where("sales_adjustment_details","zero_rated_ecozones","sales_adjustment_id='$ads->sales_adjustment_id'");
            $vat_on_sales=$this->super_model->select_sum_where("sales_adjustment_details","vat_on_sales","sales_adjustment_id='$ads->sales_adjustment_id'");
            $ewt=$this->super_model->select_sum_where("sales_adjustment_details","ewt","sales_adjustment_id='$ads->sales_adjustment_id'");
            $zero_rated=$zero_rated_sales+$zero_rated_ecozones;
            $net=$vatable_sales+$zero_rated;
            $total=($vatable_sales+$zero_rated+$vat_on_sales)-$ewt;
            $total_sum[]=$total;

            $data['due_date']=$ads->due_date;
            $data['adjustment'][]=array(
                'adjust_identifier'=>$ads->adjust_identifier,
                'particular'=>$ads->remarks,
                'reference_number'=>$ads->reference_number,
                'billing_from'=>$ads->billing_from,
                'billing_to'=>$ads->billing_to,
                'vatable_sales'=>$vatable_sales,
                'vat_on_sales'=>$vat_on_sales,
                'ewt'=>$ewt,
                'zero_rated'=>$zero_rated,
                'net'=>$net,
                'total'=>$total,
            );
        }
        $data['total_sum']=array_sum($total_sum);
        $this->load->view('reports/adjustment_sales', $data);
        $this->load->view('template/footer');
    }

    public function adjustment_sales_print(){
        $due_date=$this->uri->segment(3);
        $data['due_date']=$due_date;
        // $year=date("Y",strtotime($billing_month));
        // $month=date("m",strtotime($billing_month));
        /*$transaction_date=$this->uri->segment(3);
        $year=date("Y",strtotime($transaction_date));
        $data['invoice_date']=date("F d,Y",strtotime($transaction_date));*/
        $total_sum[]=0;
        //$data['date']=$this->super_model->custom_query("SELECT * FROM sales_adjustment_head GROUP BY transaction_date");
        //foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_head WHERE transaction_date = '$transaction_date' AND YEAR(transaction_date)='$year'") AS $ads){
        foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_head WHERE due_date='$due_date' AND saved='1'  ORDER BY billing_to ASC") AS $ads){
            $vatable_sales=$this->super_model->select_sum_where("sales_adjustment_details","vatable_sales","sales_adjustment_id='$ads->sales_adjustment_id'");
            $zero_rated_sales=$this->super_model->select_sum_where("sales_adjustment_details","zero_rated_sales","sales_adjustment_id='$ads->sales_adjustment_id'");
            $zero_rated_ecozones=$this->super_model->select_sum_where("sales_adjustment_details","zero_rated_ecozones","sales_adjustment_id='$ads->sales_adjustment_id'");
            $vat_on_sales=$this->super_model->select_sum_where("sales_adjustment_details","vat_on_sales","sales_adjustment_id='$ads->sales_adjustment_id'");
            $ewt=$this->super_model->select_sum_where("sales_adjustment_details","ewt","sales_adjustment_id='$ads->sales_adjustment_id'");
            $zero_rated=$zero_rated_sales+$zero_rated_ecozones;
            $net=$vatable_sales+$zero_rated;
            $total=($vatable_sales+$zero_rated+$vat_on_sales)-$ewt;
            $total_sum[]=$total;
            $data['due_date']=$ads->due_date;
            $data['invoice_date']=date("F d,Y",strtotime($ads->transaction_date));

            $total_vatable_sales[]=$vatable_sales;
            $total_zero_rated[]=$zero_rated;
            $total_net[]=$net;
            $total_vat_on_sales[]=$vat_on_sales;
            $total_ewt[]=$ewt;

            $data['due_date']=$ads->due_date;
            $data['adjustment'][]=array(
                'adjust_identifier'=>$ads->adjust_identifier,
                'particular'=>$ads->remarks,
                'reference_number'=>$ads->reference_number,
                'billing_from'=>$ads->billing_from,
                'billing_to'=>$ads->billing_to,
                'vatable_sales'=>$vatable_sales,
                'vat_on_sales'=>$vat_on_sales,
                'ewt'=>$ewt,
                'zero_rated'=>$zero_rated,
                'net'=>$net,
                'total'=>$total,
            );
        }
        $data['total_sum']=array_sum($total_sum);
        $data['total_vatable_sales']=array_sum($total_vatable_sales);
        $data['total_zero_rated']=array_sum($total_zero_rated);
        $data['total_net']=array_sum($total_net);
        $data['total_vat_on_sales']=array_sum($total_vat_on_sales);
        $data['total_ewt']=array_sum($total_ewt);
        $this->load->view('template/print_head');
        $this->load->view('reports/adjustment_sales_print',$data);
    }

    public function adjustment_purchases(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        // $transaction_date=$this->uri->segment(3);
        // $data['transaction_date']=$transaction_date;
        // $year=date("Y",strtotime($transaction_date));
        $due_date=$this->uri->segment(3);
        $data['due_date']=$due_date;
        // $year=date("Y",strtotime($billing_month));
        // $month=date("m",strtotime($billing_month));
        $total_sum[]=0;
        $data['date']=$this->super_model->custom_query("SELECT * FROM purchase_transaction_head WHERE adjustment='1' AND saved='1' GROUP BY due_date");
        //$data['date']=$this->super_model->custom_query("SELECT * FROM purchase_transaction_head WHERE adjustment='1' AND saved='1' GROUP BY MONTH(billing_to), YEAR(billing_to)");
        //foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head WHERE transaction_date = '$transaction_date' AND YEAR(transaction_date)='$year' AND adjustment='1'") AS $ad){
        //foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head WHERE YEAR(billing_to) = '$year' AND MONTH(billing_to) = '$month' AND adjustment='1' AND saved='1'") AS $ad){
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head WHERE due_date='$due_date' AND adjustment='1' AND saved='1' ORDER BY billing_to ASC") AS $ad){
            $zero_rated_purchases=$this->super_model->select_sum_where("purchase_transaction_details","zero_rated_purchases","purchase_id='$ad->purchase_id'");
            $zero_rated_ecozones=$this->super_model->select_sum_where("purchase_transaction_details","zero_rated_ecozones","purchase_id='$ad->purchase_id'");
            $vatables_purchases=$this->super_model->select_sum_where("purchase_transaction_details","vatables_purchases","purchase_id='$ad->purchase_id'");
            $vat_on_purchases=$this->super_model->select_sum_where("purchase_transaction_details","vat_on_purchases","purchase_id='$ad->purchase_id'");
            $ewt=$this->super_model->select_sum_where("purchase_transaction_details","ewt","purchase_id='$ad->purchase_id'");
            $zero_rated=$zero_rated_purchases+$zero_rated_ecozones;
            $net=$vatables_purchases+$zero_rated;
            $total=($vatables_purchases+$zero_rated+$vat_on_purchases)-$ewt;
            $data['due_date']=$ad->due_date;
            $total_sum[]=$total;
            $data['adjust'][]=array(
                'adjust_identifier'=>$ad->adjust_identifier,
                'particular'=>$ad->adjustment_remarks,
                'participant'=>$ad->reference_number,
                'billing_from'=>$ad->billing_from,
                'billing_to'=>$ad->billing_to,
                'vatables_purchases'=>$vatables_purchases,
                'vat_on_purchases'=>$vat_on_purchases,
                'ewt'=>$ewt,
                'zero_rated'=>$zero_rated,
                'net'=>$net,
                'total'=>$total,
            );
        }
        $data['total_sum']=array_sum($total_sum);
        $this->load->view('reports/adjustment_purchases',$data);
        $this->load->view('template/footer');
    }

    public function adjustment_purchases_print(){
        $this->load->view('template/print_head');
        // $transaction_date=$this->uri->segment(3);
        // $year=date("Y",strtotime($transaction_date));
        // $data['invoice_date']=date("F d,Y",strtotime($transaction_date));
        $due_date=$this->uri->segment(3);
        $data['due_date']=$due_date;
        // $year=date("Y",strtotime($billing_month));
        // $month=date("m",strtotime($billing_month));
        $total_sum[]=0;
        //foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head WHERE transaction_date = '$transaction_date' AND YEAR(transaction_date)='$year' AND adjustment='1'") AS $ad){
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head WHERE due_date='$due_date' AND adjustment='1' AND saved='1' ORDER BY billing_to ASC") AS $ad){
            $zero_rated_purchases=$this->super_model->select_sum_where("purchase_transaction_details","zero_rated_purchases","purchase_id='$ad->purchase_id'");
            $zero_rated_ecozones=$this->super_model->select_sum_where("purchase_transaction_details","zero_rated_ecozones","purchase_id='$ad->purchase_id'");
            $vatables_purchases=$this->super_model->select_sum_where("purchase_transaction_details","vatables_purchases","purchase_id='$ad->purchase_id'");
            $vat_on_purchases=$this->super_model->select_sum_where("purchase_transaction_details","vat_on_purchases","purchase_id='$ad->purchase_id'");
            $ewt=$this->super_model->select_sum_where("purchase_transaction_details","ewt","purchase_id='$ad->purchase_id'");
            $zero_rated=$zero_rated_purchases+$zero_rated_ecozones;
            $net=$vatables_purchases+$zero_rated;
            $total=($vatables_purchases+$zero_rated+$vat_on_purchases)-$ewt;
            $data['due_date']=$ad->due_date;
            $data['invoice_date']=date("F d,Y",strtotime($ad->transaction_date));

            $total_sum[]=$total;
            $total_vatables_purchases[]=$vatables_purchases;
            $total_zero_rated[]=$zero_rated;
            $total_net[]=$net;
            $total_vat_on_purchase[]=$vat_on_purchases;
            $total_ewt[]=$ewt;
            $data['adjust'][]=array(
                'adjust_identifier'=>$ad->adjust_identifier,
                'particular'=>$ad->adjustment_remarks,
                'participant'=>$ad->reference_number,
                'billing_from'=>$ad->billing_from,
                'billing_to'=>$ad->billing_to,
                'vatables_purchases'=>$vatables_purchases,
                'vat_on_purchases'=>$vat_on_purchases,
                'ewt'=>$ewt,
                'zero_rated'=>$zero_rated,
                'net'=>$net,
                'total'=>$total,
            );
        }
        $data['total_sum']=array_sum($total_sum);
        $data['total_vatables_purchases']=array_sum($total_vatables_purchases);
        $data['total_zero_rated']=array_sum($total_zero_rated);
        $data['total_net']=array_sum($total_net);
        $data['total_vat_on_purchase']=array_sum($total_vat_on_purchase);
        $data['total_ewt']=array_sum($total_ewt);
        $this->load->view('reports/adjustment_purchases_print',$data);
    }

    public function payment_report(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $payment_date=$this->uri->segment(3);
        $data['payment_date']=$payment_date;
        //$data['date']=$this->super_model->select_all_order_by("payment_head","payment_date","ASC");
        $data['date']=$this->super_model->custom_query("SELECT DISTINCT payment_date FROM payment_head WHERE payment_date!=''");
        foreach($this->super_model->custom_query("SELECT * FROM payment_head WHERE payment_date='$payment_date' GROUP BY payment_identifier") AS $p){
            $payment_identifier= $this->super_model->select_column_where("payment_head", "payment_identifier", "purchase_id", $p->purchase_id);
            $total_amount= $this->super_model->select_sum("payment_head", "total_amount", "payment_identifier", $payment_identifier);
            $data['payment'][]=array(
                "transaction_date"=>$p->payment_date,
                "total_amount"=>$total_amount,
                "payment_identifier"=>$payment_identifier,
            );
        }
        $this->load->view('reports/payment_report',$data);
        $this->load->view('template/footer');
    }

    public function payment_form(){
            $payment_identifier = $this->uri->segment(3);
            $this->load->view('template/print_head');
            foreach($this->super_model->custom_query("SELECT * FROM payment_head WHERE payment_identifier='$payment_identifier' GROUP BY purchase_id") AS $p){
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
        $this->load->view('reports/payment_form',$data);
    }

    public function purchases_all(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        $original=$this->uri->segment(6);
        $scanned=$this->uri->segment(7);
        $data['from'] = $from;
        $data['to'] = $to;
        $part=$this->super_model->select_column_where("participant","participant_name","tin",$participant);
        $data['part'] = $part;
        $data['original'] = $original;
        $data['scanned'] = $scanned;

        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant WHERE participant_name != '' GROUP BY tin ORDER BY participant_name");
        $sql="";

        if(!empty($participant) && $participant!='null'){
             //$sql.= " tin = '$participant' AND "; 
            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";
        } if(!empty($from) && !empty($from) && $from!='null' && $to != 'null'){
            $sql.= " ((billing_from BETWEEN '$from' AND '$to') OR (billing_to BETWEEN '$from' AND '$to')) AND ";
        } if($original!='null' && isset($original)){
             $sql.= "original_copy = '$original' AND "; 
        } if($scanned!='null'  && isset($scanned)){
             $sql.= "scanned_copy = '$scanned' AND ";
        }

        $query=substr($sql,0,-4);
        $qu = "saved='1' AND adjustment !='1' AND ".$query;
        $total_sum[]=0;

        //echo $query;
        if(!empty($query)){
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pth INNER JOIN purchase_transaction_details ptd ON pth.purchase_id = ptd.purchase_id WHERE $qu ORDER BY billing_from ASC, reference_number ASC") AS $pth){
            $participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$pth->billing_id);
            // $create_date = $this->super_model->select_column_where("purchase_transaction_head", "create_date", "purchase_id", $pth->purchase_id);
            // $company_name=$this->super_model->select_column_where("purchase_transaction_details", "company_name", "purchase_detail_id", $pth->purchase_detail_id);
            if(!empty($pth->company_name) && date('Y',strtotime($pth->create_date))==date('Y')){
                $comp_name=$pth->company_name;
            }else{
                $comp_name=$participant_name;
            }
            $total=($pth->vatables_purchases+$pth->vat_on_purchases)-$pth->ewt;
            $total_sum[]=$total;

            $data['purchaseall'][]=array(
                'participant_name'=>$comp_name,
                'billing_id'=>$pth->billing_id,
                'reference_number'=>$pth->reference_number,
                'billing_from'=>$pth->billing_from,
                'billing_to'=>$pth->billing_to,
                'vatables_purchases'=>$pth->vatables_purchases,
                'vat_on_purchases'=>$pth->vat_on_purchases,
                'zero_rated_purchases'=>$pth->zero_rated_purchases,
                'zero_rated_ecozones'=>$pth->zero_rated_ecozones,
                'ewt'=>$pth->ewt,
                'or_no'=>$pth->or_no,
                'total_update'=>$pth->total_update,
                'original_copy'=>$pth->original_copy,
                'scanned_copy'=>$pth->scanned_copy,
                'total'=>$total,
                );
            }
        }
        $data['total_sum']=array_sum($total_sum);
        $this->load->view('reports/purchases_all',$data);
        $this->load->view('template/footer');
    }

        public function export_purchases_all(){
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Purchases Wesm All Transcations.xlsx";
        $sql='';

        if($participant!='null'){
             $sql.= " tin = '$participant' AND "; 
            /*$par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";*/
        }
        if($from!='null' && $to != 'null'){
            $sql.= " ((billing_from BETWEEN '$from' AND '$to') OR (billing_to BETWEEN '$from' AND '$to')) AND ";
        }

        $query=substr($sql,0,-4);
        if($participant !='null' || $from != 'null' || $to != 'null'){
            $qu = " saved = '1' AND adjustment != '1' AND ".$query;
        }else{
             $qu = " saved = '1' AND adjustment != '1'";
        }

        $sheetno=0;
            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    )
                )
            );
            /*foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pah INNER JOIN purchase_transaction_details pad ON pah.purchase_id = pad.purchase_id WHERE $qu GROUP BY short_name ORDER BY short_name ASC") AS $head){*/
            foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pah INNER JOIN purchase_transaction_details pad ON pah.purchase_id = pad.purchase_id INNER JOIN participant p ON p.settlement_id = pad.short_name WHERE participant_name != '' AND $qu GROUP BY tin ORDER BY participant_name") AS $head){
            $settlement_id=$this->super_model->select_column_custom_where("participant",'settlement_id',"tin = '$head->tin' ORDER BY settlement_id ASC LIMIT 1");
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            // $invalidCharacters = array('*', ':', '/', '\\', '?', '[', ']');
            // $title = str_replace($invalidCharacters, '', $head->settlement_id);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($settlement_id);
            foreach(range('A','N') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A1', "Billing Period");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B1', "Billing ID");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C1', "Transaction Reference Number");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D1', "Company Name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E1', "Vatables Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F1', "Zero-rated Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G1', "Zero-rated Ecozones Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H1', "Vat On Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I1', "EWT Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J1', "Total");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K1', "OR Number");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L1', "Total Amount");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M1', "Original Copy");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('N1', "Scanned Copy");
            $objPHPExcel->getActiveSheet()->getStyle("A1:N1")->applyFromArray($styleArray);

            $total_vatables=array();
            $total_zerorated_purchases=array();
            $total_zerorated_ecozones=array();
            $total_vat=array();
            $total_ewt=array();
            $total_zero_rated=array();
            $total_ewt_amount=array();
            $total_update_amount=array();
            $overall_total=array();
            $purchaseall=array();

            // foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pah INNER JOIN purchase_transaction_details pad ON pah.purchase_id = pad.purchase_id WHERE $qu AND short_name = '$head->short_name' ORDER BY billing_from ASC, reference_number ASC") AS $pah){
            foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pah INNER JOIN purchase_transaction_details pad ON pah.purchase_id = pad.purchase_id INNER JOIN participant p ON p.billing_id = pad.billing_id WHERE tin='$head->tin' AND participant_name != '' AND $qu ORDER BY billing_from ASC, reference_number ASC, p.billing_id ASC") AS $pah){
            //$participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$pah->billing_id);
            // $zero_rated=$pah->zero_rated_purchases+$pah->zero_rated_ecozones;
            // $total=($pah->vatables_purchases+$zero_rated+$pah->vat_on_purchases)-$pah->ewt;
            // $create_date = $this->super_model->select_column_where("purchase_transaction_head", "create_date", "purchase_id", $pah->purchase_id);
            // $company_name=$this->super_model->select_column_where("purchase_transaction_details", "company_name", "purchase_detail_id", $pah->purchase_detail_id);
            if(!empty($pah->company_name) && date('Y',strtotime($pah->create_date))==date('Y')){
                $comp_name=$pah->company_name;
            }else{
                $comp_name=$pah->participant_name;
            }
            $billing_date = date("M. d, Y",strtotime($pah->billing_from))." - ".date("M. d, Y",strtotime($pah->billing_to));
            //$short_name=$this->super_model->select_column_where("purchase_transaction_details", "short_name", "purchase_detail_id", $pah->purchase_detail_id);
            $tin=$this->super_model->select_column_where("participant","tin","billing_id",$pah->billing_id);
                $purchaseall[]=array(
                    'billing_date'=>$billing_date,
                    'participant_name'=>$comp_name,
                    'billing_id'=>$pah->billing_id,
                    'reference_number'=>$pah->reference_number,
                    'vatables_purchases'=>$pah->vatables_purchases,
                    'zero_rated_purchases'=>$pah->zero_rated_purchases,
                    'zero_rated_ecozones'=>$pah->zero_rated_ecozones,
                    'vat_on_purchases'=>$pah->vat_on_purchases,
                    'ewt'=>$pah->ewt,
                    'or_no'=>$pah->or_no,
                    'total_update'=>$pah->total_update,
                    'original_copy'=>$pah->original_copy,
                    'scanned_copy'=>$pah->scanned_copy,
                    'zero_rated_purchases'=>$pah->zero_rated_purchases,
                    'zero_rated_ecozones'=>$pah->zero_rated_ecozones,
                    //  'total'=>$total,
                    'short_name'=>$pah->short_name,
                    'tin'=>$tin,
                );
            }
            $row = 2;
            $startRow = -1;
            $previousKey = '';
            $num=2;
            foreach($purchaseall AS $index => $value){
                if($startRow == -1){
                    $startRow = $row;
                    $previousKey = $value['billing_date'];
                }
                $zero_rated=$value['zero_rated_purchases']+$value['zero_rated_ecozones'];
                $total=($value['vatables_purchases']+$zero_rated+$value['vat_on_purchases'])-$value['ewt'];
                //if($value['short_name']==$pah->short_name){
            //if($value['tin']==$tin){
            if($value['tin']==$pah->tin){
                $total_vatables[]=$value['vatables_purchases'];
                $total_zerorated_purchases[]=$value['zero_rated_purchases'];
                $total_zerorated_ecozones[]=$value['zero_rated_ecozones'];
                $total_vat[]=$value['vat_on_purchases'];
                $total_ewt[]=$value['ewt'];
                $total_zero_rated[]=$zero_rated;
                $total_update_amount[]=$value['total_update'];
                $overall_total[]=$total;
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $value['billing_date']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B'.$num, $value['billing_id']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $value['reference_number']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D'.$num, $value['participant_name']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, "-".$value['vatables_purchases']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F'.$num, "-".$value['zero_rated_purchases']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G'.$num, "-".$value['zero_rated_ecozones']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, "-".$value['vat_on_purchases']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I'.$num, $value['ewt']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J'.$num, "-".$total);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K'.$num, $value['or_no']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, "-".$value['total_update']);
                if($value['original_copy']==1){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M'.$num, "Yes");
                }else if($value['original_copy']==0){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M'.$num, "No");
                }else{
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M'.$num, "");
                }
                if($value['scanned_copy']==1){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('N'.$num, "Yes");
                }else if($value['scanned_copy']==0){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('N'.$num, "No");
                }else{
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('N'.$num, "");
                }

                $nextKey = isset($purchaseall[$index+1]) ? $purchaseall[$index+1]['billing_date'] : null;

                if($row >= $startRow && (($previousKey <> $nextKey) || ($nextKey == null))){
                    $cellToMerge = 'A'.$startRow.':A'.$row;
                    $objPHPExcel->getActiveSheet()->mergeCells($cellToMerge);
                    $startRow = -1;

                }
                $row++;
                
                $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->getColor()->setRGB ('FFFFFF');
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":N".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":N".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":J".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $num++;
            }
        }
                $a = $num;
                    //$objPHPExcel->getActiveSheet()->getStyle('D'.$a)->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$a.":J".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle("E".$a.':J'.$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle("L".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    //$objPHPExcel->getActiveSheet()->setCellValue('D'.$a, "TOTAL: ");
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$a, "-".array_sum($total_vatables));
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$a, "-".array_sum($total_zerorated_purchases));
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$a, "-".array_sum($total_zerorated_ecozones));
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$a, "-".array_sum($total_vat));
                    $objPHPExcel->getActiveSheet()->setCellValue('I'.$a, array_sum($total_ewt));
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$a, "-".array_sum($overall_total));
                    $objPHPExcel->getActiveSheet()->setCellValue('L'.$a, "-".array_sum($total_update_amount));
                $num--;
            $sheetno++;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Purchases Wesm All Transcations.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Purchases Wesm All Transcations.xlsx"');
        // readfile($exportfilename);
    }

    public function sales_all(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        $original=$this->uri->segment(6);
        $scanned=$this->uri->segment(7);
        $data['from'] = $from;
        $data['to'] = $to;
        $part=$this->super_model->select_column_where("participant","participant_name","tin",$participant);
        $data['part'] = $part;
        $data['original'] = $original;
        $data['scanned'] = $scanned;

        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant WHERE participant_name != '' GROUP BY tin ORDER BY participant_name");
        $sql="";

        if(!empty($participant) && $participant!='null'){
             //$sql.= " tin = '$participant' AND "; 
            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";
        } if(!empty($from) && !empty($from) && $from!='null' && $to != 'null'){
            $sql.= " ((billing_from BETWEEN '$from' AND '$to') OR (billing_to BETWEEN '$from' AND '$to')) AND ";
        } if($original!='null' && isset($original)){
             $sql.= "original_copy = '$original' AND "; 
        } if($scanned!='null'  && isset($scanned)){
             $sql.= "scanned_copy = '$scanned' AND "; 
        }

        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND ".$query;
        $total_sum[]=0;
        if(!empty($query)){
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id = std.sales_id WHERE $qu ORDER BY billing_from ASC, reference_number ASC") AS $sth){
            $participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$sth->billing_id);
            // $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $sth->sales_id);
            // $participant_name=$this->super_model->select_column_where("sales_transaction_details", "company_name", "sales_detail_id", $sth->sales_detail_id);
            $short_name=$this->super_model->select_column_where("sales_transaction_details", "short_name", "sales_detail_id", $sth->sales_detail_id);
            $or_no=$this->super_model->select_column_custom_where("collection_details","series_number","reference_no='$sth->reference_number' AND settlement_id='$short_name'");
            if(!empty($sth->company_name) && date('Y',strtotime($sth->create_date))==date('Y')){
                    $comp_name=$sth->company_name;
                }else{
                    $comp_name=$participant_name;
                }
            $zero_rated=$sth->zero_rated_sales+$sth->zero_rated_ecozones;
            $total=($sth->vatable_sales+$zero_rated+$sth->vat_on_sales)-$sth->ewt;
            //$total_sum[]=$total;

            $data['salesall'][]=array(
                'participant_name'=>$comp_name,
                'billing_id'=>$sth->billing_id,
                'reference_number'=>$sth->reference_number,
                'sales_detail_id'=>$sth->sales_detail_id,
                'billing_from'=>$sth->billing_from,
                'billing_to'=>$sth->billing_to,
                'vatable_sales'=>$sth->vatable_sales,
                'vat_on_sales'=>$sth->vat_on_sales,
                'ewt'=>$sth->ewt,
                'ewt_amount'=>$sth->ewt_amount,
                'original_copy'=>$sth->original_copy,
                'scanned_copy'=>$sth->scanned_copy,
                'zero_rated'=>$zero_rated,
                'total'=>$total,
                'or_no'=>$or_no,
                );
            }
        }
        $data['total_sum']=array_sum($total_sum);
        $this->load->view('reports/sales_all',$data);
        $this->load->view('template/footer');
    }

    public function export_sales_all(){
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Sales Wesm All Transactions.xlsx";
        $sql='';

        if($participant!='null'){
             $sql.= " tin = '$participant' AND "; 
            /*$par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";*/
        }
        if($from!='null' && $to != 'null'){
            $sql.= " ((billing_from BETWEEN '$from' AND '$to') OR (billing_to BETWEEN '$from' AND '$to')) AND ";
        }

        $query=substr($sql,0,-4);
        if($participant != 'null' || $from != 'null' || $to != 'null'){
            $qu = " saved = '1' AND ".$query;
        }else{
             $qu = " saved = '1'";
        }
        $sheetno=0;
            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    )
                )
            );
           
        // foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id = std.sales_id WHERE $qu GROUP BY short_name ORDER BY short_name ASC") AS $head){
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id = std.sales_id INNER JOIN participant p ON p.settlement_id = std.short_name WHERE participant_name != '' AND $qu GROUP BY tin ORDER BY settlement_id ASC") AS $head){
            $settlement_id=$this->super_model->select_column_custom_where("participant",'settlement_id',"tin = '$head->tin' ORDER BY settlement_id ASC LIMIT 1");
            //foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id = std.sales_id WHERE $qu ORDER BY sales_detail_id ASC") AS $sth){
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($settlement_id);
            
            foreach(range('A','M') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A1', "Billing Period");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B1', "Billing ID");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C1', "Transaction Reference Number");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D1', "Company Name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E1', "Vatables Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F1', "Zero-rated Ecozones Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G1', "Vat on Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H1', "EWT Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I1', "Total");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J1', "EWT Amount");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K1', "Original Copy");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L1', "Scanned Copy");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M1', "OR Number");
            $objPHPExcel->getActiveSheet()->getStyle("A1:M1")->applyFromArray($styleArray);

            //$total_vatables=array();
            //$total_vat=array();
            $total_ewt=array();
            //$total_zero_rated=array();
            $total_ewt_amount=array();
            //$overall_total=array();
            $salesall=array();
            /*foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id = std.sales_id  WHERE $qu AND short_name = '$head->short_name' ORDER BY billing_from ASC, reference_number ASC") AS $sth){*/
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id = std.sales_id  INNER JOIN participant p ON p.billing_id = std.billing_id WHERE tin='$head->tin' AND participant_name != '' AND $qu ORDER BY billing_from ASC, reference_number ASC, p.billing_id ASC") AS $sth){
                //$participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$sth->billing_id);
                // $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $sth->sales_id);
                $or_no=$this->super_model->select_column_custom_where("collection_details","series_number","reference_no='$sth->reference_number' AND settlement_id='$sth->short_name'");
                // $participant_name=$this->super_model->select_column_where("sales_transaction_details", "company_name", "sales_detail_id", $sth->sales_detail_id);
                // $short_name=$this->super_model->select_column_where("sales_transaction_details", "short_name", "sales_detail_id", $sth->sales_detail_id);
                if(!empty($sth->company_name) && date('Y',strtotime($sth->create_date))==date('Y')){
                        $comp_name=$sth->company_name;
                    }else{
                        $comp_name=$sth->participant_name;
                    }
                $billing_date = date("M. d, Y",strtotime($sth->billing_from))." - ".date("M. d, Y",strtotime($sth->billing_to));
                $tin=$this->super_model->select_column_where("participant","tin","billing_id",$sth->billing_id);
                $salesall[]=array(
                    'billing_date'=>$billing_date,
                    'participant_name'=>$comp_name,
                    'billing_id'=>$sth->billing_id,
                    'reference_number'=>$sth->reference_number,
                    'vatable_sales'=>$sth->vatable_sales,
                    'vat_on_sales'=>$sth->vat_on_sales,
                    'ewt'=>$sth->ewt,
                    'ewt_amount'=>$sth->ewt_amount,
                    'short_name'=>$sth->short_name,
                    'original_copy'=>$sth->original_copy,
                    'scanned_copy'=>$sth->scanned_copy,
                    'zero_rated_sales'=>$sth->zero_rated_sales,
                    'zero_rated_ecozones'=>$sth->zero_rated_ecozones,
                    'tin'=>$tin,
                    'or_no'=>$or_no,
                );
            }
            $row = 2;
            $startRow = -1;
            $previousKey = '';
            $num=2;

            foreach($salesall AS $index => $value){
                if($startRow == -1){
                    $startRow = $row;
                    $previousKey = $value['billing_date'];
                }
                $zero_rated=$value['zero_rated_sales']+$value['zero_rated_ecozones'];
                $total=($value['vatable_sales']+$zero_rated+$value['vat_on_sales'])-$value['ewt'];
                //if($value['tin']==$tin){
                if($value['tin']==$sth->tin){
                // $total_vatables[]=$value['vatable_sales'];
                // $total_vat[]=$value['vat_on_sales'];
                $total_ewt[]=$value['ewt'];
                //$total_zero_rated[]=$zero_rated;
                $total_ewt_amount[]=$value['ewt_amount'];
                //$overall_total[]=$total;
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $value['billing_date']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B'.$num, $value['billing_id']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $value['reference_number']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D'.$num, $value['participant_name']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, $value['vatable_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F'.$num, $zero_rated);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G'.$num, $value['vat_on_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, "-".$value['ewt']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I'.$num, $total);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J'.$num, $value['ewt_amount']);
                    if($value['original_copy']==1){
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K'.$num, "Yes");
                    }else if($value['original_copy']==0){
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K'.$num, "No");
                    }else{
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K'.$num, "");
                    }
                    if($value['scanned_copy']==1){
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, "Yes");
                    }else if($value['scanned_copy']==0){
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, "No");
                    }else{
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, "");
                    }
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M'.$num, $value['or_no']);

                    $nextKey = isset($salesall[$index+1]) ? $salesall[$index+1]['billing_date'] : null;

                    if($row >= $startRow && (($previousKey <> $nextKey) || ($nextKey == null))){
                        $cellToMerge = 'A'.$startRow.':A'.$row;
                        $objPHPExcel->getActiveSheet()->mergeCells($cellToMerge);
                        $startRow = -1;

                    }
                    $row++;

                    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->getColor()->setRGB ('FFFFFF');
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":M".$num)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":M".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":I".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $num++;
                    }
                 }
                $a = $num;
                    //$objPHPExcel->getActiveSheet()->getStyle('D'.$a)->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$a.":J".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle("E".$a.':J'.$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    //$objPHPExcel->getActiveSheet()->setCellValue('D'.$a, "TOTAL: ");
                    //$objPHPExcel->getActiveSheet()->setCellValue('E'.$a, array_sum($total_vatables));
                    //$objPHPExcel->getActiveSheet()->setCellValue('F'.$a, array_sum($total_zero_rated));
                    //$objPHPExcel->getActiveSheet()->setCellValue('G'.$a, array_sum($total_vat));
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$a, "-".array_sum($total_ewt));
                    //$objPHPExcel->getActiveSheet()->setCellValue('I'.$a, array_sum($overall_total));
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$a, array_sum($total_ewt_amount));
                $num--;
            $sheetno++;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Sales Wesm All Transactions.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Sales Wesm All Transcations.xlsx"');
        // readfile($exportfilename);
    }

    public function purchases_all_adjustment(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        $original=$this->uri->segment(6);
        $scanned=$this->uri->segment(7);
        $due_date=$this->uri->segment(8);
        $data['from'] = $from;
        $data['to'] = $to;
        $part=$this->super_model->select_column_where("participant","participant_name","tin",$participant);
        $data['part'] = $part;
        $data['original'] = $original;
        $data['scanned'] = $scanned;
        $data['due'] = $due_date;

        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant WHERE participant_name != '' GROUP BY tin ORDER BY participant_name");
        $data['date'] = $this->super_model->custom_query("SELECT DISTINCT due_date FROM purchase_transaction_head WHERE due_date!='' AND adjustment='1' AND saved = '1'");
        $sql="";

        if($from!='null' && $to != 'null'){
            //$sql.= "((billing_from BETWEEN '$from' AND '$to') OR (billing_to BETWEEN '$from' AND '$to')) AND";
            $sql.= " due_date BETWEEN '$from' AND '$to' AND";
        }if($due_date!='null'){
            $sql.= " due_date = '$due_date' AND ";
        } if($participant!='null'){
             //$sql.= " tin = '$participant' AND ";
            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";
        } if($original!='null' && isset($original)){
             $sql.= " original_copy = '$original' AND "; 
        } if($scanned!='null'  && isset($scanned)){
             $sql.= " scanned_copy = '$scanned' AND "; 
        }

        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND adjustment = '1' AND ".$query;
        $total_sum[]=0;
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pth INNER JOIN purchase_transaction_details ptd ON pth.purchase_id = ptd.purchase_id WHERE $qu ORDER BY billing_from ASC, reference_number ASC") AS $pth){
            $participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$pth->billing_id);
            $total=($pth->vatables_purchases+$pth->vat_on_purchases)-$pth->ewt;
            $total_sum[]=$total;
            // $create_date = $this->super_model->select_column_where("purchase_transaction_head", "create_date", "purchase_id", $pth->purchase_id);
            // $participant_name=$this->super_model->select_column_where("purchase_transaction_details", "company_name", "purchase_detail_id", $pth->purchase_detail_id);
            if(!empty($pth->company_name) && date('Y',strtotime($pth->create_date))==date('Y')){
                    $comp_name=$pth->company_name;
                }else{
                    $comp_name=$participant_name;
                }
            $data['purchasead_all'][]=array(
                'participant_name'=>$comp_name,
                'billing_id'=>$pth->billing_id,
                'due_date'=>$pth->due_date,
                'reference_number'=>$pth->reference_number,
                'billing_from'=>$pth->billing_from,
                'billing_to'=>$pth->billing_to,
                'vatables_purchases'=>$pth->vatables_purchases,
                'zero_rated_purchases'=>$pth->zero_rated_purchases,
                'zero_rated_ecozones'=>$pth->zero_rated_ecozones,
                'vat_on_purchases'=>$pth->vat_on_purchases,
                'ewt'=>$pth->ewt,
                'or_no'=>$pth->or_no,
                'total_update'=>$pth->total_update,
                'original_copy'=>$pth->original_copy,
                'scanned_copy'=>$pth->scanned_copy,
                'total'=>$total,
            );
        }
        $data['total_sum']=array_sum($total_sum);
        $this->load->view('reports/purchases_all_adjustment',$data);
        $this->load->view('template/footer');
    }

        public function export_purchases_adjustment_all_persn(){
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        $due=$this->uri->segment(6);
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Purchases Wesm Adjustment All Transactions.xlsx";
        $sql='';

        if($from!='null' && $to != 'null'){
           //$sql.= "((billing_from BETWEEN '$from' AND '$to') OR (billing_to BETWEEN '$from' AND '$to')) AND ";
           $sql.= " due_date BETWEEN '$from' AND '$to' AND ";
        } 
        if($participant!='null'){
             $sql.= "tin = '$participant' AND ";
             /*$par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";*/
        } if($due!='null'){
             $sql.= "due_date = '$due' AND "; 
        }


        $query=substr($sql,0,-4);
        if($participant !='null' || $from != 'null' || $to != 'null' || $due != 'null'){
            $qu = " saved = '1' AND adjustment = '1' AND ".$query;
        }else{
             $qu = " saved = '1' AND adjustment = '1'";
        }

        $sheetno=0;
            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN
                    )
                )
            );
            foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pah INNER JOIN purchase_transaction_details pad ON pah.purchase_id = pad.purchase_id INNER JOIN participant p ON p.settlement_id = pad.short_name WHERE participant_name != '' AND $qu GROUP BY tin ORDER BY settlement_id ASC") AS $head){
            $settlement_id=$this->super_model->select_column_custom_where("participant",'settlement_id',"tin = '$head->tin' ORDER BY settlement_id ASC LIMIT 1");
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($settlement_id);
            foreach(range('A','Q') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A1', "Due Date");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B1', "Billing Period");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C1', "Billing ID");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D1', "Transaction Reference Number");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E1', "Company Name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F1', "Tin No.");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G1', "Address");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H1', "Vatables Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I1', "Zero-rated Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J1', "Zero-rated Ecozones Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K1', "Vat On Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L1', "EWT");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M1', "Total");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('N1', "OR Number");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O1', "Total Amount");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P1', "Original Copy");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q1', "Scanned Copy");
            $objPHPExcel->getActiveSheet()->getStyle("A1:Q1")->applyFromArray($styleArray);

            $total_vatables=array();
            $total_zerorated_purchases=array();
            $total_zerorated_ecozones=array();
            $total_vat=array();
            $total_ewt=array();
            $total_zero_rated=array();
            $total_ewt_amount=array();
            $total_update_amount=array();
            $overall_total=array();
            $purchasealladjustment=array();

            // foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pah INNER JOIN purchase_transaction_details pad ON pah.purchase_id = pad.purchase_id WHERE $qu AND short_name = '$head->short_name' ORDER BY billing_from ASC, reference_number ASC") AS $pah){
           
            foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pah INNER JOIN purchase_transaction_details pad ON pah.purchase_id = pad.purchase_id INNER JOIN participant p ON p.billing_id = pad.billing_id WHERE tin='$head->tin' AND participant_name != '' AND $qu ORDER BY due_date ASC, billing_from ASC, reference_number ASC, p.billing_id ASC") AS $pah){
            //$participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$pah->billing_id);
            // $zero_rated=$pah->zero_rated_purchases+$pah->zero_rated_ecozones;
            // $total=($pah->vatables_purchases+$zero_rated+$pah->vat_on_purchases)-$pah->ewt;
            // $create_date = $this->super_model->select_column_where("purchase_transaction_head", "create_date", "purchase_id", $pah->purchase_id);
            // $company_name=$this->super_model->select_column_where("purchase_transaction_details", "company_name", "purchase_detail_id", $pah->purchase_detail_id);
            if(!empty($pah->company_name) && date('Y',strtotime($pah->create_date))==date('Y')){
                $comp_name=$pah->company_name;
            }else{
                $comp_name=$pah->participant_name;
            }
            $billing_date = date("M. d, Y",strtotime($pah->billing_from))." - ".date("M. d, Y",strtotime($pah->billing_to));
            //$short_name=$this->super_model->select_column_where("purchase_transaction_details", "short_name", "purchase_detail_id", $pah->purchase_detail_id);
            $tin=$this->super_model->select_column_where("participant","tin","billing_id",$pah->billing_id);
            $address=$this->super_model->select_column_where("participant","registered_address","billing_id",$pah->billing_id);

                $purchasealladjustment[]=array(
                    'due_date'=>$pah->due_date,
                    'billing_date'=>$billing_date,
                    'participant_name'=>$comp_name,
                    'billing_id'=>$pah->billing_id,
                    'reference_number'=>$pah->reference_number,
                    'vatables_purchases'=>$pah->vatables_purchases,
                    'vat_on_purchases'=>$pah->vat_on_purchases,
                    'ewt'=>$pah->ewt,
                    'or_no'=>$pah->or_no,
                    'total_update'=>$pah->total_update,
                    'original_copy'=>$pah->original_copy,
                    'scanned_copy'=>$pah->scanned_copy,
                    'zero_rated_purchases'=>$pah->zero_rated_purchases,
                    'zero_rated_ecozones'=>$pah->zero_rated_ecozones,
                    //  'total'=>$total,
                    'short_name'=>$pah->short_name,
                    'tin'=>$tin,
                    'address'=>$address,
                );
            }
            $row = 2;
            $startRow = -1;
            $previousKey = '';
            $num=2;
            foreach($purchasealladjustment AS $index => $value){
                //$previousKey = $value['due_date'];
                //echo $value['billing_id']."-".$head->billing_id."<br>";
                if($startRow == -1){
                    $startRow = $row;
                    $previousKey = $value['due_date'];
                }

                $zero_rated=$value['zero_rated_purchases']+$value['zero_rated_ecozones'];
                $total=($value['vatables_purchases']+$zero_rated+$value['vat_on_purchases'])-$value['ewt'];
                //if($value['short_name']==$pah->short_name){
            //if($value['tin']==$tin){
                //echo $value['tin']."--".$head->tin."<br>";
                 //echo $head->tin."<br>";
            if($value['tin']==$pah->tin){
               // echo $value['tin']." / ".$pah->tin."<br>";
                $total_vatables[]=$value['vatables_purchases'];
                $total_zerorated_purchases[]=$value['zero_rated_purchases'];
                $total_zerorated_ecozones[]=$value['zero_rated_ecozones'];
                $total_vat[]=$value['vat_on_purchases'];
                $total_ewt[]=$value['ewt'];
                $total_zero_rated[]=$zero_rated;
                $total_update_amount[]=$value['total_update'];
                $overall_total[]=$total;
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $value['due_date']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B'.$num, $value['billing_date']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $value['billing_id']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D'.$num, $value['reference_number']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, $value['participant_name']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F'.$num, $value['tin']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G'.$num, $value['address']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, "-".$value['vatables_purchases']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I'.$num, "-".$value['zero_rated_purchases']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J'.$num, "-".$value['zero_rated_ecozones']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K'.$num, "-".$value['vat_on_purchases']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, $value['ewt']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M'.$num, "-".$total);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('N'.$num, $value['or_no']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$num, "-".$value['total_update']);
                if($value['original_copy']==1){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$num, "Yes");
                }else if($value['original_copy']==0){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$num, "No");
                }else{
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$num, "");
                }
                if($value['scanned_copy']==1){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$num, "Yes");
                }else if($value['scanned_copy']==0){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$num, "No");
                }else{
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$num, "");
                }

                $nextKey = isset($purchasealladjustment[$index+1]) ? $purchasealladjustment[$index+1]['due_date'] : null;
                if($row >= $startRow && (($previousKey <> $nextKey) || ($nextKey == null))){
                    $cellToMerge = 'A'.$startRow.':A'.$row;
                    $objPHPExcel->getActiveSheet()->mergeCells($cellToMerge);
                    $startRow = -1;

                }
                $row++;
                
                $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFont()->getColor()->setRGB ('FFFFFF');
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$num.":Q".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$num.":M".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $num++;
            }
        }
                $a = $num;
                    //$objPHPExcel->getActiveSheet()->getStyle('D'.$a)->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$a.":M".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle("H".$a.':M'.$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle("O".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    //$objPHPExcel->getActiveSheet()->setCellValue('D'.$a, "TOTAL: ");
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$a, "-".array_sum($total_vatables));
                    $objPHPExcel->getActiveSheet()->setCellValue('I'.$a, "-".array_sum($total_zerorated_purchases));
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$a, "-".array_sum($total_zerorated_ecozones));
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$a, "-".array_sum($total_vat));
                    $objPHPExcel->getActiveSheet()->setCellValue('L'.$a, array_sum($total_ewt));
                    $objPHPExcel->getActiveSheet()->setCellValue('M'.$a, "-".array_sum($overall_total));
                    $objPHPExcel->getActiveSheet()->setCellValue('O'.$a, "-".array_sum($total_update_amount));
                $num--;
            $sheetno++;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Purchases Wesm Adjustment All Transactions.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Purchases Wesm Adjustment All Transcations.xlsx"');
        // readfile($exportfilename);
    }

        public function export_purchases_adjustment_all_perm(){
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        $due=$this->uri->segment(6);
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Purchases Wesm Adjustment All Transactions.xlsx";
        $sql='';

        if($from!='null' && $to != 'null'){
           $sql.= " due_date BETWEEN '$from' AND '$to' AND ";
        } 
        if($participant!='null'){
             $sql.= "tin = '$participant' AND ";
        } if($due!='null'){
             $sql.= "due_date = '$due' AND "; 
        }


        $query=substr($sql,0,-4);
        if($participant !='null' || $from != 'null' || $to != 'null' || $due != 'null'){
            $qu = " saved = '1' AND adjustment = '1' AND ".$query;
        }else{
             $qu = " saved = '1' AND adjustment = '1'";
        }

        $sheetno=0;
            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN
                    )
                )
            );
            foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pah INNER JOIN purchase_transaction_details pad ON pah.purchase_id = pad.purchase_id WHERE $qu GROUP BY month(due_date) ORDER BY month(due_date) ASC") AS $head){
            $month=$this->super_model->select_column_custom_where("purchase_transaction_head",'month(due_date)',"purchase_id = '$head->purchase_id' ORDER BY month($head->due_date) ASC LIMIT 1");
            $year=$this->super_model->select_column_custom_where("purchase_transaction_head",'year(due_date)',"purchase_id = '$head->purchase_id' ORDER BY year($head->due_date) ASC LIMIT 1");
            $monthName = date("F", mktime(0, 0, 0, $month, 10));
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($monthName."".$year);
            foreach(range('A','M') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A1', "Item No.");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B1', "Transaction Number");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C1', "Billing Period");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D1', "Due Date");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E1', "STl ID/TPShort name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F1', "Billing ID");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G1', "Company Full Name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H1', "TIN");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I1', "Vatable Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J1', "Zero Rated EcoZones Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K1', "Vat On Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L1', "EWT");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M1', "Total");
            $objPHPExcel->getActiveSheet()->getStyle("A1:M1")->applyFromArray($styleArray);

            $total_vatables_purchases=array();
            $total_zero_rated_ecozones=array();
            $total_vat_on_purchases=array();
            $total_ewt=array();
            $overall_total=array();

            $num=2;
            $itemno=1;
                foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pah INNER JOIN purchase_transaction_details pad ON pah.purchase_id = pad.purchase_id WHERE due_date='$head->due_date' AND $qu ORDER BY billing_from ASC, reference_number ASC") AS $pah){
                    $billing_date = date("M. d, Y",strtotime($pah->billing_from))." - ".date("M. d, Y",strtotime($pah->billing_to));
                    $tin=$this->super_model->select_column_where("participant","tin","billing_id",$pah->billing_id);
                    $participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$pah->billing_id);
           
                    if(!empty($pah->company_name) && date('Y',strtotime($pah->create_date))==date('Y')){
                        $comp_name=$pah->company_name;
                    }else{
                        $comp_name=$participant_name;
                    }

                    $zero_rated=$pah->zero_rated_purchases+$pah->zero_rated_ecozones;
                    $total=($pah->vatables_purchases+$zero_rated+$pah->vat_on_purchases)-$pah->ewt;

                    $total_vatables_purchases[]=$pah->vatables_purchases;
                    $total_zero_rated_ecozones[]=$pah->zero_rated_ecozones;
                    $total_vat_on_purchases[]=$pah->vat_on_purchases;
                    $total_ewt[]=$pah->ewt;
                    $overall_total[]=$total;
                
                // //if($value['tin']==$pah->tin){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $itemno);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B'.$num, $pah->reference_number);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $billing_date);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D'.$num, $pah->due_date);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, $pah->short_name);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F'.$num, $pah->actual_billing_id);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G'.$num, $comp_name);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, $tin);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I'.$num, "-".$pah->vatables_purchases);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J'.$num, "-".$pah->zero_rated_ecozones);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K'.$num, "-".$pah->vat_on_purchases);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, $pah->ewt);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M'.$num, "-".$total);
                // }

                    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->getColor()->setRGB ('FFFFFF');
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('BFD7ED');
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('BFD7ED');
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getFont()->getColor()->setRGB ('FF0000');
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":M".$num)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$num.":M".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$num.":M".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $num++;
                    $itemno++;
                 }

                $a = $num;
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$a.":M".$a)->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$a.":M".$a)->getFont()->setItalic(true);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$a.":M".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$a.':M'.$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$a.':M'.$a)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                    $objPHPExcel->getActiveSheet()->setCellValue('I'.$a, "-".array_sum($total_vatables_purchases));
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$a, "-".array_sum($total_zero_rated_ecozones));
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$a, "-".array_sum($total_vat_on_purchases));
                    $objPHPExcel->getActiveSheet()->setCellValue('L'.$a, array_sum($total_ewt));
                    $objPHPExcel->getActiveSheet()->setCellValue('M'.$a, "-".array_sum($overall_total));
                $num--;
                 

                    
            $sheetno++;
            }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Purchases Wesm Adjustment All Transactions.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Sales Wesm Adjustment All Transcations.xlsx"');
        // readfile($exportfilename);
    }

    public function sales_all_adjustment(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        $original=$this->uri->segment(6);
        $scanned=$this->uri->segment(7);
        $year=$this->uri->segment(8);
        $data['from'] = $from;
        $data['to'] = $to;
        $part=$this->super_model->select_column_where("participant","participant_name","tin",$participant);
        $data['part'] = $part;
        $data['original'] = $original;
        $data['scanned'] = $scanned;
        $data['years'] = $year;

        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant WHERE participant_name != '' GROUP BY tin ORDER BY participant_name");
        $data['date'] = $this->super_model->custom_query("SELECT DISTINCT due_date FROM sales_adjustment_head WHERE due_date!='' AND saved = '1'");
        $sql="";

        if($from!='null' && $to != 'null'){
            //$sql.= "YEAR(billing_from) >= '$from_year' AND YEAR(billing_to) <= '$to_year' AND ";
            $sql.= "due_date BETWEEN '$from' AND '$to' AND ";
        }if($year!='null'){
            $sql.= "YEAR(due_date) = '$year' AND ";
        } if($participant!='null'){
             //$sql.= "tin = '$participant' AND ";
            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";
        } if($original!='null' && isset($original)){
             $sql.= "original_copy = '$original' AND "; 
        } if($scanned!='null'  && isset($scanned)){
             $sql.= "scanned_copy = '$scanned' AND "; 
        }

        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND ".$query;

        $total_sum[]=0;
                foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_head sah INNER JOIN sales_adjustment_details sad ON sah.sales_adjustment_id = sad.sales_adjustment_id WHERE $qu ORDER BY billing_from ASC, due_date ASC, sad.short_name ASC") AS $sah){
            $participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$sah->billing_id);
            // $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id ", $sah->sales_adjustment_id);
            // $participant_name=$this->super_model->select_column_where("sales_adjustment_details", "company_name", "adjustment_detail_id ", $sah->adjustment_detail_id);
            $short_name=$this->super_model->select_column_where("sales_adjustment_details", "short_name", "adjustment_detail_id", $sah->adjustment_detail_id);
            $or_no=$this->super_model->select_column_custom_where("collection_details","series_number","reference_no='$sah->reference_number' AND settlement_id='$short_name'");
            if(!empty($sah->company_name) && date('Y',strtotime($sah->create_date))==date('Y')){
                    $comp_name=$sah->company_name;
                }else{
                    $comp_name=$participant_name;
                }
            $zero_rated=$sah->zero_rated_sales+$sah->zero_rated_ecozones;
            $total=($sah->vatable_sales+$zero_rated+$sah->vat_on_sales)-$sah->ewt;
            $total_sum[]=$total;

            $data['salesad_all'][]=array(
                'participant_name'=>$comp_name,
                'billing_id'=>$sah->billing_id,
                'due_date'=>$sah->due_date,
                'reference_number'=>$sah->reference_number,
                'adjustment_detail_id'=>$sah->adjustment_detail_id,
                'billing_from'=>$sah->billing_from,
                'billing_to'=>$sah->billing_to,
                'vatable_sales'=>$sah->vatable_sales,
                'vat_on_sales'=>$sah->vat_on_sales,
                'ewt'=>$sah->ewt,
                'ewt_amount'=>$sah->ewt_amount,
                'original_copy'=>$sah->original_copy,
                'scanned_copy'=>$sah->scanned_copy,
                'zero_rated'=>$zero_rated,
                'total'=>$total,
                'or_no'=>$or_no,
            );
        }
        $data['total_sum']=array_sum($total_sum);
        $this->load->view('reports/sales_all_adjustment',$data);
        $this->load->view('template/footer');
    }

        public function export_sales_adjustment_all_persn(){
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        $year=$this->uri->segment(6);
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Sales Wesm Adjustment All Transcations.xlsx";
        $sql='';

        if($from!='null' && $to != 'null'){
            // $sql.= "YEAR(billing_from) >= '$from_year' AND YEAR(billing_to) <= '$to_year' AND ";
            $sql.= "due_date BETWEEN '$from' AND '$to' AND ";
        } 
        if($participant!='null'){
             $sql.= " tin = '$participant' AND ";
                /*$par=array();
                foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                    $par[]="'".$p->settlement_id."'";
                }
                $imp=implode(',',$par);
                $sql.= " short_name IN($imp) AND ";*/
        }
        if($year!='null'){
             $sql.= " YEAR(due_date) = '$year' AND "; 
        }

        $query=substr($sql,0,-4);
        if($participant !='null' || $from != 'null' || $to != 'null' || $year != 'null'){
            $qu = " saved = '1' AND ".$query;
        }else{
             $qu = " saved = '1'";
        }
        $sheetno=0;
            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN
                    )
                )
            );
            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_head sah INNER JOIN sales_adjustment_details sad ON sah.sales_adjustment_id = sad.sales_adjustment_id INNER JOIN participant p ON p.settlement_id = sad.short_name WHERE participant_name != '' AND $qu GROUP BY tin ORDER BY settlement_id ASC") AS $head){
            $settlement_id=$this->super_model->select_column_custom_where("participant",'settlement_id',"tin = '$head->tin' ORDER BY settlement_id ASC LIMIT 1");
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($settlement_id);
            foreach(range('A','N') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A1', "Due Date");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B1', "Billing Period");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C1', "Billing ID");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D1', "Transaction Reference Number");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E1', "Company Name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F1', "Vatables Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G1', "Zero-rated Ecozones Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H1', "Vat on Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I1', "EWT Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J1', "Total");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K1', "EWT Amount");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L1', "Original Copy");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M1', "Scanned Copy");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('N1', "OR Number");
            $objPHPExcel->getActiveSheet()->getStyle("A1:M1")->applyFromArray($styleArray);

            //$total_vatables=array();
            //$total_vat=array();
            $total_ewt=array();
            //$total_zero_rated=array();
            $total_ewt_amount=array();
            //$overall_total=array();

            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_head sah INNER JOIN sales_adjustment_details sad ON sah.sales_adjustment_id = sad.sales_adjustment_id INNER JOIN participant p ON p.billing_id = sad.billing_id WHERE tin='$head->tin' AND participant_name != '' AND $qu ORDER BY due_date ASC, billing_from ASC, reference_number ASC, p.billing_id ASC") AS $sah){
            //$participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$sah->billing_id);
            // $zero_rated=$sah->zero_rated_sales+$sah->zero_rated_ecozones;
            // $total=($sah->vatable_sales+$zero_rated+$sah->vat_on_sales)-$sah->ewt;
            // $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id ", $sah->sales_adjustment_id );
            // $participant_name=$this->super_model->select_column_where("sales_adjustment_details", "company_name", "adjustment_detail_id ", $sah->adjustment_detail_id );
            $short_name=$this->super_model->select_column_where("sales_adjustment_details", "short_name", "adjustment_detail_id", $sah->adjustment_detail_id);
            $or_no=$this->super_model->select_column_custom_where("collection_details","series_number","reference_no='$sah->reference_number' AND settlement_id='$short_name'");
            if(!empty($sah->company_name) && date('Y',strtotime($sah->create_date))==date('Y')){
                $comp_name=$sah->company_name;
            }else{
                $comp_name=$sah->participant_name;
            }
            $billing_date = date("M. d, Y",strtotime($sah->billing_from))." - ".date("M. d, Y",strtotime($sah->billing_to));
            $tin=$this->super_model->select_column_where("participant","tin","billing_id",$sah->billing_id);

            $salesalladjustment[]=array(
                    'due_date'=>$sah->due_date,
                    'billing_date'=>$billing_date,
                    'participant_name'=>$comp_name,
                    'billing_id'=>$sah->billing_id,
                    'reference_number'=>$sah->reference_number,
                    'vatable_sales'=>$sah->vatable_sales,
                    'vat_on_sales'=>$sah->vat_on_sales,
                    'ewt'=>$sah->ewt,
                    'ewt_amount'=>$sah->ewt_amount,
                    'original_copy'=>$sah->original_copy,
                    'scanned_copy'=>$sah->scanned_copy,
                    'short_name'=>$sah->short_name,
                    'zero_rated_sales'=>$sah->zero_rated_sales,
                    'zero_rated_ecozones'=>$sah->zero_rated_ecozones,
                    'tin'=>$tin,
                    'or_no'=>$or_no,
                    //'zero_rated'=>$zero_rated,
                    //'total'=>$total,
                );

            }
                $row = 2;
                $startRow = -1;
                $previousKey = '';
                $previousKey1 = '';
                $num=2;
                foreach($salesalladjustment AS $index => $value){
                     $previousKey = $value['due_date'];
                    if($startRow == -1){
                        $startRow = $row;
                        $previousKey = $value['due_date'];
                        $previousKey1 = $value['billing_date'];
                    }

                    
                $zero_rated=$value['zero_rated_sales']+$value['zero_rated_ecozones'];
                $total=($value['vatable_sales']+$zero_rated+$value['vat_on_sales'])-$value['ewt'];
                if($value['tin']==$sah->tin){
                // $total_vatables[]=$value['vatable_sales'];
                // $total_vat[]=$value['vat_on_sales'];
                $total_ewt[]=$value['ewt'];
                //$total_zero_rated[]=$zero_rated;
                $total_ewt_amount[]=$value['ewt_amount'];
                //$overall_total[]=$total;
                //if($value['short_name']==$sah->short_name){
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $value['due_date']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B'.$num, $value['billing_date']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $value['billing_id']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D'.$num, $value['reference_number']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, $value['participant_name']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F'.$num, $value['vatable_sales']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G'.$num, $zero_rated);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, $value['vat_on_sales']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I'.$num, "-".$value['ewt']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J'.$num, $total);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K'.$num, $value['ewt_amount']);
                if($value['original_copy']==1){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, "Yes");
                }else if($value['original_copy']==0){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, "No");
                }else{
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, "");
                }
                if($value['scanned_copy']==1){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M'.$num, "Yes");
                }else if($value['scanned_copy']==0){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M'.$num, "No");
                }else{
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M'.$num, "");
                }
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('N'.$num, $value['or_no']);

                $nextKey = isset($salesalladjustment[$index+1]) ? $salesalladjustment[$index+1]['due_date'] : null;
                $nextKey1 = isset($salesalladjustment[$index+1]) ? $salesalladjustment[$index+1]['billing_date'] : null;
               // echo $previousKey."-".$nextKey."-".$value['due_date']." - ".$value['short_name']." - ".$value['billing_date']."<br>";
                if($row >= $startRow && (($previousKey <> $nextKey) || ($nextKey == null))){
                    $cellToMerge = 'A'.$startRow.':A'.$row;
                    $objPHPExcel->getActiveSheet()->mergeCells($cellToMerge);
                    // if($row >= $startRow && (($previousKey1 <> $nextKey1) || ($nextKey1 == null))){
                    //     $cellToMerge = 'B'.$startRow.':B'.$row;
                    //     $objPHPExcel->getActiveSheet()->mergeCells($cellToMerge);
                    // }
                    $startRow = -1;
                }
                $row++;

                $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->getColor()->setRGB ('FFFFFF');
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":N".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num.":N".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":K".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                $num++;
            }
        }

                $a = $num;
                    //$objPHPExcel->getActiveSheet()->getStyle('D'.$a)->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$a.":K".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle("I".$a.':K'.$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    //$objPHPExcel->getActiveSheet()->setCellValue('D'.$a, "TOTAL: ");
                    //$objPHPExcel->getActiveSheet()->setCellValue('E'.$a, array_sum($total_vatables));
                    //$objPHPExcel->getActiveSheet()->setCellValue('F'.$a, array_sum($total_zero_rated));
                    //$objPHPExcel->getActiveSheet()->setCellValue('G'.$a, array_sum($total_vat));
                    $objPHPExcel->getActiveSheet()->setCellValue('I'.$a, "-".array_sum($total_ewt));
                    //$objPHPExcel->getActiveSheet()->setCellValue('I'.$a, array_sum($overall_total));
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$a, array_sum($total_ewt_amount));
                $num--;
            $sheetno++;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Sales Wesm Adjustment All Transcations.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Sales Wesm Adjustment All Transcations.xlsx"');
        // readfile($exportfilename);
    }

    public function export_sales_adjustment_all_perm(){
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        $year=$this->uri->segment(6);
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Sales Wesm Adjustment All Transcations.xlsx";
        $sql='';

        if($from!='null' && $to != 'null'){
            // $sql.= "YEAR(billing_from) >= '$from_year' AND YEAR(billing_to) <= '$to_year' AND ";
            $sql.= "due_date BETWEEN '$from' AND '$to' AND ";
        } 
        if($participant!='null'){
             $sql.= " tin = '$participant' AND ";
                /*$par=array();
                foreach($this->super_model->select_custom_where('participant',"tin='$participant'") AS $p){
                    $par[]="'".$p->settlement_id."'";
                }
                $imp=implode(',',$par);
                $sql.= " short_name IN($imp) AND ";*/
        }
        if($year!='null'){
             $sql.= " YEAR(due_date) = '$year' AND "; 
        }

        $query=substr($sql,0,-4);
        if($participant !='null' || $from != 'null' || $to != 'null' || $year != 'null'){
            $qu = " saved = '1' AND ".$query;
        }else{
             $qu = " saved = '1'";
        }
        $sheetno=0;
            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN
                    )
                )
            );
            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_head sah INNER JOIN sales_adjustment_details sad ON sah.sales_adjustment_id = sad.sales_adjustment_id WHERE $qu GROUP BY month(due_date) ORDER BY month(due_date) ASC") AS $head){
            $month=$this->super_model->select_column_custom_where("sales_adjustment_head",'month(due_date)',"sales_adjustment_id = '$head->sales_adjustment_id' ORDER BY month($head->due_date) ASC LIMIT 1");
            $year=$this->super_model->select_column_custom_where("sales_adjustment_head",'year(due_date)',"sales_adjustment_id = '$head->sales_adjustment_id' ORDER BY year($head->due_date) ASC LIMIT 1");
            $monthName = date("F", mktime(0, 0, 0, $month, 10));
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($monthName."".$year);
            foreach(range('A','M') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A1', "Item No.");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B1', "Transaction Number");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C1', "Billing Period");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D1', "Due Date");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E1', "STl ID/TPShort name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F1', "Billing ID");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G1', "Company Full Name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H1', "TIN");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I1', "Vatable Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J1', "Zero Rated EcoZones Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K1', "Vat On Sales");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L1', "EWT");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M1', "Total");
            $objPHPExcel->getActiveSheet()->getStyle("A1:M1")->applyFromArray($styleArray);

            $total_vatable_sales=array();
            $total_zero_rated_ecozones=array();
            $total_vat_on_sales=array();
            $total_ewt=array();
            $overall_total=array();

            $num=2;
            $itemno=1;
                foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_head sah INNER JOIN sales_adjustment_details sad ON sah.sales_adjustment_id = sad.sales_adjustment_id WHERE due_date='$head->due_date' AND $qu ORDER BY billing_from ASC, reference_number ASC") AS $sah){
                    $billing_date = date("M. d, Y",strtotime($sah->billing_from))." - ".date("M. d, Y",strtotime($sah->billing_to));
                    $tin=$this->super_model->select_column_where("participant","tin","billing_id",$sah->billing_id);
                    $participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$sah->billing_id);
           
                    if(!empty($sah->company_name) && date('Y',strtotime($sah->create_date))==date('Y')){
                        $comp_name=$sah->company_name;
                    }else{
                        $comp_name=$participant_name;
                    }

                    $zero_rated=$sah->zero_rated_sales+$sah->zero_rated_ecozones;
                    $total=($sah->vatable_sales+$zero_rated+$sah->vat_on_sales)-$sah->ewt;

                    $total_vatable_sales[]=$sah->vatable_sales;
                    $total_zero_rated_ecozones[]=$sah->zero_rated_ecozones;
                    $total_vat_on_sales[]=$sah->vat_on_sales;
                    $total_ewt[]=$sah->ewt;
                    $overall_total[]=$total;
                
                // //if($value['tin']==$sah->tin){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $itemno);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B'.$num, $sah->reference_number);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $billing_date);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D'.$num, $sah->due_date);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, $sah->short_name);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F'.$num, $sah->actual_billing_id);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G'.$num, $comp_name);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, $tin);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I'.$num, $sah->vatable_sales);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J'.$num, $sah->zero_rated_ecozones);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K'.$num, $sah->vat_on_sales);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, "-".$sah->ewt);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M'.$num, $total);
                // }

                    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->getColor()->setRGB ('FFFFFF');
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('BFD7ED');
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('BFD7ED');
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getFont()->getColor()->setRGB ('FF0000');
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":M".$num)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$num.":M".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$num.":M".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $num++;
                    $itemno++;
                 }

                $a = $num;
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$a.":M".$a)->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$a.":M".$a)->getFont()->setItalic(true);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$a.":M".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$a.':M'.$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$a.':M'.$a)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
                    $objPHPExcel->getActiveSheet()->setCellValue('I'.$a, array_sum($total_vatable_sales));
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$a, array_sum($total_zero_rated_ecozones));
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$a, array_sum($total_vat_on_sales));
                    $objPHPExcel->getActiveSheet()->setCellValue('L'.$a, "-".array_sum($total_ewt));
                    $objPHPExcel->getActiveSheet()->setCellValue('M'.$a, array_sum($overall_total));
                $num--;
                 

                    
            $sheetno++;
            }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Sales Wesm Adjustment All Transcations.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Sales Wesm Adjustment All Transcations.xlsx"');
        // readfile($exportfilename);
    }

    public function collection_report(){

        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $date=$this->uri->segment(3);
        $ref_no=$this->uri->segment(4);
        $stl_id=$this->uri->segment(5);
        $data['date'] = $date;
        $data['ref_no'] = $ref_no;
        $data['stl_id'] = $stl_id;
        $data['collection_date'] = $this->super_model->custom_query("SELECT DISTINCT collection_date FROM collection_head WHERE saved != '0'");
        $data['reference_no'] = $this->super_model->custom_query("SELECT DISTINCT reference_no FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE reference_no!='' AND saved != '0'");
        $data['buyer'] = $this->super_model->custom_query("SELECT DISTINCT settlement_id,buyer_fullname FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE reference_no!='' AND saved != '0' GROUP BY buyer_fullname");

         $sql="";

        if($date!='null'){
            $sql.= "collection_date = '$date' AND ";
        } if($ref_no!='null'){
             $sql.= "reference_no = '$ref_no' AND "; 
        } if($stl_id!='null'){
             $sql.= "settlement_id = '$stl_id' AND "; 
        }

        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND ".$query;

        foreach($this->super_model->custom_query("SELECT * FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE $qu") AS $col){
            $count_series=$this->super_model->count_custom_where("collection_details","series_number='$col->series_number' AND series_number!='' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND collection_details_id = '$col->collection_details_id'");
            $sum_amount= $this->super_model->select_sum_where("collection_details","amount","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
            $sum_zero_rated= $this->super_model->select_sum_where("collection_details","zero_rated","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
            $sum_zero_rated_ecozone= $this->super_model->select_sum_where("collection_details","zero_rated_ecozone","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
            $sum_vat = $this->super_model->select_sum_where("collection_details","vat","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
            $sum_ewt= $this->super_model->select_sum_where("collection_details","ewt","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
            // $total=($col->amount + $col->zero_rated + $col->zero_rated_ecozone + $col->vat)-$col->ewt; 
            // if($count_series>=1){
            //     $overall_total=($sum_amount + $sum_zero_rated + $sum_zero_rated_ecozone + $sum_vat)-$sum_ewt;
            // }if($count_series<=2){
            //     $overall_total=($sum_amount + $sum_zero_rated + $sum_zero_rated_ecozone + $sum_vat)-$sum_ewt;
            // }else{
            //     $overall_total=($col->amount + $col->zero_rated + $col->zero_rated_ecozone + $col->vat)-$col->ewt; 
            // }
            $overall_total=($sum_amount + $sum_zero_rated + $sum_zero_rated_ecozone + $sum_vat)-$sum_ewt;

            $data['collection'][]=array(
                "count_series"=>$count_series,
                "collection_details_id"=>$col->collection_details_id,
                "collection_id"=>$col->collection_id,
                "settlement_id"=>$col->settlement_id,
                "series_number"=>$col->series_number,
                "collection_date"=>$col->collection_date,
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
                "sum_amount"=>$sum_amount,
                "sum_zero_rated"=>$sum_zero_rated,
                "sum_zero_rated_ecozone"=>$sum_zero_rated_ecozone,
                "sum_vat"=>$sum_vat,
                "sum_ewt"=>$sum_ewt,
            );

        }
        $this->load->view('reports/collection_report',$data);
        $this->load->view('template/footer');
    }

    public function export_collection_report(){

        $date=$this->uri->segment(3);
        $ref_no=$this->uri->segment(4);
        $stl_id=$this->uri->segment(5);
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Collection Reports.xlsx";
        $sql='';
        if($date!='null'){
             $sql.= " collection_date = '$date' AND "; 
        } if($ref_no!='null'){
             $sql.= " reference_no = '$ref_no' AND "; 
        } if($stl_id!='null'){
             $sql.= " settlement_id = '$stl_id' AND "; 
        }

        $query=substr($sql,0,-4);
        if($date !='null' || $ref_no != 'null' || $stl_id != 'null'){
            $qu = " saved = '1' AND ".$query;
        }else{
             $qu = " saved = '1'";
        }

        if($date != 'null'){
            $collection_date = date('F d, Y', strtotime($date));
        // }else if($ref_no != null){
        //     $collection_date = $this->super_model->custom_query("SELECT DISTINCT collection_date FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE reference_no = '$ref_no'");
        // }else if($stl_id != null){
        //     $collection_date = $this->super_model->custom_query("SELECT DISTINCT collection_date FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE settlement_id = '$stl_id'");
        }

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', "MP Name: CENTRAL NEGROS POWER RELIABILITY, INC");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', "MP ID No.: CENPRI");
        if($date != 'null'){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', "As of $collection_date");
        }else{
             $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', "");
        }

        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        //     $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    )
                )
            );
            // $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            $objPHPExcel->setActiveSheetIndex(0);
            foreach(range('A','O') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }

            foreach(range('B2','B4') as $columnID1) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID1)
                    ->setAutoSize(false);
            }
            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6', "OR#");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B6', "Billing Remarks");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C6', "Date");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D6', "Particulars");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E6', "Received From (Buyer STL ID)");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F6', "Received From (Buyer Full name)");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G6', "Statement No (Seller)");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H6', "DefInt");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I6', "Vatable Sales");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J6', "Zero Rated Sales");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K6', "Zero Rated Ecozone");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L6', "VAT on Sales");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M6', "Withholding Tax");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N6', "Total");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O6', "OR Remarks");
            $objPHPExcel->getActiveSheet()->getStyle("A6:O6")->applyFromArray($styleArray);

        $data=array();

       // echo  $qu . "<br>";
            $row=7;
            $row_final=7;
         foreach($this->super_model->custom_query("SELECT DISTINCT  reference_no, settlement_id, collection_date, series_number FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE $qu ORDER BY series_number ASC") AS $q){


             
              $x=1;
              $final=1;
                $count = $this->super_model->count_custom("SELECT * FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE $qu AND reference_no = '$q->reference_no' AND settlement_id = '$q->settlement_id' AND collection_date = '$q->collection_date' AND series_number='$q->series_number'");
                foreach($this->super_model->custom_query("SELECT * FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE $qu AND reference_no = '$q->reference_no' 
                    AND settlement_id = '$q->settlement_id' AND collection_date = '$q->collection_date' AND series_number='$q->series_number' ORDER BY series_number ASC") AS $col){

                    $sum_amount= $this->super_model->select_sum_where("collection_details","amount","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                    $sum_zero_rated= $this->super_model->select_sum_where("collection_details","zero_rated","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                    $sum_zero_rated_ecozone= $this->super_model->select_sum_where("collection_details","zero_rated_ecozone","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                    $sum_vat = $this->super_model->select_sum_where("collection_details","vat","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                    $sum_ewt= $this->super_model->select_sum_where("collection_details","ewt","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                    $overall_total=($sum_amount + $sum_zero_rated + $sum_zero_rated_ecozone + $sum_vat)-$sum_ewt;

                    //echo $row . " ". $x. " - " .  $col->billing_remarks . " - ". $col->reference_no . ' - '  . $col->settlement_id . ", " .  $count . "<br>";

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$row, $col->billing_remarks);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$row, $col->particulars);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row, $col->settlement_id);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$row, $col->buyer_fullname);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$row, $col->reference_no);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$row, $col->amount);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$row, $col->zero_rated);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$row, $col->zero_rated_ecozone);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$row, $col->vat);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$row, $col->ewt);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$row, $col->total);

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$row.":O".$row)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$row.":O".$row)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$row.":N".$row)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);

                        if($count == $x){
                            $x=$x+2; 
                            $row = $row+2;
                            $final = $x-1;
                            $row_final = $row-1;
                            $row++;
                         } else { 
                            $x++; 
                            $row++;
                        }

                       //  if($final!=1){
                       // echo $row_final . "<br>";
                       //  }
                    
                    if($final!=1){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row_final, $col->series_number);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$row_final, date('F d, Y', strtotime($col->collection_date)));
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row_final, $col->settlement_id);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$row_final, $col->buyer_fullname);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$row_final, $col->reference_no);
                        if($col->defint != 0){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$row_final, $col->defint);
                        }
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$row_final, $sum_amount);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$row_final, $sum_zero_rated);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$row_final, $sum_zero_rated_ecozone);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$row_final, $sum_vat);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$row_final, $sum_ewt);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$row_final, $overall_total);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$row_final, $col->or_no_remarks);

                        if($col->defint != 0){
                            $objPHPExcel->getActiveSheet()->getStyle('H'.$row_final)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('BCD2E8');
                        }
                        $objPHPExcel->getActiveSheet()->getStyle('I'.$row_final.":N".$row_final)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('BCD2E8');
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$row_final.":O".$row_final)->applyFromArray($styleArray);
                        $objPHPExcel->getActiveSheet()->getStyle('H'.$row_final.":O".$row_final)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('H'.$row_final.":N".$row_final)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    }

                $objPHPExcel->getActiveSheet()->getStyle('A6:O6')->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('A6:O6')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                $objPHPExcel->getActiveSheet()->getStyle('A6:O6')->getFont()->getColor()->setRGB ('FFFFFF');
                $objPHPExcel->getActiveSheet()->getStyle('A6:O6')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A6:O6')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                } 
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Collection Reports.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Collection Reports.xlsx"');
        // readfile($exportfilename);

    }

    public function export_iemop(){

        $date=$this->uri->segment(3);
        $ref_no=$this->uri->segment(4);
        $stl_id=$this->uri->segment(5);
        $objPHPExcel = new Spreadsheet();
        $exportfilename="IEMOP Collection Reports.xlsx";
        $sql='';
        if($date!='null'){
             $sql.= " collection_date = '$date' AND "; 
        } if($ref_no!='null'){
             $sql.= " reference_no = '$ref_no' AND "; 
        } if($stl_id!='null'){
             $sql.= " settlement_id = '$stl_id' AND "; 
        }

        $query=substr($sql,0,-4);
        if($date !='null' || $ref_no != 'null' || $stl_id != 'null'){
            $qu = " saved = '1' AND ".$query;
        }else{
             $qu = " saved = '1'";
        }

        if($date != 'null'){
            $collection_date = date('F d, Y', strtotime($date));
        }

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', "MP Name: CENTRAL NEGROS POWER RELIABILITY, INC");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', "MP ID No.: CENPRI");
        if($date != 'null'){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', "As of $collection_date");
        }else{
             $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', "");
        }

            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    )
                )
            );

            $objPHPExcel->getActiveSheet(0)->setTitle('BIR SLSP');
            $objPHPExcel->setActiveSheetIndex(0);
            foreach(range('A','O') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }

            foreach(range('B2','B4') as $columnID1) {
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID1)
                    ->setAutoSize(false);
            }
            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6', "OR#");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B6', "Date");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C6', "Received From (Buyer STL ID)");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D6', "Received From (Buyer Full name)");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E6', "TIN No");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F6', "Address");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G6', "Zip Code");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H6', "Statement No (Seller)");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I6', "DefInt");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J6', "Vatable Sales");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K6', "Zero Rated Sales");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L6', "Zero Rated Ecozone");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M6', "VAT on Sales");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N6', "Withholding Tax");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O6', "Total");
            $objPHPExcel->getActiveSheet()->getStyle("A6:O6")->applyFromArray($styleArray);

        //$data=array();

            $row=7;
                foreach($this->super_model->custom_query("SELECT * FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE $qu GROUP BY series_number ORDER BY series_number ASC") AS $col){

                    $tin = $this->super_model->select_column_where("participant","tin","settlement_id",$col->settlement_id);
                    $address = $this->super_model->select_column_where("participant","registered_address","settlement_id",$col->settlement_id);
                    $zip = $this->super_model->select_column_where("participant","zip_code","settlement_id",$col->settlement_id);

                    $sum_amount= $this->super_model->select_sum_where("collection_details","amount","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                    $sum_zero_rated= $this->super_model->select_sum_where("collection_details","zero_rated","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                    $sum_zero_rated_ecozone= $this->super_model->select_sum_where("collection_details","zero_rated_ecozone","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                    $sum_vat = $this->super_model->select_sum_where("collection_details","vat","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                    $sum_ewt= $this->super_model->select_sum_where("collection_details","ewt","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                    $overall_total=($sum_amount + $sum_zero_rated + $sum_zero_rated_ecozone + $sum_vat)-$sum_ewt;

                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, $col->series_number);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$row, date('F d, Y', strtotime($col->collection_date)));
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$row, $col->settlement_id);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$row, $col->buyer_fullname);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row, $tin);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$row, $address);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$row, $zip);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$row, $col->reference_no);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$row, $col->defint);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$row, $sum_amount);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$row, $sum_zero_rated);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$row, $sum_zero_rated_ecozone);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$row, $sum_vat);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$row, $sum_ewt);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$row, $overall_total);

                        $objPHPExcel->getActiveSheet(0)->getStyle('A'.$row.":O".$row)->applyFromArray($styleArray);
                        $objPHPExcel->getActiveSheet(0)->getStyle('G'.$row)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet(0)->getStyle('I'.$row.":O".$row)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet(0)->getStyle('I'.$row.":O".$row)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $row++;
                    }

                    $objPHPExcel->getActiveSheet(0)->getStyle('A6:O6')->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet(0)->getStyle('A6:O6')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                    $objPHPExcel->getActiveSheet(0)->getStyle('A6:O6')->getFont()->getColor()->setRGB ('FFFFFF');
                    $objPHPExcel->getActiveSheet(0)->getStyle('A6:O6')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet(0)->getStyle('A6:O6')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);

                    $objPHPExcel->createSheet();
                    $objPHPExcel->setActiveSheetIndex(1);
                    $objPHPExcel->getActiveSheet(1)->setTitle('BIR SLSP - Zero Rated Ecozone');

                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue('B2', "MP Name: CENTRAL NEGROS POWER RELIABILITY, INC");
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue('B3', "MP ID No.: CENPRI");
                    if($date != 'null'){
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue('B4', "As of $collection_date");
                    }else{
                         $objPHPExcel->setActiveSheetIndex(1)->setCellValue('B4', "");
                    }


                    $objPHPExcel->setActiveSheetIndex(1);
                    foreach(range('A','I') as $columnID){
                        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                    }

                    foreach(range('B2','B4') as $columnID1) {
                        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID1)
                            ->setAutoSize(false);
                    }
                    
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue('A6', "OR#");
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue('B6', "Date");
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue('C6', "Received From (Buyer STL ID)");
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue('D6', "Received From (Buyer Full name)");
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue('E6', "TIN No");
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue('F6', "Address");
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue('G6', "Zip Code");
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue('H6', "Statement No (Seller)");
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue('I6', "Zero Rated Ecozone");
                    $objPHPExcel->getActiveSheet(1)->getStyle("A6:I6")->applyFromArray($styleArray);

                //$data=array();

                    $row=7;
                        foreach($this->super_model->custom_query("SELECT * FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE $qu GROUP BY series_number ORDER BY series_number ASC") AS $col){

                            $tin = $this->super_model->select_column_where("participant","tin","settlement_id",$col->settlement_id);
                            $address = $this->super_model->select_column_where("participant","registered_address","settlement_id",$col->settlement_id);
                            $zip = $this->super_model->select_column_where("participant","zip_code","settlement_id",$col->settlement_id);

                            $sum_amount= $this->super_model->select_sum_where("collection_details","amount","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                            $sum_zero_rated= $this->super_model->select_sum_where("collection_details","zero_rated","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                            $sum_zero_rated_ecozone= $this->super_model->select_sum_where("collection_details","zero_rated_ecozone","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                            $sum_vat = $this->super_model->select_sum_where("collection_details","vat","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                            $sum_ewt= $this->super_model->select_sum_where("collection_details","ewt","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                            $overall_total=($sum_amount + $sum_zero_rated + $sum_zero_rated_ecozone + $sum_vat)-$sum_ewt;

                                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('A'.$row, $col->series_number);
                                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('B'.$row, date('F d, Y', strtotime($col->collection_date)));
                                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('C'.$row, $col->settlement_id);
                                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('D'.$row, $col->buyer_fullname);
                                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('E'.$row, $tin);
                                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('F'.$row, $address);
                                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('G'.$row, $zip);
                                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('H'.$row, $col->reference_no);
                                $objPHPExcel->setActiveSheetIndex(1)->setCellValue('I'.$row, $sum_zero_rated_ecozone);

                                $objPHPExcel->getActiveSheet(1)->getStyle('A'.$row.":I".$row)->applyFromArray($styleArray);
                                $objPHPExcel->getActiveSheet(1)->getStyle('G'.$row)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                                $objPHPExcel->getActiveSheet(1)->getStyle('I'.$row)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                                $objPHPExcel->getActiveSheet(1)->getStyle('I'.$row)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                                $row++;
                            }

                    $objPHPExcel->getActiveSheet(1)->getStyle('A6:I6')->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet(1)->getStyle('A6:I6')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                    $objPHPExcel->getActiveSheet(1)->getStyle('A6:I6')->getFont()->getColor()->setRGB ('FFFFFF');
                    $objPHPExcel->getActiveSheet(1)->getStyle('A6:I6')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet(1)->getStyle('A6:I6')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);

            
                    $objPHPExcel->createSheet();
                    $objPHPExcel->setActiveSheetIndex(2);
                    $objPHPExcel->getActiveSheet(2)->setTitle('BIR SLSP - Vat on Sales');

                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('B2', "MP Name: CENTRAL NEGROS POWER RELIABILITY, INC");
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('B3', "MP ID No.: CENPRI");
                    if($date != 'null'){
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('B4', "As of $collection_date");
                    }else{
                         $objPHPExcel->setActiveSheetIndex(2)->setCellValue('B4', "");
                    }


                    $objPHPExcel->setActiveSheetIndex(2);
                    foreach(range('A','O') as $columnID){
                        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                    }

                    foreach(range('B2','B4') as $columnID1) {
                        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID1)
                            ->setAutoSize(false);
                    }
                    
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('A6', "OR#");
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('B6', "Date");
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('C6', "Received From (Buyer STL ID)");
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('D6', "Received From (Buyer Full name)");
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('E6', "TIN No");
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('F6', "Address");
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('G6', "Zip Code");
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('H6', "Statement No (Seller)");
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('I6', "VAT on Sales");
                    $objPHPExcel->getActiveSheet(2)->getStyle("A6:I6")->applyFromArray($styleArray);

                //$data=array();

                    $row=7;
                        foreach($this->super_model->custom_query("SELECT * FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE $qu GROUP BY series_number ORDER BY series_number ASC") AS $col){

                            $tin = $this->super_model->select_column_where("participant","tin","settlement_id",$col->settlement_id);
                            $address = $this->super_model->select_column_where("participant","registered_address","settlement_id",$col->settlement_id);
                            $zip = $this->super_model->select_column_where("participant","zip_code","settlement_id",$col->settlement_id);

                            $sum_amount= $this->super_model->select_sum_where("collection_details","amount","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                            $sum_zero_rated= $this->super_model->select_sum_where("collection_details","zero_rated","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                            $sum_zero_rated_ecozone= $this->super_model->select_sum_where("collection_details","zero_rated_ecozone","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                            $sum_vat = $this->super_model->select_sum_where("collection_details","vat","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                            $sum_ewt= $this->super_model->select_sum_where("collection_details","ewt","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id' AND series_number = '$col->series_number'");
                            $overall_total=($sum_amount + $sum_zero_rated + $sum_zero_rated_ecozone + $sum_vat)-$sum_ewt;

                                $objPHPExcel->setActiveSheetIndex(2)->setCellValue('A'.$row, $col->series_number);
                                $objPHPExcel->setActiveSheetIndex(2)->setCellValue('B'.$row, date('F d, Y', strtotime($col->collection_date)));
                                $objPHPExcel->setActiveSheetIndex(2)->setCellValue('C'.$row, $col->settlement_id);
                                $objPHPExcel->setActiveSheetIndex(2)->setCellValue('D'.$row, $col->buyer_fullname);
                                $objPHPExcel->setActiveSheetIndex(2)->setCellValue('E'.$row, $tin);
                                $objPHPExcel->setActiveSheetIndex(2)->setCellValue('F'.$row, $address);
                                $objPHPExcel->setActiveSheetIndex(2)->setCellValue('G'.$row, $zip);
                                $objPHPExcel->setActiveSheetIndex(2)->setCellValue('H'.$row, $col->reference_no);
                                $objPHPExcel->setActiveSheetIndex(2)->setCellValue('I'.$row, $sum_vat);

                                $objPHPExcel->getActiveSheet(2)->getStyle('A'.$row.":I".$row)->applyFromArray($styleArray);
                                $objPHPExcel->getActiveSheet(2)->getStyle('G'.$row)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                                $objPHPExcel->getActiveSheet(2)->getStyle('I'.$row)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                                $objPHPExcel->getActiveSheet(2)->getStyle('I'.$row)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                                $row++;
                            }


                $objPHPExcel->getActiveSheet(2)->getStyle('A6:I6')->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet(2)->getStyle('A6:I6')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                $objPHPExcel->getActiveSheet(2)->getStyle('A6:I6')->getFont()->getColor()->setRGB ('FFFFFF');
                $objPHPExcel->getActiveSheet(2)->getStyle('A6:I6')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet(2)->getStyle('A6:I6')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="IEMOP Collection Reports.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');

    }

    public function unpaid_invoices_sales(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['due_date']=$this->super_model->custom_query("SELECT DISTINCT due_date FROM sales_transaction_head WHERE saved='1' ORDER BY due_date ASC");
        $year=$this->uri->segment(3);
        $due_date=$this->uri->segment(4);
        $data['year'] = $year;
        $data['due'] = $due_date;
        $today = date('Y-m-d');
        $sql='';

        if($year!='null' && !empty($year)){
            $sql.= " YEAR(due_date) = '$year' AND ";
        }
        
        if($due_date!='null' && !empty($due_date)){
            $sql.= " due_date = '$due_date' AND ";
        }

        $query=substr($sql,0,-4);

        if(!empty($year) && !empty($due_date)){
             $qu = " saved = '1' AND ".$query;
        }else{
             $qu = "saved = '1' ";
        }
        $data['bill']=array();
        $data['total_vatable_balance']=0;
        $total_vatable_balance=array();
        $data['total_zero_rated_balance']=0;
        $total_zero_rated_balance=array();
        $data['total_zero_ecozones_balance']=0;
        $total_zero_ecozones_balance=array();
        $data['total_vat_balance']=0;
        $total_vat_balance=array();
        $data['total_ewt_balance']=0;
        $total_ewt_balance=array();
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON std.sales_id=sth.sales_id WHERE $qu ORDER BY short_name ASC") AS $ui){
                $count_collection = $this->super_model->count_custom_where("collection_details", "reference_no='$ui->reference_number' AND settlement_id ='$ui->short_name'");
                $total = $ui->vatable_sales+$ui->zero_rated_ecozones+$ui->vat_on_sales;
                $days_lapsed=$this->dateDifference($ui->due_date, $today);
            if($count_collection == 0 && $total != 0){
                $data['unpaid_sales'][]=array(
                    "date"=>$ui->transaction_date,
                    "due_date"=>$ui->due_date,
                    "billing_from"=>$ui->billing_from,
                    "billing_to"=>$ui->billing_to,
                    "reference_number"=>$ui->reference_number,
                    "vatable_sales"=>$ui->vatable_sales,
                    "zero_rated_sales"=>$ui->zero_rated_ecozones,
                    "vat_on_sales"=>$ui->vat_on_sales,
                    "ewt"=>$ui->ewt,
                    "stl_id"=>$ui->short_name,
                    "billing_id"=>$ui->billing_id,
                    "invoice_no"=>$ui->serial_no,
                    "total"=>$total,
                    "days_lapsed"=>$days_lapsed,
                    );
                }
            }
        $this->load->view('reports/unpaid_invoices_sales',$data);
        $this->load->view('template/footer');
    }

    public function export_unpaid_invoices_sales(){
        $year=$this->uri->segment(3);
        $due_date=$this->uri->segment(4);
        $today = date('F j, Y');
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Summary of Unpaid Invoices (Sales).xlsx";
        $sql='';

            if($year!='null' && !empty($year)){
                $sql.= " YEAR(due_date) = '$year' AND ";
            }
            
            if($due_date!='null' && !empty($due_date)){
                $sql.= " due_date = '$due_date' AND ";
            }

            $query=substr($sql,0,-4);

            if(!empty($year) && !empty($due_date)){
                 $qu = " saved = '1' AND ".$query;
            }else{
                 $qu = "saved = '1' ";
            }
            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN
                    )
                )
            );
            foreach(range('A','T') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', "AGING OF RECEIVABLES");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', "AS OF $today");

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', "Invoice Date");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B5', "Invoice Number");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5', "Due Date");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D5', "Transaction No");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G5', "STL No");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I5', "Billing ID");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K5', "Vatable Sales");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M5', "Zero Rated Ecozones Sales");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O5', "Vat");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q5', "Total");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S5', "Overdue Days");
            $objPHPExcel->getActiveSheet()->getStyle("A5:T5")->applyFromArray($styleArray);

            $num=6;
            $total_vatable = array();
            $total_zero_rated = array();
            $total_vat = array();
            $total_overdue = array();
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON std.sales_id=sth.sales_id WHERE $qu ORDER BY short_name ASC") AS $ui){
                $count_collection = $this->super_model->count_custom_where("collection_details", "reference_no='$ui->reference_number' AND settlement_id ='$ui->short_name'");
                $total = $ui->vatable_sales+$ui->zero_rated_ecozones+$ui->vat_on_sales;
                $days_lapsed=$this->dateDifference($ui->due_date, $today);

                if($days_lapsed != 0){
                    $overdue = $days_lapsed." day/s";
                }else{
                    $overdue = '';
                }
                if($count_collection==0 && $total != 0){

                $total_vatable[] = $ui->vatable_sales;
                $total_zero_rated[] = $ui->zero_rated_ecozones;
                $total_vat[] = $ui->vat_on_sales;
                $total_overdue[] = $total;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $ui->transaction_date);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $ui->serial_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $ui->due_date);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $ui->reference_number);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, $ui->short_name);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, $ui->billing_id);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, $ui->vatable_sales);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, $ui->zero_rated_ecozones);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, $ui->vat_on_sales);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, $total);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$num, $overdue);

                     $objPHPExcel->getActiveSheet()->mergeCells('D5:F5');
                     $objPHPExcel->getActiveSheet()->mergeCells('D'.$num.":F".$num);
                     $objPHPExcel->getActiveSheet()->mergeCells('G5:H5');
                     $objPHPExcel->getActiveSheet()->mergeCells('G'.$num.":H".$num);
                     $objPHPExcel->getActiveSheet()->mergeCells('I5:J5');
                     $objPHPExcel->getActiveSheet()->mergeCells('I'.$num.":J".$num);
                     $objPHPExcel->getActiveSheet()->mergeCells('K5:L5');
                     $objPHPExcel->getActiveSheet()->mergeCells('K'.$num.":L".$num);
                     $objPHPExcel->getActiveSheet()->mergeCells('M5:N5');
                     $objPHPExcel->getActiveSheet()->mergeCells('M'.$num.":N".$num);
                     $objPHPExcel->getActiveSheet()->mergeCells('O5:P5');
                     $objPHPExcel->getActiveSheet()->mergeCells('O'.$num.":P".$num);
                     $objPHPExcel->getActiveSheet()->mergeCells('Q5:R5');
                     $objPHPExcel->getActiveSheet()->mergeCells('Q'.$num.":R".$num);
                     $objPHPExcel->getActiveSheet()->mergeCells('S5:T5');
                     $objPHPExcel->getActiveSheet()->mergeCells('S'.$num.":T".$num);
                     $objPHPExcel->getActiveSheet()->getStyle('A5:T5')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('B'.$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('C'.$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('G'.$num.":H".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('I'.$num.":J".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('K'.$num.":L".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('K'.$num.":L".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                     $objPHPExcel->getActiveSheet()->getStyle('M'.$num.":N".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('M'.$num.":N".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                     $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":P".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":P".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                     $objPHPExcel->getActiveSheet()->getStyle('Q'.$num.":R".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('Q'.$num.":R".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                     $objPHPExcel->getActiveSheet()->getStyle('S'.$num.":T".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('A5:T5')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('F1F1F1');
                     //$objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle('A5:T5')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('G2')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
                    $num++;
                }
            }

                    $a = $num;
                         $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":T".$a)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('FFFCA5');
                         $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":T".$a)->applyFromArray($styleArray);
                         $objPHPExcel->getActiveSheet()->mergeCells('A'.$a.":J".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('K'.$a.":L".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('M'.$a.":N".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('O'.$a.":P".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('Q'.$a.":R".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('S'.$a.":T".$a);
                         $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":R".$a)->getFont()->setBold(true);
                         $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":J".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                         $objPHPExcel->getActiveSheet()->getStyle('K'.$a.":L".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('K'.$a.":L".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('M'.$a.":N".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('M'.$a.":N".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":P".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":P".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('Q'.$a.":R".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('Q'.$a.":R".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$a, "TOTAL: ");
                        $objPHPExcel->getActiveSheet()->setCellValue('K'.$a, array_sum($total_vatable));
                        $objPHPExcel->getActiveSheet()->setCellValue('M'.$a, array_sum($total_zero_rated));
                        $objPHPExcel->getActiveSheet()->setCellValue('O'.$a, array_sum($total_vat));
                        $objPHPExcel->getActiveSheet()->setCellValue('Q'.$a, array_sum($total_overdue));
                    $num--;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Summary of Unpaid Invoices (Sales).xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Summary of Unpaid Invoices (Sales).xlsx"');
        // readfile($exportfilename);
    }

    public function unpaid_invoices_salesadj(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['due_date']=$this->super_model->custom_query("SELECT DISTINCT due_date FROM sales_transaction_head WHERE saved='1' ORDER BY due_date ASC");
        $year=$this->uri->segment(3);
        $due_date=$this->uri->segment(4);
        $data['year'] = $year;
        $data['due'] = $due_date;
        $today = date('Y-m-d');
        $sql='';

        if($year!='null' && !empty($year)){
            $sql.= " YEAR(due_date) = '$year' AND ";
        }
        
        if($due_date!='null' && !empty($due_date)){
            $sql.= " due_date = '$due_date' AND ";
        }

        $query=substr($sql,0,-4);

        if(!empty($year) && !empty($due_date)){
             $qu = " saved = '1' AND ".$query;
        }else{
             $qu = "saved = '1' ";
        }
        $data['bill']=array();
        $data['total_vatable_balance']=0;
        $total_vatable_balance=array();
        $data['total_zero_rated_balance']=0;
        $total_zero_rated_balance=array();
        $data['total_zero_ecozones_balance']=0;
        $total_zero_ecozones_balance=array();
        $data['total_vat_balance']=0;
        $total_vat_balance=array();
        $data['total_ewt_balance']=0;
        $total_ewt_balance=array();
            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details std INNER JOIN sales_adjustment_head sth ON std.sales_adjustment_id=sth.sales_adjustment_id WHERE $qu ORDER BY short_name ASC") AS $ui){
                $count_collection = $this->super_model->count_custom_where("collection_details", "reference_no='$ui->reference_number' AND settlement_id ='$ui->short_name'");
                $total = $ui->vatable_sales+$ui->zero_rated_ecozones+$ui->vat_on_sales;
                $days_lapsed=$this->dateDifference($ui->due_date, $today);
            if($count_collection == 0 && $total != 0){
                $data['unpaid_sales'][]=array(
                    "date"=>$ui->transaction_date,
                    "due_date"=>$ui->due_date,
                    "billing_from"=>$ui->billing_from,
                    "billing_to"=>$ui->billing_to,
                    "reference_number"=>$ui->reference_number,
                    "vatable_sales"=>$ui->vatable_sales,
                    "zero_rated_sales"=>$ui->zero_rated_ecozones,
                    "vat_on_sales"=>$ui->vat_on_sales,
                    "ewt"=>$ui->ewt,
                    "stl_id"=>$ui->short_name,
                    "billing_id"=>$ui->billing_id,
                    "invoice_no"=>$ui->serial_no,
                    "total"=>$total,
                    "days_lapsed"=>$days_lapsed,
                    );
                }
            }
        $this->load->view('reports/unpaid_invoices_salesadj',$data);
        $this->load->view('template/footer');
    }

    public function export_unpaid_invoices_salesadj(){
        $year=$this->uri->segment(3);
        $due_date=$this->uri->segment(4);
        $today = date('F j, Y');
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Summary of Unpaid Invoices (Sales Adjustment).xlsx";
        $sql='';

            if($year!='null' && !empty($year)){
                $sql.= " YEAR(due_date) = '$year' AND ";
            }
            
            if($due_date!='null' && !empty($due_date)){
                $sql.= " due_date = '$due_date' AND ";
            }

            $query=substr($sql,0,-4);

            if(!empty($year) && !empty($due_date)){
                 $qu = " saved = '1' AND ".$query;
            }else{
                 $qu = "saved = '1' ";
            }
            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN
                    )
                )
            );
            foreach(range('A','T') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', "AGING OF RECEIVABLES");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', "AS OF $today");

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', "Invoice Date");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B5', "Invoice Number");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5', "Due Date");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D5', "Transaction No");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G5', "STL No");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I5', "Billing ID");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K5', "Vatable Sales");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M5', "Zero Rated Ecozones Sales");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O5', "Vat");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q5', "Total");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S5', "Overdue Days");
            $objPHPExcel->getActiveSheet()->getStyle("A5:T5")->applyFromArray($styleArray);

            $num=6;
            $total_vatable = array();
            $total_zero_rated = array();
            $total_vat = array();
            $total_overdue = array();
            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details std INNER JOIN sales_adjustment_head sth ON std.sales_adjustment_id=sth.sales_adjustment_id WHERE $qu ORDER BY short_name ASC") AS $ui){
                $count_collection = $this->super_model->count_custom_where("collection_details", "reference_no='$ui->reference_number' AND settlement_id ='$ui->short_name'");
                $total = $ui->vatable_sales+$ui->zero_rated_ecozones+$ui->vat_on_sales;
                $days_lapsed=$this->dateDifference($ui->due_date, $today);

                if($days_lapsed != 0){
                    $overdue = $days_lapsed." day/s";
                }else{
                    $overdue = '';
                }

                if($count_collection == 0 && $total != 0){
                    
                $total_vatable[] = $ui->vatable_sales;
                $total_zero_rated[] = $ui->zero_rated_ecozones;
                $total_vat[] = $ui->vat_on_sales;
                $total_overdue[] = $total;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $ui->transaction_date);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $ui->serial_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $ui->due_date);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $ui->reference_number);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, $ui->short_name);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, $ui->billing_id);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, $ui->vatable_sales);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, $ui->zero_rated_ecozones);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, $ui->vat_on_sales);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, $total);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$num, $overdue);

                     $objPHPExcel->getActiveSheet()->mergeCells('D5:F5');
                     $objPHPExcel->getActiveSheet()->mergeCells('D'.$num.":F".$num);
                     $objPHPExcel->getActiveSheet()->mergeCells('G5:H5');
                     $objPHPExcel->getActiveSheet()->mergeCells('G'.$num.":H".$num);
                     $objPHPExcel->getActiveSheet()->mergeCells('I5:J5');
                     $objPHPExcel->getActiveSheet()->mergeCells('I'.$num.":J".$num);
                     $objPHPExcel->getActiveSheet()->mergeCells('K5:L5');
                     $objPHPExcel->getActiveSheet()->mergeCells('K'.$num.":L".$num);
                     $objPHPExcel->getActiveSheet()->mergeCells('M5:N5');
                     $objPHPExcel->getActiveSheet()->mergeCells('M'.$num.":N".$num);
                     $objPHPExcel->getActiveSheet()->mergeCells('O5:P5');
                     $objPHPExcel->getActiveSheet()->mergeCells('O'.$num.":P".$num);
                     $objPHPExcel->getActiveSheet()->mergeCells('Q5:R5');
                     $objPHPExcel->getActiveSheet()->mergeCells('Q'.$num.":R".$num);
                     $objPHPExcel->getActiveSheet()->mergeCells('S5:T5');
                     $objPHPExcel->getActiveSheet()->mergeCells('S'.$num.":T".$num);
                     $objPHPExcel->getActiveSheet()->getStyle('A5:T5')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('B'.$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('C'.$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('G'.$num.":H".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('I'.$num.":J".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('K'.$num.":L".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('K'.$num.":L".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                     $objPHPExcel->getActiveSheet()->getStyle('M'.$num.":N".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('M'.$num.":N".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                     $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":P".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":P".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                     $objPHPExcel->getActiveSheet()->getStyle('Q'.$num.":R".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('Q'.$num.":R".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                     $objPHPExcel->getActiveSheet()->getStyle('S'.$num.":T".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                     $objPHPExcel->getActiveSheet()->getStyle('A5:T5')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('F1F1F1');
                     //$objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle('A5:T5')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('G2')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
                    $num++;
                }
            }

                    $a = $num;
                         $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":T".$a)->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('FFFCA5');
                         $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":T".$a)->applyFromArray($styleArray);
                         $objPHPExcel->getActiveSheet()->mergeCells('A'.$a.":J".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('K'.$a.":L".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('M'.$a.":N".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('O'.$a.":P".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('Q'.$a.":R".$a);
                         $objPHPExcel->getActiveSheet()->mergeCells('S'.$a.":T".$a);
                         $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":R".$a)->getFont()->setBold(true);
                         $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":J".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_RIGHT);
                         $objPHPExcel->getActiveSheet()->getStyle('K'.$a.":L".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('K'.$a.":L".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('M'.$a.":N".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('M'.$a.":N".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":P".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":P".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                         $objPHPExcel->getActiveSheet()->getStyle('Q'.$a.":R".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                         $objPHPExcel->getActiveSheet()->getStyle('Q'.$a.":R".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$a, "TOTAL: ");
                        $objPHPExcel->getActiveSheet()->setCellValue('K'.$a, array_sum($total_vatable));
                        $objPHPExcel->getActiveSheet()->setCellValue('M'.$a, array_sum($total_zero_rated));
                        $objPHPExcel->getActiveSheet()->setCellValue('O'.$a, array_sum($total_vat));
                        $objPHPExcel->getActiveSheet()->setCellValue('Q'.$a, array_sum($total_overdue));
                    $num--;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Summary of Unpaid Invoices (Sales Adjustment).xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Summary of Unpaid Invoices (Sales Adjustment).xlsx"');
        // readfile($exportfilename);
    }

    public function sales_main_ewt_variance(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $from=$this->uri->segment(3);
        $to=$this->uri->segment(4);
        $data['from'] = $from;
        $data['to'] = $to;
        $sql="";

        if($from!='null' && $to != 'null'){
            $sql.= "((billing_from BETWEEN '$from' AND '$to') OR (billing_to BETWEEN '$from' AND '$to')) AND ";
        }

        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND ".$query;

        $total_ewt=array();
        $total_ewt_amount=array();
        $variance_total=array();
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id = std.sales_id WHERE $qu ORDER BY std.billing_id ASC, billing_from ASC") AS $sah){
            $tin = $this->super_model->select_column_where("participant","tin","billing_id",$sah->billing_id);

            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$tin'") AS $p){
                $par[]="'".$p->billing_id."'";
            }
            $imp=implode(',',$par);

            //$ewt=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","billing_id='$sah->billing_id' AND sales_id = 'sah->sales_id'");
            $overall_ewt_amount = $this->super_model->select_sum_join("ewt","sales_transaction_details","sales_transaction_head", "billing_id  IN($imp) AND $qu","sales_id");
            $overall_ewt_collected = $this->super_model->select_sum_join("ewt_amount","sales_transaction_details","sales_transaction_head", "billing_id  IN($imp) AND $qu","sales_id");

            $variance = $sah->ewt - $sah->ewt_amount;
            $total_variance  = $overall_ewt_amount - $overall_ewt_collected;

            $total_ewt[]=$sah->ewt;
            $total_ewt_amount[]=$sah->ewt_amount;
            $variance_total[]=$variance;

            $data['salesmain_ewt'][]=array(
                'billing_from'=>$sah->billing_from,
                'billing_to'=>$sah->billing_to,
                'billing_id'=>$sah->billing_id,
                'transaction_no'=>$sah->reference_number,
                'ewt_amount'=>$sah->ewt,
                'overall_ewt_amount'=>$overall_ewt_amount,
                'ewt_collected'=>$sah->ewt_amount,
                'overall_ewt_collected'=>$overall_ewt_collected,
                'tin'=>$tin,
                'variance'=>$variance,
                'total_variance'=>$total_variance,
            );
        }
        $data['b_total_ewt']=array_sum($total_ewt);
        $data['b_total_ewt_amount']=array_sum($total_ewt_amount);
        $data['b_total_variance']=array_sum($variance_total);
        $this->load->view('reports/sales_main_ewt_variance',$data);
        $this->load->view('template/footer');
    }

    public function export_sales_main_ewt_variance(){
        $from=$this->uri->segment(3);
        $to=$this->uri->segment(4);
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Summary of Sales Total EWT Variance (Main).xlsx";
        $sql='';

        if($from!='null' && $to != 'null'){
            $sql.= " ((billing_from BETWEEN '$from' AND '$to') OR (billing_to BETWEEN '$from' AND '$to')) AND ";
        }

        $query=substr($sql,0,-4);
        $qu = " saved = '1' AND ".$query;
            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    )
                )
            );
            // $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            // $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($settlement_id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "Sales Regular Bill ".$from." - ".$to);
            foreach(range('A','F') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "#");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', "Short Name");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', "Company Name");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', "EWT Total Amount");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', "EWT Amount Collected");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', "Variance");
            $objPHPExcel->getActiveSheet()->getStyle("A2:F2")->applyFromArray($styleArray);

            // $total_ewt_amount=array();
            // $total_ewt_collected=array();
            // $total_variance=array();
            $x=1;
            $num=3;
            $prevtin='';
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id = std.sales_id WHERE $qu GROUP BY short_name ORDER BY short_name ASC, company_name ASC") AS $sah){
                $participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$sah->billing_id);
                $tin = $this->super_model->select_column_where("participant","tin","billing_id",$sah->billing_id);
                if(!empty($sah->company_name) && date('Y',strtotime($sah->create_date))==date('Y')){
                    $comp_name=$sah->company_name;
                }else{
                    $comp_name=$participant_name;
                }

                $par=array();
                foreach($this->super_model->select_custom_where('participant',"tin='$tin'") AS $p){
                    $par[]="'".$p->billing_id."'";
                }
                $imp=implode(',',$par);
                $overall_ewt_amount = $this->super_model->select_sum_join("ewt","sales_transaction_details","sales_transaction_head", "billing_id IN($imp) AND $qu","sales_id");
                $overall_ewt_collected = $this->super_model->select_sum_join("ewt_amount","sales_transaction_details","sales_transaction_head", "billing_id IN($imp) AND $qu","sales_id");
                $variance  = $overall_ewt_amount - $overall_ewt_collected;

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $x);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $sah->short_name);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $comp_name);
                    if($prevtin == $tin){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, '');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, '');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, '');
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $overall_ewt_amount);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $overall_ewt_collected);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, $variance);
                    }

                    $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->getColor()->setRGB ('FFFFFF');
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":F".$num)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":F".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":F".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    if($variance == 0){
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getFont()->getColor()->setRGB('008000');
                    }else if($overall_ewt_amount < $overall_ewt_collected){
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getFont()->getColor()->setRGB('3E94E1');
                    }else if($overall_ewt_amount > $overall_ewt_collected){
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getFont()->getColor()->setRGB('FF0000');
                    }
                    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $num++;
                    $x++;
                    $prevtin = $tin;
                }
                /*$a = $num;
                    //$objPHPExcel->getActiveSheet()->getStyle('D'.$a)->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$a.":F".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle("D".$a.':F'.$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    //$objPHPExcel->getActiveSheet()->setCellValue('D'.$a, "TOTAL: ");
                    //$objPHPExcel->getActiveSheet()->setCellValue('E'.$a, array_sum($total_vatables));
                    //$objPHPExcel->getActiveSheet()->setCellValue('F'.$a, array_sum($total_zero_rated));
                    //$objPHPExcel->getActiveSheet()->setCellValue('G'.$a, array_sum($total_vat));
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$a, array_sum($total_ewt_amount));
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$a, array_sum($total_ewt_collected));
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$a, array_sum($total_variance));
                $num--;*/
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Summary of Sales Total EWT Variance (Main).xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Summary of Total Sales EWT Variance (Main).xlsx"');
        // readfile($exportfilename);
    }

    public function sales_adj_ewt_variance(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $from=$this->uri->segment(3);
        $to=$this->uri->segment(4);
        $data['from'] = $from;
        $data['to'] = $to;
        $sql="";

        if($from!='null' && $to != 'null'){
            $sql.= "due_date BETWEEN '$from' AND '$to' AND ";
        }   

        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND ".$query;

        $total_ewt=array();
        $total_ewt_amount=array();
        $variance_total=array();
        foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_head sah INNER JOIN sales_adjustment_details sad ON sah.sales_adjustment_id = sad.sales_adjustment_id WHERE $qu ORDER BY sad.billing_id ASC, billing_from ASC") AS $sah){
            $tin = $this->super_model->select_column_where("participant","tin","billing_id",$sah->billing_id);

            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$tin'") AS $p){
                $par[]="'".$p->billing_id."'";
            }
            $imp=implode(',',$par);

            $overall_ewt_amount = $this->super_model->select_sum_join("ewt","sales_adjustment_details","sales_adjustment_head", "billing_id IN($imp) AND $qu","sales_adjustment_id");
            $overall_ewt_collected = $this->super_model->select_sum_join("ewt_amount","sales_adjustment_details","sales_adjustment_head", "billing_id IN($imp) AND $qu","sales_adjustment_id");

            $variance  = $sah->ewt - $sah->ewt_amount;
            $total_variance  = $overall_ewt_amount - $overall_ewt_collected;

            $total_ewt[]=$sah->ewt;
            $total_ewt_amount[]=$sah->ewt_amount;
            $variance_total[]=$variance;

            $data['salesmain_ewt'][]=array(
                'billing_from'=>$sah->billing_from,
                'billing_to'=>$sah->billing_to,
                'billing_id'=>$sah->billing_id,
                'transaction_no'=>$sah->reference_number,
                'ewt_amount'=>$sah->ewt,
                'overall_ewt_amount'=>$overall_ewt_amount,
                'ewt_collected'=>$sah->ewt_amount,
                'overall_ewt_collected'=>$overall_ewt_collected,
                'tin'=>$tin,
                'variance'=>$variance,
                'total_variance'=>$total_variance,
            );
        }
        $data['b_total_ewt']=array_sum($total_ewt);
        $data['b_total_ewt_amount']=array_sum($total_ewt_amount);
        $data['b_total_variance']=array_sum($variance_total);
        $this->load->view('reports/sales_adj_ewt_variance',$data);
        $this->load->view('template/footer');
    }

    public function export_sales_adj_ewt_variance(){
        $from=$this->uri->segment(3);
        $to=$this->uri->segment(4);
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Summary of Sales Total EWT Variance (Adjustment).xlsx";
        $sql='';

        if($from!='null' && $to != 'null'){
            $sql.= "due_date BETWEEN '$from' AND '$to' AND ";
        }

        $query=substr($sql,0,-4);
        $qu = " saved = '1' AND ".$query;

            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    )
                )
            );
            
            // $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            // $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($settlement_id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "Sales Adjustment Bill ".$from." - ".$to);
            foreach(range('A','F') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "#");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', "Short Name");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', "Company Name");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', "EWT Total Amount");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', "EWT Amount Collected");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', "Variance");
            $objPHPExcel->getActiveSheet()->getStyle("A2:F2")->applyFromArray($styleArray);

            $total_ewt_amount=array();
            $total_ewt_collected=array();
            $total_varince=array();
            $x=1;
            $num=3;
            $prevtin='';
            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_head sah INNER JOIN sales_adjustment_details sad ON sah.sales_adjustment_id = sad.sales_adjustment_id WHERE $qu GROUP BY short_name ORDER BY short_name ASC, company_name ASC") AS $sah){
                $participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$sah->billing_id);
                $tin = $this->super_model->select_column_where("participant","tin","billing_id",$sah->billing_id);
                //$settlement_id=$this->super_model->select_column_custom_where("participant",'settlement_id',"tin = '$sah->tin' ORDER BY settlement_id ASC LIMIT 1");

                if(!empty($sah->company_name) && date('Y',strtotime($sah->create_date))==date('Y')){
                    $comp_name=$sah->company_name;
                }else{
                    $comp_name=$participant_name;
                }

                $par=array();
                foreach($this->super_model->select_custom_where('participant',"tin='$tin'") AS $p){
                    $par[]="'".$p->billing_id."'";
                }
                $imp=implode(',',$par);

                //$overall_ewt_amount = $this->super_model->select_sum_where("sales_adjustment_head","ewt","sales_id='$sah->sales_id' AND short_name IN($imp)");
                $overall_ewt_amount = $this->super_model->select_sum_join("ewt","sales_adjustment_details","sales_adjustment_head", "billing_id IN($imp) AND $qu","sales_adjustment_id");
                $overall_ewt_collected = $this->super_model->select_sum_join("ewt_amount","sales_adjustment_details","sales_adjustment_head", "billing_id IN($imp) AND $qu","sales_adjustment_id");
                $variance  = $overall_ewt_amount - $overall_ewt_collected;

                // $total_ewt_amount[]=$overall_ewt_amount;
                // $total_ewt_collected[]=$overall_ewt_collected;
                // $total_varince[]=$variance;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $x);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $sah->short_name);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $comp_name);
                    /*$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $overall_ewt_amount);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $overall_ewt_collected);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, $variance);*/
                    if($prevtin == $tin){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, '');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, '');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, '');
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $overall_ewt_amount);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $overall_ewt_collected);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, $variance);
                    }

                    $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->getColor()->setRGB ('FFFFFF');
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":F".$num)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":F".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":F".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    if($variance == 0){
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getFont()->getColor()->setRGB('008000');
                    }else if($overall_ewt_amount < $overall_ewt_collected){
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getFont()->getColor()->setRGB('3E94E1');
                    }else if($overall_ewt_amount > $overall_ewt_collected){
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getFont()->getColor()->setRGB('FF0000');
                    }
                    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $x++;
                    $num++;
                    $prevtin = $tin;
                }
                /*$a = $num;
                    //$objPHPExcel->getActiveSheet()->getStyle('D'.$a)->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$a.":F".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle("D".$a.':F'.$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    //$objPHPExcel->getActiveSheet()->setCellValue('D'.$a, "TOTAL: ");
                    //$objPHPExcel->getActiveSheet()->setCellValue('E'.$a, array_sum($total_vatables));
                    //$objPHPExcel->getActiveSheet()->setCellValue('F'.$a, array_sum($total_zero_rated));
                    //$objPHPExcel->getActiveSheet()->setCellValue('G'.$a, array_sum($total_vat));
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$a, array_sum($total_ewt_amount));
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$a, array_sum($total_ewt_collected));
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$a, array_sum($total_varince));
                $num--;*/
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Summary of Sales Total EWT Variance (Adjustment).xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Summary of Total Sales EWT Variance (Main).xlsx"');
        // readfile($exportfilename);
    }

    public function purchases_main_total_variance(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $from=$this->uri->segment(3);
        $to=$this->uri->segment(4);
        $data['from'] = $from;
        $data['to'] = $to;
        $sql="";

        if($from!='null' && $to != 'null'){
            $sql.= " ((billing_from BETWEEN '$from' AND '$to') OR (billing_to BETWEEN '$from' AND '$to')) AND ";
        }
        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND adjustment = '0' AND ".$query;

        $total_amount=array();
        $total_amount_collected=array();
        $variance_total=array();
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pth INNER JOIN purchase_transaction_details ptd ON pth.purchase_id = ptd.purchase_id WHERE $qu ORDER BY ptd.billing_id ASC, due_date ASC") AS $pah){
            $tin = $this->super_model->select_column_where("participant","tin","billing_id",$pah->billing_id);

            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$tin'") AS $p){
                $par[]="'".$p->billing_id."'";
            }
            $imp=implode(',',$par);

            $overall_total_amount = $this->super_model->select_sum_join("total_amount","purchase_transaction_details","purchase_transaction_head", "billing_id IN($imp) AND $qu ","purchase_id");
            $overall_total_amount_collected = $this->super_model->select_sum_join("total_update","purchase_transaction_details","purchase_transaction_head", "billing_id IN($imp) AND $qu","purchase_id");

            $variance  = $pah->total_amount - $pah->total_update;
            $total_variance  = $overall_total_amount - $overall_total_amount_collected;

            $total_amount[]=$pah->total_amount;
            $total_amount_collected[]=$pah->total_update;
            $variance_total[]=$variance;

            $data['purchasesmain_total'][]=array(
                'due_date'=>$pah->due_date,
                'billing_from'=>$pah->billing_from,
                'billing_to'=>$pah->billing_to,
                'billing_id'=>$pah->billing_id,
                'transaction_no'=>$pah->reference_number,
                'total_amount'=>$pah->total_amount,
                'overall_total_amount'=>$overall_total_amount,
                'amount_collected'=>$pah->total_update,
                'overall_total_amount_collected'=>$overall_total_amount_collected,
                'tin'=>$tin,
                'variance'=>$variance,
                'total_variance'=>$total_variance,
            );
        }
        $data['b_total_amount']=array_sum($total_amount);
        $data['b_total_amount_collected']=array_sum($total_amount_collected);
        $data['b_total_variance']=array_sum($variance_total);
        $this->load->view('reports/purchases_main_total_variance',$data);
        $this->load->view('template/footer');
    }

        public function export_purchases_main_total_variance(){
        $from=$this->uri->segment(3);
        $to=$this->uri->segment(4);
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Summary of Purchases Total Variance (Main).xlsx";
        $sql='';

        if($from!='null' && $to != 'null'){
            $sql.= " ((billing_from BETWEEN '$from' AND '$to') OR (billing_to BETWEEN '$from' AND '$to')) AND ";
        }
        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND adjustment = '0' AND ".$query;

            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    )
                )
            );
            
            // $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            // $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($settlement_id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "Purchases Regular Bill ".$from." - ".$to);
            foreach(range('A','F') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "#");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', "Short Name");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', "Company Name");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', "Total Amount");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', "Total Collected Amount");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', "Variance");
            $objPHPExcel->getActiveSheet()->getStyle("A2:F2")->applyFromArray($styleArray);

            $total_amount=array();
            $total_amount_collected=array();
            $total_varince=array();
            $x=1;
            $num=3;
            $prevtin='';
            foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pth INNER JOIN purchase_transaction_details ptd ON pth.purchase_id = ptd.purchase_id WHERE $qu GROUP BY short_name ORDER BY short_name ASC, company_name ASC") AS $ph){
                $participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$ph->billing_id);
                $tin = $this->super_model->select_column_where("participant","tin","billing_id",$ph->billing_id);
                //$settlement_id=$this->super_model->select_column_custom_where("participant",'settlement_id',"tin = '$ph->tin' ORDER BY settlement_id ASC LIMIT 1");

                if(!empty($ph->company_name) && date('Y',strtotime($ph->create_date))==date('Y')){
                    $comp_name=$ph->company_name;
                }else{
                    $comp_name=$participant_name;
                }

                $par=array();
                foreach($this->super_model->select_custom_where('participant',"tin='$tin'") AS $p){
                    $par[]="'".$p->billing_id."'";
                }
                $imp=implode(',',$par);

                $overall_total_amount = $this->super_model->select_sum_join("total_amount","purchase_transaction_head","purchase_transaction_details", "billing_id IN($imp) AND $qu","purchase_id");
                $overall_total_amount_collected = $this->super_model->select_sum_join("total_update","purchase_transaction_head","purchase_transaction_details", "billing_id IN($imp) AND $qu","purchase_id");
                $variance  = $overall_total_amount - $overall_total_amount_collected;

                $total_amount[]=$overall_total_amount;
                $total_amount_collected[]=$overall_total_amount_collected;
                $total_varince[]=$variance;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $x);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $ph->short_name);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $comp_name);

                    if($prevtin == $tin){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, '');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, '');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, '');
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $overall_total_amount);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $overall_total_amount_collected);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, $variance);
                    }

                    $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->getColor()->setRGB ('FFFFFF');
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":F".$num)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":F".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":F".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    if($variance == 0){
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getFont()->getColor()->setRGB('008000');
                    }else if($overall_total_amount < $overall_total_amount_collected){
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getFont()->getColor()->setRGB('3E94E1');
                    }else if($overall_total_amount > $overall_total_amount_collected){
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getFont()->getColor()->setRGB('FF0000');
                    }
                    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $x++;
                    $num++;
                    $prevtin = $tin;
                }
                /*$a = $num;
                    //$objPHPExcel->getActiveSheet()->getStyle('D'.$a)->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$a.":F".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle("D".$a.':F'.$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    //$objPHPExcel->getActiveSheet()->setCellValue('D'.$a, "TOTAL: ");
                    //$objPHPExcel->getActiveSheet()->setCellValue('E'.$a, array_sum($total_vatables));
                    //$objPHPExcel->getActiveSheet()->setCellValue('F'.$a, array_sum($total_zero_rated));
                    //$objPHPExcel->getActiveSheet()->setCellValue('G'.$a, array_sum($total_vat));
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$a, array_sum($total_amount));
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$a, array_sum($total_amount_collected));
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$a, array_sum($total_varince));
                $num--;*/
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Summary of Purchases Total Variance (Main).xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Summary of Total Sales EWT Variance (Main).xlsx"');
        // readfile($exportfilename);
    }

    public function purchases_adj_total_variance(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $from=$this->uri->segment(3);
        $to=$this->uri->segment(4);
        $data['from'] = $from;
        $data['to'] = $to;
        $sql="";

        if($from!='null' && $to != 'null'){
            $sql.= "due_date BETWEEN '$from' AND '$to' AND ";
        }
        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND adjustment = '1' AND ".$query;

        $total_amount=array();
        $total_amount_collected=array();
        $variance_total=array();
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pth INNER JOIN purchase_transaction_details ptd ON pth.purchase_id = ptd.purchase_id WHERE $qu ORDER BY ptd.billing_id ASC, due_date ASC") AS $pah){
            $tin = $this->super_model->select_column_where("participant","tin","billing_id",$pah->billing_id);

            $par=array();
            foreach($this->super_model->select_custom_where('participant',"tin='$tin'") AS $p){
                $par[]="'".$p->billing_id."'";
            }
            $imp=implode(',',$par);

            $overall_total_amount = $this->super_model->select_sum_join("total_amount","purchase_transaction_details","purchase_transaction_head", "billing_id IN($imp) AND $qu","purchase_id");
            $overall_total_amount_collected = $this->super_model->select_sum_join("total_update","purchase_transaction_details","purchase_transaction_head", "billing_id IN($imp) AND $qu","purchase_id");

            $variance  = $pah->total_amount - $pah->total_update;
            $total_variance  = $overall_total_amount - $overall_total_amount_collected;

            $total_amount[]=$pah->total_amount;
            $total_amount_collected[]=$pah->total_update;
            $variance_total[]=$variance;

            $data['purchasesmain_total'][]=array(
                'due_date'=>$pah->due_date,
                'billing_from'=>$pah->billing_from,
                'billing_to'=>$pah->billing_to,
                'billing_id'=>$pah->billing_id,
                'transaction_no'=>$pah->reference_number,
                'total_amount'=>$pah->total_amount,
                'overall_total_amount'=>$overall_total_amount,
                'amount_collected'=>$pah->total_update,
                'overall_total_amount_collected'=>$overall_total_amount_collected,
                'tin'=>$tin,
                'variance'=>$variance,
                'total_variance'=>$total_variance,
            );
        }
        $data['b_total_amount']=array_sum($total_amount);
        $data['b_total_amount_collected']=array_sum($total_amount_collected);
        $data['b_total_variance']=array_sum($variance_total);
        $this->load->view('reports/purchases_adj_total_variance',$data);
        $this->load->view('template/footer');
    }

        public function export_purchases_adj_total_variance(){
        $from=$this->uri->segment(3);
        $to=$this->uri->segment(4);
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Summary of Purchases Total Variance (Adjustment).xlsx";
        $sql='';

        if($from!='null' && $to != 'null'){
            $sql.= "due_date BETWEEN '$from' AND '$to' AND ";
        }
        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND adjustment = '1' AND ".$query;

            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            // $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    )
                )
            );
            
            // $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            // $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($settlement_id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "Purchases Adjustment Bill ".$from." - ".$to);
            foreach(range('A','F') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "#");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', "Short Name");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', "Company Name");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', "Total Amount");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', "Total Collected Amount");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', "Variance");
            $objPHPExcel->getActiveSheet()->getStyle("A2:F2")->applyFromArray($styleArray);

            $total_amount=array();
            $total_amount_collected=array();
            $total_varince=array();
            $x=1;
            $num=3;
            $prevtin='';
            foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pth INNER JOIN purchase_transaction_details ptd ON pth.purchase_id = ptd.purchase_id WHERE $qu GROUP BY short_name ORDER BY short_name ASC, company_name ASC") AS $ph){
                $participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$ph->billing_id);
                $tin = $this->super_model->select_column_where("participant","tin","billing_id",$ph->billing_id);
                //$settlement_id=$this->super_model->select_column_custom_where("participant",'settlement_id',"tin = '$ph->tin' ORDER BY settlement_id ASC LIMIT 1");

                if(!empty($ph->company_name) && date('Y',strtotime($ph->create_date))==date('Y')){
                    $comp_name=$ph->company_name;
                }else{
                    $comp_name=$participant_name;
                }

                $par=array();
                foreach($this->super_model->select_custom_where('participant',"tin='$tin'") AS $p){
                    $par[]="'".$p->billing_id."'";
                }
                $imp=implode(',',$par);

                //$overall_ewt_amount = $this->super_model->select_sum_where("sales_adjustment_head","ewt","sales_id='$ph->sales_id' AND short_name IN($imp)");
                $overall_total_amount = $this->super_model->select_sum_join("total_amount","purchase_transaction_head","purchase_transaction_details", "billing_id IN($imp) AND $qu","purchase_id");
                $overall_total_amount_collected = $this->super_model->select_sum_join("total_update","purchase_transaction_head","purchase_transaction_details", "billing_id IN($imp) AND $qu","purchase_id");
                $variance  = $overall_total_amount - $overall_total_amount_collected;

                $total_amount[]=$overall_total_amount;
                $total_amount_collected[]=$overall_total_amount_collected;
                $total_varince[]=$variance;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $x);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $ph->short_name);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $comp_name);
                    if($prevtin == $tin){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, '');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, '');
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, '');
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $overall_total_amount);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $overall_total_amount_collected);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, $variance);
                    }

                    $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->getColor()->setRGB('FFFFFF');
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":F".$num)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":F".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":F".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    if($variance == 0){
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getFont()->getColor()->setRGB('008000');
                    }else if($overall_total_amount < $overall_total_amount_collected){
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getFont()->getColor()->setRGB('3E94E1');
                    }else if($overall_total_amount > $overall_total_amount_collected){
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getFont()->getColor()->setRGB('FF0000');
                    }
                    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $x++;
                    $num++;
                }
                /*$a = $num;
                    //$objPHPExcel->getActiveSheet()->getStyle('D'.$a)->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$a.":F".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle("D".$a.':F'.$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    //$objPHPExcel->getActiveSheet()->setCellValue('D'.$a, "TOTAL: ");
                    //$objPHPExcel->getActiveSheet()->setCellValue('E'.$a, array_sum($total_vatables));
                    //$objPHPExcel->getActiveSheet()->setCellValue('F'.$a, array_sum($total_zero_rated));
                    //$objPHPExcel->getActiveSheet()->setCellValue('G'.$a, array_sum($total_vat));
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$a, array_sum($total_amount));
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$a, array_sum($total_amount_collected));
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$a, array_sum($total_varince));
                $num--;*/
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Summary of Purchases Total Variance (Adjustment).xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Summary of Total Sales EWT Variance (Main).xlsx"');
        // readfile($exportfilename);
    }

    public function reserve_all(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        $original=$this->uri->segment(6);
        $scanned=$this->uri->segment(7);
        $data['from'] = $from;
        $data['to'] = $to;
        $part=$this->super_model->select_column_where("reserve_participant","res_participant_name","res_tin",$participant);
        $data['part'] = $part;
        $data['original'] = $original;
        $data['scanned'] = $scanned;
        $data['participant']=$this->super_model->custom_query("SELECT * FROM reserve_participant WHERE res_participant_name != '' GROUP BY res_tin ORDER BY res_participant_name");
        $sql="";
        if(!empty($participant) && $participant!='null'){
            $par=array();
            foreach($this->super_model->select_custom_where('reserve_participant',"res_tin='$participant'") AS $p){
                $par[]="'".$p->res_settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " short_name IN($imp) AND ";
        } if(!empty($from) && !empty($from) && $from!='null' && $to != 'null'){
            $sql.= " ((billing_from BETWEEN '$from' AND '$to') OR (billing_to BETWEEN '$from' AND '$to')) AND ";
        } if($original!='null' && isset($original)){
             $sql.= "original_copy = '$original' AND "; 
        } if($scanned!='null'  && isset($scanned)){
             $sql.= "scanned_copy = '$scanned' AND ";
        }
        $query=substr($sql,0,-4);
        $qu = "saved='1' AND adjustment !='1' AND ".$query;
        $total_sum[]=0;
        if(!empty($query)){
            foreach($this->super_model->custom_query("SELECT * FROM reserve_transaction_head pth INNER JOIN reserve_transaction_details ptd ON pth.reserve_id = ptd.reserve_id WHERE $qu ORDER BY billing_from ASC, reference_number ASC") AS $pth){
                $participant_name=$this->super_model->select_column_where("reserve_participant","res_participant_name","res_billing_id",$pth->billing_id);
                if(!empty($pth->company_name) && date('Y',strtotime($pth->create_date))==date('Y')){
                    $comp_name=$pth->company_name;
                }else{
                    $comp_name=$participant_name;
                }
                $total=($pth->vatables_purchases+$pth->vat_on_purchases)-$pth->ewt;
                $total_sum[]=$total;
                $data['reserveall'][]=array(
                    'participant_name'=>$comp_name,
                    'billing_id'=>$pth->billing_id,
                    'reference_number'=>$pth->reference_number,
                    'billing_from'=>$pth->billing_from,
                    'billing_to'=>$pth->billing_to,
                    'vatables_purchases'=>$pth->vatables_purchases,
                    'vat_on_purchases'=>$pth->vat_on_purchases,
                    'zero_rated_purchases'=>$pth->zero_rated_purchases,
                    'zero_rated_ecozones'=>$pth->zero_rated_ecozones,
                    'ewt'=>$pth->ewt,
                    'or_no'=>$pth->or_no,
                    'total_update'=>$pth->total_update,
                    'original_copy'=>$pth->original_copy,
                    'scanned_copy'=>$pth->scanned_copy,
                    'total'=>$total,
                );
            }
        }
        $data['total_sum']=array_sum($total_sum);
        $this->load->view('reports/reserve_all',$data);
        $this->load->view('template/footer');
    }
    
    public function export_reserve_all(){
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Reserve Wesm All Transcations.xlsx";
        $sql='';
        if($participant!='null'){
            $sql.= " res_tin = '$participant' AND "; 
        }
        if($from!='null' && $to != 'null'){
            $sql.= " ((billing_from BETWEEN '$from' AND '$to') OR (billing_to BETWEEN '$from' AND '$to')) AND ";
        }
        $query=substr($sql,0,-4);
        if($participant !='null' || $from != 'null' || $to != 'null'){
            $qu = " saved = '1' AND adjustment != '1' AND ".$query;
        }else{
            $qu = " saved = '1' AND adjustment != '1'";
        }
        $sheetno=0;
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                )
            )
        );
        foreach($this->super_model->custom_query("SELECT * FROM reserve_transaction_head pah INNER JOIN reserve_transaction_details pad ON pah.reserve_id = pad.reserve_id INNER JOIN reserve_participant p ON p.res_settlement_id = pad.short_name WHERE res_participant_name != '' AND $qu GROUP BY res_tin ORDER BY res_participant_name") AS $head){
            $settlement_id=$this->super_model->select_column_custom_where("reserve_participant",'res_settlement_id',"res_tin = '$head->tin' ORDER BY res_settlement_id ASC LIMIT 1");
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($settlement_id);
            foreach(range('A','N') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A1', "Billing Period");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B1', "Billing ID");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C1', "Transaction Reference Number");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D1', "Company Name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E1', "Vatables Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F1', "Zero-rated Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G1', "Zero-rated Ecozones Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H1', "Vat On Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I1', "EWT Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J1', "Total");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K1', "OR Number");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L1', "Total Amount");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M1', "Original Copy");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('N1', "Scanned Copy");
            $objPHPExcel->getActiveSheet()->getStyle("A1:N1")->applyFromArray($styleArray);
            $total_vatables=array();
            $total_zerorated_purchases=array();
            $total_zerorated_ecozones=array();
            $total_vat=array();
            $total_ewt=array();
            $total_zero_rated=array();
            $total_ewt_amount=array();
            $total_update_amount=array();
            $overall_total=array();
            $purchaseall=array();
            foreach($this->super_model->custom_query("SELECT * FROM reserve_transaction_head pah INNER JOIN reserve_transaction_details pad ON pah.reserve_id = pad.reserve_id INNER JOIN reserve_participant p ON p.res_billing_id = pad.billing_id WHERE res_tin='$head->tin' AND res_participant_name != '' AND $qu ORDER BY billing_from ASC, reference_number ASC, p.res_billing_id ASC") AS $pah){
                if(!empty($pah->company_name) && date('Y',strtotime($pah->create_date))==date('Y')){
                    $comp_name=$pah->company_name;
                }else{
                    $comp_name=$pah->res_participant_name;
                }
                $billing_date = date("M. d, Y",strtotime($pah->billing_from))." - ".date("M. d, Y",strtotime($pah->billing_to));
                $tin=$this->super_model->select_column_where("reserve_participant","res_tin","res_billing_id",$pah->billing_id);
                $purchaseall[]=array(
                    'billing_date'=>$billing_date,
                    'participant_name'=>$comp_name,
                    'billing_id'=>$pah->billing_id,
                    'reference_number'=>$pah->reference_number,
                    'vatables_purchases'=>$pah->vatables_purchases,
                    'zero_rated_purchases'=>$pah->zero_rated_purchases,
                    'zero_rated_ecozones'=>$pah->zero_rated_ecozones,
                    'vat_on_purchases'=>$pah->vat_on_purchases,
                    'ewt'=>$pah->ewt,
                    'or_no'=>$pah->or_no,
                    'total_update'=>$pah->total_update,
                    'original_copy'=>$pah->original_copy,
                    'scanned_copy'=>$pah->scanned_copy,
                    'zero_rated_purchases'=>$pah->zero_rated_purchases,
                    'zero_rated_ecozones'=>$pah->zero_rated_ecozones,
                    'short_name'=>$pah->short_name,
                    'tin'=>$tin,
                );
            }
            $row = 2;
            $startRow = -1;
            $previousKey = '';
            $num=2;
            foreach($purchaseall AS $index => $value){
                if($startRow == -1){
                    $startRow = $row;
                    $previousKey = $value['billing_date'];
                }
                $zero_rated=$value['zero_rated_purchases']+$value['zero_rated_ecozones'];
                $total=($value['vatables_purchases']+$zero_rated+$value['vat_on_purchases'])-$value['ewt'];
                if($value['tin']==$pah->tin){
                    $total_vatables[]=$value['vatables_purchases'];
                    $total_zerorated_purchases[]=$value['zero_rated_purchases'];
                    $total_zerorated_ecozones[]=$value['zero_rated_ecozones'];
                    $total_vat[]=$value['vat_on_purchases'];
                    $total_ewt[]=$value['ewt'];
                    $total_zero_rated[]=$zero_rated;
                    $total_update_amount[]=$value['total_update'];
                    $overall_total[]=$total;
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $value['billing_date']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B'.$num, $value['billing_id']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $value['reference_number']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D'.$num, $value['participant_name']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, "-".$value['vatables_purchases']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F'.$num, "-".$value['zero_rated_purchases']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G'.$num, "-".$value['zero_rated_ecozones']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, "-".$value['vat_on_purchases']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I'.$num, $value['ewt']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J'.$num, "-".$total);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K'.$num, $value['or_no']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, "-".$value['total_update']);
                    if($value['original_copy']==1){
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M'.$num, "Yes");
                    }else if($value['original_copy']==0){
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M'.$num, "No");
                    }else{
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('M'.$num, "");
                    }
                    if($value['scanned_copy']==1){
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('N'.$num, "Yes");
                    }else if($value['scanned_copy']==0){
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('N'.$num, "No");
                    }else{
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('N'.$num, "");
                    }
                    $nextKey = isset($purchaseall[$index+1]) ? $purchaseall[$index+1]['billing_date'] : null;
                    if($row >= $startRow && (($previousKey <> $nextKey) || ($nextKey == null))){
                        $cellToMerge = 'A'.$startRow.':A'.$row;
                        $objPHPExcel->getActiveSheet()->mergeCells($cellToMerge);
                        $startRow = -1;
        
                    }
                    $row++;
                    $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                    $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->getColor()->setRGB ('FFFFFF');
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":N".$num)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":N".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":J".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
                    $num++;
                }
            }
            $a = $num;
            $objPHPExcel->getActiveSheet()->getStyle('E'.$a.":J".$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('L'.$a)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle("E".$a.':J'.$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle("L".$a)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$a, "-".array_sum($total_vatables));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$a, "-".array_sum($total_zerorated_purchases));
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$a, "-".array_sum($total_zerorated_ecozones));
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$a, "-".array_sum($total_vat));
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$a, array_sum($total_ewt));
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$a, "-".array_sum($overall_total));
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$a, "-".array_sum($total_update_amount));
            $num--;
            $sheetno++;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reserve Wesm All Transcations.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
    }

    public function reserve_main_total_variance(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $from=$this->uri->segment(3);
        $to=$this->uri->segment(4);
        $data['from'] = $from;
        $data['to'] = $to;
        $sql="";
        if($from!='null' && $to != 'null'){
            $sql.= " ((billing_from BETWEEN '$from' AND '$to') OR (billing_to BETWEEN '$from' AND '$to')) AND ";
        }
        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND adjustment = '0' AND ".$query;
        $total_amount=array();
        $total_amount_collected=array();
        $variance_total=array();
        foreach($this->super_model->custom_query("SELECT * FROM reserve_transaction_head pth INNER JOIN reserve_transaction_details ptd ON pth.reserve_id = ptd.reserve_id WHERE $qu") AS $pah){
            $tin = $this->super_model->select_column_where("reserve_participant","res_tin","res_billing_id",$pah->billing_id);
            $par=array();
            foreach($this->super_model->select_custom_where('reserve_participant',"res_tin='$tin'") AS $p){
                $par[]="'".$p->res_billing_id."'";
                $imp=implode(',',$par);
            }
            $overall_total_amount = $this->super_model->select_sum_join("total_amount","reserve_transaction_details","reserve_transaction_head", "billing_id IN($imp) AND $qu ","reserve_id");
            $overall_total_amount_collected = $this->super_model->select_sum_join("total_update","reserve_transaction_details","reserve_transaction_head", "billing_id IN($imp) AND $qu","reserve_id");
            $variance  = $pah->total_amount - $pah->total_update;
            $total_variance  = $overall_total_amount - $overall_total_amount_collected;
            $total_amount[]=$pah->total_amount;
            $total_amount_collected[]=$pah->total_update;
            $variance_total[]=$variance;
            $data['purchasesmain_total'][]=array(
                'due_date'=>$pah->due_date,
                'billing_from'=>$pah->billing_from,
                'billing_to'=>$pah->billing_to,
                'billing_id'=>$pah->billing_id,
                'transaction_no'=>$pah->reference_number,
                'total_amount'=>$pah->total_amount,
                'overall_total_amount'=>$overall_total_amount,
                'amount_collected'=>$pah->total_update,
                'overall_total_amount_collected'=>$overall_total_amount_collected,
                'tin'=>$tin,
                'variance'=>$variance,
                'total_variance'=>$total_variance,
            );
        }
        $data['b_total_amount']=array_sum($total_amount);
        $data['b_total_amount_collected']=array_sum($total_amount_collected);
        $data['b_total_variance']=array_sum($variance_total);
        $this->load->view('reports/reserve_main_total_variance',$data);
        $this->load->view('template/footer');
    }

    public function export_reserve_main_total_variance(){
        $from=$this->uri->segment(3);
        $to=$this->uri->segment(4);
        $objPHPExcel = new Spreadsheet();
        $exportfilename="Summary of Reserve Total Variance (Main).xlsx";
        $sql='';

        if($from!='null' && $to != 'null'){
            $sql.= " ((billing_from BETWEEN '$from' AND '$to') OR (billing_to BETWEEN '$from' AND '$to')) AND ";
        }
        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND adjustment = '0' AND ".$query;
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                )
            )
        );
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "Reserve Regular Bill ".$from." - ".$to);
        foreach(range('A','F') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "#");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', "Short Name");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', "Company Name");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', "Total Amount");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', "Total Collected Amount");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', "Variance");
        $objPHPExcel->getActiveSheet()->getStyle("A2:F2")->applyFromArray($styleArray);
        $total_amount=array();
        $total_amount_collected=array();
        $total_varince=array();
        $x=1;
        $num=3;
        $prevtin='';
        foreach($this->super_model->custom_query("SELECT * FROM reserve_transaction_head pth INNER JOIN reserve_transaction_details ptd ON pth.reserve_id = ptd.reserve_id WHERE $qu GROUP BY short_name ORDER BY short_name ASC, company_name ASC") AS $ph){
            $participant_name=$this->super_model->select_column_where("reserve_participant","res_participant_name","res_billing_id",$ph->billing_id);
            $tin = $this->super_model->select_column_where("reserve_participant","res_tin","res_billing_id",$ph->billing_id);
            if(!empty($ph->company_name) && date('Y',strtotime($ph->create_date))==date('Y')){
                $comp_name=$ph->company_name;
            }else{
                $comp_name=$participant_name;
            }

            $par=array();
            foreach($this->super_model->select_custom_where('reserve_participant',"res_tin='$tin'") AS $p){
                $par[]="'".$p->res_billing_id."'";
                $imp=implode(',',$par);
            }
            $overall_total_amount = $this->super_model->select_sum_join("total_amount","reserve_transaction_head","reserve_transaction_details", "billing_id IN($imp) AND $qu","reserve_id");
            $overall_total_amount_collected = $this->super_model->select_sum_join("total_update","reserve_transaction_head","reserve_transaction_details", "billing_id IN($imp) AND $qu","reserve_id");
            $variance  = $overall_total_amount - $overall_total_amount_collected;

            $total_amount[]=$overall_total_amount;
            $total_amount_collected[]=$overall_total_amount_collected;
            $total_varince[]=$variance;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $x);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $ph->short_name);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $comp_name);

            if($prevtin == $tin){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, '');
            }else{
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $overall_total_amount);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $overall_total_amount_collected);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, $variance);
            }

            $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
            $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
            $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->getColor()->setRGB ('FFFFFF');
            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":F".$num)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":F".$num)->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":F".$num)->getNumberFormat()->setFormatCode(numberformat::FORMAT_NUMBER_COMMA_SEPARATED1);
            if($variance == 0){
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getFont()->getColor()->setRGB('008000');
            }else if($overall_total_amount < $overall_total_amount_collected){
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getFont()->getColor()->setRGB('3E94E1');
            }else if($overall_total_amount > $overall_total_amount_collected){
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getFont()->getColor()->setRGB('FF0000');
            }
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(alignment::HORIZONTAL_CENTER);
            $x++;
            $num++;
            $prevtin = $tin;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Summary of Reserve Total Variance (Main).xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = io_factory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
    }

}