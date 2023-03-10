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
        foreach($this->super_model->select_inner_join_where("sales_transaction_details","sales_transaction_head", $qu,"sales_id","short_name") AS $b){
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
        foreach($this->super_model->select_inner_join_where("sales_transaction_details","sales_transaction_head", $cs_qu,"sales_id"," short_name") AS $cs){

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
                $ewtbalance=$ewt - $ewt;


                $total_vatable_sales[]=$vatable_sales;
                $total_amount[]=$amount;

               // $total_amount = array_unique($total_amount);



               // $total_vatable_balance[]=$vatablebalance;

                $total_zero_rated[]=$zero_rated_sales;         
                $total_c_zero_rated[]=$zero_rated;
             
                //$total_zero_rated_balance[]=$zerobalance;

                $total_zero_ecozones[]=$zero_rated_ecozones;
                $total_c_zero_ecozones[]=$zero_rated_ecozone;

              
                //$total_zero_ecozones_balance[]=$zeroecobalance;

                $total_vat[]=$vat_on_sales;
                $total_c_vat[]=$vat;
               
                //$total_vat_balance[]=$vatbalance;

                $total_ewt[]=$ewt;
                $total_c_ewt[]=$ewt;
             
                //$total_ewt_balance[]=$ewtbalance;
                $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $cs->sales_id);
                $company_name=$this->super_model->select_column_where("sales_transaction_details", "company_name", "sales_detail_id", $cs->sales_detail_id);
                if(!empty($company_name) && date('Y',strtotime($create_date))==date('Y')){
                    $comp_name=$company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $cs->billing_id);
                }
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
        $data['total_vatable_balance']=array_sum($total_vatable_sales) - array_sum($total_amount);

        $data['total_zero_rated']=array_sum($total_zero_rated);
        $data['total_c_zero_rated']=array_sum($total_c_zero_rated);
        $data['total_zero_rated_balance']=array_sum($total_zero_rated) -array_sum($total_c_zero_rated);

        $data['total_zero_ecozones']=array_sum($total_zero_ecozones);
        $data['total_c_zero_ecozones']=array_sum($total_c_zero_ecozones);
        $data['total_zero_ecozones_balance']=array_sum($total_zero_ecozones) - array_sum($total_c_zero_ecozones);

        $data['total_vat']=array_sum($total_vat);
        $data['total_c_vat']=array_sum($total_c_vat);
        $data['total_vat_balance']=array_sum($total_vat)- array_sum($total_c_vat);

        $data['total_ewt']=array_sum($total_ewt);
        $data['total_c_ewt']=array_sum($total_c_ewt);
        $data['total_ewt_balance']=array_sum($total_ewt) - array_sum($total_c_ewt);

        $this->load->view('reports/cs_ledger', $data);
        $this->load->view('template/footer');
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
        //$this->load->view('template/navbar');
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

        $from_date  = strtotime($from);
        $from_day   = date('d',$from_date);
        $from_month = date('m',$from_date);
        $from_year  = date('Y',$from_date);

        $to_date  = strtotime($to);
        $to_day   = date('d',$to_date);
        $to_month = date('m',$to_date);
        $to_year  = date('Y',$to_date);

        if($from!='null' && $to != 'null'){
            $sql.= "MONTH(billing_from) >= '$from_month' AND MONTH(billing_to) <= '$to_month' AND DAY(billing_from) >= '$from_day' AND DAY(billing_to) <= '$to_day' AND YEAR(billing_from) >= '$from_year' AND YEAR(billing_to) <= '$to_year' AND ";
        } if($participant!='null'){
             $sql.= "tin = '$participant' AND "; 
        } if($original!='null' && isset($original)){
             $sql.= "original_copy = '$original' AND "; 
        } if($scanned!='null'  && isset($scanned)){
             $sql.= "scanned_copy = '$scanned' AND ";
        }

        $query=substr($sql,0,-4);
        $qu = "saved='1' AND adjustment!='1' AND ".$query;
        $total_sum[]=0;

        echo $query;
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pth INNER JOIN purchase_transaction_details ptd ON pth.purchase_id = ptd.purchase_id INNER JOIN participant p ON p.billing_id = ptd.billing_id WHERE $qu ORDER BY billing_from ASC, reference_number ASC, participant_name  ASC, p.billing_id ASC") AS $pth){
            //$participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$pth->billing_id);
            // $create_date = $this->super_model->select_column_where("purchase_transaction_head", "create_date", "purchase_id", $pth->purchase_id);
            // $company_name=$this->super_model->select_column_where("purchase_transaction_details", "company_name", "purchase_detail_id", $pth->purchase_detail_id);
            if(!empty($pth->company_name) && date('Y',strtotime($pth->create_date))==date('Y')){
                $comp_name=$pth->company_name;
            }else{
                $comp_name=$pth->participant_name;
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
                'ewt'=>$pth->ewt,
                'or_no'=>$pth->or_no,
                'total_update'=>$pth->total_update,
                'original_copy'=>$pth->original_copy,
                'scanned_copy'=>$pth->scanned_copy,
                'total'=>$total,
            );
        }
        $data['total_sum']=array_sum($total_sum);
        $this->load->view('reports/purchases_all',$data);
        $this->load->view('template/footer');
    }

        public function export_purchases_all(){
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="Purchases Wesm All Transcations.xlsx";
        $sql='';

        $from_date  = strtotime($from);
        $from_day   = date('d',$from_date);
        $from_month = date('m',$from_date);
        $from_year  = date('Y',$from_date);

        $to_date  = strtotime($to);
        $to_day   = date('d',$to_date);
        $to_month = date('m',$to_date);
        $to_year  = date('Y',$to_date);

        if($from!='null' && $to != 'null'){
            $sql.= "MONTH(billing_from) >= '$from_month' AND MONTH(billing_to) <= '$to_month' AND DAY(billing_from) >= '$from_day' AND DAY(billing_to) <= '$to_day' AND YEAR(billing_from) >= '$from_year' AND YEAR(billing_to) <= '$to_year' AND ";
        } if($participant!='null'){
             $sql.= "tin = '$participant' AND "; 
        }

        $query=substr($sql,0,-4);
        if($participant !='null' || $from != 'null' || $to != 'null'){
            $qu = " saved = '1' AND adjustment != '1' AND ".$query;
        }else{
             $qu = " saved = '1' AND adjustment != '1'";
        }

        $sheetno=0;
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pah INNER JOIN purchase_transaction_details pad ON pah.purchase_id = pad.purchase_id INNER JOIN participant p ON p.billing_id = pad.billing_id WHERE $qu AND participant_name != '' GROUP BY tin ORDER BY participant_name") AS $head){
 
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            // $invalidCharacters = array('*', ':', '/', '\\', '?', '[', ']');
            // $title = str_replace($invalidCharacters, '', $head->settlement_id);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($head->settlement_id);
            foreach(range('A','L') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A1', "Billing Period");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B1', "Billing ID");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C1', "Transaction Reference Number");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D1', "Company Name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E1', "Vatables Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F1', "Vat On Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G1', "EWT");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H1', "Total");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I1', "OR Number");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J1', "Total Amount");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K1', "Original Copy");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L1', "Scanned Copy");
            $objPHPExcel->getActiveSheet()->getStyle("A1:L1")->applyFromArray($styleArray);
            foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pah INNER JOIN purchase_transaction_details pad ON pah.purchase_id = pad.purchase_id INNER JOIN participant p ON p.billing_id = pad.billing_id WHERE tin='$head->tin' AND participant_name != '' AND $qu ORDER BY billing_from ASC, reference_number ASC, p.billing_id ASC") AS $pah){
            //$participant_name=$this->super_model->select_column_where("participant","participant_name","settlement_id",$pah->short_name);
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
            $tin=$this->super_model->select_column_where("participant","tin","billing_id",$pah->billing_id);
                $purchaseall[]=array(
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
                if($value['tin']==$tin){
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $value['billing_date']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B'.$num, $value['billing_id']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $value['reference_number']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D'.$num, $value['participant_name']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, "(".$value['vatables_purchases'].")");
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F'.$num, "(".$value['vat_on_purchases'].")");
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G'.$num, $value['ewt']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, "(".$total.")");
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I'.$num, $value['or_no']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J'.$num, "(".$value['total_update'].")");
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

                $nextKey = isset($purchaseall[$index+1]) ? $purchaseall[$index+1]['billing_date'] : null;

                if($row >= $startRow && (($previousKey <> $nextKey) || ($nextKey == null))){
                    $cellToMerge = 'A'.$startRow.':A'.$row;
                    $objPHPExcel->getActiveSheet()->mergeCells($cellToMerge);
                    $startRow = -1;

                }
                $row++;
                
                $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->getColor()->setRGB ('FFFFFF');
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":L".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":H".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $num++;
            }
        }
            $sheetno++;
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
        unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Purchases Wesm All Transcations.xlsx"');
        readfile($exportfilename);
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

        $from_date  = strtotime($from);
        $from_day   = date('d',$from_date);
        $from_month = date('m',$from_date);
        $from_year  = date('Y',$from_date);

        $to_date  = strtotime($to);
        $to_day   = date('d',$to_date);
        $to_month = date('m',$to_date);
        $to_year  = date('Y',$to_date);

        if($from!='null' && $to != 'null'){
            $sql.= "MONTH(billing_from) >= '$from_month' AND MONTH(billing_to) <= '$to_month' AND DAY(billing_from) >= '$from_day' AND DAY(billing_to) <= '$to_day' AND YEAR(billing_from) >= '$from_year' AND YEAR(billing_to) <= '$to_year' AND ";
        } if($participant!='null'){
             $sql.= "tin = '$participant' AND "; 
        } if($original!='null' && isset($original)){
             $sql.= "original_copy = '$original' AND "; 
        } if($scanned!='null'  && isset($scanned)){
             $sql.= "scanned_copy = '$scanned' AND "; 
        }

        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND ".$query;

        $total_sum[]=0;
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id = std.sales_id INNER JOIN participant p ON p.billing_id = std.billing_id WHERE $qu ORDER BY billing_from ASC, reference_number ASC, participant_name  ASC, p.billing_id ASC") AS $sth){
            //$participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$sth->billing_id);
            // $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $sth->sales_id);
            // $participant_name=$this->super_model->select_column_where("sales_transaction_details", "company_name", "sales_detail_id", $sth->sales_detail_id);
            if(!empty($sth->company_name) && date('Y',strtotime($sth->create_date))==date('Y')){
                    $comp_name=$sth->company_name;
                }else{
                    $comp_name=$sth->participant_name;
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
            );
        }
        $data['total_sum']=array_sum($total_sum);
        $this->load->view('reports/sales_all',$data);
        $this->load->view('template/footer');
    }

    public function export_sales_all(){
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="Sales Wesm All Transcations.xlsx";
        $sql='';

        $from_date  = strtotime($from);
        $from_day   = date('d',$from_date);
        $from_month = date('m',$from_date);
        $from_year  = date('Y',$from_date);

        $to_date  = strtotime($to);
        $to_day   = date('d',$to_date);
        $to_month = date('m',$to_date);
        $to_year  = date('Y',$to_date);

        if($from!='null' && $to != 'null'){
            $sql.= "MONTH(billing_from) >= '$from_month' AND MONTH(billing_to) <= '$to_month' AND DAY(billing_from) >= '$from_day' AND DAY(billing_to) <= '$to_day' AND YEAR(billing_from) >= '$from_year' AND YEAR(billing_to) <= '$to_year' AND ";
        } if($participant!='null'){
             $sql.= " tin = '$participant' AND "; 
        }

        $query=substr($sql,0,-4);
        if($participant !='null' || $from != 'null' || $to != 'null'){
            $qu = " saved = '1' AND ".$query;
        }else{
             $qu = " saved = '1'";
        }
        $sheetno=0;
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
           
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id = std.sales_id INNER JOIN participant p ON p.billing_id = std.billing_id WHERE participant_name != '' AND $qu GROUP BY tin ORDER BY participant_name") AS $head){
            //foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id = std.sales_id WHERE $qu ORDER BY sales_detail_id ASC") AS $sth){
            
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($head->settlement_id);
            
            foreach(range('A','L') as $columnID){
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
            $objPHPExcel->getActiveSheet()->getStyle("A1:L1")->applyFromArray($styleArray);
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id = std.sales_id INNER JOIN participant p ON p.billing_id = std.billing_id WHERE tin='$head->tin' AND participant_name != '' AND $qu ORDER BY billing_from ASC, reference_number ASC, p.billing_id ASC") AS $sth){
                //$participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$sth->billing_id);
                // $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $sth->sales_id);
                $participant_name=$this->super_model->select_column_where("sales_transaction_details", "company_name", "sales_detail_id", $sth->sales_detail_id);
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
                //if($value['short_name']==$sth->short_name){
                if($value['tin']==$tin){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $value['billing_date']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B'.$num, $value['billing_id']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $value['reference_number']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D'.$num, $value['participant_name']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, $value['vatable_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F'.$num, $zero_rated);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G'.$num, $value['vat_on_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, "(".$value['ewt'].")");
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

                    $nextKey = isset($salesall[$index+1]) ? $salesall[$index+1]['billing_date'] : null;

                    if($row >= $startRow && (($previousKey <> $nextKey) || ($nextKey == null))){
                        $cellToMerge = 'A'.$startRow.':A'.$row;
                        $objPHPExcel->getActiveSheet()->mergeCells($cellToMerge);
                        $startRow = -1;

                    }
                    $row++;

                    $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                    $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->getColor()->setRGB ('FFFFFF');
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":L".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":I".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $num++;
                }
            }
            $sheetno++;
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
        unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Sales Wesm All Transcations.xlsx"');
        readfile($exportfilename);
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

        $from_date  = strtotime($from);
        $from_day   = date('d',$from_date);
        $from_month = date('m',$from_date);
        $from_year  = date('Y',$from_date);

        $to_date  = strtotime($to);
        $to_day   = date('d',$to_date);
        $to_month = date('m',$to_date);
        $to_year  = date('Y',$to_date);

        if($from!='null' && $to != 'null'){
            $sql.= "MONTH(billing_from) >= '$from_month' AND MONTH(billing_to) <= '$to_month' AND DAY(billing_from) >= '$from_day' AND DAY(billing_to) <= '$to_day' AND YEAR(billing_from) >= '$from_year' AND YEAR(billing_to) <= '$to_year' AND ";
        }if($due_date!='null'){
            $sql.= "due_date = '$due_date' AND ";
        } if($participant!='null'){
             $sql.= "tin = '$participant' AND "; 
        } if($original!='null' && isset($original)){
             $sql.= "original_copy = '$original' AND "; 
        } if($scanned!='null'  && isset($scanned)){
             $sql.= "scanned_copy = '$scanned' AND "; 
        }

        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND adjustment = '1' AND ".$query;

        $total_sum[]=0;
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pth INNER JOIN purchase_transaction_details ptd ON pth.purchase_id = ptd.purchase_id INNER JOIN participant p ON p.billing_id = ptd.billing_id WHERE $qu ORDER BY billing_from ASC, reference_number ASC, participant_name  ASC, p.billing_id ASC") AS $pth){
            //$participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$pth->billing_id);
            $total=($pth->vatables_purchases+$pth->vat_on_purchases)-$pth->ewt;
            $total_sum[]=$total;
            // $create_date = $this->super_model->select_column_where("purchase_transaction_head", "create_date", "purchase_id", $pth->purchase_id);
            // $participant_name=$this->super_model->select_column_where("purchase_transaction_details", "company_name", "purchase_detail_id", $pth->purchase_detail_id);
            if(!empty($pth->company_name) && date('Y',strtotime($pth->create_date))==date('Y')){
                    $comp_name=$pth->company_name;
                }else{
                    $comp_name=$pth->participant_name;
                }
            $data['purchasead_all'][]=array(
                'participant_name'=>$comp_name,
                'billing_id'=>$pth->billing_id,
                'reference_number'=>$pth->reference_number,
                'billing_from'=>$pth->billing_from,
                'billing_to'=>$pth->billing_to,
                'vatables_purchases'=>$pth->vatables_purchases,
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

        public function export_purchases_adjustment_all(){
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        $due=$this->uri->segment(6);
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="Purchases Wesm Adjustment All Transcations.xlsx";
        $sql='';

        $from_date  = strtotime($from);
        $from_day   = date('d',$from_date);
        $from_month = date('m',$from_date);
        $from_year  = date('Y',$from_date);

        $to_date  = strtotime($to);
        $to_day   = date('d',$to_date);
        $to_month = date('m',$to_date);
        $to_year  = date('Y',$to_date);

        if($from!='null' && $to != 'null'){
            $sql.= "MONTH(billing_from) >= '$from_month' AND MONTH(billing_to) <= '$to_month' AND DAY(billing_from) >= '$from_day' AND DAY(billing_to) <= '$to_day' AND YEAR(billing_from) >= '$from_year' AND YEAR(billing_to) <= '$to_year' AND ";
        } if($participant!='null'){
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
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pah INNER JOIN purchase_transaction_details pad ON pah.purchase_id = pad.purchase_id INNER JOIN participant p ON p.billing_id = pad.billing_id WHERE participant_name != '' AND $qu GROUP BY tin ORDER BY participant_name") AS $head){
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($head->settlement_id);
            foreach(range('A','L') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A1', "Billing Period");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B1', "Billing ID");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C1', "Transaction Reference Number");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D1', "Company Name");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E1', "Vatables Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F1', "Vat On Purchases");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G1', "EWT");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H1', "Total");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I1', "OR Number");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J1', "Total Amount");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('K1', "Original Copy");
            $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L1', "Scanned Copy");
            $objPHPExcel->getActiveSheet()->getStyle("A1:L1")->applyFromArray($styleArray);
            foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pah INNER JOIN purchase_transaction_details pad ON pah.purchase_id = pad.purchase_id INNER JOIN participant p ON p.billing_id = pad.billing_id WHERE tin='$head->tin' AND participant_name != '' AND $qu ORDER BY billing_from ASC, reference_number ASC, p.billing_id ASC") AS $pah){
            //$participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$pah->billing_id);
            // $zero_rated=$pah->zero_rated_purchases+$pah->zero_rated_ecozones;
            // $total=($pah->vatables_purchases+$zero_rated+$pah->vat_on_purchases)-$pah->ewt;
            // $create_date = $this->super_model->select_column_where("purchase_transaction_head", "create_date", "purchase_id", $pah->purchase_id);
            // $participant_name=$this->super_model->select_column_where("purchase_transaction_details", "company_name", "purchase_detail_id", $pah->purchase_detail_id);
           if(!empty($pah->company_name) && date('Y',strtotime($pah->create_date))==date('Y')){
                    $comp_name=$pah->company_name;
                }else{
                    $comp_name=$pah->participant_name;
                }
            $billing_date = date("M. d, Y",strtotime($pah->billing_from))." - ".date("M. d, Y",strtotime($pah->billing_to));
            $tin=$this->super_model->select_column_where("participant","tin","billing_id",$pah->billing_id);

                $purchasealladjustment[]=array(
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
                    'short_name'=>$pah->short_name,
                    'zero_rated_purchases'=>$pah->zero_rated_purchases,
                    'zero_rated_ecozones'=>$pah->zero_rated_ecozones,
                    'tin'=>$tin,
                    //'total'=>$total,
                );

            }
                $row = 2;
                $startRow = -1;
                $previousKey = '';
                $num=2;
                foreach($purchasealladjustment AS $index => $value){
                    if($startRow == -1){
                        $startRow = $row;
                        $previousKey = $value['billing_date'];
                    }

                $zero_rated=$value['zero_rated_purchases']+$value['zero_rated_ecozones'];
                $total=($value['vatables_purchases']+$zero_rated+$value['vat_on_purchases'])-$value['ewt'];
                if($value['tin']==$tin){
                //if($value['short_name']==$pah->short_name){
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $value['billing_date']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B'.$num, $value['billing_id']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $value['reference_number']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D'.$num, $value['participant_name']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, "(".$value['vatables_purchases'].")");
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F'.$num, "(".$value['vat_on_purchases'].")");
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G'.$num, $value['ewt']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, "(".$total.")");
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('I'.$num, $value['or_no']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('J'.$num, "(".$value['total_update'].")");
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

                $nextKey = isset($purchasealladjustment[$index+1]) ? $purchasealladjustment[$index+1]['billing_date'] : null;

                if($row >= $startRow && (($previousKey <> $nextKey) || ($nextKey == null))){
                    $cellToMerge = 'A'.$startRow.':A'.$row;
                    $objPHPExcel->getActiveSheet()->mergeCells($cellToMerge);
                    $startRow = -1;

                }
                $row++;
                
                $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->getColor()->setRGB ('FFFFFF');
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":L".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":H".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $num++;
            }
        }
            $sheetno++;
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
        unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Purchases Wesm Adjustment All Transcations.xlsx"');
        readfile($exportfilename);
    }

    public function sales_all_adjustment(){
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
        $data['date'] = $this->super_model->custom_query("SELECT DISTINCT due_date FROM sales_adjustment_head WHERE due_date!='' AND saved = '1'");
        $sql="";

        $from_date  = strtotime($from);
        $from_day   = date('d',$from_date);
        $from_month = date('m',$from_date);
        $from_year  = date('Y',$from_date);

        $to_date  = strtotime($to);
        $to_day   = date('d',$to_date);
        $to_month = date('m',$to_date);
        $to_year  = date('Y',$to_date);

        if($from!='null' && $to != 'null'){
            $sql.= "MONTH(billing_from) >= '$from_month' AND MONTH(billing_to) <= '$to_month' AND DAY(billing_from) >= '$from_day' AND DAY(billing_to) <= '$to_day' AND YEAR(billing_from) >= '$from_year' AND YEAR(billing_to) <= '$to_year' AND ";
        }if($due_date!='null'){
            $sql.= "due_date = '$due_date' AND ";
        } if($participant!='null'){
             $sql.= "tin = '$participant' AND "; 
        } if($original!='null' && isset($original)){
             $sql.= "original_copy = '$original' AND "; 
        } if($scanned!='null'  && isset($scanned)){
             $sql.= "scanned_copy = '$scanned' AND "; 
        }

        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND ".$query;

        $total_sum[]=0;
                foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_head sah INNER JOIN sales_adjustment_details sad ON sah.sales_adjustment_id = sad.sales_adjustment_id INNER JOIN participant p ON p.billing_id = sad.billing_id WHERE $qu ORDER BY billing_from ASC, reference_number ASC, participant_name  ASC, p.billing_id ASC") AS $sah){
            //$participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$sah->billing_id);
            // $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id ", $sah->sales_adjustment_id);
            // $participant_name=$this->super_model->select_column_where("sales_adjustment_details", "company_name", "adjustment_detail_id ", $sah->adjustment_detail_id);
            if(!empty($sah->company_name) && date('Y',strtotime($sah->create_date))==date('Y')){
                    $comp_name=$sah->company_name;
                }else{
                    $comp_name=$sah->participant_name;
                }
            $zero_rated=$sah->zero_rated_sales+$sah->zero_rated_ecozones;
            $total=($sah->vatable_sales+$zero_rated+$sah->vat_on_sales)-$sah->ewt;
            $total_sum[]=$total;

            $data['salesad_all'][]=array(
                'participant_name'=>$comp_name,
                'billing_id'=>$sah->billing_id,
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
            );
        }
        $data['total_sum']=array_sum($total_sum);
        $this->load->view('reports/sales_all_adjustment',$data);
        $this->load->view('template/footer');
    }

        public function export_sales_adjustment_all(){
        $participant=$this->uri->segment(3);
        $from=$this->uri->segment(4);
        $to=$this->uri->segment(5);
        $due=$this->uri->segment(6);
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="Sales Wesm Adjustment All Transcations.xlsx";
        $sql='';
        
        $from_date  = strtotime($from);
        $from_day   = date('d',$from_date);
        $from_month = date('m',$from_date);
        $from_year  = date('Y',$from_date);

        $to_date  = strtotime($to);
        $to_day   = date('d',$to_date);
        $to_month = date('m',$to_date);
        $to_year  = date('Y',$to_date);

        if($from!='null' && $to != 'null'){
            $sql.= "MONTH(billing_from) >= '$from_month' AND MONTH(billing_to) <= '$to_month' AND DAY(billing_from) >= '$from_day' AND DAY(billing_to) <= '$to_day' AND YEAR(billing_from) >= '$from_year' AND YEAR(billing_to) <= '$to_year' AND ";
        } if($participant!='null'){
             $sql.= " tin = '$participant' AND "; 
        }   if($due!='null'){
             $sql.= " due_date = '$due' AND "; 
        }

        $query=substr($sql,0,-4);
        if($participant !='null' || $from != 'null' || $to != 'null'){
            $qu = " saved = '1' AND ".$query;
        }else{
             $qu = " saved = '1'";
        }
        $sheetno=0;
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_head sah INNER JOIN sales_adjustment_details sad ON sah.sales_adjustment_id = sad.sales_adjustment_id INNER JOIN participant p ON p.billing_id = sad.billing_id WHERE participant_name != '' AND $qu GROUP BY tin ORDER BY participant_name") AS $head){
            $objWorkSheet = $objPHPExcel->createSheet($sheetno);
            $objPHPExcel->setActiveSheetIndex($sheetno)->setTitle($head->settlement_id);
            foreach(range('A','L') as $columnID){
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
            $objPHPExcel->getActiveSheet()->getStyle("A1:L1")->applyFromArray($styleArray);
            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_head sah INNER JOIN sales_adjustment_details sad ON sah.sales_adjustment_id = sad.sales_adjustment_id INNER JOIN participant p ON p.billing_id = sad.billing_id WHERE tin='$head->tin' AND participant_name != '' AND $qu ORDER BY billing_from ASC, reference_number ASC, p.billing_id ASC") AS $sah){
            //$participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$sah->billing_id);
            // $zero_rated=$sah->zero_rated_sales+$sah->zero_rated_ecozones;
            // $total=($sah->vatable_sales+$zero_rated+$sah->vat_on_sales)-$sah->ewt;
            // $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id ", $sah->sales_adjustment_id );
            // $participant_name=$this->super_model->select_column_where("sales_adjustment_details", "company_name", "adjustment_detail_id ", $sah->adjustment_detail_id );
            if(!empty($sah->company_name) && date('Y',strtotime($sah->create_date))==date('Y')){
                $comp_name=$sah->company_name;
            }else{
                $comp_name=$sah->participant_name;
            }
            $billing_date = date("M. d, Y",strtotime($sah->billing_from))." - ".date("M. d, Y",strtotime($sah->billing_to));
            $tin=$this->super_model->select_column_where("participant","tin","billing_id",$sah->billing_id);

            $salesalladjustment[]=array(
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
                    //'zero_rated'=>$zero_rated,
                    //'total'=>$total,
                );

            }
                $row = 2;
                $startRow = -1;
                $previousKey = '';
                $num=2;
                foreach($salesalladjustment AS $index => $value){
                    if($startRow == -1){
                        $startRow = $row;
                        $previousKey = $value['billing_date'];
                    }


                $zero_rated=$value['zero_rated_sales']+$value['zero_rated_ecozones'];
                $total=($value['vatable_sales']+$zero_rated+$value['vat_on_sales'])-$value['ewt'];
                if($value['tin']==$tin){
                //if($value['short_name']==$sah->short_name){
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $value['billing_date']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('B'.$num, $value['billing_id']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $value['reference_number']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('D'.$num, $value['participant_name']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, $value['vatable_sales']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('F'.$num, $zero_rated);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('G'.$num, $value['vat_on_sales']);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, "(".$value['ewt'].")");
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

                $nextKey = isset($salesalladjustment[$index+1]) ? $salesalladjustment[$index+1]['billing_date'] : null;

                if($row >= $startRow && (($previousKey <> $nextKey) || ($nextKey == null))){
                    $cellToMerge = 'A'.$startRow.':A'.$row;
                    $objPHPExcel->getActiveSheet()->mergeCells($cellToMerge);
                    $startRow = -1;

                }
                $row++;

                $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->getColor()->setRGB ('FFFFFF');
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":L".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":J".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $num++;
            }
        }
            $sheetno++;
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
        unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Sales Wesm Adjustment All Transcations.xlsx"');
        readfile($exportfilename);
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
            $sum_amount= $this->super_model->select_sum_where("collection_details","amount","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id'");
            $sum_zero_rated= $this->super_model->select_sum_where("collection_details","zero_rated","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id'");
            $sum_zero_rated_ecozone= $this->super_model->select_sum_where("collection_details","zero_rated_ecozone","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id'");
            $sum_vat = $this->super_model->select_sum_where("collection_details","vat","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id'");
            $sum_ewt= $this->super_model->select_sum_where("collection_details","ewt","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id'");
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
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
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

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
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
         foreach($this->super_model->custom_query("SELECT DISTINCT  reference_no, settlement_id, collection_date FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE $qu") AS $q){


             
              $x=1;
              $final=1;
                $count = $this->super_model->count_custom("SELECT * FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE $qu AND reference_no = '$q->reference_no' 
                    AND settlement_id = '$q->settlement_id' AND collection_date = '$q->collection_date'");
                foreach($this->super_model->custom_query("SELECT * FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE $qu AND reference_no = '$q->reference_no' 
                    AND settlement_id = '$q->settlement_id' AND collection_date = '$q->collection_date'") AS $col){

                    $sum_amount= $this->super_model->select_sum_where("collection_details","amount","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id'");
                    $sum_zero_rated= $this->super_model->select_sum_where("collection_details","zero_rated","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id'");
                    $sum_zero_rated_ecozone= $this->super_model->select_sum_where("collection_details","zero_rated_ecozone","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id'");
                    $sum_vat = $this->super_model->select_sum_where("collection_details","vat","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id'");
                    $sum_ewt= $this->super_model->select_sum_where("collection_details","ewt","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id = '$col->collection_id'");
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
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$row.":O".$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$row.":N".$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

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
                            $objPHPExcel->getActiveSheet()->getStyle('H'.$row_final)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('BCD2E8');
                        }
                        $objPHPExcel->getActiveSheet()->getStyle('I'.$row_final.":N".$row_final)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('BCD2E8');
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$row_final.":O".$row_final)->applyFromArray($styleArray);
                        $objPHPExcel->getActiveSheet()->getStyle('H'.$row_final.":O".$row_final)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('H'.$row_final.":N".$row_final)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    }

                $objPHPExcel->getActiveSheet()->getStyle('A6:O6')->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('A6:O6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('1c4966');
                $objPHPExcel->getActiveSheet()->getStyle('A6:O6')->getFont()->getColor()->setRGB ('FFFFFF');
                $objPHPExcel->getActiveSheet()->getStyle('A6:O6')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A6:O6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                } 
         }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
        unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Collection Reports.xlsx"');
        readfile($exportfilename);

    }


}