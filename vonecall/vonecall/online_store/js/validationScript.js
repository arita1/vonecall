$(document).ready(function($) {
	
	$("#register_form").validate({
    	// Specify the validation rules
    	//email
        rules: {
        	first_name: {
            	required: true,
                minlength: 2
            },
            last_name: {
            	required: true,
                minlength: 2
            },
            /*phone: {
            	required: true,
                digits: true,
                minlength:10,
                maxlength:10
                
            },*/
            email: {
            	required: true,
                email: true
        	},
        	password: {
            	required: true,
                minlength: 6
            },
            confirm_password: {
            	required: true,
                minlength: 6,
                equalTo: "#password"
            },
            card_name: {
            	required: true,
            },
            card_number: {
            	required: true,
            	isNumeric: true
            },
            card_ccv: {
            	required: true,
            	isNumeric: true
            },
        	exp_year: {
            	required: true
            },
            exp_month: {
            	required: true
            },
            address: {
            	required: true
            },
            city: {
            	required: true
            },
            card_state: {
            	required: true
            },
            zip_code: {
            	required: true,
            	isNumeric: true
            }
        },
        
        // Specify the validation error messages
        messages: {
        	first_name: {
                required: "Please enter first name",
                  minlength: "Your username must be at least 2 characters long"
           },
            last_name: {
                required: "Please enter last name",
                  minlength: "Your last name must be at least 2 characters long"
            },
            /*phone: {
                required:  "Please enter phone number",
                digits: "phone number must be numeric",
                minlength: "Your phone number must be 10 digits",
                maxlength: "Your phone number must be 10 digits",
                
            },*/
            email: {
                required: "Please enter an email address",
                  email: "Please enter a valid email"
            },
            password: {
                required: "Please enter a password",
                minlength: "Your password must be at least 6 characters long"
            },
            confirm_password: {
                required: "Please enter a confirm password",
                  minlength: "Your password must be at least 6 characters long"
            },
            card_name: {
                required: "Please enter card number",
            },
            card_number: {
                required: "Please enter card number",
                isNumeric: "Card number must be numeric"
            },
            card_ccv: {
                required: "Please enter CCV code",
                isNumeric: "Card Ccv must be numeric"
            },
            exp_year: {
                required: "Please select year"
            },
            exp_month: {
                required: "Please select month"
            },
             address: {
            	 required: "Please enter address"
            },
            city: {
            	 required: "Please enter city name"
            },
            card_state: {
            	 required: "Please select state name"
            },
            zip_code: {
            	required: "Please enter zip code",
                isNumeric: "Zip code must be numeric"
            }
        },
        
        submitHandler: function(form) {
             if ($(form).valid()) 
                   form.submit(); 
               return false;
        }
	});
	
	//=====login form=============
		$("#new_login_form").validate({
    	// Specify the validation rules
    	//email
        rules: {
        
            email: {
            	required: true,
              
        	},
        	password: {
            	required: true,
                minlength: 6
           },
           
            
        },
        
        // Specify the validation error messages
        messages: {
        	
            email: {
                required: "Please enter an email/phone ",
                  email: "Please enter a valid email/phone"
            },
            password: {
                required: "Please enter a password",
                minlength: "Your password must be at least 6 characters long"
            }
           
        },
        
        submitHandler: function(form) {
             if ($(form).valid()) 
                   form.submit(); 
               return false;
        }
	});
	
	//=====update profile form=============
		$("#new_update_profile").validate({
    	// Specify the validation rules
    	//email
        rules: {
        
            new_email: {
            	required: true,
            	email: true
              
        	},
        	new_password: {
            	required: true,
                minlength: 6
           },
           first_name: {
            	required: true,
                minlength: 2
            },
            last_name: {
            	required: true,
                minlength: 2
            },
             address: {
            	required: true
            },
            city: {
            	required: true
            },
            card_state: {
            	required: true
            },
            zip_code: {
            	required: true,
            	isNumeric: true
            }
            
        },
        
        // Specify the validation error messages
        messages: {
        	
            new_email: {
                required: "Please enter an email address",
                  email: "Please enter a valid email"
            },
            new_password: {
                required: "Please enter a password",
                minlength: "Your password must be at least 6 characters long"
            },
            first_name: {
                required: "Please enter first name",
                  minlength: "Your username must be at least 2 characters long"
           },
            last_name: {
                required: "Please enter last name",
                  minlength: "Your last name must be at least 2 characters long"
            },
             address: {
            	 required: "Please enter address"
            },
            city: {
            	 required: "Please enter city name"
            },
            card_state: {
            	 required: "Please select state name"
            },
            zip_code: {
            	required: "Please enter zip code",
                isNumeric: "Zip code must be numeric"
            }
           
        },
        
        submitHandler: function(form) {
             if ($(form).valid()) 
                   form.submit(); 
               return false;
        }
	});
	
	

/*
accountName
my_card_type
billingName
billingAddress
billingCity
billingState
billingZip
billingCountry
agrrement
 */	
	
	//pinless form=========
	$("#pinless_form").validate({
	
    	// Specify the validation rules
    	//email
        rules: {
        	phone: {
            	required: true,
                digits: true,
                minlength:10,
                maxlength:10
                
           },
        	cardNumber: {
            	required: true,
                digits: true,
                minlength:16,
                maxlength:16
                
           },
           cardExpiry: {
            	required: true,
            	DateFormat: true
                
           },
           cardCVC: {
            	required: true,
            	digits: true,
                minlength:3,
                maxlength:3
                
           },
            amount: {
            	required: true,
            	digits: true,
            	greaterThan:25
           },
            accountName: {
            	required: true
                
           },
            my_card_type: {
            	required: true
                
           },
           my_cvv: {
            	required: true,
            	digits: true,
                minlength:3,
                maxlength:3
                
           },
            billingName: {
            	required: true
                
           },
			billingAddress: {
            	required: true
           },
            billingCity: {
            	required: true
            },
			billingState: {
            	required: true
            },
			billingZip: {
            	required: true
           },
		   billingCountry: {
            	required: true
           },
		   agrrement: {
            	required: true,
            }
        },
        
        // Specify the validation error messages
        
        messages: {
        	phone: {
                required:  "Please enter phone number",
                digits: "phone number must be numeric",
                minlength: "Your phone number must be 10 digits",
                maxlength: "Your phone number must be 10 digits",
                
           },
        	cardNumber: {
                required:  "Please enter card number",
                digits: "card number must be numeric",
                minlength: "Your card number must be 16 digits",
                maxlength: "Your card number must be 16 digits",
                
           },
           cardExpiry: {
                required:  "Please enter card expiry",
              DateFormat: "Please enter valid date format"
                
           },
           cardCVC: {
                required:  "Please enter card cvv",
                digits: "cvv number must be numeric",
                minlength: "cvv number must be 3 digits",
                maxlength: "cvv number must be 3 digits",
                
            },
           amount: {
                required:  "Please select amount",
                digits: "amount must be numeric",
                greaterThan:"Minimume $25 required!",
           },
            accountName: {
             required:  "This filed is required !",
           },
           
		  my_card_type: {
                required:  "Please card type",
           },
           my_cvv: {
                required:  "Please enter card cvv",
                digits: "cvv number must be numeric",
                minlength: "cvv number must be 3 digits",
                maxlength: "cvv number must be 3 digits",
                
            },
		  billingName: {
                required:  "This filed is required !",
           },
           
			billingAddress: {
                required:  "This filed is required !",
           },
           
			billingCity: {
               required:  "This filed is required !",
           },
           
			billingState: {
                required:  "This filed is required !",
           },
           
			billingZip: {
                required:  "This filed is required !",
           },
           
			billingCountry: {
                required:  "This filed is required !",
           },
           
			agrrement: {
               required:  "This filed is required !",
           }
        },
        
        submitHandler: function(form) {
             if ($(form).valid()) 
                   form.submit(); 
               return false;
        }
	});
//==========================
  $.validator.addMethod("DateFormat", function(value,element) {
  	// return value.match(/^(0[1-9]|1[012])[- //.](0[1-9]|[12][0-9]|3[01])[- //.](19|20)\d\d$/
        return value.match(/^(0[1-9]|1[012])[- //.](20|21)\d\d$/);
            },
                "Please enter a date in the format mm/dd/yyyy"
            );
    //======================================
}); 
