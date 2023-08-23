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

    public function export_cs_ledger_old(){
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $referenceno=str_replace("%60","",$this->uri->segment(5));

        //echo $referenceno;
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
            echo "SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON std.sales_id=sth.sales_id WHERE $cs_qu AND short_name = '$head->short_name' GROUP BY transaction_date,reference_number ORDER BY transaction_date ASC<br>";
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
                    $count_camount=$this->super_model->count_custom("SELECT settlement_id FROM collection_details WHERE settlement_id='$details->short_name' AND reference_no IN($referenceno) AND amount!= 0");
                   // echo "SELECT settlement_id FROM collection_details WHERE settlement_id='$details->short_name' AND reference_no IN($referenceno)<br>";
                    //echo $count_camount . " = " . $details->short_name . " - " .   $referenceno . "<br>";
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
                
                // if($count_camount>=2 && $count_camount!=0){
                    //echo $count_camount."-".$comp_name."<br>";
                    for($t=1;$t<=$count_camount;$t++){
                        $p=$t+2;
                        //if($count_camount>=2 && $count_camount!=0){
                        //echo $p."-".$comp_name."<br>";
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
                //}
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
        // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        // if (file_exists($exportfilename))
        // unlink($exportfilename);
        // $objWriter->save($exportfilename);
        // unset($objPHPExcel);
        // unset($objWriter);   
        // ob_end_clean();
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="Customer Sudsidiary Ledger.xlsx"');
        // readfile($exportfilename);
    }

    public function export_cs_ledger(){
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $referenceno=str_replace("%60","",$this->uri->segment(5));

        //echo $referenceno;
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
            $total_vatsales = array();
            $total_zrs = array();
            $total_zre = array();
            $total_vat = array();
            $total_ewt = array(); 

            $total_vatsales_c = array();
            $total_zrs_c = array();
            $total_zre_c = array();
            $total_vat_c = array();
            $total_ewt_c = array(); 
          
            

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

            
            if($details->short_name==$short_name){
                $sales_array_count = $this->get_count_sales_row($head->short_name,$details->reference_number);
                $collection_array_count= $this->get_count_collection_row($head->short_name,$details->reference_number);
    
                
                $max_merge_count = max(array_merge($sales_array_count,$collection_array_count));

                $sales_details = $this->get_sales($head->short_name,$details->reference_number,$max_merge_count);
                $collection_details = $this->get_collection($head->short_name,$details->reference_number,$max_merge_count);
               
               
                //print_r($sales_details);
               // echo $head->short_name. " = " .  . "<br>";
             
                //echo $head->short_name . " = " . $max_merge_count. "<br>";

              

              
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('A'.$num, $details->transaction_date);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('C'.$num, $details->due_date);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('E'.$num, $details->reference_number);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('H'.$num, $comp_name);
                $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('L'.$num, $billing_date);
                $o=$num;
                $p=$num;
                $r=$num;
                $r1=$num;
                $r2=$num;
                $r3=$num;
                $r4=$num;
                $sum_bill_vatsales = array();
                $sum_col_vatsales = array();
                $sum_bill_zrs = array();
                $sum_col_zrs = array();
                $sum_bill_zre = array();
                $sum_col_zre = array();
                $sum_bill_vat = array();
                $sum_col_vat = array();
                $sum_bill_ewt = array();
                $sum_col_ewt = array();
                foreach($sales_details AS $d){
                   
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$o,  $d['vatable_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R'.$o, $d['zero_rated_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U'.$o, $d['zero_rated_ecozones']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X'.$o, $d['vat_on_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA'.$o, $d['ewt']);

                    $total_vatsales[]=$d['vatable_sales'];
                    $total_zre[] = $d['zero_rated_sales'];
                    $total_zrs[] = $d['zero_rated_ecozones'];
                    $total_vat[] = $d['vat_on_sales'];
                    $total_ewt[] = $d['ewt'];

                    $sum_bill_vatsales[] = $d['vatable_sales'];
                    $sum_bill_zrs[] = $d['zero_rated_sales'];
                    $sum_bill_zre[] = $d['zero_rated_ecozones'];
                    $sum_bill_vat[] = $d['vat_on_sales'];
                    $sum_bill_ewt[] = $d['ewt'];

                    


                    $billing_details[] = array(
                        'short_name'=>$head->short_name,
                        'reference_no'=>$details->reference_number,
                        'vatable_sales'=>$d['vatable_sales'],
                        'zero_rated_sales'=>$d['zero_rated_sales'],
                        'zero_rated_ecozones'=>$d['zero_rated_ecozones'],
                        'vat_on_sales'=>$d['vat_on_sales'],
                        'ewt'=>$d['ewt'],
                    );

                     $objPHPExcel->getActiveSheet()->getStyle('A'.$o.":AC".$o)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$o.":B".$o);
                $objPHPExcel->getActiveSheet()->mergeCells('C'.$o.":D".$o);
                $objPHPExcel->getActiveSheet()->mergeCells('E'.$o.":G".$o);
                $objPHPExcel->getActiveSheet()->mergeCells('H'.$o.":K".$o);
                $objPHPExcel->getActiveSheet()->mergeCells('L'.$o.":N".$o);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$o.":B".$o)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$o.":D".$o)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$o.":G".$o)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$o.":N".$o)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$o.":AC".$o)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$o.":AC".$o)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$o.":L".$o)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');

                    $o++;
                }
               
                foreach($collection_details AS $c){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$p, $c['vatable_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S'.$p, $c['zero_rated_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V'.$p, $c['zero_rated_ecozones']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y'.$p, $c['vat_on_sales']);
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB'.$p, $c['ewt']);
                    $total_vatsales_c[]=$c['vatable_sales'];
                    $total_zre_c[] = $c['zero_rated_sales'];
                    $total_zrs_c[] = $c['zero_rated_ecozones'];
                    $total_vat_c[] = $c['vat_on_sales'];
                    $total_ewt_c[] = $c['ewt'];

                    $sum_col_vatsales[]=$c['vatable_sales'];
                    $sum_col_zrs[] = $c['zero_rated_sales'];
                    $sum_col_zre[] = $c['zero_rated_ecozones'];
                    $sum_col_vat[] = $c['vat_on_sales'];
                    $sum_col_ewt[] = $c['ewt'];

                    
                    $collection_details[] = array(
                        'short_name'=>$head->short_name,
                        'reference_no'=>$details->reference_number,
                        'vatable_sales'=>$c['vatable_sales'],
                        'zero_rated_sales'=>$c['zero_rated_sales'],
                        'zero_rated_ecozones'=>$c['zero_rated_ecozones'],
                        'vat_on_sales'=>$c['vat_on_sales'],
                        'ewt'=>$c['ewt'],
                    );

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$p.":AC".$p)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$p.":B".$p);
                $objPHPExcel->getActiveSheet()->mergeCells('C'.$p.":D".$p);
                $objPHPExcel->getActiveSheet()->mergeCells('E'.$p.":G".$p);
                $objPHPExcel->getActiveSheet()->mergeCells('H'.$p.":K".$p);
                $objPHPExcel->getActiveSheet()->mergeCells('L'.$p.":N".$p);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$p.":B".$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$p.":D".$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$p.":G".$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$p.":N".$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$p.":AC".$p)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$p.":AC".$p)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$p.":L".$p)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');

                $p++;
                
                }

                 $sum_vatsales = $this->get_balance($sum_bill_vatsales, $sum_col_vatsales,$max_merge_count);
                 $sum_zrs = $this->get_balance($sum_bill_zrs, $sum_col_zrs,$max_merge_count);
                 $sum_zre = $this->get_balance($sum_bill_zre, $sum_col_zre,$max_merge_count);
                 $sum_vat = $this->get_balance($sum_bill_vat, $sum_col_vat,$max_merge_count);
                 $sum_ewt = $this->get_balance($sum_bill_ewt, $sum_col_ewt,$max_merge_count);

                foreach($sum_vatsales AS $b){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$r, $b['balance']);
                    $r++;
                }
                foreach($sum_zrs AS $b1){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T'.$r1, $b1['balance']);
                    $r1++;
                }
                foreach($sum_zre AS $b2){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W'.$r2, $b2['balance']);
                    $r2++;
                }
                foreach($sum_vat AS $b3){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z'.$r3, $b3['balance']);
                    $r3++;
                }
                foreach($sum_ewt AS $b4){
                    $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC'.$r4, $b4['balance']);
                    $r4++;
                }
                // $w=0;
                // for($q=$num;$q<=$max_merge_count;$q++){
                //     $vat_sales = (empty($sales_details[$w]['vatable_sales']) ? "" : $sales_details[$w]['vatable_sales']);
                //     $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$q,  $vat_sales);
                //     $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$q, "");
                //     $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$q, "");
                //     $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R'.$q, "");
                //     $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S'.$q, "");
                //     $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T'.$q, "");
                //     $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U'.$q, "");
                //     $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V'.$q, "");
                //     $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W'.$q, "");
                //     $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X'.$q, "");
                //     $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y'.$q, "");
                //     $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z'.$q, "");
                //     $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA'.$q, "");
                //     $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB'.$q, "");
                //     $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC'.$q, "");
                //     $w++;
                // }
                    $objPHPExcel->getActiveSheet()->mergeCells('C'.$num.":D".$num);
                    $objPHPExcel->getActiveSheet()->mergeCells('E'.$num.":G".$num);
                    $objPHPExcel->getActiveSheet()->mergeCells('H'.$num.":K".$num);
                    $objPHPExcel->getActiveSheet()->mergeCells('L'.$num.":N".$num);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$num.":D".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$num.":G".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$num.":N".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('E8E0FF');
                
              
                     $num+=$max_merge_count;

                    //echo $head->short_name . " = " . $num . "<br>";
            }
        }

            $balance_vatsales= array_sum($total_vatsales) - array_sum($total_vatsales_c);
            $balance_zre= array_sum($total_zre) - array_sum($total_zre_c);
            $balance_zrs= array_sum($total_zrs) - array_sum($total_zrs_c);
            $balance_vat= array_sum($total_vat) - array_sum($total_vat_c);
            $balance_ewt= array_sum($total_ewt) - array_sum($total_ewt_c);

                $a = $num;
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AC".$a)->getFont()->setBold(true);
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$a)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AC".$a)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFCA5');
                     $objPHPExcel->getActiveSheet()->getStyle('A'.$a.":AC".$a)->applyFromArray($styleArray);
                     $objPHPExcel->getActiveSheet()->mergeCells('A'.$a.":N".$a);
                     $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":AC".$a)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                     $objPHPExcel->getActiveSheet()->getStyle('O'.$a.":AC".$a)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$a, "TOTAL: ");
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('O'.$a, array_sum($total_vatsales));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('P'.$a, array_sum($total_vatsales_c));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Q'.$a, $balance_vatsales);
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('R'.$a, array_sum($total_zrs));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('S'.$a, array_sum($total_zrs_c));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('T'.$a, $balance_zrs);
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('U'.$a, array_sum($total_zre));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('V'.$a, array_sum($total_zre_c));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('W'.$a, $balance_zre);
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('X'.$a, array_sum($total_vat));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Y'.$a, array_sum($total_vat_c));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('Z'.$a, $balance_vat);
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AA'.$a, array_sum($total_ewt));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AB'.$a, array_sum($total_ewt_c));
                        $objPHPExcel->setActiveSheetIndex($sheetno)->setCellValue('AC'.$a, $balance_ewt);
            //$num--;
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


    public function get_count_sales_row($short_name, $reference_no){
        $count_vat=0;
        $count_zrs=0;
        $count_zre=0;
        $count_vos=0;
        $count_ewt=0;
        $count_array_sales=array();
        foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON sth.sales_id=std.sales_id WHERE short_name = '$short_name' AND reference_number='$reference_no'  AND saved!=0") AS $col){
            if($col->vatable_sales!=0){
                $count_vat++;
            }
            if($col->zero_rated_sales!=0){
                $count_zrs++;
            }
            if($col->zero_rated_ecozones!=0){
                $count_zre++;
            }
            if($col->vat_on_sales!=0){
                $count_vos++;
            }
            if($col->ewt!=0){
                $count_ewt++;
            }
          
        }

        $count_array_sales=array(
            "count_vat"=>$count_vat,
            "count_zrs"=>$count_zrs,
            "count_zre"=>$count_zre,
            "count_vos"=>$count_vos,
            "count_ewt"=>$count_ewt,
        );

        return $count_array_sales;
    }

    public function get_count_collection_row($short_name, $reference_no){
        $count_col_vat=0;
        $count_col_zrs=0;
        $count_col_zre=0;
        $count_col_vos=0;
        $count_col_ewt=0;
        $count_array_sales=array();
        foreach($this->super_model->custom_query("SELECT cd.* FROM collection_details cd INNER JOIN collection_head ch ON ch.collection_id=cd.collection_id WHERE settlement_id = '$short_name' AND reference_no='$reference_no' AND saved!=0 AND (amount!=0 OR zero_rated!=0 OR zero_rated_ecozone!=0 OR vat!=0 OR ewt!=0)") AS $col){
            if($col->amount!=0){
                $count_col_vat++;
            }
            if($col->zero_rated!=0){
                $count_col_zrs++;
            }
            if($col->zero_rated_ecozone!=0){
                $count_col_zre++;
            }
            if($col->vat=0){
                $count_col_vos++;
            }
            if($col->ewt!=0){
                $count_col_ewt++;
            }
          
        }

        $count_array_collection=array(
            "count_col_vat"=>$count_col_vat,
            "count_col_zrs"=>$count_col_zrs,
            "count_col_zre"=>$count_col_zre,
            "count_col_vos"=>$count_col_vos,
            "count_col_ewt"=>$count_col_ewt,
        );

        return $count_array_collection;
    }


    public function get_sales($short_name, $reference_no,$max){
       
        $array_sales=array();

       
            foreach($this->super_model->custom_query("SELECT * FROM sales_transaction_details std INNER JOIN sales_transaction_head sth ON sth.sales_id=std.sales_id WHERE short_name = '$short_name' AND reference_number='$reference_no'  AND saved!=0 AND (vatable_sales!=0 OR zero_rated_sales!=0 OR zero_rated_ecozones!=0  OR vat_on_sales!=0 OR ewt!=0)") AS $col){
                $array_sales[] = array(
                    "short_name"=>$short_name,
                    "reference_no"=>$reference_no,
                    "vatable_sales"=>$col->vatable_sales,
                    "zero_rated_sales"=>$col->zero_rated_sales,
                    "zero_rated_ecozones"=>$col->zero_rated_ecozones,
                    "vat_on_sales"=>$col->vat_on_sales,
                    "ewt"=>$col->ewt,
                );
            
            }
            $add = $max - count($array_sales);
            for($x=1;$x<=$add;$x++){
                $array_sales[] = array(
                    "short_name"=>"",
                    "reference_no"=>"",
                    "vatable_sales"=>"",
                    "zero_rated_sales"=>"",
                    "zero_rated_ecozones"=>"",
                    "vat_on_sales"=>"",
                    "ewt"=>"",
                );
            }
        

        return $array_sales;
    }

    public function get_collection($short_name, $reference_no,$max){
       
        $array_collection=array();

       
            foreach($this->super_model->custom_query("SELECT cd.* FROM collection_details cd INNER JOIN collection_head ch ON ch.collection_id=cd.collection_id WHERE settlement_id = '$short_name' AND reference_no='$reference_no' AND saved!=0 AND (amount != 0)") AS $col){
                $array_collection[] = array(
                    "short_name"=>$short_name,
                    "reference_no"=>$reference_no,
                    "vatable_sales"=>$col->amount,
                    "zero_rated_sales"=>$col->zero_rated,
                    "zero_rated_ecozones"=>$col->zero_rated_ecozone,
                    "vat_on_sales"=>$col->vat,
                    "ewt"=>$col->ewt,
                );
            
            }
            $add = $max - count($array_collection);
            for($x=1;$x<=$add;$x++){
                $array_collection[] = array(
                    "short_name"=>"",
                    "reference_no"=>"",
                    "vatable_sales"=>"",
                    "zero_rated_sales"=>"",
                    "zero_rated_ecozones"=>"",
                    "vat_on_sales"=>"",
                    "ewt"=>"",
                );
            }
        

        return $array_collection;
    }

    public function get_balance($total_bill, $total_collect,$max){

        $balance=  array_sum($total_bill) -  array_sum($total_collect);
        $array_balance[] = array(
            "balance"=>$balance,
        );

        $add = $max - 1;
        for($x=1;$x<=$add;$x++){
            $array_balance[] = array(
                "balance"=>"",
            );
        }

        return $array_balance;
    }

}