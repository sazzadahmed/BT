$(function() {
        var oTable = $('.data-table').DataTable();
        var gIncomeExpenseFilter = 'select';
        var gCashBankFilter = 'select';

            $('select#cash_filter').change( function() {
                    gIncomeExpenseFilter = $('select#cash_filter').val();
                    oTable.fnDraw();
                });

            $('select#income_expense').change( function() {
                    gIncomeExpenseFilter = $('select#income_expense').val();
                    oTable.fnDraw();
                });

            $("#datepicker_from").datepicker({
                    showOn: "button",
                buttonImage:  "http://ams.test/assets/images/calender-icon.gif",
               buttonImageOnly: false,
                "onSelect": function(date) {
                    minDateFilter = new Date(date).getTime();
                    oTable.fnDraw();
                }
        }).keyup(function() {
                minDateFilter = new Date(this.value).getTime();
                oTable.fnDraw();
            });

            $("#datepicker_to").datepicker({
                    showOn: "button",
                buttonImage: "http://ams.test/assets/images/calender-icon.gif",
                buttonImageOnly: false,
               "onSelect": function(date) {
                    maxDateFilter = new Date(date).getTime();
                    oTable.fnDraw();
                }
        }).keyup(function() {
                maxDateFilter = new Date(this.value).getTime();
                oTable.fnDraw();
            });

        });

    $('#car_table_all thead tr').clone(true).appendTo( '#car_table_all thead' );
$('#car_table_all thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        if(title != 'actions') {
                $(this).html('<input type="text"  style="width:150px;" placeholder="Search ' + title + '" />');
            }

            $( 'input', this ).on( 'keyup change', function () {
                    if ( table.column(i).search() !== this.value ) {
                            table
                                .column(i)
                                .search( this.value )
                                .draw();
                        }
                } );
    } );

    var table = $('#car_table_all').DataTable( {
        orderCellsTop: true,
        fixedHeader: true
} );
// Date range filter
    minDateFilter = "";
    maxDateFilter = "";

    $.fn.dataTableExt.afnFiltering.push(
            function(oSettings, aData, iDataIndex) {

                        if(oSettings.nTable.id == 'base_table'){
                            aData._date = new Date(aData[1]).getTime();
                            aData.cashorBand = $('select#cash_filter').val();
                            aData.incomeExpense = $('select#income_expense').val();

                                if (minDateFilter && !isNaN(minDateFilter)) {
                                    if (aData._date < minDateFilter) {
                                            return false;
                                        }
                                }
                            if (maxDateFilter && !isNaN(maxDateFilter)) {
                                    if (aData._date > maxDateFilter) {
                                            return false;
                                        }
                                }
                            if(aData.cashorBand != 'select' && aData.cashorBand!=aData[2])
                                {
                                    return false;
                            }
                           if(aData.incomeExpense != 'select' && aData.incomeExpense!=aData[3])
                               {
                                    return false;
                           }
                        }
                    return true;
                }
        );