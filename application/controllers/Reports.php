<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
            $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$sales->billing_id);
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
                        'participant_name'=>$company_name,
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
        $sq = " ";
        foreach($ref_no AS $rn){
            $sq .= " reference_no = '". $rn . "' OR ";
        }

        $sq = substr($sq, 0, -3);

        $total_c = $this->super_model->select_sum_where("collection_details", "amount", $sq);
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
            $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$purchase->billing_id);
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
            'participant_name'=>$company_name,
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
            $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$s->billing_id);
            //$total_amount[]=$s->ewt;
            //$data['total']=array_sum($total_amount);
            $data['total']=$this->super_model->select_sum_join("ewt","purchase_transaction_details","purchase_transaction_head","purchase_transaction_head.purchase_id='$s->purchase_id' AND $query","purchase_id");
            $data['purchase'][]=array(
                'transaction_date'=>$s->transaction_date,
                'tin'=>$tin,
                'participant_name'=>$company_name,
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
        $part=$this->super_model->select_column_where("participant","participant_name","settlement_id",$participant);
        $data['part'] = $part;
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['reference_no']=$this->super_model->custom_query("SELECT DISTINCT reference_no FROM collection_details WHERE reference_no!=''");
        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant GROUP BY settlement_id");
        $sql='';
        if($participant!='null'){
            $sql.= " settlement_id = '$participant' AND ";
        } 
        if($ref_no!='null'){
            $sql.= " reference_no = '$ref_no' AND ";
        }

        $query=substr($sql,0,-4);
        $qu = " WHERE ewt!='0' AND ".$query;
        $data['total']=0;
        //foreach($this->super_model->select_innerjoin_where("collection_details","collection_head", $qu,"collection_id","settlement_id") AS $s){
        foreach($this->super_model->custom_query("SELECT * FROM collection_details ptd INNER JOIN collection_head pth ON ptd.collection_id=pth.collection_id $qu") AS $s){
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

        }
        $this->load->view('reports/cwht_summary',$data);
        $this->load->view('template/footer');
    }

    public function sales_ledger(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['reference_no']=$this->super_model->custom_query("SELECT DISTINCT reference_number FROM sales_transaction_head WHERE reference_number!=''");
        $ref_no=$this->uri->segment(3);
        $date_from=$this->uri->segment(4);
        $date_to=$this->uri->segment(5);
        $data['ref_no'] = $ref_no;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $sql='';
        if($date_from!='null' && $date_to != 'null'){
            $sql.= "billing_from = '$date_from' AND billing_to = '$date_to' AND ";
        } 
        if($ref_no!='null'){
            $sql.= "reference_number = '$ref_no' AND ";
        }

        

        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND ".$query;
        $data['bill']=array();
        $data['total_vatable_sales']=0;
        $data['total_vatable_sales']=0;
        $data['total_amount']=0.00;
        $data['total_vatable_balance']=0;
        $total_vatable_balance=array();
        $data['total_zero_rated']=0;
        $data['total_c_zero_rated']=0;
        $data['total_zero_rated_balance']=0;
        $total_zero_rated_balance=array();
        $data['total_zero_ecozones']=0;
        $data['total_c_zero_ecozones']=0;
        $data['total_zero_ecozones_balance']=0;
        $total_zero_ecozones_balance=array();
        $data['total_vat']=0;
        $data['total_c_vat']=0;
        $data['total_vat_balance']=0;
        $total_vat_balance=array();
        $data['total_ewt']=0;
        $data['total_c_ewt']=0;
        $data['total_ewt_balance']=0;
        $total_ewt_balance=array();
        foreach($this->super_model->select_innerjoin_where("sales_transaction_details","sales_transaction_head", $qu,"sales_id","short_name") AS $b){
                $vatable_sales = $this->super_model->select_sum_where("sales_transaction_details","vatable_sales","sales_id='$b->sales_id' AND short_name='$b->short_name'");
                $zero_rated_sales = $this->super_model->select_sum_where("sales_transaction_details","zero_rated_sales","sales_id='$b->sales_id' AND short_name='$b->short_name'");
                $zero_rated_ecozones = $this->super_model->select_sum_where("sales_transaction_details","zero_rated_ecozones","sales_id='$b->sales_id' AND short_name='$b->short_name'");
                $vat_on_sales = $this->super_model->select_sum_where("sales_transaction_details","vat_on_sales","sales_id='$b->sales_id' AND short_name='$b->short_name'");
                $ewt_sales = $this->super_model->select_sum_where("sales_transaction_details","ewt","sales_id='$b->sales_id' AND short_name='$b->short_name'");

                $amount=$this->super_model->select_sum_where("collection_details","amount","settlement_id='$b->short_name' AND reference_no='$b->reference_number'");
                $zero_rated=$this->super_model->select_sum_where("collection_details","zero_rated","settlement_id='$b->short_name' AND reference_no='$b->reference_number'");
                $zero_rated_ecozone=$this->super_model->select_sum_where("collection_details","zero_rated_ecozone","settlement_id='$b->short_name' AND reference_no='$b->reference_number'");
                $vat=$this->super_model->select_sum_where("collection_details","vat","settlement_id='$b->short_name' AND reference_no='$b->reference_number'");
                $ewt=$this->super_model->select_sum_where("collection_details","ewt","settlement_id='$b->short_name' AND reference_no='$b->reference_number'");

                $vatablebalance=$vatable_sales - $amount;
                $zerobalance=$zero_rated_sales - $zero_rated;
                $zeroecobalance=$zero_rated_ecozones - $zero_rated_ecozone;
                $vatbalance=$vat_on_sales - $vat;
                $ewtbalance=$ewt_sales - $ewt;
                //$total_vatable_sales[]=$vatable_sales;
                //$data['total_vatable_sales']=array_sum($total_vatable_sales);
                //$total_amount[]=$amount;
                //$data['total_amount']=array_sum($total_amount);
                //$total_vatable_balance[]=$vatablebalance;
                $vatable_sales_total= $this->super_model->select_sum_where("sales_transaction_details","vatable_sales","sales_id='$b->sales_id'");
                $data['total_vatable_sales']=$vatable_sales_total;
                $amount_total=$this->super_model->select_sum_where("collection_details","amount","reference_no='$b->reference_number'");
                $data['total_amount']=$amount_total;
                $total_vatable_balance[]=$vatablebalance;
                //$data['total_vatable_balance']=$vatable_sales_total-$amount_total;
                
                /*$total_zero_rated[]=$zero_rated_sales;
                $data['total_zero_rated']=array_sum($total_zero_rated);
                $total_c_zero_rated[]=$zero_rated;
                $data['total_c_zero_rated']=array_sum($total_c_zero_rated);
                $total_zero_rated_balance[]=$zerobalance;
                */
                $zero_rated_total=$this->super_model->select_sum_where("sales_transaction_details","zero_rated_sales","sales_id='$b->sales_id'");
                $data['total_zero_rated']=$zero_rated_total;
                $c_zero_rated_total=$this->super_model->select_sum_where("collection_details","zero_rated","reference_no='$b->reference_number'");
                $data['total_c_zero_rated']=$c_zero_rated_total;
                $total_zero_rated_balance[]=$zerobalance;
                //$data['total_zero_rated_balance']=$zero_rated_total-$c_zero_rated_total;
                

                /*$total_zero_ecozones[]=$zero_rated_ecozones;
                $data['total_zero_ecozones']=array_sum($total_zero_ecozones);
                $total_c_zero_ecozones[]=$zero_rated_ecozone;
                $data['total_c_zero_ecozones']=array_sum($total_c_zero_ecozones);
                $total_zero_ecozones_balance[]=$zeroecobalance;
                */
                $zero_rated_ecozone_total=$this->super_model->select_sum_where("sales_transaction_details","zero_rated_ecozones","sales_id='$b->sales_id'");
                $data['total_zero_ecozones']=$zero_rated_ecozone_total;
                $c_zero_rated_ecozone_total=$this->super_model->select_sum_where("collection_details","zero_rated_ecozone","reference_no='$b->reference_number'");
                $data['total_c_zero_ecozones']=$c_zero_rated_ecozone_total;
                $total_zero_ecozones_balance[]=$zeroecobalance;
                //$data['total_zero_ecozones_balance']=$zero_rated_ecozone_total-$c_zero_rated_ecozone_total;

                /*$total_vat[]=$vat_on_sales;
                $data['total_vat']=array_sum($total_vat);
                $total_c_vat[]=$vat;
                $data['total_c_vat']=array_sum($total_c_vat);
                $total_vat_balance[]=$vatbalance;
                */
                $vat_total=$this->super_model->select_sum_where("sales_transaction_details","vat_on_sales","sales_id='$b->sales_id'");
                $data['total_vat']=$vat_total;
                $c_vat_total=$this->super_model->select_sum_where("collection_details","vat","reference_no='$b->reference_number'");
                $data['total_c_vat']=$c_vat_total;
                $total_vat_balance[]=$vatbalance;
                //$data['total_vat_balance']=$vat_total-$c_vat_total;
                

                /*$total_ewt[]=$ewt_sales;
                $data['total_ewt']=array_sum($total_ewt);
                $total_c_ewt[]=$ewt;
                $data['total_c_ewt']=array_sum($total_c_ewt);
                $total_ewt_balance[]=$ewtbalance;
                */
                $ewt_total=$this->super_model->select_sum_where("sales_transaction_details","ewt","sales_id='$b->sales_id'");
                $data['total_ewt']=$ewt_total;
                $c_ewt_total=$this->super_model->select_sum_where("collection_details","ewt","reference_no='$b->reference_number'");
                $data['total_c_ewt']=$c_ewt_total;
                $total_ewt_balance[]=$ewtbalance;
                //$data['total_ewt_balance']=$ewt_total-$c_ewt_total;
               

                $data['bill'][]=array(
                    "date"=>$b->transaction_date,
                    "company_name"=>$b->company_name,
                    "billing_from"=>$b->billing_from,
                    "billing_to"=>$b->billing_to,
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
            //}
        }
        $data['total_vatable_balance']=array_sum($total_vatable_balance);
        $data['total_zero_rated_balance']=array_sum($total_zero_rated_balance);
        $data['total_zero_ecozones_balance']=array_sum($total_zero_ecozones_balance);
        $data['total_vat_balance']=array_sum($total_vat_balance);
        $data['total_ewt_balance']=array_sum($total_ewt_balance);
        $this->load->view('reports/sales_ledger',$data);
        $this->load->view('template/footer');
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
                $ewt_balance=$b->ewt - $c->ewt;
                $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$b->billing_id);


                $data['bill'][]=array(
                    "date"=>$b->transaction_date,
                    "company_name"=>$company_name,
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
                $total_p_vat= $this->super_model->select_sum_where("payment_details","vat","purchase_details_id ='$b->purchase_detail_id'");
                $data['total_p_vat']=$total_p_vat;
                $total_vat_balance[]=$vat_balance;
                
                $total_ewt= $this->super_model->select_sum_where("purchase_transaction_details","ewt","purchase_id='$b->purchase_id'");
                $data['total_ewt']=$total_ewt;
                $total_p_ewt= $this->super_model->select_sum_where("payment_details","vat","purchase_details_id ='$b->purchase_detail_id'");
                $data['total_p_ewt']=$total_p_ewt;
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

        $data['total_vat_balance']=array_sum($total_vat_balance);

        $data['total_ewt_balance']=array_sum($total_ewt_balance);

        $this->load->view('reports/purchases_ledger',$data);
        $this->load->view('template/footer');
    }

    public function cs_ledger()
    {
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
        $cs_qu = "saved = '1' AND ".$query;
        $data['csledger']=array();

        $data['total_vatable_sales']=0;
        $total_vatable_sales=array();
        $data['total_amount']=0;
        $total_amount=array();
        $data['total_vatable_balance']=0;
        $total_vatable_balance=array();

        $data['total_zero_rated']=0;
        $total_zero_rated=array();
        $data['total_c_zero_rated']=0;
        $total_c_zero_rated=array();
        $data['total_zero_rated_balance']=0;
        $total_zero_rated_balance=array();

        $data['total_zero_ecozones']=0;
        $total_zero_ecozones=array();
        $data['total_c_zero_ecozones']=0;
        $total_c_zero_ecozones=array();
        $data['total_zero_ecozones_balance']=0;
        $total_zero_ecozones_balance=array();

        $data['total_vat']=0;
        $total_vat=array();
        $data['total_c_vat']=0;
        $total_c_vat=array();
        $data['total_vat_balance']=0;
        $total_vat_balance=array();

        $data['total_ewt']=0;
        $total_ewt=array();
        $data['total_c_ewt']=0;
        $total_c_ewt=array();
        $data['total_ewt_balance']=0;
        $total_ewt_balance=array();
/*        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id=std.sales_id WHERE saved='1' $query") AS $b){
            $reference_number=$this->super_model->select_column_where("collection_details","reference_no",'settlement_id',$b->short_name);*/
        //foreach($this->super_model->select_innerjoin_where("sales_transaction_details","sales_transaction_head", $cs_qu,"sales_id","short_name") AS $cs){
        foreach($this->super_model->select_innerjoin_where("sales_transaction_details","sales_transaction_head", $cs_qu,"sales_id","reference_number") AS $cs){
                $vatable_sales = $this->super_model->select_sum_where("sales_transaction_details","vatable_sales","sales_id='$cs->sales_id' AND short_name='$cs->short_name'");
                $zero_rated_sales = $this->super_model->select_sum_where("sales_transaction_details","zero_rated_sales","sales_id='$cs->sales_id' AND short_name='$cs->short_name'");
                $zero_rated_ecozones = $this->super_model->select_sum_where("sales_transaction_details","zero_rated_ecozones","sales_id='$cs->sales_id' AND short_name='$cs->short_name'");
                $vat_on_sales = $this->super_model->select_sum_where("sales_transaction_details","vat_on_sales","sales_id='$cs->sales_id' AND short_name='$cs->short_name'");
                $ewt_sales = $this->super_model->select_sum_where("sales_transaction_details","ewt","sales_id='$cs->sales_id' AND short_name='$cs->short_name'");

            $count_collection = $this->super_model->count_custom_where("collection_details", "reference_no='$cs->reference_number' AND settlement_id ='$cs->short_name'");
                
            if($count_collection>0){

                $amount=$this->super_model->select_sum_where("collection_details","amount","settlement_id='$cs->short_name' AND reference_no='$cs->reference_number'");
                $zero_rated=$this->super_model->select_sum_where("collection_details","zero_rated","settlement_id='$cs->short_name' AND reference_no='$cs->reference_number'");
                $zero_rated_ecozone=$this->super_model->select_sum_where("collection_details","zero_rated_ecozone","settlement_id='$cs->short_name' AND reference_no='$cs->reference_number'");
                $vat=$this->super_model->select_sum_where("collection_details","vat","settlement_id='$cs->short_name' AND reference_no='$cs->reference_number'");
                $ewt=$this->super_model->select_sum_where("collection_details","ewt","settlement_id='$cs->short_name' AND reference_no='$cs->reference_number'");

                $vatablebalance=$vatable_sales - $amount;
                $zerobalance=$zero_rated_sales - $zero_rated;
                $zeroecobalance=$zero_rated_ecozones - $zero_rated_ecozone;
                $vatbalance=$vat_on_sales - $vat;
                $ewtbalance=$ewt_sales - $ewt;


                $total_vatable_sales[]=$vatable_sales;
                $total_amount[]=$amount;
                $total_vatable_balance[]=$vatablebalance;

                $total_zero_rated[]=$zero_rated_sales;         
                $total_c_zero_rated[]=$zero_rated;
                $total_zero_rated_balance[]=$zerobalance;

                $total_zero_ecozones[]=$zero_rated_ecozones;
                $total_c_zero_ecozones[]=$zero_rated_ecozone;
                $total_zero_ecozones_balance[]=$zeroecobalance;

                $total_vat[]=$vat_on_sales;
                $total_c_vat[]=$vat;
                $total_vat_balance[]=$vatbalance;

                $total_ewt[]=$ewt_sales;
                $total_c_ewt[]=$ewt;
                $total_ewt_balance[]=$ewtbalance;

                $data['csledger'][]=array(
                    "date"=>$cs->transaction_date,
                    "company_name"=>$cs->company_name,
                    "billing_from"=>$cs->billing_from,
                    "billing_to"=>$cs->billing_to,
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
        }

        $data['total_vatable_sales']=array_sum($total_vatable_sales);
        $data['total_amount']=array_sum($total_amount);
        $data['total_vatable_balance']=array_sum($total_vatable_balance);

        $data['total_zero_rated']=array_sum($total_zero_rated);
        $data['total_c_zero_rated']=array_sum($total_c_zero_rated);
        $data['total_zero_rated_balance']=array_sum($total_zero_rated_balance);

        $data['total_zero_ecozones']=array_sum($total_zero_ecozones);
        $data['total_c_zero_ecozones']=array_sum($total_c_zero_ecozones);
        $data['total_zero_ecozones_balance']=array_sum($total_zero_ecozones_balance);

        $data['total_vat']=array_sum($total_vat);
        $data['total_c_vat']=array_sum($total_c_vat);
        $data['total_vat_balance']=array_sum($total_vat_balance);

        $data['total_ewt']=array_sum($total_ewt);
        $data['total_c_ewt']=array_sum($total_c_ewt);
        $data['total_ewt_balance']=array_sum($total_ewt_balance);

        $this->load->view('reports/cs_ledger', $data);
        $this->load->view('template/footer');
    }

    public function ss_ledger()
    {
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
                $vatables_purchases = $this->super_model->select_sum_where("purchase_transaction_details","vatables_purchases","purchase_id='$ss->purchase_id' AND billing_id='$ss->billing_id'");
                $zero_rated_purchases = $this->super_model->select_sum_where("purchase_transaction_details","zero_rated_purchases","purchase_id='$ss->purchase_id' AND billing_id='$ss->billing_id'");
                $zero_rated_ecozones = $this->super_model->select_sum_where("purchase_transaction_details","zero_rated_ecozones","purchase_id='$ss->purchase_id' AND billing_id='$ss->billing_id'");
                $vat_on_purchases = $this->super_model->select_sum_where("purchase_transaction_details","vat_on_purchases","purchase_id='$ss->purchase_id' AND billing_id='$ss->billing_id'");
                $ewt_purchases = $this->super_model->select_sum_where("purchase_transaction_details","ewt","purchase_id='$ss->purchase_id' AND billing_id='$ss->billing_id'");

                $purchase_mode=$this->super_model->select_column_where("payment_details","purchase_mode","purchase_details_id",$ss->purchase_detail_id);
                $purchase_amount=$this->super_model->select_column_where("payment_details","purchase_amount","purchase_details_id",$ss->purchase_detail_id);
                $vat=$this->super_model->select_column_where("payment_details","vat","purchase_details_id",$ss->purchase_detail_id);
                $ewt=$this->super_model->select_column_where("payment_details","ewt","purchase_details_id",$ss->purchase_detail_id);

                $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$ss->billing_id);

                $count_payment = $this->super_model->count_custom_where("payment_details", "purchase_details_id ='$ss->purchase_detail_id'");

            if($count_payment>0){

            if($purchase_mode=='Vatable Purchase'){
                $vatable_purchase=$purchase_amount;
                $zero_rated='0.00';
                $rated_ecozones='0.00';
            }else if($purchase_mode=='Zero Rated Purchase'){
                $vatable_purchases='0.00';
                $zero_rated=$purchase_amount;
                $rated_ecozones='0.00';
            }else if($purchase_mode=='Zero Rated Ecozones'){
                $vatable_purchases='0.00';
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
                    "company_name"=>$company_name,
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
    
}