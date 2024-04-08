function saveAll(){
    var data = $("#upload_wesm").serialize();
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"reserve/save_all";
    var conf = confirm('Are you sure you want to save this Reserve?');
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
                window.location=loc+'reserve/upload_reserve/'+output;  
            }
        }); 
    }    
}

async function upload_btn() {
    //var sales_doc = document.getElementById("WESM_sales").value;
    var reserve_id = document.getElementById("reserve_id").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"reserve/upload_reserve_process";
    let doc = document.getElementById("WESM_reserve").files[0];
    let formData = new FormData();
         
    formData.append("doc", doc);
    formData.append("reserve_id", reserve_id);
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
                document.getElementById("proceed_reserve").disabled = true;
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

function proceed_btn() {
    var data = $("#reservehead").serialize();
   
   var loc= document.getElementById("baseurl").value;
   var redirect = loc+"reserve/add_reserve_head";
   var newurl=loc+"reserve/upload_reserve/";
   var conf = confirm('Are you sure you want to proceed?');
   if(conf){
       $.ajax({
           type: "POST",
           url: redirect,
           data: data,
           success: function(output){
               var new_url = newurl+output;
               document.getElementById("reserve_id").value  = output;
               window.history.replaceState('nextState', "Reserve Head", new_url);
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

function cancelReserve(){
    var reserve_id = document.getElementById("reserve_id").value; 
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"reserve/cancel_reserve";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
        $.ajax({
            data: "reserve_id="+reserve_id,
            type: "POST",
            url: redirect,
            success: function(response){
                window.location=loc+'reserve/upload_reserve/';
            }
        });
    }
}

function countPrint(baseurl,reserve_detail_id){
    var redirect = baseurl+"reserve/count_print";
    $.ajax({
        data: "reserve_detail_id="+reserve_detail_id,
        type: "POST",
        url: redirect,
        success: function(output){
            location.reload();
        }
    });
}

function filterDue(){
    var due_date= document.getElementById("due_date").value;
    var loc= document.getElementById("baseurl").value;
    if(due_date!=''){
        var due=due_date;
    }else{
        var due='null';
    }
    window.location=loc+'reserve/payment_reserve_list/'+due;          
}

function add_reference(){
    var loc= document.getElementById("baseurl").value;
    var redirect=loc+'reserve/getpayment';
    var reference_number =$('#reference_number').val();
    var rowCount = $('#item_body tr').length;
    count=rowCount+1;
    $.ajax({
            type: "POST",
            url:redirect,
            data: "reference_number="+reference_number,
            success: function(html){
            $('#item_body').append(html);

            var total =0;
            $('.total_amount').each(function(){
              total += parseFloat($(this).val());
            });
            document.getElementById("grand").innerHTML=total.toLocaleString();
            document.getElementById("payment_amount").value=total.toFixed(2);
            $("#reference_number option[value='"+reference_number+"']").remove();
            //internationalNumberFormat = new Intl.NumberFormat('en-US')
            //document.getElementById("grand").innerHTML=internationalNumberFormat.format(total);
            document.getElementById("reference_number").value = '';
            document.getElementById("counter").value = count;
        }
    });  
}

function savePayment(){
    var req = $("#Paymentfrm").serialize();
    var loc= document.getElementById("baseurl").value;
    var conf = confirm('Are you sure you want to save this record?');
    if(conf==true){
        var redirect = loc+'reserve/save_payment_all';
    }else {
        var redirect = '';
    }
    $.ajax({
        type: "POST",
        url: redirect,
        data: req,
        beforeSend: function(){
            document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
            document.getElementById("pay").disabled = true;
            $('#reference_number').hide();
        },
        success: function(output){
            document.getElementById("pay").disabled = false;
            $('#reference_number').show();
            if(conf==true){
                alert("Successfully Saved!");
                location.reload();
                window.open(loc+'reserve/payment_reserve_form/'+output, '_blank');
            }
        }
    });
}

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

function paid_filter() {
    var ref_no= document.getElementById("reference_number").value;
    var participant= document.getElementById("participant").value;
    var due_date= document.getElementById("due_date").value;
    var loc= document.getElementById("baseurl").value;
    if(ref_no!=''){
        var ref=ref_no;
    }else{
        var ref='null';
    }

    if(participant!=''){
        var par=participant;
    }else{
        var par='null';
    }

    if(due_date!=''){
        var due=due_date;
    }else{
        var due='null';
    }
    window.location=loc+'reserve/paid_reserve_list/'+ref+'/'+par+'/'+due; 
}

function proceed_or_bulk() {
    var data = $("#orbulk").serialize();
    var loc= document.getElementById("baseurl").value;
    var reserve_id= document.getElementById("reserve_id").value;
    if(reserve_id==""){
        alert('Reference number must not be empty!');
    }  else {
    var redirect=loc+"reserve/or_reserve_bulk/";
        $.ajax({
            type: "POST",
            url: redirect,
            data: data,
            success: function(output){
                window.location=loc+'reserve/or_reserve_bulk/'+reserve_id;
                var redirect = redirect+output;
                var save = document.getElementById("save_or_button");
                var cancel = document.getElementById("cancel_or");
                document.getElementById('ref_no').readOnly = true;
                var x = document.getElementById("upload_or");
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

function cancelBulkor(){
    var reserve_id = document.getElementById("reserve_id").value;
    var or_identifier = document.getElementById("or_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"reserve/cancel_reserve_bulk_or";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
        $.ajax({
            data: 'reserve_id='+reserve_id+'&or_identifier='+or_identifier,
            type: "POST",
            url: redirect,
            success: function(response){
                window.location=loc+'reserve/or_reserve_bulk/';
            }
        });
    }
}

async function upload_or() {
    var reserve_id = document.getElementById("reserve_id").value;
    var identifier = document.getElementById("identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"reserve/upload_reserve_or_bulk";
    let doc = document.getElementById("or_bulk").files[0];
    let formData = new FormData();
    formData.append("doc", doc);
    formData.append("reserve_id", reserve_id);
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
                document.getElementById("proceed_or").disabled = true;
                document.getElementById("cancel_or").disabled = true;
                $("#table-or").hide(); 
            },
            success: function(output){
                $("#alt").hide(); 
                window.location=loc+'reserve/or_reserve_bulk/'+reserve_id+'/'+identifier;
            }
        });
    }
}

function saveOR(){
    var data = $("#upload_bulkor").serialize();
    var reserve_id = document.getElementById("reserve_id").value;
    var or_identifier = document.getElementById("or_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"reserve/save_reserve_bulk_or";
    var conf = confirm('Are you sure you want to save this Bulk OR?');
    if(conf){
        $.ajax({
            data: data,
            type: "POST",
            url: redirect,
            beforeSend: function(){
                document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
                $("#submitor").hide(); 
            },
            success: function(output){
                window.location=loc+'reserve/or_reserve_bulk/'+reserve_id+'/'+or_identifier;
            }
        });
    }
}

function filterReserve(){
    var ref_no= document.getElementById("ref_no").value;
    var due_date= document.getElementById("due_date").value;
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
    window.location=loc+'reserve/reserve_wesm/'+ref+'/'+due+'/null/null/null/'+billfrom+'/'+billto+'/'+parti;          
}

function filterReserveOr(){
    var ref_no= document.getElementById("reference_no").value;
    var due_date= document.getElementById("due_datefilt").value;
    var billing_from= document.getElementById("billing_from").value;
    var billing_to= document.getElementById("billing_to").value;
    var participants= document.getElementById("participants").value;
    var or_no= document.getElementById("or_no").value;
    var original_yes= document.getElementById("original_yes");
    var original_no= document.getElementById("original_no");
    var scanned_yes= document.getElementById("scanned_yes");
    var scanned_no= document.getElementById("scanned_no");

    var loc= document.getElementById("base_url").value;
    if(ref_no!='null'){
        var ref=ref_no;
    }else{
        var ref='null';
    }

    if(due_date!='null'){
        var due=due_date;
    }else{
        var due='null';
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

    if(participants!=''){
        var parti=participants;
    }else{
        var parti='null';
    }

    if(or_no!='^' && or_no!='-'){
        var or=or_no;
    }else if(or_no=='-'){ 
        var or="^";
    }else{
        var or='null';
    }

    if(original_yes.checked){
        var orig=1;
    }else if(original_no.checked){
        var orig=0;
    }else{
        var orig='null';
    }

    if(scanned_yes.checked){
        var scanned=1;
    }else if(scanned_no.checked){
        var scanned=0;
    }else{
        var scanned='null';
    }
    window.location=loc+'reserve/reserve_wesm/'+ref+"/"+due+"/"+or+"/"+orig+"/"+scanned+"/"+billfrom+"/"+billto+"/"+parti;          
}

function updateReserve(baseurl,count,reserve_detail_id,reserve_id,billing_id){
    var redirect = baseurl+"reserve/update_reserve_details";
    var or_no=document.getElementById("or_no"+count).value;
    var total_update=document.getElementById("total_update"+count).value;
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
		data: 'reserve_detail_id='+reserve_detail_id+'&reserve_id='+reserve_id+'&billing_id='+billing_id+'&or_no='+or_no+'&total_update='+total_update+'&original_copy='+original_copy+'&scanned_copy='+scanned_copy,
        dataType: "json",
		success: function(response){
			document.getElementById("or_no"+count).value=response.or_no;
			document.getElementById("total_update"+count).value=response.total_update;
		}
	});
}