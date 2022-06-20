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
        $data['ref_no'] = $ref_no;
        $data['participant'] = $participant;
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        //$participant_name=$this->super_model->select_column_where("participant","participant_name","partcipant_id",$partcipant_id);
        $data['reference_no']=$this->super_model->custom_query("SELECT DISTINCT reference_number FROM sales_transaction_head WHERE reference_number!=''");
        $data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
        $sql="";
/*        if($ref_no!='null' && $participant=='null'){
           $sql.= " AND sth.reference_number = '$ref_no' AND";
        }else if($ref_no!='null' && $participant!='null'){
            $sql.= " AND sth.reference_number = '$ref_no' AND";
        }else {
            $sql.= "";
        }

        if($participant!='null' && $ref_no=='null'){
            $sql.= " AND std.billing_id = '$participant' AND";
        }else if($participant!='null' && $ref_no!='null'){
            $sql.= " std.billing_id = '$participant' AND";
        }else {
            $sql.= "";
        }

        if($from!='null' && $to=='null'){
            $sql.= " AND sth.billing_from BETWEEN '$from' AND '$to' AND";
        }else if($from!='null' && $to!='null'){
            $sql.= " sth.billing_from BETWEEN '$from' AND '$to' AND";
        }else {
            $sql.= "";
        }

        if($to!='null' && $from=='null'){
            $sql.= " AND sth.billing_to BETWEEN '$from' AND '$to' AND";
        }else if($to!='null' && $from!='null'){
            $sql.= " sth.billing_to BETWEEN '$from' AND '$to' AND";
        }else {
            $sql.= "";
        }*/

        if($ref_no!='null' && $participant=='null' && $from=='null' && $to=='null'){
            $sql.= " AND sth.reference_number = '$ref_no' AND";
        }else if($ref_no=='null' && $participant!='null' && $from=='null' && $to=='null'){
            $sql.= " AND std.billing_id = '$participant' AND";
        }else if($ref_no!='null' && $participant!='null' && $from!='null' && $to!='null'){
            $sql.= " AND sth.reference_number = '$ref_no' AND std.billing_id = '$participant' AND '$from' AND '$to' BETWEEN  sth.billing_from AND sth.billing_to AND";
        }else if($ref_no=='null' && $participant=='null' && $from!='null' && $to!='null'){
            $sql.= " AND '$from' AND '$to' BETWEEN  sth.billing_from AND sth.billing_to AND";
        }else {
            $sql.= "";
        }

        $query=substr($sql,0,-3);
        $data['total_amount']=0;
        $data['total_collection']=0;
        $data['total_balance']=0;
        $data['head']=array();
        foreach($this->super_model->custom_query("SELECT cd.ewt,cd.amount,cd.zero_rated,cd.vat,ch.collection_date,std.billing_id,sth.billing_from,sth.billing_to,sth.transaction_date,cd.settlement_id FROM collection_details cd INNER JOIN collection_head ch ON cd.collection_id=ch.collection_id INNER JOIN sales_transaction_head sth ON cd.reference_no=sth.reference_number INNER JOIN sales_transaction_details std ON cd.settlement_id=std.short_name WHERE sth.saved='1' AND cd.ewt!='0' $query") AS $head){
        /*foreach($this->super_model->custom_query("SELECT * FROM collection_details cd INNER JOIN sales_transaction_head sth ON cd.sales_id=sth.sales_id INNER JOIN sales_transaction_details std ON cd.sales_details_id=std.sales_detail_id WHERE sth.saved='1' $query") AS $head){*/
            $tin=$this->super_model->select_column_where("participant","tin","billing_id",$head->billing_id);
            $registered_address=$this->super_model->select_column_where("participant","registered_address","billing_id",$head->billing_id);
            $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$head->billing_id);
            //$collected=$this->super_model->select_column_where("collection_details","total","sales_details_id",$head->sales_details_id);
            //$amount=$this->super_model->select_column_where("collection_details","amount","sales_details_id",$head->sales_details_id);
            //$vat=$this->super_model->select_column_where("collection_details","vat","sales_details_id",$head->sales_details_id);
            //$zero_rated=$this->super_model->select_column_where("collection_details","zero_rated","sales_details_id",$head->sales_details_id);
            //$ewt=$this->super_model->select_column_where("collection_details","ewt","sales_details_id",$head->sales_details_id);
            $totalamount=$this->super_model->select_sum_where("sales_transaction_details","total_amount","short_name='$head->settlement_id'");
            $totalcollected=$this->super_model->select_sum_where("collection_details","total","settlement_id='$head->settlement_id'");
            $total_amount[]=$totalamount;
            $total_collection[]=$totalcollected;
            $total_balance[]=$totalamount - $totalcollected;
            $data['total_amount']=array_sum($total_amount);
            $data['total_collection']=array_sum($total_collection);
            $data['total_balance']=array_sum($total_balance);
            $data['sales'][] = array( 
            'transaction_date'=>$head->transaction_date,
            'tin'=>$tin,
            'participant_name'=>$company_name,
            'address'=>$registered_address,
            'vatable_sales'=>$head->amount,
            'zero_rated_sales'=>$head->zero_rated,
            //'wht_agent'=>$head->wht_agent,
            'vat_on_sales'=>$head->vat,
            'billing_from'=>$head->billing_from,
            'billing_to'=>$head->billing_to,
            'ewt'=>$head->ewt,
        );
        }
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
        $data['ref_no'] = $ref_no;
        $data['participant'] = $participant;
        $this->load->view('template/header');
        $data['reference_no']=$this->super_model->custom_query("SELECT DISTINCT reference_number FROM purchase_transaction_head WHERE reference_number!=''");
        $data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
        $sql="";
        if($ref_no!='null' && $participant=='null' && $from=='null' && $to=='null'){
            $sql.= " AND pth.reference_number = '$ref_no' AND";
        }else if($ref_no=='null' && $participant!='null' && $from=='null' && $to=='null'){
            $sql.= " AND ptd.billing_id = '$participant' AND";
        }else if($ref_no!='null' && $participant!='null' && $from!='null' && $to!='null'){
            $sql.= " AND pth.reference_number = '$ref_no' AND ptd.billing_id = '$participant' AND '$from' AND '$to' BETWEEN  pth.billing_from AND pth.billing_to AND";
        }else if($ref_no=='null' && $participant=='null' && $from!='null' && $to!='null'){
            $sql.= " AND '$from' AND '$to' BETWEEN  pth.billing_from AND pth.billing_to AND";
        }else {
            $sql.= "";
        }

        $query=substr($sql,0,-3);
        $data['total_amount']=0;
        $data['total_paid']=0;
        $data['total_balance']=0;
        $data['head']=array();
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_details ptd INNER JOIN purchase_transaction_head pth ON ptd.purchase_id=pth.purchase_id WHERE saved='1' $query") AS $head){
            $tin=$this->super_model->select_column_where("participant","tin","billing_id",$head->billing_id);
            $registered_address=$this->super_model->select_column_where("participant","registered_address","billing_id",$head->billing_id);
            $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$head->billing_id);
            //$payment_amount=$this->super_model->select_column_where("payment","purchase_amount","purchase_detail_id",$head->purchase_detail_id);
            $mode=$this->super_model->select_column_where("payment_details","purchase_mode","purchase_details_id",$head->purchase_detail_id);
            $vat=$this->super_model->select_column_where("payment_details","vat","purchase_details_id",$head->purchase_detail_id);
            $ewt=$this->super_model->select_column_where("payment_details","ewt","purchase_details_id",$head->purchase_detail_id);
            $purchase_amount=$this->super_model->select_column_where("payment_details","purchase_amount","purchase_details_id",$head->purchase_detail_id);
            $totalamount=$this->super_model->select_sum_where("purchase_transaction_details","total_amount","purchase_id='$head->purchase_id'");
            $totalpaid=$this->super_model->select_sum_where("payment_details","total_amount","purchase_details_id='$head->purchase_detail_id'");
            $total_amount[]=$totalamount;
            $total_paid[]=$totalpaid;
            $total_balance[]=$totalamount - $totalpaid;
            $data['total_amount']=array_sum($total_amount);
            $data['total_paid']=array_sum($total_paid);
            $data['total_balance']=array_sum($total_balance);

            if($mode=='Vatable Purchase'){
                $vat_on_purchases=$vat;
                $zero_rated='0.00';
                $rated_ecozones='0.00';
            }else if($mode=='Zero-Rated Purchase'){
                $vat_on_purchases='0.00';
                $zero_rated=$vat;
                $rated_ecozones='0.00';
            }else if($mode=='Zero Rated Ecozones'){
                $vat_on_purchases='0.00';
                $zero_rated='0.00';
                $rated_ecozones=$vat;
            }

            $data['purchases'][] = array( 
            'transaction_date'=>$head->transaction_date,
            'tin'=>$tin,
            'participant_name'=>$company_name,
            'address'=>$registered_address,
            'vatable_purchases'=>$purchase_amount,
            'zero_rated_purchases'=>$zero_rated,
            'zero_rated_ecozones'=>$rated_ecozones,
            'wht_agent'=>$head->wht_agent,
            'vat_on_purchases'=>$vat_on_purchases,
            'billing_from'=>$head->billing_from,
            'billing_to'=>$head->billing_to,
            'ewt'=>$ewt,
            );
        }
        $this->load->view('reports/purchases_summary',$data);
        $this->load->view('template/footer');
    }


    public function ewt_summary(){
        $ref_no=$this->uri->segment(3);
        $participant=$this->uri->segment(4);
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['reference_no']=$this->super_model->custom_query("SELECT DISTINCT reference_number FROM purchase_transaction_head WHERE reference_number!=''");
        $data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
        $sql="";
        if($ref_no!='null' && $participant=='null'){
           $sql.= " AND pth.reference_number = '$ref_no' AND";
        }else if($ref_no!='null' && $participant!='null'){
            $sql.= " AND pth.reference_number = '$ref_no' AND";
        }else {
            $sql.= "";
        }

        if($participant!='null' && $ref_no=='null'){
            $sql.= " AND ptd.billing_id = '$participant' AND";
        }else if($participant!='null' && $ref_no!='null'){
            $sql.= " ptd.billing_id = '$participant' AND";
        }else {
            $sql.= "";
        }

        $query=substr($sql,0,-3);
        $data['total']=0;
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_details ptd INNER JOIN purchase_transaction_head pth ON ptd.purchase_id=pth.purchase_id WHERE saved='1' AND ewt!='0' $query") AS $s){
            $tin=$this->super_model->select_column_where("participant","tin","billing_id",$s->billing_id);
            $registered_address=$this->super_model->select_column_where("participant","registered_address","billing_id",$s->billing_id);
            $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$s->billing_id);
            $total_amount[]=$s->ewt;
            $data['total']=array_sum($total_amount);
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
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['reference_no']=$this->super_model->custom_query("SELECT DISTINCT reference_number FROM sales_transaction_head WHERE reference_number!=''");
        $data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
        $sql="";
        if($ref_no!='null' && $participant=='null'){
           $sql.= " AND sth.reference_number = '$ref_no' AND";
        }else if($ref_no!='null' && $participant!='null'){
            $sql.= " AND sth.reference_number = '$ref_no' AND std.billing_id = '$participant' AND";
        }else if($ref_no=='null' && $participant!='null'){
            $sql.= " AND std.billing_id = '$participant' AND";
        }else {
            $sql.= "";
        }
        
        $query=substr($sql,0,-3);
        //echo $query;
        $data['total']=0;
        foreach($this->super_model->custom_query("SELECT cd.ewt,ch.collection_date,std.billing_id,sth.billing_from,sth.billing_to FROM collection_details cd INNER JOIN collection_head ch ON cd.collection_id=ch.collection_id INNER JOIN sales_transaction_head sth ON cd.reference_no=sth.reference_number INNER JOIN sales_transaction_details std ON cd.settlement_id=std.short_name WHERE sth.saved='1' AND cd.ewt!='0' $query") AS $s){
            $tin=$this->super_model->select_column_where("participant","tin","billing_id",$s->billing_id);
            $registered_address=$this->super_model->select_column_where("participant","registered_address","billing_id",$s->billing_id);
            $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$s->billing_id);
            $total_amount[]=$s->ewt;
            $data['total']=array_sum($total_amount);
            $data['sales'][]=array(
                'transaction_date'=>$s->collection_date,
                'tin'=>$tin,
                'participant_name'=>$company_name,
                'address'=>$registered_address,
                'ewt'=>$s->ewt,
                'billing_from'=>$s->billing_from,
                'billing_to'=>$s->billing_to,
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
        if($ref_no!='null' && $date_from=='null' && $date_to=='null'){
            $sql.= " AND sth.reference_number = '$ref_no' AND";
        }else if($ref_no!='null' && $date_from!='null' && $date_to!='null'){
            $sql.= " AND sth.reference_number = '$ref_no' AND '$date_from' AND '$date_to' BETWEEN  sth.billing_from AND sth.billing_to AND";
        }else if($ref_no=='null' && $date_from!='null' && $date_to!='null'){
            $sql.= " AND '$date_from' AND '$date_to' BETWEEN  sth.billing_from AND sth.billing_to AND";
        }else {
            $sql.= "";
        }

        $query=substr($sql,0,-3);
        //echo $query;
        $data['bill']=array();
        $data['total_vatable_sales']=0.00;
        $data['total_amount']=0.00;
        $data['total_vatable_balance']=0.00;
        $data['total_zero_rated']=0.00;
        $data['total_c_zero_rated']=0.00;
        $data['total_zero_rated_balance']=0.00;
        $data['total_zero_ecozones']=0.00;
        $data['total_c_zero_ecozones']=0.00;
        $data['total_zero_ecozones_balance']=0.00;
        $data['total_vat']=0.00;
        $data['total_c_vat']=0.00;
        $data['total_vat_balance']=0.00;
        $data['total_ewt']=0.00;
        $data['total_c_ewt']=0.00;
        $data['total_ewt_balance']=0.00;
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id=std.sales_id WHERE saved='1' $query") AS $b){
            $reference_number=$this->super_model->select_column_where("collection_details","reference_no",'settlement_id',$b->short_name);
            if($b->reference_number==$reference_number){
                $amount=$this->super_model->select_sum_where("collection_details","amount","settlement_id='$b->short_name'");
                $zero_rated=$this->super_model->select_sum_where("collection_details","zero_rated","settlement_id='$b->short_name'");
                $zero_rated_ecozone=$this->super_model->select_sum_where("collection_details","zero_rated_ecozone","settlement_id='$b->short_name'");
                $vat=$this->super_model->select_sum_where("collection_details","vat","settlement_id='$b->short_name'");
                $ewt=$this->super_model->select_sum_where("collection_details","ewt","settlement_id='$b->short_name'");

                $vatablebalance=$b->vatable_sales - $amount;
                $zerobalance=$b->zero_rated_sales - $zero_rated;
                $zeroecobalance=$b->zero_rated_ecozones - $zero_rated_ecozone;
                $vatbalance=$b->vat_on_sales - $vat;
                $ewtbalance=$b->ewt - $ewt;

                $total_vatable_sales[]=$b->vatable_sales;
                $data['total_vatable_sales']=array_sum($total_vatable_sales);
                $total_amount[]=$amount;
                $data['total_amount']=array_sum($total_amount);
                $total_vatable_balance[]=$vatablebalance;
                $data['total_vatable_balance']=array_sum($total_vatable_balance);

                $total_zero_rated[]=$b->zero_rated_sales;
                $data['total_zero_rated']=array_sum($total_zero_rated);
                $total_c_zero_rated[]=$zero_rated;
                $data['total_c_zero_rated']=array_sum($total_c_zero_rated);
                $total_zero_rated_balance[]=$zerobalance;
                $data['total_zero_rated_balance']=array_sum($total_zero_rated_balance);

                $total_zero_ecozones[]=$b->zero_rated_ecozones;
                $data['total_zero_ecozones']=array_sum($total_zero_ecozones);
                $total_c_zero_ecozones[]=$zero_rated_ecozone;
                $data['total_c_zero_ecozones']=array_sum($total_c_zero_ecozones);
                $total_zero_ecozones_balance[]=$zeroecobalance;
                $data['total_zero_ecozones_balance']=array_sum($total_zero_ecozones_balance);

                $total_vat[]=$b->vat_on_sales;
                $data['total_vat']=array_sum($total_vat);
                $total_c_vat[]=$vat;
                $data['total_c_vat']=array_sum($total_c_vat);
                $total_vat_balance[]=$vatbalance;
                $data['total_vat_balance']=array_sum($total_vat_balance);

                $total_ewt[]=$b->ewt;
                $data['total_ewt']=array_sum($total_ewt);
                $total_c_ewt[]=$ewt;
                $data['total_c_ewt']=array_sum($total_c_ewt);
                $total_ewt_balance[]=$ewtbalance;
                $data['total_ewt_balance']=array_sum($total_ewt_balance);

                $data['bill'][]=array(
                    "date"=>$b->transaction_date,
                    "company_name"=>$b->company_name,
                    "billing_from"=>$b->billing_from,
                    "billing_to"=>$b->billing_to,
                    "vatable_sales"=>$b->vatable_sales,
                    "zero_rated_sales"=>$b->zero_rated_sales,
                    "zero_rated_ecozones"=>$b->zero_rated_ecozones,
                    "vat_on_sales"=>$b->vat_on_sales,
                    "ewt"=>$b->ewt,
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
        if($ref_no!='null' && $date_from=='null' && $date_to=='null'){
            $sql.= " AND pth.reference_number = '$ref_no' AND";
        }else if($ref_no!='null' && $date_from!='null' && $date_to!='null'){
            $sql.= " AND pth.reference_number = '$ref_no' AND '$date_from' AND '$date_to' BETWEEN  pth.billing_from AND pth.billing_to AND";
        }else if($ref_no=='null' && $date_from!='null' && $date_to!='null'){
            $sql.= " AND '$date_from' AND '$date_to' BETWEEN  pth.billing_from AND pth.billing_to AND";
        }else {
            $sql.= "";
        }

        $query=substr($sql,0,-3);
        $data['bill']=array();
        $data['total_vatable_purchases']=0.00;
        $data['total_purchase_amount']=0.00;
        $data['total_vatable_balance']=0.00;
        $data['total_zero_rated']=0.00;
        $data['total_p_zero_rated']=0.00;
        $data['total_zero_rated_balance']=0.00;
        $data['total_zero_ecozones']=0.00;
        $data['total_p_zero_ecozones']=0.00;
        $data['total_zero_ecozones_balance']=0.00;
        $data['total_vat']=0.00;
        $data['total_p_vat']=0.00;
        $data['total_vat_balance']=0.00;
        $data['total_ewt']=0.00;
        $data['total_p_ewt']=0.00;
        $data['total_ewt_balance']=0.00;
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pth INNER JOIN purchase_transaction_details ptd ON pth.purchase_id=ptd.purchase_id WHERE saved='1' $query") AS $b){
                foreach($this->super_model->custom_query("SELECT * FROM payment_head ph INNER JOIN payment_details pd ON ph.payment_id=pd.payment_id AND ph.purchase_id='$b->purchase_id' AND pd.purchase_details_id='$b->purchase_detail_id'") AS $c){
            if($c->purchase_mode=='Vatable Purchase'){
                $vat_on_purchases=$c->vat;
                $zero_rated='0.00';
                $rated_ecozones='0.00';
            }else if($c->purchase_mode=='Zero-Rated Purchase'){
                $vat_on_purchases='0.00';
                $zero_rated=$c->vat;
                $rated_ecozones='0.00';
            }else if($c->purchase_mode=='Zero-Rated Ecozones Purchase'){
                $vat_on_purchases='0.00';
                $zero_rated='0.00';
                $rated_ecozones=$c->vat;
            }
                $vatable_balance=$b->vatables_purchases - $c->purchase_amount;
                $zerorated_balance=$b->zero_rated_purchases - $zero_rated;
                $ratedecozones_balance=$b->zero_rated_ecozones - $rated_ecozones;
                $vat_balance=$b->vat_on_purchases - $vat_on_purchases;
                $ewt_balance=$b->ewt - $c->ewt;
                $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$b->billing_id);

                $vatable_balance=$b->vatables_purchases - $c->purchase_amount;
                $zerorated_balance=$b->zero_rated_purchases - $zero_rated;
                $ratedecozones_balance=$b->zero_rated_ecozones - $rated_ecozones;
                $vat_balance=$b->vat_on_purchases - $vat_on_purchases;
                $ewt_balance=$b->ewt - $c->ewt;
                $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$b->billing_id);

                $total_vatable_purchases[]=$b->vatables_purchases;
                $data['total_vatable_purchases']=array_sum($total_vatable_purchases);
                $total_purchase_amount[]=$c->purchase_amount;
                $data['total_purchase_amount']=array_sum($total_purchase_amount);
                $total_vatable_balance[]=$vatable_balance;
                $data['total_vatable_balance']=array_sum($total_vatable_balance);

                $total_zero_rated[]=$b->zero_rated_purchases;
                $data['total_zero_rated']=array_sum($total_zero_rated);
                $total_p_zero_rated[]=$zero_rated;
                $data['total_p_zero_rated']=array_sum($total_p_zero_rated);
                $total_zero_rated_balance[]=$zerorated_balance;
                $data['total_zero_rated_balance']=array_sum($total_zero_rated_balance);

                $total_zero_ecozones[]=$b->zero_rated_ecozones;
                $data['total_zero_ecozones']=array_sum($total_zero_ecozones);
                $total_p_zero_ecozones[]=$rated_ecozones;
                $data['total_p_zero_ecozones']=array_sum($total_p_zero_ecozones);
                $total_zero_ecozones_balance[]=$ratedecozones_balance;
                $data['total_zero_ecozones_balance']=array_sum($total_zero_ecozones_balance);

                $total_vat[]=$b->vat_on_purchases;
                $data['total_vat']=array_sum($total_vat);
                $total_p_vat[]=$vat_on_purchases;
                $data['total_p_vat']=array_sum($total_p_vat);
                $total_vat_balance[]=$vat_balance;
                $data['total_vat_balance']=array_sum($total_vat_balance);

                $total_ewt[]=$b->ewt;
                $data['total_ewt']=array_sum($total_ewt);
                $total_p_ewt[]=$c->ewt;
                $data['total_p_ewt']=array_sum($total_p_ewt);
                $total_ewt_balance[]=$ewt_balance;
                $data['total_ewt_balance']=array_sum($total_ewt_balance);


                $data['bill'][]=array(
                    "date"=>$b->transaction_date,
                    "company_name"=>$company_name,
                    "billing_from"=>$b->billing_from,
                    "billing_to"=>$b->billing_to,
                    "vatables_purchases"=>$b->vatables_purchases,
                    "purchase_amount"=>$c->purchase_amount,
                    "zero_rated_purchases"=>$b->zero_rated_purchases,
                    "zero_rated"=>$zero_rated,
                    "zero_rated_ecozones"=>$b->zero_rated_ecozones,
                    "rated_ecozones"=>$rated_ecozones,
                    "vat_on_purchases"=>$b->vat_on_purchases,
                    "vat"=>$vat_on_purchases,
                    "ewt"=>$b->ewt,
                    "p_ewt"=>$c->ewt,
                    "vatable_balance"=>$vatable_balance,
                    "zerorated_balance"=>$zerorated_balance,
                    "ratedecozones_balance"=>$ratedecozones_balance,
                    "vat_balance"=>$vat_balance,
                    "ewt_balance"=>$ewt_balance,
                );
            }
        }
        $this->load->view('reports/purchases_ledger',$data);
        $this->load->view('template/footer');
    }

    public function cs_ledger()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
        $participant=$this->uri->segment(3);
        $date_from=$this->uri->segment(4);
        $date_to=$this->uri->segment(5);
        $data['part'] = $participant;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $sql='';
        if($participant!='null' && $date_from=='null' && $date_to=='null'){
            $sql.= " AND std.billing_id = '$participant' AND";
        }else if($participant!='null' && $date_from!='null' && $date_to!='null'){
            $sql.= " AND std.billing_id = '$participant' AND '$date_from' AND '$date_to' BETWEEN sth.billing_from AND sth.billing_to AND";
        }else if($participant=='null' && $date_from!='null' && $date_to!='null'){
            $sql.= " AND '$date_from' AND '$date_to' BETWEEN  sth.billing_from AND sth.billing_to AND";
        }else {
            $sql.= "";
        }

        $query=substr($sql,0,-3);
        //echo $query;
        $data['csledger']=array();
        $data['total_vatable_sales']=0.00;
        $data['total_amount']=0.00;
        $data['total_vatable_balance']=0.00;
        $data['total_zero_rated']=0.00;
        $data['total_c_zero_rated']=0.00;
        $data['total_zero_rated_balance']=0.00;
        $data['total_zero_ecozones']=0.00;
        $data['total_c_zero_ecozones']=0.00;
        $data['total_zero_ecozones_balance']=0.00;
        $data['total_vat']=0.00;
        $data['total_c_vat']=0.00;
        $data['total_vat_balance']=0.00;
        $data['total_ewt']=0.00;
        $data['total_c_ewt']=0.00;
        $data['total_ewt_balance']=0.00;
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_head sth INNER JOIN sales_transaction_details std ON sth.sales_id=std.sales_id WHERE saved='1' $query") AS $b){
            $reference_number=$this->super_model->select_column_where("collection_details","reference_no",'settlement_id',$b->short_name);
            if($b->reference_number==$reference_number){
                $amount=$this->super_model->select_sum_where("collection_details","amount","settlement_id='$b->short_name'");
                $zero_rated=$this->super_model->select_sum_where("collection_details","zero_rated","settlement_id='$b->short_name'");
                $zero_rated_ecozone=$this->super_model->select_sum_where("collection_details","zero_rated_ecozone","settlement_id='$b->short_name'");
                $vat=$this->super_model->select_sum_where("collection_details","vat","settlement_id='$b->short_name'");
                $ewt=$this->super_model->select_sum_where("collection_details","ewt","settlement_id='$b->short_name'");

                $vatablebalance=$b->vatable_sales - $amount;
                $zerobalance=$b->zero_rated_sales - $zero_rated;
                $zeroecobalance=$b->zero_rated_ecozones - $zero_rated_ecozone;
                $vatbalance=$b->vat_on_sales - $vat;
                $ewtbalance=$b->ewt - $ewt;

                $total_vatable_sales[]=$b->vatable_sales;
                $data['total_vatable_sales']=array_sum($total_vatable_sales);
                $total_amount[]=$amount;
                $data['total_amount']=array_sum($total_amount);
                $total_vatable_balance[]=$vatablebalance;
                $data['total_vatable_balance']=array_sum($total_vatable_balance);

                $total_zero_rated[]=$b->zero_rated_sales;
                $data['total_zero_rated']=array_sum($total_zero_rated);
                $total_c_zero_rated[]=$zero_rated;
                $data['total_c_zero_rated']=array_sum($total_c_zero_rated);
                $total_zero_rated_balance[]=$zerobalance;
                $data['total_zero_rated_balance']=array_sum($total_zero_rated_balance);

                $total_zero_ecozones[]=$b->zero_rated_ecozones;
                $data['total_zero_ecozones']=array_sum($total_zero_ecozones);
                $total_c_zero_ecozones[]=$zero_rated_ecozone;
                $data['total_c_zero_ecozones']=array_sum($total_c_zero_ecozones);
                $total_zero_ecozones_balance[]=$zeroecobalance;
                $data['total_zero_ecozones_balance']=array_sum($total_zero_ecozones_balance);

                $total_vat[]=$b->vat_on_sales;
                $data['total_vat']=array_sum($total_vat);
                $total_c_vat[]=$vat;
                $data['total_c_vat']=array_sum($total_c_vat);
                $total_vat_balance[]=$vatbalance;
                $data['total_vat_balance']=array_sum($total_vat_balance);

                $total_ewt[]=$b->ewt;
                $data['total_ewt']=array_sum($total_ewt);
                $total_c_ewt[]=$ewt;
                $data['total_c_ewt']=array_sum($total_c_ewt);
                $total_ewt_balance[]=$ewtbalance;
                $data['total_ewt_balance']=array_sum($total_ewt_balance);

                $data['csledger'][]=array(
                    "date"=>$b->transaction_date,
                    "company_name"=>$b->company_name,
                    "billing_from"=>$b->billing_from,
                    "billing_to"=>$b->billing_to,
                    "vatable_sales"=>$b->vatable_sales,
                    "zero_rated_sales"=>$b->zero_rated_sales,
                    "zero_rated_ecozones"=>$b->zero_rated_ecozones,
                    "vat_on_sales"=>$b->vat_on_sales,
                    "ewt"=>$b->ewt,
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
        $this->load->view('reports/cs_ledger', $data);
        $this->load->view('template/footer');
    }

    public function ss_ledger()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['participant']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
        $participant=$this->uri->segment(3);
        $date_from=$this->uri->segment(4);
        $date_to=$this->uri->segment(5);
        $data['part'] = $participant;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $sql='';
        if($participant!='null' && $date_from=='null' && $date_to=='null'){
            $sql.= " AND ptd.billing_id = '$participant' AND";
        }else if($participant!='null' && $date_from!='null' && $date_to!='null'){
            $sql.= " AND ptd.billing_id = '$participant' AND '$date_from' AND '$date_to' BETWEEN  pth.billing_from AND pth.billing_to AND";
        }else if($participant=='null' && $date_from!='null' && $date_to!='null'){
            $sql.= " AND '$date_from' AND '$date_to' BETWEEN  pth.billing_from AND pth.billing_to AND";
        }else {
            $sql.= "";
        }

        $query=substr($sql,0,-3);
        $data['ssledger']=array();
        $data['total_vatable_purchases']=0.00;
        $data['total_purchase_amount']=0.00;
        $data['total_vatable_balance']=0.00;
        $data['total_zero_rated']=0.00;
        $data['total_p_zero_rated']=0.00;
        $data['total_zero_rated_balance']=0.00;
        $data['total_zero_ecozones']=0.00;
        $data['total_p_zero_ecozones']=0.00;
        $data['total_zero_ecozones_balance']=0.00;
        $data['total_vat']=0.00;
        $data['total_p_vat']=0.00;
        $data['total_vat_balance']=0.00;
        $data['total_ewt']=0.00;
        $data['total_p_ewt']=0.00;
        $data['total_ewt_balance']=0.00;
        foreach($this->super_model->custom_query("SELECT * FROM purchase_transaction_head pth INNER JOIN purchase_transaction_details ptd ON pth.purchase_id=ptd.purchase_id WHERE saved='1' $query") AS $b){
                foreach($this->super_model->custom_query("SELECT * FROM payment_head ph INNER JOIN payment_details pd ON ph.payment_id=pd.payment_id AND ph.purchase_id='$b->purchase_id' AND pd.purchase_details_id='$b->purchase_detail_id'") AS $c){
            if($c->purchase_mode=='Vatable Purchase'){
                $vat_on_purchases=$c->vat;
                $zero_rated='0.00';
                $rated_ecozones='0.00';
            }else if($c->purchase_mode=='Zero-Rated Purchase'){
                $vat_on_purchases='0.00';
                $zero_rated=$c->vat;
                $rated_ecozones='0.00';
            }else if($c->purchase_mode=='Zero-Rated Ecozones Purchase'){
                $vat_on_purchases='0.00';
                $zero_rated='0.00';
                $rated_ecozones=$c->vat;
            }
                $vatable_balance=$b->vatables_purchases - $c->purchase_amount;
                $zerorated_balance=$b->zero_rated_purchases - $zero_rated;
                $ratedecozones_balance=$b->zero_rated_ecozones - $rated_ecozones;
                $vat_balance=$b->vat_on_purchases - $vat_on_purchases;
                $ewt_balance=$b->ewt - $c->ewt;
                $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$b->billing_id);

                $total_vatable_purchases[]=$b->vatables_purchases;
                $data['total_vatable_purchases']=array_sum($total_vatable_purchases);
                $total_purchase_amount[]=$c->purchase_amount;
                $data['total_purchase_amount']=array_sum($total_purchase_amount);
                $total_vatable_balance[]=$vatable_balance;
                $data['total_vatable_balance']=array_sum($total_vatable_balance);

                $total_zero_rated[]=$b->zero_rated_purchases;
                $data['total_zero_rated']=array_sum($total_zero_rated);
                $total_p_zero_rated[]=$zero_rated;
                $data['total_p_zero_rated']=array_sum($total_p_zero_rated);
                $total_zero_rated_balance[]=$zerorated_balance;
                $data['total_zero_rated_balance']=array_sum($total_zero_rated_balance);

                $total_zero_ecozones[]=$b->zero_rated_ecozones;
                $data['total_zero_ecozones']=array_sum($total_zero_ecozones);
                $total_p_zero_ecozones[]=$rated_ecozones;
                $data['total_p_zero_ecozones']=array_sum($total_p_zero_ecozones);
                $total_zero_ecozones_balance[]=$ratedecozones_balance;
                $data['total_zero_ecozones_balance']=array_sum($total_zero_ecozones_balance);

                $total_vat[]=$b->vat_on_purchases;
                $data['total_vat']=array_sum($total_vat);
                $total_p_vat[]=$vat_on_purchases;
                $data['total_p_vat']=array_sum($total_p_vat);
                $total_vat_balance[]=$vat_balance;
                $data['total_vat_balance']=array_sum($total_vat_balance);

                $total_ewt[]=$b->ewt;
                $data['total_ewt']=array_sum($total_ewt);
                $total_p_ewt[]=$c->ewt;
                $data['total_p_ewt']=array_sum($total_p_ewt);
                $total_ewt_balance[]=$ewt_balance;
                $data['total_ewt_balance']=array_sum($total_ewt_balance);


                $data['ssledger'][]=array(
                    "date"=>$b->transaction_date,
                    "company_name"=>$company_name,
                    "billing_from"=>$b->billing_from,
                    "billing_to"=>$b->billing_to,
                    "vatables_purchases"=>$b->vatables_purchases,
                    "purchase_amount"=>$c->purchase_amount,
                    "zero_rated_purchases"=>$b->zero_rated_purchases,
                    "zero_rated"=>$zero_rated,
                    "zero_rated_ecozones"=>$b->zero_rated_ecozones,
                    "rated_ecozones"=>$rated_ecozones,
                    "vat_on_purchases"=>$b->vat_on_purchases,
                    "vat"=>$vat_on_purchases,
                    "ewt"=>$b->ewt,
                    "p_ewt"=>$c->ewt,
                    "vatable_balance"=>$vatable_balance,
                    "zerorated_balance"=>$zerorated_balance,
                    "ratedecozones_balance"=>$ratedecozones_balance,
                    "vat_balance"=>$vat_balance,
                    "ewt_balance"=>$ewt_balance,
                );
            }
        }
        $this->load->view('reports/ss_ledger',$data);
        $this->load->view('template/footer');
    }
    
}