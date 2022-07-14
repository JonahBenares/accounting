<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
            }
            $newdata = array(
               'user_id'=> $userid,
               //'usertype'=> $usertype,
               'username'=> $username,
               'fullname'=> $fullname,
               'department'=> $department,
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
                    'billing_id'=>$part->billing_id,
                    'settlement_id'=>$part->settlement_id,
                    'category'=>$part->category,
                ); 
                
                foreach($this->super_model->select_custom_where("subparticipant","participant_id='$part->participant_id'") AS $s){
                    $subparticipant_name=$this->super_model->select_column_where("participant","participant_name","participant_id",$s->sub_participant);
                    $data['sub_participant'][]=array(
                        "participant_id"=>$s->participant_id,
                        "subparticipant_name"=>$subparticipant_name,
                    );
                }
            }
        }
        
        $this->load->view('masterfile/customer_list',$data);
        $this->load->view('template/footer');
    }

    public function add_sub_participant(){
        $id=$this->uri->segment(3);
        $data['id']=$id;
        //$data['sub_participant'] = $this->super_model->select_custom_where("participant", "participant_id != '$id'");
        //$data['sub_participant']=$this->super_model->select_all_order_by("participant","participant_name","participant_id = '$id'","ASC");
       $data['sub_participant'] = $this->super_model->custom_query("SELECT DISTINCT * FROM participant p WHERE p.participant_id!='$id' GROUP BY participant_name ORDER BY p.participant_name ASC");
        $rows = $this->super_model->count_rows("subparticipant");
        if($rows!=0){
            foreach($this->super_model->select_custom_where("subparticipant", "participant_id = '$id'") AS $sub){
            $data['subparticipant'][] = array(
                'participant_name'=>$this->super_model->select_column_where("participant","participant_name","participant_id", $sub->sub_participant),
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

    public function customer_add()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['status'] = $this->super_model->custom_query("SELECT DISTINCT status FROM participant WHERE status!=''");
        $data['region'] = $this->super_model->custom_query("SELECT DISTINCT region FROM participant WHERE region!=''");
        $this->load->view('masterfile/customer_add',$data);
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

            echo "<script>alert('Successfully Saved!'); window.opener.location ='".base_url()."Masterfile/customer_list';window.close(); </script>";
        }

    public function save_customer(){
         $data=array(
            "participant_name"=>$this->input->post('participant_name'),
            "billing_id"=>$this->input->post('billing_id'),
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
         $data=array(
            "participant_name"=>$this->input->post('participant_name'),
            "billing_id"=>$this->input->post('billing_id'),
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
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $inputFileName =realpath(APPPATH.'../uploads/excel/Customers.xlsx');
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
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
            $settlement_id = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getValue());
            $billing_id = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getValue());
            $participant_name = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getValue());
            $registered_address = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getValue());
            $tin = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getValue());
            $category = trim($objPHPExcel->getActiveSheet()->getCell('F'.$x)->getValue());
            $wht_agent = trim($objPHPExcel->getActiveSheet()->getCell('G'.$x)->getValue());
            $vat_zerorated = trim($objPHPExcel->getActiveSheet()->getCell('H'.$x)->getValue());
            $income_tax_holiday = trim($objPHPExcel->getActiveSheet()->getCell('I'.$x)->getValue());
            $documents_submitted = trim($objPHPExcel->getActiveSheet()->getCell('J'.$x)->getValue());
            $contact_person = trim($objPHPExcel->getActiveSheet()->getCell('K'.$x)->getValue());
            $contact_position = trim($objPHPExcel->getActiveSheet()->getCell('L'.$x)->getValue());
            $office_address = trim($objPHPExcel->getActiveSheet()->getCell('M'.$x)->getValue());
            $landline = trim($objPHPExcel->getActiveSheet()->getCell('N'.$x)->getValue());
            $mobile = trim($objPHPExcel->getActiveSheet()->getCell('O'.$x)->getValue());
            $contact_email = trim($objPHPExcel->getActiveSheet()->getCell('P'.$x)->getValue());
            $region = trim($objPHPExcel->getActiveSheet()->getCell('Q'.$x)->getValue());
            $status = trim($objPHPExcel->getActiveSheet()->getCell('R'.$x)->getValue());
            $zip_code = trim($objPHPExcel->getActiveSheet()->getCell('S'.$x)->getValue());


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





    public function supplier_list()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('masterfile/supplier_list');
        $this->load->view('template/footer');
    }
    public function user_list()
    {
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
                ); 
            }
        }
        $this->load->view('masterfile/user_list',$data);
        $this->load->view('template/footer');
    }
    
}