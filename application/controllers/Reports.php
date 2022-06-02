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


    public function ewt_summary()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('reports/ewt_summary');
        $this->load->view('template/footer');
    }

    public function cwht_summary()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('reports/cwht_summary');
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