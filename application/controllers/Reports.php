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
        $participant_id=$this->uri->segment(4);
        $from=$this->uri->segment(5);
        $to=$this->uri->segment(6);
        $data['ref_no'] = $ref_no;
        $data['participant_id'] = $participant_id;
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        //$participant_name=$this->super_model->select_column_where("participant","participant_name","partcipant_id",$partcipant_id);
        $data['participant']=$this->super_model->select_all_order_by("participant","participant_name","participant_name","ASC");
        $sql="";
        if($ref_no!=''){
           $sql.= " sh.reference_number = '$ref_no' AND";
        }else {
            $sql.= "NULL";
        }

        if($participant_id!='null'){
            $participant_name=$this->super_model->select_column_where("participant","participant_name","participant_id",$participant_id);
            $sql.= " sd.company_name = '$participant_name' AND";
        }else {
            $sql.= "NULL";
        }

        if($from!='null' && $to!='null'){
           $sql.= " sh.billing_from BETWEEN '$from' AND '$to' AND";
        }else {
            $sql.= "NULL";
        }

        if($from!='null' && $to!='null'){
           $sql.= " sh.billing_to BETWEEN '$from' AND '$to' AND";
        }else {
            $sql.= "NULL";
        }

        $query=substr($sql,0,-3);
        $data['head']=array();
        foreach($this->super_model->custom_query("SELECT DISTINCT * FROM sales_transaction_head sh INNER JOIN sales_transaction_details sd ON sh.sales_id = sd.sales_id INNER JOIN participant p ON p.billing_id = sd.billing_id WHERE sh.reference_number LIKE '%$ref_no%' OR sd.company_name = '$participant_name' AND ".$query." ORDER BY sh.transaction_date ASC") AS $head){
            $data['sales'][] = array( 
            'transaction_date'=>$head->transaction_date,
            'tin'=>$head->tin,
            'participant_name'=>$head->participant_name,
            'address'=>$head->registered_address,
            'vatable_sales'=>$head->vatable_sales,
            'zero_rated_sales'=>$head->zero_rated_sales,
            'wht_agent'=>$head->wht_agent,
            'vat_on_sales'=>$head->vat_on_sales,
            'billing_from'=>$head->billing_from,
            'billing_to'=>$head->billing_to,
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
        $participant_id=$this->uri->segment(4);
        $from=$this->uri->segment(5);
        $to=$this->uri->segment(6);
        $data['ref_no'] = $ref_no;
        $data['participant_id'] = $participant_id;
        $this->load->view('template/header');
        $data['participant']=$this->super_model->select_all_order_by("participant","participant_name","participant_name","ASC");
        $sql="";
        if($ref_no!=''){
           $sql.= " ph.reference_number = '$ref_no' AND";
        }else {
            $sql.= "NULL";
        }

        if($participant_id!='null'){
            $sql.= " p.participant_id = '$participant_id' AND";
        }else {
            $sql.= "NULL";
        }

        if($from!='null' && $to!='null'){
           $sql.= " sh.billing_from BETWEEN '$from' AND '$to' AND";
        }else {
            $sql.= "NULL";
        }

        if($from!='null' && $to!='null'){
           $sql.= " sh.billing_to BETWEEN '$from' AND '$to' AND";
        }else {
            $sql.= "NULL";
        }

        $query=substr($sql,0,-3);
        $data['head']=array();
        foreach($this->super_model->custom_query("SELECT DISTINCT * FROM purchase_transaction_head ph INNER JOIN purchase_transaction_details pd ON ph.purchase_id = pd.purchase_id INNER JOIN participant p ON p.billing_id = pd.billing_id WHERE ph.reference_number LIKE '%$ref_no%' AND ".$query." ORDER BY ph.transaction_date ASC") AS $head){
            $data['purchases'][] = array( 
            'transaction_date'=>$head->transaction_date,
            'tin'=>$head->tin,
            'participant_name'=>$head->participant_name,
            'address'=>$head->registered_address,
            'vatable_purchases'=>$head->vatables_purchases,
            'zero_rated_purchases'=>$head->zero_rated_purchases,
            'wht_agent'=>$head->wht_agent,
            'vat_on_purchases'=>$head->vat_on_purchases,
            'billing_from'=>$head->billing_from,
            'billing_to'=>$head->billing_to,
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
            $total_amount[]=$s->total_amount;
            $data['total']=array_sum($total_amount);
            $data['purchase'][]=array(
                'transaction_date'=>$s->transaction_date,
                'tin'=>$tin,
                'participant_name'=>$company_name,
                'address'=>$registered_address,
                'wht_agent'=>$s->wht_agent,
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

        $query=substr($sql,0,-3);
        $data['total']=0;
        foreach($this->super_model->custom_query("SELECT * FROM collection_details cd INNER JOIN sales_transaction_head sth ON cd.sales_id=sth.sales_id INNER JOIN sales_transaction_details std ON cd.sales_details_id=std.sales_detail_id WHERE sth.saved='1' AND cd.ewt!='0' $query") AS $s){
            $tin=$this->super_model->select_column_where("participant","tin","billing_id",$s->billing_id);
            $registered_address=$this->super_model->select_column_where("participant","registered_address","billing_id",$s->billing_id);
            $company_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$s->billing_id);
            $total_amount[]=$s->total;
            $data['total']=array_sum($total_amount);
            $data['sales'][]=array(
                'transaction_date'=>$s->date_collected,
                'tin'=>$tin,
                'participant_name'=>$company_name,
                'address'=>$registered_address,
                'wht_agent'=>$s->wht_agent,
                'billing_from'=>$s->billing_from,
                'billing_to'=>$s->billing_to,
            );

        }
        $this->load->view('reports/cwht_summary',$data);
        $this->load->view('template/footer');
    }

    public function sales_ledger()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('reports/sales_ledger');
        $this->load->view('template/footer');
    }

    public function purchases_ledger()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('reports/purchases_ledger');
        $this->load->view('template/footer');
    }

    public function cs_ledger()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('reports/cs_ledger');
        $this->load->view('template/footer');
    }

    public function ss_ledger()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('reports/ss_ledger');
        $this->load->view('template/footer');
    }
    
}