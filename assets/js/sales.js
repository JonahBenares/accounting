var clicksBS = 0;
function add_details_BS(baseurl) {
    window.open(baseurl+"sales/add_details_BS/", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
    clicksBS += 1;
	document.getElementById("clicksBS").innerHTML = '('+clicksBS+')';
}
function add_details_wesm(baseurl) {
    window.open(baseurl+"sales/add_details_wesm/", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
}
var clicksOR = 0;
function add_details_OR(baseurl) {
    window.open(baseurl+"sales/add_details_OR/", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
    clicksOR += 1;
	document.getElementById("clicksOR").innerHTML = '('+clicksOR+')';
}
function proceed_btn() {
	 var data = $("#saleshead").serialize();
	
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/add_sales_head";
    var newurl=loc+"sales/upload_sales/";

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

async function upload_btn() {
	//var sales_doc = document.getElementById("WESM_sales").value;
	var sales_id = document.getElementById("sales_id").value;
	var loc= document.getElementById("baseurl").value;
    var redirect = loc+"sales/upload_sales_process";
	let doc = document.getElementById("WESM_sales").files[0];
	let formData = new FormData();
	     
	formData.append("doc", doc);
	formData.append("sales_id", sales_id);
		  $.ajax({
	        type: "POST",
	        url: redirect,
	        data: formData,
	        processData: false,
    		contentType: false,
	        success: function(output){
	        	alert(output);
				var x = document.getElementById("table-wesm");
					if (x.style.display === "none") {
						x.style.display = "block";
				} else {
					x.style.display = "none";
				}
			}
	  });
}

function collection_filter() {
	var x = document.getElementById("collection-list");
		if (x.style.display === "none") {
			x.style.display = "block";
	} else {
		x.style.display = "none";
	}
}


 //    function onClick() {
	    
	// };