function saveCustomers(){
    var data = $("#CustomerHead").serialize();

    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"masterfile/save_customer";
    var conf = confirm('Are you sure you want to save to this Customer?');
   
     if(conf){
         $.ajax({
            data: data,
            type: "POST",
            url: redirect,
            success: function(output){
                if(output == 'error'){
                    alert("Duplicate unique Billing ID.");
                } else {
                   alert("Successfully saved!");
                window.location=loc+'masterfile/customer_view/'+output;
                }
            
        

            }

        });
           
    }
}

function UpdateCustomer(){
    var data = $("#CustomerHead").serialize();

    var loc= document.getElementById("baseurl").value;
    var redirect = loc+"masterfile/edit_customer";
    var conf = confirm('Are you sure you want to update this Customer?');
   
     if(conf){
         $.ajax({
            data: data,
            type: "POST",
            url: redirect,
            success: function(output){
               // alert(output);
            //   alert("Successfully udpated!");
            //   window.location=loc+'masterfile/customer_list/';

              if(output == 'error'){
                alert("Duplicate unique Billing ID.");
            } else {
               alert("Successfully updated!");
            window.location=loc+'masterfile/customer_view/'+output;
            }

              //location.reload(true);

            }

        });
           
    }
}
function confirmationDelete(anchor){
     var conf = confirm('Are you sure you want to delete this record?');
     if(conf)
     window.location=anchor.attr("href");
}

function subparticipant(baseurl, id) {
        window.open(baseurl+"index.php/masterfile/add_sub_participant/"+id, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=25,width=500,height=500");
}
