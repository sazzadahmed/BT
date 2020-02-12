var g_SparePartDetail;
var g_addMoreCount = 1;
var g_tireCount = 0;
var g_tireAvailable = undefined;
var selectedTire = [];
$('#entry_details_spareParts').change(() => {
    g_tireCount = 0;
    $('#add_more_panel').empty();
    g_addMoreCount = 1;
    let val = $('#entry_details_spareParts').val();
    let responseData;
    let selectedText = $( "#entry_details_spareParts option:selected" ).text();
    if(selectedText == 'Tire' && $('#entry_details_car').val())
    {
        getCarSpecificTire();
    }

    $.ajax({
        url:'/spare/parts/detail/json',
        type: "POST",
        data: {
            id:val,
        },
        async: true,
        success: function (data)
        {
            let sparePart = data;
            g_SparePartDetail = data;
            // console.log(data);

            if(data.numOrQty ==1)
            {
                // $('#add_more_spare_part').show();
                $('#quantity_L_Particle').hide();
            }
            else
            {
                $('#add_more_spare_part').hide();
                $('#quantity_L_Particle').show();
            }

            if(data.isTire == '1')
            {

                $('#tire_control_panel').show();
            }
            else
            {
                $('#tire_control_panel').hide();
            }

        }
    });

});

$('#entry_details_car').change(() => {
    g_tireCount = 0;
    $('#add_more_panel').empty();
    g_addMoreCount = 1;
    let selectedText = $( "#entry_details_spareParts option:selected" ).text();
    if(selectedText == 'Tire' && $('#entry_details_car').val())
    {
        getCarSpecificTire();
    }
});

$('#add_more_spare_part').click(() =>{

    let car = $('#entry_details_car').val();
    let sparePart = $('#entry_details_spareParts').val();
    let quantity = $('#entry_details_qty').val();


    if(!(car && sparePart && quantity))
    {
        return;
    }


    let quantityCount = Number(quantity);
    let sizeOfContainer = $("[ id ^='main_container_']").length;
    if(quantityCount == sizeOfContainer+2)
    {
        $('#add_more_spare_part').hide();
    }

    g_addMoreCount = g_addMoreCount + 1;
    // Add new collecton type form to the form


    let formString = '<div class="col-md-12">' +
        '<h4>'+g_addMoreCount+'</h4>'+
        '</div>'+
        ' <div class="col-md-3">\n' +
        '                    <div class="control-group row" >\n' +
        '                        <div class="controls col-md-12">\n' +
        '                            <label for="entry_details_price___name__" class="required">Price</label>' +
        '                        </div>\n' +
        '                        <div class="col-md-12">\n' +
        '                            <input type="text" id="entry_details_price___name__" name="entry_details[price___name__]" required="required" class="span11">' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                </div>';


    if(g_SparePartDetail && g_SparePartDetail.isTire == '1'){
        formString = formString + "<div class=\"col-md-3\">\n" +
            "                    <div class=\"control-group row\" >\n" +
            "                        <div class=\"controls col-md-12\">\n" +
            "                         <label for=\"entry_details_tirePosition___name__\" class=\"required\">Tire Position</label>  " +
            "                        </div>\n" +
            "                        <div class=\"col-md-12\">\n" +
            "                         <select class='tire_change_cls' onchange='changeSelectOption(this)' id=\"entry_details_tirePosition___name__\" required =\"required\"name=\"entry_details[tirePosition___name__]\"></select>   " +
            "                        </div>\n" +
            "                    </div>\n" +
            "                </div>";
    }


    formString = formString + "                <div class=\"col-md-6\">\n" +
        "                    <div class=\"control-group row\" >\n" +
        "                        <div class=\"controls col-md-12\">\n" +
        "                            <label for=\"entry_details_partsDescription___name__\" class=\"required\">Description</label>" +
        "                        </div>\n" +
        "                        <div class=\"col-md-12\">\n" +
        "                          <textarea id=\"entry_details_partsDescription___name__\" name=\"entry_details[partsDescription___name__]\" required=\"required\" class=\"span8\" colspan=\"10\" rows=\"4\"></textarea>" +
        "                        </div>\n" +
        "                    </div>\n" +
        "                </div>";




    formString ='<div class=\"row\" style=\"border: 1px solid #DDDDDD\" id ="main_container___name__">' +formString + ' <i id="times___name__" class="fas fa-times" style=\"position: absolute;\n' +
        '    right: 6px;\n' +
        '    color: red;" onclick="closeThisPanel(__name__)"></i></div>';




    formString=  formString.replace(/__name__/g,g_addMoreCount);


    $('#add_more_panel').append(formString);
    let selectedText = $( "#entry_details_spareParts option:selected" ).text();
    if(selectedText == 'Tire' )
    {
        selecttireOption()
    }




});

function closeThisPanel(id) {
    $('#main_container_'+id).remove();
    $('#add_more_spare_part').show();

}

function getCarSpecificTire() {
    let car = $('#entry_details_car').val();
    let tire = $('#entry_details_spareParts').val();

    $.ajax({
        url:'/spare/parts/car/tire/json',
        type: "POST",
        data: {
            car:car,
            tire:tire
        },
        async: true,
        success: function (data)
        {
            g_tireCount = data.length;
            g_tireAvailable = data;
            checkTireOrNot();
        }
    });

}

$('#entry_details_qty').keyup((e) => {
    $('#add_more_panel').empty();

    if(g_SparePartDetail.numOrQty != 1) return;

    $('#add_more_spare_part').show();
    g_addMoreCount = 1;
    let id = e.target.id;
    let val = $('#'+id).val();
    if(!val){
        $('#add_more_spare_part').hide();
    }

    if(val == "1")
    {
        $('#add_more_spare_part').hide();
    }

    let lastChar = val.substr(val.length - 1);
   if(isNaN(lastChar)){
       $('#entry_details_qty').val(val.substr(0,val.length-1));
       alert('Plz insert a valid value');
   }
   else
   {
       let selectedText = $( "#entry_details_spareParts option:selected" ).text();
       if(selectedText == 'Tire') {
           if(Number(val)> (7- g_tireCount)){
               alert('Max '+ (7-g_tireCount) + 'Tire can be inserted');
               $('#entry_details_qty').val(val.substr(0,val.length-1));
           }
           selecttireOption();
       }
   }
});

function selecttireOption()
{
    let optionData = '<option value="">Select Tire</option>';
    let avilable = [];
    for(let j=1;j<=7;j++){
   {
        let flag = false;
        if(!g_tireAvailable) return;
       for(let i = 0; i<g_tireAvailable.length; i = i+1){
           if(j== g_tireAvailable[i])
            {
                flag = true;
                break;
            }
        }
        if(!flag)
        {
            avilable.push(j);
        }
    }
    }

   let adMoreData = $('[id ^="entry_details_tirePosition"]');


        let options =  '<option disabled ="disabled">Selected</option>';

        for(let j =0;j<avilable.length;j++){
            options = options + '<option value="'+avilable[j]+'">'+avilable[j]+' Position</option>';
        }

        for(let j=adMoreData.length-1; true; j++) {
            $('#'+adMoreData[j].id).html(options);
            break;
          }
}

function changeSelectOption(dt) {

}

function checkTireOrNot() {
    let car = $('#entry_details_car').val();
    let tire  = $( "#entry_details_spareParts option:selected" ).text();
    if(car && tire =='Tire'){
        selecttireOption();
    }

}

$(document).ready(() => {
    getCarSpecificTire();
    let pathList = window.location.href.split('/');
    let sz = pathList.length -2;
    let id = pathList[sz];
    if( $( "#entry_details_spareParts option:selected" ).text() == 'Tire'){
        $('#tire_control_panel').show();
    }

    if('edit'  == pathList[sz+1] && 'details' == pathList[sz-1] && 'entry' == pathList[sz-2]){
        $.ajax({
            url:'/entry/details/sparepart/json',
            type: "POST",
            data: {
                id:id
            },
            async: true,
            success: function (data)
            {
                console.log(data);
               let selectOption = $('#entry_details_tirePosition').html();
               selectOption = selectOption + '<option selected ="selected" value="'+data.tirePosition+'">'+ data.tirePosition+' Position</option>';
                $('#entry_details_tirePosition').html(selectOption);
            }
        });
    }


});



