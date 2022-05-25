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

    public function upload_sales()
    {
        $id=$this->uri->segment(3);
        $data['sales_id'] = $id;

        if(!empty($id)){
            foreach($this->super_model->select_row_where("sales_transaction_head", "sales_id",$id) AS $h){
                $data['transaction_date']=$h->transaction_date;
                $data['billing_from']=$h->billing_from;
                $data['billing_to']=$h->billing_to;
                $data['reference_number']=$h->reference_number;
                $data['due_date']=$h->due_date;
                $data['saved']=$h->saved;
                foreach($this->super_model->select_row_where("sales_transaction_details","sales_id",$h->sales_id) AS $d){
                    $data['details'][]=array(
                        'sales_detail_id'=>$d->sales_detail_id,
                        'sales_id'=>$d->sales_id,
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
                        'total_amount'=>$d->total_amount,
                    );
                }
            }
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/upload_sales',$data);
        $this->load->view('template/footer');
    }

    public function add_sales_head(){
        $tdate=date("Y-m-d", strtotime($this->input->post('transaction_date')));
        $billingf=date("Y-m-d", strtotime($this->input->post('billing_from')));
        $billingt=date("Y-m-d", strtotime($this->input->post('billing_to')));
        $due=date("Y-m-d", strtotime($this->input->post('due_date')));
        $data=array(
            "reference_number"=>$this->input->post('reference_number'),
            "transaction_date"=>$tdate,
            "billing_from"=>$billingf,
            "billing_to"=>$billingt,
            "due_date"=>$due,
            "user_id"=>$_SESSION['user_id'],
            "create_date"=>date("Y-m-d H:i:s")
        );
        $sales_id = $this->super_model->insert_return_id("sales_transaction_head",$data);

        echo $sales_id;
    }

    public function upload_sales_process(){
        $sales_id = $this->input->post('sales_id');
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
        for($x=3;$x<=$highestRow;$x++){
            $itemno = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getFormattedValue());
            $shortname = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getFormattedValue());
            $billing_id = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getFormattedValue());
            $company_name = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getFormattedValue());
            $fac_type = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getFormattedValue());
            $wht_agent = trim($objPHPExcel->getActiveSheet()->getCell('F'.$x)->getFormattedValue());
            $ith = trim($objPHPExcel->getActiveSheet()->getCell('G'.$x)->getFormattedValue());
            $non_vatable = trim($objPHPExcel->getActiveSheet()->getCell('H'.$x)->getFormattedValue());
            $zero_rated = trim($objPHPExcel->getActiveSheet()->getCell('I'.$x)->getFormattedValue());
            $vatable_sales = $objPHPExcel->getActiveSheet()->getCell('J'.$x)->getFormattedValue();
            $zero_rated_sales = $objPHPExcel->getActiveSheet()->getCell('K'.$x)->getFormattedValue();
            $zero_rated_ecozone = $objPHPExcel->getActiveSheet()->getCell('L'.$x)->getFormattedValue();
            $vat_on_sales = $objPHPExcel->getActiveSheet()->getCell('M'.$x)->getFormattedValue();
            $ewt = trim($objPHPExcel->getActiveSheet()->getCell('N'.$x)->getFormattedValue(),'()');
            $total_amount = $objPHPExcel->getActiveSheet()->getCell('O'.$x)->getFormattedValue();
            if (strpos($itemno, 'Note:') === false) {
                $data_sales = array(
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
                );
                $this->super_model->insert_into("sales_transaction_details", $data_sales);
            }
            echo $sales_id;
        }
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

    /*public function load_sales_data(){
        $sales_id=$this->input->post('sales_id');
        $base_url=$this->input->post('baseurl');
        $count_item = $this->super_model->count_rows_where("sales_transaction_details","sales_id",$sales_id);
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details WHERE sales_id='$sales_id'") AS $app){
            echo '<tr id="load_data'.$count_item.'"><td align="center" style="background: #fff;"><div class="btn-group mb-0"><a style="color:#fff" onclick="add_details_BS('.$base_url.','.$app->sales_id.')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Add Details"><span class="m-0 fas fa-indent"></span></a></div><a id="clicksBS"></a></td><td>'.$app->company_name.'</td><td>'.$app->facility_type.'</td><td>'.$app->wht_agent.'</td><td>'.$app->non_vatable.'</td><td>'.$app->zero_rated.'</td><td>'.$app->vatable_sales.'</td><td>'.$app->zero_rated_sales.'</td><td>'.$app->zero_rated_ecozones.'</td><td>'.$app->vat_on_sales.'</td><td>'.$app->ewt.'</td><td>0</td></tr>';
            $count_item++;
        }
    }*/


    public function collection_list()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/collection_list');
        $this->load->view('template/footer');
    }

    public function print_OR()
    {
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('sales/print_OR');
        $this->load->view('template/footer');
    }

    public function sales_wesm(){
        $ref_no=$this->uri->segment(3);
        $participant=$this->uri->segment(4);
        $data['participant']=$participant;
        $data['ref_no']=$ref_no;
        $data['participants']=$this->super_model->select_all_order_by("participant","participant_name","ASC");
        $sql="";
        if($ref_no!='null'){
            $sql.= " AND sh.reference_number LIKE '%$ref_no%' AND";
        }

        if($participant!='null' && $ref_no=='null'){
            $sql.= " AND sd.billing_id = '$participant' AND";
        }else if($ref_no!='null' && $participant!='null'){
            $sql.= " sd.billing_id = '$participant' AND";
        }
        $query=substr($sql,0,-3);
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $row_count=$this->super_model->count_custom("SELECT * FROM sales_transaction_details sd INNER JOIN sales_transaction_head sh ON sd.sales_id=sh.sales_id WHERE saved='1' $query");
        if($row_count!=0){
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details sd INNER JOIN sales_transaction_head sh ON sd.sales_id=sh.sales_id WHERE saved='1' $query") AS $d){
                $data['details'][]=array(
                    'sales_detail_id'=>$d->sales_detail_id,
                    'sales_id'=>$d->sales_id,
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
                    'total_amount'=>$d->total_amount,
                    'reference_number'=>$d->reference_number,
                    'transaction_date'=>$d->transaction_date,
                    'billing_from'=>$d->billing_from,
                    'billing_to'=>$d->billing_to,
                    'due_date'=>$d->due_date,
                );
            }
        }else{
            $data['details']=array();
        }
        $this->load->view('sales/sales_wesm',$data);
        $this->load->view('template/footer');
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

    public function convertNumber($number)
    {
        list($integer,$fraction) = explode(".", (string) $number);

        $output = " ";

        if ($integer{0} == "-")
        {
            $output = "negative ";
            $integer    = ltrim($integer, "-");
        }
        else if ($integer{0} == "+")
        {
            $output = "positive ";
            $integer    = ltrim($integer, "+");
        }

        if ($integer{0} == "0")
        {
            $output .= "zero";
        }
        else
        {
            $integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
            $group   = rtrim(chunk_split($integer, 3, " "), " ");
            $groups  = explode(" ", $group);

            $groups2 = array();
            foreach ($groups as $g)
            {
                $groups2[] = $this->convertThreeDigit($g{0}, $g{1}, $g{2});
            }

            for ($z = 0; $z < count($groups2); $z++)
            {
                if ($groups2[$z] != "")
                {
                    $output .= $groups2[$z] . $this->convertGroup(11 - $z) . (
                            $z < 11
                            && !array_search('', array_slice($groups2, $z + 1, -1))
                            && $groups2[11] != ''
                            && $groups[11]{0} == '0'
                                ? " and "
                                : " "
                        );
                }
            }

            $output = rtrim($output, ", ");
        }

        if ($fraction > 0)
        {
            $output .= " PESOS ";
            for ($i = 0; $i < strlen($fraction); $i++)
            {
               if($fraction==01){

                   $i = 1;
                    while ($i <2):
                         $output .= " and one centavo only";

                     $i++;
                    endwhile;
               }
               else if($fraction==02){

                   $i = 1;
                    while ($i <2):
                         $output .= " and two centavos only";

                     $i++;
                    endwhile;
               }
               else if($fraction==03){

                   $i = 1;
                    while ($i <2):
                         $output .= " and three centavos only";

                     $i++;
                    endwhile;
               }
               else if($fraction==04){

                   $i = 1;
                    while ($i <2):
                             $output .= " and four centavos only";

                     $i++;
                    endwhile;
               }
               else if($fraction==05){

                   $i = 1;
                    while ($i <2):
                             $output .= " and five centavos only";

                     $i++;
                    endwhile;
               }
               else if($fraction==06){

                   $i = 1;
                    while ($i <2):
                             $output .= " and six centavos only";

                     $i++;
                    endwhile;
               }
               else if($fraction==07){

                  $i = 1;
                    while ($i <2):
                             $output .= " point seven centavos only";

                     $i++;
                    endwhile;
               }
                else if($fraction==8 || $fraction==08){

                   $i = 1;
                    while ($i <2):
                     $output .= " and eight centavos only";

                     $i++;
                    endwhile;
               }
                else if($fraction==9 || $fraction==09){

                   $i = 1;
                    while ($i <2):
                     $output .= " and nine centavos only";

                     $i++;
                    endwhile;
               }
                else if($fraction==10){

                      $i = 1;
                    while ($i <2):
                      $output .= " and ten centavos only";

                     $i++;
                    endwhile;
               }
               else if($fraction==11){

                  $i = 1;
                    while ($i <2):
                             $output .= " and eleven centavos only";

                     $i++;
                    endwhile;
               }
                 else if($fraction==12){

                     $i = 1;
                    while ($i <2):
                         $output .= " and twelve centavos only";

                     $i++;
                    endwhile;
               }
               else if($fraction==13){

                 $i = 1;
                    while ($i <2):
                             $output .= " and thirteen centavos only";

                     $i++;
                    endwhile;
               }
                else if($fraction==14){

                   $i = 1;
                    while ($i <2):
                             $output .= " and fourteen centavos only";
                     $i++;
                    endwhile;
               }
               else if($fraction==15){

                   $i = 1;
                    while ($i <2):
                             $output .= " and fifteen centavos only";

                     $i++;
                    endwhile;
               }
               else if($fraction==16){

                   $i = 1;
                    while ($i <2):
                             $output .= " and sixteen centavos only";
                     $i++;
                    endwhile;
               }
               else if($fraction==17){


                   $i = 1;
                    while ($i <2):
                         $output .= " and seventeen centavos only";
                     $i++;
                    endwhile;
               }
               else if($fraction==18){


                   $i = 1;
                    while ($i <2):
                    $output .= " and eighteen centavos only";
                     $i++;
                    endwhile;
               }
               else if($fraction==19){

                   $i = 1;
                    while ($i <2):
                             $output .= " and nineteen centavos only";
                     $i++;
                    endwhile;
               }
               else if($fraction==20){

                   $i = 1;
                    while ($i <2):
                         $output .= " and twenty centavos only";
                     $i++;
                    endwhile;
               }
                else if($fraction==21){
                   $i = 1;
                    while ($i <2):
                      $output .= " and twenty one centavos only";
                     $i++;
                    endwhile;
               }
                else if($fraction==22){

                  $i = 1;
                    while ($i <2):
                             $output .= " and twenty two centavos only";
                     $i++;
                    endwhile;
               }
                else if($fraction==23){
                     $i = 1;
                    while ($i <2):
                             $output .= " and twenty three centavos only";
                     $i++;
                    endwhile;
               }
                else if($fraction==24){

                     $i = 1;
                    while ($i <2):
                         $output .= " and twenty four centavos only";
                     $i++;
                    endwhile;
               }
                else if($fraction==25){

                 $i = 1;
                    while ($i <2):
                     $output .= " and twenty five centavos only";
                     $i++;
                    endwhile;
               }
                else if($fraction==26){

                    $i = 1;
                    while ($i <2):
                        $output .= " and twenty six centavos only";
                     $i++;
                    endwhile;
               }
                else if($fraction==27){

                    $i = 1;
                    while ($i <2):
                     $output .= " and twenty seven centavos only";
                     $i++;
                    endwhile;
               }
                else if($fraction==28){

                    $i = 1;
                    while ($i <2):
                        $output .= " and twenty eight centavos only";
                     $i++;
                    endwhile;
               }
                else if($fraction==29){

                    $i = 1;
                    while ($i <2):
                         $output .= " and twenty nine centavos only";
                        $i++;
                    endwhile;
               }
                 else if($fraction ==30){

                    $i = 1;
                    while ($i <2):
                         $output .= " and thirty centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==31){

                    $i = 1;
                    while ($i <2):
                         $output .= " and thirty one centavos only";
                        $i++;
                    endwhile;
               }
                else if($fraction ==32){

                    $i = 1;
                    while ($i <2):
                         $output .= " and thirty two centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==33){

                    $i = 1;
                    while ($i <2):
                         $output .= " and thirty three centavos only";
                        $i++;
                    endwhile;
               }
                else if($fraction ==34){

                    $i = 1;
                    while ($i <2):
                         $output .= " and thirty four centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==35){

                    $i = 1;
                    while ($i <2):
                         $output .= " and thirty five centavos only";
                        $i++;
                    endwhile;
               }
                 else if($fraction ==36){

                    $i = 1;
                    while ($i <2):
                         $output .= " and thirty six centavos only";
                        $i++;
                    endwhile;
               }
                else if($fraction ==37){

                    $i = 1;
                    while ($i <2):
                         $output .= " and thirty seven centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==38){

                    $i = 1;
                    while ($i <2):
                         $output .= " and thirty eight centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==39){

                    $i = 1;
                    while ($i <2):
                         $output .= " and thirty nine centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==40){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fourty centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==41){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fourty one centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==42){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fourty two centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==43){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fourty three centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==44){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fourty four centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==45){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fourty five centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==46){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fourty six centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==47){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fourty seven centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==48){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fourty eight centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==49){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fourty nine centavos only";
                        $i++;
                    endwhile;
               }
                else if($fraction ==50){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fifty centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==51){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fifty one centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==52){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fifty two centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==53){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fifty three centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==54){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fifty four centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==55){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fifty five centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==56){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fifty six centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==57){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fifty seven centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==58){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fifty eight centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==59){

                    $i = 1;
                    while ($i <2):
                         $output .= " and fifty nine centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==60){

                    $i = 1;
                    while ($i <2):
                         $output .= " and sixty centavos only";
                        $i++;
                    endwhile;
               }
              else if($fraction ==61){

                    $i = 1;
                    while ($i <2):
                         $output .= " and sixty one centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==62){

                    $i = 1;
                    while ($i <2):
                         $output .= " and sixty two centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==63){

                    $i = 1;
                    while ($i <2):
                         $output .= " and sixty three centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==64){

                    $i = 1;
                    while ($i <2):
                         $output .= " and sixty four centavos only";
                        $i++;
                    endwhile;
               }
              else if($fraction ==65){

                    $i = 1;
                    while ($i <2):
                         $output .= " and sixty five centavos only";
                        $i++;
                    endwhile;
               }
             else if($fraction ==66){

                    $i = 1;
                    while ($i <2):
                         $output .= " and sixty six centavos only";
                        $i++;
                    endwhile;
               }

             else if($fraction ==67){

                    $i = 1;
                    while ($i <2):
                         $output .= " and sixty seven centavos only";
                        $i++;
                    endwhile;
               }
            else if($fraction ==68){

                    $i = 1;
                    while ($i <2):
                         $output .= " and sixty eight centavos only";
                        $i++;
                    endwhile;
               }
            else if($fraction ==69){

                    $i = 1;
                    while ($i <2):
                         $output .= " and sixty nine centavos only";
                        $i++;
                    endwhile;
               }
           else if($fraction ==70){

                    $i = 1;
                    while ($i <2):
                         $output .= " and seventy centavos only";
                        $i++;
                    endwhile;
               }
         else if($fraction ==71){

                    $i = 1;
                    while ($i <2):
                         $output .= " and seventy one centavos only";
                        $i++;
                    endwhile;
               }

         else if($fraction ==72){

                    $i = 1;
                    while ($i <2):
                         $output .= " and seventy two centavos only";
                        $i++;
                    endwhile;
               }
        else if($fraction ==73){

                    $i = 1;
                    while ($i <2):
                         $output .= " and seventy three centavos only";
                        $i++;
                    endwhile;
               }
         else if($fraction ==74){

                    $i = 1;
                    while ($i <2):
                         $output .= " and seventy four centavos only";
                        $i++;
                    endwhile;
               }

                else if($fraction ==75){

                    $i = 1;
                    while ($i <2):
                         $output .= " and seventy five centavos only";
                        $i++;
                    endwhile;
               }
                else if($fraction ==76){

                    $i = 1;
                    while ($i <2):
                         $output .= " and seventy six centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==77){

                    $i = 1;
                    while ($i <2):
                         $output .= " and seventy seven centavos only";
                        $i++;
                    endwhile;
               }
                else if($fraction ==78){

                    $i = 1;
                    while ($i <2):
                         $output .= " and seventy eight centavos only";
                        $i++;
                    endwhile;
               }
                else if($fraction ==79){

                    $i = 1;
                    while ($i <2):
                         $output .= " and seventy nine centavos only";
                        $i++;
                    endwhile;
               }
                else if($fraction ==80){

                    $i = 1;
                    while ($i <2):
                         $output .= " and eighty centavos only";
                        $i++;
                    endwhile;
               }
             else if($fraction ==81){

                    $i = 1;
                    while ($i <2):
                         $output .= " and eighty one centavos only";
                        $i++;
                    endwhile;
               }
            else if($fraction ==82){

                    $i = 1;
                    while ($i <2):
                         $output .= " and eighty two centavos only";
                        $i++;
                    endwhile;
               }
           else if($fraction ==83){

                    $i = 1;
                    while ($i <2):
                         $output .= " and eighty three centavos only";
                        $i++;
                    endwhile;
               }
          else if($fraction ==84){

                    $i = 1;
                    while ($i <2):
                         $output .= " and eighty four centavos only";
                        $i++;
                    endwhile;
               }
            else if($fraction ==85){

                    $i = 1;
                    while ($i <2):
                         $output .= " and eighty five centavos only";
                        $i++;
                    endwhile;
               }
             else if($fraction ==86){

                    $i = 1;
                    while ($i <2):
                         $output .= " and eighty six centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==87){

                    $i = 1;
                    while ($i <2):
                         $output .= " and eighty seven centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==88){

                    $i = 1;
                    while ($i <2):
                         $output .= " and eighty eight centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==89){

                    $i = 1;
                    while ($i <2):
                         $output .= " and eighty nine centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==90){

                    $i = 1;
                    while ($i <2):
                         $output .= " and ninety centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==91){

                    $i = 1;
                    while ($i <2):
                         $output .= " and ninety one centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==92){

                    $i = 1;
                    while ($i <2):
                         $output .= " and ninety two centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==93){

                    $i = 1;
                    while ($i <2):
                         $output .= " and ninety three centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==94){

                    $i = 1;
                    while ($i <2):
                         $output .= " and ninety four centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==95){

                    $i = 1;
                    while ($i <2):
                         $output .= " and ninety five centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==96){

                    $i = 1;
                    while ($i <2):
                         $output .= " and ninety six centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==97){

                    $i = 1;
                    while ($i <2):
                         $output .= " and ninety seven centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==98){

                    $i = 1;
                    while ($i <2):
                         $output .= " and ninety eight centavos only";
                        $i++;
                    endwhile;
               }
               else if($fraction ==99){

                    $i = 1;
                    while ($i <2):
                         $output .= " and ninety nine centavos only";
                        $i++;
                    endwhile;
               }

            }

        }
          else{
                $output .= " PESOS ONLY";
               }

        return $output;
    }

    public function convertGroup($index)
    {
        switch ($index)
        {
            case 11:
                return " decillion";
            case 10:
                return " nonillion";
            case 9:
                return " octillion";
            case 8:
                return " septillion";
            case 7:
                return " sextillion";
            case 6:
                return " quintrillion";
            case 5:
                return " quadrillion";
            case 4:
                return " trillion";
            case 3:
                return " billion";
            case 2:
                return " million";
            case 1:
                return " thousand";
            case 0:
                return "";
        }
    }

    public function convertThreeDigit($digit1, $digit2, $digit3)
    {
        $buffer = " ";

        if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0")
        {
            return "";
        }

        if ($digit1 != "0")
        {
            $buffer .= $this->convertDigit($digit1) . " hundred";
            if ($digit2 != "0" || $digit3 != "0")
            {
                $buffer .= " ";
            }
        }

        if ($digit2 != "0")
        {
            $buffer .= $this->convertTwoDigit($digit2, $digit3);
        }
        else if ($digit3 != "0")
        {
            $buffer .= $this->convertDigit($digit3);
        }

        return $buffer;
    }

    public function convertTwoDigit($digit1, $digit2)
    {
        if ($digit2 == "0")
        {
            switch ($digit1)
            {
                case "1":
                    return "ten";
                case "2":
                    return "twenty";
                case "3":
                    return "thirty";
                case "4":
                    return "forty";
                case "5":
                    return "fifty";
                case "6":
                    return "sixty";
                case "7":
                    return "seventy";
                case "8":
                    return "eighty";
                case "9":
                    return "ninety";
            }
        } else if ($digit1 == "1")
        {
            switch ($digit2)
            {
                case "1":
                    return "eleven";
                case "2":
                    return "twelve";
                case "3":
                    return "thirteen";
                case "4":
                    return "fourteen";
                case "5":
                    return "fifteen";
                case "6":
                    return "sixteen";
                case "7":
                    return "seventeen";
                case "8":
                    return "eighteen";
                case "9":
                    return "nineteen";
            }
        } else
        {
            $temp = $this->convertDigit($digit2);
            switch ($digit1)
            {
                case "2":
                    return "twenty $temp";
                case "3":
                    return "thirty $temp";
                case "4":
                    return "forty $temp";
                case "5":
                    return "fifty $temp";
                case "6":
                    return "sixty $temp";
                case "7":
                    return "seventy $temp";
                case "8":
                    return "eighty $temp";
                case "9":
                    return "ninety $temp";
            }
        }
    }

    public function convertDigit($digit)
    {
        switch ($digit)
        {
            case "0":
                return "zero";
            case "1":
                return "one";
            case "2":
                return "two";
            case "3":
                return "three";
            case "4":
                return "four";
            case "5":
                return "five";
            case "6":
                return "six";
            case "7":
                return "seven";
            case "8":
                return "eight";
            case "9":
                return "nine";
        }

    }

    public function print_BS(){
        $sales_detail_id = $this->uri->segment(3);
        $this->load->view('template/header');
       // $this->load->view('template/navbar');
        foreach($this->super_model->select_row_where("sales_transaction_details","sales_detail_id",$sales_detail_id) AS $p){
            $data['address']=$this->super_model->select_column_where("participant","office_address","billing_id",$p->billing_id);
            $data['tin']=$this->super_model->select_column_where("participant","tin","billing_id",$p->billing_id);
            $data['company_name']=$p->company_name;
            $data['amount_words']=strtoupper($this->convertNumber($p->total_amount));
        }
        $this->load->view('sales/print_BS',$data);
        $this->load->view('template/footer');
    }

    public function add_details_OR()
    {
        $this->load->view('template/header');
        $this->load->view('sales/add_details_OR');
        $this->load->view('template/footer');
    }

    public function add_details_wesm()
    {
        $this->load->view('template/header');
        $this->load->view('sales/add_details_wesm');
        $this->load->view('template/footer');
    }
    
}