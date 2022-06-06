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