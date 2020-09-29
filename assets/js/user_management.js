const base_url='';
$("#close_user_div").click(function() {
    var validator = $("#add_user_form").validate();

    validator.resetForm();
    document.getElementById("add_user_form").reset();
    $('#user_section').slideToggle();
    $("#email_id").prop("readonly", false);
    $("#mobile_no").prop("readonly", false);
});
$("#search_role_type").change(function() {
    var role_type = $("#search_role_type").val();
    load_user_table(role_type);


});

$("#start_date").change(function () {
    newtoday = document.getElementById("start_date").value;
    var today = new Date().toISOString().split('T')[0];
    document.getElementById("end_date").setAttribute('min', newtoday);

});
function fetch_user_data(user_id) {
    var validator = $("#add_user_form").validate();

    validator.resetForm();
    document.getElementById("add_user_form").reset();
    // $.LoadingOverlay("show");
    $.ajax({
        type: "POST",
        url:base_url+'fetch_user_data',

        data: {user_id: user_id},
        dataType: "json",
        success: function (result) {
            // $.LoadingOverlay("hide");
            var user_data = result.body;
            if (result.status === true) {

                var element=document.getElementById('role_type');
                element.scrollIntoView(false);
                $('#user_section').slideDown();
                $('#f_name').val(user_data.first_name);
                $('#l_name').val(user_data.last_name);
                $('#role_type').val(user_data.role_type);
                $('#mobile_no').val(user_data.mobile_no);
                $('#address').val(user_data.address);
                $('#update_user_id').val(user_data.user_id);
                $('#email_id').val(user_data.email);

                $("#email_id").rules("remove", "uniqueUserEmail");
                $("#mobile_no").rules("remove", "mobile");
                $("#email_id").prop("readonly", true);
                $("#mobile_no").prop("readonly", true);
            } else {

            }

        }, error: function (error) {
            //$.LoadingOverlay("hide");
            toastr.error('Something went wrong please try again');
        }
    });


}



function delete_user_data(user_id) {

    // $.LoadingOverlay("show");
    $.ajax({
        type: "POST",
        url:base_url+'delete_user_data',

        data: {user_id: user_id},
        dataType: "json",
        success: function (result) {

            if (result.status === true) {
                load_user_table();
                toastr.success(result.body);
            } else {
                toastr.info(result.body);
            }

        }, error: function (error) {
            //$.LoadingOverlay("hide");
            toastr.error('Something went wrong please try again');
        }
    });


}


$(document).ready(function () {
    load_user_table();
    $('#user_div').click(function (){
        var validator = $("#add_user_form").validate();

        validator.resetForm();
        document.getElementById("add_user_form").reset();
        $('#update_user_id').val(0);
        $('#user_section').slideToggle();
        $("#email_id").prop("readonly", false);
        $("#mobile_no").prop("readonly", false);
        $('#email_id').rules("add", {
            uniqueUserEmail: true

        });

        $('#mobile_no').rules("add", {
            mobile: true

        });



    });

    $.validator.addMethod("uniqueUserEmail", function (value, element) {
        $.ajax({url:base_url+'email_validation',
            data: {email: value},
            type: "post",
            dataType: "json",
            async: false,
            success: function (msg) {
                console.log(msg);
                isSuccess = msg === true ? true : false;
            }
        });
        console.log(isSuccess);
        return isSuccess;
    }, "Email Id already exists");

    $.validator.addMethod("mobile", function (value, element) {
        $.ajax({url:base_url+'mobile_validation',
            data: {mobile_no: value},
            type: "post",
            dataType: "json",
            async: false,
            success: function (msg) {
                console.log(msg);
                isSuccess = msg === true ? true : false;
            }
        });
        console.log(isSuccess);
        return isSuccess;
    }, "Mobile no  already exists");

    $.validator.addMethod("validMobile", function (value, element) {
        return mobileValidation(value);
    }, "Enter valid Mobile");






    $("#add_user_form").validate({
        rules: {
            f_name: {
                required: true,

            },
            l_name: {
                required: true,

            },
            mobile_no: {
                required: true,
                mobile: true,
                validMobile: true,
                minlength: 10,
                maxlength: 10

            },
            email_id: {
                required: true,
                email:true,
                uniqueUserEmail: true,

            },
            address: {
                required: true,
            },





        },
        messages: {

            f_name: {
                required: "Please Enter First name",
            },
            l_name: {
                required: "Please Enter Last name",
            },
            mobile_no: {
                required: "Please Enter mobile_no",
            },
            email_id: {
                required: "Please Enter Email id",
                email: "Please Enter valid Email id",
            },

            address: {
                required: "Please Enter Address",
            },


        },
        errorElement: 'span',
        submitHandler: function (form) {

            $.ajax({
                url:base_url+'add_user',

                type: "POST",
                dataType: "json",
                data: $("#add_user_form").serialize(),
                success: function (success) {

                    if (success.status === true) {
                        toastr.success(success.body);

                        $("#add_user_form").trigger("reset");
                        $('#user_section').slideToggle();
                        load_user_table();
                    } else {

                        toastr.error(success.body);
                    }
                },
                error: function (error) {
                    //                    $('#item_modal').modal("toggle");
                    toastr.error("something went to wrong");
                }
            });

        }
    });



    $("#fetch_date_wise_result").validate({
        rules: {
            start_date: {
                required: true,

            },
            end_date: {
                required: true,

            },






        },
        messages: {

            start_date: {
                required: "Please Select Start date",
            },
            end_date: {
                required: "Please Select  End date",
            },



        },
        errorElement: 'span',
        submitHandler: function (form) {


            load_user_table('',$('#start_date').val(),$('#end_date').val())
        }
    });

});

function mobileValidation(value) {
    if (/^[0-9 ()+]+$/.test(value)) {
        return true;
    } else {
        return false;
    }
}

function load_user_table(role_type='',start_date='',end_date='') {//done by vishu  to get stock data

    $("#user_table").DataTable({
        destroy: true,
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url":base_url+'load_user_table',
            "type": "POST",
            data: function (a_data) {
                a_data.role_type = role_type;
                a_data.start_date = start_date;
                a_data.end_date = end_date;

            }
        },


        columns: [
            {data: 0}, {data: 1}, {data: 2},{data: 3},{data: 4},

            {
                data: 5,
                render: (dataValue, type, row, meta) => {

                    return '<div class="buttons"><button type="button" class="btn btn-icon btn-primary"  onclick="fetch_user_data(`'+row[5]+'`)"><i class="fa fa-edit"></i></button><button type="button" class="btn btn-icon btn-danger" onclick="delete_user_data(`'+row[5]+'`)"><i class="fa fa-trash"></i></button></div>';

                }
            },

        ]


    }).columns.adjust().responsive.recalc();;
}
