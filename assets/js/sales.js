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

function collection_filter() {
	 var ref_number = document.getElementById("ref_number").value; 
	  var loc= document.getElementById("baseurl").value;
	  window.location=loc+'sales/collection_list/'+ref_number;

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
				countPrint(loc,sales_detail_id);
			    window.opener.location=loc+'sales/print_BS/'+output;
			    window.close();
			}
		});
    }
}

function filterSales(){
	var ref_no= document.getElementById("ref_no").value;
	var loc= document.getElementById("baseurl").value;
	window.location=loc+'sales/sales_wesm/'+ref_no;          
}