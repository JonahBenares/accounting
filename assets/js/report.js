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