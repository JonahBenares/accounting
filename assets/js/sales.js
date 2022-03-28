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