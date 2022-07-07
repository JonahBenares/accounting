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

function paid_filter() {
    var ref_no= document.getElementById("reference_number").value;
    var participant= document.getElementById("participant").value;
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
    window.location=loc+'purchases/paid_list/'+ref+'/'+par; 
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
            document.getElementById("grand").innerHTML=total.toFixed(2);
            document.getElementById("payment_amount").value=total.toFixed(2);
            $("#reference_number option[value='"+reference_number+"']").remove();
            //internationalNumberFormat = new Intl.NumberFormat('en-US')
            //document.getElementById("grand").innerHTML=internationalNumberFormat.format(total);
            document.getElementById("reference_number").value = '';
            document.getElementById("counter").value = count;
        }
    });  
}

function savePaymentall(){
    var req = $("#Paymentfrm").serialize();
    var loc= document.getElementById("baseurl").value;
    //var redirect = loc+'index.php/request/insertRequest';
    var conf = confirm('Are you sure you want to save this record?');
    if(conf==true){
        var redirect = loc+'purchases/save_payment_all';
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
                //document.getElementById("reference_number").disabled = true;
            },
            success: function(output){
                document.getElementById("pay").disabled = false;
                $('#reference_number').show();
                //alert(output);
                //var conf = confirm('Are you sure you want to save this record?');
                if(conf==true){
                    alert("Successfully Saved!");
                    location.reload();
                    window.open(loc+'purchases/payment_form/'+output, '_blank');
                    //window.location=loc+'purchases/payment_form/'+output;;
                }
            }
      });
}

function downloadbulk2307(baseurl,refno){
    var redirect = baseurl+"purchases/download_bulk";
    $.ajax({
        type: "POST",
        url: redirect,
        data: 'refno='+refno,
        success: function(output){
          
            var HTML_Width = $(output+".canvas_div_pdf").width();
   alert(HTML_Width);
    /*contents = output.find(".canvas_div_pdf").first();
    console.log(contents.height());*/
    //alert(contents.height());
  
      

           // getPDF(output.innerHTML);
        }
    });
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
