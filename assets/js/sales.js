function add_details(baseurl) {
    window.open(baseurl+"sales/add_details/", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50,left=350,width=700,height=600");
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

 var clicks = 0;

    function onClick() {
    clicks += 1;
    document.getElementById("clicks").innerHTML = '('+clicks+')';
};