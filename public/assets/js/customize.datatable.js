$(function() {

    // var oTable = $('#base_table').DataTable({
    //     "oLanguage": {
    //         "sSearch": "Filter Data"
    //     },
    //     "iDisplayLength": -1,
    //     "sPaginationType": "full_numbers",
    //
    //
    // });
    var oTable = $('#list-table').DataTable({
        aaSorting: [[0, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                //text: 'Export Search Results',
                className: '',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'csv',
                //text: 'Export Search Results',
                //className: '',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'pdf',
                //text: 'Export Search Results',
                //className: '',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'print',
                //text: 'Export Search Results',
                //className: '',
                exportOptions: {
                    columns: 'th:not(:last-child)'
                }
            },

        ]
    } );


    $("#datepicker_from").datepicker({
        //showOn: "button",
        //buttonImage:  "http://ams.test/assets/images/calender-icon.gif",
        // buttonImageOnly: false,
        "onSelect": function(date) {
            minDateFilter = new Date(date).getTime();
            oTable.draw();
        }
    }).keyup(function() {
        minDateFilter = new Date(this.value).getTime();
        oTable.draw();
    });

    $("#datepicker_to").datepicker({
        //showOn: "button",
        // buttonImage: "http://ams.test/assets/images/calender-icon.gif",
        // buttonImageOnly: false,
        "onSelect": function(date) {
            maxDateFilter = new Date(date).getTime();
            oTable.draw();
        }
    }).keyup(function() {
        maxDateFilter = new Date(this.value).getTime();
        oTable.draw();
    });


});


