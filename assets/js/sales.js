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

function proceed_res_btn() {
	 var data = $("#reservesaleshead").serialize();
	
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/add_reserve_sales_head";
    var newurl=loc+"sales/upload_reserve_sales/";
    var conf = confirm('Are you sure you want to proceed?');
    if(conf){
	  $.ajax({
        type: "POST",
        url: redirect,
        data: data,
        success: function(output){
        	//alert(output);
        	var new_url = newurl+output;
        	document.getElementById("reserve_sales_id").value  = output;
        	window.history.replaceState('nextState', "Sales Head", new_url);
 			var save = document.getElementById("save_head_button");
            var cancel = document.getElementById("cancel");
        	document.getElementById('res_transaction_date').readOnly = true;
        	document.getElementById('res_reference_number').readOnly = true;
        	document.getElementById('res_billing_from').readOnly = true;
        	document.getElementById('res_due_date').readOnly = true;
        	document.getElementById('res_billing_to').readOnly = true;
        	var x = document.getElementById("upload_res");
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
	        	// document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
                document.getElementById("alt").style.display = 'block';  
                document.getElementById("alert_error").style.display = 'none';  
	        	document.getElementById("proceed_sales").disabled = true;
	        	document.getElementById("cancel").disabled = true;
	        	$("#table-wesm").hide(); 
	        },
	        success: function(output){
                if(output=='error'){
                    // $("#alt").hide();
                    document.getElementById("alt").style.display = 'none';  
                    document.getElementById("WESM_sales").value = '';
                    document.getElementById("proceed_sales").disabled = false;
	        	    document.getElementById("cancel").disabled = false;
                    document.getElementById("alert_error").style.display = 'block';  
                }else{
                    // $("#alt").hide(); 
                    document.getElementById("alt").style.display = 'none';  
	        	    location.reload();
                }
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

async function upload_res_btn() {
	var reserve_sales_id = document.getElementById("reserve_sales_id").value;
	var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/upload_reserve_sales_process";
	let doc = document.getElementById("WESM_reserve_sales").files[0];
	let formData = new FormData();
	     
	formData.append("doc", doc);
	formData.append("reserve_sales_id", reserve_sales_id);
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
	        	document.getElementById("proceed_reserve_sales").disabled = true;
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

function cancelResSales(){
    var reserve_sales_id = document.getElementById("reserve_sales_id").value; 
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/cancel_reserve_sales";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
		$.ajax({
			data: "reserve_sales_id="+reserve_sales_id,
			type: "POST",
			url: redirect,
			success: function(response){
			    window.location=loc+'sales/upload_reserve_sales/';
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
		        	// document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
                    document.getElementById("alt1").style.display = 'block';  
		            $("#submitdata").hide(); 
		        },
		        success: function(output){
		        	window.location=loc+'sales/upload_sales/'+output;  
		        }
		    }); 
    	}
    }	 
}

function saveAllReserve(){
	var data = $("#upload_reserve_wesm").serialize();
	var loc= document.getElementById("baseurl").value;
	var count_name = document.getElementById("count_name").value;
  	if(count_name != 0){
      	alert('Some of the Company Name are empty!');
  	}  else {
    var redirect = loc+"sales/save_all_reserve";
    var conf = confirm('Are you sure you want to save this Reserve Sales?');
		if(conf){
		    $.ajax({
		        data: data,
		        type: "POST",
		        url: redirect,
		        beforeSend: function(){
		        	document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
		            $("#submitdata").hide(); 
		            $("#cancel").hide(); 
		        },
		        success: function(output){
		        	window.location=loc+'sales/upload_reserve_sales/'+output;  
		        }
		    }); 
    	}
    }	 
}

function proceed_collection() {
	 var data = $("#collection_bulk").serialize();
	
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/add_collection_head";
    var newurl=loc+"sales/upload_collection/";
    var conf = confirm('Are you sure you want to proceed?');
    if(conf){
	  $.ajax({
        type: "POST",
        url: redirect,
        data: data,
        success: function(output){
        	//alert(output);
        	var new_url = newurl+output;
        	document.getElementById("collection_id").value  = output;
        	window.history.replaceState('nextState', "Collection Head", new_url);
 			var save = document.getElementById("save_head_button");
            var cancel = document.getElementById("cancel");
        	document.getElementById('collection_date').readOnly = true;
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

function cancelCollection(){
    var collection_id = document.getElementById("collection_id").value; 
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/cancel_collection";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
		$.ajax({
			data: "collection_id="+collection_id,
			type: "POST",
			url: redirect,
			success: function(response){
			    window.location=loc+'sales/upload_collection/';
			}
		});
    }
}

async function upload_collection1() {
	//var sales_doc = document.getElementById("WESM_sales").value;
	var collection_id = document.getElementById("collection_id").value;
	var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/upload_bulk_collection";
    //var redirect1 = loc+"sales/load_bulk_data";
	let doc = document.getElementById("bulk_collection").files[0];
	let formData = new FormData();
	     
	formData.append("doc", doc);
	formData.append("collection_id", collection_id);
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
	        	document.getElementById("proceed_collection1").disabled = true;
	        	document.getElementById("cancel").disabled = true;
	        	$("#table-collection").hide(); 
	        },
	        success: function(output){
	        	//console.log(output);
	        	$("#alt").hide(); 
	        	location.reload();
	        	//alert(output);
			}
		});
	}
}

function saveAllCollection(){
	var data = $("#upload_bulkcollection").serialize();
	var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/save_all_collection";
    var conf = confirm('Are you sure you want to save this Collection?');
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
		        	//window.location=loc+'sales/upload_collection/'+output; 
		        	location.reload(); 
		        }
		    }); 
    }	 
}

function collection_filter() {
	 var collection_date = document.getElementById("col_date").value; 
     var reference_no = document.getElementById("reference_no").value;
     var buyer_fn = document.getElementById("buyer_fn").value;

    if(collection_date!=''){
        collection_date=collection_date;
    }else{
        collection_date='null';
    }

    if(reference_no!=''){
        reference_no=reference_no;
    }else{
        reference_no='null';
    }

    if(buyer_fn!=''){
        buyer_fn=buyer_fn;
    }else{
        buyer_fn='null';
    }


      var loc= document.getElementById("baseurl").value;
      window.location=loc+'sales/collection_list/'+collection_date+'/'+reference_no+'/'+buyer_fn;

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

function filterUploadResSales(){
	var reserve_sales_id= document.getElementById("res_sales_id").value;
	var in_ex_sub= document.getElementById("in_ex_sub").value;
	var loc= document.getElementById("baseurl").value;
    if(in_ex_sub!=''){
        var sub=in_ex_sub;
    }else{
        var sub='null';
    }
	window.location=loc+'sales/upload_reserve_sales/'+reserve_sales_id+'/'+sub;          
}

function filterSales(){
	var ref_no= document.getElementById("ref_no").value;
	var due_date= document.getElementById("due_date").value;
	var in_ex_sub= document.getElementById("in_ex_sub").value;
	var billing_from= document.getElementById("billing_from").value;
	var billing_to= document.getElementById("billing_to").value;
	var participant= document.getElementById("participant").value;
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

    if(billing_from!=''){
        var billfrom=billing_from;
    }else{
        var billfrom='null';
    }

    if(billing_to!=''){
        var billto=billing_to;
    }else{
        var billto='null';
    }

    if(participant!=''){
        var parti=participant;
    }else{
        var parti='null';
    }
	window.location=loc+'sales/sales_wesm/'+ref+'/'+due+'/'+sub+'/'+billfrom+'/'+billto+'/'+parti;          
}

function filterReserveSales(){
	var ref_no= document.getElementById("ref_no").value;
	var due_date= document.getElementById("due_date").value;
	var in_ex_sub= document.getElementById("in_ex_sub").value;
	var billing_from= document.getElementById("billing_from").value;
	var billing_to= document.getElementById("billing_to").value;
	var participant= document.getElementById("participant").value;
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

    if(billing_from!=''){
        var billfrom=billing_from;
    }else{
        var billfrom='null';
    }

    if(billing_to!=''){
        var billto=billing_to;
    }else{
        var billto='null';
    }

    if(participant!=''){
        var parti=participant;
    }else{
        var parti='null';
    }
	window.location=loc+'sales/reserve_sales_wesm/'+ref+'/'+due+'/'+sub+'/'+billfrom+'/'+billto+'/'+parti;          
}

function filterSalesAdjustment(){
	var ref_no= document.getElementById("ref_no").value;
	var due_date_from= document.getElementById("due_date_from").value;
	var due_date_to= document.getElementById("due_date_to").value;
	var in_ex_sub= document.getElementById("in_ex_sub").value;
	var participant= document.getElementById("participant").value;
	var loc= document.getElementById("baseurl").value;
	if(ref_no!=''){
        var ref=ref_no;
    }else{
        var ref='null';
    }

    if(due_date_from!=''){
        var duefrom=due_date_from;
    }else{
        var duefrom='null';
    }

    if(due_date_to!=''){
        var dueto=due_date_to;
    }else{
        var dueto='null';
    }

    if(in_ex_sub!=''){
        var sub=in_ex_sub;
    }else{
        var sub='null';
    }

    if(participant!=''){
        var parti=participant;
    }else{
        var parti='null';
    }
	window.location=loc+'sales/sales_wesm_adjustment/'+ref+'/'+duefrom+'/'+dueto+'/'+sub+'/'+parti;          
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

// function saveBseries(){
// 	var data = $("#update").serialize();
// 	var loc= document.getElementById("baseurl").value;
//     var redirect = loc+"sales/update_BSeriesno";
//     $.ajax({
//         data: data,
//         type: "POST",
//         url: redirect,
//         success: function(output){
//         	window.location=loc+'sales/sales_wesm/'+output;  
//         }
//     });  
// }

function saveBseries(baseurl,count,sales_detail_id,serial_no){
    var redirect = baseurl+"sales/update_BSeriesno";
    var series_number=document.getElementById("series_number"+count).value;

	$.ajax({
		type: "POST",
		url: redirect,
		data: 'sales_detail_id='+sales_detail_id+'&serial_no='+serial_no+'&series_number='+series_number,
        dataType: "json",
		success: function(response){
			document.getElementById("series_number"+count).value=response.series_number;
			//location.reload();
		}
	});
}

function saveResBseries(baseurl,count,reserve_sales_detail_id,serial_no){
    var redirect = baseurl+"sales/update_Res_BSeriesno";
    var series_number=document.getElementById("series_number"+count).value;

	$.ajax({
		type: "POST",
		url: redirect,
		data: 'reserve_sales_detail_id='+reserve_sales_detail_id+'&serial_no='+serial_no+'&series_number='+series_number,
        dataType: "json",
		success: function(response){
			document.getElementById("series_number"+count).value=response.series_number;
			//location.reload();
		}
	});
}

function saveBseriesadjustment(baseurl,count,sales_detail_id,serial_no){
    var redirect = baseurl+"sales/update_BSeriesnoAdjustment";
    var series_number=document.getElementById("series_number"+count).value;

	$.ajax({
		type: "POST",
		url: redirect,
		data: 'sales_detail_id='+sales_detail_id+'&serial_no='+serial_no+'&series_number='+series_number,
        dataType: "json",
		success: function(response){
			document.getElementById("series_number"+count).value=response.series_number;
			//location.reload();
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


function updateSeries(baseurl,count,collection_id,settlement_id,reference_number,item_no){
    var redirect = baseurl+"sales/update_seriesno";
    var series_number=document.getElementById("series_number"+count).value;
    var old_series=document.getElementById("old_series_no"+count).value;
    document.getElementById("old_series_no"+count).setAttribute('value','');
    //alert(settlement_id);
	$.ajax({
		type: "POST",
		url: redirect,
		data: 'series_number='+series_number+'&collection_id='+collection_id+'&settlement_id='+settlement_id+'&reference_number='+reference_number+'&old_series='+old_series+'&item_no='+item_no,
		success: function(output){
			//alert(output);
			document.getElementById("series_number"+count).setAttribute('value',output);
			document.getElementById("old_series_no"+count).value=series_number;
						//document.getElementById("series_number"+count).value=output;
			//document.getElementById("old_series_no"+count).value=output;
		}
	});
}

function updateORDate(baseurl,count,collection_id,settlement_id,reference_number,item_no){
    var redirect = baseurl+"sales/update_ordate";
    var or_date=document.getElementById("or_date"+count).value;
    var old_or_date=document.getElementById("old_or_date"+count).value;
    document.getElementById("old_or_date"+count).setAttribute('value','');
	$.ajax({
		type: "POST",
		url: redirect,
		data: 'or_date='+or_date+'&collection_id='+collection_id+'&settlement_id='+settlement_id+'&reference_number='+reference_number+'&old_or_date='+old_or_date+'&item_no='+item_no,
		success: function(output){
			document.getElementById("or_date"+count).setAttribute('value',output);
			document.getElementById("old_or_date"+count).value=or_date;
		}
	});
}

function updateorRemarks(baseurl,count,collection_id,settlement_id,reference_number,item_no){
    var redirect = baseurl+"sales/update_orno_remarks";
    var or_no_remarks=document.getElementById("or_no_remarks"+count).value;
	$.ajax({
		type: "POST",
		url: redirect,
		data: 'or_no_remarks='+or_no_remarks+'&collection_id='+collection_id+'&settlement_id='+settlement_id+'&reference_number='+reference_number+'&item_no='+item_no,
		success: function(output){
			//alert(output);
			document.getElementById("or_no_remarks"+count).setAttribute('value',output);
		}
	});
}

function updateDefInt(baseurl,count,collection_id,settlement_id,reference_number,item_no){
    var redirect = baseurl+"sales/update_defint";
    var def_int=document.getElementById("def_int"+count).value;
	$.ajax({
		type: "POST",
		url: redirect,
		data: 'def_int='+def_int+'&collection_id='+collection_id+'&settlement_id='+settlement_id+'&reference_number='+reference_number+'&item_no='+item_no,
		success: function(output){
			//alert(output);
			document.getElementById("def_int"+count).setAttribute('value',output);
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

function printMultipleAdjustment(){
	var x = document.getElementsByClassName("multiple_print");
	var loc= document.getElementById("baseurl").value;
 	var redirect = loc+"sales/print_multiple_adjustment";
 	var form = document.querySelector('#print_mult');
    var formData = new FormData(form);
	for(var i =0;i<x.length;i++){
		if(document.getElementsByClassName('multiple_print')[i].checked){
			multiple_print= document.querySelector('input[name="multiple_print[]"]').value;
			formData.append('multiple_print'+[i], multiple_print);
		}
    }
    $.ajax({
        type: "POST",
        url: redirect,
        data: formData,
        processData: false,
        contentType: false,
        success: function(output){
            var exp=output.split(",");
            //window.open(loc+'sales/print_BS_multiple/'+exp[0]+'/'+exp[1]+'/'+exp[2],"_blank");
            window.open(loc+'sales/print_bs_adjustment/'+exp[0]+'/'+exp[1]+'/'+exp[2],"_blank");
        }
    });
}

function printReserveMultiple(){
	var x = document.getElementsByClassName("multiple_print");
	var loc= document.getElementById("baseurl").value;
 	var redirect = loc+"sales/print_multiple_reserve";
 	var form = document.querySelector('#print_mult');
    var formData = new FormData(form);
	for(var i =0;i<x.length;i++){
		if(document.getElementsByClassName('multiple_print')[i].checked){
			multiple_print= document.querySelector('input[name="multiple_print[]"]').value;
			formData.append('multiple_print'+[i], multiple_print);
		}
    }
    $.ajax({
        type: "POST",
        url: redirect,
        data: formData,
        processData: false,
        contentType: false,
        success: function(output){
            var exp=output.split(",");
            window.open(loc+'sales/print_BS_reserve/'+exp[0]+'/'+exp[1]+'/'+exp[2],"_blank");
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
				// document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
                document.getElementById("alt").style.display = 'block';  
                document.getElementById("alert_error").style.display = 'none';  
				document.getElementById("selectfile").disabled = true;
			},
			success: function(output) {
                if(output=='error'){
                    $(".loading").removeClass("d-block").addClass("d-none");
                    document.getElementById("selectfile").disabled = false;
                    document.getElementById("alt").style.display = 'none';  
                    document.getElementById("selectfile").value = '';
                    document.getElementById("alert_error").style.display = 'block';  
                }else{
                    $(".loading").removeClass("d-block").addClass("d-none");
                    document.getElementById("alt").style.display = 'none';  
                    window.location=loc+'sales/upload_sales_adjustment/'+output;
                }
				//$("#showThumb").append(data);
				// $("#alt").hide();
				
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

function updateReserveSales(baseurl,count,reserve_sales_detail_id,reserve_sales_id,billing_id){
    var redirect = baseurl+"sales/update_reserve_details";
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
		data: 'reserve_sales_detail_id='+reserve_sales_detail_id+'&reserve_sales_id='+reserve_sales_id+'&billing_id='+billing_id+'&ewt_amount='+ewt_amount+'&original_copy='+original_copy+'&scanned_copy='+scanned_copy,
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


function proceed_bulk_update_main() {
    var data = $("#bulkupdatemain").serialize();
    
    var loc= document.getElementById("baseurl").value;
    var sales_id= document.getElementById("sales_id").value;
    if(sales_id==""){
        alert('Reference number must not be empty!');
    }  else {
    var redirect=loc+"sales/bulk_update_main/";
        $.ajax({
            type: "POST",
            url: redirect,
            data: data,
            success: function(output){
                window.location=loc+'sales/bulk_update_main/'+sales_id;
                var redirect = redirect+output;
                var save = document.getElementById("save_updatebulk_main");
                var cancel = document.getElementById("cancel_updatebulk_main");
                document.getElementById('ref_no').readOnly = true;
                var x = document.getElementById("upload_bulk_update_main");
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

function proceed_bulk_update_reserve_main() {
    var data = $("#bulkupdatereservemain").serialize();
    
    var loc= document.getElementById("baseurl").value;
    var reserve_sales_id= document.getElementById("reserve_sales_id").value;
    if(reserve_sales_id==""){
        alert('Reference number must not be empty!');
    }  else {
    var redirect=loc+"sales/bulk_update_reserve_main/";
        $.ajax({
            type: "POST",
            url: redirect,
            data: data,
            success: function(output){
                window.location=loc+'sales/bulk_update_reserve_main/'+reserve_sales_id;
                var redirect = redirect+output;
                var save = document.getElementById("save_updatebulk_reservemain");
                var cancel = document.getElementById("cancel_updatebulk_reservemain");
                document.getElementById('ref_no').readOnly = true;
                var x = document.getElementById("upload_bulk_update_reserve_main");
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

async function upload_bulkupdate_main() {
    //var sales_doc = document.getElementById("WESM_sales").value;
    var sales_id = document.getElementById("sales_id").value;
    var identifier = document.getElementById("identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/upload_bulk_update_main";
    let doc = document.getElementById("bulkupdate_main").files[0];
    let formData = new FormData();
         
    formData.append("doc", doc);
    formData.append("sales_id", sales_id);
    formData.append("identifier", identifier);
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
                document.getElementById("proceed_bulkupdate_main").disabled = true;
                document.getElementById("cancel_updatebulk_main").disabled = true;
                $("#table-main").hide(); 
            },
            success: function(output){
                $("#alt").hide(); 
              
                window.location=loc+'sales/bulk_update_main/'+sales_id+'/'+identifier;
            }
        });
    }
}

async function upload_bulkupdate_reserve_main() {
    var reserve_sales_id = document.getElementById("reserve_sales_id").value;
    var identifier = document.getElementById("identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/upload_bulk_update_reserve_main";
    let doc = document.getElementById("bulkupdate_reserve_main").files[0];
    let formData = new FormData();
         
    formData.append("doc", doc);
    formData.append("reserve_sales_id", reserve_sales_id);
    formData.append("identifier", identifier);
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
                document.getElementById("proceed_bulkupdate_reserve_main").disabled = true;
                document.getElementById("cancel_updatebulk_reservemain").disabled = true;
                $("#table-reserve-main").hide(); 
            },
            success: function(output){
                $("#alt").hide(); 
              
                window.location=loc+'sales/bulk_update_reserve_main/'+reserve_sales_id+'/'+identifier;
            }
        });
    }
}

function saveBulkUpdateMain(){
    var data = $("#upload_bulkupdate_main").serialize();
    var sales_id = document.getElementById("sales_id").value;
    var main_identifier = document.getElementById("main_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/save_bulkupdate_main";
    var conf = confirm('Are you sure you want to save this Bulk Update?');
    if(conf){
        $.ajax({
            data: data,
            type: "POST",
            url: redirect,
            beforeSend: function(){
                document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
                $("#submitbulkmain").hide(); 
            },
            success: function(output){
                window.location=loc+'sales/bulk_update_main/'+sales_id+'/'+main_identifier;
            }
        });
    }
}

function saveBulkUpdateReserveMain(){
    var data = $("#upload_bulkupdate_reserve_main").serialize();
    var reserve_sales_id = document.getElementById("reserve_sales_id").value;
    var main_identifier = document.getElementById("main_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/save_bulkupdate_reserve_main";
    var conf = confirm('Are you sure you want to save this Bulk Update?');
    if(conf){
        $.ajax({
            data: data,
            type: "POST",
            url: redirect,
            beforeSend: function(){
                document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
                $("#submitbulkmain").hide(); 
            },
            success: function(output){
                window.location=loc+'sales/bulk_update_main/'+reserve_sales_id+'/'+main_identifier;
            }
        });
    }
}

function cancelBulkUpdateMain(){
    var sales_id = document.getElementById("sales_id").value;
    var main_identifier = document.getElementById("main_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/cancel_bulkupdate_main";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
        $.ajax({
            data: 'sales_id='+sales_id+'&main_identifier='+main_identifier,
            type: "POST",
            url: redirect,
            success: function(response){
                window.location=loc+'sales/bulk_update_main/';
            }
        });
    }
}

function cancelBulkUpdateReserveMain(){
    var reserve_sales_id= document.getElementById("reserve_sales_id").value;
    var main_identifier = document.getElementById("main_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/cancel_bulkupdate_reserve_main";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
        $.ajax({
            data: 'reserve_sales_id='+reserve_sales_id+'&main_identifier='+main_identifier,
            type: "POST",
            url: redirect,
            success: function(response){
                window.location=loc+'sales/bulk_update_reserve_main/';
            }
        });
    }
}

function proceed_bulk_update_adjustment() {
    var data = $("#bulkupdateadjustment").serialize();
    
    var loc= document.getElementById("baseurl").value;
    var due_date= document.getElementById("due_date").value;
    if(due_date==""){
        alert('Due Date must not be empty!');
    }  else {
    var redirect=loc+"sales/bulk_update_adjustment/";
        $.ajax({
            type: "POST",
            url: redirect,
            data: data,
            success: function(output){
                window.location=loc+'sales/bulk_update_adjustment/'+due_date;
                var redirect = redirect+output;
                var save = document.getElementById("save_updatebulk_adjustment");
                var cancel = document.getElementById("cancel_updatebulk_adjustment");
                document.getElementById('due_date').readOnly = true;
                var x = document.getElementById("upload_bulk_update_adjustment");
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

async function upload_bulkupdate_adjustment() {
    //var sales_doc = document.getElementById("WESM_sales").value;
    var due = document.getElementById("due").value;
    var identifier = document.getElementById("identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/upload_bulk_update_adjustment";
    let doc = document.getElementById("bulkupdate_adjustment").files[0];
    let formData = new FormData();
         
    formData.append("doc", doc);
    formData.append("due", due);
    formData.append("identifier", identifier);
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
                document.getElementById("proceed_bulkupdate_adjustment").disabled = true;
                document.getElementById("cancel_updatebulk_adjustment").disabled = true;
                $("#table-adjustment").hide(); 
            },
            success: function(output){
                $("#alt").hide(); 
                //console.log(output);
                window.location=loc+'sales/bulk_update_adjustment/'+due+'/'+identifier;
            }
        });
    }
}

function saveBulkUpdateAdjustment(){
    var data = $("#upload_bulkupdate_adjustment").serialize();
    var due = document.getElementById("due").value;
    var adjustment_identifier = document.getElementById("adjustment_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/save_bulkupdate_adjustment";
    var conf = confirm('Are you sure you want to save this Bulk Update?');
    if(conf){
        $.ajax({
            data: data,
            type: "POST",
            url: redirect,
            beforeSend: function(){
                document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
                $("#submitbulkadjustment").hide(); 
            },
            success: function(output){
                window.location=loc+'sales/bulk_update_adjustment/'+due+'/'+adjustment_identifier;
            }
        });
    }
}

function cancelBulkUpdateAdjustment(){
    var due = document.getElementById("due").value;
    var adjustment_identifier = document.getElementById("adjustment_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/cancel_bulkupdate_adjustment";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
        $.ajax({
            data: 'due_date='+due+'&adjustment_identifier='+adjustment_identifier,
            type: "POST",
            url: redirect,
            success: function(response){
                window.location=loc+'sales/bulk_update_adjustment/';
            }
        });
    }
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

function printbs_adjustment_history(){
	var data = $("#InsertBSAdjustment").serialize();
	var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/insert_printbs_adjustment";
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

function printreservebs_history(){
	var data = $("#InsertReserveBS").serialize();
	var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/insert_printreservebs";
	    $.ajax({
	        data: data,
	        type: "POST",
	        url: redirect,
	        success: function(output){
	         window.print();
	        }
	    });
	    
}

function proceed_sales_main_invoicing() {
    var data = $("#bulkinvoicing").serialize();
    
    var loc= document.getElementById("baseurl").value;
    var year_filt= document.getElementById("year").value;
    var reference_filt= document.getElementById("reference_number").value;
    var due_date_filt= document.getElementById("due_date").value;

	   if(year_filt!=''){
	       var year=year_filt;
	   }else{
	       var year='null';
	   }

	   if(reference_filt!=''){
	       var reference=reference_filt;
	   }else{
	       var reference='null';
	   }

	   if(due_date_filt!=''){
	       var due_date=due_date_filt;
	   }else{
	       var due_date='null';
	   }


    if(year=="" && reference=="" && due_date==""){
        alert('You must select data to proceed!');
    }  else {
    var redirect=loc+"sales/bulk_invoicing_main/";
        $.ajax({
            type: "POST",
            url: redirect,
            data: data,
            success: function(output){
                window.location=loc+'sales/bulk_invoicing_main/'+year+'/'+reference+'/'+due_date;
                var redirect = redirect+output;
                var save = document.getElementById("save_bulk_invoicing");
                var cancel = document.getElementById("cancel_bulk_invoicing");
                document.getElementById('due_date').readOnly = true;
                var x = document.getElementById("upload_bulk_invoicing");
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

async function upload_bulkinvoicing_main() {
	var year= document.getElementById("year_disp").value;
    var reference= document.getElementById("reference_number").value;
    var due = document.getElementById("due").value;
    var identifier = document.getElementById("identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/upload_bulk_invoicing_main";
    let doc = document.getElementById("bulkinvoicing_main").files[0];
    let formData = new FormData();
         
    formData.append("doc", doc);
    formData.append("year", year);
    formData.append("reference", reference);
    formData.append("due", due);
    formData.append("identifier", identifier);
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
                document.getElementById("proceed_bulkinvoicing_main").disabled = true;
                document.getElementById("cancel_bulk_invoicing").disabled = true;
                $("#table-invoicing").hide(); 
            },
            success: function(output){
                $("#alt").hide();
                window.location=loc+'sales/bulk_invoicing_main/'+year+'/'+reference+'/'+due+'/'+identifier;
            }
        });
    }
}

function saveBulkInvoicingMain(){
    var data = $("#upload_bulk_invoicing_main").serialize();
    var year= document.getElementById("year_disp").value;
    var reference= document.getElementById("reference_number").value;
    var due = document.getElementById("due").value;
    var main_identifier = document.getElementById("main_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/save_bulkinvoicing_main";
    var conf = confirm('Are you sure you want to save this Bulk Update?');
    if(conf){
        $.ajax({
            data: data,
            type: "POST",
            url: redirect,
            beforeSend: function(){
                document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>';
                $("#submitbulkmain").hide(); 
            },
            success: function(output){
                window.location=loc+'sales/bulk_invoicing_main/'+year+'/'+reference+'/'+due+'/'+main_identifier;
            }
        });
    }
}

function cancelSalesMainInvoicing(){
    var main_identifier = document.getElementById("main_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/cancel_main_sales_invoicing";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
        $.ajax({
            data: '&main_identifier='+main_identifier,
            type: "POST",
            url: redirect,
            success: function(response){
                window.location=loc+'sales/bulk_invoicing_main/';
            }
        });
    }
}

function proceed_sales_invoicing() {
    var data = $("#bulkinvoicing").serialize();
    
    var loc= document.getElementById("baseurl").value;
    var due_date= document.getElementById("due_date").value;
    if(due_date==""){
        alert('Due Date must not be empty!');
    }  else {
    var redirect=loc+"sales/bulk_invoicing/";
        $.ajax({
            type: "POST",
            url: redirect,
            data: data,
            success: function(output){
                window.location=loc+'sales/bulk_invoicing/'+due_date;
                var redirect = redirect+output;
                var save = document.getElementById("save_bulk_invoicing");
                var cancel = document.getElementById("cancel_bulkinvoicing_adjustment");
                document.getElementById('due_date').readOnly = true;
                var x = document.getElementById("upload_bulk_invoicing");
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

async function upload_bulkinvoicing_adjustment() {
    var due = document.getElementById("due").value;
    var identifier = document.getElementById("identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/upload_bulk_invoicing_adjustment";
    let doc = document.getElementById("bulkinvoicing_adjustment").files[0];
    let formData = new FormData();
         
    formData.append("doc", doc);
    formData.append("due", due);
    formData.append("identifier", identifier);
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
                document.getElementById("proceed_bulkinvoicing_adjustment").disabled = true;
                document.getElementById("cancel_bulkinvoicing_adjustment").disabled = true;
                $("#table-invoicing").hide(); 
            },
            success: function(output){
                $("#alt").hide();
                window.location=loc+'sales/bulk_invoicing/'+due+'/'+identifier;
            }
        });
    }
}

function saveBulkInvoicingAdjustment(){
    var data = $("#upload_bulkinvoicing_adjustment").serialize();
    var due = document.getElementById("due").value;
    var adjustment_identifier = document.getElementById("adjustment_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/save_bulkinvoicing_adjustment";
    var conf = confirm('Are you sure you want to save this Bulk Update?');
    if(conf){
        $.ajax({
            data: data,
            type: "POST",
            url: redirect,
            beforeSend: function(){
                document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>';
                $("#submitbulkadjustment").hide(); 
            },
            success: function(output){
                window.location=loc+'sales/bulk_invoicing/'+due+'/'+adjustment_identifier;
            }
        });
    }
}

function cancelSalesInvoicing(){
    var due = document.getElementById("due").value;
    var adjustment_identifier = document.getElementById("adjustment_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/cancel_sales_invoicing";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
        $.ajax({
            data: 'due_date='+due+'&adjustment_identifier='+adjustment_identifier,
            type: "POST",
            url: redirect,
            success: function(response){
                window.location=loc+'sales/bulk_invoicing/';
            }
        });
    }
}

function proceed_collection_reserve() {
    var data = $("#collection_bulk").serialize();
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/add_reserve_collection_head";
    var newurl=loc+"sales/upload_reserve_collection/";
    var conf = confirm('Are you sure you want to proceed?');
    if(conf){
     $.ajax({
       type: "POST",
       url: redirect,
       data: data,
       success: function(output){
           var new_url = newurl+output;
           document.getElementById("res_collection_id").value  = output;
           window.history.replaceState('nextState', "Collection Head", new_url);
            var save = document.getElementById("save_head_button");
           var cancel = document.getElementById("cancel");
           document.getElementById('collection_date').readOnly = true;
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

function cancelReserveCollection(){
    var res_collection_id = document.getElementById("res_collection_id").value; 
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/cancel_reserve_sales";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
		$.ajax({
			data: "res_collection_id="+res_collection_id,
			type: "POST",
			url: redirect,
			success: function(response){
			    window.location=loc+'sales/upload_reserve_collection';
			}
		});
    }
}

async function upload_reserve_collection() {
	var res_collection_id = document.getElementById("res_collection_id").value;
	var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/upload_reserve_bulk_collection";
	let doc = document.getElementById("bulk_collection").files[0];
	let formData = new FormData();
	formData.append("doc", doc);
	formData.append("res_collection_id", res_collection_id);
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
	        	document.getElementById("proceed_collection1").disabled = true;
	        	document.getElementById("cancel").disabled = true;
	        	$("#table-collection").hide(); 
	        },
	        success: function(output){
	        	$("#alt").hide(); 
	        	location.reload();
			}
		});
	}
}

function updateSeriesReserve(baseurl,count,res_collection_id,settlement_id,reference_number,item_no){
    var redirect = baseurl+"sales/update_reserve_seriesno";
    var series_number=document.getElementById("series_number"+count).value;
    var old_series=document.getElementById("old_series_no"+count).value;
    document.getElementById("old_series_no"+count).setAttribute('value','');
	$.ajax({
		type: "POST",
		url: redirect,
		data: 'series_number='+series_number+'&res_collection_id='+res_collection_id+'&settlement_id='+settlement_id+'&reference_number='+reference_number+'&old_series='+old_series+'&item_no='+item_no,
		success: function(output){
			document.getElementById("series_number"+count).setAttribute('value',output);
			document.getElementById("old_series_no"+count).value=series_number;
		}
	});
}

function updateORDateReserve(baseurl,count,res_collection_id,settlement_id,reference_number,item_no){
    var redirect = baseurl+"sales/update_reserve_ordate";
    var or_date=document.getElementById("or_date"+count).value;
    var old_or_date=document.getElementById("old_or_date"+count).value;
    document.getElementById("old_or_date"+count).setAttribute('value','');
	$.ajax({
		type: "POST",
		url: redirect,
		data: 'or_date='+or_date+'&res_collection_id='+res_collection_id+'&settlement_id='+settlement_id+'&reference_number='+reference_number+'&old_or_date='+old_or_date+'&item_no='+item_no,
		success: function(output){
			document.getElementById("or_date"+count).setAttribute('value',output);
			document.getElementById("old_or_date"+count).value=or_date;
		}
	});
}

function updateorRemarksReserve(baseurl,count,res_collection_id,settlement_id,reference_number,item_no){
    var redirect = baseurl+"sales/update_reserve_orno_remarks";
    var or_no_remarks=document.getElementById("or_no_remarks"+count).value;
	$.ajax({
		type: "POST",
		url: redirect,
		data: 'or_no_remarks='+or_no_remarks+'&res_collection_id='+res_collection_id+'&settlement_id='+settlement_id+'&reference_number='+reference_number+'&item_no='+item_no,
		success: function(output){
			document.getElementById("or_no_remarks"+count).setAttribute('value',output);
		}
	});
}

function updateDefIntReserve(baseurl,count,res_collection_id,settlement_id,reference_number,item_no){
    var redirect = baseurl+"sales/update_reserve_defint";
    var def_int=document.getElementById("def_int"+count).value;
	$.ajax({
		type: "POST",
		url: redirect,
		data: 'def_int='+def_int+'&res_collection_id='+res_collection_id+'&settlement_id='+settlement_id+'&reference_number='+reference_number+'&item_no='+item_no,
		success: function(output){
			document.getElementById("def_int"+count).setAttribute('value',output);
		}
	});
}

function saveAllCollectionReserve(){
	var data = $("#upload_bulkcollection").serialize();
	var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/save_reserve_all_collection";
    var conf = confirm('Are you sure you want to save this Collection?');
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
                location.reload(); 
            }
        }); 
    }	 
}

function collection_reserve_filter() {
    var collection_date = document.getElementById("col_date").value; 
    var reference_no = document.getElementById("reference_no").value;
    var buyer_fn = document.getElementById("buyer_fn").value;

   if(collection_date!=''){
       collection_date=collection_date;
   }else{
       collection_date='null';
   }

   if(reference_no!=''){
       reference_no=reference_no;
   }else{
       reference_no='null';
   }

   if(buyer_fn!=''){
        buyer_fn=buyer_fn;
    }else{
        buyer_fn='null';
    }

     var loc= document.getElementById("baseurl").value;
     window.location=loc+'sales/collection_reserve_list/'+collection_date+'/'+reference_no+'/'+buyer_fn;
}

function select_signatory() {
    var signatory = document.getElementById("signatory").value; 
    var collection_date = document.getElementById("date_collect").value; 
    var reference_no = document.getElementById("refno").value;
    var buyer_fn = document.getElementById("buyerfn").value;
    var loc= document.getElementById("baseurl").value;
    var exported = loc+'sales/PDF_OR_bulk/'+collection_date+'/'+reference_no+'/'+buyer_fn;
    $('#export').prop('href', exported+'/'+signatory);

    var count = document.getElementsByClassName("print_pdf"); 
    for(var i = 1; i<=count.length;i++){
        var collection_id = document.getElementById("collection_idurl"+i).value; 
        var settlement_id_single = document.getElementById("settlement_id_singleurl"+i).value; 
        var reference_no_single = document.getElementById("reference_no_singleurl"+i).value; 
        var series_number = document.getElementById("series_numberurl"+i).value; 
        var printed = loc+'sales/PDF_OR/'+collection_id+'/'+settlement_id_single+'/'+reference_no_single+'/'+series_number;
        // alert(printed+'/'+signatory)
        $('#print_pdf'+i).prop('href', printed+'/'+signatory);
       
    }
}

function select_signatory_reserve() {
    var signatory = document.getElementById("signatory").value; 
    var collection_date = document.getElementById("date_collect").value; 
    var reference_no = document.getElementById("refno").value;
    // var stl_id = document.getElementById("stlid").value;
    var buyer_fn = document.getElementById("buyerfn").value;
    var loc= document.getElementById("baseurl").value;
    var exported = loc+'sales/PDF_OR_bulk_reserve/'+collection_date+'/'+reference_no+'/'+buyer_fn;
    $('#export').prop('href', exported+'/'+signatory);

    var count = document.getElementsByClassName("print_pdf"); 
    for(var i = 1; i<=count.length;i++){
        var collection_id = document.getElementById("collection_idurl"+i).value; 
        var settlement_id_single = document.getElementById("settlement_id_singleurl"+i).value; 
        var reference_no_single = document.getElementById("reference_no_singleurl"+i).value; 
        var series_number = document.getElementById("series_numberurl"+i).value; 
        var printed = loc+'sales/PDF_OR_reserve/'+collection_id+'/'+settlement_id_single+'/'+reference_no_single+'/'+series_number;
        // alert(printed+'/'+signatory)
        $('#print_pdf'+i).prop('href', printed+'/'+signatory);
       
    }
    
}