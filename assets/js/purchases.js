function add_payment(baseurl,purchase_id,purchase_detail_id) {
    window.open(baseurl+"purchases/add_payment/"+purchase_id+'/'+purchase_detail_id, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
}
function pay_all(baseurl, id) {
    /*var classes= document.getElementsByClassName("total_amount");
    var parentText =classes[0].innerText;*/
    //alert(parentText);

    //var v = window.opener.classes;
    //alert(parentText);
   window.open(baseurl+"purchases/pay_all/"+id, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
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

function proceed_or_bulk() {
    var data = $("#orbulk").serialize();
    
    var loc= document.getElementById("baseurl").value;
    var purchase_id= document.getElementById("purchase_id").value;
    if(purchase_id==""){
        alert('Reference number must not be empty!');
    }  else {
    var redirect=loc+"purchases/or_bulk/";
    /*var conf = confirm('Are you sure you want to proceed?');
    if(conf){*/
        $.ajax({
            type: "POST",
            url: redirect,
            data: data,
            success: function(output){
                window.location=loc+'purchases/or_bulk/'+purchase_id;
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
    //}
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
                // document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
                document.getElementById("alt").style.display = 'block';  
                document.getElementById("alert_error").style.display = 'none';  
                document.getElementById("proceed_purchase").disabled = true;
                document.getElementById("cancel").disabled = true;
                $("#table-wesm").hide(); 
            },
            success: function(output){
                if(output=='error'){
                    // $("#alt").hide();
                    document.getElementById("alt").style.display = 'none';  
                    document.getElementById("WESM_purchase").value = '';
                    document.getElementById("proceed_purchase").disabled = false;
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

async function upload_or() {
    //var sales_doc = document.getElementById("WESM_sales").value;
    var purchase_id = document.getElementById("purchase_id").value;
    var identifier = document.getElementById("identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"purchases/upload_or_bulk";
    let doc = document.getElementById("or_bulk").files[0];
    let formData = new FormData();
         
    formData.append("doc", doc);
    formData.append("purchase_id", purchase_id);
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
              
                window.location=loc+'purchases/or_bulk/'+purchase_id+'/'+identifier;
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

function cancelBulkor(){
    var purchase_id = document.getElementById("purchase_id").value;
    var or_identifier = document.getElementById("or_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"purchases/cancel_bulk_or";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
        $.ajax({
            data: 'purchase_id='+purchase_id+'&or_identifier='+or_identifier,
            type: "POST",
            url: redirect,
            success: function(response){
                window.location=loc+'purchases/or_bulk/';
            }
        });
    }
}

function saveOR(){
    var data = $("#upload_bulkor").serialize();
    var purchase_id = document.getElementById("purchase_id").value;
    var or_identifier = document.getElementById("or_identifier").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"purchases/save_bulk_or";
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
                window.location=loc+'purchases/or_bulk/'+purchase_id+'/'+or_identifier;
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
                // document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
                document.getElementById("alt1").style.display = 'block'; 
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
    window.location=loc+'purchases/purchases_wesm/'+ref+'/'+due+'/null/null/null/'+billfrom+'/'+billto+'/'+parti;          
}

function filterPurchaseAdj(){
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
        var inex=in_ex_sub;
    }else{
        var inex='null';
    }

    if(participant!=''){
        var parti=participant;
    }else{
        var parti='null';
    }
    window.location=loc+'purchases/purchases_wesm_adjustment/'+ref+'/'+duefrom+'/'+dueto+'/'+inex+'/null/null/null/'+parti;          
}

function payment_filter() {
	var ref_no= document.getElementById("reference_number").value;
    var loc= document.getElementById("baseurl").value;
    window.location=loc+'purchases/payment_list/'+ref_no; 
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
    window.location=loc+'purchases/paid_list/'+ref+'/'+par+'/'+due; 
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


function savePaymentAll(){
    var data = $("#paymentdataall").serialize();
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"purchases/save_payment_all";
    var total_amount= parseFloat(document.getElementById("total_amount").value);
    var payment_amount= parseFloat(document.getElementById("payment_amount").value);
    var conf = confirm('Are you sure you want to save this payment?');

    if(conf){

        if(total_amount!=payment_amount){
            alert("Payment amount is not equal to total amount.");
        } else {
            $.ajax({
                data: data,
                type: "POST",
                url: redirect,
                success: function(output){
                   /* alert(output);*/
                    //window.opener.location=loc+'purchases/payment_list/'+output;
                    window.opener.location=loc+'purchases/payment_form/'+output;
                    window.close();
                }
            });
        }
    }
}


function calculatePayment(){
    var purchase_amount = document.getElementById("purchase_amount").value;
    var vat = document.getElementById("vat").value;
    var ewt = document.getElementById("ewt").value;

    /*if(vat!=0 && purchase_amount==0 && ewt==0){
        var total = parseFloat(vat);
    }else if(purchase_amount!=0 && vat==0 && ewt==0){
        var total = parseFloat(purchase_amount);
    }else if(purchase_amount!=0 && vat==0 && ewt!=0){
        var total = parseFloat(purchase_amount)-parseFloat(ewt);
    }else if(ewt!=0 && vat!=0 && purchase_amount==0){
        var total = parseFloat(vat)-parseFloat(ewt);
    }else if(purchase_amount!=0 && vat!=0 && ewt==0){
        var total = parseFloat(purchase_amount) + parseFloat(vat);
    }else if(purchase_amount!=0 && vat!=0 && ewt!=0){
        var total = (parseFloat(purchase_amount) + parseFloat(vat)) - ewt;
    }*/

    if(purchase_amount!=0){
        var pa=purchase_amount;
    }else{
        var pa=0;
    }

    if(vat!=0){
        var vt=vat;
    }else{
        var vt=0;
    }

    if(ewt!=0){
        var et=ewt;
    }else{
        var et=0;
    }
    var total = (parseFloat(pa) + parseFloat(vt)) - et;

    document.getElementById("total_amount").value  = parseFloat(total).toFixed(2);
}

function isNumberKey(txt, evt){
   var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46) {
        //Check if the text already contains the . character
        if (txt.value.indexOf('.') === -1) {
            return true;
        } else {
            return false;
        }
    } else {
        if (charCode > 31
             && (charCode < 48 || charCode > 57))
            return false;
    }
    return true;
}

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

function add_reference(){
    var loc= document.getElementById("baseurl").value;
    var redirect=loc+'purchases/getpayment';
    const dataList = document.getElementById("reference_number");
    const options = dataList.options;
    var reference_number =$('#reference_number').val();
    // var market_fee =$('#market_fee').val() ?? 0;
    // var ewt =$('#ewt').val() ?? 0;
    // Loop through the options to find the matching one
    // let purchase_id = 0;
    // var array = [];
    // for (let i = 0; i < options.length; i++) {
    //     const myarray = String(reference_number).split(",");
    //     alert(myarray[i])
    //     if (myarray[i]!=undefined && options[i].value === myarray[i]) {
    //         purchase_id = options[i].getAttribute("data-id");
    //         array.push(purchase_id)
    //     }
    // }

    var selectedPurchaseIds = []; // Array to store purchase IDs
    // Get all selected reference numbers from the Select2 dropdown
    const selectedReferenceNumbers = $('#reference_number').val(); // This returns an array of selected values

    if (selectedReferenceNumbers && selectedReferenceNumbers.length > 0) {
        for (let i = 0; i < options.length; i++) {
            // Check if the current option's value is in the selectedReferenceNumbers array
            if (selectedReferenceNumbers.includes(options[i].value)) {
                const purchase_id = options[i].getAttribute("data-id"); // Get purchase_id
                selectedPurchaseIds.push(purchase_id); // Add it to the result array
            }
        }
    }
    
    var rowCount = selectedReferenceNumbers.length;
    // var rowCount = $('#item_body tr').length;
    // count=rowCount+1;
    count=rowCount+1;
    $.ajax({
            type: "POST",
            url:redirect,
            data: 'purchase_id='+selectedPurchaseIds+'&reference_number='+reference_number,
            // data: 'purchase_id='+purchase_id+'&reference_number='+reference_number+'&market_fee='+market_fee+'&ewt='+ewt,
            beforeSend: function(){
                // console.log(selectedPurchaseIds)
                document.getElementById("addref").disabled = true;
            },
            success: function(html){
            document.getElementById("addref").disabled = false;

            // Always insert above manual row
            $('#item_body tr#row_template').before(html);

            var total = 0;
            $('.total_amount').each(function(){
                total += parseFloat($(this).val()) || 0;
            });

            $('#grand').html(total.toFixed(2));
            $('#payment_amount').val(total.toFixed(2));

            // Remove used options from dropdown
            let selectedValues = $("#reference_number").val() || [];
            selectedValues.forEach(value => {
                $("#reference_number option[value='" + value + "']").remove();
            });

            $("#reference_number").val(null).trigger("change");

            document.getElementById("counter").value = count;
        }
    });  
}

function remove_item(i,ref_no,purchase_id){
    var newOption = $('<option>', {
        value: ref_no,
        text: ref_no,
        'data-id': purchase_id
    });
    $('#reference_number').append(newOption).trigger('change');

    $('#item_row'+i).remove();
    var counter = document.getElementById("counter").value;
    // alert(counter)
    document.getElementById("counter").value = counter-1 
    var total =0;
    $('.total_amount').each(function(){
        total += parseFloat($(this).val());
    });
    document.getElementById("grand").innerHTML=total.toFixed(2);
    document.getElementById("payment_amount").value=total.toFixed(2);
}

function calculateMarketFee() {
    var total = 0;

    // Loop through all table rows inside #item_body
    $("#item_body tr").each(function() {
        var row = $(this);

        var market_fee_input = row.find("input[name='market_fee[]']");
        var withholding_tax_input = row.find("input[name='withholding_tax[]']");
        var total_amount_input = row.find("input[name='total_amount[]']");

        // Only compute if this row has editable fields (manual reference)
        if (market_fee_input.length && !market_fee_input.prop("readonly")) {
            var market_fee = parseFloat(market_fee_input.val()) || 0;
            var withholding_tax = parseFloat(withholding_tax_input.val()) || 0;

            // Example total: you can modify the formula if different
            var total_row = market_fee - withholding_tax;

            total_amount_input.val(total_row.toFixed(2));
        }

        // Sum all totals (including dropdown reference rows)
        var row_total = parseFloat(total_amount_input.val()) || 0;
        total += row_total;
    });

    // Update grand total
    $("#grand").html(total.toFixed(2));
    $("#payment_amount").val(total.toFixed(2));
}

function savePaymentall(){
    var loc = document.getElementById("baseurl").value;
    var conf = confirm('Are you sure you want to save this record?');
    if(!conf) return;

    // ✅ Reindex all manual inputs before serializing
    $("#item_body tr").each(function(i){
        $(this).find("input[name='manual_reference[]']").attr("name", "manual_reference[" + i + "]");
        $(this).find("input[name='market_fee[]']").attr("name", "market_fee[" + i + "]");
        $(this).find("input[name='withholding_tax[]']").attr("name", "withholding_tax[" + i + "]");
        $(this).find("input[name='total_amount[]']").attr("name", "total_amount[" + i + "]");
        $(this).find("input[name='total_vatable_purchase[]']").attr("name", "total_vatable_purchase[" + i + "]");
        $(this).find("input[name='total_vat[]']").attr("name", "total_vat[" + i + "]");
        $(this).find("input[name='total_ewt[]']").attr("name", "total_ewt[" + i + "]");
        $(this).find("input[name='purchase_id[]']").attr("name", "purchase_id[" + i + "]");
    });

    var req = $("#Paymentfrm").serialize();
    var redirect = loc+'purchases/save_payment_all';

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
            alert("Successfully Saved!");
            location.reload();
            window.open(loc+'purchases/payment_form/'+output, '_blank');
        }
    });
}

/*function downloadbulk2307(baseurl,refno){
    var redirect = baseurl+"purchases/download_bulk";
    $.ajax({
        type: "POST",
        url: redirect,
        data: 'refno='+refno,
        success: function(output){
         

        }
    });
}*/

/*<script> document.getElementsByClassName("button_click")[0].click();</script>*/
function getDownload(){
    //var x = document.getElementById("canvas_div_pdf");
     for(var i =0;i<400;i++){
     var x = document.getElementById("printableArea"+i);
    /*var HTML_Width = $(".canvas_div_pdf").width();
    var HTML_Height = $(".canvas_div_pdf").height();*/

    var HTML_Width = $("#printableArea"+i).width();
    var HTML_Height = $("#printableArea"+i).height();


    var top_left_margin = 10;
    var PDF_Width = HTML_Width+(top_left_margin*2);
    var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
    var canvas_image_width = HTML_Width;
    var canvas_image_height = HTML_Height;
    var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;
   
   
        //alert(i);
        /*var shortname=document.getElementsByClassName('shortname')[i].value;
        var refno=document.getElementsByClassName('ref_no')[i].value;
        var billing_month=document.getElementsByClassName('billing_month')[i].value;
        var timestamp=document.getElementsByClassName('timestamp')[i].value;*/


        var shortname=document.getElementById('shortname'+i).value;
       
        var refno=document.getElementById('ref_no'+i).value;
        var billing_month=document.getElementById('billing_month'+i).value;
        var timestamp=document.getElementById('timestamp'+i).value;

        
        html2canvas($(".canvas_div_pdf"+i)[0],{allowTaint:true, 
            useCORS: true,
            logging: false,
            height: window.outerHeight + window.innerHeight,
            windowHeight: window.outerHeight + window.innerHeight}).then(function(canvas) {
            canvas.getContext('2d');
            var imgData = canvas.toDataURL("image/jpeg", 1.0);
            var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
            pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
           // for (var x = 1; x <=totalPDFPages; x++) { 
                //pdf.fromHTML(output);
                pdf.addPage(PDF_Width, PDF_Height);
                pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
           // }
             console.log(shortname);
             //pdf.save("BIR2307 CENPRI.pdf");
             pdf.save("BIR2307 CENPRI "+shortname+" "+refno+" "+billing_month+" "+timestamp+".pdf");
            
        });
    }
    
}

  function getPDF(content){


        var HTML_Width = $(".canvas_div_pdf").width();
        
        var HTML_Height = $(".canvas_div_pdf").height();

      
        var top_left_margin = 10;
        var PDF_Width = HTML_Width+(top_left_margin*2);
        var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
        var canvas_image_width = HTML_Width;
        var canvas_image_height = HTML_Height;
        
        var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;
        

       html2canvas($(".canvas_div_pdf")[0],{allowTaint:true, 
            useCORS: true,
            logging: false,
            height: window.outerHeight + window.innerHeight,
            windowHeight: window.outerHeight + window.innerHeight}).then(function(canvas) {
            canvas.getContext('2d');
     
            
            var imgData = canvas.toDataURL("image/jpeg", 1.0);
            var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
            pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
            
            
            for (var i = 1; i <= totalPDFPages; i++) { 
                pdf.addPage(PDF_Width, PDF_Height);
                pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
            }
            

          
            pdf.save("BIR2307 CENPRI.pdf");


        });

            //pdf.save("BIR2307 CENPRI "+shortname+" "+refno+" "+billing_month+" "+timestamp+".pdf");
    }

async function upload_adjust_btn() {
    var count_file = document.getElementById("count").value;
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"purchases/upload_purchase_adjust";
    var conf = confirm('Are you sure you want to upload this file?');
    if(conf){
        var form = document.querySelector('#uploadadjust');
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
                //document.getElementById("cancel").disabled = true;
                //$("#table-wesm").hide(); 
            },
            success: function(output){
                //alert(output);
                $("#alt").hide(); 
                window.location=loc+'purchases/upload_purchases_adjustment/'+output;
            }
        });
        
    }
}

 function saveAlladjust(){
    var loc= document.getElementById("baseurl").value;
    var saveadjust_identifier= document.getElementById("saveadjust_identifier").value;
    var redirect = loc+"purchases/save_alladjust";
    var conf = confirm('Are you sure you want to save this Purchases?');
    if(conf){
        $.ajax({
            data: 'adjust_identifier='+saveadjust_identifier,
            type: "POST",
            url: redirect,
            beforeSend: function(){
                // document.getElementById('alt').innerHTML='<b>Please wait, Saving Data...</b>'; 
                document.getElementById("alt1").style.display = 'block';  
                $("#submitdata").hide(); 
            },
            success: function(output){
                $("#alt").hide();
                window.location=loc+'purchases/upload_purchases_adjustment/'+output;  
            }
        }); 
    }    
}

function cancelmultiplePurchase(){
    var saveadjust_identifier = document.getElementById("saveadjust_identifier").value; 
    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"purchases/cancel_multiple_purchase";
    var conf = confirm('Are you sure you want to cancel this transaction?');
    if(conf){
        $.ajax({
            data: "saveadjust_identifier="+saveadjust_identifier,
            type: "POST",
            url: redirect,
            success: function(response){
                window.location=loc+'purchases/upload_purchases_adjustment/';
            }
        });
    }
}

function filterDue(){
    var due_date= document.getElementById("due_date").value;
    var loc= document.getElementById("baseurl").value;
    if(due_date!=''){
        var due=due_date;
    }else{
        var due='null';
    }
    window.location=loc+'purchases/payment_list/'+due;          
}

function updatePurchases(baseurl,count,purchase_detail_id,purchase_id,billing_id){
    var redirect = baseurl+"purchases/update_details";
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
		data: 'purchase_detail_id='+purchase_detail_id+'&purchase_id='+purchase_id+'&billing_id='+billing_id+'&or_no='+or_no+'&total_update='+total_update+'&original_copy='+original_copy+'&scanned_copy='+scanned_copy,
        dataType: "json",
		success: function(response){
			document.getElementById("or_no"+count).value=response.or_no;
			document.getElementById("total_update"+count).value=response.total_update;
		}
	});
}

function filterPurchases(){
    var ref_no= document.getElementById("reference_no").value;
    var due_date= document.getElementById("due_datefilt").value;
    var or_no= document.getElementById("or_no").value;
    // if(or_no!='-'){
    //     var or_filt=or_no;
    // }else if(or_no=='-'){
    //     var or_filt='^';
    // }
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
    window.location=loc+'purchases/purchases_wesm/'+ref+"/"+due+"/"+or+"/"+orig+"/"+scanned;          
}

function filterPurchasesAdj(){
    var ref_no= document.getElementById("reference_no").value;
    var due_date= document.getElementById("due_datefilt").value;
    var or_no= document.getElementById("or_no").value;
    var in_ex_sub= document.getElementById("in_ex_subfilt").value;
    // if(or_no!='-'){
    //     var or_filt=or_no;
    // }else if(or_no=='-'){
    //     var or_filt='^';
    // }
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

    if(in_ex_sub!='null'){
        var inex=in_ex_sub;
    }else{
        var inex='null';
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
    window.location=loc+'purchases/purchases_wesm_adjustment/'+ref+"/"+due+"/"+inex+"/"+or+"/"+orig+"/"+scanned;          
}