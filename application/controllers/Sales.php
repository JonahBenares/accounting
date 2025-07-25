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

    public function insert_printbs(){
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
        $rated_sales = $this->input->post('rated_sales');
        $zero_ecozones = $this->input->post('zero_ecozones');
        $total_vat = $this->input->post('vat');
        $ewt_arr = $this->input->post('ewt_arr');
        $overall_total = $this->input->post('overall_total'); 
        $count_head=count($participant_id);

        $actual_billing_id = $this->input->post('sub_participant');
        $vatable_sales = $this->input->post('vatable_sales');
        $zero_rated_sales = $this->input->post('zero_rated_sales');
        $vat = $this->input->post('vat_on_sales');
        $ewt = $this->input->post('ewt');
        $net_amount = $this->input->post('net_amount');
        $details_id = $this->input->post('details_id');
        $count_details=count($actual_billing_id);
       
        for($x=0;$x<$count_head;$x++){
            $count_participant = $this->super_model->count_custom_where("bs_head", "participant_id = '$participant_id[$x]' AND reference_number = '$reference_number[$x]' AND billing_from = '$billing_from[$x]' AND billing_to = '$billing_to[$x]'");
            if($count_participant == 0){ 
                  $data_head = array(
                        'sales_detail_id' => $detail_id[$x],
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
                 //$bs_head_id = $this->super_model->insert_return_id("bs_head", $data_head);
                 $this->super_model->insert_return_id("bs_head", $data_head);
                }
            }

        for($y=0;$y<$count_details;$y++){
            if($details_id[$y] != ''){
            $bs_head_id = $this->super_model->select_column_custom_where("bs_head","bs_head_id","sales_detail_id='$details_id[$y]'");
            $count_bs_details = $this->super_model->count_custom_where("bs_details", "bs_head_id = '$bs_head_id' AND actual_billing_id = '$actual_billing_id[$y]'");

            if($count_bs_details == 0){

                $settlement_id =  $settlement[$y];
                $tin_no =  $tin[$y];

                $unique_bill_id = $this->super_model->select_column_custom_where("participant", "billing_id", "actual_billing_id = '$actual_billing_id[$y]' AND settlement_id = '$settlement_id' AND tin = '$tin_no'");

                   $data_details = array(
                        'bs_head_id'=>$bs_head_id,
                        'billing_id' => $unique_bill_id,
                        'actual_billing_id' => $actual_billing_id[$y],
                        'vatable_sales' => $vatable_sales[$y],
                        'zero_rated_sales' => $zero_rated_sales[$y],
                        'vat' => $vat[$y],
                        'ewt' => $ewt[$y],
                        'net_amount' => $net_amount[$y],
                   );
                   $this->super_model->insert_into("bs_details", $data_details);
                        }
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

    public function print_multiple_adjustment(){
        $identifier=$this->input->post('multiple_print');
        $count=count($identifier);
        $invoice_no_disp='';
        for($x=0;$x<$count;$x++){
            $exp_identifier=explode(",",$identifier[$x]);
            $identifier_code=$exp_identifier[0];
            $invoice_no=$exp_identifier[1];
            $invoice_no_disp.=$exp_identifier[1]."-";
            //$reference_number=$exp_identifier[2];
            $data_head = array(
                'print_identifier'=>$identifier_code
            );
            $this->super_model->update_where("sales_adjustment_details",$data_head, "serial_no", $invoice_no);
        }
        echo $invoice_no_disp.",".$identifier_code.",".$count;
    }

        public function print_multiple_reserve(){
        $identifier=$this->input->post('multiple_print');
        $count=count($identifier);
        $res_sales_detail_disp='';
        for($x=0;$x<$count;$x++){
            $exp_identifier=explode(",",$identifier[$x]);
            $identifier_code=$exp_identifier[0];
            $res_sales_detail_id=$exp_identifier[1];
            $res_sales_detail_disp.=$exp_identifier[1]."-";
            $reference_number=$exp_identifier[2];
            $data_head = array(
                'res_print_identifier'=>$identifier_code
            );
            $this->super_model->update_where("reserve_sales_transaction_details",$data_head, "reserve_sales_detail_id", $res_sales_detail_id);
        }
        echo $res_sales_detail_disp.",".$identifier_code.",".$count;
    }

        public function insert_printreservebs(){
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
        $rated_sales = $this->input->post('rated_sales');
        $zero_ecozones = $this->input->post('zero_ecozones');
        $total_vat = $this->input->post('vat');
        $ewt_arr = $this->input->post('ewt_arr');
        $overall_total = $this->input->post('overall_total'); 
        $count_head=count($participant_id);

        $actual_billing_id = $this->input->post('sub_participant');
        $vatable_sales = $this->input->post('vatable_sales');
        $zero_rated_sales = $this->input->post('zero_rated_sales');
        $vat = $this->input->post('vat_on_sales');
        $ewt = $this->input->post('ewt');
        $net_amount = $this->input->post('net_amount');
        $details_id = $this->input->post('details_id');
        $count_details=count($actual_billing_id);
       
        for($x=0;$x<$count_head;$x++){
            $count_participant = $this->super_model->count_custom_where("reserve_bs_head", "res_participant_id = '$participant_id[$x]' AND res_reference_number = '$reference_number[$x]' AND res_billing_from = '$billing_from[$x]' AND res_billing_to = '$billing_to[$x]'");
            if($count_participant == 0){ 
                  $data_head = array(
                        'reserve_sales_detail_id' => $detail_id[$x],
                        'res_participant_id' => $participant_id[$x],
                        'res_participant_name' => $company_name[$x],
                        'res_address' => $address[$x],
                        'res_tin' => $tin[$x],
                        'res_stl_id' => $settlement[$x],
                        'res_invoice_no' => $serial_no[$x],
                        'res_statement_date' => $transaction_date[$x],
                        'res_billing_from' => $billing_from[$x],
                        'res_billing_to' => $billing_to[$x],
                        'res_due_date' => $due_date[$x],
                        'res_reference_number' => $reference_number[$x],
                        'res_prepared_by' => $prepared_by[$x],
                        'res_checked_by_emg' => $checked_by_emg[$x],
                        'res_checked_by_emg_pos' => $checked_by_emg_pos[$x],
                        'res_checked_by_accounting' => $checked_by_accounting[$x],
                        'res_checked_by_accounting_pos' => $checked_by_accounting_pos[$x],
                        'res_checked_by_finance' => $checked_by_finance[$x],
                        'res_checked_by_finance_pos' => $checked_by_finance_pos[$x],
                        'res_noted_by' => $noted_by[$x],
                        'res_noted_by_pos' => $noted_by_pos[$x],
                        'res_total_vatable_sales' => $vatable[$x],
                        'res_total_zero_rated' => $zero[$x],
                        'res_total_zero_sales' => $rated_sales[$x],
                        'res_total_zero_ecozones' => $zero_ecozones[$x],
                        'res_total_vat' => $total_vat[$x],
                        'res_total_ewt' => $ewt_arr[$x],
                        'res_total_net_amount' => $overall_total[$x],
                 );
                 $this->super_model->insert_return_id("reserve_bs_head", $data_head);
                }
            }

        for($y=0;$y<$count_details;$y++){
            if($details_id[$y] != ''){
            $bs_head_id = $this->super_model->select_column_custom_where("reserve_bs_head","reserve_bs_head_id","reserve_sales_detail_id='$details_id[$y]'");
            $count_bs_details = $this->super_model->count_custom_where("reserve_bs_details", "reserve_bs_head_id = '$bs_head_id' AND res_actual_billing_id = '$actual_billing_id[$y]'");

            if($count_bs_details == 0){

                $settlement_id =  $settlement[$y];
                $tin_no =  $tin[$y];

                $unique_bill_id = $this->super_model->select_column_custom_where("reserve_participant", "res_billing_id", "res_actual_billing_id = '$actual_billing_id[$y]' AND res_settlement_id = '$settlement_id' AND res_tin = '$tin_no'");

                   $data_details = array(
                        'reserve_bs_head_id'=>$bs_head_id,
                        'res_billing_id' => $unique_bill_id,
                        'res_actual_billing_id' => $actual_billing_id[$y],
                        'res_vatable_sales' => $vatable_sales[$y],
                        'res_zero_rated_sales' => $zero_rated_sales[$y],
                        'res_vat' => $vat[$y],
                        'res_ewt' => $ewt[$y],
                        'res_net_amount' => $net_amount[$y],
                   );
                   $this->super_model->insert_into("reserve_bs_details", $data_details);
                        }
                    }
                }
            }

    public function update_details(){
        $sales_detail_id=$this->input->post('sales_detail_id');
        $sales_id=$this->input->post('sales_id');
        $billing_id=$this->input->post('billing_id');
        $ewt_amount=$this->input->post('ewt_amount');
        $original_copy=$this->input->post('original_copy');
        $scanned_copy=$this->input->post('scanned_copy');
        $data_update=array(
            "ewt_amount"=>$ewt_amount,
            "original_copy"=>$original_copy,
            "scanned_copy"=>$scanned_copy,
        );
        if($this->super_model->update_custom_where("sales_transaction_details", $data_update, "sales_detail_id='$sales_detail_id' AND sales_id='$sales_id' AND billing_id='$billing_id'")){
            foreach($this->super_model->select_custom_where("sales_transaction_details","sales_detail_id='$sales_detail_id' AND sales_id='$sales_id' AND billing_id='$billing_id'") AS $latest_data){
                $return = array('ewt_amount'=>$latest_data->ewt_amount, 'original_copy'=>$latest_data->original_copy, 'scanned_copy'=>$latest_data->scanned_copy);
            }
            echo json_encode($return);
        }
    }

    public function update_reserve_details(){
        $res_sales_detail_id=$this->input->post('reserve_sales_detail_id');
        $res_sales_id=$this->input->post('reserve_sales_id');
        $billing_id=$this->input->post('billing_id');
        $ewt_amount=$this->input->post('ewt_amount');
        $original_copy=$this->input->post('original_copy');
        $scanned_copy=$this->input->post('scanned_copy');
        $data_update=array(
            "res_ewt_amount"=>$ewt_amount,
            "res_original_copy"=>$original_copy,
            "res_scanned_copy"=>$scanned_copy,
        );
        if($this->super_model->update_custom_where("reserve_sales_transaction_details", $data_update, "reserve_sales_detail_id='$res_sales_detail_id' AND reserve_sales_id='$res_sales_id' AND res_billing_id='$billing_id'")){
            foreach($this->super_model->select_custom_where("reserve_sales_transaction_details","reserve_sales_detail_id='$res_sales_detail_id' AND reserve_sales_id='$res_sales_id' AND res_billing_id='$billing_id'") AS $latest_data){
                $return = array('ewt_amount'=>$latest_data->res_ewt_amount, 'original_copy'=>$latest_data->res_original_copy, 'scanned_copy'=>$latest_data->res_scanned_copy);
            }
            echo json_encode($return);
        }
    }

    public function update_adjustment_details(){
        $sales_detail_id=$this->input->post('sales_detail_id');
        $sales_adjustment_id=$this->input->post('sales_adjustment_id');
        $billing_id=$this->input->post('billing_id');
        $ewt_amount=$this->input->post('ewt_amount');
        $original_copy=$this->input->post('original_copy');
        $scanned_copy=$this->input->post('scanned_copy');
        $data_update=array(
            "ewt_amount"=>$ewt_amount,
            "original_copy"=>$original_copy,
            "scanned_copy"=>$scanned_copy,
        );
        if($this->super_model->update_custom_where("sales_adjustment_details", $data_update, "adjustment_detail_id='$sales_detail_id' AND sales_adjustment_id='$sales_adjustment_id' AND billing_id='$billing_id'")){
            foreach($this->super_model->select_custom_where("sales_adjustment_details","adjustment_detail_id='$adjustment_detail_id' AND sales_adjustment_id='$sales_adjustment_id' AND billing_id='$billing_id'") AS $latest_data){
                $return = array('ewt_amount'=>$latest_data->ewt_amount, 'original_copy'=>$latest_data->original_copy, 'scanned_copy'=>$latest_data->scanned_copy);
            }
            echo json_encode($return);
        }
    }

    public function upload_sales()
    {
        $id=$this->uri->segment(3);
        $sub=$this->uri->segment(4);
        $data['sales_id'] = $id;
        $data['sub'] = $sub;
        $data['identifier_code']=$this->generateRandomString();
        $data['count_name'] = $this->super_model->count_custom_where("sales_transaction_details", "company_name = '' AND sales_id ='$id'"); 
        $data['count_empty_actual']=0;
        if(!empty($id)){
            foreach($this->super_model->select_row_where("sales_transaction_head", "sales_id",$id) AS $h){
                $data['transaction_date']=$h->transaction_date;
                $data['billing_from']=$h->billing_from;
                $data['billing_to']=$h->billing_to;
                $data['reference_number']=$h->reference_number;
                $data['due_date']=$h->due_date;
                $data['saved']=$h->saved;
                //$data['adjustment']=$h->adjustment;
                if($sub==0 ||  $sub=='null'){
                    foreach($this->super_model->select_row_where("sales_transaction_details","sales_id",$h->sales_id) AS $d){
                        $data['count_empty_actual']=$this->super_model->count_custom_where('sales_transaction_details',"sales_id='$h->sales_id' AND billing_id IS NULL");
                        //$unique_bill_id = $this->super_model->select_column_custom_where("participant", "billing_id", "actual_billing_id = '$d->billing_id' AND settlement_id = '$d->short_name'");
                        $data['details'][]=array(
                            'sales_detail_id'=>$d->sales_detail_id,
                            'sales_id'=>$d->sales_id,
                            'item_no'=>$d->item_no,
                            'short_name'=>$d->short_name,
                            'actual_billing_id'=>$d->actual_billing_id,
                            'billing_id'=>$d->billing_id,
                            // 'billing_id'=>$unique_bill_id,
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
                    foreach($this->super_model->select_row_where("sales_transaction_details","sales_id",$h->sales_id) AS $d){
                        $participant_id = $this->super_model->select_column_custom_where("participant","participant_id","billing_id='$d->billing_id'");
                        //$sub_participant = $this->super_model->select_column_custom_where("subparticipant","sub_participant","sub_participant='$participant_id'");
                        $sub_participant = $this->super_model->count_custom_where("subparticipant","sub_participant='$participant_id'");
                        //if($participant_id != $sub_participant){

                        // $unique_bill_id = $this->super_model->select_column_custom_where("participant", "billing_id", "actual_billing_id = '$d->billing_id' AND settlement_id = '$d->short_name'");

                        if($sub_participant==0){
                            $data['details'][]=array(
                                'sales_detail_id'=>$d->sales_detail_id,
                                'sales_id'=>$d->sales_id,
                                'item_no'=>$d->item_no,
                                'short_name'=>$d->short_name,
                                'actual_billing_id'=>$d->actual_billing_id,
                                'billing_id'=>$d->billing_id,
                                // 'billing_id'=>$unique_bill_id,
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
        $this->load->view('sales/upload_sales',$data);
        $this->load->view('template/footer');
    }

        public function upload_reserve_sales()
    {
        $id=$this->uri->segment(3);
        $res_sub=$this->uri->segment(4);
        $data['res_sales_id'] = $id;
        $data['res_sub'] = $res_sub;
        $data['identifier_code']=$this->generateRandomString();
        $data['count_name'] = $this->super_model->count_custom_where("reserve_sales_transaction_details", "res_company_name = '' AND reserve_sales_id ='$id'"); 
        if(!empty($id)){
            foreach($this->super_model->select_row_where("reserve_sales_transaction_head", "reserve_sales_id",$id) AS $h){
                $data['res_transaction_date']=$h->res_transaction_date;
                $data['res_billing_from']=$h->res_billing_from;
                $data['res_billing_to']=$h->res_billing_to;
                $data['res_reference_number']=$h->res_reference_number;
                $data['res_due_date']=$h->res_due_date;
                $data['res_saved']=$h->res_saved;
            if($res_sub==0 ||  $res_sub=='null'){
                foreach($this->super_model->select_row_where("reserve_sales_transaction_details","reserve_sales_id",$h->reserve_sales_id) AS $d){
                    $data['details'][]=array(
                        'reserve_sales_detail_id'=>$d->reserve_sales_detail_id,
                        'reserve_sales_id'=>$d->reserve_sales_id,
                        'res_item_no'=>$d->res_item_no,
                        'res_short_name'=>$d->res_short_name,
                        'res_actual_billing_id'=>$d->res_actual_billing_id,
                        'res_billing_id'=>$d->res_billing_id,
                        'res_company_name'=>$d->res_company_name,
                        'res_facility_type'=>$d->res_facility_type,
                        'res_wht_agent'=>$d->res_wht_agent,
                        'res_ith_tag'=>$d->res_ith_tag,
                        'res_non_vatable'=>$d->res_non_vatable,
                        'res_zero_rated'=>$d->res_zero_rated,
                        'res_vatable_sales'=>$d->res_vatable_sales,
                        'res_vat_on_sales'=>$d->res_vat_on_sales,
                        'res_zero_rated_sales'=>$d->res_zero_rated_sales,
                        'res_zero_rated_ecozones'=>$d->res_zero_rated_ecozones,
                        'res_ewt'=>$d->res_ewt,
                        'res_serial_no'=>$d->res_serial_no,
                        'res_total_amount'=>$d->res_total_amount,
                        'res_print_counter'=>$d->res_print_counter
                    );
                }
            }else if($res_sub==1){
                foreach($this->super_model->select_row_where("reserve_sales_transaction_details","reserve_sales_id",$h->reserve_sales_id) AS $d){
                    $res_participant_id = $this->super_model->select_column_custom_where("reserve_participant","res_participant_id","res_billing_id='$d->res_billing_id'");
                    // $ressub_part_id = $this->super_model->select_column_custom_where("reserve_subparticipant","res_sub_participant","res_sub_participant='$res_participant_id'");
                    // $sub_billing_id = $this->super_model->select_column_custom_where("reserve_participant","res_billing_id","res_participant_id='$ressub_part_id'");
                    $ressub_participant = $this->super_model->count_custom_where("reserve_subparticipant","res_sub_participant='$res_participant_id'");


                        if($ressub_participant==0){
                        // if($d->res_billing_id != $sub_billing_id){
                            $data['details'][]=array(
                                'reserve_sales_detail_id'=>$d->reserve_sales_detail_id,
                                'reserve_sales_id'=>$d->reserve_sales_id,
                                'res_item_no'=>$d->res_item_no,
                                'res_short_name'=>$d->res_short_name,
                                'res_actual_billing_id'=>$d->res_actual_billing_id,
                                'res_billing_id'=>$d->res_billing_id,
                                'res_company_name'=>$d->res_company_name,
                                'res_facility_type'=>$d->res_facility_type,
                                'res_wht_agent'=>$d->res_wht_agent,
                                'res_ith_tag'=>$d->res_ith_tag,
                                'res_non_vatable'=>$d->res_non_vatable,
                                'res_zero_rated'=>$d->res_zero_rated,
                                'res_vatable_sales'=>$d->res_vatable_sales,
                                'res_vat_on_sales'=>$d->res_vat_on_sales,
                                'res_zero_rated_sales'=>$d->res_zero_rated_sales,
                                'res_zero_rated_ecozones'=>$d->res_zero_rated_ecozones,
                                'res_ewt'=>$d->res_ewt,
                                'res_serial_no'=>$d->res_serial_no,
                                'res_total_amount'=>$d->res_total_amount,
                                'res_print_counter'=>$d->res_print_counter
                            );
                        }
                    }
                }
            }

        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/upload_reserve_sales',$data);
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
        $data['sales_detail_id']=$sales_details_id;
        $data['print_identifier']=$print_identifier;
        $data['address'][]='';
        $data['tin'][]='';
        $data['company_name'][]='';
        $data['settlement'][]='';
        $data['billing_from'][]='';
        $data['billing_to'][]='';
        $data['due_date'][]='';
        $data['reference_number'][]='';
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


public function print_BS_new(){
        /*$sales_detail_id = $this->uri->segment(3);
        $data['sales_detail_id']=$sales_detail_id;*/
        $sales_details_id = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $data['count']=$count;
        $sales_det_exp=explode("-",$sales_details_id);
        $data['sales_detail_id']=$sales_details_id;
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
        $data['total_vat'][]='';
        $data['total_ewt'][]='';
        $data['total_net_amount'][]='';
        $data['bs_head_id'][]='';
        $data['total_sub_h'][]='';
        $data['total_sub'][]='';
        
        for($x=0;$x<$count;$x++){
            $array_salesdet_id[]=$sales_det_exp[$x];
            $bs_id[]=$this->super_model->custom_query_single('sales_detail_id',"SELECT * FROM sales_transaction_details std INNER JOIN bs_head bh ON bh.sales_detail_id=std.sales_detail_id WHERE bh.sales_detail_id='$sales_det_exp[$x]'");
            //$sales_det_id=implode(',',$array_salesdet_id);
            if(in_array($sales_det_exp[$x],$bs_id)){
            // if(array_key_exists($sales_det_exp[$x],$bs_id)){
                foreach($this->super_model->select_custom_where("bs_head","sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                    $data['detail_id'][$x]=$p->sales_detail_id;
                    $detail_id=$p->sales_detail_id;
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
                    $billing_from=$p->billing_from;
                    $data['billing_to'][$x]=$p->billing_to;
                    $billing_to=$p->billing_to;
                    $data['due_date'][$x]=$p->due_date;
                    $due_date=$p->due_date;
                    $data['reference_number'][$x]=$p->reference_number;
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
                    $data['total_vat'][$x]=$p->total_vat;
                    $data['total_ewt'][$x]=$p->total_ewt;
                    $data['total_net_amount'][$x]=$p->total_net_amount;
                    $data['bs_head_id'][$x]=$p->bs_head_id;
                    $reference_number=$p->reference_number;
                    $participant_id = $p->participant_id;
                    $count_sub_hist=$this->super_model->count_custom_where("bs_details","bs_head_id='$p->bs_head_id'");
                    $data['count_sub_hist'][$x]=$this->super_model->count_custom_where("bs_details","bs_head_id='$p->bs_head_id'");
                    $actual_billing_id = $this->super_model->select_column_where("bs_details","actual_billing_id","bs_head_id",$p->bs_head_id);
                    $vatable_sales = $this->super_model->select_column_where("bs_details","vatable_sales","bs_head_id",$p->bs_head_id);
                    $zero_rated_sales = $this->super_model->select_column_where("bs_details","zero_rated_sales","bs_head_id",$p->bs_head_id);
                    $vat = $this->super_model->select_column_where("bs_details","vat","bs_head_id",$p->bs_head_id);
                    $ewt = $this->super_model->select_column_where("bs_details","ewt","bs_head_id",$p->bs_head_id);
                    $overall_total = $this->super_model->select_column_where("bs_details","net_amount","bs_head_id",$p->bs_head_id);
                    $total_amount = $vatable_sales + $zero_rated_sales;

                    $data['sub_participant'][$x]=$actual_billing_id;
                    $data['vatable_sales'][$x]=$vatable_sales;
                    $data['vat_on_sales'][$x]=$vat;
                    $data['zero_rated'][$x]=$zero_rated_sales;
                    $data['rated_sales'][$x]=$p->total_zero_sales;
                    $data['zero_ecozones_sales'][$x]=$p->total_zero_ecozones;
                    $data['total_amount'][$x]=$total_amount;
                    $data['ewt'][$x]=$ewt;
                    $data['overall_total'][$x]=$overall_total;
                    $data['participant_id'][$x]=$participant_id;

                    $data['sub'][]=array(
                        "participant_id"=>$participant_id,
                        "bs_head_id"=>$p->bs_head_id,
                        "sub_participant"=>$actual_billing_id,
                        "vatable_sales"=>$vatable_sales,
                        "zero_rated_sales"=>$zero_rated_sales,
                        "zero_ecozones_sales"=>$p->total_zero_ecozones,
                        "total_amount"=>$total_amount,
                        "vat_on_sales"=>$vat,
                        "ewt"=>$ewt,
                        "overall_total"=>$overall_total,
                    );
                     
                    
                    $h=0;
                    $u=1;

                    
                    foreach($this->super_model->select_custom_where("bs_details","bs_head_id='$p->bs_head_id'") AS $s){
                    if($u <= 15){
                            $data['sub_participant_sub']=$s->actual_billing_id;
                            $data['vatable_sales_sub']=$s->vatable_sales;
                            $data['vat_on_sales_sub']=$s->vat;
                            $data['zero_rated_sales_sub']=$s->zero_rated_sales;
                            $data['ewt_s']=$s->ewt;
                            $data['overall_total_sub']=$overall_total;
                            $data['participant_id_sub']=$participant_id;
                            $data['total_sub_h']= $this->super_model->count_custom_where("bs_details","bs_head_id='$p->bs_head_id'");
                            $data['total_sub']='';
                                $data['sub_part'][]=array(
                                    "participant_id"=>$participant_id,
                                    "bs_head_id"=>$p->bs_head_id,
                                    "sub_participant"=>$s->actual_billing_id,
                                    "vatable_sales"=>$s->vatable_sales,
                                    "zero_rated_sales"=>$s->zero_rated_sales,
                                    "vat_on_sales"=>$s->vat,
                                    "ewt"=>$s->ewt,
                                    "overall_total"=>$s->net_amount,
                                );
                               
                            $h++;
                        }
                       $u++; 
                    }

                        $data['sub_participant'][$x]=$actual_billing_id;
                        $data['vatable_sales'][$x]=$vatable_sales;
                        $data['vat_on_sales'][$x]=$vat;
                        $data['zero_rated_sales'][$x]=$zero_rated_sales;
                        //$data['total_amount'][$x]=$total_amount;
                        $data['ewt'][$x]=$ewt;
                        $data['overall_total'][$x]=$overall_total;
                        $data['participant_id'][$x]=$participant_id;
                        $data['sub_second'][]=array(
                            "participant_id"=>$participant_id,
                            "bs_head_id"=>$p->bs_head_id,
                            "sub_participant"=>$actual_billing_id,
                            "vatable_sales"=>$vatable_sales,
                            "zero_rated_sales"=>$zero_rated_sales,
                            "total_amount"=>$total_amount,
                            "vat_on_sales"=>$vat,
                            "ewt"=>$ewt,
                            "overall_total"=>$overall_total,
                        );
                    
                        $z=0;
                        $t=1;
                        foreach($this->super_model->select_custom_where("bs_details","bs_head_id='$p->bs_head_id'") AS $s){
                    if($t>=16){
                                $data['sub_participant_sub'][$z]=$s->actual_billing_id;
                                $data['vatable_sales_sub'][$z]=$s->vatable_sales;
                                $data['vat_on_sales_sub'][$z]=$s->vat;
                                $data['zero_rated_sales_sub'][$z]=$s->zero_rated_sales;
                                $data['ewt_s'][$z]=$s->ewt;
                                $data['participant_id_sub'][$z]=$participant_id;
                                $data['sub_part_second'][]=array(
                                    "counter"=>'',
                                    "counter_h"=>$t,
                                    "participant_id"=>$participant_id,
                                    "bs_head_id"=>$p->bs_head_id,
                                    "sub_participant"=>$s->actual_billing_id,
                                    "vatable_sales"=>$s->vatable_sales,
                                    "zero_rated_sales"=>$s->zero_rated_sales,
                                    //"total_amount"=>$total_amount,
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
                foreach($this->super_model->select_custom_where("sales_transaction_details","print_identifier='$print_identifier' AND sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                    $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $p->sales_id);
                    if(!empty($p->company_name)){
                        $comp_name=$p->company_name;
                    }else{
                        $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $p->billing_id);
                    }
                    $data['detail_id'][$x]=$p->sales_detail_id;
                    $detail_id=$p->sales_detail_id;
                    $data['address'][$x]=$this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                    $address=$this->super_model->select_column_where("participant","office_address","billing_id",$p->billing_id);
                    $data['tin'][$x]=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                    $tin=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                    $data['company_name'][$x]=$comp_name;
                    $company_name=$comp_name;
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
                    $data['bs_head_id'][$x]='';
                    $data['count_sub_hist'][$x]='';

                    $data['sub_participant'][$x]=$p->actual_billing_id;
                    $data['vatable_sales'][$x]=$p->vatable_sales;
                    $data['vat_on_sales'][$x]=$p->vat_on_sales;
                    $data['zero_rated'][$x]=$zero_rated;
                    $data['rated_sales'][$x]=$p->zero_rated_sales;
                    $data['zero_ecozones_sales'][$x]=$p->zero_rated_ecozones;
                    $data['total_amount'][$x]=$total_amount;
                    $data['ewt'][$x]=$p->ewt;
                    $data['overall_total'][$x]=$overall_total;
                    $data['participant_id'][$x]=$participant_id;

                    $data['sub'][]=array(
                        "participant_id"=>$participant_id,
                        "sub_participant"=>$p->actual_billing_id,
                        "vatable_sales"=>$p->vatable_sales,
                        "zero_rated_sales"=>$zero_rated,
                        "rated_sales"=>$p->zero_rated_sales,
                        "zero_ecozones_sales"=>$p->zero_rated_ecozones,
                        "total_amount"=>$total_amount,
                        "vat_on_sales"=>$p->vat_on_sales,
                        "ewt"=>$p->ewt,
                        "overall_total"=>$overall_total,
                    );

                    $h=1;
                    foreach($this->super_model->select_custom_where("subparticipant","participant_id = '$participant_id'") AS $sp){
                            $subparticipant=$this->super_model->select_column_where("participant","billing_id","participant_id",$sp->sub_participant);
                            //$billing_id=$this->super_model->select_column_where("participant","billing_id","participant_id",$sp->sub_participant);
                        foreach($this->super_model->select_custom_where("sales_transaction_details","billing_id = '$subparticipant' AND sales_id = '$p->sales_id' AND total_amount != '0'") AS $s){
                            if($h<=15){

                                    $vatable_sales=$s->vatable_sales;
                                    $zero_rated_sales=$s->zero_rated_sales;
                                    $zero_rated_ecozones=$s->zero_rated_ecozones;
                                    $vat_on_sales=$s->vat_on_sales;
                                    $ewt=$s->ewt;


                                    // $vatable_sales=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                                    // $zero_rated_sales=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                                    // $zero_rated_ecozones=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                                    //$total_amount=$this->super_model->select_column_custom_where("sales_transaction_details","total_amount","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                                    // $vat_on_sales=$this->super_model->select_column_custom_where("sales_transaction_details","vat_on_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                                    // $ewt=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                                    $zero_rated= $zero_rated_sales + $zero_rated_ecozones;
                                    $total_amount = $vatable_sales + $zero_rated_sales + $zero_rated_ecozones;
                                    $overall_total= ($total_amount + $vat_on_sales) - $ewt;
                                    //$data['sub_participant_sub'][$h]=$subparticipant;
                                    //$data['vatable_sales_sub'][$h]=$vatable_sales;
                                    //$data['vat_on_sales_sub'][$h]=$vat_on_sales;
                                    //$data['zero_rated_sales_sub'][$h]=$zero_rated;
                                    //$data['total_amount_sub'][$h]=$total_amount;
                                    //$data['ewt_s'][$h]=$ewt;
                                    //$data['overall_total_sub'][$h]=$overall_total;
                                    //$data['participant_id_sub'][$h]=$sp->participant_id;
                                    $data['sub_part'][]=array(
                                        "counter"=>$this->super_model->count_custom_where("subparticipant","participant_id='$participant_id'"),
                                        "counter_h"=>'',
                                        "participant_id"=>$sp->participant_id,
                                        "sub_participant"=>$subparticipant,
                                        "vatable_sales"=>$vatable_sales,
                                        "zero_rated_sales"=>$zero_rated,
                                        "rated_sales"=>$zero_rated_sales,
                                        "zero_rated_ecozones"=>$zero_rated_ecozones,
                                        "total_amount"=>$total_amount,
                                        "vat_on_sales"=>$vat_on_sales,
                                        "ewt"=>$ewt,
                                        "overall_total"=>$overall_total,
                                        //"zero_rated"=>$zero_rated,
                                    );
                                }
                                $h++;
                            }
                        }

                        $total_amount = $p->vatable_sales + $p->zero_rated_sales + $p->zero_rated_ecozones;
                        $overall_total= ($total_amount+$p->vat_on_sales) - $p->ewt;

                        $data['sub_participant'][$x]=$p->actual_billing_id;
                        $data['vatable_sales'][$x]=$p->vatable_sales;
                        $data['vat_on_sales'][$x]=$p->vat_on_sales;
                        $data['zero_rated_sales'][$x]=$zero_rated;
                        $data['total_amount'][$x]=$total_amount;
                        $data['ewt'][$x]=$p->ewt;
                        $data['overall_total'][$x]=$overall_total;
                        $data['participant_id'][$x]=$participant_id;

                        $data['sub_second'][]=array(
                            "participant_id"=>$participant_id,
                            "sub_participant"=>$p->actual_billing_id,
                            "vatable_sales"=>$p->vatable_sales,
                            "zero_rated_sales"=>$p->zero_rated_sales,
                            "rated_sales"=>$p->zero_rated_sales,
                            "zero_ecozones_sales"=>$p->zero_rated_ecozones,
                            "total_amount"=>$total_amount,
                            "vat_on_sales"=>$p->vat_on_sales,
                            "ewt"=>$p->ewt,
                            "overall_total"=>$overall_total,
                        );
/*                        $z=0;
                        $r=1;
                        foreach($this->super_model->select_custom_where("subparticipant","participant_id = '$participant_id'") AS $s){*/

                        $z=1;
                        foreach($this->super_model->select_custom_where("subparticipant","participant_id = '$participant_id'") AS $sp){
                                $subparticipant=$this->super_model->select_column_where("participant","billing_id","participant_id",$sp->sub_participant);
                                //$billing_id=$this->super_model->select_column_where("participant","billing_id","participant_id",$sp->sub_participant);
                        foreach($this->super_model->select_custom_where("sales_transaction_details","billing_id = '$subparticipant' AND sales_id = '$p->sales_id' AND total_amount != '0'") AS $s){

                            
                              
                            $vatable_sales=$s->vatable_sales;
                            $zero_rated_sales=$s->zero_rated_sales;
                            $zero_rated_ecozones=$s->zero_rated_ecozones;
                            $vat_on_sales=$s->vat_on_sales;
                            $ewt=$s->ewt;
                                // $vatable_sales=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                                // $zero_rated_sales=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                                // $zero_rated_ecozones=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                                //$total_amount=$this->super_model->select_column_custom_where("sales_transaction_details","total_amount","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                                // $vat_on_sales=$this->super_model->select_column_custom_where("sales_transaction_details","vat_on_sales","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
                                // $ewt=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","billing_id = '$billing_id' AND sales_id = '$p->sales_id'");
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

                                if($z>=16){
                                //$data['sub_participant_sub'][$z]=$subparticipant;
                                // $data['vatable_sales_sub'][$z]=$vatable_sales;
                                // $data['vat_on_sales_sub'][$z]=$vat_on_sales;
                                // $data['zero_rated_sales_sub'][$z]=$zero_rated;
                                // $data['total_amount_sub'][$z]=$total_amount;
                                // $data['ewt_s'][$z]=$ewt;
                                // $data['overall_total_sub'][$z]=$overall_total;
                                // $data['participant_id_sub'][$z]=$sp->participant_id;
                                $data['sub_part_second'][]=array(
                                    "counter"=>$h,
                                    "counter_h"=>'',
                                    "participant_id"=>$sp->participant_id,
                                    "sub_participant"=>$subparticipant,
                                    "vatable_sales"=>$vatable_sales,
                                    "zero_rated_sales"=>$zero_rated,
                                    "rated_sales"=>$zero_rated_sales,
                                    "zero_ecozones_sales"=>$zero_rated_ecozones,
                                    "total_amount"=>$total_amount,
                                    "vat_on_sales"=>$vat_on_sales,
                                    "ewt"=>$ewt,
                                    "overall_total"=>$overall_total,
                                    //"zero_rated"=>$zero_rated,
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
        }
        $this->load->view('template/print_head');
        $this->load->view('sales/print_BS_new',$data);
    }

    public function print_invoice_multiple_small(){
        error_reporting(0);
        //$sales_detail_id = $this->uri->segment(3);
        $sales_details_id = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $sales_det_exp=explode("-",$sales_details_id);
        $data['count']=$count;
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        // $this->load->view('template/header');
        //$this->load->view('template/navbar');
        for($x=0;$x<$count;$x++){
             $bs_id[]=$this->super_model->custom_query_single('sales_detail_id',"SELECT * FROM sales_transaction_details std INNER JOIN bs_head bh ON bh.sales_detail_id=std.sales_detail_id WHERE bh.sales_detail_id='$sales_det_exp[$x]'");
            // if(array_key_exists($sales_det_exp[$x],$bs_id)){
            if(in_array($sales_det_exp[$x],$bs_id)){
                foreach($this->super_model->select_custom_where("bs_head","sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                    $data['address'][$x]=$p->address;
                    $data['tin'][$x]=$p->tin;
                    $data['company_name'][$x]=$p->participant_name;
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

            $data['vat_sales_peso_sub'][$x] = 0;
            $data['vat_sales_cents_sub'][$x] = 0;

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

            $data['total_zra_sub']=0;
            $data['zero_rated_ecozones_peso_sub'][$x]=0;
            $data['zero_rated_ecozones_cents_sub'][$x]=0;

            $total_vos=$p->total_vat;
            $vat_on_sales = explode(".",$total_vos);
            $data['vat_peso'][$x] = $vat_on_sales[0];
            $data['vat_cents'][$x] = $vat_on_sales[1];

            $data['vat_peso_sub'][$x] = 0;
            $data['vat_cents_sub'][$x] = 0;

            $total_ewt=$p->total_ewt;
            $ewt_exp=explode(".", $total_ewt);
            $data['ewt_peso'][$x]=$ewt_exp[0];
            $data['ewt_cents'][$x]=$ewt_exp[1];

            $data['ewt_peso_sub'][$x]=0;
            $data['ewt_cents_sub'][$x]=0;
            $total= $p->total_net_amount;

            
            //$total_sub= ($sum_vatable_sales + $sum_vat_on_sales + $sum_zero_rated_ecozone + $sum_zero_rated) - $sum_ewt;
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
                //foreach($this->super_model->select_row_where("sales_transaction_details","sales_detail_id",$sales_detail_id) AS $p){
            foreach($this->super_model->select_custom_where("sales_transaction_details","print_identifier='$print_identifier' AND sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                $data['address'][$x]=$this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                $data['tin'][$x]=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                $data['company_name'][$x]=$p->company_name;
                $data['billing_from'][$x]=$this->super_model->select_column_where("sales_transaction_head","billing_from","sales_id",$p->sales_id);
                $data['billing_to'][$x]=$this->super_model->select_column_where("sales_transaction_head","billing_to","sales_id",$p->sales_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("sales_transaction_head","transaction_date","sales_id",$p->sales_id);
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                $data['participant_id'][$x] = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                 //echo $participant_id."<br>";
                //$vatable_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","billing_id='$p->billing_id' AND sales_id='$p->sales_id'");
                $h=0;
                $vatable_sales_bs=array();
                $vat_on_sales_bs=array();
                $ewt_bs=array();
                $zero_rated_ecozone_bs=array();
                $zero_rated_bs=array();
                foreach($this->super_model->select_custom_where("subparticipant","participant_id='$participant_id'") AS $s){
                    $data['participant_id_sub'][$h]=$s->participant_id;
                    $billing_id=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);
                    $vatable_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","serial_no='$p->serial_no' AND billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vat_on_sales","serial_no='$p->serial_no' AND billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $ewt_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","serial_no='$p->serial_no' AND billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","serial_no='$p->serial_no' AND billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $zero_rated_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated","serial_no='$p->serial_no' AND billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $h++;
                }
            }

            //echo array_sum($vatable_sales_bs)."-".array_sum($vatable_sales_bs_sub)."<br>";
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

            //$ewt=str_replace("-", '', $p->ewt);
            
            //$total_vs=$p->vatable_sales + $sum_vatable_sales;
            $total_vs=$p->vatable_sales;
            $vatable_sales = explode(".",$total_vs);
            $data['vat_sales_peso'][$x] = $vatable_sales[0];
            $data['vat_sales_cents'][$x] = $vatable_sales[1];

            $total_vs_sub=$p->vatable_sales + $sum_vatable_sales;
            $vatable_sales_sub = explode(".",$total_vs_sub);
            $data['vat_sales_peso_sub'][$x] = $vatable_sales_sub[0];
            $data['vat_sales_cents_sub'][$x] = $vatable_sales_sub[1];

            $total_zr=$p->zero_rated_sales;
            $data['total_zr'][$x]=$total_zr;
            $zero_rated_sales = explode(".",$total_zr);
            $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            $total_zr_sub=$p->zero_rated_sales + $sum_zero_rated;
            $data['total_zr_sub'][$x]=$total_zr_sub;
            $zero_rated_sales_sub = explode(".",$total_zr_sub);
            $data['zero_rated_peso_sub'][$x] = $zero_rated_sales_sub[0];
            $data['zero_rated_cents_sub'][$x] = $zero_rated_sales_sub[1];

            $total_zra=$p->zero_rated_ecozones;
            $data['total_zra'][$x]=$total_zra;
            $zero_rated_ecozones_exp=explode(".", $total_zra);
            $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            $total_zra_sub=$p->zero_rated_ecozones + $sum_zero_rated_ecozone;
            $data['total_zra_sub']=$total_zra_sub;
            $zero_rated_ecozones_exp_sub=explode(".", $total_zra_sub);
            $data['zero_rated_ecozones_peso_sub'][$x]=$zero_rated_ecozones_exp_sub[0];
            $data['zero_rated_ecozones_cents_sub'][$x]=$zero_rated_ecozones_exp_sub[1];

            $total_vos=$p->vat_on_sales;
            $vat_on_sales = explode(".",$total_vos);
            $data['vat_peso'][$x] = $vat_on_sales[0];
            $data['vat_cents'][$x] = $vat_on_sales[1];

            $total_vos_sub=$p->vat_on_sales + $sum_vat_on_sales;
            $vat_on_sales_sub = explode(".",$total_vos_sub);
            $data['vat_peso_sub'][$x] = $vat_on_sales_sub[0];
            $data['vat_cents_sub'][$x] = $vat_on_sales_sub[1];

            $total_ewt=$p->ewt;
            $ewt_exp=explode(".", $total_ewt);
            $data['ewt_peso'][$x]=$ewt_exp[0];
            $data['ewt_cents'][$x]=$ewt_exp[1];

            $total_ewt_sub=$p->ewt + $sum_ewt;
            $ewt_exp_sub=explode(".", $total_ewt_sub);
            $data['ewt_peso_sub'][$x]=$ewt_exp_sub[0];
            $data['ewt_cents_sub'][$x]=$ewt_exp_sub[1];
            $total= ($p->vatable_sales + $p->vat_on_sales + $p->zero_rated_ecozones + $p->zero_rated_sales) - $p->ewt;

            
            $total_sub= ($sum_vatable_sales + $sum_vat_on_sales + $sum_zero_rated_ecozone + $sum_zero_rated) - $sum_ewt;
            $total_amount=str_replace(',','',number_format($total,2));
           
            $total_amount_sub=$total + $total_sub;
            $data['total_amount'][$x]=$total_amount;
            $data['total_amount_sub'][$x]=$total_amount_sub;
            $data['amount_words'][$x]=strtoupper($this->convertNumber($total_amount));
            $data['amount_words_sub'][$x]=strtoupper($this->convertNumber(str_replace(',','',number_format($total_amount_sub,2))));
            $total_exp=explode(".", $total_amount);
            $data['total_peso'][$x]=$total_exp[0];
            $data['total_cents'][$x]=$total_exp[1];

            $total_exp_sub=explode(".", $total_amount_sub);
            $data['total_peso_sub'][$x]=$total_exp_sub[0];
            $data['total_cents_sub'][$x]=$total_exp_sub[1];
            }
        }
        $this->load->view('template/print_head');
        $this->load->view('sales/print_invoice_multiple_small',$data);
    }
    public function print_invoice_multiple_half(){
        error_reporting(0);
        //$sales_detail_id = $this->uri->segment(3);
        $sales_details_id = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $sales_det_exp=explode("-",$sales_details_id);
        $data['count']=$count;
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        // $this->load->view('template/header');
        //$this->load->view('template/navbar');
        for($x=0;$x<$count;$x++){
             $bs_id[]=$this->super_model->custom_query_single('sales_detail_id',"SELECT * FROM sales_transaction_details std  INNER JOIN bs_head bh ON bh.sales_detail_id=std.sales_detail_id WHERE bh.sales_detail_id='$sales_det_exp[$x]'");
            // if(array_key_exists($sales_det_exp[$x],$bs_id)){
            if(in_array($sales_det_exp[$x],$bs_id)){
                foreach($this->super_model->select_custom_where("bs_head","sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                    $data['address'][$x]=$p->address;
                    $data['tin'][$x]=$p->tin;
                    $data['company_name'][$x]=$p->participant_name;
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

            $data['vat_sales_peso_sub'][$x] = 0;
            $data['vat_sales_cents_sub'][$x] = 0;

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

            $data['total_zra_sub']=0;
            $data['zero_rated_ecozones_peso_sub'][$x]=0;
            $data['zero_rated_ecozones_cents_sub'][$x]=0;

            $total_vos=$p->total_vat;
            $vat_on_sales = explode(".",$total_vos);
            $data['vat_peso'][$x] = $vat_on_sales[0];
            $data['vat_cents'][$x] = $vat_on_sales[1];

            $data['vat_peso_sub'][$x] = 0;
            $data['vat_cents_sub'][$x] = 0;

            $total_ewt=$p->total_ewt;
            $ewt_exp=explode(".", $total_ewt);
            $data['ewt_peso'][$x]=$ewt_exp[0];
            $data['ewt_cents'][$x]=$ewt_exp[1];

            $data['ewt_peso_sub'][$x]=0;
            $data['ewt_cents_sub'][$x]=0;
            $total= $p->total_net_amount;

            
            //$total_sub= ($sum_vatable_sales + $sum_vat_on_sales + $sum_zero_rated_ecozone + $sum_zero_rated) - $sum_ewt;
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
                //foreach($this->super_model->select_row_where("sales_transaction_details","sales_detail_id",$sales_detail_id) AS $p){
            
            foreach($this->super_model->select_custom_where("sales_transaction_details","print_identifier='$print_identifier' AND sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                $data['address'][$x]=$this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                $data['tin'][$x]=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                $data['company_name'][$x]=$p->company_name;
                $data['billing_from'][$x]=$this->super_model->select_column_where("sales_transaction_head","billing_from","sales_id",$p->sales_id);
                $data['billing_to'][$x]=$this->super_model->select_column_where("sales_transaction_head","billing_to","sales_id",$p->sales_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("sales_transaction_head","transaction_date","sales_id",$p->sales_id);
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                $data['participant_id'][$x] = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                 //echo $participant_id."<br>";
                //$vatable_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","billing_id='$p->billing_id' AND sales_id='$p->sales_id'");
                $h=0;
                $vatable_sales_bs=array();
                $vat_on_sales_bs=array();
                $ewt_bs=array();
                $zero_rated_ecozone_bs=array();
                $zero_rated_bs=array();
                foreach($this->super_model->select_custom_where("subparticipant","participant_id='$participant_id'") AS $s){
                    $data['participant_id_sub'][$h]=$s->participant_id;
                    $billing_id=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);
                    $vatable_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vat_on_sales","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $ewt_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $zero_rated_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $h++;
                }
            }

            //echo array_sum($vatable_sales_bs)."-".array_sum($vatable_sales_bs_sub)."<br>";
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

            //$ewt=str_replace("-", '', $p->ewt);
            
            //$total_vs=$p->vatable_sales + $sum_vatable_sales;
            $total_vs=$p->vatable_sales;
            $vatable_sales = explode(".",$total_vs);
            $data['vat_sales_peso'][$x] = $vatable_sales[0];
            $data['vat_sales_cents'][$x] = $vatable_sales[1];

            $total_vs_sub=$p->vatable_sales + $sum_vatable_sales;
            $vatable_sales_sub = explode(".",$total_vs_sub);
            $data['vat_sales_peso_sub'][$x] = $vatable_sales_sub[0];
            $data['vat_sales_cents_sub'][$x] = $vatable_sales_sub[1];

            $total_zr=$p->zero_rated_sales;
            $data['total_zr'][$x]=$total_zr;
            $zero_rated_sales = explode(".",$total_zr);
            $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            $total_zr_sub=$p->zero_rated_sales + $sum_zero_rated;
            $data['total_zr_sub'][$x]=$total_zr_sub;
            $zero_rated_sales_sub = explode(".",$total_zr_sub);
            $data['zero_rated_peso_sub'][$x] = $zero_rated_sales_sub[0];
            $data['zero_rated_cents_sub'][$x] = $zero_rated_sales_sub[1];

            $total_zra=$p->zero_rated_ecozones;
            $data['total_zra'][$x]=$total_zra;
            $zero_rated_ecozones_exp=explode(".", $total_zra);
            $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            $total_zra_sub=$p->zero_rated_ecozones + $sum_zero_rated_ecozone;
            $data['total_zra_sub']=$total_zra_sub;
            $zero_rated_ecozones_exp_sub=explode(".", $total_zra_sub);
            $data['zero_rated_ecozones_peso_sub'][$x]=$zero_rated_ecozones_exp_sub[0];
            $data['zero_rated_ecozones_cents_sub'][$x]=$zero_rated_ecozones_exp_sub[1];

            $total_vos=$p->vat_on_sales;
            $vat_on_sales = explode(".",$total_vos);
            $data['vat_peso'][$x] = $vat_on_sales[0];
            $data['vat_cents'][$x] = $vat_on_sales[1];

            $total_vos_sub=$p->vat_on_sales + $sum_vat_on_sales;
            $vat_on_sales_sub = explode(".",$total_vos_sub);
            $data['vat_peso_sub'][$x] = $vat_on_sales_sub[0];
            $data['vat_cents_sub'][$x] = $vat_on_sales_sub[1];

            $total_ewt=$p->ewt;
            $ewt_exp=explode(".", $total_ewt);
            $data['ewt_peso'][$x]=$ewt_exp[0];
            $data['ewt_cents'][$x]=$ewt_exp[1];

            $total_ewt_sub=$p->ewt + $sum_ewt;
            $ewt_exp_sub=explode(".", $total_ewt_sub);
            $data['ewt_peso_sub'][$x]=$ewt_exp_sub[0];
            $data['ewt_cents_sub'][$x]=$ewt_exp_sub[1];
            $total= ($p->vatable_sales + $p->vat_on_sales + $p->zero_rated_ecozones + $p->zero_rated_sales) - $p->ewt;

            
            $total_sub= ($sum_vatable_sales + $sum_vat_on_sales + $sum_zero_rated_ecozone + $sum_zero_rated) - $sum_ewt;
            $total_amount=str_replace(',','',number_format($total,2));
           
            $total_amount_sub=$total + $total_sub;
            $data['total_amount'][$x]=$total_amount;
            $data['total_amount_sub'][$x]=$total_amount_sub;
            $data['amount_words'][$x]=strtoupper($this->convertNumber($total_amount));
            $data['amount_words_sub'][$x]=strtoupper($this->convertNumber(str_replace(',','',number_format($total_amount_sub,2))));
            $total_exp=explode(".", $total_amount);
            $data['total_peso'][$x]=$total_exp[0];
            $data['total_cents'][$x]=$total_exp[1];

            $total_exp_sub=explode(".", $total_amount_sub);
            $data['total_peso_sub'][$x]=$total_exp_sub[0];
            $data['total_cents_sub'][$x]=$total_exp_sub[1];
            }
        }
        $this->load->view('template/print_head');
        $this->load->view('sales/print_invoice_multiple_half',$data);
    }
    public function print_invoice_multiple_main(){
        error_reporting(0);
        //$sales_detail_id = $this->uri->segment(3);
        $sales_details_id = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $sales_det_exp=explode("-",$sales_details_id);
        $data['count']=$count;
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        // $this->load->view('template/header');
        //$this->load->view('template/navbar');
        for($x=0;$x<$count;$x++){
             $bs_id[]=$this->super_model->custom_query_single('sales_detail_id',"SELECT * FROM sales_transaction_details std  INNER JOIN bs_head bh ON bh.sales_detail_id=std.sales_detail_id WHERE bh.sales_detail_id='$sales_det_exp[$x]'");
            // if(array_key_exists($sales_det_exp[$x],$bs_id)){
            if(in_array($sales_det_exp[$x],$bs_id)){
                foreach($this->super_model->select_custom_where("bs_head","sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                    $data['address'][$x]=$p->address;
                    $data['tin'][$x]=$p->tin;
                    $data['company_name'][$x]=$p->participant_name;
                    $data['billing_from'][$x]=$p->billing_from;
                    $data['billing_to'][$x]=$p->billing_to;
                    $data['transaction_date'][$x]=$p->statement_date;
                    $participant_id = $p->participant_id;
                    $data['participant_id'][$x] = $p->participant_id;
                    $data['reference_number'][$x] = $this->super_model->select_column_where("bs_head","reference_number","bs_head_id",$p->bs_head_id);
                    $data['or_no'][$x]=$p->invoice_no;

                    $data['total_vs'][$x]= $p->total_vatable_sales;
                    $data['total_zr'][$x]=$p->total_zero_sales;
                    $data['total_zra'][$x]= $p->total_zero_ecozones;
                    $data['total_vos'][$x]=$p->total_vat;
                    $data['total_ewt'][$x]= $p->total_ewt;
                }

            }else{
                //foreach($this->super_model->select_row_where("sales_transaction_details","sales_detail_id",$sales_detail_id) AS $p){
            foreach($this->super_model->select_custom_where("sales_transaction_details","print_identifier='$print_identifier' AND sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                $data['address'][$x]=$this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                $data['tin'][$x]=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                // $data['company_name'][$x]=$p->company_name;
                $data['billing_from'][$x]=$this->super_model->select_column_where("sales_transaction_head","billing_from","sales_id",$p->sales_id);
                $data['billing_to'][$x]=$this->super_model->select_column_where("sales_transaction_head","billing_to","sales_id",$p->sales_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("sales_transaction_head","transaction_date","sales_id",$p->sales_id);
                $data['reference_number'][$x]=$this->super_model->select_column_where("sales_transaction_head","reference_number","sales_id",$p->sales_id);
                $data['or_no'][$x]=$p->serial_no;
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                $data['participant_id'][$x] = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);

                $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $p->sales_id);
                if(!empty($p->company_name)){
                    $data['company_name'][$x]=$p->company_name;
                }else{
                    $data['company_name'][$x]=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $p->billing_id);
                }
                 //echo $participant_id."<br>";
                //$vatable_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","billing_id='$p->billing_id' AND sales_id='$p->sales_id'");
                $h=0;
                $vatable_sales_bs=array();
                $vat_on_sales_bs=array();
                $ewt_bs=array();
                $zero_rated_ecozone_bs=array();
                $zero_rated_bs=array();
                foreach($this->super_model->select_custom_where("subparticipant","participant_id='$participant_id'") AS $s){
                    $data['participant_id_sub'][$h]=$s->participant_id;
                    $billing_id=trim($this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant));
                    
                //    $data['billing_id']= $billing_id;
                //    $data['sales_id']= $p->sales_id;
                    $vatable_sales_bs[]=$this->super_model->select_sum_where("sales_transaction_details","vatable_sales","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $vat_on_sales_bs[]=$this->super_model->select_sum_where("sales_transaction_details","vat_on_sales","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $ewt_bs[]=$this->super_model->select_sum_where("sales_transaction_details","ewt","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $zero_rated_ecozone_bs[]=$this->super_model->select_sum_where("sales_transaction_details","zero_rated_ecozones","billing_id='$billing_id' AND sales_id='$p->sales_id'");

                    //echo $billing_id . " "  . $p->sales_id . " = ". $this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","billing_id='$billing_id' AND sales_id='$p->sales_id'"). "<br>";
                    $zero_rated_bs[]=$this->super_model->select_sum_where("sales_transaction_details","zero_rated","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $h++;
                }

                $vatable_sales_bs_parent=$this->super_model->select_sum_where("sales_transaction_details","vatable_sales","billing_id='$p->billing_id' AND sales_id='$p->sales_id'");
                $vat_on_sales_bs_parent=$this->super_model->select_sum_where("sales_transaction_details","vat_on_sales","billing_id='$p->billing_id' AND sales_id='$p->sales_id'");
                $zero_rated_ecozone_bs_parent=$this->super_model->select_sum_where("sales_transaction_details","zero_rated_ecozones","billing_id='$p->billing_id' AND sales_id='$p->sales_id'");
                $zero_rated_bs_parent=$this->super_model->select_sum_where("sales_transaction_details","zero_rated","billing_id='$p->billing_id' AND sales_id='$p->sales_id'");
                $ewt_bs_parent=$this->super_model->select_sum_where("sales_transaction_details","ewt","billing_id='$p->billing_id' AND sales_id='$p->sales_id'");

            }

            //echo array_sum($vatable_sales_bs)."-".array_sum($vatable_sales_bs_sub)."<br>";
            $sum_vatable_sales=array_sum($vatable_sales_bs) + $vatable_sales_bs_parent;
            $sum_vs_exp = explode(".", $sum_vatable_sales);
            $sum_vatable_sales_peso=$sum_vs_exp[0];
            $sum_vatable_sales_cents=$sum_vs_exp[1];

            $sum_zero_rated_ecozone=array_sum($zero_rated_ecozone_bs) + $zero_rated_ecozone_bs_parent;
           // echo $sum_zero_rated_ecozone;
            $sum_zre_exp=explode(".", $sum_zero_rated_ecozone);
            $sum_zero_rated_ecozone_peso=$sum_zre_exp[0];
            $sum_zero_rated_ecozone_cents=$sum_zre_exp[1];

            $sum_vat_on_sales=array_sum($vat_on_sales_bs) + $vat_on_sales_bs_parent;
            $sum_vos_exp=explode(".", $sum_vat_on_sales);
            $sum_vat_on_sales_peso=$sum_vos_exp[0];
            $sum_vat_on_sales_cents=$sum_vos_exp[1];

            $sum_ewt=array_sum($ewt_bs)  + $ewt_bs_parent;
            $sum_e_exp=explode(".", $sum_ewt);
            $sum_ewt_peso=$sum_e_exp[0];
            $sum_ewt_cents=$sum_e_exp[1];

            $sum_zero_rated=array_sum($zero_rated_bs) + $zero_rated_bs_parent;
            $sum_zr_exp=explode(".", $sum_zero_rated);
            $sum_zero_rated_peso=$sum_zr_exp[0];
            $sum_zero_rated_cents=$sum_zr_exp[1];

            $ewt=str_replace("-", '', $p->ewt);
            
            $data['total_vs'][$x]= $sum_vatable_sales;
            //$total_vs=$p->vatable_sales + $sum_vatable_sales;
            // $total_vs=$p->vatable_sales;
            // $vatable_sales = explode(".",$total_vs);
            // $data['vat_sales_peso'][$x] = $vatable_sales[0];
            // $data['vat_sales_cents'][$x] = $vatable_sales[1];

            // $total_vs_sub=$p->vatable_sales + $sum_vatable_sales;
            // $vatable_sales_sub = explode(".",$total_vs_sub);
            // $data['vat_sales_peso_sub'][$x] = $vatable_sales_sub[0];
            // $data['vat_sales_cents_sub'][$x] = $vatable_sales_sub[1];

            $data['total_zr'][$x]=$sum_zero_rated;
            // $total_zr=$p->zero_rated_sales;
            // $data['total_zr'][$x]=$total_zr;
            // $zero_rated_sales = explode(".",$total_zr);
            // $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            // $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            // $total_zr_sub=$p->zero_rated_sales + $sum_zero_rated;
            // $data['total_zr_sub'][$x]=$total_zr_sub;
            // $zero_rated_sales_sub = explode(".",$total_zr_sub);
            // $data['zero_rated_peso_sub'][$x] = $zero_rated_sales_sub[0];
            // $data['zero_rated_cents_sub'][$x] = $zero_rated_sales_sub[1];

            $data['total_zra'][$x]= $sum_zero_rated_ecozone;
            // $total_zra=$p->zero_rated_ecozones;
            // $data['total_zra'][$x]=$total_zra;
            // $zero_rated_ecozones_exp=explode(".", $total_zra);
            // $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            // $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            // $total_zra_sub=$p->zero_rated_ecozones + $sum_zero_rated_ecozone;
            // $data['total_zra_sub']=$total_zra_sub;
            // $zero_rated_ecozones_exp_sub=explode(".", $total_zra_sub);
            // $data['zero_rated_ecozones_peso_sub'][$x]=$zero_rated_ecozones_exp_sub[0];
            // $data['zero_rated_ecozones_cents_sub'][$x]=$zero_rated_ecozones_exp_sub[1];

            $data['total_vos'][$x]=$sum_vat_on_sales;
            // $total_vos=$p->vat_on_sales;
            // $vat_on_sales = explode(".",$total_vos);
            // $data['vat_peso'][$x] = $vat_on_sales[0];
            // $data['vat_cents'][$x] = $vat_on_sales[1];

            // $total_vos_sub=$p->vat_on_sales + $sum_vat_on_sales;
            // $vat_on_sales_sub = explode(".",$total_vos_sub);
            // $data['vat_peso_sub'][$x] = $vat_on_sales_sub[0];
            // $data['vat_cents_sub'][$x] = $vat_on_sales_sub[1];

            $data['total_ewt'][$x]= $sum_ewt;
            // $total_ewt=$p->ewt;
            // $ewt_exp=explode(".", $total_ewt);
            // $data['ewt_peso'][$x]=$ewt_exp[0];
            // $data['ewt_cents'][$x]=$ewt_exp[1];

            // $total_ewt_sub=$p->ewt + $sum_ewt;
            // $ewt_exp_sub=explode(".", $total_ewt_sub);
            // $data['ewt_peso_sub'][$x]=$ewt_exp_sub[0];
            // $data['ewt_cents_sub'][$x]=$ewt_exp_sub[1];
            // $total= ($p->vatable_sales + $p->vat_on_sales + $p->zero_rated_ecozones + $p->zero_rated_sales) - $p->ewt;

            
            // $total_sub= ($sum_vatable_sales + $sum_vat_on_sales + $sum_zero_rated_ecozone + $sum_zero_rated) - $sum_ewt;
            // $total_amount=str_replace(',','',number_format($total,2));
           
            // $total_amount_sub=$total + $total_sub;
            // $data['total_amount'][$x]=$total_amount;
            // $data['total_amount_sub'][$x]=$total_amount_sub;
            // $data['amount_words'][$x]=strtoupper($this->convertNumber($total_amount));
            // $data['amount_words_sub'][$x]=strtoupper($this->convertNumber(str_replace(',','',number_format($total_amount_sub,2))));
            // $total_exp=explode(".", $total_amount);
            // $data['total_peso'][$x]=$total_exp[0];
            // $data['total_cents'][$x]=$total_exp[1];

            // $total_exp_sub=explode(".", $total_amount_sub);
            // $data['total_peso_sub'][$x]=$total_exp_sub[0];
            // $data['total_cents_sub'][$x]=$total_exp_sub[1];
            }
        }
        $this->load->view('template/print_head');
        $this->load->view('sales/print_invoice_multiple_main',$data);
    }

    public function print_invoice_multiple_new(){
        error_reporting(0);
        //$sales_detail_id = $this->uri->segment(3);
        $sales_details_id = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $sales_det_exp=explode("-",$sales_details_id);
        $data['count']=$count;
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        // $this->load->view('template/header');
        //$this->load->view('template/navbar');
        for($x=0;$x<$count;$x++){
            //foreach($this->super_model->select_row_where("sales_transaction_details","sales_detail_id",$sales_detail_id) AS $p){
            foreach($this->super_model->select_custom_where("sales_transaction_details","print_identifier='$print_identifier' AND sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                $data['address'][$x]=$this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                $data['tin'][$x]=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                $data['company_name'][$x]=$p->company_name;
                $data['billing_from'][$x]=$this->super_model->select_column_where("sales_transaction_head","billing_from","sales_id",$p->sales_id);
                $data['billing_to'][$x]=$this->super_model->select_column_where("sales_transaction_head","billing_to","sales_id",$p->sales_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("sales_transaction_head","transaction_date","sales_id",$p->sales_id);
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                $data['participant_id'][$x] = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                 //echo $participant_id."<br>";
                //$vatable_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","billing_id='$p->billing_id' AND sales_id='$p->sales_id'");
                $h=0;
                foreach($this->super_model->select_custom_where("subparticipant","participant_id='$participant_id'") AS $s){
                    $data['participant_id_sub'][$h]=$s->participant_id;
                    $billing_id=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);
                    $vatable_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vat_on_sales","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $ewt_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $zero_rated_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated","billing_id='$billing_id' AND sales_id='$p->sales_id'");
                    $h++;
                }
            }

            //echo array_sum($vatable_sales_bs)."-".array_sum($vatable_sales_bs_sub)."<br>";
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

            //$ewt=str_replace("-", '', $p->ewt);
            
            //$total_vs=$p->vatable_sales + $sum_vatable_sales;
            $total_vs=$p->vatable_sales;
            $vatable_sales = explode(".",$total_vs);
            $data['vat_sales_peso'][$x] = $vatable_sales[0];
            $data['vat_sales_cents'][$x] = $vatable_sales[1];

            $total_vs_sub=$p->vatable_sales + $sum_vatable_sales;
            $vatable_sales_sub = explode(".",$total_vs_sub);
            $data['vat_sales_peso_sub'][$x] = $vatable_sales_sub[0];
            $data['vat_sales_cents_sub'][$x] = $vatable_sales_sub[1];

            $total_zr=$p->zero_rated_sales;
            $data['total_zr'][$x]=$total_zr;
            $zero_rated_sales = explode(".",$total_zr);
            $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            $total_zr_sub=$p->zero_rated_sales + $sum_zero_rated;
            $data['total_zr_sub'][$x]=$total_zr_sub;
            $zero_rated_sales_sub = explode(".",$total_zr_sub);
            $data['zero_rated_peso_sub'][$x] = $zero_rated_sales_sub[0];
            $data['zero_rated_cents_sub'][$x] = $zero_rated_sales_sub[1];

            $total_zra=$p->zero_rated_ecozones;
            $data['total_zra'][$x]=$total_zra;
            $zero_rated_ecozones_exp=explode(".", $total_zra);
            $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            $total_zra_sub=$p->zero_rated_ecozones + $sum_zero_rated_ecozone;
            $data['total_zra_sub']=$total_zra_sub;
            $zero_rated_ecozones_exp_sub=explode(".", $total_zra_sub);
            $data['zero_rated_ecozones_peso_sub'][$x]=$zero_rated_ecozones_exp_sub[0];
            $data['zero_rated_ecozones_cents_sub'][$x]=$zero_rated_ecozones_exp_sub[1];

            $total_vos=$p->vat_on_sales;
            $vat_on_sales = explode(".",$total_vos);
            $data['vat_peso'][$x] = $vat_on_sales[0];
            $data['vat_cents'][$x] = $vat_on_sales[1];

            $total_vos_sub=$p->vat_on_sales + $sum_vat_on_sales;
            $vat_on_sales_sub = explode(".",$total_vos_sub);
            $data['vat_peso_sub'][$x] = $vat_on_sales_sub[0];
            $data['vat_cents_sub'][$x] = $vat_on_sales_sub[1];

            $total_ewt=$p->ewt;
            $ewt_exp=explode(".", $total_ewt);
            $data['ewt_peso'][$x]=$ewt_exp[0];
            $data['ewt_cents'][$x]=$ewt_exp[1];

            $total_ewt_sub=$p->ewt + $sum_ewt;
            $ewt_exp_sub=explode(".", $total_ewt_sub);
            $data['ewt_peso_sub'][$x]=$ewt_exp_sub[0];
            $data['ewt_cents_sub'][$x]=$ewt_exp_sub[1];
            $total= ($p->vatable_sales + $p->vat_on_sales + $p->zero_rated_ecozones + $p->zero_rated_sales) - $p->ewt;

            
            $total_sub= ($sum_vatable_sales + $sum_vat_on_sales + $sum_zero_rated_ecozone + $sum_zero_rated) - $sum_ewt;
            $total_amount=str_replace(',','',number_format($total,2));
           
            $total_amount_sub=$total + $total_sub;
            $data['total_amount'][$x]=$total_amount;
            $data['total_amount_sub'][$x]=$total_amount_sub;
            $data['amount_words'][$x]=strtoupper($this->convertNumber($total_amount));
            $data['amount_words_sub'][$x]=strtoupper($this->convertNumber(str_replace(',','',number_format($total_amount_sub,2))));
            $total_exp=explode(".", $total_amount);
            $data['total_peso'][$x]=$total_exp[0];
            $data['total_cents'][$x]=$total_exp[1];

            $total_exp_sub=explode(".", $total_amount_sub);
            $data['total_peso_sub'][$x]=$total_exp_sub[0];
            $data['total_cents_sub'][$x]=$total_exp_sub[1];
        }
        $this->load->view('template/print_head');
        $this->load->view('sales/print_invoice_multiple_new',$data);
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

    public function print_BS_reserve(){
        $res_sales_details_id = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $data['count']=$count;
        $res_sales_det_exp=explode("-",$res_sales_details_id);
        $data['res_sales_detail_id']=$res_sales_details_id;
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
        $data['total_vat'][]='';
        $data['total_ewt'][]='';
        $data['total_net_amount'][]='';
        $data['res_bs_head_id'][]='';
        $data['total_sub_h'][]='';
        $data['total_sub'][]='';
        
        for($x=0;$x<$count;$x++){
            $array_ressalesdet_id[]=$res_sales_det_exp[$x];
            $res_bs_id[]=$this->super_model->custom_query_single('reserve_sales_detail_id',"SELECT * FROM reserve_sales_transaction_details std INNER JOIN reserve_bs_head bh ON bh.reserve_sales_detail_id=std.reserve_sales_detail_id WHERE bh.reserve_sales_detail_id='$res_sales_det_exp[$x]'");
            if(array_key_exists($res_sales_det_exp[$x],$res_bs_id)){

                
                foreach($this->super_model->select_custom_where("reserve_bs_head","reserve_sales_detail_id='".$res_sales_det_exp[$x]."'") AS $p){
                    $data['detail_id'][$x]=$p->reserve_sales_detail_id;
                    $detail_id=$p->reserve_sales_detail_id;
                    $data['address'][$x]=$p->res_address;
                    $address=$p->res_address;
                    $data['tin'][$x]=$p->res_tin;
                    $tin=$p->res_tin;
                    $data['company_name'][$x]=$p->res_participant_name;
                    $company_name=$p->res_participant_name;
                    $data['serial_no'][$x]=$p->res_invoice_no;
                    $serial_no=$p->res_invoice_no;
                    $data['settlement'][$x]=$p->res_stl_id;
                    $settlement=$p->res_stl_id;
                    $data['transaction_date'][$x]=$p->res_statement_date;
                    $transaction_date=$p->res_statement_date;
                    $data['billing_from'][$x]=$p->res_billing_from;
                    $billing_from=$p->res_billing_from;
                    $data['billing_to'][$x]=$p->res_billing_to;
                    $billing_to=$p->res_billing_to;
                    $data['due_date'][$x]=$p->res_due_date;
                    $due_date=$p->res_due_date;
                    $data['reference_number'][$x]=$p->res_reference_number;
                    $data['fullname'][$x]=$this->super_model->select_column_where("users","fullname","user_id",$p->res_prepared_by);
                    $data['prepared_by_pos'][$x]=$this->super_model->select_column_where("users","position","user_id",$p->res_prepared_by);
                    $data['checked_by_emg'][$x]=$p->res_checked_by_emg;
                    $data['checked_by_emg_pos'][$x]=$p->res_checked_by_emg_pos;
                    $data['checked_by_accounting'][$x]=$p->res_checked_by_accounting;
                    $data['checked_by_accounting_pos'][$x]=$p->res_checked_by_accounting_pos;
                    $data['checked_by_finance'][$x]=$p->res_checked_by_finance;
                    $data['checked_by_finance_pos'][$x]=$p->res_checked_by_finance_pos;
                    $data['noted_by'][$x]=$p->res_noted_by;
                    $data['noted_by_pos'][$x]=$p->res_noted_by_pos;
                    $data['total_vatable_sales'][$x]=$p->res_total_vatable_sales;
                    $data['total_zero_rated'][$x]=$p->res_total_zero_rated;
                    $data['total_vat'][$x]=$p->res_total_vat;
                    $data['total_ewt'][$x]=$p->res_total_ewt;
                    $data['total_net_amount'][$x]=$p->res_total_net_amount;
                    $data['bs_head_id'][$x]=$p->res_bs_head_id;
                    $reference_number=$p->res_reference_number;
                    $participant_id = $p->res_participant_id;
                    $count_sub_hist=$this->super_model->count_custom_where("reserve_bs_details","reserve_bs_head_id='$p->reserve_bs_head_id'");
                    $data['count_sub_hist'][$x]=$this->super_model->count_custom_where("reserve_bs_details","reserve_bs_head_id='$p->reserve_bs_head_id'");
                    $actual_billing_id = $this->super_model->select_column_where("reserve_bs_details","res_actual_billing_id","reserve_bs_head_id",$p->reserve_bs_head_id);
                    $vatable_sales = $this->super_model->select_column_where("reserve_bs_details","res_vatable_sales","reserve_bs_head_id",$p->reserve_bs_head_id);
                    $zero_rated_sales = $this->super_model->select_column_where("reserve_bs_details","res_zero_rated_sales","reserve_bs_head_id",$p->reserve_bs_head_id);
                    $vat = $this->super_model->select_column_where("reserve_bs_details","res_vat","reserve_bs_head_id",$p->reserve_bs_head_id);
                    $ewt = $this->super_model->select_column_where("reserve_bs_details","res_ewt","reserve_bs_head_id",$p->reserve_bs_head_id);
                    $overall_total = $this->super_model->select_column_where("reserve_bs_details","res_net_amount","reserve_bs_head_id",$p->reserve_bs_head_id);
                    $total_amount = $vatable_sales + $zero_rated_sales;

                    $data['sub_participant'][$x]=$actual_billing_id;
                    $data['vatable_sales'][$x]=$vatable_sales;
                    $data['vat_on_sales'][$x]=$vat;
                    $data['zero_rated'][$x]=$zero_rated_sales;
                    $data['rated_sales'][$x]=$p->res_total_zero_sales;
                    $data['zero_ecozones_sales'][$x]=$p->res_total_zero_ecozones;
                    $data['total_amount'][$x]=$total_amount;
                    $data['ewt'][$x]=$ewt;
                    $data['overall_total'][$x]=$overall_total;
                    $data['participant_id'][$x]=$participant_id;

                    $data['sub'][]=array(
                        "participant_id"=>$participant_id,
                        "bs_head_id"=>$p->reserve_bs_head_id,
                        "sub_participant"=>$actual_billing_id,
                        "vatable_sales"=>$vatable_sales,
                        "zero_rated_sales"=>$zero_rated_sales,
                        "zero_ecozones_sales"=>$p->res_total_zero_ecozones,
                        "total_amount"=>$total_amount,
                        "vat_on_sales"=>$vat,
                        "ewt"=>$ewt,
                        "overall_total"=>$overall_total,
                    );
                     
                    
                    $h=0;
                    $u=1;

                    
                    foreach($this->super_model->select_custom_where("reserve_bs_details","reserve_bs_head_id='$p->reserve_bs_head_id'") AS $s){
                    if($u <= 15){
                            $data['sub_participant_sub']=$s->res_actual_billing_id;
                            $data['vatable_sales_sub']=$s->res_vatable_sales;
                            $data['vat_on_sales_sub']=$s->res_vat;
                            $data['zero_rated_sales_sub']=$s->res_zero_rated_sales;
                            $data['ewt_s']=$s->res_ewt;
                            $data['overall_total_sub']=$overall_total;
                            $data['participant_id_sub']=$participant_id;
                            $data['total_sub_h']= $this->super_model->count_custom_where("reserve_bs_details","reserve_bs_head_id='$p->reserve_bs_head_id'");
                            $data['total_sub']='';
                                $data['sub_part'][]=array(
                                    "participant_id"=>$participant_id,
                                    "bs_head_id"=>$p->reserve_bs_head_id,
                                    "sub_participant"=>$s->res_actual_billing_id,
                                    "vatable_sales"=>$s->res_vatable_sales,
                                    "zero_rated_sales"=>$s->res_zero_rated_sales,
                                    "vat_on_sales"=>$s->res_vat,
                                    "ewt"=>$s->res_ewt,
                                    "overall_total"=>$s->res_net_amount,
                                );
                               
                            $h++;
                        }
                       $u++; 
                    }

                        $data['sub_participant'][$x]=$actual_billing_id;
                        $data['vatable_sales'][$x]=$vatable_sales;
                        $data['vat_on_sales'][$x]=$vat;
                        $data['zero_rated_sales'][$x]=$zero_rated_sales;
                        $data['ewt'][$x]=$ewt;
                        $data['overall_total'][$x]=$overall_total;
                        $data['participant_id'][$x]=$participant_id;
                        $data['sub_second'][]=array(
                            "participant_id"=>$participant_id,
                            "bs_head_id"=>$p->reserve_bs_head_id,
                            "sub_participant"=>$actual_billing_id,
                            "vatable_sales"=>$vatable_sales,
                            "zero_rated_sales"=>$zero_rated_sales,
                            "total_amount"=>$total_amount,
                            "vat_on_sales"=>$vat,
                            "ewt"=>$ewt,
                            "overall_total"=>$overall_total,
                        );
                    
                        $z=0;
                        $t=1;
                        foreach($this->super_model->select_custom_where("reserve_bs_details","reserve_bs_head_id='$p->reserve_bs_head_id'") AS $s){
                    if($t>=16){
                                $data['sub_participant_sub'][$z]=$s->res_actual_billing_id;
                                $data['vatable_sales_sub'][$z]=$s->res_vatable_sales;
                                $data['vat_on_sales_sub'][$z]=$s->res_vat;
                                $data['zero_rated_sales_sub'][$z]=$s->res_zero_rated_sales;
                                $data['ewt_s'][$z]=$s->res_ewt;
                                $data['participant_id_sub'][$z]=$participant_id;
                                $data['sub_part_second'][]=array(
                                    "counter"=>'',
                                    "counter_h"=>$t,
                                    "participant_id"=>$participant_id,
                                    "bs_head_id"=>$p->res_bs_head_id,
                                    "sub_participant"=>$s->res_actual_billing_id,
                                    "vatable_sales"=>$s->res_vatable_sales,
                                    "zero_rated_sales"=>$s->res_zero_rated_sales,
                                    "vat_on_sales"=>$s->res_vat,
                                    "ewt"=>$s->res_ewt,
                                    "overall_total"=>$s->res_net_amount,
                                );
                            }
                            $z++;
                            $t++;
                    }
                }
            }else{
                foreach($this->super_model->select_custom_where("reserve_sales_transaction_details","res_print_identifier='$print_identifier' AND reserve_sales_detail_id='".$res_sales_det_exp[$x]."'") AS $p){
                    $data['detail_id'][$x]=$p->reserve_sales_detail_id;
                    $detail_id=$p->reserve_sales_detail_id;
                    $data['address'][$x]=$this->super_model->select_column_where("reserve_participant","res_registered_address","res_billing_id",$p->res_billing_id);
                    $address=$this->super_model->select_column_where("reserve_participant","res_office_address","res_billing_id",$p->res_billing_id);
                    $data['tin'][$x]=$this->super_model->select_column_where("reserve_participant","res_tin","res_billing_id",$p->res_billing_id);
                    $tin=$this->super_model->select_column_where("reserve_participant","res_tin","res_billing_id",$p->res_billing_id);
                    $data['company_name'][$x]=$p->res_company_name;
                    $company_name=$p->res_company_name;
                    $data['serial_no'][$x]=$p->res_serial_no;
                    $serial_no=$p->res_serial_no;
                    $data['settlement'][$x]=$this->super_model->select_column_where("reserve_participant","res_settlement_id","res_billing_id",$p->res_billing_id);
                    $settlement=$this->super_model->select_column_where("reserve_participant","res_settlement_id","res_billing_id",$p->res_billing_id);
                    $data['transaction_date'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_transaction_date","reserve_sales_id",$p->reserve_sales_id);
                    $transaction_date=$this->super_model->select_column_where("reserve_sales_transaction_head","res_transaction_date","reserve_sales_id",$p->reserve_sales_id);
                    $data['billing_from'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_from","reserve_sales_id",$p->reserve_sales_id);
                    $billing_from=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_from","reserve_sales_id",$p->reserve_sales_id);
                    $data['billing_to'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_to","reserve_sales_id",$p->reserve_sales_id);
                    $billing_to=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_to","reserve_sales_id",$p->reserve_sales_id);
                    $data['due_date'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_due_date","reserve_sales_id",$p->reserve_sales_id);
                    $due_date=$this->super_model->select_column_where("reserve_sales_transaction_head","res_due_date","reserve_sales_id",$p->reserve_sales_id);
                    $data['reference_number'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_reference_number","reserve_sales_id",$p->reserve_sales_id);
                    $reference_number=$this->super_model->select_column_where("reserve_sales_transaction_head","res_reference_number","reserve_sales_id",$p->reserve_sales_id);
                    $participant_id = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$p->res_billing_id);
                    $count_sub=$this->super_model->count_custom_where("reserve_subparticipant","res_participant_id='$participant_id'");
                    $zero_rated= $p->res_zero_rated_sales + $p->res_zero_rated_ecozones;
                    $total_amount = $p->res_vatable_sales + $p->res_zero_rated_sales + $p->res_zero_rated_ecozones;
                    $overall_total= ($total_amount+$p->res_vat_on_sales) - $p->res_ewt;
                    $data['bs_head_id'][$x]='';
                    $data['count_sub_hist'][$x]='';

                    $data['sub_participant'][$x]=$p->res_actual_billing_id;
                    $data['vatable_sales'][$x]=$p->res_vatable_sales;
                    $data['vat_on_sales'][$x]=$p->res_vat_on_sales;
                    $data['zero_rated'][$x]=$zero_rated;
                    $data['rated_sales'][$x]=$p->res_zero_rated_sales;
                    $data['zero_ecozones_sales'][$x]=$p->res_zero_rated_ecozones;
                    $data['total_amount'][$x]=$total_amount;
                    $data['ewt'][$x]=$p->res_ewt;
                    $data['overall_total'][$x]=$overall_total;
                    $data['participant_id'][$x]=$participant_id;

                    $data['sub'][]=array(
                        "participant_id"=>$participant_id,
                        "sub_participant"=>$p->res_actual_billing_id,
                        "vatable_sales"=>$p->res_vatable_sales,
                        "zero_rated_sales"=>$zero_rated,
                        "rated_sales"=>$p->res_zero_rated_sales,
                        "zero_ecozones_sales"=>$p->res_zero_rated_ecozones,
                        "total_amount"=>$total_amount,
                        "vat_on_sales"=>$p->res_vat_on_sales,
                        "ewt"=>$p->res_ewt,
                        "overall_total"=>$overall_total,
                    );

                    $h=1;
                    foreach($this->super_model->select_custom_where("reserve_subparticipant","res_participant_id = '$participant_id'") AS $sp){
                            $subparticipant=$this->super_model->select_column_where("reserve_participant","res_billing_id","res_participant_id",$sp->sub_participant);
                        foreach($this->super_model->select_custom_where("reserve_sales_transaction_details","res_billing_id = '$subparticipant' AND reserve_sales_id = '$p->reserve_sales_id' AND res_total_amount != '0'") AS $s){
                            if($h<=15){

                                    $vatable_sales=$this->super_model->select_column_where("reserve_sales_transaction_details","res_vatable_sales","reserve_sales_detail_id",$s->reserve_sales_detail_id);
                                    $zero_rated_sales=$this->super_model->select_column_where("reserve_sales_transaction_details","res_zero_rated_sales","reserve_sales_detail_id",$s->reserve_sales_detail_id);
                                    $zero_rated_ecozones=$this->super_model->select_column_where("reserve_sales_transaction_details","res_zero_rated_ecozones","reserve_sales_detail_id",$s->reserve_sales_detail_id);
                                    $vat_on_sales=$this->super_model->select_column_where("reserve_sales_transaction_details","res_vat_on_sales","reserve_sales_detail_id",$s->reserve_sales_detail_id);
                                    $ewt=$this->super_model->select_column_where("reserve_sales_transaction_details","res_ewt","reserve_sales_detail_id",$s->reserve_sales_detail_id);

                                    $zero_rated= $zero_rated_sales + $zero_rated_ecozones;
                                    $total_amount = $vatable_sales + $zero_rated_sales + $zero_rated_ecozones;
                                    $overall_total= ($total_amount + $vat_on_sales) - $ewt;
                                    $data['sub_part'][]=array(
                                        "counter"=>$this->super_model->count_custom_where("reserve_subparticipant","res_participant_id='$participant_id'"),
                                        "counter_h"=>'',
                                        "participant_id"=>$sp->res_participant_id,
                                        "sub_participant"=>$subparticipant,
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
                        }

                        $total_amount = $p->res_vatable_sales + $p->res_zero_rated_sales + $p->res_zero_rated_ecozones;
                        $overall_total= ($total_amount+$p->res_vat_on_sales) - $p->res_ewt;

                        $data['sub_participant'][$x]=$p->res_actual_billing_id;
                        $data['vatable_sales'][$x]=$p->res_vatable_sales;
                        $data['vat_on_sales'][$x]=$p->res_vat_on_sales;
                        $data['zero_rated_sales'][$x]=$zero_rated;
                        $data['total_amount'][$x]=$total_amount;
                        $data['ewt'][$x]=$p->res_ewt;
                        $data['overall_total'][$x]=$overall_total;
                        $data['participant_id'][$x]=$participant_id;

                        $data['sub_second'][]=array(
                            "participant_id"=>$participant_id,
                            "sub_participant"=>$p->res_actual_billing_id,
                            "vatable_sales"=>$p->res_vatable_sales,
                            "zero_rated_sales"=>$p->res_zero_rated_sales,
                            "rated_sales"=>$p->res_zero_rated_sales,
                            "zero_ecozones_sales"=>$p->res_zero_rated_ecozones,
                            "total_amount"=>$total_amount,
                            "vat_on_sales"=>$p->res_vat_on_sales,
                            "ewt"=>$p->res_ewt,
                            "overall_total"=>$overall_total,
                        );

                        $z=1;
                        foreach($this->super_model->select_custom_where("reserve_subparticipant","res_participant_id = '$participant_id'") AS $sp){
                                $subparticipant=$this->super_model->select_column_where("reserve_participant","res_billing_id","res_participant_id",$sp->res_sub_participant);
                        foreach($this->super_model->select_custom_where("reserve_sales_transaction_details","res_billing_id = '$subparticipant' AND reserve_sales_id = '$p->reserve_sales_id' AND total_amount != '0'") AS $s){

                            
                                $vatable_sales=$this->super_model->select_column_where("reserve_sales_transaction_details","res_vatable_sales","reserve_sales_detail_id",$s->reserve_sales_detail_id);
                                $zero_rated_sales=$this->super_model->select_column_where("reserve_sales_transaction_details","res_zero_rated_sales","reserve_sales_detail_id",$s->reserve_sales_detail_id);
                                $zero_rated_ecozones=$this->super_model->select_column_where("reserve_sales_transaction_details","res_zero_rated_ecozones","reserve_sales_detail_id",$s->reserve_sales_detail_id);
                                $vat_on_sales=$this->super_model->select_column_where("reserve_sales_transaction_details","res_vat_on_sales","reserve_sales_detail_id",$s->reserve_sales_detail_id);
                                $ewt=$this->super_model->select_column_where("reserve_sales_transaction_details","ewt","res_reserve_sales_detail_id",$s->reserve_sales_detail_id);
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

                                if($z>=16){
                                $data['sub_part_second'][]=array(
                                    "counter"=>$h,
                                    "counter_h"=>'',
                                    "participant_id"=>$sp->res_participant_id,
                                    "sub_participant"=>$subparticipant,
                                    "vatable_sales"=>$vatable_sales,
                                    "zero_rated_sales"=>$zero_rated,
                                    "rated_sales"=>$zero_rated_sales,
                                    "zero_ecozones_sales"=>$zero_rated_ecozones,
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
        }
        $this->load->view('template/print_head');
        $this->load->view('sales/print_BS_reserve',$data);
    }

    public function print_reserve_invoice_multiple_small(){
        error_reporting(0);
        $reserve_sales_details_id = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $sales_det_exp=explode("-",$reserve_sales_details_id);
        $data['count']=$count;
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        for($x=0;$x<$count;$x++){
            $bs_id[]=$this->super_model->custom_query_single('reserve_sales_detail_id',"SELECT * FROM reserve_sales_transaction_details std  INNER JOIN reserve_bs_head bh ON bh.reserve_sales_detail_id=std.reserve_sales_detail_id WHERE bh.reserve_sales_detail_id='$sales_det_exp[$x]'");
            if(array_key_exists($sales_det_exp[$x],$bs_id)){
                foreach($this->super_model->select_custom_where("reserve_bs_head","reserve_sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                    $data['address'][$x]=$p->res_address;
                    $data['tin'][$x]=$p->res_tin;
                    $data['company_name'][$x]=$p->res_participant_name;
                    $data['billing_from'][$x]=$p->res_billing_from;
                    $data['billing_to'][$x]=$p->res_billing_to;
                    $data['transaction_date'][$x]=$p->res_statement_date;
                    $participant_id = $p->res_participant_id;
                    $data['participant_id'][$x] = $p->res_participant_id;
                }

            $sum_vs_exp = explode(".", $p->res_total_vatable_sales);
            $sum_vatable_sales_peso=$sum_vs_exp[0];
            $sum_vatable_sales_cents=$sum_vs_exp[1];

            $sum_vos_exp=explode(".", $p->res_vat);
            $sum_vat_on_sales_peso=$sum_vos_exp[0];
            $sum_vat_on_sales_cents=$sum_vos_exp[1];

            $sum_e_exp=explode(".", $p->res_total_ewt);
            $sum_ewt_peso=$sum_e_exp[0];
            $sum_ewt_cents=$sum_e_exp[1];

            $sum_zr_exp=explode(".", $p->res_total_zero_rated);
            $sum_zero_rated_peso=$sum_zr_exp[0];
            $sum_zero_rated_cents=$sum_zr_exp[1];

            $total_vs=$p->res_total_vatable_sales;
            $vatable_sales = explode(".",$total_vs);
            $data['vat_sales_peso'][$x] = $vatable_sales[0];
            $data['vat_sales_cents'][$x] = $vatable_sales[1];

            $data['vat_sales_peso_sub'][$x] = 0;
            $data['vat_sales_cents_sub'][$x] = 0;

            $total_zr=$p->res_total_zero_sales;
            $data['total_zr'][$x]=$total_zr;
            $zero_rated_sales = explode(".",$total_zr);
            $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            $data['total_zr_sub'][$x]=0;
            $data['zero_rated_peso_sub'][$x] = 0;
            $data['zero_rated_cents_sub'][$x] = 0;

            $total_zra=$p->res_total_zero_ecozones;
            $data['total_zra'][$x]=$total_zra;
            $zero_rated_ecozones_exp=explode(".", $total_zra);
            $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            $data['total_zra_sub']=0;
            $data['zero_rated_ecozones_peso_sub'][$x]=0;
            $data['zero_rated_ecozones_cents_sub'][$x]=0;

            $total_vos=$p->res_total_vat;
            $vat_on_sales = explode(".",$total_vos);
            $data['vat_peso'][$x] = $vat_on_sales[0];
            $data['vat_cents'][$x] = $vat_on_sales[1];

            $data['vat_peso_sub'][$x] = 0;
            $data['vat_cents_sub'][$x] = 0;

            $total_ewt=$p->res_total_ewt;
            $ewt_exp=explode(".", $total_ewt);
            $data['ewt_peso'][$x]=$ewt_exp[0];
            $data['ewt_cents'][$x]=$ewt_exp[1];

            $data['ewt_peso_sub'][$x]=0;
            $data['ewt_cents_sub'][$x]=0;
            $total= $p->res_total_net_amount;

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
            foreach($this->super_model->select_custom_where("reserve_sales_transaction_details","res_print_identifier='$print_identifier' AND reserve_sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                $data['address'][$x]=$this->super_model->select_column_where("reserve_participant","res_registered_address","res_billing_id",$p->res_billing_id);
                $data['tin'][$x]=$this->super_model->select_column_where("reserve_participant","res_tin","res_billing_id",$p->res_billing_id);
                $data['company_name'][$x]=$p->company_name;
                $data['billing_from'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_from","reserve_sales_id",$p->reserve_sales_id);
                $data['billing_to'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_to","reserve_sales_id",$p->reserve_sales_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_transaction_date","reserve_sales_id",$p->reserve_sales_id);
                $participant_id = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$p->res_billing_id);
                $data['participant_id'][$x] = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$p->billing_id);
                $h=0;
                $vatable_sales_bs=array();
                $vat_on_sales_bs=array();
                $ewt_bs=array();
                $zero_rated_ecozone_bs=array();
                $zero_rated_bs=array();
                foreach($this->super_model->select_custom_where("reserve_subparticipant","res_participant_id='$res_participant_id'") AS $s){
                    $data['participant_id_sub'][$h]=$s->res_participant_id;
                    $billing_id=$this->super_model->select_column_where("reserve_participant","res_billing_id","res_participant_id",$s->res_sub_participant);
                    $vatable_sales_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_vatable_sales","res_serial_no='$p->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_vat_on_sales","res_serial_no='$p->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $ewt_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_ewt","res_serial_no='$p->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_zero_rated_ecozones","res_serial_no='$p->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $zero_rated_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_zero_rated","res_serial_no='$p->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $h++;
                }
            }

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

            $total_vs=$p->res_vatable_sales;
            $vatable_sales = explode(".",$total_vs);
            $data['vat_sales_peso'][$x] = $vatable_sales[0];
            $data['vat_sales_cents'][$x] = $vatable_sales[1];

            $total_vs_sub=$p->res_vatable_sales + $sum_vatable_sales;
            $vatable_sales_sub = explode(".",$total_vs_sub);
            $data['vat_sales_peso_sub'][$x] = $vatable_sales_sub[0];
            $data['vat_sales_cents_sub'][$x] = $vatable_sales_sub[1];

            $total_zr=$p->res_zero_rated_sales;
            $data['total_zr'][$x]=$total_zr;
            $zero_rated_sales = explode(".",$total_zr);
            $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            $total_zr_sub=$p->res_zero_rated_sales + $sum_zero_rated;
            $data['total_zr_sub'][$x]=$total_zr_sub;
            $zero_rated_sales_sub = explode(".",$total_zr_sub);
            $data['zero_rated_peso_sub'][$x] = $zero_rated_sales_sub[0];
            $data['zero_rated_cents_sub'][$x] = $zero_rated_sales_sub[1];

            $total_zra=$p->res_zero_rated_ecozones;
            $data['total_zra'][$x]=$total_zra;
            $zero_rated_ecozones_exp=explode(".", $total_zra);
            $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            $total_zra_sub=$p->res_zero_rated_ecozones + $sum_zero_rated_ecozone;
            $data['total_zra_sub']=$total_zra_sub;
            $zero_rated_ecozones_exp_sub=explode(".", $total_zra_sub);
            $data['zero_rated_ecozones_peso_sub'][$x]=$zero_rated_ecozones_exp_sub[0];
            $data['zero_rated_ecozones_cents_sub'][$x]=$zero_rated_ecozones_exp_sub[1];

            $total_vos=$p->res_vat_on_sales;
            $vat_on_sales = explode(".",$total_vos);
            $data['vat_peso'][$x] = $vat_on_sales[0];
            $data['vat_cents'][$x] = $vat_on_sales[1];

            $total_vos_sub=$p->res_vat_on_sales + $sum_vat_on_sales;
            $vat_on_sales_sub = explode(".",$total_vos_sub);
            $data['vat_peso_sub'][$x] = $vat_on_sales_sub[0];
            $data['vat_cents_sub'][$x] = $vat_on_sales_sub[1];

            $total_ewt=$p->res_ewt;
            $ewt_exp=explode(".", $total_ewt);
            $data['ewt_peso'][$x]=$ewt_exp[0];
            $data['ewt_cents'][$x]=$ewt_exp[1];

            $total_ewt_sub=$p->res_ewt + $sum_ewt;
            $ewt_exp_sub=explode(".", $total_ewt_sub);
            $data['ewt_peso_sub'][$x]=$ewt_exp_sub[0];
            $data['ewt_cents_sub'][$x]=$ewt_exp_sub[1];
            $total= ($p->res_vatable_sales + $p->res_vat_on_sales + $p->res_zero_rated_ecozones + $p->res_zero_rated_sales) - $p->res_ewt;

            
            $total_sub= ($sum_vatable_sales + $sum_vat_on_sales + $sum_zero_rated_ecozone + $sum_zero_rated) - $sum_ewt;
            $total_amount=str_replace(',','',number_format($total,2));
           
            $total_amount_sub=$total + $total_sub;
            $data['total_amount'][$x]=$total_amount;
            $data['total_amount_sub'][$x]=$total_amount_sub;
            $data['amount_words'][$x]=strtoupper($this->convertNumber($total_amount));
            $data['amount_words_sub'][$x]=strtoupper($this->convertNumber(str_replace(',','',number_format($total_amount_sub,2))));
            $total_exp=explode(".", $total_amount);
            $data['total_peso'][$x]=$total_exp[0];
            $data['total_cents'][$x]=$total_exp[1];

            $total_exp_sub=explode(".", $total_amount_sub);
            $data['total_peso_sub'][$x]=$total_exp_sub[0];
            $data['total_cents_sub'][$x]=$total_exp_sub[1];
            }
        }
        $this->load->view('template/print_head');
        $this->load->view('sales/print_reserve_invoice_multiple_small',$data);
    }

     public function print_reserve_invoice_multiple_half(){
        error_reporting(0);
        $reserve_sales_details_id = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $sales_det_exp=explode("-",$reserve_sales_details_id);
        $data['count']=$count;
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        for($x=0;$x<$count;$x++){
            $bs_id[]=$this->super_model->custom_query_single('reserve_sales_detail_id',"SELECT * FROM reserve_sales_transaction_details std  INNER JOIN reserve_bs_head bh ON bh.reserve_sales_detail_id=std.reserve_sales_detail_id WHERE bh.reserve_sales_detail_id='$sales_det_exp[$x]'");
            if(array_key_exists($sales_det_exp[$x],$bs_id)){
                foreach($this->super_model->select_custom_where("reserve_bs_head","reserve_sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                    $data['address'][$x]=$p->res_address;
                    $data['tin'][$x]=$p->res_tin;
                    $data['company_name'][$x]=$p->res_participant_name;
                    $data['billing_from'][$x]=$p->res_billing_from;
                    $data['billing_to'][$x]=$p->res_billing_to;
                    $data['transaction_date'][$x]=$p->res_statement_date;
                    $participant_id = $p->res_participant_id;
                    $data['participant_id'][$x] = $p->res_participant_id;
                }

            $sum_vs_exp = explode(".", $p->res_total_vatable_sales);
            $sum_vatable_sales_peso=$sum_vs_exp[0];
            $sum_vatable_sales_cents=$sum_vs_exp[1];

            $sum_vos_exp=explode(".", $p->res_vat);
            $sum_vat_on_sales_peso=$sum_vos_exp[0];
            $sum_vat_on_sales_cents=$sum_vos_exp[1];

            $sum_e_exp=explode(".", $p->res_total_ewt);
            $sum_ewt_peso=$sum_e_exp[0];
            $sum_ewt_cents=$sum_e_exp[1];

            $sum_zr_exp=explode(".", $p->res_total_zero_rated);
            $sum_zero_rated_peso=$sum_zr_exp[0];
            $sum_zero_rated_cents=$sum_zr_exp[1];

            $total_vs=$p->res_total_vatable_sales;
            $vatable_sales = explode(".",$total_vs);
            $data['vat_sales_peso'][$x] = $vatable_sales[0];
            $data['vat_sales_cents'][$x] = $vatable_sales[1];

            $data['vat_sales_peso_sub'][$x] = 0;
            $data['vat_sales_cents_sub'][$x] = 0;

            $total_zr=$p->res_total_zero_sales;
            $data['total_zr'][$x]=$total_zr;
            $zero_rated_sales = explode(".",$total_zr);
            $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            $data['total_zr_sub'][$x]=0;
            $data['zero_rated_peso_sub'][$x] = 0;
            $data['zero_rated_cents_sub'][$x] = 0;

            $total_zra=$p->res_total_zero_ecozones;
            $data['total_zra'][$x]=$total_zra;
            $zero_rated_ecozones_exp=explode(".", $total_zra);
            $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            $data['total_zra_sub']=0;
            $data['zero_rated_ecozones_peso_sub'][$x]=0;
            $data['zero_rated_ecozones_cents_sub'][$x]=0;

            $total_vos=$p->res_total_vat;
            $vat_on_sales = explode(".",$total_vos);
            $data['vat_peso'][$x] = $vat_on_sales[0];
            $data['vat_cents'][$x] = $vat_on_sales[1];

            $data['vat_peso_sub'][$x] = 0;
            $data['vat_cents_sub'][$x] = 0;

            $total_ewt=$p->res_total_ewt;
            $ewt_exp=explode(".", $total_ewt);
            $data['ewt_peso'][$x]=$ewt_exp[0];
            $data['ewt_cents'][$x]=$ewt_exp[1];

            $data['ewt_peso_sub'][$x]=0;
            $data['ewt_cents_sub'][$x]=0;
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
            foreach($this->super_model->select_custom_where("reserve_sales_transaction_details","res_print_identifier='$print_identifier' AND reserve_sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                $data['address'][$x]=$this->super_model->select_column_where("reserve_participant","res_registered_address","res_billing_id",$p->res_billing_id);
                $data['tin'][$x]=$this->super_model->select_column_where("reserve_participant","res_tin","res_billing_id",$p->res_billing_id);
                $data['company_name'][$x]=$p->company_name;
                $data['billing_from'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_from","reserve_sales_id",$p->reserve_sales_id);
                $data['billing_to'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_to","reserve_sales_id",$p->reserve_sales_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_transaction_date","reserve_sales_id",$p->reserve_sales_id);
                $participant_id = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$p->res_billing_id);
                $data['participant_id'][$x] = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$p->billing_id);
                $h=0;
                $vatable_sales_bs=array();
                $vat_on_sales_bs=array();
                $ewt_bs=array();
                $zero_rated_ecozone_bs=array();
                $zero_rated_bs=array();
                foreach($this->super_model->select_custom_where("reserve_subparticipant","res_participant_id='$res_participant_id'") AS $s){
                    $data['participant_id_sub'][$h]=$s->res_participant_id;
                    $billing_id=$this->super_model->select_column_where("reserve_participant","res_billing_id","res_participant_id",$s->res_sub_participant);
                    $vatable_sales_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_vatable_sales","res_serial_no='$p->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_vat_on_sales","res_serial_no='$p->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $ewt_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_ewt","res_serial_no='$p->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_zero_rated_ecozones","res_serial_no='$p->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $zero_rated_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_zero_rated","res_serial_no='$p->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $h++;
                }
            }

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

            $total_vs=$p->res_vatable_sales;
            $vatable_sales = explode(".",$total_vs);
            $data['vat_sales_peso'][$x] = $vatable_sales[0];
            $data['vat_sales_cents'][$x] = $vatable_sales[1];

            $total_vs_sub=$p->res_vatable_sales + $sum_vatable_sales;
            $vatable_sales_sub = explode(".",$total_vs_sub);
            $data['vat_sales_peso_sub'][$x] = $vatable_sales_sub[0];
            $data['vat_sales_cents_sub'][$x] = $vatable_sales_sub[1];

            $total_zr=$p->res_zero_rated_sales;
            $data['total_zr'][$x]=$total_zr;
            $zero_rated_sales = explode(".",$total_zr);
            $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            $total_zr_sub=$p->res_zero_rated_sales + $sum_zero_rated;
            $data['total_zr_sub'][$x]=$total_zr_sub;
            $zero_rated_sales_sub = explode(".",$total_zr_sub);
            $data['zero_rated_peso_sub'][$x] = $zero_rated_sales_sub[0];
            $data['zero_rated_cents_sub'][$x] = $zero_rated_sales_sub[1];

            $total_zra=$p->res_zero_rated_ecozones;
            $data['total_zra'][$x]=$total_zra;
            $zero_rated_ecozones_exp=explode(".", $total_zra);
            $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            $total_zra_sub=$p->res_zero_rated_ecozones + $sum_zero_rated_ecozone;
            $data['total_zra_sub']=$total_zra_sub;
            $zero_rated_ecozones_exp_sub=explode(".", $total_zra_sub);
            $data['zero_rated_ecozones_peso_sub'][$x]=$zero_rated_ecozones_exp_sub[0];
            $data['zero_rated_ecozones_cents_sub'][$x]=$zero_rated_ecozones_exp_sub[1];

            $total_vos=$p->res_vat_on_sales;
            $vat_on_sales = explode(".",$total_vos);
            $data['vat_peso'][$x] = $vat_on_sales[0];
            $data['vat_cents'][$x] = $vat_on_sales[1];

            $total_vos_sub=$p->res_vat_on_sales + $sum_vat_on_sales;
            $vat_on_sales_sub = explode(".",$total_vos_sub);
            $data['vat_peso_sub'][$x] = $vat_on_sales_sub[0];
            $data['vat_cents_sub'][$x] = $vat_on_sales_sub[1];

            $total_ewt=$p->res_ewt;
            $ewt_exp=explode(".", $total_ewt);
            $data['ewt_peso'][$x]=$ewt_exp[0];
            $data['ewt_cents'][$x]=$ewt_exp[1];

            $total_ewt_sub=$p->res_ewt + $sum_ewt;
            $ewt_exp_sub=explode(".", $total_ewt_sub);
            $data['ewt_peso_sub'][$x]=$ewt_exp_sub[0];
            $data['ewt_cents_sub'][$x]=$ewt_exp_sub[1];
            $total= ($p->res_vatable_sales + $p->res_vat_on_sales + $p->res_zero_rated_ecozones + $p->res_zero_rated_sales) - $p->res_ewt;

            
            $total_sub= ($sum_vatable_sales + $sum_vat_on_sales + $sum_zero_rated_ecozone + $sum_zero_rated) - $sum_ewt;
            $total_amount=str_replace(',','',number_format($total,2));
           
            $total_amount_sub=$total + $total_sub;
            $data['total_amount'][$x]=$total_amount;
            $data['total_amount_sub'][$x]=$total_amount_sub;
            $data['amount_words'][$x]=strtoupper($this->convertNumber($total_amount));
            $data['amount_words_sub'][$x]=strtoupper($this->convertNumber(str_replace(',','',number_format($total_amount_sub,2))));
            $total_exp=explode(".", $total_amount);
            $data['total_peso'][$x]=$total_exp[0];
            $data['total_cents'][$x]=$total_exp[1];

            $total_exp_sub=explode(".", $total_amount_sub);
            $data['total_peso_sub'][$x]=$total_exp_sub[0];
            $data['total_cents_sub'][$x]=$total_exp_sub[1];
            }
        }
        $this->load->view('template/print_head');
        $this->load->view('sales/print_reserve_invoice_multiple_half',$data);
    }

    public function print_reserve_invoice_multiple_main(){
        error_reporting(0);
        $sales_details_id = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $sales_det_exp=explode("-",$sales_details_id);
        $data['count']=$count;
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        for($x=0;$x<$count;$x++){
            $bs_id[]=$this->super_model->custom_query_single('reserve_sales_detail_id',"SELECT * FROM reserve_sales_transaction_details std  INNER JOIN reserve_bs_head bh ON bh.reserve_sales_detail_id=std.reserve_sales_detail_id WHERE bh.reserve_sales_detail_id='$sales_det_exp[$x]'");
            if(in_array($sales_det_exp[$x],$bs_id)){
                foreach($this->super_model->select_custom_where("reserve_bs_head","reserve_sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                    $data['address'][$x]=$p->res_address;
                    $data['tin'][$x]=$p->res_tin;
                    $data['company_name'][$x]=$p->res_participant_name;
                    $data['billing_from'][$x]=$p->res_billing_from;
                    $data['billing_to'][$x]=$p->res_billing_to;
                    $data['transaction_date'][$x]=$p->res_statement_date;
                    $participant_id = $p->res_participant_id;
                    $data['participant_id'][$x] = $p->res_participant_id;
                    $data['reference_number'][$x] = $this->super_model->select_column_where("reserve_bs_head","res_reference_number","reserve_bs_head_id",$p->reserve_bs_head_id);
                    $data['or_no'][$x]=$p->res_invoice_no;

                    $data['total_vs'][$x]= $p->res_total_vatable_sales;
                    $data['total_zr'][$x]=$p->res_total_zero_sales;
                    $data['total_zra'][$x]= $p->res_total_zero_ecozones;
                    $data['total_vos'][$x]=$p->res_total_vat;
                    $data['total_ewt'][$x]= $p->res_total_ewt;
                }

            }else{
            foreach($this->super_model->select_custom_where("reserve_sales_transaction_details","res_print_identifier='$print_identifier' AND reserve_sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                $data['address'][$x]=$this->super_model->select_column_where("reserve_participant","res_registered_address","res_billing_id",$p->res_billing_id);
                $data['tin'][$x]=$this->super_model->select_column_where("reserve_participant","res_tin","res_billing_id",$p->res_billing_id);
                $data['billing_from'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_from","reserve_sales_id",$p->reserve_sales_id);
                $data['billing_to'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_to","reserve_sales_id",$p->reserve_sales_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_transaction_date","reserve_sales_id",$p->reserve_sales_id);
                $data['reference_number'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_reference_number","reserve_sales_id",$p->reserve_sales_id);
                $data['or_no'][$x]=$p->res_serial_no;
                $participant_id = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$p->res_billing_id);
                $data['participant_id'][$x] = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$p->res_billing_id);

                $create_date = $this->super_model->select_column_where("reserve_sales_transaction_head", "res_create_date", "reserve_sales_id", $p->reserve_sales_id);
                if(!empty($p->company_name)){
                    $data['company_name'][$x]=$p->res_company_name;
                }else{
                    $data['company_name'][$x]=$this->super_model->select_column_where("reserve_participant", "res_participant_name", "res_billing_id", $p->res_billing_id);
                }
                $h=0;
                $vatable_sales_bs=array();
                $vat_on_sales_bs=array();
                $ewt_bs=array();
                $zero_rated_ecozone_bs=array();
                $zero_rated_bs=array();
                foreach($this->super_model->select_custom_where("reserve_subparticipant","res_participant_id='$res_participant_id'") AS $s){
                    $data['participant_id_sub'][$h]=$s->res_participant_id;
                    $billing_id=$this->super_model->select_column_where("reserve_participant","res_billing_id","res_participant_id",$s->res_sub_participant);
                    $vatable_sales_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_vatable_sales","res_serial_no='$p->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_vat_on_sales","res_serial_no='$p->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $ewt_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_ewt","res_serial_no='$p->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_zero_rated_ecozones","res_serial_no='$p->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $zero_rated_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_zero_rated","res_serial_no='$p->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $h++;
                }

                $vatable_sales_bs_parent=$this->super_model->select_sum_where("reserve_sales_transaction_details","res_vatable_sales","res_billing_id='$p->res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                $vat_on_sales_bs_parent=$this->super_model->select_sum_where("reserve_sales_transaction_details","res_vat_on_sales","res_billing_id='$p->res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                $zero_rated_ecozone_bs_parent=$this->super_model->select_sum_where("reserve_sales_transaction_details","res_zero_rated_ecozones","res_billing_id='$p->res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                $zero_rated_bs_parent=$this->super_model->select_sum_where("reserve_sales_transaction_details","res_zero_rated","res_billing_id='$p->res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                $ewt_bs_parent=$this->super_model->select_sum_where("reserve_sales_transaction_details","res_ewt","res_billing_id='$p->res_billing_id' AND reserve_sales_id='$p->reserve_sales_id'");

            }

            $sum_vatable_sales=array_sum($vatable_sales_bs) + $vatable_sales_bs_parent;
            $sum_vs_exp = explode(".", $sum_vatable_sales);
            $sum_vatable_sales_peso=$sum_vs_exp[0];
            $sum_vatable_sales_cents=$sum_vs_exp[1];

            $sum_zero_rated_ecozone=array_sum($zero_rated_ecozone_bs) + $zero_rated_ecozone_bs_parent;
            $sum_zre_exp=explode(".", $sum_zero_rated_ecozone);
            $sum_zero_rated_ecozone_peso=$sum_zre_exp[0];
            $sum_zero_rated_ecozone_cents=$sum_zre_exp[1];

            $sum_vat_on_sales=array_sum($vat_on_sales_bs) + $vat_on_sales_bs_parent;
            $sum_vos_exp=explode(".", $sum_vat_on_sales);
            $sum_vat_on_sales_peso=$sum_vos_exp[0];
            $sum_vat_on_sales_cents=$sum_vos_exp[1];

            $sum_ewt=array_sum($ewt_bs)  + $ewt_bs_parent;
            $sum_e_exp=explode(".", $sum_ewt);
            $sum_ewt_peso=$sum_e_exp[0];
            $sum_ewt_cents=$sum_e_exp[1];

            $sum_zero_rated=array_sum($zero_rated_bs) + $zero_rated_bs_parent;
            $sum_zr_exp=explode(".", $sum_zero_rated);
            $sum_zero_rated_peso=$sum_zr_exp[0];
            $sum_zero_rated_cents=$sum_zr_exp[1];

            $ewt=str_replace("-", '', $p->res_ewt);
            
            $data['total_vs'][$x]= $sum_vatable_sales;
            $data['total_zr'][$x]=$sum_zero_rated;
            $data['total_zra'][$x]= $sum_zero_rated_ecozone;
            $data['total_vos'][$x]=$sum_vat_on_sales;
            $data['total_ewt'][$x]= $sum_ewt;
            }
        }
        $this->load->view('template/print_head');
        $this->load->view('sales/print_reserve_invoice_multiple_main',$data);
    }

        public function print_reserve_invoice_multiple(){
        error_reporting(0);
        $sales_details_id = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $sales_det_exp=explode("-",$sales_details_id);
        $data['count']=$count;
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        for($x=0;$x<$count;$x++){
             $bs_id[]=$this->super_model->custom_query_single('reserve_sales_detail_id',"SELECT * FROM reserve_sales_transaction_details std  INNER JOIN reserve_bs_head bh ON bh.reserve_sales_detail_id=std.reserve_sales_detail_id WHERE bh.reserve_sales_detail_id='$sales_det_exp[$x]'");
            if(array_key_exists($sales_det_exp[$x],$bs_id)){
                foreach($this->super_model->select_custom_where("reserve_bs_head","reserve_sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                    $data['address'][$x]=$p->res_address;
                    $data['tin'][$x]=$p->res_tin;
                    $data['company_name'][$x]=$p->res_participant_name;
                    $data['billing_from'][$x]=$p->res_billing_from;
                    $data['billing_to'][$x]=$p->res_billing_to;
                    $data['transaction_date'][$x]=$p->res_statement_date;
                    $participant_id = $p->res_participant_id;
                    $data['participant_id'][$x] = $p->res_participant_id;
                }

            $sum_vs_exp = explode(".", $p->res_total_vatable_sales);
            $sum_vatable_sales_peso=$sum_vs_exp[0];
            $sum_vatable_sales_cents=$sum_vs_exp[1];

            $sum_vos_exp=explode(".", $p->res_vat);
            $sum_vat_on_sales_peso=$sum_vos_exp[0];
            $sum_vat_on_sales_cents=$sum_vos_exp[1];

            $sum_e_exp=explode(".", $p->res_total_ewt);
            $sum_ewt_peso=$sum_e_exp[0];
            $sum_ewt_cents=$sum_e_exp[1];

            $sum_zr_exp=explode(".", $p->res_total_zero_rated);
            $sum_zero_rated_peso=$sum_zr_exp[0];
            $sum_zero_rated_cents=$sum_zr_exp[1];

            $total_vs=$p->res_total_vatable_sales;
            $vatable_sales = explode(".",$total_vs);
            $data['vat_sales_peso'][$x] = $vatable_sales[0];
            $data['vat_sales_cents'][$x] = $vatable_sales[1];

            $data['vat_sales_peso_sub'][$x] = 0;
            $data['vat_sales_cents_sub'][$x] = 0;

            $total_zr=$p->res_total_zero_sales;
            $data['total_zr'][$x]=$total_zr;
            $zero_rated_sales = explode(".",$total_zr);
            $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            $data['total_zr_sub'][$x]=0;
            $data['zero_rated_peso_sub'][$x] = 0;
            $data['zero_rated_cents_sub'][$x] = 0;

            $total_zra=$p->res_total_zero_ecozones;
            $data['total_zra'][$x]=$total_zra;
            $zero_rated_ecozones_exp=explode(".", $total_zra);
            $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            $data['total_zra_sub']=0;
            $data['zero_rated_ecozones_peso_sub'][$x]=0;
            $data['zero_rated_ecozones_cents_sub'][$x]=0;

            $total_vos=$p->res_total_vat;
            $vat_on_sales = explode(".",$total_vos);
            $data['vat_peso'][$x] = $vat_on_sales[0];
            $data['vat_cents'][$x] = $vat_on_sales[1];

            $data['vat_peso_sub'][$x] = 0;
            $data['vat_cents_sub'][$x] = 0;

            $total_ewt=$p->res_total_ewt;
            $ewt_exp=explode(".", $total_ewt);
            $data['ewt_peso'][$x]=$ewt_exp[0];
            $data['ewt_cents'][$x]=$ewt_exp[1];

            $data['ewt_peso_sub'][$x]=0;
            $data['ewt_cents_sub'][$x]=0;
            $total= $p->res_total_net_amount;

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
            $vatable_sales_bs=array();
            $vat_on_sales_bs=array();
            $ewt_bs=array();
            $zero_rated_ecozone_bs=array();
            $zero_rated_bs=array();
            foreach($this->super_model->select_custom_where("reserve_sales_transaction_details","res_print_identifier='$print_identifier' AND reserve_sales_detail_id='".$sales_det_exp[$x]."'") AS $p){
                $data['address'][$x]=$this->super_model->select_column_where("reserve_participant","res_registered_address","res_billing_id",$p->res_billing_id);
                $data['tin'][$x]=$this->super_model->select_column_where("reserve_participant","res_tin","res_billing_id",$p->res_billing_id);
                $data['company_name'][$x]=$p->res_company_name;
                $data['billing_from'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_from","reserve_sales_id",$p->reserve_sales_id);
                $data['billing_to'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_to","reserve_sales_id",$p->reserve_sales_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("reserve_sales_transaction_head","res_transaction_date","reserve_sales_id",$p->reserve_sales_id);
                $participant_id = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$p->res_billing_id);
                $data['participant_id'][$x] = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$p->res_billing_id);
                $h=0;
                foreach($this->super_model->select_custom_where("reserve_subparticipant","res_participant_id='$res_participant_id'") AS $s){
                    $data['participant_id_sub'][$h]=$s->participant_id;
                    $billing_id=$this->super_model->select_column_where("reserve_participant","res_billing_id","res_participant_id",$s->res_sub_participant);
                    $vatable_sales_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_vatable_sales","res_billing_id='$billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_vat_on_sales","res_billing_id='$billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $ewt_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_ewt","res_billing_id='$billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_zero_rated_ecozones","res_billing_id='$billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $zero_rated_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_zero_rated","res_billing_id='$billing_id' AND reserve_sales_id='$p->reserve_sales_id'");
                    $h++;
                }
            }

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

            $total_vs=$p->res_vatable_sales;
            $vatable_sales = explode(".",$total_vs);
            $data['vat_sales_peso'][$x] = $vatable_sales[0];
            $data['vat_sales_cents'][$x] = $vatable_sales[1];

            $total_vs_sub=$p->res_vatable_sales + $sum_vatable_sales;
            $vatable_sales_sub = explode(".",$total_vs_sub);
            $data['vat_sales_peso_sub'][$x] = $vatable_sales_sub[0];
            $data['vat_sales_cents_sub'][$x] = $vatable_sales_sub[1];

            $total_zr=$p->res_zero_rated_sales;
            $data['total_zr'][$x]=$total_zr;
            $zero_rated_sales = explode(".",$total_zr);
            $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            $total_zr_sub=$p->res_zero_rated_sales + $sum_zero_rated;
            $data['total_zr_sub'][$x]=$total_zr_sub;
            $zero_rated_sales_sub = explode(".",$total_zr_sub);
            $data['zero_rated_peso_sub'][$x] = $zero_rated_sales_sub[0];
            $data['zero_rated_cents_sub'][$x] = $zero_rated_sales_sub[1];

            $total_zra=$p->res_zero_rated_ecozones;
            $data['total_zra'][$x]=$total_zra;
            $zero_rated_ecozones_exp=explode(".", $total_zra);
            $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            $total_zra_sub=$p->res_zero_rated_ecozones + $sum_zero_rated_ecozone;
            $data['total_zra_sub']=$total_zra_sub;
            $zero_rated_ecozones_exp_sub=explode(".", $total_zra_sub);
            $data['zero_rated_ecozones_peso_sub'][$x]=$zero_rated_ecozones_exp_sub[0];
            $data['zero_rated_ecozones_cents_sub'][$x]=$zero_rated_ecozones_exp_sub[1];

            $total_vos=$p->res_vat_on_sales;
            $vat_on_sales = explode(".",$total_vos);
            $data['vat_peso'][$x] = $vat_on_sales[0];
            $data['vat_cents'][$x] = $vat_on_sales[1];

            $total_vos_sub=$p->res_vat_on_sales + $sum_vat_on_sales;
            $vat_on_sales_sub = explode(".",$total_vos_sub);
            $data['vat_peso_sub'][$x] = $vat_on_sales_sub[0];
            $data['vat_cents_sub'][$x] = $vat_on_sales_sub[1];

            $total_ewt=$p->res_ewt;
            $ewt_exp=explode(".", $total_ewt);
            $data['ewt_peso'][$x]=$ewt_exp[0];
            $data['ewt_cents'][$x]=$ewt_exp[1];

            $total_ewt_sub=$p->res_ewt + $sum_ewt;
            $ewt_exp_sub=explode(".", $total_ewt_sub);
            $data['ewt_peso_sub'][$x]=$ewt_exp_sub[0];
            $data['ewt_cents_sub'][$x]=$ewt_exp_sub[1];
            $total= ($p->res_vatable_sales + $p->res_vat_on_sales + $p->res_zero_rated_ecozones + $p->res_zero_rated_sales) - $p->ewt;

            $total_sub= ($sum_vatable_sales + $sum_vat_on_sales + $sum_zero_rated_ecozone + $sum_zero_rated) - $sum_ewt;
            $total_amount=str_replace(',','',number_format($total,2));
           
            $total_amount_sub=$total + $total_sub;
            $data['total_amount'][$x]=$total_amount;
            $data['total_amount_sub'][$x]=$total_amount_sub;
            $data['amount_words'][$x]=strtoupper($this->convertNumber($total_amount));
            $data['amount_words_sub'][$x]=strtoupper($this->convertNumber(str_replace(',','',number_format($total_amount_sub,2))));
            $total_exp=explode(".", $total_amount);
            $data['total_peso'][$x]=$total_exp[0];
            $data['total_cents'][$x]=$total_exp[1];

            $total_exp_sub=explode(".", $total_amount_sub);
            $data['total_peso_sub'][$x]=$total_exp_sub[0];
            $data['total_cents_sub'][$x]=$total_exp_sub[1];
            }
        }
        $this->load->view('template/print_head');
        $this->load->view('sales/print_reserve_invoice_multiple',$data);
    }

    public function add_reserve_sales_head(){
        $res_tdate=date("Y-m-d", strtotime($this->input->post('res_transaction_date')));
        $res_billingf=date("Y-m-d", strtotime($this->input->post('res_billing_from')));
        $res_billingt=date("Y-m-d", strtotime($this->input->post('res_billing_to')));
        $res_due=date("Y-m-d", strtotime($this->input->post('res_due_date')));
        $data=array(
            "res_reference_number"=>$this->input->post('res_reference_number'),
            "res_transaction_date"=>$res_tdate,
            "res_billing_from"=>$res_billingf,
            "res_billing_to"=>$res_billingt,
            "res_due_date"=>$res_due,
            "res_user_id"=>$_SESSION['user_id'],
            "res_create_date"=>date("Y-m-d H:i:s"),

        );
        $reserve_sales_id = $this->super_model->insert_return_id("reserve_sales_transaction_head",$data);

        echo $reserve_sales_id;
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

        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();

        $inputFileName =realpath(APPPATH.'../uploads/excel/wesm_sales.xlsx');

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

            // $total_amount = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('O'.$x)->getOldCalculatedValue());
            $total_amount = ((double)$vatable_sales+(double)$zero_rated_ecozone+(double)$vat_on_sales)-(double)$ewt;

            $series_no = $objPHPExcel->getActiveSheet()->getCell('P'.$x)->getFormattedValue();
            

            // $cellDataType = $objPHPExcel->getActiveSheet()->getCell('K'.$x)->getDataType();
            // if ($cellDataType === \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NULL) {
            //     echo "This cell contains a formula.-".$actual_billing_id.'*';
            // }
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
                $this->super_model->delete_where('sales_transaction_details', 'sales_id', $sales_id);
                break;
            }else{
                $data_sales = array(
                    'sales_id'=>$sales_id,
                    'item_no'=>$y,
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
                $this->super_model->insert_into("sales_transaction_details", $data_sales);
                $y++;
            }
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
            
            //dri asta
         */
                // $data_sales = array(
                //     'sales_id'=>$sales_id,
                //     'item_no'=>$y,
                //     'short_name'=>$shortname,
                //     'billing_id'=>$unique_bill_id,
                //     'actual_billing_id'=>$actual_billing_id,
                //     'company_name'=>$company_name,
                //     'facility_type'=>$fac_type,
                //     'wht_agent'=>$wht_agent,
                //     'ith_tag'=>$ith,
                //     'non_vatable'=>$non_vatable,
                //     'zero_rated'=>$zero_rated,
                //     'vatable_sales'=>$vatable_sales,
                //     'vat_on_sales'=>$vat_on_sales,
                //     'serial_no'=>$series_no,
                //     'zero_rated_ecozones'=>$zero_rated_ecozone,
                //     'ewt'=>$ewt,
                //     'total_amount'=>$total_amount,
                //     'balance'=>$total_amount
                // );
                // $this->super_model->insert_into("sales_transaction_details", $data_sales);
                
            }
        }
            //echo $sales_id;
      
    }

    public function cancel_sales(){
        $sales_id = $this->input->post('sales_id');
        $this->super_model->delete_where("sales_transaction_details", "sales_id", $sales_id);
        $this->super_model->delete_where("sales_transaction_head", "sales_id", $sales_id);
    }

        public function upload_reserve_sales_process(){
        $res_sales_id = $this->input->post('reserve_sales_id');
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
                $filename1='wesm_reserve_sales.'.$ext1;
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                    $this->readExcel_ressales($res_sales_id);
                } 
            }
        }
    }

    public function readExcel_ressales($res_sales_id){
        $objPHPExcel = new Spreadsheet();
        $inputFileName =realpath(APPPATH.'../uploads/excel/wesm_reserve_sales.xlsx');

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
                $company_name =trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getFormattedValue());
                $tin = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue());
                $fac_type = trim($objPHPExcel->getActiveSheet()->getCell('F'.$x)->getFormattedValue());

                $unique_bill_id = $this->super_model->select_column_custom_where("reserve_participant", "res_billing_id", "res_actual_billing_id = '$actual_billing_id' AND res_settlement_id = '$shortname' AND res_tin = '$tin'");
            
                $wht_agent = trim($objPHPExcel->getActiveSheet()->getCell('G'.$x)->getFormattedValue());
                $ith = trim($objPHPExcel->getActiveSheet()->getCell('H'.$x)->getFormattedValue());
                $non_vatable = trim($objPHPExcel->getActiveSheet()->getCell('I'.$x)->getFormattedValue());
                $zero_rated = trim($objPHPExcel->getActiveSheet()->getCell('J'.$x)->getFormattedValue());
                $vatable_sales = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('K'.$x)->getFormattedValue());
                $zero_rated_sales = $objPHPExcel->getActiveSheet()->getCell('L'.$x)->getFormattedValue();
                $zero_rated_ecozone = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('M'.$x)->getFormattedValue());
                $vat_on_sales = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('N'.$x)->getFormattedValue());
                $ewt = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('O'.$x)->getFormattedValue());
                // $total_amount = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('P'.$x)->getOldCalculatedValue());
                // $total_amount = ($vatable_sales+$zero_rated_sales+$zero_rated_ecozone+$vat_on_sales)-$ewt;
                $total_amount = ((double)$vatable_sales+(double)$zero_rated_sales+(double)$zero_rated_ecozone+(double)$vat_on_sales)-(double)$ewt;
                $series_no = $objPHPExcel->getActiveSheet()->getCell('Q'.$x)->getFormattedValue();
                $data_sales = array(
                    'reserve_sales_id'=>$res_sales_id,
                    'res_item_no'=>$y,
                    'res_short_name'=>$shortname,
                    'res_billing_id'=>$unique_bill_id,
                    'res_actual_billing_id'=>$actual_billing_id,
                    'res_company_name'=>$company_name,
                    'res_facility_type'=>$fac_type,
                    'res_wht_agent'=>$wht_agent,
                    'res_ith_tag'=>$ith,
                    'res_non_vatable'=>$non_vatable,
                    'res_zero_rated'=>$zero_rated,
                    'res_vatable_sales'=>$vatable_sales,
                    'res_vat_on_sales'=>$vat_on_sales,
                    'res_serial_no'=>$series_no,
                    'res_zero_rated_ecozones'=>$zero_rated_ecozone,
                    'res_ewt'=>$ewt,
                    'res_total_amount'=>$total_amount,
                    'res_balance'=>$total_amount
                );
                $this->super_model->insert_into("reserve_sales_transaction_details", $data_sales);
                $y++;
                
            }
        }
      
    }

        public function cancel_reserve_sales(){
        $reserve_sales_id = $this->input->post('reserve_sales_id');
        $this->super_model->delete_where("reserve_sales_transaction_details", "reserve_sales_id", $reserve_sales_id);
        $this->super_model->delete_where("reserve_sales_transaction_head", "reserve_sales_id", $reserve_sales_id);
    }

    public function save_all(){
        $sales_id = $this->input->post('sales_id');
        $data_head = array(
            'saved'=>1
        );
        $this->super_model->update_where("sales_transaction_head",$data_head, "sales_id", $sales_id);
        echo $sales_id;
    }

        public function save_all_reserve(){
        $reserve_sales_id = $this->input->post('reserve_sales_id');
        $data_head = array(
            'res_saved'=>1
        );
        $this->super_model->update_where("reserve_sales_transaction_head",$data_head, "reserve_sales_id", $reserve_sales_id);
        echo $reserve_sales_id;
    }

    public function collection_list()
    {
        $date=$this->uri->segment(3);
        $ref_no=$this->uri->segment(4);
        $buyer_fullname = urldecode($this->uri->segment(5) ?? '');
        $data['date'] = $date;
        $data['ref_no'] = $ref_no;
        $data['buyer_fullname'] = $buyer_fullname;
        $data['collection_date'] = $this->super_model->custom_query("SELECT DISTINCT collection_date FROM collection_head WHERE saved != '0'");
        $data['reference_no'] = $this->super_model->custom_query("SELECT DISTINCT reference_no FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE reference_no!='' AND saved != '0'");
        $data['buyer'] = $this->super_model->custom_query("SELECT DISTINCT settlement_id,buyer_fullname FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE reference_no!='' AND saved != '0' GROUP BY buyer_fullname");
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
        // if($stl_id!='null'){
        //      $sql.= "cd.settlement_id = '$stl_id' AND "; 
        // }

        $query=substr($sql,0,-4);
        $qu = "saved = '1' AND ".$query;
        $data['collection']=array();

            foreach($this->super_model->custom_query("SELECT * FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE $qu") AS $col){
            $count_series=$this->super_model->count_custom_where("collection_details","series_number='$col->series_number' AND series_number!='' AND settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND series_number='$col->series_number'");
            $sum_amount= $this->super_model->select_sum_where("collection_details","amount","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND series_number='$col->series_number'");
            $sum_zero_rated= $this->super_model->select_sum_where("collection_details","zero_rated","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND series_number='$col->series_number'");
            $sum_zero_rated_ecozone= $this->super_model->select_sum_where("collection_details","zero_rated_ecozone","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND series_number='$col->series_number'");
            $sum_vat = $this->super_model->select_sum_where("collection_details","vat","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND series_number='$col->series_number'");
            $sum_ewt= $this->super_model->select_sum_where("collection_details","ewt","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND series_number='$col->series_number'");
            $sum_def_int = $this->super_model->select_sum_where("collection_details","defint","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND series_number='$col->series_number'");
            //$total=($col->amount + $col->zero_rated + $col->zero_rated_ecozone + $col->vat)-$col->ewt; 
            //if($count_series>=1){
                //$overall_total=($sum_amount + $sum_zero_rated + $sum_zero_rated_ecozone + $sum_vat)-$sum_ewt;
            // }else{
            //     $overall_total=($col->amount + $col->zero_rated + $col->zero_rated_ecozone + $col->vat)-$col->ewt; 
            // }
            $overall_total=($sum_amount + $sum_zero_rated + $sum_zero_rated_ecozone + $sum_vat)-$sum_ewt;
            $data['collection'][]=array(
                "count_series"=>$count_series,
                "collection_details_id"=>$col->collection_details_id,
                "collection_date"=>$col->collection_date,
                "collection_id"=>$col->collection_id,
                "settlement_id"=>$col->settlement_id,
                "series_number"=>$col->series_number,
                "or_date"=>$col->or_date,
                "billing_remarks"=>$col->billing_remarks,
                "particulars"=>$col->particulars,
                "item_no"=>$col->item_no,
                //"defint"=>$sum_def_int,
                "defint"=>$col->defint,
                "reference_no"=>$col->reference_no,
                "vat"=>$col->vat,
                "zero_rated"=>$col->zero_rated,
                "zero_rated_ecozone"=>$col->zero_rated_ecozone,
                "ewt"=>$col->ewt,
                "total"=>$col->total,
                //"company_name"=>$company_name,
                "company_name"=>$col->buyer_fullname,
                "amount"=>$col->amount,
                "or_no_remarks"=>$col->or_no_remarks,
                "overall_total"=>$overall_total,
            );
            $data['details_id']=$col->collection_details_id;
        //}
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
        $item_no=$this->input->post('item_no');
        foreach($this->super_model->select_custom_where("collection_details","collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $check){
            $count=$this->super_model->count_custom_where("collection_details","collection_id = '$check->collection_id' AND old_series_no!='' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)");
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

        if($this->super_model->update_custom_where("collection_details", $data_update, "collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)")){
            foreach($this->super_model->select_custom_where("collection_details","collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $latest_series){
               echo $latest_series->series_number;
            }
        }
    }

    public function update_ordate(){
        $ref_no=$this->input->post('reference_number');
        $collection_id=$this->input->post('collection_id');
        $new_or_date=$this->input->post('or_date');
        $old_or_date=$this->input->post('or_date');
        $settlement_id=$this->input->post('settlement_id');
        $item_no=$this->input->post('item_no');
        foreach($this->super_model->select_custom_where("collection_details","collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $check){
            $count=$this->super_model->count_custom_where("collection_details","collection_id = '$check->collection_id' AND old_or_date!='' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)");
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

        if($this->super_model->update_custom_where("collection_details", $data_update, "collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)")){
            foreach($this->super_model->select_custom_where("collection_details","collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $latest_ordate){
               echo $latest_ordate->or_date;
            }
        }
    }

    public function update_defint(){
        $ref_no=$this->input->post('reference_number');
        $collection_id=$this->input->post('collection_id');
        $def_int=$this->input->post('def_int');
        $settlement_id=$this->input->post('settlement_id');
        $item_no=$this->input->post('item_no');
        $data_update = array(
            'defint'=>$def_int,
        );

        if($this->super_model->update_custom_where("collection_details", $data_update, "collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)")){
            foreach($this->super_model->select_custom_where("collection_details","collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $latest_defint){
               echo $latest_defint->defint;
            }
        }
    }

    public function update_orno_remarks(){
        $ref_no=$this->input->post('reference_number');
        $collection_id=$this->input->post('collection_id');
        $orno_remarks=$this->input->post('or_no_remarks');
        $settlement_id=$this->input->post('settlement_id');
        $item_no=$this->input->post('item_no');
        $data_update = array(
            'or_no_remarks'=>$orno_remarks,
        );

        if($this->super_model->update_custom_where("collection_details", $data_update, "collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)")){
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

            foreach($this->super_model->select_custom_where("collection_details","collection_id='$collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $latest_or_remarks){
               echo $latest_or_remarks->or_no_remarks;
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

    public function print_OR_new()
    {
        $collection_id=$this->uri->segment(3);
        $settlement_id=$this->uri->segment(4);
        $reference_no=$this->uri->segment(5);
        $data['ref_no'] = $reference_no;
        //$reference_no = $this->super_model->select_column_where("collection_details", "reference_no", "collection_id", $collection_id);
        //$settlement_id = $this->super_model->select_column_where("collection_details", "settlement_id", "collection_id", $collection_id);
        //$billing_id = $this->super_model->select_column_where("participant", "billing_id", "short_name", $settlement_id);
        
        $data['client']=$this->super_model->select_row_where("participant", "billing_id", $settlement_id);
        $data['sum_amount']=$this->super_model->select_sum_where("collection_details","amount","settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        /*$data['amount'] =  $this->super_model->select_column_custom_where("collection_details", "amount", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['vat'] =  $this->super_model->select_column_custom_where("collection_details", "vat", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");*/
        $data['sum_vat']=$this->super_model->select_sum_where("collection_details","vat","settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['sum_ewt'] =  $this->super_model->select_sum_where("collection_details", "ewt", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['sum_zero_rated'] =  $this->super_model->select_sum_where("collection_details", "zero_rated", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['sum_zero_rated_ecozone'] =  $this->super_model->select_sum_where("collection_details", "zero_rated_ecozone", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['defint'] =  $this->super_model->select_sum_where("collection_details", "defint", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['date'] =  $this->super_model->select_column_custom_where("collection_details","or_date","settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        //$data['date'] = $this->super_model->select_column_where("collection_head", "collection_date", "collection_id", $collection_id);
        //$data['ref_no'] = $this->super_model->select_column_where("collection_details", "reference_no", "collection_id", $collection_id);
        $this->load->view('template/print_head');
        $this->load->view('sales/print_OR_new',$data);
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
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT reference_number FROM sales_transaction_head WHERE reference_number!=''");
        $data['date'] = $this->super_model->custom_query("SELECT DISTINCT due_date FROM sales_transaction_head WHERE due_date!=''");
        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant WHERE participant_name != '' GROUP BY tin ORDER BY participant_name");
        $data['participant_name']=$this->super_model->select_column_where('participant','participant_name','tin',$participants);
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
                //$sql.= " tin = '$participant' AND "; 
               $par=array();
               foreach($this->super_model->select_custom_where('participant',"tin='$participants'") AS $p){
                   $par[]="'".$p->settlement_id."'";
               }
               $imp=implode(',',$par);
               $sql.= " sd.short_name IN($imp) AND ";
            }
            $query=substr($sql,0,-4);
            $qu = " WHERE saved='1' AND ".$query;
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details sd INNER JOIN sales_transaction_head sh ON sd.sales_id=sh.sales_id $qu") AS $d){
            // foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details sd INNER JOIN sales_transaction_head sh ON sd.sales_id=sh.sales_id WHERE saved='1' AND (reference_number LIKE '%$ref_no%' OR due_date = '$due_date')") AS $d){
                $series_number=$this->super_model->select_column_custom_where("collection_details","series_number","reference_no='$d->reference_number' AND settlement_id='$d->short_name'");
                $old_series_no=$this->super_model->select_column_custom_where("collection_details","old_series_no","reference_no='$d->reference_number' AND settlement_id='$d->short_name'");

                // $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $d->sales_id);
                // $company_name=$this->super_model->select_column_where("sales_transaction_details", "company_name", "sales_detail_id", $d->sales_detail_id);
                if(!empty($d->company_name)){
                    $comp_name=$d->company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $d->billing_id);
                }
                $data['details'][]=array(
                    'sales_detail_id'=>$d->sales_detail_id,
                    'sales_id'=>$d->sales_id,
                    'item_no'=>$d->item_no,
                    'billing_from'=>$d->billing_from,
                    'billing_to'=>$d->billing_to,
                    'reference_number'=>$d->reference_number,
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
                    // 'billing_from'=>$d->billing_from,
                    // 'billing_to'=>$d->billing_to,
                    'due_date'=>$d->due_date,
                    'print_counter'=>$d->print_counter,
                    'ewt_amount'=>$d->ewt_amount,
                    'original_copy'=>$d->original_copy,
                    'scanned_copy'=>$d->scanned_copy
                );
            }
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
                //$sql.= " tin = '$participant' AND "; 
               $par=array();
               foreach($this->super_model->select_custom_where('participant',"tin='$participants'") AS $p){
                   $par[]="'".$p->settlement_id."'";
               }
               $imp=implode(',',$par);
               $sql.= " sd.short_name IN($imp) AND ";
            }
            $query=substr($sql,0,-4);
            $qu = " WHERE saved='1' AND ".$query;
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details sd INNER JOIN sales_transaction_head sh ON sd.sales_id=sh.sales_id $qu") AS $d){
                $participant_id = $this->super_model->select_column_custom_where("participant","participant_id","billing_id='$d->billing_id'");
                //$sub_participant = $this->super_model->select_column_custom_where("subparticipant","sub_participant","participant_id='$participant_id'");
                $sub_participant = $this->super_model->count_custom_where("subparticipant","sub_participant='$participant_id'");
                $series_number=$this->super_model->select_column_custom_where("collection_details","series_number","reference_no='$d->reference_number' AND settlement_id='$d->short_name'");
                $old_series_no=$this->super_model->select_column_custom_where("collection_details","old_series_no","reference_no='$d->reference_number' AND settlement_id='$d->short_name'");
                if($sub_participant==0){
                $data['details'][]=array(
                    'sales_detail_id'=>$d->sales_detail_id,
                    'sales_id'=>$d->sales_id,
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
                    'reference_number'=>$d->reference_number,
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
        }
        $this->load->view('sales/sales_wesm',$data);
        $this->load->view('template/footer');
    }

    public function sales_wesm_pdf_or(){
        $id=$this->uri->segment(3);

        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        
            $vatable_sales_bs=array();
            $vat_on_sales_bs=array();
            $ewt_bs=array();
            $zero_rated_ecozone_bs=array();
            $zero_rated_bs=array();
                foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details sd INNER JOIN sales_transaction_head sh ON sd.sales_id=sh.sales_id WHERE sales_detail_id='$id'") AS $d){
                $data['stl_id']=$d->short_name;
                $data['address']=$this->super_model->select_column_where("participant","registered_address","billing_id",$d->billing_id);
                $data['tin']=$this->super_model->select_column_where("participant","tin","billing_id",$d->billing_id);
                // $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $d->sales_id);
                if(!empty($d->company_name)){
                    $data['company_name']=$d->company_name;
                }else{
                    $data['company_name']=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $d->billing_id);
                }
                $data['billing_from']=$this->super_model->select_column_where("sales_transaction_head","billing_from","sales_id",$d->sales_id);
                $data['billing_to']=$this->super_model->select_column_where("sales_transaction_head","billing_to","sales_id",$d->sales_id);
                $data['transaction_date']=$this->super_model->select_column_where("sales_transaction_head","transaction_date","sales_id",$d->sales_id);
                $data['reference_number']=$this->super_model->select_column_where("sales_transaction_head","reference_number","sales_id",$d->sales_id);
                $data['or_no']=$d->serial_no;
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);
                $data['participant_id'] = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);

                $data['billing_month'] = date('my',strtotime($d->transaction_date));
                $data['date_uploaded'] = date('Ymd',strtotime($d->create_date));
                $data['refno'] = preg_replace("/[^0-9]/", "",$d->reference_number);
                $h=0;
                foreach($this->super_model->select_custom_where("subparticipant","participant_id='$participant_id'") AS $s){
                    $data['participant_id_sub'][$h]=$s->participant_id;
                    $billing_id=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);
                    $vatable_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vat_on_sales","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $ewt_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $zero_rated_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $h++;
                }

            $sum_vatable_sales=array_sum($vatable_sales_bs);
            $sum_zero_rated_ecozone=array_sum($zero_rated_ecozone_bs);
            $sum_vat_on_sales=array_sum($vat_on_sales_bs);
            $sum_ewt=array_sum($ewt_bs);
            $sum_zero_rated=array_sum($zero_rated_bs);
            
            $data['total_vs']=$d->vatable_sales + $sum_vatable_sales;
            $data['total_zr']=$d->zero_rated_sales + $sum_zero_rated;
            $data['total_zra']=$d->zero_rated_ecozones + $sum_zero_rated_ecozone;
            $data['total_vos']=$d->vat_on_sales + $sum_vat_on_sales;
            $data['total_ewt']=$d->ewt + $sum_ewt;
            }
        $this->load->view('sales/sales_wesm_pdf_or',$data);
    }

    public function sales_wesm_pdf_si(){
        $id=$this->uri->segment(3);

        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        
            $vatable_sales_bs=array();
            $vat_on_sales_bs=array();
            $ewt_bs=array();
            $zero_rated_ecozone_bs=array();
            $zero_rated_bs=array();
                foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details sd INNER JOIN sales_transaction_head sh ON sd.sales_id=sh.sales_id WHERE sales_detail_id='$id'") AS $d){
                $data['stl_id']=$d->short_name;
                $data['address']=$this->super_model->select_column_where("participant","registered_address","billing_id",$d->billing_id);
                $data['tin']=$this->super_model->select_column_where("participant","tin","billing_id",$d->billing_id);
                // $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $d->sales_id);
                if(!empty($d->company_name)){
                    $data['company_name']=$d->company_name;
                }else{
                    $data['company_name']=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $d->billing_id);
                }
                $data['billing_from']=$this->super_model->select_column_where("sales_transaction_head","billing_from","sales_id",$d->sales_id);
                $data['billing_to']=$this->super_model->select_column_where("sales_transaction_head","billing_to","sales_id",$d->sales_id);
                $data['transaction_date']=$this->super_model->select_column_where("sales_transaction_head","transaction_date","sales_id",$d->sales_id);
                $data['reference_number']=$this->super_model->select_column_where("sales_transaction_head","reference_number","sales_id",$d->sales_id);
                $data['or_no']=$d->serial_no;
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);
                $data['participant_id'] = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);

                $data['billing_month'] = date('my',strtotime($d->transaction_date));
                $data['date_uploaded'] = date('Ymd',strtotime($d->create_date));
                $data['refno'] = preg_replace("/[^0-9]/", "",$d->reference_number);
                $h=0;
                foreach($this->super_model->select_custom_where("subparticipant","participant_id='$participant_id'") AS $s){
                    $data['participant_id_sub'][$h]=$s->participant_id;
                    $billing_id=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);
                    $vatable_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vat_on_sales","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $ewt_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $zero_rated_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $h++;
                }

            $sum_vatable_sales=array_sum($vatable_sales_bs);
            $sum_zero_rated_ecozone=array_sum($zero_rated_ecozone_bs);
            $sum_vat_on_sales=array_sum($vat_on_sales_bs);
            $sum_ewt=array_sum($ewt_bs);
            $sum_zero_rated=array_sum($zero_rated_bs);
            
            $data['total_vs']=$d->vatable_sales + $sum_vatable_sales;
            $data['total_zr']=$d->zero_rated_sales + $sum_zero_rated;
            $data['total_zra']=$d->zero_rated_ecozones + $sum_zero_rated_ecozone;
            $data['total_vos']=$d->vat_on_sales + $sum_vat_on_sales;
            $data['total_ewt']=$d->ewt + $sum_ewt;
            }
        $this->load->view('sales/sales_wesm_pdf_si',$data);
    }

    public function sales_wesm_pdf_or_bulk(){
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
        $qu = " WHERE sd.bulk_pdf_flag = '0' AND serial_no != '' AND saved = '1' AND ".$query;

        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        
            $vatable_sales_bs=array();
            $vat_on_sales_bs=array();
            $ewt_bs=array();
            $zero_rated_ecozone_bs=array();
            $zero_rated_bs=array();
            $data['details']=array();
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details sd INNER JOIN sales_transaction_head sh ON sd.sales_id=sh.sales_id $qu GROUP BY serial_no LIMIT 10") AS $d){
                $stl_id=$d->short_name;
                $address=$this->super_model->select_column_where("participant","registered_address","billing_id",$d->billing_id);
                $tin=$this->super_model->select_column_where("participant","tin","billing_id",$d->billing_id);
                // $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $d->sales_id);
                if(!empty($d->company_name)){
                    $comp_name=$d->company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $d->billing_id);
                }
                $billing_from=$this->super_model->select_column_where("sales_transaction_head","billing_from","sales_id",$d->sales_id);
                $billing_to=$this->super_model->select_column_where("sales_transaction_head","billing_to","sales_id",$d->sales_id);
                $transaction_date=$this->super_model->select_column_where("sales_transaction_head","transaction_date","sales_id",$d->sales_id);
                $reference_number=$this->super_model->select_column_where("sales_transaction_head","reference_number","sales_id",$d->sales_id);
                $or_no=$d->serial_no;
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);

                $billing_month = date('my',strtotime($d->transaction_date));
                $date_uploaded = date('Ymd',strtotime($d->create_date));
                $refno = preg_replace("/[^0-9]/", "",$d->reference_number);
                $h=0;
                foreach($this->super_model->select_custom_where("subparticipant","participant_id='$participant_id'") AS $s){
                    $data['participant_id_sub'][$h]=$s->participant_id;
                    $billing_id=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);
                    $vatable_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vat_on_sales","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $ewt_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $zero_rated_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $h++;
                }

                $sum_vatable_sales=array_sum($vatable_sales_bs);
                $sum_zero_rated_ecozone=array_sum($zero_rated_ecozone_bs);
                $sum_vat_on_sales=array_sum($vat_on_sales_bs);
                $sum_ewt=array_sum($ewt_bs);
                $sum_zero_rated=array_sum($zero_rated_bs);
                
                $total_vs=$d->vatable_sales + $sum_vatable_sales;
                $total_zr=$d->zero_rated_sales + $sum_zero_rated;
                $total_zra=$d->zero_rated_ecozones + $sum_zero_rated_ecozone;
                $total_vos=$d->vat_on_sales + $sum_vat_on_sales;
                $total_ewt=$d->ewt + $sum_ewt;
                $total_sales = $total_vs + $total_zra + $total_vos;
                $net_of_vat = $total_vs + $total_zra;
                $total_amount_due = ($total_vs + $total_zra + $total_vos) - $total_ewt;

                $data['details'][]=array(
                    'sales_id'=>$d->sales_id,
                    'stl_id'=>$d->short_name,
                    'company_name'=>$comp_name,
                    'date'=>$d->transaction_date,
                    'transaction_date'=>$transaction_date,
                    'address'=>$address,
                    'tin'=>$tin,
                    'or_no'=>$d->serial_no,
                    'ref_no'=>$d->reference_number,
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

        $this->load->view('sales/sales_wesm_pdf_or_bulk',$data);
    }
    public function sales_wesm_pdf_si_bulk(){
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
        $qu = "WHERE sd.bulk_pdf_flag = '0' AND serial_no != '' AND saved = '1' AND ".$query;

        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        
            // $vatable_sales_bs=array();
            // $vat_on_sales_bs=array();
            // $ewt_bs=array();
            // $zero_rated_ecozone_bs=array();
            // $zero_rated_bs=array();
            $data['details']=array();
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details sd INNER JOIN sales_transaction_head sh ON sd.sales_id=sh.sales_id $qu GROUP BY serial_no LIMIT 10") AS $d){
                $stl_id=$d->short_name;
                $address=$this->super_model->select_column_where("participant","registered_address","billing_id",$d->billing_id);
                $tin=$this->super_model->select_column_where("participant","tin","billing_id",$d->billing_id);
                // $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $d->sales_id);
                if(!empty($d->company_name)){
                    $comp_name=$d->company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $d->billing_id);
                }
                $billing_from=$this->super_model->select_column_where("sales_transaction_head","billing_from","sales_id",$d->sales_id);
                $billing_to=$this->super_model->select_column_where("sales_transaction_head","billing_to","sales_id",$d->sales_id);
                $transaction_date=$this->super_model->select_column_where("sales_transaction_head","transaction_date","sales_id",$d->sales_id);
                $reference_number=$this->super_model->select_column_where("sales_transaction_head","reference_number","sales_id",$d->sales_id);
                $or_no=$d->serial_no;
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);
                // $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);

                $billing_month = date('my',strtotime($d->transaction_date));
                $date_uploaded = date('Ymd',strtotime($d->create_date));
                $refno = preg_replace("/[^0-9]/", "",$d->reference_number);
                // $h=0;
                // foreach($this->super_model->select_custom_where("subparticipant","participant_id='$participant_id'") AS $s){
                //     $data['participant_id_sub'][$h]=$s->participant_id;
                //     $billing_id=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);
                //     $vatable_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                //     $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vat_on_sales","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                //     $ewt_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                //     $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                //     $zero_rated_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                //     $h++;
                // }

                $vatable_sales_bs=$this->super_model->select_sum_where("sales_transaction_details","vatable_sales","serial_no='$d->serial_no' AND sales_id='$d->sales_id'");
                $vat_on_sales_bs=$this->super_model->select_sum_where("sales_transaction_details","vat_on_sales","serial_no='$d->serial_no' AND sales_id='$d->sales_id'");
                $ewt_bs=$this->super_model->select_sum_where("sales_transaction_details","ewt","serial_no='$d->serial_no' AND sales_id='$d->sales_id'");
                $zero_rated_ecozone_bs=$this->super_model->select_sum_where("sales_transaction_details","zero_rated_ecozones","serial_no='$d->serial_no' AND sales_id='$d->sales_id'");
                $zero_rated_bs=$this->super_model->select_sum_where("sales_transaction_details","zero_rated_sales","serial_no='$d->serial_no' AND sales_id='$d->sales_id'");

                // $sum_vatable_sales=array_sum($vatable_sales_bs);
                // $sum_zero_rated_ecozone=array_sum($zero_rated_ecozone_bs);
                // $sum_vat_on_sales=array_sum($vat_on_sales_bs);
                // $sum_ewt=array_sum($ewt_bs);
                // $sum_zero_rated=array_sum($zero_rated_bs);
                
                // $total_vs=$d->vatable_sales + $sum_vatable_sales;
                // $total_zr=$d->zero_rated_sales + $sum_zero_rated;
                // $total_zra=$d->zero_rated_ecozones + $sum_zero_rated_ecozone;
                // $total_vos=$d->vat_on_sales + $sum_vat_on_sales;
                // $total_ewt=$d->ewt + $sum_ewt;
                $total_vs=$vatable_sales_bs;
                $total_zr=$zero_rated_bs;
                $total_zra=$zero_rated_ecozone_bs;
                $total_vos=$vat_on_sales_bs;
                $total_ewt=$ewt_bs;
                $total_sales = $total_vs + $total_zra + $total_vos;
                $net_of_vat = $total_vs + $total_zra;
                $total_amount_due = ($total_vs + $total_zra + $total_vos) - $total_ewt;

                $data['details'][]=array(
                    'sales_id'=>$d->sales_id,
                    'stl_id'=>$d->short_name,
                    'company_name'=>$comp_name,
                    'date'=>$d->transaction_date,
                    'transaction_date'=>$transaction_date,
                    'address'=>$address,
                    'tin'=>$tin,
                    'or_no'=>$d->serial_no,
                    'ref_no'=>$d->reference_number,
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

        $this->load->view('sales/sales_wesm_pdf_si_bulk',$data);
    }
        public function update_sales_wesm_flag(){
        $serial_no = $this->input->post('serial_no');
        // $settlement_id = $this->input->post('stl_id');
        $sales_id = $this->input->post('sales_id');
        $filename = $this->input->post('filename');
        $data_update = array(
                "bulk_pdf_flag"=>1,
                "filename"=>$filename
            );
            $this->super_model->update_custom_where("sales_transaction_details", $data_update, "serial_no='$serial_no' AND sales_id='$sales_id'");
    }

        public function export_not_download_sales_wesm()
    {

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
            $qu = " WHERE sd.bulk_pdf_flag = '0' AND serial_no != '' AND saved = '1' AND ".$query;

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
        
       foreach($this->super_model->custom_query("SELECT filename FROM sales_transaction_details sd INNER JOIN sales_transaction_head sh ON sd.sales_id=sh.sales_id $qu GROUP BY serial_no,sd.sales_id") AS $db){
        $db_files[] = $db->filename;
       }

        $data['result'] = array_diff($db_files, $pdffiles);

        $result=array_diff($db_files, $pdffiles);
        $x=1;
       
        $this->load->view('sales/export_not_download_sales_wesm',$data);
        $this->load->view('template/footer');
    }

    public function sales_wesm_pdf_scan_directory(){

        $filenames = $this->input->post('filenames');
        $sql="(";
        foreach($filenames AS $f){
            $sql .= " filename =  '$f'  OR ";
        }
        $query=substr($sql,0,-3) . ")";

        
        $qu = "WHERE serial_no != '' AND saved = '1' AND ".$query;

        
        $data['details']=array();
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        $data['timestamp'] = date('Ymd');

            $data['details']=array();
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details sd INNER JOIN sales_transaction_head sh ON sd.sales_id=sh.sales_id $qu GROUP BY serial_no LIMIT 10") AS $d){
                $stl_id=$d->short_name;
                $address=$this->super_model->select_column_where("participant","registered_address","billing_id",$d->billing_id);
                $tin=$this->super_model->select_column_where("participant","tin","billing_id",$d->billing_id);
                if(!empty($d->company_name)){
                    $comp_name=$d->company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $d->billing_id);
                }
                $billing_from=$this->super_model->select_column_where("sales_transaction_head","billing_from","sales_id",$d->sales_id);
                $billing_to=$this->super_model->select_column_where("sales_transaction_head","billing_to","sales_id",$d->sales_id);
                $transaction_date=$this->super_model->select_column_where("sales_transaction_head","transaction_date","sales_id",$d->sales_id);
                $reference_number=$this->super_model->select_column_where("sales_transaction_head","reference_number","sales_id",$d->sales_id);
                $or_no=$d->serial_no;
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);

                $billing_month = date('my',strtotime($d->transaction_date));
                $date_uploaded = date('Ymd',strtotime($d->create_date));
                $refno = preg_replace("/[^0-9]/", "",$d->reference_number);

                $vatable_sales_bs=$this->super_model->select_sum_where("sales_transaction_details","vatable_sales","serial_no='$d->serial_no' AND sales_id='$d->sales_id'");
                $vat_on_sales_bs=$this->super_model->select_sum_where("sales_transaction_details","vat_on_sales","serial_no='$d->serial_no' AND sales_id='$d->sales_id'");
                $ewt_bs=$this->super_model->select_sum_where("sales_transaction_details","ewt","serial_no='$d->serial_no' AND sales_id='$d->sales_id'");
                $zero_rated_ecozone_bs=$this->super_model->select_sum_where("sales_transaction_details","zero_rated_ecozones","serial_no='$d->serial_no' AND sales_id='$d->sales_id'");
                $zero_rated_bs=$this->super_model->select_sum_where("sales_transaction_details","zero_rated_sales","serial_no='$d->serial_no' AND sales_id='$d->sales_id'");

                $total_vs=$vatable_sales_bs;
                $total_zr=$zero_rated_bs;
                $total_zra=$zero_rated_ecozone_bs;
                $total_vos=$vat_on_sales_bs;
                $total_ewt=$ewt_bs;
                $total_sales = $total_vs + $total_zra + $total_vos;
                $net_of_vat = $total_vs + $total_zra;
                $total_amount_due = ($total_vs + $total_zra + $total_vos) - $total_ewt;

                $data['details'][]=array(
                    'sales_id'=>$d->sales_id,
                    'stl_id'=>$d->short_name,
                    'company_name'=>$comp_name,
                    'date'=>$d->transaction_date,
                    'transaction_date'=>$transaction_date,
                    'address'=>$address,
                    'tin'=>$tin,
                    'or_no'=>$d->serial_no,
                    'ref_no'=>$d->reference_number,
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

       
        $this->load->view('sales/sales_wesm_pdf_scan_directory',$data);
    }

        public function reserve_sales_wesm(){
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
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT res_reference_number FROM reserve_sales_transaction_head WHERE res_reference_number!=''");
        $data['date'] = $this->super_model->custom_query("SELECT DISTINCT res_due_date FROM reserve_sales_transaction_head WHERE res_due_date!=''");
        $data['participant']=$this->super_model->custom_query("SELECT * FROM reserve_participant WHERE res_participant_name != '' GROUP BY res_tin ORDER BY res_participant_name");
        $data['participant_name']=$this->super_model->select_column_where('reserve_participant','res_participant_name','res_tin',$participants);
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        if($in_ex_sub==0 ||  $in_ex_sub=='null'){
            $sql='';
            if($ref_no!='null'){
                $sql.= "sh.res_reference_number = '$ref_no' AND ";
            }

            if($due_date!='null'){
                $sql.= "sh.res_due_date = '$due_date' AND ";
            }

            if($billfrom!='null' && $billto!='null'){ 
                $sql.= " ((sh.res_billing_from BETWEEN '$billfrom' AND '$billto') OR (sh.res_billing_to BETWEEN '$billfrom' AND '$billto'))  AND ";
            }

            if(!empty($participants) && $participants!='null'){
               $par=array();
               foreach($this->super_model->select_custom_where('reserve_participant',"res_tin='$participants'") AS $p){
                   $par[]="'".$p->res_settlement_id."'";
                   $imp=implode(',',$par);
                   $sql.= " sd.res_short_name IN($imp) AND ";
               }
            }

            $query=substr($sql,0,-4);
            $qu = " WHERE res_saved='1' AND ".$query;

            foreach($this->super_model->custom_query("SELECT * FROM reserve_sales_transaction_details sd INNER JOIN reserve_sales_transaction_head sh ON sd.reserve_sales_id=sh.reserve_sales_id $qu") AS $d){
                $series_number=$this->super_model->select_column_custom_where("collection_reserve_details","series_number","reference_no='$d->res_reference_number' AND settlement_id='$d->res_short_name'");
                $old_series_no=$this->super_model->select_column_custom_where("collection_reserve_details","old_series_no","reference_no='$d->res_reference_number' AND settlement_id='$d->res_short_name'");

                // $create_date = $this->super_model->select_column_where("reserve_sales_transaction_head", "res_create_date", "reserve_sales_id", $d->reserve_sales_id);
                $date=$this->super_model->select_column_where("reserve_sales_transaction_details", "res_company_name", "reserve_sales_detail_id", $d->reserve_sales_detail_id);
                if(!empty($d->company_name)){
                    $comp_name=$d->company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("reserve_participant", "res_participant_name", "res_billing_id", $d->res_billing_id);
                }
                $data['details'][]=array(
                    'reserve_sales_detail_id'=>$d->reserve_sales_detail_id,
                    'reserve_sales_id'=>$d->reserve_sales_id,
                    'item_no'=>$d->res_item_no,
                    'billing_from'=>$d->res_billing_from,
                    'billing_to'=>$d->res_billing_to,
                    'reference_number'=>$d->res_reference_number,
                    'series_number'=>$series_number,
                    'old_series_no_col'=>$old_series_no,
                    'old_series_no'=>$d->res_old_series_no,
                    'short_name'=>$d->res_short_name,
                    'billing_id'=>$d->res_billing_id,
                    'actual_billing_id'=>$d->res_actual_billing_id,
                    'company_name'=>$comp_name,
                    'facility_type'=>$d->res_facility_type,
                    'wht_agent'=>$d->res_wht_agent,
                    'ith_tag'=>$d->res_ith_tag,
                    'non_vatable'=>$d->res_non_vatable,
                    'zero_rated'=>$d->res_zero_rated,
                    'vatable_sales'=>$d->res_vatable_sales,
                    'vat_on_sales'=>$d->res_vat_on_sales,
                    'zero_rated_sales'=>$d->res_zero_rated_sales,
                    'zero_rated_ecozones'=>$d->res_zero_rated_ecozones,
                    'ewt'=>$d->res_ewt,
                    'serial_no'=>$d->res_serial_no,
                    'total_amount'=>$d->res_total_amount,
                    'reference_number'=>$d->res_reference_number,
                    'transaction_date'=>$d->res_transaction_date,
                    'due_date'=>$d->res_due_date,
                    'print_counter'=>$d->res_print_counter,
                    'ewt_amount'=>$d->res_ewt_amount,
                    'original_copy'=>$d->res_original_copy,
                    'scanned_copy'=>$d->res_scanned_copy
                );
            }
        }else if($in_ex_sub==1){
            $sql='';
            if($ref_no!='null'){
                $sql.= "sh.res_reference_number = '$ref_no' AND ";
            }

            if($due_date!='null'){
                $sql.= "sh.res_due_date = '$due_date' AND ";
            }

            if($billfrom!='null' && $billto!='null'){ 
                $sql.= " ((sh.res_billing_from BETWEEN '$billfrom' AND '$billto') OR (sh.res_billing_to BETWEEN '$billfrom' AND '$billto'))  AND ";
            }

            if(!empty($participants) && $participants!='null'){
               $par=array();
               foreach($this->super_model->select_custom_where('reserve_participant',"res_tin='$participants'") AS $p){
                   $par[]="'".$p->res_settlement_id."'";
                    $imp=implode(',',$par);
                    $sql.= " sd.res_short_name IN($imp) AND ";
               } 
            }
            $query=substr($sql,0,-4);
            $qu = " WHERE res_saved='1' AND ".$query;
            foreach($this->super_model->custom_query("SELECT * FROM reserve_sales_transaction_details sd INNER JOIN reserve_sales_transaction_head sh ON sd.reserve_sales_id=sh.reserve_sales_id $qu") AS $d){
                $participant_id = $this->super_model->select_column_custom_where("reserve_participant","res_participant_id","res_billing_id='$d->res_billing_id'");
                $sub_participant = $this->super_model->count_custom_where("reserve_subparticipant","res_sub_participant='$participant_id'");
                $series_number=$this->super_model->select_column_custom_where("collection_reserve_details","series_number","reference_no='$d->res_reference_number' AND settlement_id='$d->res_short_name'");
                $old_series_no=$this->super_model->select_column_custom_where("collection_reserve_details","old_series_no","reference_no='$d->res_reference_number' AND settlement_id='$d->res_short_name'");
                if($sub_participant==0){
                    $data['details'][]=array(
                        'reserve_sales_detail_id'=>$d->reserve_sales_detail_id,
                        'reserve_sales_id'=>$d->reserve_sales_id,
                        'item_no'=>$d->res_item_no,
                        'series_number'=>$series_number,
                        'old_series_no_col'=>$old_series_no,
                        'old_series_no'=>$d->res_old_series_no,
                        'short_name'=>$d->res_short_name,
                        'billing_id'=>$d->res_billing_id,
                        'actual_billing_id'=>$d->res_actual_billing_id,
                        'company_name'=>$d->res_company_name,
                        'facility_type'=>$d->res_facility_type,
                        'wht_agent'=>$d->res_wht_agent,
                        'ith_tag'=>$d->res_ith_tag,
                        'non_vatable'=>$d->res_non_vatable,
                        'zero_rated'=>$d->res_zero_rated,
                        'vatable_sales'=>$d->res_vatable_sales,
                        'vat_on_sales'=>$d->res_vat_on_sales,
                        'zero_rated_sales'=>$d->res_zero_rated_sales,
                        'zero_rated_ecozones'=>$d->res_zero_rated_ecozones,
                        'ewt'=>$d->res_ewt,
                        'serial_no'=>$d->res_serial_no,
                        'total_amount'=>$d->res_total_amount,
                        'reference_number'=>$d->res_reference_number,
                        'transaction_date'=>$d->res_transaction_date,
                        'billing_from'=>$d->res_billing_from,
                        'billing_to'=>$d->res_billing_to,
                        'due_date'=>$d->res_due_date,
                        'print_counter'=>$d->res_print_counter,
                        'ewt_amount'=>$d->res_ewt_amount,
                        'original_copy'=>$d->res_original_copy,
                        'scanned_copy'=>$d->res_scanned_copy
                    );
                }
            }
        }
        $this->load->view('sales/reserve_sales_wesm',$data);
        $this->load->view('template/footer');
    }

    public function reserve_sales_wesm_pdf_or_bulk(){
        $ref_no=$this->uri->segment(3);
        $due_date=$this->uri->segment(4);
        $in_ex_sub=$this->uri->segment(5);
        $billfrom=$this->uri->segment(6);
        $billto=$this->uri->segment(7);
        $participants=$this->uri->segment(8);

        $sql='';
        if($ref_no!='null'){
            $sql.= "sh.res_reference_number = '$ref_no' AND ";
        }

        if($due_date!='null'){
            $sql.= "sh.res_due_date = '$due_date' AND ";
        }

        if($billfrom!='null' && $billto!='null'){ 
            $sql.= " ((sh.res_billing_from BETWEEN '$billfrom' AND '$billto') OR (sh.res_billing_to BETWEEN '$billfrom' AND '$billto'))  AND ";
        }

        if(!empty($participants) && $participants!='null'){
            $par=array();
            foreach($this->super_model->select_custom_where('reserve_participant',"res_tin='$participants'") AS $p){
                $par[]="'".$p->settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " sd.res_short_name IN($imp) AND ";
        }
        $query=substr($sql,0,-4);
        $qu = " WHERE sd.res_bulk_pdf_flag = '0' AND res_serial_no != '' AND res_saved = '1' AND ".$query;

        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        
            $vatable_sales_bs=array();
            $vat_on_sales_bs=array();
            $ewt_bs=array();
            $zero_rated_ecozone_bs=array();
            $zero_rated_bs=array();
            $data['details']=array();
            foreach($this->super_model->custom_query("SELECT * FROM reserve_sales_transaction_details sd INNER JOIN reserve_sales_transaction_head sh ON sd.reserve_sales_id=sh.reserve_sales_id $qu GROUP BY res_serial_no LIMIT 10") AS $d){
                $stl_id=$d->res_short_name;
                $address=$this->super_model->select_column_where("reserve_participant","res_registered_address","res_billing_id",$d->res_billing_id);
                $tin=$this->super_model->select_column_where("reserve_participant","res_tin","res_billing_id",$d->res_billing_id);
                if(!empty($d->res_company_name)){
                    $comp_name=$d->res_company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("reserve_participant", "res_participant_name", "res_billing_id", $d->res_billing_id);
                }
                $billing_from=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_from","reserve_sales_id",$d->reserve_sales_id);
                $billing_to=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_to","reserve_sales_id",$d->reserve_sales_id);
                $transaction_date=$this->super_model->select_column_where("reserve_sales_transaction_head","res_transaction_date","reserve_sales_id",$d->reserve_sales_id);
                $reference_number=$this->super_model->select_column_where("reserve_sales_transaction_head","res_reference_number","reserve_sales_id",$d->reserve_sales_id);
                $or_no=$d->res_serial_no;
                $participant_id = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$d->res_billing_id);
                $participant_id = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$d->res_billing_id);

                $billing_month = date('my',strtotime($d->res_transaction_date));
                $date_uploaded = date('Ymd',strtotime($d->res_create_date));
                $refno = preg_replace("/[^0-9]/", "",$d->res_reference_number);
                $h=0;
                foreach($this->super_model->select_custom_where("reserve_subparticipant","res_participant_id='$participant_id'") AS $s){
                    $data['participant_id_sub'][$h]=$s->res_participant_id;
                    $billing_id=$this->super_model->select_column_where("reserve_participant","res_billing_id","res_participant_id",$s->res_sub_participant);
                    $vatable_sales_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_vatable_sales","res_serial_no='$d->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$d->reserve_sales_id'");
                    $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_vat_on_sales","res_serial_no='$d->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$d->reserve_sales_id'");
                    $ewt_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_ewt","res_serial_no='$d->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$d->reserve_sales_id'");
                    $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_zero_rated_ecozones","res_serial_no='$d->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$d->reserve_sales_id'");
                    $zero_rated_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_zero_rated","res_serial_no='$d->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$d->reserve_sales_id'");
                    $h++;
                }

                $sum_vatable_sales=array_sum($vatable_sales_bs);
                $sum_zero_rated_ecozone=array_sum($zero_rated_ecozone_bs);
                $sum_vat_on_sales=array_sum($vat_on_sales_bs);
                $sum_ewt=array_sum($ewt_bs);
                $sum_zero_rated=array_sum($zero_rated_bs);
                
                $total_vs=$d->res_vatable_sales + $sum_vatable_sales;
                $total_zr=$d->res_zero_rated_sales + $sum_zero_rated;
                $total_zra=$d->res_zero_rated_ecozones + $sum_zero_rated_ecozone;
                $total_vos=$d->res_vat_on_sales + $sum_vat_on_sales;
                $total_ewt=$d->res_ewt + $sum_ewt;
                $total_sales = $total_vs + $total_zra + $total_vos;
                $net_of_vat = $total_vs + $total_zra;
                $total_amount_due = ($total_vs + $total_zra + $total_vos) - $total_ewt;

                $data['details'][]=array(
                    'sales_id'=>$d->reserve_sales_id,
                    'stl_id'=>$d->res_short_name,
                    'company_name'=>$comp_name,
                    'date'=>$d->res_transaction_date,
                    'transaction_date'=>$transaction_date,
                    'address'=>$address,
                    'tin'=>$tin,
                    'or_no'=>$d->res_serial_no,
                    'ref_no'=>$d->res_reference_number,
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

        $this->load->view('sales/reserve_sales_wesm_pdf_or_bulk',$data);
    }

    public function update_reserve_sales_wesm_flag(){
        $serial_no = $this->input->post('serial_no');
        $sales_id = $this->input->post('sales_id');
        $filename = $this->input->post('filename');
        $data_update = array(
                "res_bulk_pdf_flag"=>1,
                "res_filename"=>$filename
            );
            $this->super_model->update_custom_where("reserve_sales_transaction_details", $data_update, "res_serial_no='$serial_no' AND reserve_sales_id='$sales_id'");
    }

    public function reserve_sales_wesm_pdf_or(){
        $id=$this->uri->segment(3);

        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        
            $vatable_sales_bs=array();
            $vat_on_sales_bs=array();
            $ewt_bs=array();
            $zero_rated_ecozone_bs=array();
            $zero_rated_bs=array();
                foreach($this->super_model->custom_query("SELECT * FROM reserve_sales_transaction_details sd INNER JOIN reserve_sales_transaction_head sh ON sd.reserve_sales_id=sh.reserve_sales_id WHERE reserve_sales_detail_id='$id'") AS $d){
                $data['stl_id']=$d->res_short_name;
                $data['address']=$this->super_model->select_column_where("reserve_participant","res_registered_address","res_billing_id",$d->res_billing_id);
                $data['tin']=$this->super_model->select_column_where("reserve_participant","res_tin","res_billing_id",$d->res_billing_id);
                // $create_date = $this->super_model->select_column_where("sales_transaction_head", "create_date", "sales_id", $d->sales_id);
                if(!empty($d->res_company_name)){
                    $data['company_name']=$d->res_company_name;
                }else{
                    $data['company_name']=$this->super_model->select_column_where("reserve_participant", "res_participant_name", "res_billing_id", $d->res_billing_id);
                }
                $data['billing_from']=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_from","reserve_sales_id",$d->reserve_sales_id);
                $data['billing_to']=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_to","reserve_sales_id",$d->reserve_sales_id);
                $data['transaction_date']=$this->super_model->select_column_where("reserve_sales_transaction_head","res_transaction_date","reserve_sales_id",$d->reserve_sales_id);
                $data['reference_number']=$this->super_model->select_column_where("reserve_sales_transaction_head","res_reference_number","reserve_sales_id",$d->reserve_sales_id);
                $data['or_no']=$d->res_serial_no;
                $participant_id = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$d->res_billing_id);
                $data['participant_id'] = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$d->res_billing_id);

                $data['billing_month'] = date('my',strtotime($d->res_transaction_date));
                $data['date_uploaded'] = date('Ymd',strtotime($d->res_create_date));
                $data['refno'] = preg_replace("/[^0-9]/", "",$d->res_reference_number);
                $h=0;
                foreach($this->super_model->select_custom_where("reserve_subparticipant","res_participant_id='$participant_id'") AS $s){
                    $data['participant_id_sub'][$h]=$s->res_participant_id;
                    $billing_id=$this->super_model->select_column_where("reserve_participant","res_billing_id","res_participant_id",$s->res_sub_participant);
                    $vatable_sales_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_vatable_sales","res_serial_no='$d->res_serial_no' AND res_billing_id='$billing_id' AND res_sales_id='$d->res_sales_id'");
                    $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_vat_on_sales","res_serial_no='$d->res_serial_no' AND res_billing_id='$billing_id' AND res_sales_id='$d->res_sales_id'");
                    $ewt_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_ewt","res_serial_no='$d->res_serial_no' AND res_billing_id='$billing_id' AND res_sales_id='$d->res_sales_id'");
                    $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_zero_rated_ecozones","res_serial_no='$d->res_serial_no' AND res_billing_id='$billing_id' AND res_sales_id='$d->res_sales_id'");
                    $zero_rated_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_zero_rated","res_serial_no='$d->res_serial_no' AND res_billing_id='$billing_id' AND res_sales_id='$d->res_sales_id'");
                    $h++;
                }

            $sum_vatable_sales=array_sum($vatable_sales_bs);
            $sum_zero_rated_ecozone=array_sum($zero_rated_ecozone_bs);
            $sum_vat_on_sales=array_sum($vat_on_sales_bs);
            $sum_ewt=array_sum($ewt_bs);
            $sum_zero_rated=array_sum($zero_rated_bs);
            
            $data['total_vs']=$d->res_vatable_sales + $sum_vatable_sales;
            $data['total_zr']=$d->res_zero_rated_sales + $sum_zero_rated;
            $data['total_zra']=$d->res_zero_rated_ecozones + $sum_zero_rated_ecozone;
            $data['total_vos']=$d->res_vat_on_sales + $sum_vat_on_sales;
            $data['total_ewt']=$d->res_ewt + $sum_ewt;
            }
        $this->load->view('sales/reserve_sales_wesm_pdf_or',$data);
    }

    public function reserve_sales_wesm_pdf_si_bulk(){
        $ref_no=$this->uri->segment(3);
        $due_date=$this->uri->segment(4);
        $in_ex_sub=$this->uri->segment(5);
        $billfrom=$this->uri->segment(6);
        $billto=$this->uri->segment(7);
        $participants=$this->uri->segment(8);

        $sql='';
        if($ref_no!='null'){
            $sql.= "sh.res_reference_number = '$ref_no' AND ";
        }

        if($due_date!='null'){
            $sql.= "sh.res_due_date = '$due_date' AND ";
        }

        if($billfrom!='null' && $billto!='null'){ 
            $sql.= " ((sh.res_billing_from BETWEEN '$billfrom' AND '$billto') OR (sh.res_billing_to BETWEEN '$billfrom' AND '$billto'))  AND ";
        }

        if(!empty($participants) && $participants!='null'){
            $par=array();
            foreach($this->super_model->select_custom_where('reserve_participant',"res_tin='$participants'") AS $p){
                $par[]="'".$p->res_settlement_id."'";
            }
            $imp=implode(',',$par);
            $sql.= " sd.res_short_name IN($imp) AND ";
        }
        $query=substr($sql,0,-4);
        $qu = "WHERE sd.res_bulk_pdf_flag = '0' AND res_serial_no != '' AND res_saved = '1' AND ".$query;

        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        
            $data['details']=array();
            foreach($this->super_model->custom_query("SELECT * FROM reserve_sales_transaction_details sd INNER JOIN reserve_sales_transaction_head sh ON sd.reserve_sales_id=sh.reserve_sales_id $qu GROUP BY res_serial_no LIMIT 10") AS $d){
                $stl_id=$d->res_short_name;
                $address=$this->super_model->select_column_where("reserve_participant","res_registered_address","res_billing_id",$d->res_billing_id);
                $tin=$this->super_model->select_column_where("reserve_participant","res_tin","res_billing_id",$d->res_billing_id);
                if(!empty($d->company_name)){
                    $comp_name=$d->company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("reserve_participant", "res_participant_name", "res_billing_id", $d->res_billing_id);
                }
                $billing_from=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_from","reserve_sales_id",$d->reserve_sales_id);
                $billing_to=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_to","reserve_sales_id",$d->reserve_sales_id);
                $transaction_date=$this->super_model->select_column_where("reserve_sales_transaction_head","res_transaction_date","reserve_sales_id",$d->reserve_sales_id);
                $reference_number=$this->super_model->select_column_where("reserve_sales_transaction_head","res_reference_number","reserve_sales_id",$d->reserve_sales_id);
                $or_no=$d->res_serial_no;
                $participant_id = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$d->res_billing_id);
                $billing_month = date('my',strtotime($d->res_transaction_date));
                $date_uploaded = date('Ymd',strtotime($d->res_create_date));
                $refno = preg_replace("/[^0-9]/", "",$d->res_reference_number);

                $vatable_sales_bs=$this->super_model->select_sum_where("reserve_sales_transaction_details","res_vatable_sales","res_serial_no='$d->res_serial_no' AND reserve_sales_id='$d->reserve_sales_id'");
                $vat_on_sales_bs=$this->super_model->select_sum_where("reserve_sales_transaction_details","res_vat_on_sales","res_serial_no='$d->res_serial_no' AND reserve_sales_id='$d->reserve_sales_id'");
                $ewt_bs=$this->super_model->select_sum_where("reserve_sales_transaction_details","res_ewt","res_serial_no='$d->res_serial_no' AND reserve_sales_id='$d->reserve_sales_id'");
                $zero_rated_ecozone_bs=$this->super_model->select_sum_where("reserve_sales_transaction_details","res_zero_rated_ecozones","res_serial_no='$d->res_serial_no' AND reserve_sales_id='$d->reserve_sales_id'");
                $zero_rated_bs=$this->super_model->select_sum_where("reserve_sales_transaction_details","res_zero_rated_sales","res_serial_no='$d->res_serial_no' AND reserve_sales_id='$d->reserve_sales_id'");

                $total_vs=$vatable_sales_bs;
                $total_zr=$zero_rated_bs;
                $total_zra=$zero_rated_ecozone_bs;
                $total_vos=$vat_on_sales_bs;
                $total_ewt=$ewt_bs;
                $total_sales = $total_vs + $total_zra + $total_vos;
                $net_of_vat = $total_vs + $total_zra;
                $total_amount_due = ($total_vs + $total_zra + $total_vos) - $total_ewt;

                $data['details'][]=array(
                    'sales_id'=>$d->reserve_sales_id,
                    'stl_id'=>$d->res_short_name,
                    'company_name'=>$comp_name,
                    'date'=>$d->res_transaction_date,
                    'transaction_date'=>$transaction_date,
                    'address'=>$address,
                    'tin'=>$tin,
                    'or_no'=>$d->res_serial_no,
                    'ref_no'=>$d->res_reference_number,
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

        $this->load->view('sales/reserve_sales_wesm_pdf_si_bulk',$data);
    }


    public function reserve_sales_wesm_pdf_si(){
        $id=$this->uri->segment(3);

        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        
            $vatable_sales_bs=array();
            $vat_on_sales_bs=array();
            $ewt_bs=array();
            $zero_rated_ecozone_bs=array();
            $zero_rated_bs=array();
                foreach($this->super_model->custom_query("SELECT * FROM reserve_sales_transaction_details sd INNER JOIN reserve_sales_transaction_head sh ON sd.reserve_sales_id=sh.reserve_sales_id WHERE reserve_sales_detail_id='$id'") AS $d){
                $data['stl_id']=$d->res_short_name;
                $data['address']=$this->super_model->select_column_where("reserve_participant","res_registered_address","res_billing_id",$d->res_billing_id);
                $data['tin']=$this->super_model->select_column_where("reserve_participant","res_tin","res_billing_id",$d->res_billing_id);
                if(!empty($d->res_company_name)){
                    $data['company_name']=$d->res_company_name;
                }else{
                    $data['company_name']=$this->super_model->select_column_where("reserve_participant", "res_participant_name", "res_billing_id", $d->res_billing_id);
                }
                $data['billing_from']=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_from","reserve_sales_id",$d->reserve_sales_id);
                $data['billing_to']=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_to","reserve_sales_id",$d->reserve_sales_id);
                $data['transaction_date']=$this->super_model->select_column_where("reserve_sales_transaction_head","res_transaction_date","reserve_sales_id",$d->reserve_sales_id);
                $data['reference_number']=$this->super_model->select_column_where("reserve_sales_transaction_head","res_reference_number","reserve_sales_id",$d->reserve_sales_id);
                $data['or_no']=$d->res_serial_no;
                $participant_id = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$d->res_billing_id);
                $data['participant_id'] = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$d->res_billing_id);

                $data['billing_month'] = date('my',strtotime($d->res_transaction_date));
                $data['date_uploaded'] = date('Ymd',strtotime($d->res_create_date));
                $data['refno'] = preg_replace("/[^0-9]/", "",$d->res_reference_number);
                $h=0;
                foreach($this->super_model->select_custom_where("reserve_subparticipant","res_participant_id='$participant_id'") AS $s){
                    $data['participant_id_sub'][$h]=$s->res_participant_id;
                    $billing_id=$this->super_model->select_column_where("reserve_participant","res_billing_id","res_participant_id",$s->res_sub_participant);
                    $vatable_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vatable_sales","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","vat_on_sales","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $ewt_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","ewt","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated_ecozones","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $zero_rated_bs[]=$this->super_model->select_column_custom_where("sales_transaction_details","zero_rated","serial_no='$d->serial_no' AND billing_id='$billing_id' AND sales_id='$d->sales_id'");
                    $h++;
                }

            $sum_vatable_sales=array_sum($vatable_sales_bs);
            $sum_zero_rated_ecozone=array_sum($zero_rated_ecozone_bs);
            $sum_vat_on_sales=array_sum($vat_on_sales_bs);
            $sum_ewt=array_sum($ewt_bs);
            $sum_zero_rated=array_sum($zero_rated_bs);
            
            $data['total_vs']=$d->res_vatable_sales + $sum_vatable_sales;
            $data['total_zr']=$d->res_zero_rated_sales + $sum_zero_rated;
            $data['total_zra']=$d->res_zero_rated_ecozones + $sum_zero_rated_ecozone;
            $data['total_vos']=$d->res_vat_on_sales + $sum_vat_on_sales;
            $data['total_ewt']=$d->res_ewt + $sum_ewt;
            }
        $this->load->view('sales/reserve_sales_wesm_pdf_si',$data);
    }

    public function export_not_download_reserve_sales_wesm(){
        $ref_no=$this->uri->segment(3);
        $due_date=$this->uri->segment(4);
        $in_ex_sub=$this->uri->segment(5);
        $billfrom=$this->uri->segment(6);
        $billto=$this->uri->segment(7);
        $participants=$this->uri->segment(8);

            $sql='';
            if($ref_no!='null'){
                $sql.= "sh.res_reference_number = '$ref_no' AND ";
            }

            if($due_date!='null'){
                $sql.= "sh.res_due_date = '$due_date' AND ";
            }

            if($billfrom!='null' && $billto!='null'){ 
                $sql.= " ((sh.res_billing_from BETWEEN '$billfrom' AND '$billto') OR (sh.res_billing_to BETWEEN '$billfrom' AND '$billto'))  AND ";
            }

            if(!empty($participants) && $participants!='null'){
               $par=array();
               foreach($this->super_model->select_custom_where('reserve_participant',"res_tin='$participants'") AS $p){
                   $par[]="'".$p->res_settlement_id."'";
               }
               $imp=implode(',',$par);
               $sql.= " sd.res_short_name IN($imp) AND ";
            }
            $query=substr($sql,0,-4);
            $qu = " WHERE sd.res_bulk_pdf_flag = '0' AND res_serial_no != '' AND res_saved = '1' AND ".$query;

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
        
       foreach($this->super_model->custom_query("SELECT res_filename FROM reserve_sales_transaction_details sd INNER JOIN reserve_sales_transaction_head sh ON sd.reserve_sales_id=sh.reserve_sales_id $qu GROUP BY res_serial_no,sd.reserve_sales_id") AS $db){
        $db_files[] = $db->res_filename;
       }

        $data['result'] = array_diff($db_files, $pdffiles);

        $result=array_diff($db_files, $pdffiles);
        $x=1;
       
        $this->load->view('sales/export_not_download_reserve_sales_wesm',$data);
        $this->load->view('template/footer');
    }

    public function reserve_sales_wesm_pdf_scan_directory(){

        $filenames = $this->input->post('filenames');
        $sql="(";
        foreach($filenames AS $f){
            $sql .= " res_filename =  '$f'  OR ";
        }
        $query=substr($sql,0,-3) . ")";

        
        $qu = "WHERE res_serial_no != '' AND res_saved = '1' AND ".$query;
        
        $data['details']=array();
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        
            $vatable_sales_bs=array();
            $vat_on_sales_bs=array();
            $ewt_bs=array();
            $zero_rated_ecozone_bs=array();
            $zero_rated_bs=array();
            $data['details']=array();
            foreach($this->super_model->custom_query("SELECT * FROM reserve_sales_transaction_details sd INNER JOIN reserve_sales_transaction_head sh ON sd.reserve_sales_id=sh.reserve_sales_id $qu GROUP BY res_serial_no LIMIT 10") AS $d){
                $stl_id=$d->res_short_name;
                $address=$this->super_model->select_column_where("reserve_participant","res_registered_address","res_billing_id",$d->res_billing_id);
                $tin=$this->super_model->select_column_where("reserve_participant","res_tin","res_billing_id",$d->res_billing_id);
                if(!empty($d->res_company_name)){
                    $comp_name=$d->res_company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("reserve_participant", "res_participant_name", "res_billing_id", $d->res_billing_id);
                }
                $billing_from=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_from","reserve_sales_id",$d->reserve_sales_id);
                $billing_to=$this->super_model->select_column_where("reserve_sales_transaction_head","res_billing_to","reserve_sales_id",$d->reserve_sales_id);
                $transaction_date=$this->super_model->select_column_where("reserve_sales_transaction_head","res_transaction_date","reserve_sales_id",$d->reserve_sales_id);
                $reference_number=$this->super_model->select_column_where("reserve_sales_transaction_head","res_reference_number","reserve_sales_id",$d->reserve_sales_id);
                $or_no=$d->res_serial_no;
                $participant_id = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$d->res_billing_id);
                $participant_id = $this->super_model->select_column_where("reserve_participant","res_participant_id","res_billing_id",$d->res_billing_id);

                $billing_month = date('my',strtotime($d->res_transaction_date));
                $date_uploaded = date('Ymd',strtotime($d->res_create_date));
                $refno = preg_replace("/[^0-9]/", "",$d->res_reference_number);
                $h=0;
                foreach($this->super_model->select_custom_where("reserve_subparticipant","res_participant_id='$participant_id'") AS $s){
                    $data['participant_id_sub'][$h]=$s->res_participant_id;
                    $billing_id=$this->super_model->select_column_where("reserve_participant","res_billing_id","res_participant_id",$s->res_sub_participant);
                    $vatable_sales_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_vatable_sales","res_serial_no='$d->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$d->reserve_sales_id'");
                    $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_vat_on_sales","res_serial_no='$d->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$d->reserve_sales_id'");
                    $ewt_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_ewt","res_serial_no='$d->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$d->reserve_sales_id'");
                    $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_zero_rated_ecozones","res_serial_no='$d->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$d->reserve_sales_id'");
                    $zero_rated_bs[]=$this->super_model->select_column_custom_where("reserve_sales_transaction_details","res_zero_rated","res_serial_no='$d->res_serial_no' AND res_billing_id='$res_billing_id' AND reserve_sales_id='$d->reserve_sales_id'");
                    $h++;
                }

                $sum_vatable_sales=array_sum($vatable_sales_bs);
                $sum_zero_rated_ecozone=array_sum($zero_rated_ecozone_bs);
                $sum_vat_on_sales=array_sum($vat_on_sales_bs);
                $sum_ewt=array_sum($ewt_bs);
                $sum_zero_rated=array_sum($zero_rated_bs);
                
                $total_vs=$d->res_vatable_sales + $sum_vatable_sales;
                $total_zr=$d->res_zero_rated_sales + $sum_zero_rated;
                $total_zra=$d->res_zero_rated_ecozones + $sum_zero_rated_ecozone;
                $total_vos=$d->res_vat_on_sales + $sum_vat_on_sales;
                $total_ewt=$d->res_ewt + $sum_ewt;
                $total_sales = $total_vs + $total_zra + $total_vos;
                $net_of_vat = $total_vs + $total_zra;
                $total_amount_due = ($total_vs + $total_zra + $total_vos) - $total_ewt;

                $data['details'][]=array(
                    'sales_id'=>$d->reserve_sales_id,
                    'stl_id'=>$d->res_short_name,
                    'company_name'=>$comp_name,
                    'date'=>$d->res_transaction_date,
                    'transaction_date'=>$transaction_date,
                    'address'=>$address,
                    'tin'=>$tin,
                    'or_no'=>$d->res_serial_no,
                    'ref_no'=>$d->res_reference_number,
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
        $this->load->view('sales/reserve_sales_wesm_pdf_scan_directory',$data);
    }

    public function update_BSeriesno(){
        //$ref_no=$this->input->post('ref_no');
        $sales_detail_id=$this->input->post('sales_detail_id');
        $new_series=$this->input->post('series_number');
        $old_series=$this->input->post('serial_no');
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

        // $this->super_model->update_custom_where("sales_transaction_details", $data_update, "sales_detail_id='$sales_detail_id'");
        // echo $ref_no;
        if($this->super_model->update_custom_where("sales_transaction_details", $data_update, "sales_detail_id='$sales_detail_id'")){
            foreach($this->super_model->select_custom_where("sales_transaction_details","sales_detail_id='$sales_detail_id'") AS $latest_data){
                $return = array('series_number'=>$latest_data->serial_no);
            }
            echo json_encode($return);
    }
}


    public function update_Res_BSeriesno(){
        $res_sales_detail_id=$this->input->post('reserve_sales_detail_id');
        $new_series=$this->input->post('series_number');
        $old_series=$this->input->post('serial_no');
        foreach($this->super_model->custom_query("SELECT * FROM reserve_sales_transaction_details WHERE reserve_sales_detail_id='$res_sales_detail_id'") AS $check){
            $count=$this->super_model->count_custom_where("reserve_sales_transaction_details","reserve_sales_detail_id = '$check->reserve_sales_detail_id' AND res_old_series_no!=''");
            if($count==0){
                $old_series_insert = $old_series;
            }else{
                $old_series_insert = $old_series.", ".$check->res_old_series_no;
            }
        }

        $data_update = array(
            'res_serial_no'=>$new_series,
            'res_old_series_no'=>$old_series_insert,
        );

        if($this->super_model->update_custom_where("reserve_sales_transaction_details", $data_update, "reserve_sales_detail_id='$res_sales_detail_id'")){
            foreach($this->super_model->select_custom_where("reserve_sales_transaction_details","reserve_sales_detail_id='$res_sales_detail_id'") AS $latest_data){
                $return = array('series_number'=>$latest_data->res_serial_no);
            }
            echo json_encode($return);
    }
}
    public function update_BSeriesnoAdjustment(){
        //$ref_no=$this->input->post('ref_no');
        $sales_detail_id=$this->input->post('sales_detail_id');
        $new_series=$this->input->post('series_number');
        $old_series=$this->input->post('serial_no');
        foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details WHERE adjustment_detail_id='$sales_detail_id'") AS $check){
            $count=$this->super_model->count_custom_where("sales_adjustment_details","adjustment_detail_id = '$check->sales_detail_id' AND old_series_no!=''");
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
        if($this->super_model->update_custom_where("sales_adjustment_details", $data_update, "adjustment_detail_id='$sales_detail_id'")){
            foreach($this->super_model->select_custom_where("sales_adjustment_details","adjustment_detail_id='$sales_detail_id'") AS $latest_data){
                $return = array('series_number'=>$latest_data->serial_no);
            }
            echo json_encode($return);
    }
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
            // if($amount == 1 && $fraction == 0){
                    $string = $dictionary[$amount];
                // }elseif($amount == 1 && $fraction != 0){
                //     $string = $dictionary[$amount]." PESO";
                // }elseif($amount > 1){
                //     $string = $dictionary[$amount];
                // }
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

/*        $decones = array( 
                    "01" => "One", 
                    "02" => "Two", 
                    "03" => "Three", 
                    "04" => "Four", 
                    "05" => "Five", 
                    "06" => "Six", 
                    "07" => "Seven", 
                    "08" => "Eight", 
                    "09" => "Nine", 
                    "10" => "Ten", 
                    "11" => "Eleven", 
                    "12" => "Twelve", 
                    "13" => "Thirteen", 
                    "14" => "Fourteen", 
                    "15" => "Fifteen", 
                    "16" => "Sixteen", 
                    "17" => "Seventeen", 
                    "18" => "Eighteen", 
                    "19" => "Nineteen" 
                    );
        $ones = array( 
                    "1" => "One",     
                    "2" => "Two", 
                    "3" => "Three", 
                    "4" => "Four",
                    "5" => "Five", 
                    "6"=> "Six", 
                    "7" => "Seven", 
                    "8" => "Eight", 
                    "9" => "Nine", 
                    "10" => "Ten", 
                    "11" => "Eleven", 
                    "12" => "Twelve", 
                    "13" => "Thirteen", 
                    "14" => "Fourteen", 
                    "15" => "Fifteen", 
                    "16" => "Sixteen", 
                    "17" => "Seventeen", 
                    "18" => "Eighteen", 
                    "19" => "Nineteen" 
                    ); 
        $tens = array( 
                    "0" => "",
                    "2" => "Twenty", 
                    "3" => "Thirty", 
                    "4" => "Forty", 
                    "5" => "Fifty", 
                    "6" => "Sixty", 
                    "7" => "Seventy", 
                    "8" => "Eighty", 
                    "9"=> "Ninety" 
                    );*/ 

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

     public function add_collection_head(){
        $data=array(
            "collection_date"=>$this->input->post('collection_date'),
            "user_id"=>$_SESSION['user_id'],
            //"date_uploaded"=>date("Y-m-d H:i:s"),
            //"adjustment"=>$adjustment

        );
        $collection_id = $this->super_model->insert_return_id("collection_head",$data);

        echo $collection_id;
    }

    public function cancel_collection(){
        $collection_id = $this->input->post('collection_id');
        $this->super_model->delete_where("collection_head", "collection_id", $collection_id);
        $this->super_model->delete_where("collection_details", "collection_id", $collection_id);
    }

    public function upload_collection()
    {
        $id=$this->uri->segment(3);
        $data['collection_id'] = $id;
        $data['collection']=array();
        if(!empty($id)){
            foreach($this->super_model->select_row_where("collection_head", "collection_id", $id) AS $h){
                $data['saved']=$h->saved;
                $data['collection_date']=$h->collection_date;
        //foreach($this->super_model->custom_query("SELECT * FROM collection_details $id") AS $col){
            foreach($this->super_model->select_row_where("collection_details","collection_id",$h->collection_id) AS $col){
            //$company_name=$this->super_model->select_column_where("participant","participant_name","settlement_id",$col->settlement_id);
            $count_series=$this->super_model->count_custom_where("collection_details","series_number='$col->series_number' AND series_number!='' AND settlement_id='$col->settlement_id' AND collection_id='$col->collection_id'");
            $sum_amount= $this->super_model->select_sum_where("collection_details","amount","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id='$col->collection_id'");
            $sum_zero_rated= $this->super_model->select_sum_where("collection_details","zero_rated","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id='$col->collection_id'");
            $sum_zero_rated_ecozone= $this->super_model->select_sum_where("collection_details","zero_rated_ecozone","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id='$col->collection_id'");
            $sum_vat = $this->super_model->select_sum_where("collection_details","vat","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id='$col->collection_id'");
            $sum_ewt= $this->super_model->select_sum_where("collection_details","ewt","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND collection_id='$col->collection_id'");
            //$sum_def_int = $this->super_model->select_sum_where("collection_details","defint","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id'");
            //$total=($col->amount + $col->zero_rated + $col->zero_rated_ecozone + $col->vat)-$col->ewt; 
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
                "or_date"=>$col->or_date,
                "billing_remarks"=>$col->billing_remarks,
                "particulars"=>$col->particulars,
                "item_no"=>$col->item_no,
                //"defint"=>$sum_def_int,
                "defint"=>$col->defint,
                "reference_no"=>$col->reference_no,
                "vat"=>$col->vat,
                "zero_rated"=>$col->zero_rated,
                "zero_rated_ecozone"=>$col->zero_rated_ecozone,
                "ewt"=>$col->ewt,
                "total"=>$col->total,
                //"company_name"=>$company_name,
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
        $this->load->view('sales/upload_collection', $data);
        $this->load->view('template/footer');
    }


    public function upload_bulk_collection(){
        $collection_id=$this->input->post('collection_id');
        $dest= realpath(APPPATH . '../uploads/excel/');
        $error_ext=0;
        if(!empty($_FILES['doc']['name'])){
            $exc= basename($_FILES['doc']['name']);
            $exc=explode('.',$exc);
            $ext1=$exc[1];
          
            
            if($ext1=='php'){
                $error_ext++;

            } 
            else {
                $filename1='bulkcollection.'.$ext1;
              
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                     $this->readBulkCollection($collection_id,$ext1);
                } 
            }
        }
    }

    public function readBulkCollection($collection_id,$doc_type){



        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();

        if($doc_type=='xlsx'){
            $inputFileName =realpath(APPPATH.'../uploads/excel/bulkcollection.xlsx');
        }else if($doc_type=='xlsm'){
            $inputFileName =realpath(APPPATH.'../uploads/excel/bulkcollection.xlsm');
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
            //"collection_date"=>$coldate,
            //"user_id"=>$_SESSION['user_id'],
            "date_uploaded"=>date('Y-m-d H:i:s'),
        );
        //$collection_id = $this->super_model->insert_return_id("collection_head", $data_head);
        $this->super_model->update_where("collection_head", $data_head, "collection_id", $collection_id);
        $a=1;
        echo $highestRow;
       for($x=6;$x<=$highestRow;$x++){

           
                //$no = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue());
            
            //if($no!='' ){

               
                  /* if($a==1){*/
                    //$itemno = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue());
              /*   } else {
                     $itemno = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getOldCalculatedValue());
                     }*/
               
                $remarks = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue() ?? '');
                if($remarks!='' ){
                $particulars = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue() ?? '');
                //$stl_id = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue());
                $stl_id = str_replace(array('_FIT'), '',$objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue());
                $buyer = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getFormattedValue() ?? '');
                $statement_no = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue() ?? '');

                $vatable_sales = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('F'.$x)->getFormattedValue());
               
                $zero_rated = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('G'.$x)->getFormattedValue());
                $zero_rated_ecozone = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('H'.$x)->getFormattedValue());
                $vat = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('I'.$x)->getFormattedValue());
                $ewt = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('J'.$x)->getFormattedValue());
                //$ewt = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('K'.$x)->getFormattedValue());
                $total = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('K'.$x)->getFormattedValue());
                //$defint = trim($objPHPExcel->getActiveSheet()->getCell('N'.$x)->getFormattedValue());
                //$series = trim($objPHPExcel->getActiveSheet()->getCell('O'.$x)->getFormattedValue());
             
                 $data_details = array(
                        'collection_id'=>$collection_id,
                        'or_date'=>$this->super_model->select_column_where("collection_head","collection_date","collection_id",$collection_id),
                        //'item_no'=>$itemno,
                        'item_no'=>$a,
                        'billing_remarks'=>$remarks,
                        'particulars'=>$particulars,
                        //'series_number'=>$series,
                        //'defint'=>$defint,
                        //'reference_no'=>$statement_no,
                        'reference_no'=>rtrim($statement_no,"S"),
                        //'settlement_id'=>$stl_id,
                        'settlement_id'=>$stl_id,
                        'buyer_fullname'=>$buyer,
                        'amount'=>$vatable_sales,
                        'vat'=>$vat,
                        'zero_rated'=>$zero_rated,
                        'zero_rated_ecozone'=>$zero_rated_ecozone,
                        'ewt'=>$ewt,
                        'total'=>$total,
                    );
                 //echo $x;
                    $this->super_model->insert_into("collection_details", $data_details);
                    $a++;
            //} 

                    //print_r($data_details);

           
        }
    }

       
        //echo "saved-".$collection_id;

           
      
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

    public function save_all_collection(){
        $collection_id = $this->input->post('collection_id');
        $data_head = array(
            'saved'=>1,
        );
        $this->super_model->update_where("collection_head",$data_head, "collection_id", $collection_id);
        //echo $collection_id;
    }

    public function sample_table(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/sample_table');
        $this->load->view('template/footer');
    }

    public function sales_wesm_adjustment(){
        $ref_no=$this->uri->segment(3);
        $due_datefrom=$this->uri->segment(4);
        $due_dateto=$this->uri->segment(5);
        $in_ex_sub=$this->uri->segment(6);
        $participants=$this->uri->segment(7);
        $data['ref_no']=$ref_no;
        $data['due_date_from']=$due_datefrom;
        $data['due_date_to']=$due_dateto;
        $data['in_ex_sub']=$in_ex_sub;
        $data['identifier_code']=$this->generateRandomString();
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT reference_number FROM sales_adjustment_head WHERE reference_number!=''");
        $data['date'] = $this->super_model->custom_query("SELECT DISTINCT due_date FROM sales_adjustment_head WHERE due_date!=''");
        $data['participant']=$this->super_model->custom_query("SELECT * FROM participant WHERE participant_name != '' GROUP BY tin ORDER BY participant_name");
        $data['participant_name']=$this->super_model->select_column_where('participant','participant_name','tin',$participants);
        $data['part_name']= $participants;
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        if($in_ex_sub==0 || $in_ex_sub=='null'){
            $sql='';
            if($ref_no!='null'){
                $sql.= "sah.reference_number = '$ref_no' AND ";
            }

            if($due_datefrom!='null' && $due_dateto!='null'){ 
                $sql.= " sah.due_date BETWEEN '$due_datefrom' AND '$due_dateto' AND ";
            }

            if(!empty($participants) && $participants!='null'){
                //$sql.= " tin = '$participant' AND "; 
               $par=array();
               foreach($this->super_model->select_custom_where('participant',"tin='$participants'") AS $p){
                   $par[]="'".$p->settlement_id."'";
               }
               $imp=implode(',',$par);
               $sql.= " sad.short_name IN($imp) AND ";
            }
            $query=substr($sql,0,-4);
            $qu = " WHERE saved='1' AND ".$query;
            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id=sah.sales_adjustment_id $qu ORDER BY serial_no ASC, item_no ASC") AS $d){
                $series_number=$this->super_model->select_column_custom_where("collection_details","series_number","reference_no='$d->reference_number' AND settlement_id='$d->short_name'");
                $old_series_no=$this->super_model->select_column_custom_where("collection_details","old_series_no","reference_no='$d->reference_number' AND settlement_id='$d->short_name'");

                // $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id", $d->sales_adjustment_id);
                // $company_name=$this->super_model->select_column_where("sales_adjustment_details", "company_name", "adjustment_detail_id", $d->adjustment_detail_id);
                if(!empty($d->company_name)){
                    $comp_name=$d->company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $d->billing_id);
                }
                $data['details'][]=array(
                    'sales_detail_id'=>$d->adjustment_detail_id,
                    'sales_adjustment_id'=>$d->sales_adjustment_id,
                    'item_no'=>$d->item_no,
                    'due_date'=>$d->due_date,
                    'reference_number'=>$d->reference_number,
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
                    // 'reference_number'=>$d->reference_number,
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
        }else if($in_ex_sub==1){
            $sql='';
            if($ref_no!='null'){
                $sql.= "sah.reference_number = '$ref_no' AND ";
            }

            if($due_datefrom!='null' && $due_dateto!='null'){ 
                $sql.= " sah.due_date BETWEEN '$due_datefrom' AND '$due_dateto' AND ";
            }

            if(!empty($participants) && $participants!='null'){
                //$sql.= " tin = '$participant' AND "; 
               $par=array();
               foreach($this->super_model->select_custom_where('participant',"tin='$participants'") AS $p){
                   $par[]="'".$p->settlement_id."'";
               }
               $imp=implode(',',$par);
               $sql.= " sad.short_name IN($imp) AND ";
            }
            $query=substr($sql,0,-4);
            $qu = " WHERE saved='1' AND ".$query;
            /*foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id=sah.sales_adjustment_id INNER JOIN participant p ON sad.billing_id=p.billing_id INNER JOIN subparticipant sp ON p.participant_id=sp.participant_id $qu GROUP BY p.participant_id") AS $d){*/
            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id=sah.sales_adjustment_id $qu ORDER BY serial_no ASC, item_no ASC") AS $d){
                $participant_id = $this->super_model->select_column_custom_where("participant","participant_id","billing_id='$d->billing_id'");
                //$sub_participant = $this->super_model->select_column_custom_where("subparticipant","sub_participant","sub_participant='$participant_id'");
                $sub_participant = $this->super_model->count_custom_where("subparticipant","sub_participant='$participant_id'");
                //echo $sub_participant. "<br>";
                //if($participant_id != $sub_participant){
                $series_number=$this->super_model->select_column_custom_where("collection_details","series_number","reference_no='$d->reference_number' AND settlement_id='$d->short_name'");
                $old_series_no=$this->super_model->select_column_custom_where("collection_details","old_series_no","reference_no='$d->reference_number' AND settlement_id='$d->short_name'");
                if($sub_participant==0){
                $data['details'][]=array(
                    'sales_detail_id'=>$d->adjustment_detail_id,
                    'sales_adjustment_id'=>$d->sales_adjustment_id,
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
                    'print_counter'=>$d->print_counter,
                    'ewt_amount'=>$d->ewt_amount,
                    'original_copy'=>$d->original_copy,
                    'scanned_copy'=>$d->scanned_copy
                );
                }
            }
        }
        $this->load->view('sales/sales_wesm_adjustment',$data);
        $this->load->view('template/footer');
    }

     public function sales_wesm_adjustment_pdf_or(){
        $id=$this->uri->segment(3);

        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        
                foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sd INNER JOIN sales_adjustment_head sh ON sd.sales_adjustment_id=sh.sales_adjustment_id WHERE adjustment_detail_id='$id' AND sh.saved != '0' AND serial_no != '0' GROUP BY serial_no") AS $d){
                $data['stl_id']=$d->short_name;
                $data['address']=$this->super_model->select_column_where("participant","registered_address","billing_id",$d->billing_id);
                $data['tin']=$this->super_model->select_column_where("participant","tin","billing_id",$d->billing_id);
                $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id", $d->sales_adjustment_id);
                if(!empty($d->company_name)){
                     $data['company_name']=$d->company_name;
                }else{
                    $data['company_name']=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $d->billing_id);
                }
                $data['billing_from']=$this->super_model->select_column_where("sales_adjustment_head","billing_from","sales_adjustment_id",$d->sales_adjustment_id);
                $data['billing_to']=$this->super_model->select_column_where("sales_adjustment_head","billing_to","sales_adjustment_id",$d->sales_adjustment_id);
                $data['transaction_date']=$this->super_model->select_column_where("sales_adjustment_head","transaction_date","sales_adjustment_id",$d->sales_adjustment_id);

                $reference_number = $this->super_model->custom_query("SELECT * FROM sales_adjustment_details sd INNER JOIN sales_adjustment_head sh ON sd.sales_adjustment_id=sh.sales_adjustment_id WHERE serial_no = '$d->serial_no' AND serial_no != '0' GROUP BY reference_number");
                // $data['reference_number']=$this->super_model->select_column_where("sales_adjustment_head","reference_number","sales_adjustment_id",$d->sales_adjustment_id);

                $all_ref=array();
                foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sd INNER JOIN sales_adjustment_head sh ON sd.sales_adjustment_id=sh.sales_adjustment_id WHERE serial_no = '$d->serial_no' AND serial_no != '0' GROUP BY reference_number") AS $p){
                    $all_ref[]=$p->reference_number;
                }

                $data['reference_number']=implode(",", $all_ref);
                $data['or_no']=$d->serial_no;
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);
                $data['participant_id'] = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);

                $data['billing_month'] = date('my',strtotime($d->transaction_date));
                $data['date_uploaded'] = date('Ymd',strtotime($d->create_date));
                $data['refno'] = preg_replace("/[^0-9]/", "",$d->reference_number);
                $data['csrnumber'] = $d->collection_report_number;

                $vatable_sales= $this->super_model->select_sum_where("sales_adjustment_details","vatable_sales","serial_no='$d->serial_no' AND sales_adjustment_id='$d->sales_adjustment_id'");
                $zero_rated_sales= $this->super_model->select_sum_where("sales_adjustment_details","zero_rated_sales","serial_no='$d->serial_no' AND sales_adjustment_id='$d->sales_adjustment_id'");
                $zero_rated_ecozones= $this->super_model->select_sum_where("sales_adjustment_details","zero_rated_ecozones","serial_no='$d->serial_no' AND sales_adjustment_id='$d->sales_adjustment_id'");
                $vat_on_sales= $this->super_model->select_sum_where("sales_adjustment_details","vat_on_sales","serial_no='$d->serial_no' AND sales_adjustment_id='$d->sales_adjustment_id'");
                $ewt= $this->super_model->select_sum_where("sales_adjustment_details","ewt","serial_no='$d->serial_no' AND sales_adjustment_id='$d->sales_adjustment_id'");

                $data['total_vs'] = $vatable_sales;
                $data['total_zr'] = $zero_rated_sales;
                $data['total_zra'] = $zero_rated_ecozones;
                $data['total_vos'] = $vat_on_sales;
                $data['total_ewt'] = $ewt;
            }

        $this->load->view('sales/sales_wesm_adjustment_pdf_or',$data);
    }

    public function sales_wesm_adjustment_pdf_or_bulk(){
        $ref_no=$this->uri->segment(3);
        $due_datefrom=$this->uri->segment(4);
        $due_dateto=$this->uri->segment(5);
        $in_ex_sub=$this->uri->segment(6);
        $participants=$this->uri->segment(7);

            $sql='';
            if($ref_no!='null'){
                $sql.= "sh.reference_number = '$ref_no' AND ";
            }

            if($due_datefrom!='null' && $due_dateto!='null'){ 
                $sql.= " sh.due_date BETWEEN '$due_datefrom' AND '$due_dateto' AND ";
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
            $qu = " WHERE sd.bulk_pdf_flag = '0' AND serial_no != '' AND saved = '1' AND ".$query;

            $data['details']=array();
            $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
            $data['timestamp'] = date('Ymd');
            
           foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sd INNER JOIN sales_adjustment_head sh ON sd.sales_adjustment_id=sh.sales_adjustment_id $qu GROUP BY serial_no LIMIT 10") AS $d){
                if(!empty($d->company_name)){
                    $comp_name=$d->company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $d->billing_id);
                }

                $address = $this->super_model->select_column_where("participant","registered_address","billing_id",$d->billing_id);
                $tin = $this->super_model->select_column_where("participant","tin","billing_id",$d->billing_id);
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);

                // $h=0;
                // $vatable_sales_bs=array();
                // $vat_on_sales_bs=array();
                // $ewt_bs=array();
                // $zero_rated_ecozone_bs=array();
                // $zero_rated_bs=array();
                // foreach($this->super_model->select_custom_where("subparticipant","participant_id='$participant_id'") AS $s){
                //     $billing_id=$this->super_model->select_column_where("participant","billing_id","participant_id",$s->sub_participant);
                //     $vatable_sales_bs[]=$this->super_model->select_column_custom_where("sales_adjustment_details","vatable_sales","serial_no='$d->serial_no'");
                //     $vat_on_sales_bs[]=$this->super_model->select_column_custom_where("sales_adjustment_details","vat_on_sales","serial_no='$d->serial_no'");
                //     $ewt_bs[]=$this->super_model->select_column_custom_where("sales_adjustment_details","ewt","serial_no='$d->serial_no'");
                //     $zero_rated_ecozone_bs[]=$this->super_model->select_column_custom_where("sales_adjustment_details","zero_rated_ecozones","serial_no='$d->serial_no'");
                //     $zero_rated_bs[]=$this->super_model->select_column_custom_where("sales_adjustment_details","zero_rated","serial_no='$d->serial_no'");
                //     $h++;
                // }

                $vatable_sales= $this->super_model->select_sum_where("sales_adjustment_details","vatable_sales","serial_no='$d->serial_no' AND sales_adjustment_id='$d->sales_adjustment_id'");
                $zero_rated_sales= $this->super_model->select_sum_where("sales_adjustment_details","zero_rated_sales","serial_no='$d->serial_no' AND sales_adjustment_id='$d->sales_adjustment_id'");
                $zero_rated_ecozones= $this->super_model->select_sum_where("sales_adjustment_details","zero_rated_ecozones","serial_no='$d->serial_no' AND sales_adjustment_id='$d->sales_adjustment_id'");
                $vat_on_sales= $this->super_model->select_sum_where("sales_adjustment_details","vat_on_sales","serial_no='$d->serial_no' AND sales_adjustment_id='$d->sales_adjustment_id'");
                $ewt= $this->super_model->select_sum_where("sales_adjustment_details","ewt","serial_no='$d->serial_no' AND sales_adjustment_id='$d->sales_adjustment_id'");

                //  $sum_vatable_sales=array_sum($vatable_sales_bs);
                //  $sum_vat_on_sales=array_sum($vat_on_sales_bs);
                //  $sum_ewt=array_sum($ewt_bs);
                //  $sum_zero_rated_ecozone=array_sum($zero_rated_ecozone_bs);
                //  $sum_zero_rated=array_sum($zero_rated_bs);


                // $total_vs = $d->vatable_sales + $sum_vatable_sales;
                // $total_zr = $d->zero_rated_sales + $sum_zero_rated;
                // $total_zra = $d->zero_rated_ecozones + $sum_zero_rated_ecozone;
                // $total_vos = $d->vat_on_sales + $sum_vat_on_sales;
                // $total_ewt = $d->ewt + $sum_ewt;

                if($ref_no!='null'){
                    $reference_number = $ref_no;
                }else{
                    $reference_number = $d->reference_number;
                }

                $billing_month = date('my',strtotime($d->transaction_date));
                $date_uploaded = date('Ymd',strtotime($d->create_date));
                $refno = preg_replace("/[^0-9]/", "",$reference_number);

                 $all_ref=array();
                foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sd INNER JOIN sales_adjustment_head sh ON sd.sales_adjustment_id=sh.sales_adjustment_id WHERE serial_no = '$d->serial_no' AND serial_no != '0' GROUP BY reference_number") AS $p){
                    $all_ref[]=$p->reference_number;
                }

                    $data['details'][]=array(
                        'sales_id'=>$d->sales_adjustment_id,
                        'stl_id'=>$d->short_name,
                        'buyer'=>$comp_name,
                        'date'=>$d->transaction_date,
                        'address'=>$address,
                        'tin'=>$tin,
                        'or_no'=>$d->serial_no,
                        'ref_no'=>$d->reference_number,
                        'all_ref_no'=>implode(",", $all_ref),
                        'billing_month'=>$billing_month,
                        'date_uploaded'=>$date_uploaded,
                        'refno'=>$refno,
                        'csrnumber'=>$d->collection_report_number,
                        // 'total_vs'=>$total_vs,
                        // 'total_zr'=>$total_zr,
                        // 'total_zra'=>$total_zra,
                        // 'total_vos'=>$total_vos,
                        // 'total_ewt'=>$total_ewt,
                        'total_vs'=>$vatable_sales,
                        'total_zr'=>$zero_rated_sales,
                        'total_zra'=>$zero_rated_ecozones,
                        'total_vos'=>$vat_on_sales,
                        'total_ewt'=>$ewt,
                    );
            }

        $this->load->view('sales/sales_wesm_adjustment_pdf_or_bulk',$data);
    }

        public function update_sales_wesm_adjustment_flag(){
        $serial_no = $this->input->post('serial_no');
        $sales_adjustment_id = $this->input->post('sales_id');
        $filename = $this->input->post('filename');
        $data_update = array(
                "bulk_pdf_flag"=>1,
                "filename"=>$filename
            );
            // $this->super_model->update_custom_where("sales_adjustment_details", $data_update, "serial_no='$serial_no' AND sales_adjustment_id='$sales_adjustment_id'");
            $this->super_model->update_custom_where("sales_adjustment_details", $data_update, "serial_no='$serial_no'");
    }

        public function export_not_download_sales_wesm_adjustment()
    {

        $ref_no=$this->uri->segment(3);
        $due_datefrom=$this->uri->segment(4);
        $due_dateto=$this->uri->segment(5);
        $in_ex_sub=$this->uri->segment(6);
        $participants=$this->uri->segment(7);

            $sql='';
            if($ref_no!='null'){
                $sql.= "sh.reference_number = '$ref_no' AND ";
            }

            if($due_datefrom!='null' && $due_dateto!='null'){ 
                $sql.= " sh.due_date BETWEEN '$due_datefrom' AND '$due_dateto' AND ";
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
            $qu = " WHERE sd.bulk_pdf_flag = '0' AND serial_no != '' AND saved = '1' AND ".$query;

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
        
       foreach($this->super_model->custom_query("SELECT filename FROM sales_adjustment_details sd INNER JOIN sales_adjustment_head sh ON sd.sales_adjustment_id=sh.sales_adjustment_id $qu GROUP BY serial_no,short_name,sh.sales_adjustment_id") AS $db){
        $db_files[] = $db->filename;
       }

        $data['result'] = array_diff($db_files, $pdffiles);

        $result=array_diff($db_files, $pdffiles);
        $x=1;
       
        $this->load->view('sales/export_not_download_sales_wesm_adjustment',$data);
        $this->load->view('template/footer');
    }

    public function sales_wesm_adjustment_pdf_scan_directory(){

        $filenames = $this->input->post('filenames');
        $sql="(";
        foreach($filenames AS $f){
            $sql .= " filename =  '$f'  OR ";
        }
        $query=substr($sql,0,-3) . ")";

        
        $qu = "serial_no != '' AND saved = '1' AND ".$query;

        
        $data['details']=array();
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        $data['timestamp'] = date('Ymd');


           foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sd INNER JOIN sales_adjustment_head sh ON sd.sales_adjustment_id=sh.sales_adjustment_id $qu GROUP BY serial_no LIMIT 10") AS $d){
                if(!empty($d->company_name)){
                    $comp_name=$d->company_name;
                }else{
                    $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $d->billing_id);
                }

                $address = $this->super_model->select_column_where("participant","registered_address","billing_id",$d->billing_id);
                $tin = $this->super_model->select_column_where("participant","tin","billing_id",$d->billing_id);
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$d->billing_id);

                $vatable_sales= $this->super_model->select_sum_where("sales_adjustment_details","vatable_sales","serial_no='$d->serial_no'");
                $zero_rated_sales= $this->super_model->select_sum_where("sales_adjustment_details","zero_rated_sales","serial_no='$d->serial_no'");
                $zero_rated_ecozones= $this->super_model->select_sum_where("sales_adjustment_details","zero_rated_ecozones","serial_no='$d->serial_no'");
                $vat_on_sales= $this->super_model->select_sum_where("sales_adjustment_details","vat_on_sales","serial_no='$d->serial_no'");
                $ewt= $this->super_model->select_sum_where("sales_adjustment_details","ewt","serial_no='$d->serial_no'");


                if($ref_no!='null'){
                    $reference_number = $ref_no;
                }else{
                    $reference_number = $d->reference_number;
                }

                $billing_month = date('my',strtotime($d->transaction_date));
                $date_uploaded = date('Ymd',strtotime($d->create_date));
                $refno = preg_replace("/[^0-9]/", "",$reference_number);

                 $all_ref=array();
                foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sd INNER JOIN sales_adjustment_head sh ON sd.sales_adjustment_id=sh.sales_adjustment_id WHERE serial_no = '$d->serial_no' AND serial_no != '0' GROUP BY reference_number") AS $p){
                    $all_ref[]=$p->reference_number;
                }

                    $data['details'][]=array(
                        'sales_id'=>$d->sales_adjustment_id,
                        'stl_id'=>$d->short_name,
                        'buyer'=>$comp_name,
                        'date'=>$d->transaction_date,
                        'address'=>$address,
                        'tin'=>$tin,
                        'or_no'=>$d->serial_no,
                        'ref_no'=>$d->reference_number,
                        'all_ref_no'=>implode(",", $all_ref),
                        'billing_month'=>$billing_month,
                        'date_uploaded'=>$date_uploaded,
                        'refno'=>$refno,
                        'csrnumber'=>$d->collection_report_number,
                        'total_vs'=>$vatable_sales,
                        'total_zr'=>$zero_rated_sales,
                        'total_zra'=>$zero_rated_ecozones,
                        'total_vos'=>$vat_on_sales,
                        'total_ewt'=>$ewt,
                    );
            }

       
        $this->load->view('sales/sales_wesm_adjustment_pdf_scan_directory',$data);
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
        $sales_adjustment_id=$this->super_model->select_column_where("sales_adjustment_head","sales_adjustment_id","adjust_identifier",$identifier);
        $data['count_name'] = $this->super_model->count_custom_where("sales_adjustment_details", "company_name ='' AND sales_adjustment_id ='$sales_adjustment_id'");
        $data['count_empty_actual']=0;
        foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id=sah.sales_adjustment_id WHERE adjust_identifier='$identifier'") AS $d){
            $data['count_empty_actual']=$this->super_model->count_custom_where('sales_adjustment_details',"sales_adjustment_id='$sales_adjustment_id' AND billing_id IS NULL");
            //$unique_bill_id = $this->super_model->select_column_custom_where("participant", "billing_id", "actual_billing_id = '$d->billing_id' AND settlement_id = '$d->short_name'");   
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
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
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
                            $remarks = trim($objPHPExcel->getActiveSheet()->getCell('F2')->getFormattedValue());
                            //$remarks = $this->input->post('remarks['.$x.']');
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
                                
                                $itemno = trim($objPHPExcel->getActiveSheet()->getCell('A'.$z)->getFormattedValue());
                                $shortname = str_replace(array(' '), '',$objPHPExcel->getActiveSheet()->getCell('B'.$z)->getFormattedValue());
                                if($shortname!="" || !empty($shortname)){
                                    $actual_billing_id = str_replace(array(' '), '',$objPHPExcel->getActiveSheet()->getCell('C'.$z)->getFormattedValue());

                                    $company_name =trim($objPHPExcel->getActiveSheet()->getCell('D'.$z)->getOldCalculatedValue());
                                    $tin = trim($objPHPExcel->getActiveSheet()->getCell('E'.$z)->getFormattedValue());

                                    $unique_bill_id = $this->super_model->select_column_custom_where("participant", "billing_id", "actual_billing_id = '$actual_billing_id' AND settlement_id = '$shortname' AND tin = '$tin'");

                                    $fac_type = trim($objPHPExcel->getActiveSheet()->getCell('F'.$z)->getFormattedValue());
                                    $wht_agent = trim($objPHPExcel->getActiveSheet()->getCell('G'.$z)->getFormattedValue());
                                    $ith = trim($objPHPExcel->getActiveSheet()->getCell('H'.$z)->getFormattedValue());
                                    $non_vatable = trim($objPHPExcel->getActiveSheet()->getCell('I'.$z)->getFormattedValue());
                                    $zero_rated = trim($objPHPExcel->getActiveSheet()->getCell('J'.$z)->getFormattedValue());
                                    $vatable_sales = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('K'.$z)->getFormattedValue());
                                    $zero_rated_sales = $objPHPExcel->getActiveSheet()->getCell('K'.$z)->getFormattedValue();
                                    $zero_rated_ecozone = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('L'.$z)->getFormattedValue());
                                    $vat_on_sales = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('M'.$z)->getFormattedValue());
                                    $ewt = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('N'.$z)->getFormattedValue());
                                    // $total_amount = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('O'.$z)->getOldCalculatedValue());
                                    $total_amount = ((double)$vatable_sales+(double)$zero_rated_ecozone+(double)$vat_on_sales)-(double)$ewt;
                                    //$series_no = $objPHPExcel->getActiveSheet()->getCell('P'.$z)->getFormattedValue();


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
                                        'zero_rated_ecozones'=>$zero_rated_ecozone,
                                        'ewt'=>$ewt,
                                        'total_amount'=>$total_amount,
                                        'balance'=>$total_amount,
                                        //'serial_no'=>$series_no,
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

public function cancel_multiple_sales(){
    $adjust_identifier = $this->input->post('save_sales_adjustment');
    foreach($this->super_model->select_row_where("sales_adjustment_head","adjust_identifier",$adjust_identifier) AS $del){
        $this->super_model->delete_where("sales_adjustment_head", "sales_adjustment_id", $del->sales_adjustment_id);
        $this->super_model->delete_where("sales_adjustment_details", "sales_adjustment_id", $del->sales_adjustment_id);
    }
}


public function upload_sales_adjustment_test(){
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
    $this->load->view('sales/upload_sales_adjustment_test',$data);
    $this->load->view('template/footer');
}

    public function display_upload_adjust(){
        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();
        $imageData = '';
        $dest= realpath(APPPATH . '../uploads/excel/');
        $adjust_identifier = $this->input->post('adjust_identifier');
        $x=0;
        foreach ($_FILES['file']['name'] as $keys => $values) {
            $error_ext=0;
            if(!empty($_FILES['file']['name'][$keys])){
                $exc= basename($_FILES['file']['name'][$keys]);
                $exc=explode('.',$exc);
                $ext1=$exc[1];
                if($ext1=='php' || $ext1!='xlsx'){
                    $error_ext++;
                }else {
                    $filename1='wesm_sales_adjust'.$x.".".$ext1;
                    if (move_uploaded_file($_FILES['file']['tmp_name'][$keys], $dest.'/'.$filename1)) {
                        $inputFileName =realpath(APPPATH.'../uploads/excel/wesm_sales_adjust'.$x.'.xlsx');
                        try {
                            $inputFileType = io_factory::identify($inputFileName);
                            $objReader = io_factory::createReader($inputFileType);
                            $objPHPExcel = $objReader->load($inputFileName);
                        } 
                        catch(Exception $e) {
                            die('Error loading file"'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
                        }
                        $objPHPExcel->setActiveSheetIndex(2);
                        $reference_number = trim($objPHPExcel->getActiveSheet()->getCell('A2')->getFormattedValue() ?? '');
                        $transaction_date = trim($objPHPExcel->getActiveSheet()->getCell('B2')->getFormattedValue() ?? '');
                        $billing_from = trim($objPHPExcel->getActiveSheet()->getCell('C2')->getFormattedValue() ?? '');
                        $billing_to = trim($objPHPExcel->getActiveSheet()->getCell('D2')->getFormattedValue() ?? '');
                        $due_date = trim($objPHPExcel->getActiveSheet()->getCell('E2')->getFormattedValue() ?? '');
                        $remarks = trim($objPHPExcel->getActiveSheet()->getCell('F2')->getFormattedValue() ?? '');
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
                        $highestRow = $highestRow;
                        // $highestRow = $highestRow-1;
                        $y=1;
                        for($z=4;$z<$highestRow;$z++){
                            $itemno = trim($objPHPExcel->getActiveSheet()->getCell('A'.$z)->getFormattedValue() ?? '');
                            $shortname = str_replace(array(' '), '',$objPHPExcel->getActiveSheet()->getCell('B'.$z)->getFormattedValue() ?? '');
                            if($shortname!="" || !empty($shortname)){
                                $actual_billing_id = str_replace(array(' '), '',$objPHPExcel->getActiveSheet()->getCell('C'.$z)->getFormattedValue() ?? '');

                                // $company_name =trim($objPHPExcel->getActiveSheet()->getCell('D'.$z)->getOldCalculatedValue() ?? '');
                                $company_name =trim($objPHPExcel->getActiveSheet()->getCell('D'.$z)->getFormattedValue() ?? '');
                                $tin = trim($objPHPExcel->getActiveSheet()->getCell('E'.$z)->getFormattedValue() ?? '');
                                // $tin = trim($objPHPExcel->getActiveSheet()->getCell('E'.$z)->getFormattedValue() ?? '');

                                $unique_bill_id = $this->super_model->select_column_custom_where("participant", "billing_id", "actual_billing_id = '$actual_billing_id' AND settlement_id = '$shortname' AND tin = '$tin'");

                                $fac_type = trim($objPHPExcel->getActiveSheet()->getCell('F'.$z)->getFormattedValue() ?? '');
                                $wht_agent = trim($objPHPExcel->getActiveSheet()->getCell('G'.$z)->getFormattedValue() ?? '');
                                $ith = trim($objPHPExcel->getActiveSheet()->getCell('H'.$z)->getFormattedValue() ?? '');
                                $non_vatable = trim($objPHPExcel->getActiveSheet()->getCell('I'.$z)->getFormattedValue() ?? '');
                                $zero_rated = trim($objPHPExcel->getActiveSheet()->getCell('J'.$z)->getFormattedValue() ?? '');
                                $vatable_sales = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('K'.$z)->getFormattedValue());
                                $zero_rated_sales = $objPHPExcel->getActiveSheet()->getCell('K'.$z)->getFormattedValue();
                                $zero_rated_ecozone = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('L'.$z)->getFormattedValue());
                                $vat_on_sales = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('M'.$z)->getFormattedValue());
                                $ewt = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('N'.$z)->getFormattedValue());
                                // $total_amount = str_replace(array( '(', ')',','), '',$objPHPExcel->getActiveSheet()->getCell('O'.$z)->getOldCalculatedValue());
                                $total_amount = ((double)$vatable_sales+(double)$zero_rated_ecozone+(double)$vat_on_sales)-(double)$ewt;

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
                                $count_max=$this->super_model->count_rows("sales_adjustment_head");
                                if($count_max==0){
                                    $sales_adjustment_id=1;
                                }else{
                                    $sales_adjustment_id = $this->super_model->get_max("sales_adjustment_head", "sales_adjustment_id");
                                }
                                if(in_array('error',$error)){
                                    // echo 'error';
                                    $this->super_model->delete_where('sales_adjustment_head', 'adjust_identifier', $adjust_identifier);
                                    $this->super_model->delete_where('sales_adjustment_details', 'sales_adjustment_id', $sales_adjustment_id);
                                    break;
                                }else{
                                    $data_sales = array(
                                        'sales_adjustment_id'=>$sales_adjustment_id,
                                        'item_no'=>$y,
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
                        $x++;
                    }
                    
                }
            }
        }
        if(count($error)==0){
            echo $adjust_identifier;
        }else{
            echo 'error';
        }
    }

    public function bulk_update_main(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $identifier_code=$this->generateRandomString();
        $data['identifier_code']=$identifier_code;
        $salesid=$this->uri->segment(3);
        $data['sales_id'] = $salesid;
        $identifier=$this->uri->segment(4);
        $data['identifier']=$this->uri->segment(4);
        $ref_no=$this->super_model->select_column_where("sales_transaction_head","reference_number","sales_id",$salesid);
        $data['refno']=$ref_no;
        $data['saved']=$this->super_model->select_column_where("sales_transaction_details","saved_bulk_update","bulk_update_identifier",$identifier);
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT reference_number,sales_id FROM sales_transaction_head WHERE reference_number!='' AND saved='1' ");
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON std.sales_id=sth.sales_id WHERE reference_number='$ref_no' AND saved='1' AND bulk_update_identifier ='$identifier'") AS $d){
            $data['details'][]=array(
                'sales_detail_id'=>$d->sales_detail_id,
                'sales_id'=>$d->sales_id,
                'ewt_amount'=>$d->ewt_amount,
                'original_copy'=>$d->original_copy,
                'scanned_copy'=>$d->scanned_copy,
                'billing_id'=>$d->billing_id,
            );
        }
        $this->load->view('sales/upload_bulk_update_main', $data);
        $this->load->view('template/footer');
    }

    public function cancel_bulkupdate_main(){
        $sales_id = $this->input->post('sales_id');
        $main_identifier = $this->input->post('main_identifier');
            $data_main = array(
                'ewt_amount'=>'',
                'original_copy'=>0,
                'scanned_copy'=>0,
                'bulk_update_identifier'=>Null,
            );
           $this->super_model->update_custom_where("sales_transaction_details", $data_main, "sales_id='$sales_id' AND bulk_update_identifier='$main_identifier'");
        }

    public function upload_bulk_update_main(){
        $sales_id = $this->input->post('sales_id');
        $dest= realpath(APPPATH . '../uploads/excel/');
        $error_ext=0;
        if(!empty($_FILES['doc']['name'])){
            $exc= basename($_FILES['doc']['name']);
            $exc=explode('.',$exc);
            $ext1=$exc[1];
            if($ext1=='php' || $ext1!='xlsx'){
                $error_ext++;
            }else{
                $filename1='bir_monitoring_bulkupdate_main.'.$ext1;
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                     $this->readExcel_bulkupdate_main($sales_id);
                }
            }
        }
    }

    public function readExcel_bulkupdate_main($sales_id){

        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();

        $inputFileName =realpath(APPPATH.'../uploads/excel/bir_monitoring_bulkupdate_main.xlsx');

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
            $billing_id = str_replace(array(' '), '',$objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue() ?? '');
            $unique_bill_id = str_replace(array(' '), '',$objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue() ?? '');
            $ewt_amount = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue());
            $original_copy = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getFormattedValue() ?? '');
            $scanned_copy = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue() ?? '');
     
            $data_main = array(
                'ewt_amount'=>$ewt_amount,
                'original_copy'=>$original_copy,
                'scanned_copy'=>$scanned_copy,
                'bulk_update_identifier'=>$identifier,
            );
           $this->super_model->update_custom_where("sales_transaction_details", $data_main, "sales_id='$sales_id' AND billing_id='$unique_bill_id' AND actual_billing_id='$billing_id'");
        }
    }

    public function save_bulkupdate_main(){
        $sales_id = $this->input->post('sales_id');
        $bulk_update_identifier = $this->input->post('main_identifier');
        $data_head = array(
            'saved_bulk_update'=>1
        );
        $this->super_model->update_custom_where("sales_transaction_details", $data_head, "sales_id='$sales_id' AND bulk_update_identifier='$bulk_update_identifier'");
    }

    public function upload_bulk_update_reserve_main(){
        $reserve_sales_id = $this->input->post('reserve_sales_id');
        $dest= realpath(APPPATH . '../uploads/excel/');
        $error_ext=0;
        if(!empty($_FILES['doc']['name'])){
            $exc= basename($_FILES['doc']['name']);
            $exc=explode('.',$exc);
            $ext1=$exc[1];
            if($ext1=='php' || $ext1!='xlsx'){
                $error_ext++;
            }else{
                $filename1='bir_monitoring_bulkupdate_reserve_main.'.$ext1;
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                     $this->readExcel_bulkupdate_reserve_main($reserve_sales_id);
                }
            }
        }
    }

    public function readExcel_bulkupdate_reserve_main($reserve_sales_id){
        $objPHPExcel = new Spreadsheet();
        $inputFileName =realpath(APPPATH.'../uploads/excel/bir_monitoring_bulkupdate_reserve_main.xlsx');
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
            $billing_id = str_replace(array(' '), '',$objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue() ?? '');
            $unique_bill_id = str_replace(array(' '), '',$objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue() ?? '');
            $ewt_amount = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue());
            $original_copy = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getFormattedValue() ?? '');
            $scanned_copy = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue() ?? '');
     
            $data_main = array(
                'res_ewt_amount'=>$ewt_amount,
                'res_original_copy'=>$original_copy,
                'res_scanned_copy'=>$scanned_copy,
                'res_bulk_update_identifier'=>$identifier,
            );
           $this->super_model->update_custom_where("reserve_sales_transaction_details", $data_main, "reserve_sales_id='$reserve_sales_id' AND res_billing_id='$unique_bill_id' AND res_actual_billing_id='$billing_id'");
        }
    }

    public function save_bulkupdate_reserve_main(){
        $reserve_sales_id = $this->input->post('reserve_sales_id');
        $bulk_update_identifier = $this->input->post('main_identifier');
        $data_head = array(
            'res_saved_bulk_update'=>1
        );
        $this->super_model->update_custom_where("reserve_sales_transaction_details", $data_head, "reserve_sales_id='$reserve_sales_id' AND res_bulk_update_identifier='$bulk_update_identifier'");
    }

    public function bulk_update_reserve_main(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $identifier_code=$this->generateRandomString();
        $data['identifier_code']=$identifier_code;
        $ressalesid=$this->uri->segment(3);
        $data['reserve_sales_id'] = $ressalesid;
        $identifier=$this->uri->segment(4);
        $data['identifier']=$this->uri->segment(4);
        $ref_no=$this->super_model->select_column_where("reserve_sales_transaction_head","res_reference_number","reserve_sales_id",$ressalesid);
        $data['refno']=$ref_no;
        $data['saved']=$this->super_model->select_column_where("reserve_sales_transaction_details","res_saved_bulk_update","res_bulk_update_identifier",$identifier);
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT res_reference_number,reserve_sales_id FROM reserve_sales_transaction_head WHERE res_reference_number!='' AND res_saved='1' ");
        foreach($this->super_model->custom_query("SELECT * FROM reserve_sales_transaction_details std INNER JOIN reserve_sales_transaction_head sth ON std.reserve_sales_id=sth.reserve_sales_id WHERE res_reference_number='$ref_no' AND res_saved='1' AND res_bulk_update_identifier ='$identifier'") AS $d){
            $data['details'][]=array(
                'reserve_sales_detail_id'=>$d->reserve_sales_detail_id,
                'reserve_sales_id'=>$d->reserve_sales_id,
                'ewt_amount'=>$d->res_ewt_amount,
                'original_copy'=>$d->res_original_copy,
                'scanned_copy'=>$d->res_scanned_copy,
                'billing_id'=>$d->res_billing_id,
            );
        }
        $this->load->view('sales/upload_bulk_update_reserve_main', $data);
        $this->load->view('template/footer');
    }

    public function cancel_bulkupdate_reserve_main(){
        $reserve_sales_id = $this->input->post('reserve_sales_id');
        $main_identifier = $this->input->post('main_identifier');
            $data_main = array(
                'res_ewt_amount'=>'',
                'res_original_copy'=>0,
                'res_scanned_copy'=>0,
                'res_bulk_update_identifier'=>Null,
            );
           $this->super_model->update_custom_where("reserve_sales_transaction_details", $data_main, "reserve_sales_id='$reserve_sales_id' AND res_bulk_update_identifier='$main_identifier'");
        }

    public function bulk_update_adjustment(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $identifier_code=$this->generateRandomString();
        $data['identifier_code']=$identifier_code;
        $due_date=$this->uri->segment(3);
        $data['due_date'] = $due_date;
        $identifier=$this->uri->segment(4);
        $data['identifier']=$this->uri->segment(4);
        // $ref_no=$this->super_model->select_column_where("sales_adjustment_head","reference_number","sales_adjustment_id",$salesadjustmentid);
        // $data['refno']=$ref_no;
        $data['saved']=$this->super_model->select_column_where("sales_adjustment_details","saved_bulk_update","bulk_update_identifier",$identifier);
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT reference_number,sales_adjustment_id FROM sales_adjustment_head WHERE reference_number!='' AND saved='1' ");
        $data['due'] = $this->super_model->custom_query("SELECT DISTINCT due_date FROM sales_adjustment_head WHERE saved='1' ORDER BY due_date ASC");
        foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details std INNER JOIN sales_adjustment_head sth ON std.sales_adjustment_id=sth.sales_adjustment_id WHERE due_date='$due_date' AND saved='1' AND bulk_update_identifier ='$identifier'") AS $d){
            $data['details'][]=array(
                'adjustment_detail_id'=>$d->adjustment_detail_id,
                'sales_adjustment_id'=>$d->sales_adjustment_id,
                'ewt_amount'=>$d->ewt_amount,
                'original_copy'=>$d->original_copy,
                'scanned_copy'=>$d->scanned_copy,
                'billing_id'=>$d->billing_id,
            );
        }
        $this->load->view('sales/upload_bulk_update_adjustment', $data);
        $this->load->view('template/footer');
    }

    public function cancel_bulkupdate_adjustment(){
        $due_date = $this->input->post('due_date');
        $adjustment_identifier = $this->input->post('adjustment_identifier');
        $sales_adjustment_id=array();
        $array=array();
        foreach($this->super_model->select_row_where('sales_adjustment_head','due_date',$due_date) AS $dues){
            $sales_adjustment_id[]=$dues->sales_adjustment_id;
        }
        $sales_adjust_id=implode(',',$sales_adjustment_id);
        $data_adjustment = array(
            'ewt_amount'=>'',
            'original_copy'=>0,
            'scanned_copy'=>0,
            'bulk_update_identifier'=>Null,
        );
        $this->super_model->update_custom_where("sales_adjustment_details", $data_adjustment, "sales_adjustment_id IN ($sales_adjust_id) AND bulk_update_identifier='$adjustment_identifier'");
    }

    public function upload_bulk_update_adjustment(){
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
                $filename1='bir_monitoring_bulkupdate_adjustment.'.$ext1;
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                     $this->readExcel_bulkupdate_adjustment($due);
                }
            }
        }
    }

    public function readExcel_bulkupdate_adjustment($due){

        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();

        //$inputFileName =realpath(APPPATH.'../uploads/excel/bir_monitoring_bulkupdate_adjustment.xlsx');
        $inputFileName =realpath(APPPATH.'../uploads/excel/bir_monitoring_bulkupdate_adjustment.xlsx');
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
            $due_date = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue() ?? '');
            $transaction_no = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue() ?? '');
            $settlement_id = str_replace(array(' '), '',$objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue() ?? '');
            $billing_id = str_replace(array(' '), '',$objPHPExcel->getActiveSheet()->getCell('D'.$x)->getFormattedValue() ?? '');
            $unique_bill_id = str_replace(array(' '), '',$objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue() ?? '');
            $ewt_amount = str_replace(array( '(', ')',',','-'), '',$objPHPExcel->getActiveSheet()->getCell('F'.$x)->getFormattedValue());
            $original_copy = trim($objPHPExcel->getActiveSheet()->getCell('G'.$x)->getFormattedValue() ?? '');
            $scanned_copy = trim($objPHPExcel->getActiveSheet()->getCell('H'.$x)->getFormattedValue() ?? '');
            $sales_adjustment_id=array();
            $array=array();
            foreach($this->super_model->select_custom_where('sales_adjustment_head',"due_date='$due_date' AND reference_number='$transaction_no'") AS $dues){
                $array[]=$dues->sales_adjustment_id;
                $sales_adjustment_id=array_merge($sales_adjustment_id, $array);
            }
            $sales_adjust_id="'".implode("','", $sales_adjustment_id)."'";
            if(!in_array($sales_adjust_id,$array)){
                $imp_id=$sales_adjust_id;    
            }
            //echo $imp_id;
            //$sales_adjust_id=implode(",",$sales_adjustment_id);

             // $unique_bill_id = $this->super_model->select_column_custom_where("participant", "billing_id", "actual_billing_id = '$billing_id' AND settlement_id = '$settlement_id'");

            $data_adjustment = array(
                'ewt_amount'=>$ewt_amount,
                'original_copy'=>$original_copy,
                'scanned_copy'=>$scanned_copy,
                'bulk_update_identifier'=>$identifier,
            );
            $this->super_model->update_custom_where("sales_adjustment_details", $data_adjustment, "sales_adjustment_id IN ($imp_id) AND billing_id='$unique_bill_id' AND actual_billing_id='$billing_id'");
        }
    }

    public function save_bulkupdate_adjustment(){
        $due_date = $this->input->post('due');
        $bulk_update_identifier = $this->input->post('adjustment_identifier');
        $sales_adjustment_id=array();
        $array=array();
        foreach($this->super_model->select_custom_where('sales_adjustment_head',"due_date='$due_date'") AS $dues){
            //$sales_adjustment_id[]="'".$dues->sales_adjustment_id."'";
            $array[]=$dues->sales_adjustment_id;
            $sales_adjustment_id=array_merge($sales_adjustment_id, $array);
        }
        //$sales_adjust_id=implode(',',$sales_adjustment_id);
        $sales_adjust_id="'".implode("','", $sales_adjustment_id)."'";
        if(!in_array($sales_adjust_id,$array)){
            $imp_id=$sales_adjust_id;    
        }
        $data_head = array(
            'saved_bulk_update'=>1
        );
        //$this->super_model->update_custom_where("sales_adjustment_details", $data_head, "due_date='$due_date' AND bulk_update_identifier='$bulk_update_identifier'");
        $this->super_model->update_custom_where("sales_adjustment_details", $data_head, "sales_adjustment_id IN ($imp_id) AND bulk_update_identifier='$bulk_update_identifier'");
    }

    public function PDF_OR(){
        $collection_id=$this->uri->segment(3);
        $settlement_id=str_replace('%20', ' ', $this->uri->segment(4));
        $reference_no=$this->uri->segment(5);
        $series_number=$this->uri->segment(6);
        $signatory= ($this->uri->segment(7)!='' || !empty($this->uri->segment(7))) ? $this->uri->segment(7) : $_SESSION['user_id'];
        $data['ref_no'] = $reference_no;
        $data['refno'] = preg_replace("/[^0-9]/", "",$reference_no);

        //$refno = preg_replace("/[^0-9]/", "",$reference_no);

        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id", $signatory);
        $collection_date = $this->super_model->select_column_where("collection_head", "collection_date", "collection_id", $collection_id);
        $data['billing_month'] = date('my',strtotime($collection_date));
        $data['timestamp'] = date('Ymd');
        $billing_id = $this->super_model->select_column_where("participant", "billing_id", "settlement_id", $settlement_id);
        
        //$data['client']=$this->super_model->select_row_where("participant", "billing_id", $billing_id);
        $data['address']=$this->super_model->select_column_where("participant", "registered_address", "billing_id", $billing_id);
        $data['tin']=$this->super_model->select_column_where("participant", "tin", "billing_id", $billing_id);
        $data['stl_id']=$this->super_model->select_column_custom_where("collection_details","settlement_id","settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        //$data['or_no']=$this->super_model->select_column_custom_where("collection_details","series_number","settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no'");
        $data['or_no']=$series_number;
        $data['buyer']=$this->super_model->select_column_custom_where("collection_details","buyer_fullname","settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no' ");
        $data['sum_amount']=$this->super_model->select_sum_where("collection_details","amount","settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['sum_vat']=$this->super_model->select_sum_where("collection_details","vat","settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['sum_ewt'] =  $this->super_model->select_sum_where("collection_details", "ewt", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['sum_zero_rated'] =  $this->super_model->select_sum_where("collection_details", "zero_rated", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['sum_zero_rated_ecozone'] =  $this->super_model->select_sum_where("collection_details", "zero_rated_ecozone", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['defint'] =  $this->super_model->select_sum_where("collection_details", "defint", "settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['date']=$this->super_model->select_column_custom_where("collection_details","or_date","settlement_id='$settlement_id' AND collection_id='$collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        //$data['date'] = $this->super_model->select_column_where("collection_head", "collection_date", "collection_id", $collection_id);
        $this->load->view('sales/PDF_OR',$data);
    }

    public function PDF_OR_bulk(){
        $date=$this->uri->segment(3);
        $ref_no=$this->uri->segment(4);
        // $stl_id=$this->uri->segment(5);
        $buyer_fullname = urldecode($this->uri->segment(5) ?? '');
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

            //$count = $this->super_model->count_custom("SELECT * FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE $qu GROUP BY series_number,settlement_id,reference_no");

            //echo $count."<br>";

            foreach($this->super_model->custom_query("SELECT * FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE $qu GROUP BY series_number LIMIT 10") AS $col){

                //echo $col->series_number."-".$col->settlement_id."<br>";

            /*$data_update = array(
                "bulk_pdf_flag"=>1
            );
            $this->super_model->update_where("collection_details", $data_update, "series_number", $col->series_number);*/

            if($ref_no!='null'){
                $reference_number = $ref_no;
            }else{
                $reference_number = $col->reference_no;
            }

            $billing_month = date('my',strtotime($col->collection_date));
            $refno = preg_replace("/[^0-9]/", "",$reference_number);

            $billing_id = $this->super_model->select_column_where("participant", "billing_id", "settlement_id", $col->settlement_id);
            $sum_amount=$this->super_model->select_sum_where("collection_details","amount","settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_vat=$this->super_model->select_sum_where("collection_details","vat","settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_ewt =  $this->super_model->select_sum_where("collection_details", "ewt", "settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND reference_no='$col->reference_no'");
            $sum_zero_rated =  $this->super_model->select_sum_where("collection_details", "zero_rated", "settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_zero_rated_ecozone =  $this->super_model->select_sum_where("collection_details", "zero_rated_ecozone", "settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $defint =  $this->super_model->select_sum_where("collection_details", "defint", "settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");

            $data['details'][] = array(
                'collection_details_id'=>$col->collection_details_id,
                'collection_id'=>$col->collection_id,
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

       
        $this->load->view('sales/PDF_OR_bulk',$data);
    }

    public function pdf_scan_directory(){
      

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

         

            foreach($this->super_model->custom_query("SELECT * FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE $qu GROUP BY series_number,settlement_id,reference_no") AS $col){

             

            
            $reference_number = $col->reference_no;
            

            $billing_month = date('my',strtotime($col->collection_date));
            $refno = preg_replace("/[^0-9]/", "",$reference_number);

            $billing_id = $this->super_model->select_column_where("participant", "billing_id", "settlement_id", $col->settlement_id);
            $sum_amount=$this->super_model->select_sum_where("collection_details","amount","settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_vat=$this->super_model->select_sum_where("collection_details","vat","settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_ewt =  $this->super_model->select_sum_where("collection_details", "ewt", "settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND reference_no='$col->reference_no'");
            $sum_zero_rated =  $this->super_model->select_sum_where("collection_details", "zero_rated", "settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_zero_rated_ecozone =  $this->super_model->select_sum_where("collection_details", "zero_rated_ecozone", "settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $defint =  $this->super_model->select_sum_where("collection_details", "defint", "settlement_id='$col->settlement_id' AND collection_id='$col->collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");

            $data['details'][] = array(
                'collection_details_id'=>$col->collection_details_id,
                'collection_id'=>$col->collection_id,
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

       
        $this->load->view('sales/pdf_scan_directory',$data);
    }


    public function update_flag(){
        $series_number = $this->input->post('series_no');
        $settlement_id = $this->input->post('stl_id');
        $reference_no = $this->input->post('reference_no');
        $collection_id = $this->input->post('collection_id');
        //$collection_details_id = $this->input->post('collection_details_id');
        $filename = $this->input->post('filename');
        $data_update = array(
                "bulk_pdf_flag"=>1,
                "filename"=>$filename
            );
            //$this->super_model->update_where("collection_details", $data_update, "series_number", $series_number);
            //echo $collection_details_id;
            $this->super_model->update_custom_where("collection_details", $data_update, "series_number='$series_number' AND settlement_id='$settlement_id' AND reference_no='$reference_no' AND collection_id='$collection_id'");
            //echo $series_number."-".$settlement_id."-".$reference_no."-".$collection_id;
    }

    public function bulk_invoicing_main(){
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

        $data['saved']=$this->super_model->select_column_where("sales_transaction_details","saved_bulk_invoicing","bulk_invoicing_identifier",$identifier);
        $data['years'] = $this->super_model->custom_query("SELECT DISTINCT YEAR(billing_to) AS year FROM sales_transaction_head WHERE saved='1' ORDER BY year DESC");
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT reference_number FROM sales_transaction_head WHERE reference_number!='' AND saved='1' ORDER BY due_date ASC");
        $data['due'] = $this->super_model->custom_query("SELECT DISTINCT due_date FROM sales_transaction_head WHERE saved='1' ORDER BY due_date ASC");
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON std.sales_id=sth.sales_id WHERE $qu") AS $d){
            $data['details'][]=array(
                'sales_detail_id'=>$d->sales_detail_id,
                'sales_id'=>$d->sales_id,
                'reference_number'=>$d->reference_number,
                'settlement_id'=>$d->short_name,
                'billing_id'=>$d->actual_billing_id,
                'actual_billing_id'=>$d->actual_billing_id,
                'serial_no'=>$d->serial_no,
            );
        }
        $this->load->view('sales/upload_bulk_invoicing_main', $data);
        $this->load->view('template/footer');
    }

    public function cancel_main_sales_invoicing(){
        $main_identifier = $this->input->post('main_identifier');
        $data_main = array(
            'serial_no'=>Null,
            'bulk_invoicing_identifier'=>Null,
        );
        $this->super_model->update_custom_where("sales_transaction_details", $data_main, "bulk_invoicing_identifier='$main_identifier'");
    }

    public function upload_bulk_invoicing_main(){
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
                $filename1='bulk_upload_sales_main.'.$ext1;
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                     $this->readExcel_bulkinvoicing_main($year,$reference,$due);
                }
            }
        }
    }

    public function readExcel_bulkinvoicing_main($year,$reference,$due){
        $objPHPExcel = new Spreadsheet();
        $inputFileName =realpath(APPPATH.'../uploads/excel/bulk_upload_sales_main.xlsx');
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
            $settlement_id = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue() ?? '');
            $billing_id = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue() ?? '');
            $invoice_no = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue() ?? '');

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
            foreach ($this->super_model->select_custom_where('sales_transaction_head', "$query") as $dues) {
                $sales_id[] = $dues->sales_id;
            }

            if (!empty($sales_id)) {
                $salesid_str = "'" . implode("','", $sales_id) . "'";

                $data_main = array(
                    'serial_no' => $invoice_no,
                    'bulk_invoicing_identifier' => $identifier,
                );

                $this->super_model->update_custom_where("sales_transaction_details",$data_main,"sales_id IN ($salesid_str) AND short_name='$settlement_id' AND actual_billing_id='$billing_id'");
            }
        }
    }

    public function save_bulkinvoicing_main(){
        $bulk_invoicing_identifier = $this->input->post('main_identifier');
        $data_head = array(
            'saved_bulk_invoicing'=>1
        );
        $this->super_model->update_custom_where("sales_transaction_details", $data_head, "bulk_invoicing_identifier='$bulk_invoicing_identifier'");
    }

    public function bulk_invoicing(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $identifier_code=$this->generateRandomString();
        $data['identifier_code']=$identifier_code;
        $due_date=$this->uri->segment(3);
        $data['due_date'] = $due_date;
        $identifier=$this->uri->segment(4);
        $data['identifier']=$this->uri->segment(4);
        $data['saved']=$this->super_model->select_column_where("sales_adjustment_details","saved_bulk_invoicing","bulk_invoicing_identifier",$identifier);
        $data['reference'] = $this->super_model->custom_query("SELECT DISTINCT reference_number,sales_adjustment_id FROM sales_adjustment_head WHERE reference_number!='' AND saved='1' ");
        $data['due'] = $this->super_model->custom_query("SELECT DISTINCT due_date FROM sales_adjustment_head WHERE saved='1' ORDER BY due_date ASC");
        foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details std INNER JOIN sales_adjustment_head sth ON std.sales_adjustment_id=sth.sales_adjustment_id WHERE due_date='$due_date' AND saved='1' AND bulk_invoicing_identifier ='$identifier'") AS $d){
            $data['details'][]=array(
                'adjustment_detail_id'=>$d->adjustment_detail_id,
                'sales_adjustment_id'=>$d->sales_adjustment_id,
                'reference_number'=>$d->reference_number,
                'billing_id'=>$d->billing_id,
                'actual_billing_id'=>$d->actual_billing_id,
                'serial_no'=>$d->serial_no,
                'csr_number'=>$d->collection_report_number,
            );
        }
        $this->load->view('sales/upload_bulk_invoicing', $data);
        $this->load->view('template/footer');
    }

    public function cancel_sales_invoicing(){
        $due_date = $this->input->post('due_date');
        $adjustment_identifier = $this->input->post('adjustment_identifier');
        /*$sales_adjustment_id=array();
        foreach($this->super_model->select_row_where('sales_adjustment_head','due_date',$due_date) AS $dues){
            $sales_adjustment_id[]=$dues->sales_adjustment_id;
        }
        $sales_adjust_id=implode(',',$sales_adjustment_id);*/
        $data_adjustment = array(
            'serial_no'=>Null,
            'bulk_invoicing_identifier'=>Null,
        );
        //$this->super_model->update_custom_where("sales_adjustment_details", $data_adjustment, "sales_adjustment_id IN($sales_adjust_id) AND bulk_invoicing_identifier='$adjustment_identifier'");
        $this->super_model->update_custom_where("sales_adjustment_details", $data_adjustment, "bulk_invoicing_identifier='$adjustment_identifier'");
    }

    public function upload_bulk_invoicing_adjustment(){
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
                $filename1='bulk_upload_sales_invoicing.'.$ext1;
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                     $this->readExcel_bulkinvoicing_adjustment($due);
                }
            }
        }
    }

    public function readExcel_bulkinvoicing_adjustment($due){

        //require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new Spreadsheet();

        $inputFileName =realpath(APPPATH.'../uploads/excel/bulk_upload_sales_invoicing.xlsx');

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
            $actual_billing_id = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue() ?? '');

            $billing_id = $this->super_model->select_column_custom_where("participant", "billing_id", "actual_billing_id = '$actual_billing_id' AND settlement_id = '$settlement_id'");

            $invoice_no = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getFormattedValue() ?? '');
            $csr_number = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue() ?? '');
            // $sales_adjustment_id=array();
            // foreach($this->super_model->select_custom_where('sales_adjustment_head',"due_date='$due' AND reference_number='$reference_no'") AS $dues){
            //     $sales_adjustment_id[]="'".$dues->sales_adjustment_id."'";
            // }
            // $sales_adjust_id=implode(',',$sales_adjustment_id);
            $sales_adjustment_id=array();
            $array=array();
            foreach($this->super_model->select_custom_where('sales_adjustment_head',"due_date='$due' AND reference_number='$reference_no'") AS $dues){
                $array[]=$dues->sales_adjustment_id;
                $sales_adjustment_id=array_merge($sales_adjustment_id, $array);
            }
            $sales_adjust_id="'".implode("','", $sales_adjustment_id)."'";
            if(!in_array($sales_adjust_id,$array)){
                $imp_id=$sales_adjust_id;    
            }
            $data_adjustment = array(
                'serial_no'=>$invoice_no,
                'collection_report_number'=>$csr_number,
                'bulk_invoicing_identifier'=>$identifier,
            );
            $this->super_model->update_custom_where("sales_adjustment_details", $data_adjustment, "sales_adjustment_id IN ($imp_id) AND billing_id='$billing_id'");
        }
    }

    public function save_bulkinvoicing_adjustment(){
        // $due_date = $this->input->post('due');
        // $ref_no = $this->input->post('ref_no');
        $bulk_invoicing_identifier = $this->input->post('adjustment_identifier');
        /*foreach($this->super_model->select_custom_where('sales_adjustment_head',"due_date='$due_date' AND reference_number='$ref_no'") AS $dues){
            $sales_adjustment_id[]="'".$dues->sales_adjustment_id."'";
        }
        $sales_adjust_id=implode(',',$sales_adjustment_id);*/
        $data_head = array(
            'saved_bulk_invoicing'=>1
        );
        //$this->super_model->update_custom_where("sales_adjustment_details", $data_head, "due_date='$due_date' AND bulk_update_identifier='$bulk_update_identifier'");
        $this->super_model->update_custom_where("sales_adjustment_details", $data_head, "bulk_invoicing_identifier='$bulk_invoicing_identifier'");
    }

        public function insert_printbs_adjustment(){
        $company_name = $this->input->post('company_name');
        $address = $this->input->post('address');
        $tin = $this->input->post('tin');
        $settlement = $this->input->post('settlement');
        $serial_no = $this->input->post('serial_no');
        $transaction_date = $this->input->post('transaction_date');
        $due_date = $this->input->post('due_date');
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
        $total_rated_sales = $this->input->post('total_rated_sales');
        $total_rated_ecozones = $this->input->post('total_rated_ecozones');
        $zero = $this->input->post('zero');
        $total_vat = $this->input->post('vat');
        $ewt_arr = $this->input->post('ewt_arr');
        $overall_total = $this->input->post('overall_total');
        $count_head=count($serial_no);

        $billing_from = $this->input->post('billing_from');
        $billing_to = $this->input->post('billing_to');
        $reference_number = $this->input->post('ref_no');
        $billing_id = $this->input->post('sub_participant');
        $vatable_sales = $this->input->post('vatable_sales');
        $rated_sales = $this->input->post('rated_sales');
        $rated_ecozones = $this->input->post('rated_ecozones');
        $zero_rated_sales = $this->input->post('zero_rated_sales');
        $vat = $this->input->post('vat_on_sales');
        $ewt = $this->input->post('ewt');
        $net_amount = $this->input->post('net_amount');
        $invoice_no = $this->input->post('invoice_no');
        $count_details=count($billing_id);
       
        for($x=0;$x<$count_head;$x++){
            $count_participant = $this->super_model->count_custom_where("bs_head_adjustment", "invoice_no = '$serial_no[$x]'");
            if($count_participant == 0){ 
                  $data_head = array(
                        'participant_name' => $company_name[$x],
                        'address' => $address[$x],
                        'tin' => $tin[$x],
                        'stl_id' => $settlement[$x],
                        'invoice_no' => $serial_no[$x],
                        'statement_date' => $transaction_date[$x],
                        'due_date' => $due_date[$x],
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
                        'total_zero_sales' => $total_rated_sales[$x],
                        'total_zero_ecozones' => $total_rated_ecozones[$x],
                        'total_vat' => $total_vat[$x],
                        'total_ewt' => $ewt_arr[$x],
                        'total_net_amount' => $overall_total[$x],
                 );
                 $this->super_model->insert_return_id("bs_head_adjustment", $data_head);
                }
            }

        for($y=0;$y<$count_details;$y++){
            if($invoice_no[$y] != ''){
            $bs_head_adjustment_id = $this->super_model->select_column_custom_where("bs_head_adjustment","bs_head_adjustment_id","invoice_no='$invoice_no[$y]'");
            $count_bs_details = $this->super_model->count_custom_where("bs_details_adjustment", "bs_head_adjustment_id = '$bs_head_adjustment_id' AND billing_id = '$billing_id[$y]' AND billing_from = '$billing_from[$y]' AND billing_to = '$billing_to[$y]' AND reference_number = '$reference_number[$y]'");
            // echo $count_bs_details;
            if($count_bs_details == 0){

                $settlement_id =  $settlement[$y];
                $tin_no =  $tin[$y];

                $unique_bill_id = $this->super_model->select_column_custom_where("participant", "billing_id", "actual_billing_id = '$billing_id[$y]' AND settlement_id = '$settlement_id' AND tin = '$tin_no'");


                   $data_details = array(
                        'bs_head_adjustment_id'=>$bs_head_adjustment_id,
                        'billing_from' => $billing_from[$y],
                        'billing_to' => $billing_to[$y],
                        'reference_number' => $reference_number[$y],
                        'actual_billing_id' => $billing_id[$y],
                        'billing_id'=>$unique_bill_id,
                        'vatable_sales' => $vatable_sales[$y],
                        'rated_sales' => $rated_sales[$y],
                        'rated_ecozones' => $rated_ecozones[$y],
                        'zero_rated_sales' => $zero_rated_sales[$y],
                        'vat' => $vat[$y],
                        'ewt' => $ewt[$y],
                        'net_amount' => $net_amount[$y],
                   );
                   $this->super_model->insert_into("bs_details_adjustment", $data_details);
                        }
                    }
                }
            }

    public function print_bs_adjustment(){
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
        $data['bs_head_adjustment_id'][]='';
        $data['total_sub_h'][]='';
        $data['total_sub'][]='';
  
        for($x=0;$x<$count;$x++){
            $invoice[]=$this->super_model->custom_query_single('invoice_no',"SELECT * FROM sales_adjustment_details sad INNER JOIN bs_head_adjustment bha ON bha.invoice_no=sad.serial_no WHERE bha.invoice_no='$invoice_no_exp[$x]'");
            if(array_key_exists($invoice_no_exp[$x],$invoice)){
                foreach($this->super_model->select_custom_where("bs_head_adjustment","invoice_no='".$invoice_no_exp[$x]."'") AS $p){
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
                    $data['bs_head_adjustment_id'][$x]=$p->bs_head_adjustment_id;
                    $count_sub_hist=$this->super_model->count_custom_where("bs_details_adjustment","bs_head_adjustment_id='$p->bs_head_adjustment_id'");
                    $data['count_sub_hist'][$x]=$this->super_model->count_custom_where("bs_details_adjustment","bs_head_adjustment_id='$p->bs_head_adjustment_id'");
                    $billing_id = $this->super_model->select_column_where("bs_details_adjustment","billing_id","bs_head_adjustment_id",$p->bs_head_adjustment_id);
                    $vatable_sales = $this->super_model->select_column_where("bs_details_adjustment","vatable_sales","bs_head_adjustment_id",$p->bs_head_adjustment_id);
                    $zero_rated_sales = $this->super_model->select_column_where("bs_details_adjustment","zero_rated_sales","bs_head_adjustment_id",$p->bs_head_adjustment_id);
                    $vat = $this->super_model->select_column_where("bs_details_adjustment","vat","bs_head_adjustment_id",$p->bs_head_adjustment_id);
                    $ewt = $this->super_model->select_column_where("bs_details_adjustment","ewt","bs_head_adjustment_id",$p->bs_head_adjustment_id);
                    $overall_total = $this->super_model->select_column_where("bs_details_adjustment","net_amount","bs_head_adjustment_id",$p->bs_head_adjustment_id);
                    $total_amount = $vatable_sales + $zero_rated_sales;

                    $data['head'][]=array(
                        "serial_no"=>$p->invoice_no,
                    );
                     
                    
                    $h=0;
                    $u=1;
                    foreach($this->super_model->select_custom_where("bs_details_adjustment","bs_head_adjustment_id='$p->bs_head_adjustment_id'") AS $s){
                    if($u <= 10){
                            $data['total_sub_h']=$this->super_model->count_custom_where("bs_details_adjustment","bs_head_adjustment_id='$p->bs_head_adjustment_id'");
                            $data['total_sub']='';
                                $data['sub_part'][]=array(
                                    "serial_no"=>$p->invoice_no,
                                    "ref_no"=>$s->reference_number,
                                    "billing_from"=>$s->billing_from,
                                    "billing_to"=>$s->billing_to,
                                    "bs_head_adjustment_id"=>$p->bs_head_adjustment_id,
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
                            "bs_head_adjustment_id"=>$p->bs_head_adjustment_id,
                            "serial_no"=>$p->invoice_no,
                            "ref_no"=>$s->reference_number,
                            "billing_from"=>$s->billing_from,
                            "billing_to"=>$s->billing_to,
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
                        foreach($this->super_model->select_custom_where("bs_details_adjustment","bs_head_adjustment_id='$p->bs_head_adjustment_id'") AS $s){
                    if($t>=11){

                                $data['sub_part_second'][]=array(
                                    "counter"=>'',
                                    "counter_h"=>$t,
                                    "bs_head_adjustment_id"=>$p->bs_head_adjustment_id,
                                    "serial_no"=>$p->invoice_no,
                                    "ref_no"=>$s->reference_number,
                                    "billing_from"=>$s->billing_from,
                                    "billing_to"=>$s->billing_to,
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
                    foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details WHERE print_identifier='$print_identifier' AND serial_no='".$invoice_no_exp[$x]."' GROUP BY serial_no ORDER BY serial_no ASC")AS $p){
                    $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                    $mother_participant_id = $this->super_model->select_column_where("subparticipant","participant_id","sub_participant",$participant_id);
                    if($mother_participant_id != ''){
                            $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                            // $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id", $p->sales_adjustment_id);
                            // // $comp_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                            $mother_billing_id = $this->super_model->select_column_where("participant","actual_billing_id","participant_id",$mother_participant_id);
                            // $mother_company_name = $this->super_model->select_column_custom_where("sales_adjustment_details","company_name","actual_billing_id = '$mother_billing_id'");
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

                            // $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id", $p->sales_adjustment_id);
                            if(!empty($p->company_name)){
                                $comp_name=$p->company_name;
                            }else{
                                $comp_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $p->billing_id);
                            }
                            // $company_name = $p->company_name;
                            $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                            $settlement = $this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
                    }

                    // $data['address'][$x]=$this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                    $data['address'][$x]= $address;
                    // $data['company_name'][$x]=$p->company_name;
                    $data['company_name'][$x]=$comp_name;
                    // $data['tin'][$x]=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                    $data['tin'][$x]=$tin_no;
                    // $tin=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                    $tin=$tin_no;
                    $data['serial_no'][$x]=$this->super_model->select_column_where("sales_adjustment_details","serial_no","serial_no",$p->serial_no);
                    $serial_no=$this->super_model->select_column_where("sales_adjustment_details","serial_no","serial_no",$p->serial_no);
                    // $data['settlement'][$x]=$this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
                    $data['settlement'][$x]=$settlement;
                    $data['transaction_date'][$x]=$this->super_model->select_column_where("sales_adjustment_head","transaction_date","sales_adjustment_id",$p->sales_adjustment_id);
                    $transaction_date=$this->super_model->select_column_where("sales_adjustment_head","transaction_date","sales_adjustment_id",$p->sales_adjustment_id);
                    $data['due_date'][$x]=$this->super_model->select_column_where("sales_adjustment_head","due_date","sales_adjustment_id",$p->sales_adjustment_id);
                    $due_date=$this->super_model->select_column_where("sales_adjustment_head","due_date","sales_adjustment_id",$p->sales_adjustment_id);
                    $count_sub=$this->super_model->count_custom_where("subparticipant","participant_id='$participant_id'");
                    $zero_rated= $p->zero_rated_sales + $p->zero_rated_ecozones;
                    $total_amount = $p->vatable_sales + $p->zero_rated_sales + $p->zero_rated_ecozones;
                    $overall_total= ($total_amount+$p->vat_on_sales) - $p->ewt;
                    $data['bs_head_adjustment_id'][$x]='';
                    $data['count_sub_hist'][$x]='';

                    $data['head'][]=array(
                        "serial_no"=>$p->serial_no,
                    );

                        $h=1;
                            // $data['total_sub']=$this->super_model->count_custom_where("sales_adjustment_details","serial_no = '$p->serial_no' AND total_amount != '0'");
                            $data['total_sub']=$this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id = sah.sales_adjustment_id WHERE sad.serial_no = '$p->serial_no' AND sad.total_amount != '0' AND sah.saved != '0'");
                            // $data['total_sub'] = $this->super_model->count_custom_where("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id = sah.sales_adjustment_id","sad.serial_no = '$p->serial_no' AND sad.total_amount != '0' AND sah.saved != '0'");
                            $data['total_sub_h']='';
                        // foreach($this->super_model->select_custom_where("sales_adjustment_details","serial_no = '$p->serial_no' AND total_amount != '0'") AS $s){
                            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id = sah.sales_adjustment_id WHERE sad.serial_no = '$p->serial_no' AND sad.total_amount != '0' AND sah.saved != '0'") AS $s){
                            if($h<=10){
                                    $reference_number=$this->super_model->select_column_where("sales_adjustment_head","reference_number","sales_adjustment_id",$s->sales_adjustment_id);
                                    $billing_from=$this->super_model->select_column_where("sales_adjustment_head","billing_from","sales_adjustment_id",$s->sales_adjustment_id);
                                    $billing_to=$this->super_model->select_column_where("sales_adjustment_head","billing_to","sales_adjustment_id",$s->sales_adjustment_id);
                                    $vatable_sales=$this->super_model->select_column_where("sales_adjustment_details","vatable_sales","adjustment_detail_id",$s->adjustment_detail_id);
                                    $zero_rated_sales=$this->super_model->select_column_where("sales_adjustment_details","zero_rated_sales","adjustment_detail_id",$s->adjustment_detail_id);
                                    $zero_rated_ecozones=$this->super_model->select_column_where("sales_adjustment_details","zero_rated_ecozones","adjustment_detail_id",$s->adjustment_detail_id);
                                    $vat_on_sales=$this->super_model->select_column_where("sales_adjustment_details","vat_on_sales","adjustment_detail_id",$s->adjustment_detail_id);
                                    $ewt=$this->super_model->select_column_where("sales_adjustment_details","ewt","adjustment_detail_id",$s->adjustment_detail_id);

                                    $zero_rated= $zero_rated_sales + $zero_rated_ecozones;
                                    $total_amount = $vatable_sales + $zero_rated_sales + $zero_rated_ecozones;
                                    $overall_total= ($total_amount + $vat_on_sales) - $ewt;
                                    $data['sub_part'][]=array(
                                        "counter"=>$h,
                                        "counter_h"=>'',
                                        "ref_no"=>$reference_number,
                                        "billing_from"=>$billing_from,
                                        "billing_to"=>$billing_to,
                                        "sub_participant"=>$s->actual_billing_id,
                                        // "sub_participant"=>$s->billing_id,
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
                            "ref_no"=>$reference_number,
                            "billing_from"=>$billing_from,
                            "billing_to"=>$billing_to,
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
                        // foreach($this->super_model->select_custom_where("sales_adjustment_details","serial_no = '$p->serial_no' AND total_amount != '0'") AS $s){
                        foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id = sah.sales_adjustment_id WHERE sad.serial_no = '$p->serial_no' AND sad.total_amount != '0' AND sah.saved != '0'") AS $s){

                                
                                $reference_number=$this->super_model->select_column_where("sales_adjustment_head","reference_number","sales_adjustment_id",$s->sales_adjustment_id);
                                $billing_from=$this->super_model->select_column_where("sales_adjustment_head","billing_from","sales_adjustment_id",$s->sales_adjustment_id);
                                $billing_to=$this->super_model->select_column_where("sales_adjustment_head","billing_to","sales_adjustment_id",$s->sales_adjustment_id);
                                $vatable_sales=$this->super_model->select_column_where("sales_adjustment_details","vatable_sales","adjustment_detail_id",$s->adjustment_detail_id);
                                $zero_rated_sales=$this->super_model->select_column_where("sales_adjustment_details","zero_rated_sales","adjustment_detail_id",$s->adjustment_detail_id);
                                $zero_rated_ecozones=$this->super_model->select_column_where("sales_adjustment_details","zero_rated_ecozones","adjustment_detail_id",$s->adjustment_detail_id);
                                $vat_on_sales=$this->super_model->select_column_where("sales_adjustment_details","vat_on_sales","adjustment_detail_id",$s->adjustment_detail_id);
                                $ewt=$this->super_model->select_column_where("sales_adjustment_details","ewt","adjustment_detail_id",$s->adjustment_detail_id);
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
                                    "sub_participant"=>$s->actual_billing_id,
                                    "ref_no"=>$reference_number,
                                    "billing_from"=>$billing_from,
                                    "billing_to"=>$billing_to,
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
        $this->load->view('sales/print_bs_adjustment',$data);
    }


    public function print_invoice_adjustment_small(){
        error_reporting(0);
        $invoice_no = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $invoice_no_exp=explode("-",$invoice_no);
        $data['count']=$count;
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        for($x=0;$x<$count;$x++){
             $invoice[]=$this->super_model->custom_query_single('invoice_no',"SELECT * FROM sales_adjustment_details sad  INNER JOIN bs_head_adjustment bha ON bha.invoice_no=sad.serial_no WHERE bha.invoice_no='$invoice_no_exp[$x]'");
            if(array_key_exists($invoice_no_exp[$x],$invoice)){
                foreach($this->super_model->select_custom_where("bs_head_adjustment","invoice_no='".$invoice_no_exp[$x]."'") AS $p){
                    $data['address'][$x]=$p->address;
                    $data['tin'][$x]=$p->tin;
                    $data['company_name'][$x]=$p->participant_name;
                    $data['due_date'][$x]=$p->due_date;
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
            $data['amount_words'][$x]=strtoupper($this->convertNumber($total_amount));
            $total_exp=explode(".", $total_amount);
            $data['total_peso'][$x]=$total_exp[0];
            $data['total_cents'][$x]=$total_exp[1];

        }else{
            // foreach($this->super_model->select_custom_where("sales_adjustment_details","print_identifier='$print_identifier' AND serial_no='".$invoice_no_exp[$x]."' GROUP by serial_no") AS $p){
            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id = sah.sales_adjustment_id WHERE print_identifier='$print_identifier' AND serial_no='".$invoice_no_exp[$x]."' AND sah.saved != '0' GROUP by serial_no") AS $p){
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                // $mother_participant_id = $this->super_model->select_column_where("subparticipant","participant_id","sub_participant",$participant_id);

                //     if($mother_participant_id != ''){
                //             $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                //             $company_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                //             $tin_no = $this->super_model->select_column_where("participant","tin","participant_id",$mother_participant_id);
                //     }else{
                //             $address = $this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                //             $company_name = $p->company_name;
                //             $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                //     }

                $mother_participant_id = $this->super_model->select_column_where("subparticipant","participant_id","sub_participant",$participant_id);

                // if($mother_participant_id != ''){
                //         $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                //         $company_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                //         $tin_no = $this->super_model->select_column_where("participant","tin","participant_id",$mother_participant_id);
                //         $settlement = $this->super_model->select_column_where("participant","settlement_id","participant_id",$mother_participant_id);
                // }else{
                //         $address = $this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                //         $company_name = $p->company_name;
                //         $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                //         $settlement = $this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
                // }

                if($mother_participant_id != ''){
                        $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                        // $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id", $p->sales_adjustment_id);
                        // // $comp_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
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

                        // $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id", $p->sales_adjustment_id);
                        if(!empty($p->company_name)){
                            $company_name=$p->company_name;
                        }else{
                            $company_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $p->billing_id);
                        }
                        // $company_name = $p->company_name;
                        $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                        $settlement = $this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
                }


                // $data['address'][$x]=$this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                // $data['tin'][$x]=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                // $data['company_name'][$x]=$p->company_name;
                $data['address'][$x]=$address;
                $data['tin'][$x]=$tin_no;
                $data['company_name'][$x]=$company_name;
                $data['due_date'][$x]=$this->super_model->select_column_where("sales_adjustment_head","due_date","sales_adjustment_id",$p->sales_adjustment_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("sales_adjustment_head","transaction_date","sales_adjustment_id",$p->sales_adjustment_id);

                $vatable_sales = $this->super_model->select_sum_join("vatable_sales","sales_adjustment_details","sales_adjustment_head", "serial_no='$p->serial_no' AND saved = '1'","sales_adjustment_id");
                $zero_rated_sales = $this->super_model->select_sum_join("zero_rated_sales","sales_adjustment_details","sales_adjustment_head", "serial_no='$p->serial_no' AND saved = '1'","sales_adjustment_id");
                $zero_rated_ecozones = $this->super_model->select_sum_join("zero_rated_ecozones","sales_adjustment_details","sales_adjustment_head", "serial_no='$p->serial_no' AND saved = '1'","sales_adjustment_id");
                $vat_on_sales = $this->super_model->select_sum_join("vat_on_sales","sales_adjustment_details","sales_adjustment_head", "serial_no='$p->serial_no' AND saved = '1'","sales_adjustment_id");
                $ewt = $this->super_model->select_sum_join("ewt","sales_adjustment_details","sales_adjustment_head", "serial_no='$p->serial_no' AND saved = '1'","sales_adjustment_id");
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
        $this->load->view('sales/print_invoice_adjustment_small',$data);
    }

    public function print_invoice_adjustment_half(){
        error_reporting(0);
        $invoice_no = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $invoice_no_exp=explode("-",$invoice_no);
        $data['count']=$count;
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        for($x=0;$x<$count;$x++){
             $invoice[]=$this->super_model->custom_query_single('invoice_no',"SELECT * FROM sales_adjustment_details sad  INNER JOIN bs_head_adjustment bha ON bha.invoice_no=sad.serial_no WHERE bha.invoice_no='$invoice_no_exp[$x]'");
            if(array_key_exists($invoice_no_exp[$x],$invoice)){
                foreach($this->super_model->select_custom_where("bs_head_adjustment","invoice_no='".$invoice_no_exp[$x]."'") AS $p){
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
            // foreach($this->super_model->select_custom_where("sales_adjustment_details","print_identifier='$print_identifier' AND serial_no='".$invoice_no_exp[$x]."' GROUP by serial_no") AS $p){
            // foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id = sah.sales_adjustment_id WHERE print_identifier='$print_identifier' AND serial_no='".$invoice_no_exp[$x]."' AND sah.saved != '0' GROUP by serial_no") AS $p){
            foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id = sah.sales_adjustment_id WHERE print_identifier='$print_identifier' AND serial_no='".$invoice_no_exp[$x]."' AND sah.saved != '0' GROUP by serial_no") AS $p){
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                // $mother_participant_id = $this->super_model->select_column_where("subparticipant","participant_id","sub_participant",$participant_id);

                //     if($mother_participant_id != ''){
                //             $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                //             $company_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                //             $tin_no = $this->super_model->select_column_where("participant","tin","participant_id",$mother_participant_id);
                //     }else{
                //             $address = $this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                //             $company_name = $p->company_name;
                //             $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                //     }

                $mother_participant_id = $this->super_model->select_column_where("subparticipant","participant_id","sub_participant",$participant_id);

                // if($mother_participant_id != ''){
                //         $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                //         $company_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                //         $tin_no = $this->super_model->select_column_where("participant","tin","participant_id",$mother_participant_id);
                //         $settlement = $this->super_model->select_column_where("participant","settlement_id","participant_id",$mother_participant_id);
                // }else{
                //         $address = $this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                //         $company_name = $p->company_name;
                //         $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                //         $settlement = $this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
                // }
                if($mother_participant_id != ''){
                        $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                        // $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id", $p->sales_adjustment_id);
                        // // $comp_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
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

                        // $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id", $p->sales_adjustment_id);
                        if(!empty($p->company_name)){
                            $company_name=$p->company_name;
                        }else{
                            $company_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $p->billing_id);
                        }
                        // $company_name = $p->company_name;
                        $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                        $settlement = $this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
                }


                // $data['address'][$x]=$this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                // $data['tin'][$x]=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                // $data['company_name'][$x]=$p->company_name;
                $data['address'][$x]=$address;
                $data['tin'][$x]=$tin_no;
                $data['company_name'][$x]=$company_name;
                $data['due_date'][$x]=$this->super_model->select_column_where("sales_adjustment_head","due_date","sales_adjustment_id",$p->sales_adjustment_id);
                $data['billing_from'][$x]=$this->super_model->select_column_where("sales_adjustment_head","billing_from","sales_adjustment_id",$p->sales_adjustment_id);
                $data['billing_to'][$x]=$this->super_model->select_column_where("sales_adjustment_head","billing_to","sales_adjustment_id",$p->sales_adjustment_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("sales_adjustment_head","transaction_date","sales_adjustment_id",$p->sales_adjustment_id);
                // $vatable_sales= $this->super_model->select_sum_where("sales_adjustment_details","vatable_sales","serial_no='$p->serial_no'");
                // $zero_rated_sales= $this->super_model->select_sum_where("sales_adjustment_details","zero_rated_sales","serial_no='$p->serial_no'");
                // $zero_rated_ecozones= $this->super_model->select_sum_where("sales_adjustment_details","zero_rated_ecozones","serial_no='$p->serial_no'");
                // $vat_on_sales= $this->super_model->select_sum_where("sales_adjustment_details","vat_on_sales","serial_no='$p->serial_no'");
                // $ewt= $this->super_model->select_sum_where("sales_adjustment_details","ewt","serial_no='$p->serial_no'");
                $vatable_sales = $this->super_model->select_sum_join("vatable_sales","sales_adjustment_details","sales_adjustment_head", "serial_no='$p->serial_no' AND saved = '1'","sales_adjustment_id");
                $zero_rated_sales = $this->super_model->select_sum_join("zero_rated_sales","sales_adjustment_details","sales_adjustment_head", "serial_no='$p->serial_no' AND saved = '1'","sales_adjustment_id");
                $zero_rated_ecozones = $this->super_model->select_sum_join("zero_rated_ecozones","sales_adjustment_details","sales_adjustment_head", "serial_no='$p->serial_no' AND saved = '1'","sales_adjustment_id");
                $vat_on_sales = $this->super_model->select_sum_join("vat_on_sales","sales_adjustment_details","sales_adjustment_head", "serial_no='$p->serial_no' AND saved = '1'","sales_adjustment_id");
                $ewt = $this->super_model->select_sum_join("ewt","sales_adjustment_details","sales_adjustment_head", "serial_no='$p->serial_no' AND saved = '1'","sales_adjustment_id");
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
        $this->load->view('sales/print_invoice_adjustment_half',$data);
    }

    public function print_invoice_adjustment_main(){
        error_reporting(0);
        $invoice_no = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $invoice_no_exp=explode("-",$invoice_no);
        $data['count']=$count;
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        for($x=0;$x<$count;$x++){
             $invoice[]=$this->super_model->custom_query_single('invoice_no',"SELECT * FROM sales_adjustment_details sad  INNER JOIN bs_head_adjustment bha ON bha.invoice_no=sad.serial_no WHERE bha.invoice_no='$invoice_no_exp[$x]'");
            if(array_key_exists($invoice_no_exp[$x],$invoice)){
                foreach($this->super_model->select_custom_where("bs_head_adjustment","invoice_no='".$invoice_no_exp[$x]."'") AS $p){
                    $data['address'][$x]=$p->address;
                    $data['tin'][$x]=$p->tin;
                    $data['company_name'][$x]=$p->participant_name;
                    $data['due_date'][$x]=$p->due_date;
                    $data['transaction_date'][$x]=$p->statement_date;
                    $participant_id = $p->participant_id;
                    $data['participant_id'][$x] = $p->participant_id;
                    $reference_number = $p->reference_number;
                    $data['reference_number'][$x] = $this->super_model->select_column_where("bs_details_adjustment","reference_number","bs_head_adjustment_id",$p->bs_head_adjustment_id);
                    $data['or_no'][$x] = $p->invoice_no;
                }

            // $sum_vs_exp = explode(".", $p->total_vatable_sales);
            // $sum_vatable_sales_peso=$sum_vs_exp[0];
            // $sum_vatable_sales_cents=$sum_vs_exp[1];

            // $sum_vos_exp=explode(".", $p->vat);
            // $sum_vat_on_sales_peso=$sum_vos_exp[0];
            // $sum_vat_on_sales_cents=$sum_vos_exp[1];

            // $sum_e_exp=explode(".", $p->total_ewt);
            // $sum_ewt_peso=$sum_e_exp[0];
            // $sum_ewt_cents=$sum_e_exp[1];

            // $sum_zr_exp=explode(".", $p->total_zero_rated);
            // $sum_zero_rated_peso=$sum_zr_exp[0];
            // $sum_zero_rated_cents=$sum_zr_exp[1];

            $data['total_vs'][$x] = $p->total_vatable_sales;
            // $total_vs=$p->total_vatable_sales;
            // $vatable_sales = explode(".",$total_vs);
            // $data['vat_sales_peso'][$x] = $vatable_sales[0];
            // $data['vat_sales_cents'][$x] = $vatable_sales[1];

            // $total_zr=$p->total_zero_sales;
            // $data['total_zr'][$x]=$total_zr;
            // $zero_rated_sales = explode(".",$total_zr);
            // $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            // $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            // $data['total_zr_sub'][$x]=0;
            // $data['zero_rated_peso_sub'][$x] = 0;
            // $data['zero_rated_cents_sub'][$x] = 0;

            $data['total_zra'][$x] = $p->total_zero_ecozones;
            // $total_zra=$p->total_zero_ecozones;
            // $data['total_zra'][$x]=$total_zra;
            // $zero_rated_ecozones_exp=explode(".", $total_zra);
            // $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            // $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            $data['total_vos'][$x] = $p->total_vat;
            // $total_vos=$p->total_vat;
            // $vat_on_sales = explode(".",$total_vos);
            // $data['vat_peso'][$x] = $vat_on_sales[0];
            // $data['vat_cents'][$x] = $vat_on_sales[1];

            $data['total_ewt'][$x] = $p->total_vat;
            // $total_ewt=$p->total_ewt;
            // $ewt_exp=explode(".", $total_ewt);
            // $data['ewt_peso'][$x]=$ewt_exp[0];
            // $data['ewt_cents'][$x]=$ewt_exp[1];

            // $total= $p->total_net_amount;

            // $total_amount=str_replace(',','',number_format($total,2));
           
            // $total_amount_sub=0;
            // $data['total_amount'][$x]=$total_amount;
            // $data['amount_words'][$x]=strtoupper($this->convertNumber($total_amount));
            // $total_exp=explode(".", $total_amount);
            // $data['total_peso'][$x]=$total_exp[0];
            // $data['total_cents'][$x]=$total_exp[1];

        }else{
            // foreach($this->super_model->select_custom_where("sales_adjustment_details","print_identifier='$print_identifier' AND serial_no='".$invoice_no_exp[$x]."' GROUP by serial_no") AS $p){
                 foreach($this->super_model->custom_query("SELECT * FROM sales_adjustment_details sad INNER JOIN sales_adjustment_head sah ON sad.sales_adjustment_id = sah.sales_adjustment_id WHERE print_identifier='$print_identifier' AND serial_no='".$invoice_no_exp[$x]."' AND sah.saved != '0' GROUP by serial_no") AS $p){
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                // $mother_participant_id = $this->super_model->select_column_where("subparticipant","participant_id","sub_participant",$participant_id);

                //     if($mother_participant_id != ''){
                //             $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                //             $company_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                //             $tin_no = $this->super_model->select_column_where("participant","tin","participant_id",$mother_participant_id);
                //     }else{
                //             $address = $this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                //             $company_name = $p->company_name;
                //             $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                //     }

                $mother_participant_id = $this->super_model->select_column_where("subparticipant","participant_id","sub_participant",$participant_id);
                if($mother_participant_id != ''){
                            $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                        // $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id", $p->sales_adjustment_id);
                        // // $comp_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
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

                        // $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id", $p->sales_adjustment_id);
                        if(!empty($p->company_name)){
                            $company_name=$p->company_name;
                        }else{
                            $company_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $p->billing_id);
                        }
                        // $company_name = $p->company_name;
                        $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                        $settlement = $this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
                }


                // $data['address'][$x]=$this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                // $data['tin'][$x]=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                // $data['company_name'][$x]=$p->company_name;
                $data['address'][$x]=$address;
                $data['tin'][$x]=$tin_no;
                $data['company_name'][$x]=$company_name;
                $data['due_date'][$x]=$this->super_model->select_column_where("sales_adjustment_head","due_date","sales_adjustment_id",$p->sales_adjustment_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("sales_adjustment_head","transaction_date","sales_adjustment_id",$p->sales_adjustment_id);
                $data['reference_number'][$x]=$this->super_model->select_column_where("sales_adjustment_head","reference_number","sales_adjustment_id",$p->sales_adjustment_id);
                $data['or_no'][$x]=$p->serial_no;
                $vatable_sales = $this->super_model->select_sum_join("vatable_sales","sales_adjustment_details","sales_adjustment_head", "serial_no='$p->serial_no' AND saved = '1'","sales_adjustment_id");
                $zero_rated_sales = $this->super_model->select_sum_join("zero_rated_sales","sales_adjustment_details","sales_adjustment_head", "serial_no='$p->serial_no' AND saved = '1'","sales_adjustment_id");
                $zero_rated_ecozones = $this->super_model->select_sum_join("zero_rated_ecozones","sales_adjustment_details","sales_adjustment_head", "serial_no='$p->serial_no' AND saved = '1'","sales_adjustment_id");
                $vat_on_sales = $this->super_model->select_sum_join("vat_on_sales","sales_adjustment_details","sales_adjustment_head", "serial_no='$p->serial_no' AND saved = '1'","sales_adjustment_id");
                $ewt = $this->super_model->select_sum_join("ewt","sales_adjustment_details","sales_adjustment_head", "serial_no='$p->serial_no' AND saved = '1'","sales_adjustment_id");
                // $vatable_sales= $this->super_model->select_sum_where("sales_adjustment_details","vatable_sales","serial_no='$p->serial_no'");
                // $zero_rated_sales= $this->super_model->select_sum_where("sales_adjustment_details","zero_rated_sales","serial_no='$p->serial_no'");
                // $zero_rated_ecozones= $this->super_model->select_sum_where("sales_adjustment_details","zero_rated_ecozones","serial_no='$p->serial_no'");
                // $vat_on_sales= $this->super_model->select_sum_where("sales_adjustment_details","vat_on_sales","serial_no='$p->serial_no'");
                // $ewt= $this->super_model->select_sum_where("sales_adjustment_details","ewt","serial_no='$p->serial_no'");
            }

            $data['total_vs'][$x]= $vatable_sales;
            // $total_vs=$vatable_sales;
            // $vatable_sales = explode(".",$total_vs);
            // $data['vat_sales_peso'][$x] = $vatable_sales[0];
            // $data['vat_sales_cents'][$x] = $vatable_sales[1];

            // $total_zr=$zero_rated_sales;
            // $data['total_zr'][$x]=$total_zr;
            // $zero_rated_sales = explode(".",$total_zr);
            // $data['zero_rated_peso'][$x] = $zero_rated_sales[0];
            // $data['zero_rated_cents'][$x] = $zero_rated_sales[1];

            $data['total_zra'][$x]= $zero_rated_ecozones;
            // $total_zra=$zero_rated_ecozones;
            // $data['total_zra'][$x]=$total_zra;
            // $zero_rated_ecozones_exp=explode(".", $total_zra);
            // $data['zero_rated_ecozones_peso'][$x]=$zero_rated_ecozones_exp[0];
            // $data['zero_rated_ecozones_cents'][$x]=$zero_rated_ecozones_exp[1];

            $data['total_vos'][$x]= $vat_on_sales;
            // $total_vos=$vat_on_sales;
            // $vat_on_sales = explode(".",$total_vos);
            // $data['vat_peso'][$x] = $vat_on_sales[0];
            // $data['vat_cents'][$x] = $vat_on_sales[1];

            $data['total_ewt'][$x]= $ewt;
            // $total_ewt=$ewt;
            // $ewt_exp=explode(".", $total_ewt);
            // $data['ewt_peso'][$x]=$ewt_exp[0];
            // $data['ewt_cents'][$x]=$ewt_exp[1];

            // $total= ($total_vs + $total_vos + $total_zra + $total_zr) - $total_ewt;
            // $total_amount=str_replace(',','',number_format($total,2));

            // $data['total_amount'][$x]=$total_amount;
            // $data['amount_words'][$x]=strtoupper($this->convertNumber($total_amount));
            // $total_exp=explode(".", $total_amount);
            // $data['total_peso'][$x]=$total_exp[0];
            // $data['total_cents'][$x]=$total_exp[1];
            }
        }
        $this->load->view('template/print_head');
        $this->load->view('sales/print_invoice_adjustment_main',$data);
    }
    
    public function print_invoice_adjustment(){
        error_reporting(0);
        $invoice_no = $this->uri->segment(3);
        $print_identifier = $this->uri->segment(4);
        $count = $this->uri->segment(5);
        $invoice_no_exp=explode("-",$invoice_no);
        $data['count']=$count;
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$_SESSION['user_id']);
        for($x=0;$x<$count;$x++){
             $invoice[]=$this->super_model->custom_query_single('invoice_no',"SELECT * FROM sales_adjustment_details sad  INNER JOIN bs_head_adjustment bha ON bha.invoice_no=sad.serial_no WHERE bha.invoice_no='$invoice_no_exp[$x]'");
            if(array_key_exists($invoice_no_exp[$x],$invoice)){
                foreach($this->super_model->select_custom_where("bs_head_adjustment","invoice_no='".$invoice_no_exp[$x]."'") AS $p){
                    $data['address'][$x]=$p->address;
                    $data['tin'][$x]=$p->tin;
                    $data['company_name'][$x]=$p->participant_name;
                    $data['due_date'][$x]=$p->due_date;
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
            $data['amount_words'][$x]=strtoupper($this->convertNumber($total_amount));
            $total_exp=explode(".", $total_amount);
            $data['total_peso'][$x]=$total_exp[0];
            $data['total_cents'][$x]=$total_exp[1];

        }else{
            foreach($this->super_model->select_custom_where("sales_adjustment_details","print_identifier='$print_identifier' AND serial_no='".$invoice_no_exp[$x]."' GROUP by serial_no") AS $p){
                $participant_id = $this->super_model->select_column_where("participant","participant_id","billing_id",$p->billing_id);
                // $mother_participant_id = $this->super_model->select_column_where("subparticipant","participant_id","sub_participant",$participant_id);

                //     if($mother_participant_id != ''){
                //             $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                //             $company_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                //             $tin_no = $this->super_model->select_column_where("participant","tin","participant_id",$mother_participant_id);
                //     }else{
                //             $address = $this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                //             $company_name = $p->company_name;
                //             $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                //     }

                $mother_participant_id = $this->super_model->select_column_where("subparticipant","participant_id","sub_participant",$participant_id);

                // if($mother_participant_id != ''){
                //         $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                //         $company_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                //         $tin_no = $this->super_model->select_column_where("participant","tin","participant_id",$mother_participant_id);
                //         $settlement = $this->super_model->select_column_where("participant","settlement_id","participant_id",$mother_participant_id);
                // }else{
                //         $address = $this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                //         $company_name = $p->company_name;
                //         $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                //         $settlement = $this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
                // }

                if($mother_participant_id != ''){
                        $address = $this->super_model->select_column_where("participant","registered_address","participant_id",$mother_participant_id);
                        $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id", $p->sales_adjustment_id);
                        $company_name = $this->super_model->select_column_where("participant","participant_name","participant_id",$mother_participant_id);
                        $tin_no = $this->super_model->select_column_where("participant","tin","participant_id",$mother_participant_id);
                        $settlement = $this->super_model->select_column_where("participant","settlement_id","participant_id",$mother_participant_id);
                }else{
                        $address = $this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                        $create_date = $this->super_model->select_column_where("sales_adjustment_head", "create_date", "sales_adjustment_id", $p->sales_adjustment_id);
                        if(!empty($p->company_name)){
                           $company_name=$p->company_name;
                        }else{
                           $company_name=$this->super_model->select_column_where("participant", "participant_name", "billing_id", $p->billing_id);
                        }
                        // $company_name = $p->company_name;
                        $tin_no = $this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                        $settlement = $this->super_model->select_column_where("participant","settlement_id","billing_id",$p->billing_id);
                }


                // $data['address'][$x]=$this->super_model->select_column_where("participant","registered_address","billing_id",$p->billing_id);
                // $data['tin'][$x]=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
                // $data['company_name'][$x]=$p->company_name;
                $data['address'][$x]=$address;
                $data['tin'][$x]=$tin_no;
                $data['company_name'][$x]=$company_name;
                $data['due_date'][$x]=$this->super_model->select_column_where("sales_adjustment_head","due_date","sales_adjustment_id",$p->sales_adjustment_id);
                $data['transaction_date'][$x]=$this->super_model->select_column_where("sales_adjustment_head","transaction_date","sales_adjustment_id",$p->sales_adjustment_id);
                $vatable_sales= $this->super_model->select_sum_where("sales_adjustment_details","vatable_sales","serial_no='$p->serial_no'");
                $zero_rated_sales= $this->super_model->select_sum_where("sales_adjustment_details","zero_rated_sales","serial_no='$p->serial_no'");
                $zero_rated_ecozones= $this->super_model->select_sum_where("sales_adjustment_details","zero_rated_ecozones","serial_no='$p->serial_no'");
                $vat_on_sales= $this->super_model->select_sum_where("sales_adjustment_details","vat_on_sales","serial_no='$p->serial_no'");
                $ewt= $this->super_model->select_sum_where("sales_adjustment_details","ewt","serial_no='$p->serial_no'");
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
        $this->load->view('sales/print_invoice_adjustment_multiple',$data);
    }
    public function export_not_download()
    {

        $date=$this->uri->segment(3);
        $ref_no=$this->uri->segment(4);
        // $stl_id=$this->uri->segment(5);
        $buyer_fullname = urldecode($this->uri->segment(5) ?? '');

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

        //$dir    = "C:\Users\Henne\Downloads\/";
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
        
       // foreach($pdffiles AS $pdf){
       //  echo $pdf . "<br>";
       // }
       foreach($this->super_model->custom_query("SELECT filename FROM collection_head ch INNER JOIN collection_details cd ON ch.collection_id = cd.collection_id WHERE $qu GROUP BY series_number,settlement_id,reference_no") AS $db){
        $db_files[] = $db->filename;
       }
        

       //   foreach($db_files AS $db){
       //  echo $db . "<br>";
       // }
       // $data['result'] = array_diff($db_files, $pdffiles);

        $data['result'] = array_diff($db_files, $pdffiles);

        $result=array_diff($db_files, $pdffiles);
        $x=1;
       

      

     
        
        $this->load->view('sales/export_not_download',$data);
        $this->load->view('template/footer');
    }

    public function collection_reserve_list(){
        $date=$this->uri->segment(3);
        $ref_no=$this->uri->segment(4);
        // $stl_id=$this->uri->segment(5);
        $buyer_fullname = urldecode($this->uri->segment(5) ?? '');
        $data['date'] = $date;
        $data['ref_no'] = $ref_no;
        // $data['stl_id'] = $stl_id;
        $data['buyer_fullname'] = $buyer_fullname;
        $data['employees']=$this->super_model->select_all_order_by('users','fullname','ASC');
        $data['collection_date'] = $this->super_model->custom_query("SELECT DISTINCT collection_date FROM collection_reserve_head WHERE saved != '0'");
        $data['reference_no'] = $this->super_model->custom_query("SELECT DISTINCT reference_no FROM collection_reserve_head ch INNER JOIN collection_reserve_details cd ON ch.res_collection_id = cd.res_collection_id WHERE reference_no!='' AND saved != '0'");
        $data['buyer'] = $this->super_model->custom_query("SELECT DISTINCT settlement_id,buyer_fullname FROM collection_reserve_head ch INNER JOIN collection_reserve_details cd ON ch.res_collection_id = cd.res_collection_id WHERE reference_no!='' AND saved != '0' GROUP BY buyer_fullname");
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
        foreach($this->super_model->custom_query("SELECT * FROM collection_reserve_head ch INNER JOIN collection_reserve_details cd ON ch.res_collection_id = cd.res_collection_id WHERE $qu") AS $col){
            $count_series=$this->super_model->count_custom_where("collection_reserve_details","series_number='$col->series_number' AND series_number!='' AND settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND series_number='$col->series_number'");
            $sum_amount= $this->super_model->select_sum_where("collection_reserve_details","amount","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND series_number='$col->series_number'");
            $sum_zero_rated= $this->super_model->select_sum_where("collection_reserve_details","zero_rated","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND series_number='$col->series_number'");
            $sum_zero_rated_ecozone= $this->super_model->select_sum_where("collection_reserve_details","zero_rated_ecozone","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND series_number='$col->series_number'");
            $sum_vat = $this->super_model->select_sum_where("collection_reserve_details","vat","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND series_number='$col->series_number'");
            $sum_ewt= $this->super_model->select_sum_where("collection_reserve_details","ewt","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND series_number='$col->series_number'");
            $sum_def_int = $this->super_model->select_sum_where("collection_reserve_details","defint","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND series_number='$col->series_number'");
            $overall_total=($sum_amount + $sum_zero_rated + $sum_zero_rated_ecozone + $sum_vat)-$sum_ewt;
            $data['collection'][]=array(
                "count_series"=>$count_series,
                "res_collection_details_id"=>$col->res_collection_details_id,
                "collection_date"=>$col->collection_date,
                "res_collection_id"=>$col->res_collection_id,
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
            $data['details_id']=$col->res_collection_details_id;
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/collection_reserve_list', $data);
        $this->load->view('template/footer');
    }

    public function add_reserve_collection_head(){
        $data=array(
            "collection_date"=>$this->input->post('collection_date'),
            "user_id"=>$_SESSION['user_id'],
        );
        $res_collection_id = $this->super_model->insert_return_id("collection_reserve_head",$data);
        echo $res_collection_id;
    }

    public function upload_reserve_collection(){
        $id=$this->uri->segment(3);
        $data['res_collection_id'] = $id;
        $data['collection']=array();
        if(!empty($id)){
            foreach($this->super_model->select_row_where("collection_reserve_head", "res_collection_id", $id) AS $h){
                $data['saved']=$h->saved;
                $data['collection_date']=$h->collection_date;
                foreach($this->super_model->select_row_where("collection_reserve_details","res_collection_id",$h->res_collection_id) AS $col){
                    $count_series=$this->super_model->count_custom_where("collection_reserve_details","series_number='$col->series_number' AND series_number!='' AND settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id'");
                    $sum_amount= $this->super_model->select_sum_where("collection_reserve_details","amount","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id'");
                    $sum_zero_rated= $this->super_model->select_sum_where("collection_reserve_details","zero_rated","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id'");
                    $sum_zero_rated_ecozone= $this->super_model->select_sum_where("collection_reserve_details","zero_rated_ecozone","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id'");
                    $sum_vat = $this->super_model->select_sum_where("collection_reserve_details","vat","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id'");
                    $sum_ewt= $this->super_model->select_sum_where("collection_reserve_details","ewt","reference_no='$col->reference_no' AND settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id'");
                    $overall_total=($sum_amount + $sum_zero_rated + $sum_zero_rated_ecozone + $sum_vat)-$sum_ewt;
                    $data['collection'][]=array(
                        "count_series"=>$count_series,
                        "res_collection_details_id"=>$col->res_collection_details_id,
                        "res_collection_id"=>$col->res_collection_id,
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
        $this->load->view('sales/upload_reserve_collection', $data);
        $this->load->view('template/footer');
    }

    public function upload_reserve_bulk_collection(){
        $res_collection_id=$this->input->post('res_collection_id');
        $dest= realpath(APPPATH . '../uploads/excel/');
        $error_ext=0;
        if(!empty($_FILES['doc']['name'])){
            $exc= basename($_FILES['doc']['name']);
            $exc=explode('.',$exc);
            $ext1=$exc[1];
            if($ext1=='php'){
                $error_ext++;
            } 
            else {
                $filename1='bulkcollectionreserve.'.$ext1;
                if(move_uploaded_file($_FILES["doc"]['tmp_name'], $dest.'/'.$filename1)){
                     $this->readBulkCollectionReserve($res_collection_id,$ext1);
                } 
            }
        }
    }

    public function readBulkCollectionReserve($res_collection_id,$doc_type){
        $objPHPExcel = new Spreadsheet();
        if($doc_type=='xlsx'){
            $inputFileName =realpath(APPPATH.'../uploads/excel/bulkcollectionreserve.xlsx');
        }else if($doc_type=='xlsm'){
            $inputFileName =realpath(APPPATH.'../uploads/excel/bulkcollectionreserve.xlsm');
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
        $this->super_model->update_where("collection_reserve_head", $data_head, "res_collection_id", $res_collection_id);
        $a=1;
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
                    'res_collection_id'=>$res_collection_id,
                    'or_date'=>$this->super_model->select_column_where("collection_reserve_head","collection_date","res_collection_id",$res_collection_id),
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
                $this->super_model->insert_into("collection_reserve_details", $data_details);
                $a++;
            }
        }
    }

    public function print_OR_reserve()
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
        $this->load->view('sales/print_OR_reserve',$data);
    }

    public function update_reserve_seriesno(){
        $ref_no=$this->input->post('reference_number');
        $res_collection_id=$this->input->post('res_collection_id');
        $new_series=$this->input->post('series_number');
        $old_series=$this->input->post('old_series');
        $settlement_id=$this->input->post('settlement_id');
        $item_no=$this->input->post('item_no');
        foreach($this->super_model->select_custom_where("collection_reserve_details","res_collection_id='$res_collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $check){
            $count=$this->super_model->count_custom_where("collection_reserve_details","res_collection_id = '$check->res_collection_id' AND old_series_no!='' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)");
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

        if($this->super_model->update_custom_where("collection_reserve_details", $data_update, "res_collection_id='$res_collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)")){
            foreach($this->super_model->select_custom_where("collection_reserve_details","res_collection_id='$res_collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $latest_series){
               echo $latest_series->series_number;
            }
        }
    }

    public function update_reserve_ordate(){
        $ref_no=$this->input->post('reference_number');
        $res_collection_id=$this->input->post('res_collection_id');
        $new_or_date=$this->input->post('or_date');
        $old_or_date=$this->input->post('or_date');
        $settlement_id=$this->input->post('settlement_id');
        $item_no=$this->input->post('item_no');
        foreach($this->super_model->select_custom_where("collection_reserve_details","res_collection_id='$res_collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $check){
            $count=$this->super_model->count_custom_where("collection_reserve_details","res_collection_id = '$check->res_collection_id' AND old_or_date!='' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)");
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

        if($this->super_model->update_custom_where("collection_reserve_details", $data_update, "res_collection_id='$res_collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)")){
            foreach($this->super_model->select_custom_where("collection_reserve_details","res_collection_id='$res_collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $latest_ordate){
               echo $latest_ordate->or_date;
            }
        }
    }

    public function update_reserve_defint(){
        $ref_no=$this->input->post('reference_number');
        $res_collection_id=$this->input->post('res_collection_id');
        $def_int=$this->input->post('def_int');
        $settlement_id=$this->input->post('settlement_id');
        $item_no=$this->input->post('item_no');
        $data_update = array(
            'defint'=>$def_int,
        );

        if($this->super_model->update_custom_where("collection_reserve_details", $data_update, "res_collection_id='$res_collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)")){
            foreach($this->super_model->select_custom_where("collection_reserve_details","res_collection_id='$res_collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $latest_defint){
               echo $latest_defint->defint;
            }
        }
    }

    public function update_reserve_orno_remarks(){
        $ref_no=$this->input->post('reference_number');
        $res_collection_id=$this->input->post('res_collection_id');
        $orno_remarks=$this->input->post('or_no_remarks');
        $settlement_id=$this->input->post('settlement_id');
        $item_no=$this->input->post('item_no');
        $data_update = array(
            'or_no_remarks'=>$orno_remarks,
        );
        if($this->super_model->update_custom_where("collection_reserve_details", $data_update, "res_collection_id='$res_collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)")){
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
            foreach($this->super_model->select_custom_where("collection_reserve_details","res_collection_id='$res_collection_id' AND settlement_id='$settlement_id' AND reference_no='$ref_no' AND item_no IN($item_no)") AS $latest_or_remarks){
               echo $latest_or_remarks->or_no_remarks;
            }
        }
    }

    public function save_reserve_all_collection(){
        $res_collection_id = $this->input->post('res_collection_id');
        $data_head = array(
            'saved'=>1,
        );
        $this->super_model->update_where("collection_reserve_head",$data_head, "res_collection_id", $res_collection_id);
    }

    public function PDF_OR_reserve(){
        $res_collection_id=$this->uri->segment(3);
        $settlement_id=str_replace('%20', ' ', $this->uri->segment(4));
        $reference_no=$this->uri->segment(5);
        $series_number=$this->uri->segment(6);
        $signatory=($this->uri->segment(7)!='' || !empty($this->uri->segment(7))) ? $this->uri->segment(7) : $_SESSION['user_id'];
        $data['ref_no'] = $reference_no;
        $data['refno'] = preg_replace("/[^0-9]/", "",$reference_no);
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id",$signatory);
        $collection_date = $this->super_model->select_column_where("collection_reserve_head", "collection_date", "res_collection_id", $res_collection_id);
        $data['billing_month'] = date('my',strtotime($collection_date));
        $data['timestamp'] = date('Ymd');
        $billing_id = $this->super_model->select_column_where("reserve_participant", "res_billing_id", "res_settlement_id", $settlement_id);
        $data['address']=$this->super_model->select_column_where("reserve_participant", "res_registered_address", "res_billing_id", $billing_id);
        $data['tin']=$this->super_model->select_column_where("reserve_participant", "res_tin", "res_billing_id", $billing_id);
        $data['stl_id']=$this->super_model->select_column_custom_where("collection_reserve_details","settlement_id","settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['or_no']=$series_number;
        $data['buyer']=$this->super_model->select_column_custom_where("collection_reserve_details","buyer_fullname","settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no' ");
        $data['sum_amount']=$this->super_model->select_sum_where("collection_reserve_details","amount","settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['sum_vat']=$this->super_model->select_sum_where("collection_reserve_details","vat","settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['sum_ewt'] =  $this->super_model->select_sum_where("collection_reserve_details", "ewt", "settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['sum_zero_rated'] =  $this->super_model->select_sum_where("collection_reserve_details", "zero_rated", "settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['sum_zero_rated_ecozone'] =  $this->super_model->select_sum_where("collection_reserve_details", "zero_rated_ecozone", "settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['defint'] =  $this->super_model->select_sum_where("collection_reserve_details", "defint", "settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $data['date']=$this->super_model->select_column_custom_where("collection_reserve_details","or_date","settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no' AND series_number='$series_number'");
        $this->load->view('sales/PDF_OR_reserve',$data);
    }

    public function PDF_OR_bulk_reserve(){
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
        //     $sql.= "cd.settlement_id = '$stl_id' AND "; 
        // }
        $query=substr($sql,0,-4);
        $qu = "bulk_pdf_flag = '0' AND series_number != '0' AND saved = '1' AND ".$query;
        $data['details']=array();
        $data['user_signature']=$this->super_model->select_column_where("users","user_signature","user_id", $signatory);
        $data['timestamp'] = date('Ymd');
        foreach($this->super_model->custom_query("SELECT * FROM collection_reserve_head ch INNER JOIN collection_reserve_details cd ON ch.res_collection_id = cd.res_collection_id WHERE $qu GROUP BY series_number LIMIT 10") AS $col){
            if($ref_no!='null'){
                $reference_number = $ref_no;
            }else{
                $reference_number = $col->reference_no;
            }
            $billing_month = date('my',strtotime($col->collection_date));
            $refno = preg_replace("/[^0-9]/", "",$reference_number);
            $billing_id = $this->super_model->select_column_where("reserve_participant", "res_billing_id", "res_settlement_id", $col->settlement_id);
            $sum_amount=$this->super_model->select_sum_where("collection_reserve_details","amount","settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_vat=$this->super_model->select_sum_where("collection_reserve_details","vat","settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_ewt =  $this->super_model->select_sum_where("collection_reserve_details", "ewt", "settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND reference_no='$col->reference_no'");
            $sum_zero_rated =  $this->super_model->select_sum_where("collection_reserve_details", "zero_rated", "settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_zero_rated_ecozone =  $this->super_model->select_sum_where("collection_reserve_details", "zero_rated_ecozone", "settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $defint =  $this->super_model->select_sum_where("collection_reserve_details", "defint", "settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $data['details'][] = array(
                'res_collection_details_id'=>$col->res_collection_details_id,
                'res_collection_id'=>$col->res_collection_id,
                'billing_id'=>$billing_id,
                'address'=>$this->super_model->select_column_where("reserve_participant", "res_registered_address", "res_billing_id", $billing_id),
                'tin'=>$this->super_model->select_column_where("reserve_participant", "res_tin", "res_billing_id", $billing_id),
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
        $this->load->view('sales/PDF_OR_bulk_reserve',$data);
    }

    public function print_OR_new_reserve()
    {
        $res_collection_id=$this->uri->segment(3);
        $settlement_id=$this->uri->segment(4);
        $reference_no=$this->uri->segment(5);
        $data['ref_no'] = $reference_no;
        $data['client']=$this->super_model->select_row_where("reserve_participant", "res_billing_id", $settlement_id);
        $data['sum_amount']=$this->super_model->select_sum_where("collection_reserve_details","amount","settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no'");
        $data['sum_vat']=$this->super_model->select_sum_where("collection_reserve_details","vat","settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no'");
        $data['sum_ewt'] =  $this->super_model->select_sum_where("collection_reserve_details", "ewt", "settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no'");
        $data['sum_zero_rated'] =  $this->super_model->select_sum_where("collection_reserve_details", "zero_rated", "settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no'");
        $data['sum_zero_rated_ecozone'] =  $this->super_model->select_sum_where("collection_reserve_details", "zero_rated_ecozone", "settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no'");
        $data['defint'] =  $this->super_model->select_sum_where("collection_reserve_details", "defint", "settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no'");
        $data['date'] =  $this->super_model->select_column_custom_where("collection_reserve_details","or_date","settlement_id='$settlement_id' AND res_collection_id='$res_collection_id' AND reference_no='$reference_no'");
        $this->load->view('template/print_head');
        $this->load->view('sales/print_OR_new_reserve',$data);
    }

    public function export_not_download_reserve()
    {
        $date=$this->uri->segment(3);
        $ref_no=$this->uri->segment(4);
        // $stl_id=$this->uri->segment(5);
        $buyer_fullname = urldecode($this->uri->segment(5) ?? '');
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
        // $dir    = "C:\Users\steph\Downloads\/";
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
        foreach($this->super_model->custom_query("SELECT filename FROM collection_reserve_head ch INNER JOIN collection_reserve_details cd ON ch.res_collection_id = cd.res_collection_id WHERE $qu GROUP BY series_number,settlement_id,reference_no") AS $db){
                $db_files[] = $db->filename;
        }
        $data['result'] = array_diff($db_files, $pdffiles);
        $result=array_diff($db_files, $pdffiles);
        $x=1;
        $this->load->view('sales/export_not_download_reserve',$data);
        $this->load->view('template/footer');
    }

    public function pdf_scan_directory_reserve(){
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
        foreach($this->super_model->custom_query("SELECT * FROM collection_reserve_head ch INNER JOIN collection_reserve_details cd ON ch.res_collection_id = cd.res_collection_id WHERE $qu GROUP BY series_number,settlement_id,reference_no") AS $col){
            $reference_number = $col->reference_no;
            $billing_month = date('my',strtotime($col->collection_date));
            $refno = preg_replace("/[^0-9]/", "",$reference_number);
            $billing_id = $this->super_model->select_column_where("reserve_participant", "res_billing_id", "res_settlement_id", $col->settlement_id);
            $sum_amount=$this->super_model->select_sum_where("collection_reserve_details","amount","settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_vat=$this->super_model->select_sum_where("collection_reserve_details","vat","settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_ewt =  $this->super_model->select_sum_where("collection_reserve_details", "ewt", "settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND reference_no='$col->reference_no'");
            $sum_zero_rated =  $this->super_model->select_sum_where("collection_reserve_details", "zero_rated", "settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $sum_zero_rated_ecozone =  $this->super_model->select_sum_where("collection_reserve_details", "zero_rated_ecozone", "settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $defint =  $this->super_model->select_sum_where("collection_reserve_details", "defint", "settlement_id='$col->settlement_id' AND res_collection_id='$col->res_collection_id' AND reference_no='$col->reference_no' AND series_number='$col->series_number'");
            $data['details'][] = array(
                'res_collection_details_id'=>$col->res_collection_details_id,
                'res_collection_id'=>$col->res_collection_id,
                'billing_id'=>$billing_id,
                'address'=>$this->super_model->select_column_where("reserve_participant", "res_registered_address", "res_billing_id", $billing_id),
                'tin'=>$this->super_model->select_column_where("reserve_participant", "res_tin", "res_billing_id", $billing_id),
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
        $this->load->view('sales/pdf_scan_directory_reserve',$data);
    }

    public function update_reserve_flag(){
        $series_number = $this->input->post('series_no');
        $settlement_id = $this->input->post('stl_id');
        $reference_no = $this->input->post('reference_no');
        $res_collection_id = $this->input->post('res_collection_id');
        $filename = $this->input->post('filename');
        $data_update = array(
            "bulk_pdf_flag"=>1,
            "filename"=>$filename
        );
        $this->super_model->update_custom_where("collection_reserve_details", $data_update, "series_number='$series_number' AND settlement_id='$settlement_id' AND reference_no='$reference_no' AND res_collection_id='$res_collection_id'");
    }
}