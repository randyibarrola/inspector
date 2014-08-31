var Login = function () {
    
    return {
        //main function to initiate the module
        init: function () {
           

            $("input").keypress(function(event) {
                if (event.which == 13) {
                    event.preventDefault();
                    $(login_form).submit();
                }
            });

 
            
           $('.login-form').validate({
	            errorElement: 'label', //default input error message container
	            errorClass: 'help-inline', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            rules: {
	                _username: {
	                    required: true
	                },
	                _password: {
	                    required: true
	                },
	                _remember_me: {
	                    required: false
	                }
	            },

	            messages: {
	                _username: {
	                    required: "Username requerido."
	                },
	                _password: {
	                    required: "Password requerido."
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   
	                $('.alert-error', $('.login-form')).show();
	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.control-group').addClass('error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.control-group').removeClass('error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                error.addClass('help-small no-left-padding').insertAfter(element.closest('.input-icon'));
	            }
	        });
        }

    };

}();