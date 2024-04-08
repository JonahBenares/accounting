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
class Masterfile extends CI_Controller {

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

    public function index()
    {
        $this->load->view('masterfile/login');
    }

    public function login_process(){
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $count=$this->super_model->login_user($username,$password);
        if($count>0){   
            $password1 =md5($this->input->post('password'));
            $fetch=$this->super_model->select_custom_where("users", "username = '$username' AND (password = '$password' OR password = '$password1')");
            foreach($fetch AS $d){
                $userid = $d->user_id;
                //$usertype = $d->usertype_id;
                $username = $d->username;
                $fullname = $d->fullname;
                $department = $d->department;
                $user_signature = $d->user_signature;
            }
            $newdata = array(
               'user_id'=> $userid,
               //'usertype'=> $usertype,
               'username'=> $username,
               'fullname'=> $fullname,
               'department'=> $department,
               'user_signature'=> $user_signature,
               'logged_in'=> TRUE
            );
            $this->session->set_userdata($newdata);
            redirect(base_url().'index.php/masterfile/dashboard/');
        }
        else{
            $this->session->set_flashdata('error_msg', 'Username And Password Do not Exist!');
            //$this->load->view('template/header_login');
            $this->load->view('masterfile/login');
            $this->load->view('template/footer');       
        }
    }

    public function user_logout(){
        $this->session->sess_destroy();
        $this->load->view('template/header');
        $this->load->view('masterfile/login');
        $this->load->view('template/footer');
        echo "<script>alert('You have successfully logged out.'); 
        window.location ='".base_url()."index.php/masterfile/index'; </script>";
    }

    public function home()
    {
        $this->load->view('template/header');
        // $this->load->view('template/navbar');
        $this->load->view('masterfile/home');
        $this->load->view('template/footer');
    }

    public function dashboard()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('masterfile/dashboard');
        $this->load->view('template/footer');
    }

    public function customer_list()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $rows = $this->super_model->count_rows("participant");
        $data['participant']=array();
        if($rows!=0){
            foreach($this->super_model->select_all_order_by("participant","participant_name","ASC") AS $part){
                $data['participant'][] = array(
                    'participant_id'=>$part->participant_id,
                    'participant_name'=>$part->participant_name,
                    'actual_billing_id'=>$part->actual_billing_id,
                    'billing_id'=>$part->billing_id,
                    'settlement_id'=>$part->settlement_id,
                    'category'=>$part->category,
                ); 
                
                foreach($this->super_model->select_custom_where("subparticipant","participant_id='$part->participant_id'") AS $s){
                    $subparticipant_name=$this->super_model->select_column_where("participant","participant_name","participant_id",$s->sub_participant);
                    $data['sub_participant'][]=array(
                        "participant_id"=>$s->participant_id,
                        "subparticipant_name"=>$subparticipant_name,
                        "billing_id"=>$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant),
                    );
                }
            }
        }
        
        $this->load->view('masterfile/customer_list',$data);
        $this->load->view('template/footer');
    }

    public function reserve_customer_list()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $rows = $this->super_model->count_rows("reserve_participant");
        $data['res_participant']=array();
        if($rows!=0){
            foreach($this->super_model->select_all_order_by("reserve_participant","res_participant_name","ASC") AS $part){
                $data['res_participant'][] = array(
                    'res_participant_id'=>$part->res_participant_id,
                    'res_participant_name'=>$part->res_participant_name,
                    'res_actual_billing_id'=>$part->res_actual_billing_id,
                    'res_billing_id'=>$part->res_billing_id,
                    'res_settlement_id'=>$part->res_settlement_id,
                    'res_category'=>$part->res_category,
                ); 
                
                foreach($this->super_model->select_custom_where("reserve_subparticipant","res_participant_id='$part->res_participant_id'") AS $s){
                    $subparticipant_name=$this->super_model->select_column_where("reserve_participant","res_participant_name","res_participant_id",$s->res_sub_participant);
                    $data['res_subparticipant'][]=array(
                        "res_participant_id"=>$s->res_participant_id,
                        "res_subparticipant_name"=>$subparticipant_name,
                        "res_billing_id"=>$this->super_model->select_column_where("reserve_participant","res_billing_id","res_participant_id",$s->res_sub_participant),
                    );
                }
            }
        }
        
        $this->load->view('masterfile/reserve_customer_list',$data);
        $this->load->view('template/footer');
    }

    public function add_sub_participant(){
        $id=$this->uri->segment(3);
        $data['id']=$id;
        //$data['sub_participant'] = $this->super_model->select_custom_where("participant", "participant_id != '$id'");
        //$data['sub_participant']=$this->super_model->select_all_order_by("participant","participant_name","participant_id = '$id'","ASC");
        //$data['sub_participant'] = $this->super_model->custom_query("SELECT DISTINCT * FROM participant p WHERE p.participant_id!='$id' GROUP BY participant_name ORDER BY p.participant_name ASC");
        $rows = $this->super_model->count_rows("subparticipant");
        $data['sub_participant'] = $this->super_model->custom_query("SELECT * FROM participant a WHERE NOT EXISTS (SELECT 1 FROM subparticipant b WHERE a.participant_id = b.sub_participant OR a.participant_id = b.participant_id) AND a.participant_id!='$id' ORDER BY a.participant_name ASC");
        if($rows!=0){
                foreach($this->super_model->select_custom_where("subparticipant", "participant_id = '$id'") AS $sub){
                    //$data['sub_participant'] = $this->super_model->custom_query(" SELECT * FROM participant a WHERE NOT EXISTS (SELECT 1 FROM subparticipant b WHERE a.participant_id = b.sub_participant AND b.participant_id='$sub->participant_id') AND a.participant_id!='$id' ORDER BY a.participant_name ASC");
                    $data['subparticipant'][] = array(
                        'subparticipant_id'=>$sub->subparticipant_id,
                        'participant_name'=>$this->super_model->select_column_where("participant","participant_name","participant_id", $sub->sub_participant),
                        'billing_id'=>$this->super_model->select_column_where("participant","billing_id","participant_id", $sub->sub_participant),
                        'participant_id'=>$id,
                    ); 
                }
            } else {
                $data['subparticipant']=array();
            }
        $this->load->view('template/header');
        $this->load->view('masterfile/add_sub_participant',$data);
        $this->load->view('template/footer');
    }

        public function add_reserve_sub_participant(){
        $id=$this->uri->segment(3);
        $data['id']=$id;
        $rows = $this->super_model->count_rows("reserve_subparticipant");
        $data['res_sub_participant'] = $this->super_model->custom_query("SELECT * FROM reserve_participant a WHERE NOT EXISTS (SELECT 1 FROM reserve_subparticipant b WHERE a.res_participant_id = b.res_sub_participant OR a.res_participant_id = b.res_participant_id) AND a.res_participant_id!='$id' ORDER BY a.res_participant_name ASC");
        if($rows!=0){
                foreach($this->super_model->select_custom_where("reserve_subparticipant", "res_participant_id = '$id'") AS $sub){
                    $data['subparticipant'][] = array(
                        'res_subparticipant_id'=>$sub->res_subparticipant_id,
                        'res_participant_name'=>$this->super_model->select_column_where("reserve_participant","res_participant_name","res_participant_id", $sub->res_sub_participant),
                        'res_billing_id'=>$this->super_model->select_column_where("reserve_participant","res_billing_id","res_participant_id", $sub->res_sub_participant),
                        'res_participant_id'=>$id,
                    ); 
                }
            } else {
                $data['subparticipant']=array();
            }
        $this->load->view('template/header');
        $this->load->view('masterfile/add_reserve_sub_participant',$data);
        $this->load->view('template/footer');
    }

    public function customer_add()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['status'] = $this->super_model->custom_query("SELECT DISTINCT status FROM participant WHERE status!=''");
        $data['region'] = $this->super_model->custom_query("SELECT DISTINCT region FROM participant WHERE region!=''");
        $this->load->view('masterfile/customer_add',$data);
        $this->load->view('template/footer');
    }

        public function reserve_customer_add()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['status'] = $this->super_model->custom_query("SELECT DISTINCT res_status FROM reserve_participant WHERE res_status!=''");
        $data['region'] = $this->super_model->custom_query("SELECT DISTINCT res_region FROM reserve_participant WHERE res_region!=''");
        $this->load->view('masterfile/reserve_customer_add',$data);
        $this->load->view('template/footer');
    }

    public function insert_sub(){
        $participant_id = $this->input->post('participant_id');
        $count_sub_participant = count($this->input->post('sub_participant'));
            for($z=0; $z<$count_sub_participant;$z++){
                $sub_participant='';
                if($this->input->post('sub_participant['.$z.']')!=''){
                    $sub_participant = $this->input->post('sub_participant['.$z.']');
                }

                $data_sub = array(
                    'participant_id'=>$participant_id,
                    'sub_participant'=>$sub_participant,
                );
                $this->super_model->insert_into("subparticipant", $data_sub);
            }

            //echo "<script>alert('Successfully Saved!'); window.opener.location ='".base_url()."Masterfile/customer_list';window.close(); </script>";
            echo "<script>alert('Successfully Added!'); window.opener.location.reload();window.location = '".base_url()."masterfile/add_sub_participant/$participant_id';</script>";
        }

    public function insert_res_sub(){
        $res_participant_id = $this->input->post('res_participant_id');
        $count_sub_participant = count($this->input->post('res_sub_participant'));
            for($z=0; $z<$count_sub_participant;$z++){
                $res_sub_participant='';
                if($this->input->post('res_sub_participant['.$z.']')!=''){
                    $res_sub_participant = $this->input->post('res_sub_participant['.$z.']');
                }

                $data_sub = array(
                    'res_participant_id'=>$res_participant_id,
                    'res_sub_participant'=>$res_sub_participant,
                );
                $this->super_model->insert_into("reserve_subparticipant", $data_sub);
            }

            //echo "<script>alert('Successfully Saved!'); window.opener.location ='".base_url()."Masterfile/customer_list';window.close(); </script>";
            echo "<script>alert('Successfully Added!'); window.opener.location.reload();window.location = '".base_url()."masterfile/add_reserve_sub_participant/$res_participant_id';</script>";
        }

    public function save_customer(){
        $bill_id= $this->input->post('billing_id');
        $check_unique = $this->super_model->count_custom_where("participant","billing_id = '$bill_id'");

        if($check_unique == 0){
            $data=array(
                "participant_name"=>$this->input->post('participant_name'),
                "billing_id"=>$this->input->post('billing_id'),
                "actual_billing_id"=>$this->input->post('actual_billing_id'),
                "region"=>$this->input->post('region'),
                "category"=>$this->input->post('category'),
                "membership"=>$this->input->post('membership'),
                "registered_address"=>$this->input->post('registered_address'),
                "settlement_id"=>$this->input->post('settlement_id'),
                "resource"=>$this->input->post('resource'),
                "tin"=>$this->input->post('tin'),
                "effective_date"=>$this->input->post('effective_date'),
                "participant_email"=>$this->input->post('participant_email'),
                "wht_agent"=>$this->input->post('wht_agent'),
                "vat_zerorated"=>$this->input->post('vat_zerorated'),
                "income_tax_holiday"=>$this->input->post('income_tax_holiday'),
                "contact_person"=>$this->input->post('contact_person'),
                "contact_position"=>$this->input->post('contact_position'),
                "office_address"=>$this->input->post('office_address'),
                "status"=>$this->input->post('status'),
                "mobile"=>$this->input->post('mobile'),
                "landline"=>$this->input->post('landline'),
                "contact_email"=>$this->input->post('contact_email'),
                "documents_submitted"=>$this->input->post('documents_submitted'),
                "zip_code"=>$this->input->post('zip_code'),
                "create_date"=>date("Y-m-d H:i:s"),
                "user_id"=>$_SESSION['user_id'],
            );
        

            $participant_id= $this->super_model->insert_return_id("participant", $data);

            echo $participant_id;
        } else {
            echo 'error';
        }
    }

        public function save_reserve_customer(){
        $res_bill_id= $this->input->post('res_billing_id');
        $check_unique = $this->super_model->count_custom_where("reserve_participant","res_billing_id = '$res_bill_id'");

        if($check_unique == 0 && $res_bill_id != ''){
            $data=array(
                "res_participant_name"=>$this->input->post('res_participant_name'),
                "res_billing_id"=>$this->input->post('res_billing_id'),
                "res_actual_billing_id"=>$this->input->post('res_actual_billing_id'),
                "res_region"=>$this->input->post('res_region'),
                "res_category"=>$this->input->post('res_category'),
                "res_membership"=>$this->input->post('res_membership'),
                "res_registered_address"=>$this->input->post('res_registered_address'),
                "res_settlement_id"=>$this->input->post('res_settlement_id'),
                "res_resource"=>$this->input->post('res_resource'),
                "res_tin"=>$this->input->post('res_tin'),
                "res_effective_date"=>$this->input->post('res_effective_date'),
                "res_participant_email"=>$this->input->post('res_participant_email'),
                "res_wht_agent"=>$this->input->post('res_wht_agent'),
                "res_vat_zerorated"=>$this->input->post('res_vat_zerorated'),
                "res_income_tax_holiday"=>$this->input->post('res_income_tax_holiday'),
                "res_contact_person"=>$this->input->post('res_contact_person'),
                "res_contact_position"=>$this->input->post('res_contact_position'),
                "res_office_address"=>$this->input->post('res_office_address'),
                "res_status"=>$this->input->post('res_status'),
                "res_mobile"=>$this->input->post('res_mobile'),
                "res_landline"=>$this->input->post('res_landline'),
                "res_contact_email"=>$this->input->post('res_contact_email'),
                "res_documents_submitted"=>$this->input->post('res_documents_submitted'),
                "res_zip_code"=>$this->input->post('res_zip_code'),
                "create_date"=>date("Y-m-d H:i:s"),
                "res_user_id"=>$_SESSION['user_id'],
            );
        

            $res_participant_id= $this->super_model->insert_return_id("reserve_participant", $data);

            echo $res_participant_id;
        } else {
            echo 'error';
        }
    }

    public function customer_update()
    {
        $data['id']=$this->uri->segment(3);
        $id=$this->uri->segment(3);
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['status'] = $this->super_model->custom_query("SELECT DISTINCT status FROM participant WHERE status!=''");
        $data['region'] = $this->super_model->custom_query("SELECT DISTINCT region FROM participant WHERE region!=''");
        $row=$this->super_model->count_rows("participant");

        if($row!=0){
            foreach($this->super_model->select_row_where('participant', 'participant_id', $id) AS $part){
                $data['details'][] = array(
                    'participant_name'=>$part->participant_name,
                    'billing_id'=>$part->billing_id,
                    'actual_billing_id'=>$part->actual_billing_id,
                    'registered_address'=>$part->registered_address,
                    'settlement_id'=>$part->settlement_id,
                    'region'=>$part->region,
                    'resource'=>$part->resource,
                    'category'=>$part->category,
                    'tin'=>$part->tin,
                    'effective_date'=>$part->effective_date,
                    'membership'=>$part->membership,
                    'participant_email'=>$part->participant_email,
                    'wht_agent'=>$part->wht_agent,
                    'vat_zerorated'=>$part->vat_zerorated,
                    'income_tax_holiday'=>$part->income_tax_holiday,
                    'documents_submitted'=>$part->documents_submitted,
                    'contact_person'=>$part->contact_person,
                    'contact_position'=>$part->contact_position,
                    'contact_email'=>$part->contact_email,
                    'office_address'=>$part->office_address,
                    'status'=>$part->status,
                    'mobile'=>$part->mobile,
                    'landline'=>$part->landline,
                    'zip_code'=>$part->zip_code,
                );
            }
        }else{
            $data['details'] = array();
        }
        $this->load->view('masterfile/customer_update',$data);
        $this->load->view('template/footer');
    }

    public function edit_customer(){
        
        $participant_id=$this->input->post('id');
        $bill_id= $this->input->post('billing_id');
        $check_unique = $this->super_model->count_custom_where("participant","billing_id = '$bill_id' AND participant_id != '$participant_id'");
        if($check_unique == 0){
            $data=array(
                "participant_name"=>$this->input->post('participant_name'),
                "billing_id"=>$this->input->post('billing_id'),
                "actual_billing_id"=>$this->input->post('actual_billing_id'),
                "region"=>$this->input->post('region'),
                "category"=>$this->input->post('category'),
                "membership"=>$this->input->post('membership'),
                "registered_address"=>$this->input->post('registered_address'),
                "settlement_id"=>$this->input->post('settlement_id'),
                "resource"=>$this->input->post('resource'),
                "tin"=>$this->input->post('tin'),
                "effective_date"=>$this->input->post('effective_date'),
                "participant_email"=>$this->input->post('participant_email'),
                "wht_agent"=>$this->input->post('wht_agent'),
                "vat_zerorated"=>$this->input->post('vat_zerorated'),
                "income_tax_holiday"=>$this->input->post('income_tax_holiday'),
                "contact_person"=>$this->input->post('contact_person'),
                "contact_position"=>$this->input->post('contact_position'),
                "office_address"=>$this->input->post('office_address'),
                "status"=>$this->input->post('status'),
                "mobile"=>$this->input->post('mobile'),
                "landline"=>$this->input->post('landline'),
                "contact_email"=>$this->input->post('contact_email'),
                "documents_submitted"=>$this->input->post('documents_submitted'),
                "zip_code"=>$this->input->post('zip_code'),
                "user_id"=>$_SESSION['user_id'],
            );
        

            if($this->super_model->update_where("participant", $data, "participant_id", $participant_id)){

            echo $participant_id;
            }
        } else {
            echo 'error';
        }
    }

        public function reserve_customer_update()
    {
        $data['id']=$this->uri->segment(3);
        $id=$this->uri->segment(3);
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['status'] = $this->super_model->custom_query("SELECT DISTINCT res_status FROM reserve_participant WHERE res_status!=''");
        $data['region'] = $this->super_model->custom_query("SELECT DISTINCT res_region FROM reserve_participant WHERE res_region!=''");
        $row=$this->super_model->count_rows("reserve_participant");

        if($row!=0){
            foreach($this->super_model->select_row_where('reserve_participant', 'res_participant_id', $id) AS $part){
                $data['details'][] = array(
                    'res_participant_name'=>$part->res_participant_name,
                    'res_billing_id'=>$part->res_billing_id,
                    'res_actual_billing_id'=>$part->res_actual_billing_id,
                    'res_registered_address'=>$part->res_registered_address,
                    'res_settlement_id'=>$part->res_settlement_id,
                    'res_region'=>$part->res_region,
                    'res_resource'=>$part->res_resource,
                    'res_category'=>$part->res_category,
                    'res_tin'=>$part->res_tin,
                    'res_effective_date'=>$part->res_effective_date,
                    'res_membership'=>$part->res_membership,
                    'res_participant_email'=>$part->res_participant_email,
                    'res_wht_agent'=>$part->res_wht_agent,
                    'res_vat_zerorated'=>$part->res_vat_zerorated,
                    'res_income_tax_holiday'=>$part->res_income_tax_holiday,
                    'res_documents_submitted'=>$part->res_documents_submitted,
                    'res_contact_person'=>$part->res_contact_person,
                    'res_contact_position'=>$part->res_contact_position,
                    'res_contact_email'=>$part->res_contact_email,
                    'res_office_address'=>$part->res_office_address,
                    'res_status'=>$part->res_status,
                    'res_mobile'=>$part->res_mobile,
                    'res_landline'=>$part->res_landline,
                    'res_zip_code'=>$part->res_zip_code,
                );
            }
        }else{
            $data['details'] = array();
        }
        $this->load->view('masterfile/reserve_customer_update',$data);
        $this->load->view('template/footer');
    }

        public function edit_reserve_customer(){
        
        $res_participant_id=$this->input->post('id');
        $res_bill_id= $this->input->post('res_billing_id');
        $check_unique = $this->super_model->count_custom_where("reserve_participant","res_billing_id = '$res_bill_id' AND res_participant_id != '$res_participant_id'");
        if($check_unique == 0){
            $data=array(
                "res_participant_name"=>$this->input->post('res_participant_name'),
                "res_billing_id"=>$this->input->post('res_billing_id'),
                "res_actual_billing_id"=>$this->input->post('res_actual_billing_id'),
                "res_region"=>$this->input->post('res_region'),
                "res_category"=>$this->input->post('res_category'),
                "res_membership"=>$this->input->post('res_membership'),
                "res_registered_address"=>$this->input->post('res_registered_address'),
                "res_settlement_id"=>$this->input->post('res_settlement_id'),
                "res_resource"=>$this->input->post('res_resource'),
                "res_tin"=>$this->input->post('res_tin'),
                "res_effective_date"=>$this->input->post('res_effective_date'),
                "res_participant_email"=>$this->input->post('res_participant_email'),
                "res_wht_agent"=>$this->input->post('res_wht_agent'),
                "res_vat_zerorated"=>$this->input->post('res_vat_zerorated'),
                "res_income_tax_holiday"=>$this->input->post('res_income_tax_holiday'),
                "res_contact_person"=>$this->input->post('res_contact_person'),
                "res_contact_position"=>$this->input->post('res_contact_position'),
                "res_office_address"=>$this->input->post('res_office_address'),
                "res_status"=>$this->input->post('res_status'),
                "res_mobile"=>$this->input->post('res_mobile'),
                "res_landline"=>$this->input->post('res_landline'),
                "res_contact_email"=>$this->input->post('res_contact_email'),
                "res_documents_submitted"=>$this->input->post('res_documents_submitted'),
                "res_zip_code"=>$this->input->post('res_zip_code'),
                "res_user_id"=>$_SESSION['user_id'],
            );
        

            if($this->super_model->update_where("reserve_participant", $data, "res_participant_id", $res_participant_id)){

            echo $res_participant_id;
            }
        } else {
            echo 'error';
        }
    }

        public function customer_view(){
        $data['id']=$this->uri->segment(3);
        $id=$this->uri->segment(3);
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $row=$this->super_model->count_rows("participant");

        if($row!=0){
            foreach($this->super_model->select_row_where('participant', 'participant_id', $id) AS $part){
                $data['details'][] = array(
                    'participant_name'=>$part->participant_name,
                    'billing_id'=>$part->billing_id,
                    'actual_billing_id'=>$part->actual_billing_id,
                    'registered_address'=>$part->registered_address,
                    'settlement_id'=>$part->settlement_id,
                    'region'=>$part->region,
                    'resource'=>$part->resource,
                    'category'=>$part->category,
                    'tin'=>$part->tin,
                    'effective_date'=>$part->effective_date,
                    'membership'=>$part->membership,
                    'participant_email'=>$part->participant_email,
                    'wht_agent'=>$part->wht_agent,
                    'vat_zerorated'=>$part->vat_zerorated,
                    'income_tax_holiday'=>$part->income_tax_holiday,
                    'documents_submitted'=>$part->documents_submitted,
                    'contact_person'=>$part->contact_person,
                    'contact_position'=>$part->contact_position,
                    'contact_email'=>$part->contact_email,
                    'office_address'=>$part->office_address,
                    'status'=>$part->status,
                    'mobile'=>$part->mobile,
                    'landline'=>$part->landline,
                    'zip_code'=>$part->zip_code,
                );
            }
        }else{
            $data['details'] = array();
        }
        $this->load->view('masterfile/customer_view', $data);
        $this->load->view('template/footer');;
    }

    public function reserve_customer_view(){
        $data['id']=$this->uri->segment(3);
        $id=$this->uri->segment(3);
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $row=$this->super_model->count_rows("reserve_participant");

        if($row!=0){
            foreach($this->super_model->select_row_where('reserve_participant', 'res_participant_id', $id) AS $part){
                $data['details'][] = array(
                    'res_participant_name'=>$part->res_participant_name,
                    'res_billing_id'=>$part->res_billing_id,
                    'res_actual_billing_id'=>$part->res_actual_billing_id,
                    'res_registered_address'=>$part->res_registered_address,
                    'res_settlement_id'=>$part->res_settlement_id,
                    'res_region'=>$part->res_region,
                    'res_resource'=>$part->res_resource,
                    'res_category'=>$part->res_category,
                    'res_tin'=>$part->res_tin,
                    'res_effective_date'=>$part->res_effective_date,
                    'res_membership'=>$part->res_membership,
                    'res_participant_email'=>$part->res_participant_email,
                    'res_wht_agent'=>$part->res_wht_agent,
                    'res_vat_zerorated'=>$part->res_vat_zerorated,
                    'res_income_tax_holiday'=>$part->res_income_tax_holiday,
                    'res_documents_submitted'=>$part->res_documents_submitted,
                    'res_contact_person'=>$part->res_contact_person,
                    'res_contact_position'=>$part->res_contact_position,
                    'res_contact_email'=>$part->res_contact_email,
                    'res_office_address'=>$part->res_office_address,
                    'res_status'=>$part->res_status,
                    'res_mobile'=>$part->res_mobile,
                    'res_landline'=>$part->res_landline,
                    'res_zip_code'=>$part->res_zip_code,
                );
            }
        }else{
            $data['details'] = array();
        }
        $this->load->view('masterfile/reserve_customer_view', $data);
        $this->load->view('template/footer');;
    }

    public function customer_delete(){
        $id=$this->uri->segment(3);
        if($this->super_model->delete_where('participant', 'participant_id', $id)){
            echo "<script>alert('Succesfully Deleted'); 
                window.location ='".base_url()."masterfile/customer_list'; </script>";
        }
    }

        public function upload_customer(){
         $dest= realpath(APPPATH . '../uploads/excel/');
         $error_ext=0;
        if(!empty($_FILES['excelfile_customer']['name'])){
             $exc= basename($_FILES['excelfile_customer']['name']);
             $exc=explode('.',$exc);
             $ext1=$exc[1];
            if($ext1=='php' || $ext1!='xlsx'){
                $error_ext++;
            } 
            else {
                 $filename1='Customers.'.$ext1;
                if(move_uploaded_file($_FILES["excelfile_customer"]['tmp_name'], $dest.'/'.$filename1)){
                    $this->readExcel_customer();
                }   
            }
        }
    }

    public function readExcel_customer(){
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $inputFileName =realpath(APPPATH.'../uploads/excel/Customers.xlsx');
        try {
            $inputFileType = io_factory::identify($inputFileName);
            $objReader = io_factory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file"'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 

        for($x=2;$x<=$highestRow;$x++){
            $head_rows = $this->super_model->count_rows("participant");
        if($head_rows==0){
            $participant_id=1;
        } else {
            $maxid=$this->super_model->get_max("participant", "participant_id");
            $participant_id=$maxid+1;
        }
            $settlement_id = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue());
            $billing_id = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue());
            $participant_name = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue());
            $registered_address = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getFormattedValue());
            $tin = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue());
            $category = trim($objPHPExcel->getActiveSheet()->getCell('F'.$x)->getFormattedValue());
            $wht_agent = trim($objPHPExcel->getActiveSheet()->getCell('G'.$x)->getFormattedValue());
            $vat_zerorated = trim($objPHPExcel->getActiveSheet()->getCell('H'.$x)->getFormattedValue());
            $income_tax_holiday = trim($objPHPExcel->getActiveSheet()->getCell('I'.$x)->getFormattedValue());
            $documents_submitted = trim($objPHPExcel->getActiveSheet()->getCell('J'.$x)->getFormattedValue());
            $contact_person = trim($objPHPExcel->getActiveSheet()->getCell('K'.$x)->getFormattedValue());
            $contact_position = trim($objPHPExcel->getActiveSheet()->getCell('L'.$x)->getFormattedValue());
            $office_address = trim($objPHPExcel->getActiveSheet()->getCell('M'.$x)->getFormattedValue());
            $landline = trim($objPHPExcel->getActiveSheet()->getCell('N'.$x)->getFormattedValue());
            $mobile = trim($objPHPExcel->getActiveSheet()->getCell('O'.$x)->getFormattedValue());
            $contact_email = trim($objPHPExcel->getActiveSheet()->getCell('P'.$x)->getFormattedValue());
            $region = trim($objPHPExcel->getActiveSheet()->getCell('Q'.$x)->getFormattedValue());
            $status = trim($objPHPExcel->getActiveSheet()->getCell('R'.$x)->getFormattedValue());
            $zip_code = trim($objPHPExcel->getActiveSheet()->getCell('S'.$x)->getFormattedValue());


            $data_customer = array(
                'participant_id'=>$participant_id,
                'settlement_id'=>$settlement_id,
                'billing_id'=>$billing_id,
                'participant_name'=>$participant_name,
                'registered_address'=>$registered_address,
                'tin'=>$tin,
                'category'=>$category,
                'wht_agent'=>$wht_agent,
                'vat_zerorated'=>$vat_zerorated,
                'income_tax_holiday'=>$income_tax_holiday,
                'documents_submitted'=>$documents_submitted,
                'contact_person'=>$contact_person,
                'contact_position'=>$contact_position,
                'office_address'=>$office_address,
                'landline'=>$landline,
                'mobile'=>$mobile,
                'contact_email'=>$contact_email,
                'region'=>$region,
                'status'=>$status,
                'zip_code'=>$zip_code,
                'date_imported'=>date('Y-m-d H:i:s'),
                'imported_by'=>$_SESSION['user_id'],
            );
           $this->super_model->insert_into("participant", $data_customer);
        }

        echo "<script>alert('Successfully Uploaded!'); window.location = 'customer_list';</script>";
    }


    public function upload_reserve_customer(){
         $dest= realpath(APPPATH . '../uploads/excel/');
         $error_ext=0;
        if(!empty($_FILES['excelfile_res_customer']['name'])){
             $exc= basename($_FILES['excelfile_res_customer']['name']);
             $exc=explode('.',$exc);
             $ext1=$exc[1];
            if($ext1=='php' || $ext1!='xlsx'){
                $error_ext++;
            } 
            else {
                 $filename1='Reserve_Customers.'.$ext1;
                if(move_uploaded_file($_FILES["excelfile_res_customer"]['tmp_name'], $dest.'/'.$filename1)){
                    $this->readExcel_reserve_customer();
                }   
            }
        }
    }

    public function readExcel_reserve_customer(){
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $inputFileName =realpath(APPPATH.'../uploads/excel/Reserve_Customers.xlsx');
        try {
            $inputFileType = io_factory::identify($inputFileName);
            $objReader = io_factory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file"'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 

        for($x=2;$x<=$highestRow;$x++){
            $head_rows = $this->super_model->count_rows("reserve_participant");
        if($head_rows==0){
            $res_participant_id=1;
        } else {
            $maxid=$this->super_model->get_max("reserve_participant", "res_participant_id");
            $res_participant_id=$maxid+1;
        }
            $res_settlement_id = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue());
            $res_billing_id = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue());
            $res_actual_billing_id = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue());
            $res_participant_name = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getFormattedValue());
            $res_registered_address = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue());
            $res_tin = trim($objPHPExcel->getActiveSheet()->getCell('F'.$x)->getFormattedValue());
            $res_category = trim($objPHPExcel->getActiveSheet()->getCell('G'.$x)->getFormattedValue());
            $res_wht_agent = trim($objPHPExcel->getActiveSheet()->getCell('H'.$x)->getFormattedValue());
            $res_vat_zerorated = trim($objPHPExcel->getActiveSheet()->getCell('I'.$x)->getFormattedValue());
            $res_income_tax_holiday = trim($objPHPExcel->getActiveSheet()->getCell('J'.$x)->getFormattedValue());
            $res_documents_submitted = trim($objPHPExcel->getActiveSheet()->getCell('K'.$x)->getFormattedValue());
            $res_contact_person = trim($objPHPExcel->getActiveSheet()->getCell('L'.$x)->getFormattedValue());
            $res_contact_position = trim($objPHPExcel->getActiveSheet()->getCell('M'.$x)->getFormattedValue());
            $res_office_address = trim($objPHPExcel->getActiveSheet()->getCell('N'.$x)->getFormattedValue());
            $res_landline = trim($objPHPExcel->getActiveSheet()->getCell('O'.$x)->getFormattedValue());
            $res_mobile = trim($objPHPExcel->getActiveSheet()->getCell('P'.$x)->getFormattedValue());
            $res_contact_email = trim($objPHPExcel->getActiveSheet()->getCell('Q'.$x)->getFormattedValue());
            $res_region = trim($objPHPExcel->getActiveSheet()->getCell('R'.$x)->getFormattedValue());
            $res_status = trim($objPHPExcel->getActiveSheet()->getCell('S'.$x)->getFormattedValue());
            $res_zip_code = trim($objPHPExcel->getActiveSheet()->getCell('T'.$x)->getFormattedValue());


            $data_res_customer = array(
                'res_participant_id'=>$res_participant_id,
                'res_settlement_id'=>$res_settlement_id,
                'res_billing_id'=>$res_billing_id,
                'res_actual_billing_id'=>$res_actual_billing_id,
                'res_participant_name'=>$res_participant_name,
                'res_registered_address'=>$res_registered_address,
                'res_tin'=>$res_tin,
                'res_category'=>$res_category,
                'res_wht_agent'=>$res_wht_agent,
                'res_vat_zerorated'=>$res_vat_zerorated,
                'res_income_tax_holiday'=>$res_income_tax_holiday,
                'res_documents_submitted'=>$res_documents_submitted,
                'res_contact_person'=>$res_contact_person,
                'res_contact_position'=>$res_contact_position,
                'res_office_address'=>$res_office_address,
                'res_landline'=>$res_landline,
                'res_mobile'=>$res_mobile,
                'res_contact_email'=>$res_contact_email,
                'res_region'=>$res_region,
                'res_status'=>$res_status,
                'res_zip_code'=>$res_zip_code,
                'res_date_imported'=>date('Y-m-d H:i:s'),
                'res_imported_by'=>$_SESSION['user_id'],
            );
           $this->super_model->insert_into("reserve_participant", $data_res_customer);
        }

        echo "<script>alert('Successfully Uploaded!'); window.location = 'reserve_customer_list';</script>";
    }




    public function supplier_list()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('masterfile/supplier_list');
        $this->load->view('template/footer');
    }

    public function user_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $rows = $this->super_model->count_rows("users");
        $data['users']=array();
        if($rows!=0){
            foreach($this->super_model->select_all_order_by("users","fullname","ASC") AS $user){
                $data['users'][] = array(
                    'user_id'=>$user->user_id,
                    'username'=>$user->username,
                    'fullname'=>$user->fullname,
                    'position'=>$user->position,
                    'department'=>$user->department,
                    'user_signature'=>$user->user_signature,
                ); 
            }
        }
        $this->load->view('masterfile/user_list',$data);
        $this->load->view('template/footer');
    }

    public function change_password(){
        $newpassword = md5($this->input->post('newpass'));
        $data = array(
            'password'=> $newpassword
        );
        $userid = $this->input->post('userid');

        $password = $this->super_model->select_column_where("users", "password", "user_id", $userid);

        $oldpassword = ($this->input->post('oldpass'));
        $oldpasswordmd5 = md5($this->input->post('oldpass'));

        if($oldpassword == $password){
            $this->super_model->update_where("users", $data, "user_id" , $userid );echo "<script>alert('Successfully Updated'); location.replace(document.referrer); </script>"; 
        }else if(md5($oldpasswordmd5) == md5($password)) {
            $this->super_model->update_where("users", $data,"user_id" , $userid  );echo "<script>alert('Successfully Updated'); location.replace(document.referrer); </script>";
        }
        else{
            echo "<script>alert('Incorrect old password!'); location.replace(document.referrer); </script>";
        }
    }

    public function insert_employee(){
        $username = $this->input->post('username');
        $fullname = $this->input->post('fullname');
        $position = $this->input->post('position');
        $department = $this->input->post('department');
        $error_ext=0;
        $dest= realpath(APPPATH . '../uploads/');
        if(!empty($_FILES['signature']['name'])){
             $e_signature= basename($_FILES['signature']['name']);
             $e_signature=explode('.',$e_signature);
             $ext1=$e_signature[1];
            
            if($ext1=='php' || ($ext1!='png' && $ext1!='PNG' && $ext1 != 'jpg' && $ext1 != 'JPG' && $ext1!='jpeg' && $ext1!='JPEG')){
                $error_ext++;
            } else {
                 $signature=$username.'1.'.$ext1;
                 move_uploaded_file($_FILES["signature"]['tmp_name'], $dest.'/'.$signature);
            }

        } else {
            $signature="";
        }
        $data_user = array(
            'username'=>$username,
            'fullname'=>$fullname,
            'position'=>$position,
            'department'=>$department,
            'password'=>'1234',
            'user_signature'=>$signature
        );
        if($this->super_model->insert_into("users", $data_user)){
            echo "<script>alert('Successfully Added!'); window.location = '".base_url()."masterfile/user_list';</script>";
        }
    }

    public function edit_user(){
        $user_id = $this->input->post('user_id');
        $username = $this->input->post('username');
        $fullname = $this->input->post('fullname');
        $position = $this->input->post('position');
        $department = $this->input->post('department');
        $error_ext=0;
        $dest= realpath(APPPATH . '../uploads/');
        if(!empty($_FILES['signature']['name'])){
             $e_signature= basename($_FILES['signature']['name']);
             $e_signature=explode('.',$e_signature);
             $ext1=$e_signature[1];
            
            if($ext1=='php' || ($ext1!='png' && $ext1!='PNG' && $ext1 != 'jpg' && $ext1 != 'JPG' && $ext1!='jpeg' && $ext1!='JPEG')){
                $error_ext++;
            } else {
                 $signature=$username.'1.'.$ext1;
                 move_uploaded_file($_FILES["signature"]['tmp_name'], $dest.'/'.$signature);
            }

        } else {
            $signature=$this->input->post('e_signature');
        }
        $data_user = array(
            'username'=>$username,
            'fullname'=>$fullname,
            'position'=>$position,
            'department'=>$department,
            'user_signature'=>$signature
        );
     
        if($this->super_model->update_where("users", $data_user, "user_id", $user_id)){
            echo "<script>alert('Successfully Updated!'); window.location = '".base_url()."masterfile/user_list';</script>";
        }
    }

    public function delete_subparticipant(){
        $participant_id=$this->uri->segment(3);
        $subparticipant_id=$this->uri->segment(4);
        if($this->super_model->delete_where('subparticipant', 'subparticipant_id ', $subparticipant_id)){
            echo "<script>alert('Succesfully Deleted'); 
                window.location ='".base_url()."masterfile/add_sub_participant/$participant_id'; </script>";
        }
    }

    public function delete_res_subparticipant(){
        $res_participant_id=$this->uri->segment(3);
        $res_subparticipant_id=$this->uri->segment(4);
        if($this->super_model->delete_where('reserve_subparticipant', 'res_subparticipant_id ', $res_subparticipant_id)){
            echo "<script>alert('Succesfully Deleted'); 
                window.location ='".base_url()."masterfile/add_reserve_sub_participant/$res_participant_id'; </script>";
        }
    }


    public function duplicate_billing_id_process(){
        
        // foreach($this->super_model->select_all("participant") AS $part){

        //     $data = array(
        //         'actual_billing_id'=>$part->billing_id
        //     );
        //     $this->super_model->update_where("participant", $data, "participant_id", $part->participant_id);
        // }

        foreach($this->super_model->select_all("purchase_transaction_details") AS $purch){

            $data = array(
                'actual_billing_id'=>$purch->billing_id
            );
            $this->super_model->update_where("purchase_transaction_details", $data, "purchase_detail_id", $purch->purchase_detail_id);
        }

        foreach($this->super_model->select_all("sales_adjustment_details") AS $sadjust){

            $data = array(
                'actual_billing_id'=>$sadjust->billing_id
            );
            $this->super_model->update_where("sales_adjustment_details", $data, "adjustment_detail_id", $sadjust->adjustment_detail_id);
        }

        foreach($this->super_model->select_all("sales_transaction_details") AS $sales){

            $data = array(
                'actual_billing_id'=>$sales->billing_id
            );
            $this->super_model->update_where("sales_transaction_details", $data, "sales_detail_id", $sales->sales_detail_id);
        }

        foreach($this->super_model->select_all("bs_details") AS $bs){

            $data = array(
                'actual_billing_id'=>$bs->billing_id
            );
            $this->super_model->update_where("bs_details", $data, "bs_details_id", $bs->bs_details_id);
        }

        foreach($this->super_model->select_all("bs_details_adjustment") AS $bsadj){

            $data = array(
                'actual_billing_id'=>$bsadj->billing_id
            );
            $this->super_model->update_where("bs_details_adjustment", $data, "bs_details_adjustment_id", $bsadj->bs_details_adjustment_id);
        }
    }
    
}