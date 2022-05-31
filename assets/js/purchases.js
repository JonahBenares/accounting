function add_payment(baseurl,purchase_id,purchase_detail_id) {
    window.open(baseurl+"purchases/add_payment/"+purchase_id+'/'+purchase_detail_id, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
}

function add_details_wesm(baseurl,purchase_details_id) {
    /*var redirect = baseurl+"purchases/count_print";
    $.ajax({
        data: "purchase_details_id="+purchase_details_id,
        type: "POST",
        url: redirect,
        success: function(output){*/
            window.open(baseurl+"purchases/add_details_wesm/"+purchase_details_id, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
        /*}
    });*/
}

function countPrint(baseurl,purchase_details_id){
  var redirect = baseurl+"purchases/count_print";
  $.ajax({
    data: "purchase_details_id="+purchase_details_id,
    type: "POST",
    url: redirect,
    success: function(output){
        location.reload();
    }
  });
}

function proceed_btn() {
     var data = $("#purchasehead").serialize();
    
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"purchases/add_purchase_head";
    var newurl=loc+"purchases/upload_purchases/";
    var conf = confirm('Are you sure you want to proceed?');
    if(conf){
        $.ajax({
            type: "POST",
            url: redirect,
            data: data,
            success: function(output){
                var new_url = newurl+output;
                document.getElementById("purchase_id").value  = output;
                window.history.replaceState('nextState', "Purchase Head", new_url);
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
    var purchase_id = document.getElementById("purchase_id").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"purchases/upload_purchase_process";
    let doc = document.getElementById("WESM_purchase").files[0];
    let formData = new FormData();
         
    formData.append("doc", doc);
    formData.append("purchase_id", purchase_id);
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
                document.getElementById("proceed_purchase").disabled = true;
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

function cancelPurchase(){
    var purchase_id = document.getElementById("purchase_id").value; 
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"purchases/cancel_purchase";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
        $.ajax({
            data: "purchase_id="+purchase_id,
            type: "POST",
            url: redirect,
            success: function(response){
                window.location=loc+'purchases/upload_purchases/';
            }
        });
    }
}

function saveAll(){
    var data = $("#upload_wesm").serialize();
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"purchases/save_all";
    var conf = confirm('Are you sure you want to save this Purchases?');
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
                window.location=loc+'purchases/upload_purchases/'+output;  
            }
        }); 
    }    
}

function saveBS(){
    var purchase_detail_id = document.getElementById("purchase_detail_id").value; 
    var serial_no = document.getElementById("serial_no").value; 
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"purchases/save_serialno";
    var conf = confirm('Are you sure you want to save this serial number?');
    if(conf){
        $.ajax({
            data: "purchase_detail_id="+purchase_detail_id+"&serial_no="+serial_no,
            type: "POST",
            url: redirect,
            success: function(output){
                if(serial_no!=''){
                    countPrint(loc,purchase_detail_id);
                    window.opener.location=loc+'purchases/print_BS/'+output;
                    window.close();
                }else{
                    alert("Please encode serial number!");
                }
            }
        });
    }
}

function filterPurchase(){
    var ref_no= document.getElementById("ref_no").value;
    var loc= document.getElementById("baseurl").value;
    window.location=loc+'purchases/purchases_wesm/'+ref_no;          
}


function payment_filter() {
	var ref_no= document.getElementById("reference_number").value;
    var loc= document.getElementById("baseurl").value;
    window.location=loc+'purchases/payment_list/'+ref_no; 
}

function savePayment(){
    var data = $("#paymentdata").serialize();
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"purchases/save_payment";
    var conf = confirm('Are you sure you want to save this payment?');
    if(conf){
        $.ajax({
            data: data,
            type: "POST",
            url: redirect,
            success: function(output){
                window.opener.location=loc+'purchases/payment_list/'+output;
                window.close();
            }
        });
    }
}

/*function calculatePayment(){
    var purchase_amount = document.getElementById("purchase_amount").value;
    var total_calculation = document.getElementById("total_calculation").value;
    var total = parseFloat(purchase_amount) + parseFloat(total_calculation);
    document.getElementById("total_amount").value  = parseFloat(total);
}*/

 var clicks = 0;

    function onClick() {
    clicks += 1;
    document.getElementById("clicks").innerHTML = '('+clicks+')';
};

 function checkRadio() {
      var x = document.getElementById("checkID");
      var y = document.getElementById("cashID");
      if (x.style.display === "none") {
        x.style.display = "block";
        y.style.display = "none";
      } else {
        y.style.display = "block";
        x.style.display = "none";
      }
    }
    function cashRadio() {
      var x = document.getElementById("cashID");
      var y = document.getElementById("checkID");
      if (x.style.display === "none") {
        x.style.display = "block";
        y.style.display = "none";
      } else {
        y.style.display = "block";
        x.style.display = "none";
      }
    }