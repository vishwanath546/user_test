const base_url='';

$(document).ready(function () {
    $("#login_form").validate({

        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
            },

        },
        messages: {
            email: {
                required: "Please Enter Email",
            },
            password: {
                required: "Please Enter Password",
            },

        },
        errorElement: 'span',
        submitHandler: function (form) {

            $.ajax({
                url:base_url+'login_validation',
                type: "POST",
                dataType: "json",
                data: $("#login_form").serialize(),
                success: function (result) {

                    if (result.status == 200) {

                       // window.location=window.location.protocol+'user_form';

                    } else {
                        toastr.info(result.body);
                    }

                }, error: function (error) {

                    toastr.error("Something went wrong please try again");
                }

            });
        }
    });
});