//var clicksBS = 0;
function add_details_BS(baseurl,sales_details_id) {
	/*var redirect = baseurl+"sales/count_print";
	$.ajax({
		data: "sales_details_id="+sales_details_id,
		type: "POST",
		url: redirect,
		success: function(output){*/
			window.open(baseurl+"sales/add_details_BS/"+sales_details_id, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
		/*}
	});*/
    /*clicksBS += 1;
	document.getElementById("clicksBS").innerHTML = '('+clicksBS+')';*/
}

function add_adjust_details_BS(baseurl,adjustment_detail_id) {
	window.open(baseurl+"sales/add_adjust_details_BS/"+adjustment_detail_id, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
}

function countPrint(baseurl,sales_details_id){
	var redirect = baseurl+"sales/count_print";
	$.ajax({
		data: "sales_details_id="+sales_details_id,
		type: "POST",
		url: redirect,
		success: function(output){

		}
	});
}

function collection_process(){
	var date_collected = document.getElementById("date_collected").value;
  	var series_number = document.getElementById("series_number").value;
  	var amount = document.getElementById("amount").value;
  	if(date_collected==""){
      	alert('Date Collected must not be empty!');
  	}  else if (series_number==""){
      	alert('Series Number must not be empty!');
  	}  else if (amount==""){
      	alert('Amount must not be empty!');
  	}  else {
	var data = $("#collectiondetails").serialize();
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/save_collection";

    var conf = confirm('Are you sure you want to process collection?');
    console.log(data);
    if(conf){
		$.ajax({
			data: data,
			type: "POST",
			url: redirect,
			success: function(output){
				//alert(output);
			    window.opener.location=loc+'sales/print_OR/'+output;
			    window.close();
			}
		});
    }
	}
}

  function isNumberKey(evt)
   {
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
         return false;

      return true;
   }

function add_details_wesm(baseurl) {
    window.open(baseurl+"sales/add_details_wesm/", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
}
var clicksOR = 0;
function add_details_OR(baseurl, sales_id, sales_details_id){
    window.open(baseurl+"sales/add_details_OR/"+sales_id+"/"+sales_details_id, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
    clicksOR += 1;
	document.getElementById("clicksOR").innerHTML = '('+clicksOR+')';
}
function proceed_btn() {
	 var data = $("#saleshead").serialize();
	
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/add_sales_head";
    var newurl=loc+"sales/upload_sales/";
    var conf = confirm('Are you sure you want to proceed?');
    if(conf){
	  $.ajax({
        type: "POST",
        url: redirect,
        data: data,
        success: function(output){
        	//alert(output);
        	var new_url = newurl+output;
        	document.getElementById("sales_id").value  = output;
        	window.history.replaceState('nextState', "Sales Head", new_url);
 			var save = document.getElementById("save_head_button");
            var cancel = document.getElementById("cancel");
        	document.getElementById('transaction_date').readOnly = true;
        	document.getElementById('reference_number').readOnly = true;
        	document.getElementById('billing_from').readOnly = true;
        	document.getElementById('due_date').readOnly = true;
        	document.getElementById('billing_to').readOnly = true;
        	var x = document.getElementById("upload");
				if (x.style.display === "none") {
					x.style.display = "block";

					save.style.display = "none";
                    cancel.style.display = "block";
			} else {
				x.style.display = "none";
				save.style.display = "block";
                    cancel.style.display = "none";
			}
          
        	}
        });
	}
	
}

async function upload_btn() {
	//var sales_doc = document.getElementById("WESM_sales").value;
	var sales_id = document.getElementById("sales_id").value;
	var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/upload_sales_process";
    var redirect1 = loc+"sales/load_sales_data";
	let doc = document.getElementById("WESM_sales").files[0];
	let formData = new FormData();
	     
	formData.append("doc", doc);
	formData.append("sales_id", sales_id);
	var conf = confirm('Are you sure you want to upload this file?');
    if(conf){
		$.ajax({
			type: "POST",
			url: redirect,
			data: formData,
			processData: false,
			contentType: false,
			beforeSend: function(){
	        	document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
	        	document.getElementById("proceed_sales").disabled = true;
	        	document.getElementById("cancel").disabled = true;
	        	$("#table-wesm").hide(); 
	        },
	        success: function(output){
	        	/*console.log(output);*/
	        	$("#alt").hide(); 
	        	location.reload();
			}
		});
	}
}

function cancelSales(){
    var sales_id = document.getElementById("sales_id").value; 
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/cancel_sales";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
		$.ajax({
			data: "sales_id="+sales_id,
			type: "POST",
			url: redirect,
			success: function(response){
			    window.location=loc+'sales/upload_sales/';
			}
		});
    }
}

function saveAll(){
	var data = $("#upload_wesm").serialize();
	var loc= document.getElementById("baseurl").value;
	var count_name = document.getElementById("count_name").value;
  	if(count_name != 0){
      	alert('Some of the Company Name are empty!');
  	}  else {
    var redirect = loc+"sales/save_all";
    var conf = confirm('Are you sure you want to save this Sales?');
		if(conf){
		    $.ajax({
		        data: data,
		        type: "POST",
		        url: redirect,
		        beforeSend: function(){
		        	document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
		            $("#submitdata").hide(); 
		        },
		        success: function(output){
		        	window.location=loc+'sales/upload_sales/'+output;  
		        }
		    }); 
    	}
    }	 
}

function collection_filter() {
	var ref_number = document.getElementById("ref_number").value; 
	//var participant = document.getElementById("participant").value; 
	var loc= document.getElementById("baseurl").value;
	if(ref_number!=''){
		var ref=ref_number;
	}else{
		var ref='null';
	}
	/*
	if(participant!=''){
		var par=participant;
	}else{
		var par='null';
	}*/


	window.location=loc+'sales/collection_list/'+ref;

}

function collected_filter() {
	var ref_number = document.getElementById("ref_number").value;
	var participant = document.getElementById("participant").value;  
	var loc= document.getElementById("baseurl").value;
	if(ref_number!=''){
		var ref=ref_number;
	}else{
		var ref='null';
	}

	if(participant!=''){
		var par=participant;
	}else{
		var par='null';
	}
	window.location=loc+'sales/collected_list/'+ref+'/'+par;

}

function saveBS(){
    var sales_detail_id = document.getElementById("sales_detail_id").value; 
    var serial_no = document.getElementById("serial_no").value; 
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/save_serialno";
    var conf = confirm('Are you sure you want to save this serial number?');
    if(conf){
		$.ajax({
			data: "sales_detail_id="+sales_detail_id+"&serial_no="+serial_no,
			type: "POST",
			url: redirect,
			success: function(output){
				if(serial_no!=''){
					countPrint(loc,sales_detail_id);
					/*window.opener.location.reload();
					opener.open(loc+'sales/print_BS/'+output, '_blank');*/
				    //window.opener.location=loc+'sales/print_BS/'+output;
				    window.open(loc+'sales/print_BS/'+output,'_blank');
				    window.close();
				}else{
					alert("Please encode serial number!");
				}
			}
		});
    }
}

function filterUploadSales(){
	var sales_id= document.getElementById("sales_id").value;
	var in_ex_sub= document.getElementById("in_ex_sub").value;
	var loc= document.getElementById("baseurl").value;
    if(in_ex_sub!=''){
        var sub=in_ex_sub;
    }else{
        var sub='null';
    }
	window.location=loc+'sales/upload_sales/'+sales_id+'/'+sub;          
}

function filterSales(){
	var ref_no= document.getElementById("ref_no").value;
	var due_date= document.getElementById("due_date").value;
	var in_ex_sub= document.getElementById("in_ex_sub").value;
	var loc= document.getElementById("baseurl").value;
	if(ref_no!=''){
        var ref=ref_no;
    }else{
        var ref='null';
    }

    if(due_date!=''){
        var due=due_date;
    }else{
        var due='null';
    }

    if(in_ex_sub!=''){
        var sub=in_ex_sub;
    }else{
        var sub='null';
    }
	window.location=loc+'sales/sales_wesm/'+ref+'/'+due+'/'+sub;          
}

function filterSalesAdjustment(){
	var ref_no= document.getElementById("ref_no").value;
	var due_date= document.getElementById("due_date").value;
	var in_ex_sub= document.getElementById("in_ex_sub").value;
	var loc= document.getElementById("baseurl").value;
	if(ref_no!=''){
        var ref=ref_no;
    }else{
        var ref='null';
    }

    if(due_date!=''){
        var due=due_date;
    }else{
        var due='null';
    }

    if(in_ex_sub!=''){
        var sub=in_ex_sub;
    }else{
        var sub='null';
    }
	window.location=loc+'sales/sales_wesm_adjustment/'+ref+'/'+due+'/'+sub;          
}


$(document).on("click", "#seriesupdate", function () {
	 var collection_id = $(this).attr("data-id");
	 var series_number = $(this).attr("data-name");
	 var settlement_id = $(this).attr("data-settlement");
	 var reference_number = $(this).attr("data-reference");
	 $("#collection_id").val(collection_id);
	 $("#series_number").val(series_number);
	 $("#old_series_no").val(series_number);
	 $("#settlement_id").val(settlement_id);
	 $("#ref_no").val(reference_number);

});

function saveSeries(){
	var data = $("#update").serialize();
	var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/update_seriesno";
    $.ajax({
        data: data,
        type: "POST",
        url: redirect,
        success: function(output){
        	window.location=loc+'sales/collection_list/'+output;  
        }
    });  
}

$(document).on("click", "#BSupdate", function () {
	 var sales_detail_id = $(this).attr("data-id");
	 var series_number = $(this).attr("data-series");
	 $("#sales_detail_id").val(sales_detail_id);
	 $("#series_number").val(series_number);
	 $("#old_series_no").val(series_number);

});

$(document).on("click", "#ORNo", function () {
	 var series_no = $(this).attr("data-series-col");
	 var old_series_no_col = $(this).attr("data-old-series-col");
	 document.getElementById('series_no').innerHTML=series_no;
	 document.getElementById('old_series_no_disp').innerHTML=old_series_no_col.replace(/(,)/g, '<hr style="margin:3px 0px">');

});

$(document).on("click", "#BSNo", function () {
	 var bs_no = $(this).attr("data-bs");
	 var old_bs_no = $(this).attr("data-old-bs");
	 document.getElementById('bs_no').innerHTML=bs_no;
	 document.getElementById('old_bs_no_disp').innerHTML=old_bs_no.replace(/(,)/g, '<hr style="margin:3px 0px">');

});

function saveBseries(){
	var data = $("#update").serialize();
	var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/update_BSeriesno";
    $.ajax({
        data: data,
        type: "POST",
        url: redirect,
        success: function(output){
        	window.location=loc+'sales/sales_wesm/'+output;  
        }
    });  
}




function uploadCollection(){
	var loc= document.getElementById("baseurl").value;
	var col_date= document.getElementById("collection_date").value;
    var redirect = loc+"sales/upload_bulk_collection";
	let doc = document.getElementById("collectionbulk").files[0];
	let formData = new FormData();
	     
	formData.append("doc", doc);
	formData.append("col_date", col_date);

	var conf = confirm('Are you sure you want to upload this file?');
    if(conf){
		$.ajax({
			type: "POST",
			url: redirect,
			data: formData,
			processData: false,
			contentType: false,
			beforeSend: function(){
	        	document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
	        	document.getElementById("upload").disabled = true;
	        	/*document.getElementById("proceed_sales").disabled = true;
	        	document.getElementById("cancel").disabled = true;
	        	$("#table-wesm").hide(); */
	        },
	        success: function(output){
	        	console.log(output);
	        	$("#alt").hide(); 
	        	window.location=loc+'sales/collection_list/'+output;  
	        	//location.reload();
			}
		});
	}
}

function calculateSales(){
    var amount = document.getElementById("amount").value;
    var vat = document.getElementById("vat").value;
    var ewt = document.getElementById("ewt").value;
    var zero_rated = document.getElementById("zero_rated").value;
    var zero_rated_ecozone = document.getElementById("zero_rated_ecozone").value;
    if(amount==0){
    	var amnt=0;
    }else{
    	var amnt=amount;
    }

    if(vat==0){
    	var vt=0;
    }else{
    	var vt=vat;
    }

    if(zero_rated==0){
    	var zrt=0;
    }else{
    	var zrt=zero_rated;
    }

    if(zero_rated_ecozone==0){
    	var zre=0;
    }else{
    	var zre=zero_rated_ecozone;
    }

    if(ewt==0){
    	var et=0;
    }else{
    	var et=ewt;
    }
    var total = (parseFloat(amnt)+parseFloat(vt)+parseFloat(zrt)+parseFloat(zre))-parseFloat(et);
    document.getElementById("total_amount").value  = parseFloat(total).toFixed(2);

}


function updateSeries(baseurl,count,collection_id,settlement_id,reference_number){
    var redirect = baseurl+"sales/update_seriesno";
    var series_number=document.getElementById("series_number"+count).value;
    var old_series=document.getElementById("old_series_no"+count).value;
    document.getElementById("old_series_no"+count).setAttribute('value','');
    //alert(settlement_id);
	$.ajax({
		type: "POST",
		url: redirect,
		data: 'series_number='+series_number+'&collection_id='+collection_id+'&settlement_id='+settlement_id+'&reference_number='+reference_number+'&old_series='+old_series,
		success: function(output){
			//alert(output);
			document.getElementById("series_number"+count).setAttribute('value',output);
			document.getElementById("old_series_no"+count).value=series_number;
						//document.getElementById("series_number"+count).value=output;
			//document.getElementById("old_series_no"+count).value=output;
		}
	});
	

}

async function upload_sales_adjust_btn() {
    var count_file = document.getElementById("count").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/upload_sales_adjust";
    var conf = confirm('Are you sure you want to upload this file?');
    if(conf){
        var form = document.querySelector('#uploadsalesadjust');
        var formData = new FormData(form);
        for (var i=1;i<=count_file;i++) { 
            fileupload = document.querySelector('input[name="fileupload[]"]').files[0];
            adjust_identifier = document.getElementById("adjust_identifier").value;
            count = document.getElementById("count").value;
            //remarks= document.querySelector('input[name="remarks[]"]').value;
            formData.append('fileupload'+[i], fileupload);
            //formData.append('remarks'+[i], remarks);
            formData.append('count', count);
            formData.append('adjust_identifier', adjust_identifier);
        }
        $.ajax({
            type: "POST",
            url: redirect,
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function(){
                document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
                document.getElementById("button_adjust").disabled = true;
            },
            success: function(output){
            	/*alert(output);*/
               $("#alt").hide();
                window.location=loc+'sales/upload_sales_adjustment/'+output;
            }
        });
        
    }
}

function saveAllAdjust(){
    var loc= document.getElementById("baseurl").value;
    var saveadjust_identifier= document.getElementById("save_sales_adjustment").value;
    var count_name = document.getElementById("count_name").value;
  	if(count_name != 0){
      	alert('Some of the Company Name are empty!');
  	}  else {
    var redirect = loc+"sales/save_all_adjust";
    var conf = confirm('Are you sure you want to save this Sales?');
    if(conf){
        $.ajax({
            data: 'adjust_identifier='+saveadjust_identifier,
            type: "POST",
            url: redirect,
            beforeSend: function(){
                document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
                $("#submitdata").hide(); 
            },
            success: function(output){
                $("#alt").hide();
                window.location=loc+'sales/upload_sales_adjustment/'+output;  
            }
        }); 
    	} 
    }   
}

function printMultiple(){
	var x = document.getElementsByClassName("multiple_print");
	var loc= document.getElementById("baseurl").value;
 	var redirect = loc+"sales/print_multiple";
 	var form = document.querySelector('#print_mult');
    var formData = new FormData(form);
	for(var i =0;i<x.length;i++){
		if(document.getElementsByClassName('multiple_print')[i].checked){
			multiple_print= document.querySelector('input[name="multiple_print[]"]').value;
			formData.append('multiple_print'+[i], multiple_print);
			//formData.append('count', i);
		}
    }
    $.ajax({
        type: "POST",
        url: redirect,
        data: formData,
        processData: false,
        contentType: false,
        success: function(output){
            //alert(output);
            /*$("#alt").hide(); */
            var exp=output.split(",");
            //window.open(loc+'sales/print_BS_multiple/'+exp[0]+'/'+exp[1]+'/'+exp[2],"_blank");
            window.open(loc+'sales/print_BS_new/'+exp[0]+'/'+exp[1]+'/'+exp[2],"_blank");
           	//window.location=loc+'sales/print_BS_multiple/'+exp[0]+'/'+exp[1]+'/'+exp[2];
        }
    });
}

function cancelmultipleSales(){
    var save_sales_adjustment = document.getElementById("save_sales_adjustment").value; 
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/cancel_multiple_sales";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
		$.ajax({
			data: "save_sales_adjustment="+save_sales_adjustment,
			type: "POST",
			url: redirect,
			success: function(response){
				document.getElementById("selectfile").disabled = false;
			    window.location=loc+'sales/upload_sales_adjustment/';
			}
		});
    }
}

$(document).ready(function() {
	$("#ddArea").on("dragover", function() {
		$(this).addClass("drag_over");
		return false;
	});

	$("#ddArea").on("dragleave", function() {
		$(this).removeClass("drag_over");
		return false;
	});

	$("#ddArea").on("click", function(e) {
		file_explorer();
	});

	$("#ddArea").on("drop", function(e) {
		e.preventDefault();
		$(this).removeClass("drag_over");
		var formData = new FormData();
		var files = e.originalEvent.dataTransfer.files;
		adjust_identifier = document.getElementById("adjust_identifier").value;
		for (var i = 0; i < files.length; i++) {
			formData.append("file[]", files[i]);
			formData.append('adjust_identifier', adjust_identifier);
		}
		uploadmultipleFiles(formData); 
	});

	function file_explorer() {
		document.getElementById("selectfile").click();
		document.getElementById("selectfile").onchange = function() {
			files = document.getElementById("selectfile").files;
			adjust_identifier = document.getElementById("adjust_identifier").value;
			var formData = new FormData();
			for (var i = 0; i < files.length; i++) {
				formData.append("file[]", files[i]);
				formData.append('adjust_identifier', adjust_identifier);
			}
			uploadmultipleFiles(formData); 
		};
	}

	function uploadmultipleFiles(form_data) {
		$(".loading").removeClass("d-none").addClass("d-block");
		var loc= document.getElementById("baseurl").value;
		var identifier= document.getElementById("adjust_identifier").value;
		var redirect = loc+"sales/display_upload_adjust";
		$.ajax({
			url: redirect,
			method: "POST",
			data: form_data,
			contentType: false,
			cache: false,
			processData: false,
			beforeSend: function(){
				document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
				document.getElementById("selectfile").disabled = true;
			},
			success: function(output) {
				$(".loading").removeClass("d-block").addClass("d-none");
				//$("#showThumb").append(data);
				$("#alt").hide();
				window.location=loc+'sales/upload_sales_adjustment/'+output;
			}
		});
	}
});

function updateSales(baseurl,count,sales_detail_id,sales_id,billing_id){
    var redirect = baseurl+"sales/update_details";
    var ewt_amount=document.getElementById("ewt_amount"+count).value;
    var orig_yes=document.getElementById("orig_yes"+count);
    if(orig_yes.checked){
        var original_copy=1;
    }
    var orig_no=document.getElementById("orig_no"+count);
    if(orig_no.checked){
        var original_copy=0;
    }
    var scanned_yes=document.getElementById("scanned_yes"+count);
    if(scanned_yes.checked){
        var scanned_copy=1;
    }
    var scanned_no=document.getElementById("scanned_no"+count);
    if(scanned_no.checked){
        var scanned_copy=0;
    }
	$.ajax({
		type: "POST",
		url: redirect,
		data: 'sales_detail_id='+sales_detail_id+'&sales_id='+sales_id+'&billing_id='+billing_id+'&ewt_amount='+ewt_amount+'&original_copy='+original_copy+'&scanned_copy='+scanned_copy,
        dataType: "json",
		success: function(response){
			document.getElementById("ewt_amount"+count).value=response.ewt_amount;
		}
	});
}

function updateSalesAdjustment(baseurl,count,sales_detail_id,sales_adjustment_id,billing_id){
    var redirect = baseurl+"sales/update_adjustment_details";
    var ewt_amount=document.getElementById("ewt_amount"+count).value;
    var orig_yes=document.getElementById("orig_yes"+count);
    if(orig_yes.checked){
        var original_copy=1;
    }
    var orig_no=document.getElementById("orig_no"+count);
    if(orig_no.checked){
        var original_copy=0;
    }
    var scanned_yes=document.getElementById("scanned_yes"+count);
    if(scanned_yes.checked){
        var scanned_copy=1;
    }
    var scanned_no=document.getElementById("scanned_no"+count);
    if(scanned_no.checked){
        var scanned_copy=0;
    }
	$.ajax({
		type: "POST",
		url: redirect,
		data: 'sales_detail_id='+sales_detail_id+'&sales_adjustment_id='+sales_adjustment_id+'&billing_id='+billing_id+'&ewt_amount='+ewt_amount+'&original_copy='+original_copy+'&scanned_copy='+scanned_copy,
        dataType: "json",
		success: function(response){
			document.getElementById("ewt_amount"+count).value=response.ewt_amount;
		}
	});
}

function printbs_history(){
	var data = $("#InsertBS").serialize();
	var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/insert_printbs";
	    $.ajax({
	        data: data,
	        type: "POST",
	        url: redirect,
	        success: function(output){
	        	
	         window.print();  
	         //alert(output);
	         //console.log(output);
	         //print_r(output);
	        }
	    }); 
	     //window.print();
}
