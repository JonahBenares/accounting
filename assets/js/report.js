function filterEwt(){
    var ref_no= document.getElementById("ref_no").value;
    var participant= document.getElementById("participant").value;

    if(ref_no!=''){
    	ref=ref_no;
    }else{
    	ref='null';
    }

    if(participant!=''){
    	part=participant;
    }else{
    	part='null';
    }
    var loc= document.getElementById("baseurl").value;
    window.location=loc+'reports/ewt_summary/'+ref+'/'+part;          
}

function filterCreditable(){
    var ref_no= document.getElementById("ref_no").value;
    var participant= document.getElementById("participant").value;

    if(ref_no!=''){
    	ref=ref_no;
    }else{
    	ref='null';
    }

    if(participant!=''){
    	part=participant;
    }else{
    	part='null';
    }
    var loc= document.getElementById("baseurl").value;
    window.location=loc+'reports/cwht_summary/'+ref+'/'+part;          
}

function filterLedger(){
    var ref_no= document.getElementById("ref_no").value;
    var date_from= document.getElementById("date_from").value;
    var date_to= document.getElementById("date_to").value;
    var loc= document.getElementById("baseurl").value;

    if(ref_no!=''){
        var ref=ref_no;
    }else{
        var ref='null';
    }


    if(date_from){
        var from=date_from;
    }else{
        var from='null';
    }


    if(date_to){
        var to=date_to;
    }else{
        var to='null';
    }
    window.location=loc+'reports/sales_ledger/'+ref+'/'+from+'/'+to;          
}

function filter_sales() {
     var ref_no = document.getElementById("ref_no").value; 
     var participant = document.getElementById("participant").value; 
     var from = document.getElementById("from").value; 
     var to = document.getElementById("to").value; 

    if(ref_no!=''){
        ref=ref_no;
    }else{
        ref='null';
    }

    if(participant!=''){
        part=participant;
    }else{
        part='null';
    }

    if(from!=''){
        from=from;
    }else{
        from='null';
    }

    if(to!=''){
        to=to;
    }else{
        to='null';
    }

      var loc= document.getElementById("baseurl").value;
      window.location=loc+'reports/sales_summary/'+ref+'/'+part+'/'+from+'/'+to;

}

function filter_purchases() {
     var ref_no = document.getElementById("ref_no").value; 
     var participant = document.getElementById("participant").value; 
     var from = document.getElementById("from").value; 
     var to = document.getElementById("to").value; 

    if(ref_no!=''){
        ref=ref_no;
    }else{
        ref='null';
    }

    if(participant!=''){
        part=participant;
    }else{
        part='null';
    }

    if(from!=''){
        from=from;
    }else{
        from='null';
    }

    if(to!=''){
        to=to;
    }else{
        to='null';
    }

      var loc= document.getElementById("baseurl").value;
      window.location=loc+'reports/purchases_summary/'+ref+'/'+part+'/'+from+'/'+to;

}

function filter_purchasesledger(){
    var ref_no= document.getElementById("ref_no").value;
    var date_from= document.getElementById("date_from").value;
    var date_to= document.getElementById("date_to").value;
    var loc= document.getElementById("baseurl").value;

    if(ref_no!=''){
        var ref=ref_no;
    }else{
        var ref='null';
    }


    if(date_from){
        var from=date_from;
    }else{
        var from='null';
    }


    if(date_to){
        var to=date_to;
    }else{
        var to='null';
    }
    window.location=loc+'reports/purchases_ledger/'+ref+'/'+from+'/'+to;          
}

function filterCSLedger(){
    var participant= document.getElementById("participant").value;
    var date_from= document.getElementById("date_from").value;
    var date_to= document.getElementById("date_to").value;
    var loc= document.getElementById("baseurl").value;

    if(participant!=''){
        var part=participant;
    }else{
        var part='null';
    }


    if(date_from){
        var from=date_from;
    }else{
        var from='null';
    }


    if(date_to){
        var to=date_to;
    }else{
        var to='null';
    }
    window.location=loc+'reports/cs_ledger/'+part+'/'+from+'/'+to;          
}

function filterSSLedger(){
    var participant= document.getElementById("participant").value;
    var date_from= document.getElementById("date_from").value;
    var date_to= document.getElementById("date_to").value;
    var loc= document.getElementById("baseurl").value;

    if(participant!=''){
        var part=participant;
    }else{
        var part='null';
    }


    if(date_from){
        var from=date_from;
    }else{
        var from='null';
    }


    if(date_to){
        var to=date_to;
    }else{
        var to='null';
    }
    window.location=loc+'reports/ss_ledger/'+part+'/'+from+'/'+to;          
}

function filterOR(){
    var participant= document.getElementById("participant").value;
    var from= document.getElementById("date_from").value;
    var to= document.getElementById("date_to").value;
    var loc= document.getElementById("baseurl").value;

    if(participant!=''){
        var part=participant;
    }else{
        var part='null';
    }


    if(from){
        var from=from;
    }else{
        var from='null';
    }


    if(to){
        var to=to;
    }else{
        var to='null';
    }
    window.location=loc+'reports/or_summary/'+part+'/'+from+'/'+to;          
}

function cancelOR(baseurl,or_no,participant,date_from,date_to){
    var redirect = baseurl+"reports/cancel_or";
    var conf = confirm('Are you sure you want to cancel this OR?');
    if(conf){
            if(participant!=''){
                var participant=participant;
            }else{
                var participant='null';
            }


            if(date_from){
                var date_from=date_from;
            }else{
                var date_from='null';
            }


            if(date_to){
                var date_to=date_to;
            }else{
                var date_to='null';
            }
        $.ajax({
            data: "or_no="+or_no+"&participant="+participant+"&date_from="+date_from+"&date_to="+date_to,
            type: "POST",
            url: redirect,
            success: function(output){
                alert('Successfully cancelled the OR.');
                window.location=baseurl+'reports/or_summary/'+participant+'/'+date_from+'/'+date_to;
            }
        });
    }
}

function ignoreOR(baseurl,or_no,participant,date_from,date_to){
    var redirect = baseurl+"reports/ignore_or";
    var conf = confirm('Are you sure you want to ignore this OR?');
    if(conf){
            if(participant!=''){
                var participant=participant;
            }else{
                var participant='null';
            }


            if(date_from){
                var date_from=date_from;
            }else{
                var date_from='null';
            }


            if(date_to){
                var date_to=date_to;
            }else{
                var date_to='null';
            }
        $.ajax({
            data: "or_no="+or_no+"&participant="+participant+"&date_from="+date_from+"&date_to="+date_to,
            type: "POST",
            url: redirect,
            success: function(output){
                alert('Successfully ignored the OR.');
                window.location=baseurl+'reports/or_summary/'+participant+'/'+date_from+'/'+date_to;
            }
        });
    }
}

function filter_adjusted_sales(){
    var due_date= document.getElementById("due_date").value;
    var loc= document.getElementById("baseurl").value;
    window.location=loc+'reports/adjustment_sales/'+due_date; 
}

function adjustment_purchases_filter() {
    var due_date= document.getElementById("due_date").value;
    var loc= document.getElementById("baseurl").value;
    window.location=loc+'reports/adjustment_purchases/'+due_date; 
}

function filter_payment_form(){
    var payment_date= document.getElementById("payment_date").value;
    var loc= document.getElementById("baseurl").value;
    window.location=loc+'reports/payment_report/'+payment_date; 
}

function filter_sales_all() { 
     var from = document.getElementById("from").value; 
     var to = document.getElementById("to").value;
     var participant = document.getElementById("participant").value;
     var original = document.getElementById("og_copy").value;
     var scanned = document.getElementById("s_copy").value;

    if(from!=''){
        from=from;
    }else{
        from='null';
    }

    if(to!=''){
        to=to;
    }else{
        to='null';
    }

    if(participant!=''){
        part=participant;
    }else{
        part='null';
    }

    if(original!=''){
        original=original;
    }else{
        original='null';
    }

    if(scanned!=''){
        scanned=scanned;
    }else{
        scanned='null';
    }

      var loc= document.getElementById("baseurl").value;
      window.location=loc+'reports/sales_all/'+part+'/'+from+'/'+to+'/'+original+'/'+scanned;

}

function export_sales_all() { 
     var from = document.getElementById("from").value; 
     var to = document.getElementById("to").value;
     var participant = document.getElementById("participant1").value;

    if(from!=''){
        from=from;
    }else{
        from='null';
    }

    if(to!=''){
        to=to;
    }else{
        to='null';
    }

    if(participant!=''){
        part=participant;
    }else{
        part='null';
    }

      var loc= document.getElementById("baseurl").value;
      window.location=loc+'reports/export_sales_all/'+part+'/'+from+'/'+to;

}

function filter_purchases_all() { 
     var from = document.getElementById("from").value; 
     var to = document.getElementById("to").value;
     var participant = document.getElementById("participant").value;
     var original = document.getElementById("og_copy").value;
     var scanned = document.getElementById("s_copy").value;

    if(from!=''){
        from=from;
    }else{
        from='null';
    }

    if(to!=''){
        to=to;
    }else{
        to='null';
    }

    if(participant!=''){
        part=participant;
    }else{
        part='null';
    }

    if(original!=''){
        original=original;
    }else{
        original='null';
    }

    if(scanned!=''){
        scanned=scanned;
    }else{
        scanned='null';
    }

      var loc= document.getElementById("baseurl").value;
      window.location=loc+'reports/purchases_all/'+part+'/'+from+'/'+to+'/'+original+'/'+scanned;

}

function filter_sales_adjustment_all() { 
     var from = document.getElementById("from").value; 
     var to = document.getElementById("to").value;
     var participant = document.getElementById("participant").value;
     var original = document.getElementById("og_copy").value;
     var scanned = document.getElementById("s_copy").value;

    if(from!=''){
        from=from;
    }else{
        from='null';
    }

    if(to!=''){
        to=to;
    }else{
        to='null';
    }

    if(participant!=''){
        part=participant;
    }else{
        part='null';
    }

    if(original!=''){
        original=original;
    }else{
        original='null';
    }

    if(scanned!=''){
        scanned=scanned;
    }else{
        scanned='null';
    }

      var loc= document.getElementById("baseurl").value;
      window.location=loc+'reports/sales_all_adjustment/'+part+'/'+from+'/'+to+'/'+original+'/'+scanned;

}

function filter_purchases_adjustment_all() { 
     var from = document.getElementById("from").value; 
     var to = document.getElementById("to").value;
     var participant = document.getElementById("participant").value;
     var original = document.getElementById("og_copy").value;
     var scanned = document.getElementById("s_copy").value;

    if(from!=''){
        from=from;
    }else{
        from='null';
    }

    if(to!=''){
        to=to;
    }else{
        to='null';
    }

    if(participant!=''){
        part=participant;
    }else{
        part='null';
    }

    if(original!=''){
        original=original;
    }else{
        original='null';
    }

    if(scanned!=''){
        scanned=scanned;
    }else{
        scanned='null';
    }

      var loc= document.getElementById("baseurl").value;
      window.location=loc+'reports/purchases_all_adjustment/'+part+'/'+from+'/'+to+'/'+original+'/'+scanned;

}