"use strict";

$("[data-checkboxes]").each(function () {
  var me = $(this),
    group = me.data('checkboxes'),
    role = me.data('checkbox-role');

  me.change(function () {
    var all = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"])'),
      checked = $('[data-checkboxes="' + group + '"]:not([data-checkbox-role="dad"]):checked'),
      dad = $('[data-checkboxes="' + group + '"][data-checkbox-role="dad"]'),
      total = all.length,
      checked_length = checked.length;

    if (role == 'dad') {
      if (me.is(':checked')) {
        all.prop('checked', true);
      } else {
        all.prop('checked', false);
      }
    } else {
      if (checked_length >= total) {
        dad.prop('checked', true);
      } else {
        dad.prop('checked', false);
      }
    }
  });
});
$("#table-1").dataTable({
  order: [[2, 'asc']],
});
// $("#table-1").dataTable({
//   order: [[2, 'asc']],
// });
$("#table-2").dataTable({
  "columnDefs": [
    { "sortable": false, "targets": [0, 0] }
  ],
  order: [[1, "asc"]] //column indexes is zero based

});
$("#tables").dataTable({
  "columnDefs": [
    { "sortable": false, "targets": [0, 0] }
  ],
});
$("#table-3").dataTable({
  // "scrollX": true,
  // "ordering": true,
  "columnDefs": [
    { "width": "1%", "targets": 1 }
  ]
});
$("#table-4").dataTable({
  "scrollX": true,
});
$("#table-5").dataTable({
  "columnDefs": [
    { "sortable": false, "targets": [0,2] }
  ],
  order: [[1, "asc"]] //column indexes is zero based

});

$('#save-stage').DataTable({
  "scrollX": true,
  stateSave: true
});


$("#table-6").dataTable({
  // order: [[2, 'asc']],
  "scrollX": true,
  
});

$("#table-7").dataTable({
  order: [[1, "asc"]] //column indexes is zero based

});

$("#table-8").dataTable({
  order: [[1, 'asc']],
  "scrollX": true,
});


var table = $('#example').DataTable( {
    scrollX:        true,
    scrollCollapse: true,
    fixedColumns:   {
        left: 0,
        right: 1
    }
  } );
$('#tableExport').DataTable({
  dom: 'Bfrtip',
  buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
  ]
});

// $("#table-4").dataTable({
//   order: [[1, 'asc']],
// });
