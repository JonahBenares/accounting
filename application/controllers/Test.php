<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

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

    public function sales_sum($short_name,$reference_no,$type){
        $sum=$this->super_model->select_sum_join("$type","sales_transaction_details","sales_transaction_head","short_name = '$short_name' AND reference_number='$reference_no' AND saved!=0",'sales_id');
        return $sum;
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

    public function export_cs_ledger(){
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $referenceno=str_replace("%60","",$this->uri->segment(5));
        $participant=$this->uri->segment(6);
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
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
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
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
            $bal_amountarr = array();
            $bal_camountarr = array();
            $balance_vatsalarr = array();
            $bal_zeroratedarr = array();
            $bal_czerorated_amountarr = array(); 
            $balance_zeroratedarr = array();
            $bal_zeroratedecoarr = array();
            $bal_czeroratedeco_amountarr = array();
            $balance_zeroratedecoarr = array();
            $bal_vatonsalesarr = array();
            $bal_cvatonsal_amountarr = array(); 
            $balance_vatonsalesarr = array();
            $bal_ewtarr = array();
            $bal_cewt_amountarr = array();
            $balance_ewtarr = array();
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON std.sales_id=sth.sales_id WHERE $cs_qu AND short_name = '$head->short_name' GROUP BY transaction_date,reference_number ORDER BY transaction_date ASC") AS $details){
            $or_no=$this->super_model->select_column_custom_where("collection_details","series_number","reference_no='$details->reference_number' AND settlement_id='$details->short_name'");
            $participant_name=$this->super_model->select_column_where("participant","participant_name","billing_id",$details->billing_id);
            if(!empty($details->company_name) && date('Y',strtotime($details->create_date))==date('Y')){
                $comp_name=$details->company_name;
            }else{
                $comp_name=$participant_name;
            }
            $billing_date = date("M. d, Y",strtotime($details->billing_from))." - ".date("M. d, Y",strtotime($details->billing_to));
            //$tin=$this->super_model->select_column_where("participant","tin","billing_id",$details->billing_id);
            $short_name=$this->super_model->select_column_where("sales_transaction_details","short_name","sales_detail_id",$details->sales_detail_id);

            $amount=$this->sales_display_export($details->short_name,$details->reference_number,'vatable_sales');
            $zerorated=$this->sales_display_export($details->short_name,$details->reference_number,'zero_rated_sales');
            $zeroratedeco=$this->sales_display_export($details->short_name,$details->reference_number,'zero_rated_ecozones');
            $vatonsales=$this->sales_display_export($details->short_name,$details->reference_number,'vat_on_sales');
            $ewt=$this->sales_display_export($details->short_name,$details->reference_number,'ewt');
            $exp=explode('-',$amount);
            // $count_amount=$this->super_model->count_custom("SELECT vatable_sales FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON sth.sales_id=std.sales_id WHERE short_name = '$details->short_name' AND reference_number='$details->reference_number' AND vatable_sales!=0 AND saved!=0");
            //$id=array();

            //Sales Balance
            $bal_amount=$this->sales_sum($details->short_name,$details->reference_number,'vatable_sales');
            $bal_amountarr[]=$this->sales_sum($details->short_name,$details->reference_number,'vatable_sales');
            $bal_zerorated=$this->sales_sum($details->short_name,$details->reference_number,'zero_rated_sales');
            $bal_zeroratedarr[]=$this->sales_sum($details->short_name,$details->reference_number,'zero_rated_sales');
            $bal_zeroratedeco=$this->sales_sum($details->short_name,$details->reference_number,'zero_rated_ecozones');
            $bal_zeroratedecoarr[]=$this->sales_sum($details->short_name,$details->reference_number,'zero_rated_ecozones');
            $bal_vatonsales=$this->sales_sum($details->short_name,$details->reference_number,'vat_on_sales');
            $bal_vatonsalesarr[]=$this->sales_sum($details->short_name,$details->reference_number,'vat_on_sales');
            $bal_ewt=$this->sales_sum($details->short_name,$details->reference_number,'ewt');
            $bal_ewtarr[]=$this->sales_sum($details->short_name,$details->reference_number,'ewt');

                    $camount='';
                    $czerorated='';
                    $czeroratedeco='';
                    $cvat='';
                    $cewt='';
                    //$count_camount=array();
                    foreach($this->super_model->select_custom_where("collection_details","reference_no='$details->reference_number' AND settlement_id ='$details->short_name'") AS $c){
                        $camount.=$this->collection_display_export($c->collection_details_id,$c->settlement_id,$c->reference_no,'amount');
                        $czerorated.=$this->collection_display_export($c->collection_details_id,$c->settlement_id,$c->reference_no,'zero_rated');
                        $czeroratedeco.=$this->collection_display_export($c->collection_details_id,$c->settlement_id,$c->reference_no,'zero_rated_ecozone');
                        $cvat.=$this->collection_display_export($c->collection_details_id,$c->settlement_id,$c->reference_no,'vat');
                        $cewt.=$this->collection_display_export($c->collection_details_id,$c->settlement_id,$c->reference_no,'ewt'); 
                        // $count_camount=$this->super_model->counter_single('settlement_id', 'collection_details', "settlement_id='$details->short_name' AND reference_no='$details->reference_number' AND amount!='0'");
                        
                    }
                    //$count_camount=$this->super_model->select_custom_sum_where('collection_details','settlement_id',$details->short_name,"settlement_id='$details->short_name' AND reference_no IN($referenceno) AND amount!='0'");
                    $count_camount=$this->super_model->count_custom("SELECT settlement_id FROM collection_details WHERE settlement_id='$details->short_name' AND reference_no IN($referenceno)");
                    $cvatsal_amount=$camount." Total: ".number_format($this->collection_sum($details->short_name,$details->reference_number,'amount'),2);
                    $czerorated_amount=$czerorated." Total: ".number_format($this->collection_sum($details->short_name,$details->reference_number,'zero_rated'),2);
                    $czeroratedeco_amount=$czeroratedeco." Total: ".number_format($this->collection_sum($details->short_name,$details->reference_number,'zero_rated_ecozone'),2);
                    $cvatonsal_amount=$cvat." Total: ".number_format($this->collection_sum($details->short_name,$details->reference_number,'vat'),2);
                    $cewt_amount=$cewt." Total: ".number_format($this->collection_sum($details->short_name,$details->reference_number,'ewt'),2);

                    //Collection Balance
                    $bal_camount=$this->collection_sum($details->short_name,$details->reference_number,'amount');
                    $bal_camountarr[]=$this->collection_sum($details->short_name,$details->reference_number,'amount');
                    $bal_czerorated_amount=$this->collection_sum($details->short_name,$details->reference_number,'zero_rated');
                    $bal_czerorated_amountarr[]=$this->collection_sum($details->short_name,$details->reference_number,'zero_rated');
                    $bal_czeroratedeco_amount=$this->collection_sum($details->short_name,$details->reference_number,'zero_rated_ecozone');
                    $bal_czeroratedeco_amountarr[]=$this->collection_sum($details->short_name,$details->reference_number,'zero_rated_ecozone');
                    $bal_cvatonsal_amount=$this->collection_sum($details->short_name,$details->reference_number,'vat');
                    $bal_cvatonsal_amountarr[]=$this->collection_sum($details->short_name,$details->reference_number,'vat');
                    $bal_cewt_amount=$this->collection_sum($details->short_name,$details->reference_number,'ewt');
                    $bal_cewt_amountarr[]=$this->collection_sum($details->short_name,$details->reference_number,'ewt');
                    
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

                    //echo $comp_name."-".$amount."AND".$cvatsal_amount."<br>";

            //if($details->tin==$tin){
            if($details->short_name==$short_name){
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $details->transaction_date);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $details->due_date);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, $details->reference_number);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, $comp_name);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, $billing_date);
                //echo $exp[1]."<br>";
                //if($exp[1]>=2){
                    //echo $exp[1]."-".$comp_name."**<br>";
                if($exp[1]>=2){    
                    for($g=1;$g<=$exp[1];$g++){
                        $o=$g+2;
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$o, $amount);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":AC".$num)->applyFromArray($styleArray);
                        $objPHPExcel->getActiveSheet()->mergeCells('A'.$num.":B".$num);
                        $objPHPExcel->getActiveSheet()->mergeCells('C'.$num.":D".$num);
                        $objPHPExcel->getActiveSheet()->mergeCells('E'.$num.":G".$num);
                        $objPHPExcel->getActiveSheet()->mergeCells('H'.$num.":K".$num);
                        $objPHPExcel->getActiveSheet()->mergeCells('L'.$num.":N".$num);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('C'.$num.":D".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":G".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('L'.$num.":N".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":AC".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":AC".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                        $num++;
                    }
                }
                
                if($count_camount>=2 && $count_camount!=0){
                    //echo $count_camount."-".$comp_name."<br>";
                    for($t=1;$t<=$count_camount;$t++){
                        $p=$t+2;
                        //if($count_camount>=2 && $count_camount!=0){
                        echo $p."-".$comp_name."<br>";
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$p, '1000');
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":AC".$num)->applyFromArray($styleArray);
                        $objPHPExcel->getActiveSheet()->mergeCells('A'.$num.":B".$num);
                        $objPHPExcel->getActiveSheet()->mergeCells('C'.$num.":D".$num);
                        $objPHPExcel->getActiveSheet()->mergeCells('E'.$num.":G".$num);
                        $objPHPExcel->getActiveSheet()->mergeCells('H'.$num.":K".$num);
                        $objPHPExcel->getActiveSheet()->mergeCells('L'.$num.":N".$num);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('C'.$num.":D".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":G".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('L'.$num.":N".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":AC".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":AC".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                        $num++;
                    }
                }
                //$num2++;
                // else{
                //     // for($a=$num;$a<=$exp[1];$a++){
                //     //     echo $a."-".$comp_name."**<br>";
                //     // }
                //     echo $num."-".$comp_name."<br>";
                // }
                //$objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$num, $amount);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$num, $cvatsal_amount);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$num, $balance_vatsal);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R'.$num, $zerorated);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S'.$num, $czerorated_amount);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T'.$num, $balance_zerorated);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U'.$num, $zeroratedeco);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V'.$num, $czeroratedeco_amount);
                // $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W'.$num, $balance_zeroratedeco);
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
                // //  $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getAlignment()->setWrapText(true);
                // //  $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
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
                //  $objPHPExcel->getActiveSheet()->getStyle('X'.$num)->getAlignment()->setWrapText(true);
                //  $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('Y'.$num)->getAlignment()->setWrapText(true);
                //  $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('Z'.$num)->getAlignment()->setWrapText(true);
                //  $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('AA'.$num)->getAlignment()->setWrapText(true);
                //  $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('AB'.$num)->getAlignment()->setWrapText(true);
                //  $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('AC'.$num)->getAlignment()->setWrapText(true);
                //  $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('A1:AC2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //  $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //  $objPHPExcel->getActiveSheet()->getStyle('C'.$num.":D".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //  $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":G".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //  $objPHPExcel->getActiveSheet()->getStyle('L'.$num.":N".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //  $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":AC".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                //  $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":AC".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                //  $objPHPExcel->getActiveSheet()->getStyle('A1:AC2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('F1F1F1');
                //  $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                //  $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":AC".$num)->applyFromArray($styleArray);
                //  $objPHPExcel->getActiveSheet()->getStyle('A1:AC1')->getFont()->setBold(true);
                //  $objPHPExcel->getActiveSheet()->getStyle('A2:AC2')->getFont()->setBold(true);
                // $num++;
            }
        }

                $a = $num;
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AC".$a)->getFont()->setBold(true);
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$a)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AC".$a)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFCA5');
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AC".$a)->applyFromArray($styleArray);
                     $objPHPExcel->getActiveSheet()->mergeCells('A'.$a.":N".$a);
                     $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":AC".$a)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":AC".$a)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$a, "TOTAL: ");
                        //$objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$a, array_sum($bal_amountarr));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$a, array_sum($bal_camountarr));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$a, array_sum($balance_vatsalarr));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R'.$a, array_sum($bal_zeroratedarr));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S'.$a, array_sum($bal_czerorated_amountarr));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T'.$a, array_sum($balance_zeroratedarr));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U'.$a, array_sum($bal_zeroratedecoarr));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V'.$a, array_sum($bal_czeroratedeco_amountarr));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W'.$a, array_sum($balance_zeroratedecoarr));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X'.$a, array_sum($bal_vatonsalesarr));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y'.$a, array_sum($bal_cvatonsal_amountarr));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z'.$a, array_sum($balance_vatonsalesarr));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA'.$a, array_sum($bal_ewtarr));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB'.$a, array_sum($bal_cewt_amountarr));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC'.$a, array_sum($balance_ewtarr));
                $num--;
            $sheetno++;
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
        header('Content-Disposition: attachment; filename="Customer Sudsidiary Ledger.xlsx"');
        readfile($exportfilename);
    }

}