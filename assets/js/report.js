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
    var from= document.getElementById("date_from").value;
    var to= document.getElementById("date_to").value;
    var participant= document.getElementById("participant").value;
    var loc= document.getElementById("baseurl").value;

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

    if(participant!=''){
        var part=participant;
    }else{
        var part='null';
    }
    window.location=loc+'reports/or_summary/'+from+'/'+to+'/'+part;          
}

function cancelOR(baseurl,or_no,date_from,date_to,participant){
    var redirect = baseurl+"reports/cancel_or";
    var conf = confirm('Are you sure you want to cancel this OR?');
    if(conf){

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

            if(participant!=''){
                var participant=participant;
            }else{
                var participant='null';
            }
        $.ajax({
            data: "or_no="+or_no+"&date_from="+date_from+"&date_to="+date_to+"&participant="+participant,
            type: "POST",
            url: redirect,
            success: function(output){
                alert('Successfully cancelled the OR.');
                window.location=baseurl+'reports/or_summary/'+date_from+'/'+date_to+'/'+participant;
            }
        });
    }
}

function ignoreOR(baseurl,or_no,date_from,date_to,participant){
    var redirect = baseurl+"reports/ignore_or";
    var conf = confirm('Are you sure you want to ignore this OR?');
    if(conf){
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

            if(participant!=''){
                var participant=participant;
            }else{
                var participant='null';
            }
        $.ajax({
            data: "or_no="+or_no+"&date_from="+date_from+"&date_to="+date_to+"&participant="+participant,
            type: "POST",
            url: redirect,
            success: function(output){
                alert('Successfully ignored the OR.');
                window.location=baseurl+'reports/or_summary/'+date_from+'/'+date_to+'/'+participant;
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

function export_salesall() { 
     var e_from = document.getElementById("export_from").value; 
     var e_to = document.getElementById("export_to").value;
     var participant = document.getElementById("participant1").value;

    if(e_from!=''){
        e_from=e_from;
    }else{
        e_from='null';
    }

    if(e_to!=''){
        e_to=e_to;
    }else{
        e_to='null';
    }

    if(participant!=''){
        part=participant;
    }else{
        part='null';
    }

      var loc= document.getElementById("baseurl").value;
      window.location=loc+'reports/export_sales_all/'+part+'/'+e_from+'/'+e_to;

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

function export_purchasesall() { 
     var e_from = document.getElementById("export_from").value; 
     var e_to = document.getElementById("export_to").value;
     var participant = document.getElementById("participant1").value;

    if(e_from!=''){
        e_from=e_from;
    }else{
        e_from='null';
    }

    if(e_to!=''){
        e_to=e_to;
    }else{
        e_to='null';
    }

    if(participant!=''){
        part=participant;
    }else{
        part='null';
    }

      var loc= document.getElementById("baseurl").value;
      window.location=loc+'reports/export_purchases_all/'+part+'/'+e_from+'/'+e_to;

}

function filter_sales_adjustment_all() { 
     var from = document.getElementById("from").value; 
     var to = document.getElementById("to").value;
     var due = document.getElementById("due_date").value;
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

    if(due!=''){
        due=due;
    }else{
        due='null';
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
      window.location=loc+'reports/sales_all_adjustment/'+part+'/'+from+'/'+to+'/'+original+'/'+scanned+'/'+due;

}

function export_sales_adjustment_all() { 
     var e_from = document.getElementById("export_from").value; 
     var e_to = document.getElementById("export_to").value;
     var participant = document.getElementById("participant1").value;
     var e_due = document.getElementById("due_date1").value;

    if(e_from!=''){
        e_from=e_from;
    }else{
        e_from='null';
    }

    if(e_to!=''){
        e_to=e_to;
    }else{
        e_to='null';
    }

    if(participant!=''){
        part=participant;
    }else{
        part='null';
    }

    if(e_due!=''){
        e_due=e_due;
    }else{
        e_due='null';
    }

      var loc= document.getElementById("baseurl").value;
      window.location=loc+'reports/export_sales_adjustment_all/'+part+'/'+e_from+'/'+e_to+'/'+e_due;

}

function filter_purchases_adjustment_all() { 
     var from = document.getElementById("from").value; 
     var to = document.getElementById("to").value;
     var due = document.getElementById("due_date").value;
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

    if(due!=''){
        due=due;
    }else{
        due='null';
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
      window.location=loc+'reports/purchases_all_adjustment/'+part+'/'+from+'/'+to+'/'+original+'/'+scanned+'/'+due;

}

function export_purchases_adjustment_all() { 
     var e_from = document.getElementById("export_from").value; 
     var e_to = document.getElementById("export_to").value;
     var participant = document.getElementById("participant1").value;
     var e_due = document.getElementById("due_date1").value;

    if(e_from!=''){
        e_from=e_from;
    }else{
        e_from='null';
    }

    if(e_to!=''){
        e_to=e_to;
    }else{
        e_to='null';
    }

    if(participant!=''){
        part=participant;
    }else{
        part='null';
    }

    if(e_due!=''){
        e_due=e_due;
    }else{
        e_due='null';
    }

      var loc= document.getElementById("baseurl").value;
      window.location=loc+'reports/export_purchases_adjustment_all/'+part+'/'+e_from+'/'+e_to+'/'+e_due;

}

function filter_collection() { 
     var collection_date = document.getElementById("collection_date").value; 
     var reference_no = document.getElementById("reference_no").value;
     var settlement_id = document.getElementById("settlement_id").value;

    if(collection_date!=''){
        collection_date=collection_date;
    }else{
        collection_date='null';
    }

    if(reference_no!=''){
        reference_no=reference_no;
    }else{
        reference_no='null';
    }

    if(settlement_id!=''){
        settlement_id=settlement_id;
    }else{
        settlement_id='null';
    }


      var loc= document.getElementById("baseurl").value;
      window.location=loc+'reports/collection_report/'+collection_date+'/'+reference_no+'/'+settlement_id;

}