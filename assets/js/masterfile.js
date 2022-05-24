function saveCustomer(){
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
               // alert(output);
              alert("Successfully saved!");
              window.location=loc+'masterfile/customer_view/'+output;
              //location.reload(true);

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
              alert("Successfully udpated!");
              window.location=loc+'masterfile/customer_list/';
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
