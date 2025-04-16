function proceed_btn() {
	var data = $("#saleshead").serialize();
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"salesmerge/add_salesmerge_head";
    var newurl=loc+"salesmerge/upload_sales_merge/";
    var conf = confirm('Are you sure you want to proceed?');
    if(conf){
	  $.ajax({
        type: "POST",
        url: redirect,
        data: data,
        success: function(output){
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

async function upload_merge_btn() {
	var sales_id = document.getElementById("sales_id").value;
	var loc= document.getElementById("baseurl").value;
    var redirect = loc+"salesmerge/upload_salesmerge_process";
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
                document.getElementById("alt").style.display = 'block';  
                document.getElementById("alert_error").style.display = 'none';  
	        	document.getElementById("proceed_sales").disabled = true;
	        	document.getElementById("cancel").disabled = true;
	        	$("#table-wesm").hide(); 
	        },
	        success: function(output){
                if(output=='error'){
                    document.getElementById("alt").style.display = 'none';  
                    document.getElementById("WESM_sales").value = '';
                    document.getElementById("proceed_sales").disabled = false;
	        	    document.getElementById("cancel").disabled = false;
                    document.getElementById("alert_error").style.display = 'block';
                }else{
                    document.getElementById("alt").style.display = 'none';  
	        	    location.reload();
                }
			}
		});
	}
}

function saveAllMerge(){
	var data = $("#upload_wesm").serialize();
	var loc= document.getElementById("baseurl").value;
	var count_name = document.getElementById("count_name").value;
  	if(count_name != 0){
      	alert('Some of the Company Name are empty!');
  	}  else {
    var redirect = loc+"salesmerge/save_all_merge";
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
		        	window.location=loc+'salesmerge/upload_sales_merge/'+output;  
		        }
		    }); 
    	}
    }	 
}

function cancelMergeSales(){
    var sales_id = document.getElementById("sales_id").value; 
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"salesmerge/cancel_sales_merge";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
		$.ajax({
			data: "sales_id="+sales_id,
			type: "POST",
			url: redirect,
			success: function(response){
			    window.location=loc+'salesmerge/upload_sales_merge/';
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
	window.location=loc+'salesmerge/upload_sales_merge/'+sales_id+'/'+sub;          
}

function filterMergeSales(){
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
	window.location=loc+'salesmerge/sales_wesm_merge/'+ref+'/'+due+'/'+sub+'/'+billfrom+'/'+billto+'/'+parti;          
}

function printMultipleMerge(){
	var x = document.getElementsByClassName("multiple_print");
	var loc= document.getElementById("baseurl").value;
 	var redirect = loc+"salesmerge/print_multiple";
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
            window.open(loc+'salesmerge/print_BS_merge/'+exp[0]+'/'+exp[1]+'/'+exp[2],"_blank");
        }
    });
}

function printbsm_history(){
    var data = $("#InsertBSM").serialize();
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"salesmerge/insert_printbsm";
    $.ajax({
        data: data,
        type: "POST",
        url: redirect,
        success: function(output){
         window.print();
        }
    });
}

function saveBseries(baseurl,count,sales_detail_id,serial_no){
    var redirect = baseurl+"salesmerge/update_BSeriesno";
    var series_number=document.getElementById("series_number"+count).value;

    $.ajax({
        type: "POST",
        url: redirect,
        data: 'sales_detail_id='+sales_detail_id+'&serial_no='+serial_no+'&series_number='+series_number,
        dataType: "json",
        success: function(response){
            document.getElementById("series_number"+count).value=response.series_number;
        }
    });
}

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

function proceed_sales_merge_invoicing() {
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
    var redirect=loc+"salesmerge/bulk_invoicing_merge/";
        $.ajax({
            type: "POST",
            url: redirect,
            data: data,
            success: function(output){
                window.location=loc+'salesmerge/bulk_invoicing_merge/'+year+'/'+reference+'/'+due_date;
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

function cancelSalesMergeInvoicing(){
    var main_identifier = document.getElementById("main_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"salesmerge/cancel_merge_sales_invoicing";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
        $.ajax({
            data: '&main_identifier='+main_identifier,
            type: "POST",
            url: redirect,
            success: function(response){
                window.location=loc+'salesmerge/bulk_invoicing_merge/';
            }
        });
    }
}

async function upload_bulkinvoicing_merge() {
    var year= document.getElementById("year_disp").value;
    var reference= document.getElementById("reference_number").value;
    var due = document.getElementById("due").value;
    var identifier = document.getElementById("identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"salesmerge/upload_bulk_invoicing_merge";
    let doc = document.getElementById("bulkinvoicing_merge").files[0];
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
                document.getElementById("proceed_bulkinvoicing_merge").disabled = true;
                document.getElementById("cancel_bulk_invoicing").disabled = true;
                $("#table-invoicing").hide(); 
            },
            success: function(output){
                $("#alt").hide();
                window.location=loc+'salesmerge/bulk_invoicing_merge/'+year+'/'+reference+'/'+due+'/'+identifier;
            }
        });
    }
}

function saveBulkInvoicingMerge(){
    var data = $("#upload_bulk_invoicing_merge").serialize();
    var year= document.getElementById("year_disp").value;
    var reference= document.getElementById("reference_number").value;
    var due = document.getElementById("due").value;
    var main_identifier = document.getElementById("main_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"salesmerge/save_bulkinvoicing_merge";
    var conf = confirm('Are you sure you want to save this Bulk Update?');
    if(conf){
        $.ajax({
            data: data,
            type: "POST",
            url: redirect,
            beforeSend: function(){
                document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>';
                $("#submitbulkmerge").hide(); 
            },
            success: function(output){
                window.location=loc+'salesmerge/bulk_invoicing_merge/'+year+'/'+reference+'/'+due+'/'+main_identifier;
            }
        });
    }
}