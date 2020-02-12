$(function() {
    $('.js-datepicker').datepicker({  maxDate: 0 });

    $('.js-datepicker1').datepicker({
        format: 'yyyy-mm-dd', maxDate: 0
    });
});

// Delete Contra Transaction
$('.confirmationDelete').on('click', function (e) {
    e.preventDefault();
    href = $(this).attr('href');
    return bootbox.confirm('Are you sure?', function(result) {
        if (result) {
            window.location = href
        }
    });
});
//  Wastage Title

function makeWastage(id, changePath, actionStatus) {

    $.ajax({
        type: "POST",
        url: changePath,
        async: true,
        data: { id : id ,actionStatus: actionStatus},
        success: function (data) {
            //console.log(data['formHtml']);
             console.log(data);
            let innerHTML = '';
            // Set H5 Title
            $("#exampleModalLabel").html('Action Panel');
            $('#transactionContent').html(data['formHtml']);
        }
    });
    $('#showTransactionDetails').addClass('make-wastage')
    $('#showTransactionDetails').modal('show');
}