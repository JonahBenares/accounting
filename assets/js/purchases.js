function add_payment(baseurl) {
    window.open(baseurl+"purchases/add_payment/", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
}
function add_details_wesm(baseurl) {
    window.open(baseurl+"purchases/add_details_wesm/", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
}

function proceed_btn() {
	var x = document.getElementById("upload");
		if (x.style.display === "none") {
			x.style.display = "block";
	} else {
		x.style.display = "none";
	}
}

function upload_btn() {
	var x = document.getElementById("table-wesm");
		if (x.style.display === "none") {
			x.style.display = "block";
	} else {
		x.style.display = "none";
	}
}

function payment_filter() {
	var x = document.getElementById("payment-list");
		if (x.style.display === "none") {
			x.style.display = "block";
	} else {
		x.style.display = "none";
	}
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