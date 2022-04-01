var clicksBS = 0;
function add_details_BS(baseurl) {
    window.open(baseurl+"sales/add_details_BS/", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
    clicksBS += 1;
	document.getElementById("clicksBS").innerHTML = '('+clicksBS+')';
}
function add_details_wesm(baseurl) {
    window.open(baseurl+"sales/add_details_wesm/", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
}
var clicksOR = 0;
function add_details_OR(baseurl) {
    window.open(baseurl+"sales/add_details_OR/", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
    clicksOR += 1;
	document.getElementById("clicksOR").innerHTML = '('+clicksOR+')';
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

function collection_filter() {
	var x = document.getElementById("collection-list");
		if (x.style.display === "none") {
			x.style.display = "block";
	} else {
		x.style.display = "none";
	}
}


 //    function onClick() {
	    
	// };