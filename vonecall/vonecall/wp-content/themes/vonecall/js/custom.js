var URL = Site_url.url + '/';
var pathname = window.location;
$(document).ready(function() {
     // get the url
   
    var formdata; // to get all form data in it
    var $root = $('html, body');
    var getTabName = localStorage.getItem('tab');
    var gethref = localStorage.getItem('href');

/**********get calender ***************/
   $(".datepicker").datepicker({ 
        autoclose: true, 
        todayHighlight: true,
        format: 'dd-mm-yyyy'
  }).datepicker('update', new Date());

    $('#expiration-date').datepicker( {
        autoclose: true,
        minViewMode: 1,
        format: 'mm-yyyy'
    }).on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('.to').datepicker('setStartDate', startDate);
    }); 

/*** scroll bar view ************/
var nice = $(".scrollbar").niceScroll();  

$(".scrollbar").mouseover(function() {
    $(".scrollbar").getNiceScroll().resize();
});

//    $(".scrollbar").niceScroll({cursorborder:"#0099ff ",cursorcolor:"#0099ff ",boxzoom:true});
//   $( window ).resize(function() {
//       $('.scrollbar').getNiceScroll().resize()
// });

$('.textwidget').find('li').css('list-style','none');

   /**** get session tabs*********/
    
    if ((getTabName == 'Rates' || getTabName == 'Access Numbers') && (gethref == URL || gethref == '#rates-success')) {


        if (getTabName == 'Rates') {
            $('#access , #1b').removeClass('active');
            $('#rates , #2b ').addClass('active');
        } else if (getTabName == 'Access Numbers') {
            $('#access , #1b').addClass('active');
            $('#rates , #2b').removeClass('active');
        }

        $root.animate({
            scrollTop: $(gethref).offset().top - 75
        }, 1000, function() {
            window.location.hash = gethref;
        });
        localStorage.removeItem('href', '');
        localStorage.removeItem('tab', '');
    }

    // show registeration tab on register click

    if (pathname == URL + 'customer/#register') {

        $('#log , #tab_default_1').removeClass('active');

        $('#reg , #tab_default_2 ').addClass('active');
    }

    if (pathname == URL + 'dashboard/') {

       // $('.login-menu').hide();

    }

    /************set hide show saved card **********/
    $('#select-card').on('change', function() {
  if(this.value == "0") {
    $('#hide-card-detail').show();
  } else {
    $('#hide-card-detail').hide();
  }
});


/**************show more content *************/

  var showChar = 104;  // How many characters are shown by default
    var ellipsestext = "...";
    var moretext = "Show more";
    var lesstext = "Show less";
    

    $('.more').each(function() {
        var content = $(this).html();

        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
 
            $(this).html(html);
        }
 
    });
 
    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
$("#checkbox-1-4 , #checkbox-1-5").change(function() {
    if(this.checked) {
      $(this).val(1);
    }else{
        $(this).val(0);
    }
});
    /**********get current timezone ****************/

    var visitortime = new Date();
    var visitortimezone = -visitortime.getTimezoneOffset();
    $.ajax({
        type: "POST",
        url: MBAjax.ajaxurl,
        data: 'time=' + visitortimezone + "&action=timezone",
        success: function(response) {
            console.log(response);
        }
    });




/************get store session ************/
                    $.ajax({
                                type: "POST",
                                url: MBAjax.ajaxurl,
                                data: "action=get_store",
                                success: function(response) {
                                    // if(response == 0){
                                    //     location.reload();
                                    // }
                                }
                            });

    /**** script to change header on scroll ************/
    $(document).scroll(function() {
        var fromTop = $(document).scrollTop();

        // change header background on scroll
        if (fromTop > 10) {
            $(".navbar-fixed-top").addClass("scrolled_header");
        } else {
            $(".navbar-fixed-top").removeClass("scrolled_header");
        }
    });

$('#email_move').click(function(){ $('#name').focus()})

    // scroll to  particular section 


    $('header a').not('#logout_header').click(function() {

        var href = $.attr(this, 'href');
        var tab = $(this).text();
        localStorage.setItem('href', href);
        localStorage.setItem('tab', tab);

//                     console.log(pathname +'!='+ URL +"&&"+ pathname +"!="+ URL + href+ "&&"+ href +' == #rates-success');
// return false;
        if (pathname != URL && pathname != URL + href && href == '#rates-success') {
          //  console.log(pathname +'='+ URL +"&&"+ pathname +"!="+ URL + href+ "&&"+ href +' == #rates-success');
            $(location).attr('href', "../" + href);
        } else {
          // alert(2+href+ tab);
            if (tab == 'Rates') {
                $('#access , #1b').removeClass('active');
                $('#rates , #2b ').addClass('active');
            } else if (tab == 'Access Numbers') {
                $('#access , #1b').addClass('active');
                $('#rates , #2b').removeClass('active');
            }
            $root.animate({
                scrollTop: $(href).offset().top - 75
            }, 1000, function() {
                window.location.hash = href;
            });
            /** select particular tab **/
        }
        return false;
    });

     $('#call_details').click(function() {

        var href = '#rates-success';
        var tab = 'Rates';
        localStorage.setItem('href', href);
        localStorage.setItem('tab', tab);
      
            if (tab == 'Rates') {
                $('#access , #1b').removeClass('active');
                $('#rates , #2b ').addClass('active');
            } else if (tab == 'Access Numbers') {
                $('#access , #1b').addClass('active');
                $('#rates , #2b').removeClass('active');
            }
            $root.animate({
                scrollTop: $(href).offset().top - 10
            }, 1000, function() {
                window.location.hash = href;
            });
            /** select particular tab **/
    
        return false;
    });

// on country flag selection show the country data 
  $('.gs_logo_single').hover(function(){
    $(this).css('cursor','pointer');
  })
  $('.gs_logo_single').click(function(){ 

          if($('#access , #1b').hasClass('active')){
             $('#access , #1b').removeClass('active');
                $('#rates , #2b ').addClass('active');
          }

        $("#country_select option:contains("+country_name+")").prop('selected',false);
       var country_name = $(this).children()[1].innerHTML;
    
        if(country_name == "UK"){
            country_name = 'United Kingdom';
        }else if (country_name == "UAE"){
             country_name = 'United Arab Emirates';
        }

      $("#country_select option:contains("+country_name+")").prop('selected', true);
       $('#rates-form').submit();


    })



   
   //  if(pathname == URL + '1'){
   //       $.ajax({
   //                              type: "POST",
   //                              url: MBAjax.ajaxurl,
   //                              data: "action=get_store",
   //                              success: function(response) {
   //                                  // if(response == 0){
   //                                  //     location.reload();
   //                                  // }
   //                              }
   //                          });
   //   window.location.replace(URL);
   //  // location.reload();
   // }


    // join button animation
    $('.nextend-smartslider-button-container').attr('class', 'hvr-shutter-out-horizontal')
  
    if (pathname == URL || pathname == URL + 'rates_access/' ||  pathname == URL + '#rates-success') {
        // access numbers and rates data-tables
        $('#access-numbers').DataTable({});
        $('#rates_table').dataTable({

        });
        $('#task-table').DataTable({
            "pageLength": 10
        });

        $("#access-numbers_length , #rates_table_length").parent().remove();
        $("#access-numbers_filter").parent().removeClass("col-sm-6").addClass("col-md-12");
        $("#rates_table_filter").parent().removeClass("col-sm-6").addClass("col-md-12");
        $('#access-numbers_paginate,#rates_table_paginate').parent().removeClass("col-md-6").addClass("col-md-12 text-center");
        $("#access-numbers_filter").find("label").find("input").attr({
            "placeholder": "Search Access Number : Enter  Area Code, State, or City Here"
        });
        // $("#rates_table_filter").find("label").find("input").attr({
        //     "placeholder": "Search Rates"
        // });
        $(".dataTables_length, #rates_table_filter").hide();
        $("#access-numbers_filter").find("label")[0].childNodes[0].remove();
        $("#rates_table_filter").find("label")[0].childNodes[0].remove();
          $("#rates_table_filter").find("label").find("input").remove();



    }

    
       
    // to show header background
    $(".nav-tog").click(function() {
        $(".navbar-header").toggleClass("show-bg");
    });


    /* to remove apps button link*/
    $('.n2-ow').parent('a').attr('href', '');

    /* added attributes to show modal on app button click*/
    $('#n2-ss-1item3,#n2-ss-1item4,#n2-ss-1item7,#n2-ss-1item8,#n2-ss-1item12,#n2-ss-1item13').attr({
        'data-toggle': 'modal',
        'data-target': '.confirmation-modal'
    });


    // map js
    $('.map-container').click(function() {

        $(this).find('iframe').addClass('clicked')
    }).mouseleave(function() {

        $(this).find('iframe').removeClass('clicked');
    });


    // FAQ Open and close div css

    $(".accordion-toggle").click(function() {
        $(".panel-heading").removeClass("active");
        $(".panel-collapse.in").siblings(".accordion-toggle").find(".panel-heading").addClass("active");
    })

    $(".accordion-toggle").click(function() {
        $(".accordion-toggle").find(".panel-heading").removeClass("active");
        $(this).find(".panel-heading").addClass("active");
    })


    $('.collapse').on('shown.bs.collapse', function() {
        $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");

    }).on('hidden.bs.collapse', function() {
        $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
    });
     
// vonecall faq path

     if(pathname == URL+'vonecall-faq/?1/'){
     //   alert(pathname == URL+'vonecall-faq/?1/');
        $('#collapse0').addClass('in');
     }


    /** HIDING SEARCHED DIV FOR COUNTRY  **/
    $('#searched_country').hide();
    $('.rates_all_div').show();
    /********************* GET SELECTED  COUNTRY DATA ON LOAD **************/
    var country = $('#country_select').val();
    balance = $('#balance_select').val();
    if (country == 'All') {
            rate_count = $('#rates_table').find('tr');
            for (i = 1; i < rate_count.length; i++) {
                var td = rate_count[i].children;
                var rate = td[2].abbr;
                var balance = $('#balance_select').val();
                var minutes = (balance / (rate / 100)) / 100;
                td[3].innerHTML = minutes.toFixed(0);
            }
        }

        // ON Rates Pagination
        $(document).on('click','.paginate_button' , function(){ 
           rate_count = $('#rates_table').find('tr');
            for (i = 1; i < rate_count.length; i++) { 
                var td = rate_count[i].children;//console.log(td);
                var rate = td[2].abbr; //console.log(rate);
                var balance = $('#balance_select').val();
                var minutes = (balance / (rate / 100)) / 100;
                td[3].innerHTML = minutes.toFixed(0);
            }});
    /********************* SEARCH COUNTRY AJAX CALL **************/
    $('#search_rate').on('keyup', function(e) {
        e.preventDefault();
        var name;
        var obj;
        var keyword;
        var i;
        $('#search_ul').html('');
        keyword = $(this).val();
        $.ajax({
            url: MBAjax.ajaxurl,
            data: 'key=' + keyword + '&action=search_country',
            type: 'POST',
            success: function(responseData) {
                obj = jQuery.parseJSON(responseData);
                for (i = 0; i < obj.length; i++) {
                    name = obj[i]['country']; // GET COUNTRY NAME
                    $('#searched_country').show();
                    $('#search_ul').append('<li class="search_li" style="list-style:none;background:white;hover"><a href="#">' + name + '</a><input type="hidden" value=""/> </li>');
                }

            }
        });

    });


    /*********************  COUNTRY DROPDOWN  AJAX CALL **************/

    $('#rates-form').on('submit', function() {
        var obj;
        var name;
        var landline;
        var mobile;
        var effective_date;
        var rate_count;
        name = $('#country_select').val();
        if (name == 'Country') {
            $('#searched_data').show();
            $('.rates_all_div').hide();
            $('#city').html('');
            $('#state').html('');
            $('#sms').html('');

        } else {
            var balance = $('#balance_select').val();
            $.ajax({
                url: MBAjax.ajaxurl,
                data: 'key=' + name + '&action=search_rate',
                type: 'POST',
                 beforeSend: function() {
                $(".loading").show();
                },
                complete: function() {
                $(".loading").hide();
                },
                success: function(responseData) {
                   
                    obj = jQuery.parseJSON(responseData);
                    var result = [];
                    for( i = 0 ; i <= obj.length-1 ; i++){
                      var rates = obj[i].rates;
                       obj[i].rates = parseFloat(rates * 100).toFixed(2)+'¢';
                       result.push(obj[i]);
                       
                     }
                     
                      $("#rates_table").dataTable().fnDestroy();
                      $("#rates_table").DataTable({
                         data : result,
                         columns: [
                                    { data : "country" },
                                    { data: "description" },
                                    { data: "rates" },
                                    //{ data: "date" },
                                    { data :'minutes'}
                                ]
                    });
                   
                  rate_count = $('#rates_table').find('tr');
                  for (i = 1; i < rate_count.length; i++) {
                    var td = rate_count[i].children;
                    var rate = result[i-1]['new_rates'];

                    var float_rate = parseFloat(rate * 100).toFixed(2);
//                    console.log(float_rate);
                    var minutes = (balance / float_rate)*100;

                    td[3].innerHTML = minutes.toFixed(0);
                    
                }
                
                $("#rates_table_length").parent().remove();
                $("#rates_table_filter").parent().removeClass("col-sm-6").addClass("col-md-12");
                $('#rates_table_paginate').parent().removeClass("col-md-6").addClass("col-md-12 text-center");
                $(".dataTables_length, #rates-table_filter").hide();
                $("#rates_table_filter").find("label")[0].childNodes[0].remove();
                $("#rates_table_filter").find("label").find("input").remove();
                }


            });
      
       return false;    


        }
    })


// on country flag selection show the country data 
  $('.gs_logo_single').hover(function(){
    $(this).css('cursor','pointer');
  })
  $('.gs_logo_single').click(function(){ 

          if($('#access , #1b').hasClass('active')){
             $('#access , #1b').removeClass('active');
                $('#rates , #2b ').addClass('active');
          }

        $("#country_select option:contains("+country_name+")").prop('selected',false);
       var country_name = $(this).children()[1].innerHTML;
    
        if(country_name == "UK"){
            country_name = 'United Kingdom';
        }else if (country_name == "UAE"){
             country_name = 'United Arab Emirates';
        }

      $("#country_select option:contains("+country_name+")").prop('selected', true);
       $('#rates-form').submit();


    })

    // $('#balance_select').on('change', function() {
    //     var balance = this.value;
    //     if ($('#country_select').val() == 'All') {
    //         var rate_count = $('#rates_table').find('tr');
    //         for (i = 1; i < rate_count.length; i++) {
    //             var td = rate_count[i].children;
    //             var rate = td[2].abbr;
    //             var minutes = (balance / (rate / 100)) / 100;
    //             td[4].innerHTML = minutes.toFixed(0);
    //         }
    //     } else {
    //         landline = $('#landline').val();
    //         mobile = $('#mobile').val();
    //         landline_float = parseFloat(landline * 100);
    //         mobile_float = parseFloat(mobile * 100);
    //         land_minutes = ((balance / (landline / 100)) / 100).toFixed(0);
    //         if (mobile != '') {
    //             mob_minutes = ((balance / (mobile / 100)) / 100).toFixed(0);
    //             mobile_rate = mobile_float.toFixed(2) + '/' + mob_minutes + 'min';

    //         } else {
    //             mobile_rate = 'NA';

    //         }

    //         $('#city').html(landline_float.toFixed(2) + '/' + land_minutes + 'min');
    //         $('#state').html(mobile_rate);
    //     }
    // });





    /*****************VERIFY USER PHONE NUMBER AJAX CALL *****************/
    $('#phone_verify_form').submit(function(e) {
        e.preventDefault();
        var form_data = new FormData(this);
        $.ajax({
            url: MBAjax.ajaxurl,
            data: form_data,
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $(".loading").show();
            },
            complete: function() {
                $(".loading").hide();
            },
            success: function(responseData) {
                var obj = jQuery.parseJSON(responseData);
                var status = obj.redirect_rule;
                var contact_no = obj.phone;
                console.log("OTP CODE : "+obj.otp);
//                alert(obj.message);
                if (status == 1 || status == 3) {
                    var x = document.getElementById("snackbar")
                        x.className = "show";
                        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                        document.getElementById('snackbar').innerHTML = 'You are an existing Customer of Vonecall Pinless Please login<br>';
                    //alert('You are a valid customer please login');
                    window.location.replace(URL + '/customer');

                } else if (status == 2) {
                    var x = document.getElementById("snackbar")
                        x.className = "show";
                        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                        document.getElementById('snackbar').innerHTML = 'You are an existing customer of Vonecall.  Please create a login credential<br>'+obj.message;
                   // alert('Not a Existing customer');
                    $('#newPhone').val(contact_no);
                    //  $('.otp_verify').css('display','block');
                    $('.register-user').css('display', 'block');
                    $('.phone_verify').css('display', 'none');
                } else if (status == 4) {
                    var x = document.getElementById("snackbar")
                        x.className = "show";
                        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                        document.getElementById('snackbar').innerHTML = 'You are not currently a Vonecall Pinless Customer<br>'+obj.message;
                    //alert('Not a Existing customer');
                    $('#newPhone').val(contact_no);
                    $('.register-user').css('display', 'block');
                    $('.phone_verify').css('display', 'none');
                }


            }
        });
    });


    //****** LOGIN  AJAX CALL ****/
    $('#frm-login').submit(function(e) {
        e.preventDefault();
        var redirect_url;
        var ajax_url;
        var user_type = $('#user_role').val();
        var base_path = URL.replace('/vonecall', '');
        var data1 = new FormData(this);
        if (user_type == 1) {
            ajax_url = base_path + 'distributors/login';
            redirect_url = base_path + 'distributors/home';
        } else if (user_type == 2) {
            ajax_url = URL + 'online_store/login';
            redirect_url = URL + 'online_store/home';
             var store_name = $('#email').val();
        } else {

            ajax_url = MBAjax.ajaxurl;
            redirect_url = URL + '/dashboard';
        }
        $.ajax({
            url: ajax_url,
            data: data1,
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $(".loading").show();
            },
            complete: function() {
                $(".loading").hide();
            },
            success: function(responseData) {
                console.log(responseData);
                // return false;
                if (user_type == 3) {
                    if (responseData == 0) {
                            var x = document.getElementById("snackbar")
                            x.className = "show";
                            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                            document.getElementById('snackbar').innerHTML = 'User Not Exist';                          
                        //alert('User Not Exist');
                    } else if (responseData == 4) {
                            var x = document.getElementById("snackbar")
                            x.className = "show";
                            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                            document.getElementById('snackbar').innerHTML = 'Incorrect Password';
                    } else if (responseData == 1) {
                            var x = document.getElementById("snackbar")
                            x.className = "show";
                            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                            document.getElementById('snackbar').innerHTML = 'Login Successfully';
                        window.location.replace(redirect_url);
                    }
                } else {
                    if (responseData.indexOf("Invalid ID or password!") > -1) {
                            var x = document.getElementById("snackbar")
                            x.className = "show";
                            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                            document.getElementById('snackbar').innerHTML = 'Invalid Id or Password';                        
                    } else {
                         $.ajax({
                                type: "POST",
                                url: MBAjax.ajaxurl,
                                data: 'store=' + store_name + "&action=store",
                                success: function(response) {
                                    console.log(response);
                                }
                            });
                       var x = document.getElementById("snackbar")
                            x.className = "show";
                            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                            document.getElementById('snackbar').innerHTML = 'Login Successfully';
                        window.location.replace(redirect_url);
                       
                    }
                }

            }
        });
    });

    /*************login auth check **************/
    //****** SUBMIT REGISTRATION FORM AJAX CALL ****/
    $('#register_form').submit(function(e) {
        e.preventDefault();
        var redirect_url;
        var visitortime = new Date();
        var visitortimezone = -visitortime.getTimezoneOffset();
        if ($('#terms').is(':checked')) {
            redirect_url = URL + '/dashboard';
            var form_data = new FormData(this);
            form_data.append('timezone', visitortimezone);
            $.ajax({
                url: MBAjax.ajaxurl,
                data: form_data,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(".loading").show();
                },
                complete: function() {
                    $(".loading").hide();
                },
                success: function(responseData) {
                    if (responseData == 1) {
                    var x = document.getElementById("snackbar")
                    x.className = "show";
                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                    document.getElementById('snackbar').innerHTML = 'User Registered successfully';                        
                        //alert('User Registered successfully');
                        window.location.replace(redirect_url);
                    } else {
                    var rdata = jQuery.parseJSON(responseData);
                    var x = document.getElementById("snackbar")
                    x.className = "show";
                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                    document.getElementById('snackbar').innerHTML = rdata.error;
                        //alert('Error in user registeration');
                        return false;
                    }
                }
            });

        } else {
                    var x = document.getElementById("snackbar")
                    x.className = "show";
                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                    document.getElementById('snackbar').innerHTML = 'Please accept terms & conditions';            
            //alert('Please accept terms & conditions');
            return false;
        }


    });


    /***************SUBMIT OTP FORM AJAX CALL *********/
    $('#otp_form').submit(function() {
        var submit_otp = $('#otp').val();
        if (submit_otp != '') {

            var form_data = new FormData(this);
            $.ajax({
                url: MBAjax.ajaxurl,
                data: form_data,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(".loading").show();
                },
                complete: function() {
                    $(".loading").hide();
                },
                success: function(responseData) {
                    if (responseData == 1) {
                        var x = document.getElementById("snackbar")
                        x.className = "show";
                        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                        document.getElementById('snackbar').innerHTML = 'OTP verified successfully';   

                        //alert('OTP verified successfully');
                        return false;
                    } else {
                        var x = document.getElementById("snackbar")
                        x.className = "show";
                        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                        document.getElementById('snackbar').innerHTML = 'Error in verification';                          
                        //alert('Error in verification');
                        return false;
                    }
                }
            });
        } else {
                var x = document.getElementById("snackbar")
                x.className = "show";
                setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                document.getElementById('snackbar').innerHTML = 'OTP value cannot be empty';            
            //alert('OTP value cannot be empty');
            return false;
        }

    })

/*******************Logout **********/
$("a, #logout_header").click(function(){

var href = $(this).attr("href");

if(href == "#logout"){
     $.ajax({
        type: "POST",
        url: MBAjax.ajaxurl,
        data: "&action=logout",
        success: function(response) {
            if (response == 1) {
                            var x = document.getElementById("snackbar")
                            x.className = "show";
                            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                            document.getElementById('snackbar').innerHTML = 'logout  successfully';  
                  //alert('logout  successfully');
                  window.location.replace(URL);
              } else {
                            var x = document.getElementById("snackbar")
                            x.className = "show";
                            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                            document.getElementById('snackbar').innerHTML = 'Error in logging out';                  
                        //alert('Error in logging out');
                        return false;
                    }
                }
    });
        } 
});




/*************update form ********/
$('#update_profile').submit(function(e){
    e.preventDefault();
     var form_data = new FormData(this);
      $.ajax({
                url: MBAjax.ajaxurl,
                data: form_data,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(".loading").show();
                },
                complete: function() {
                    $(".loading").hide();
                },
                success: function(responseData) {
                    if(responseData == 1){
                            var x = document.getElementById("snackbar")
                            x.className = "show";
                            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                            document.getElementById('snackbar').innerHTML = 'Profile Updated Successfully';                        
                        //alert('Profile Updated Successfully');
                        location.reload();
                    }else{
                            var x = document.getElementById("snackbar")
                            x.className = "show";
                            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                            document.getElementById('snackbar').innerHTML = 'Error in Profile Updation';                         
                        //alert('Error in Profile Updation');
                    }
                }
            });


});


/**************Update password **********/

$('#update_password').submit(function(e){
    e.preventDefault();
    if($('#new_pass').val() == $('#c_pass').val() ){
     var form_data = new FormData(this);
      $.ajax({
                url: MBAjax.ajaxurl,
                data: form_data,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(".loading").show();
                },
                complete: function() {
                    $(".loading").hide();
                },
                success: function(responseData) {
                   if(responseData == 1){
                            var x = document.getElementById("snackbar")
                            x.className = "show";
                            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                            document.getElementById('snackbar').innerHTML = 'Password Updated Successfully';                     
                        //alert('Password Updated Successfully');
                    }else if (responseData == 2){
                            var x = document.getElementById("snackbar")
                            x.className = "show";
                            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                            document.getElementById('snackbar').innerHTML = 'Current password not matched';                         
                        //alert(' Current password not matched');
                    }
                    else if (responseData == 0){
                            var x = document.getElementById("snackbar")
                            x.className = "show";
                            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                            document.getElementById('snackbar').innerHTML = 'Error in password updation';                         
                       // alert(' Error in password updation');
                    }
                }
            });

}else{
                                var x = document.getElementById("snackbar")
                            x.className = "show";
                            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                            document.getElementById('snackbar').innerHTML = 'Password Not Matched'; 
    //alert('Password Not Matched');
}
});

// calling history & transaction history

      $('#calling_history').DataTable({
        "deferRender": true,
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "sDom": 'lfrtip'
    });
       $('#trans_history').DataTable({
        "deferRender": true,
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "sDom": 'lfrtip'
    });
        $("#calling_history_length , #trans_history_length").parent().remove();
        $("#calling_history_filter , #calling_history_paginate , #trans_history_filter , #trans_history_paginate").parent().removeClass("col-sm-6").addClass("col-md-12");
        $('#calling_history_paginate ,#trans_history_paginate').parent().removeClass("col-md-6").addClass("col-md-12 text-center");
        $("#calling_history_filter ,  #trans_history_filter").find("label").hide();
        /*** to set date 1 month ago *************/
 var d = new Date();
 d.setMonth(d.getMonth() - 1);
 $('.call_from_date').datepicker('update' , d);

/*****************on load show history ***************/


/******************calling history ***********/

      var from_date = $('.call_from_date').val();
       var to_date = $('.call_to_date').val();
      
       $.ajax({
                 url: MBAjax.ajaxurl,
                 data: {'from_date':from_date,'to_date':to_date, action : 'get_calling_history'},
                 type: 'POST',
               
                 beforeSend: function() {
                  //   $(".loading").show();
                 },
                 complete: function() {
                   //  $(".loading").hide();
                 },
                 success: function(responseData) {
                    if(responseData != ''){
                     var obj = JSON.parse(responseData);
                     var result = [];
                     for( i = 0 ; i <= obj.length-1 ; i++){
                          result.push(obj[i]);
                      }
                     $("#calling_history").dataTable().fnDestroy();
                     $('#calling_history').DataTable({
                          data : result,
                          columns: [
                                     {data : "sno" },
                                     { data: "destined_number" },
                                     { data: "call_on" },
                                     { data: "call_duration" },
                                     { data: "charged_amount" }
                                  
                                 ]
                     });
                   
                   
                    }else{
                    console.log('No Data available');
                     return false;
                    }
                 }
             });



/******************transaction history ***********/

    
       $.ajax({
                 url: MBAjax.ajaxurl,
                 data: {'from_date':from_date,'to_date':to_date, action : 'get_transaction_history'},
                 type: 'POST',
               
                 beforeSend: function() {
                   //  $(".loading").show();
                 },
                 complete: function() {
                  //   $(".loading").hide();
                 },
                 success: function(responseData) {
                    if(responseData != ''){
                     var obj = JSON.parse(responseData);
                     var result = [];
                     for( i = 0 ; i <= obj.length-1 ; i++){
                          result.push(obj[i]);
                      }
                     $("#trans_history").dataTable().fnDestroy();
                     $('#trans_history').DataTable({
                          data : result,
                          columns: [
                                     {data : "sno" },
                                     { data: "payment_type" },
                                     { data: "charged_amount" },
                                      { data: "call_on" }
                                  
                                 ]
                     });
                   
                   
                    }else{
                   //  console.log('No Data available');
                     return false;
                    }
                 }
             });




// /**********************Get transaction history ***********/
 $('#get_transact_history').submit(function(e){
     e.preventDefault();

       var form_data = new FormData(this);
    
       $.ajax({
                 url: MBAjax.ajaxurl,
                 data: form_data,
                 type: 'POST',
                 cache: false,
                 processData: false,
                 contentType: false,
                 beforeSend: function() {
                     $(".loading").show();
                 },
                 complete: function() {
                     $(".loading").hide();
                 },
                 success: function(responseData) {
                    if(responseData != ''){
                     var obj = JSON.parse(responseData);
                     var result = [];
                     for( i = 0 ; i <= obj.length-1 ; i++){
                          result.push(obj[i]);
                      }
                     $("#trans_history").dataTable().fnDestroy();
                     $('#trans_history').DataTable({
                          data : result,
                          columns: [
                                     {data : "sno" },
                                     { data: "payment_type" },
                                     { data: "charged_amount" },
                                      { data: "call_on" }
                                  
                                 ]
                     });
                   
                   
                    }else{
                   //  alert('No Data available');
                     return false;
                    }
                 }
             });

 });


 /**********GET CALLING HISTORY ***********/
 $('#get_call_history').submit(function(e){
     e.preventDefault();

       var form_data = new FormData(this);
      
       $.ajax({
                 url: MBAjax.ajaxurl,
                 data: form_data,
                 type: 'POST',
                 cache: false,
                 processData: false,
                 contentType: false,
                 beforeSend: function() {
                     $(".loading").show();
                 },
                 complete: function() {
                     $(".loading").hide();
                 },
                 success: function(responseData) {
                    if(responseData != ''){
                     var obj = JSON.parse(responseData);
                     var result = [];
                     for( i = 0 ; i <= obj.length-1 ; i++){
                          result.push(obj[i]);
                      }
                     $("#calling_history").dataTable().fnDestroy();
                     $('#calling_history').DataTable({
                          data : result,
                          columns: [
                                     {data : "sno" },
                                     { data: "destined_number" },
                                     { data: "call_on" },
                                     { data: "call_duration" },
                                     { data: "charged_amount" }
                                  
                                 ]
                     });
                   
                   
                    }else{
                     alert('No Data available');
                     return false;
                    }
                 }
             });

 });


/******************pay by saved card on select **********/
$('#select-card').change(function(){
    var card_id = $(this).val();
    if(card_id != 0){
           $.ajax({
                type: "POST",
                url: MBAjax.ajaxurl,
                data: "card_id="+card_id+"&action=getcard",
                success: function(response) {
                        var obj = JSON.parse(response);
                        // console.log(obj[0].sa_card_number);
                        $('#card-name').val('test');
                        $('#card-number').val(obj[0].sa_card_number);
                        $('#expiration-date').val(obj[0].sa_card_exp);
                        $('#cvv').val(obj[0].sa_card_cvv);
                    }
            });
    }else if(card_id == 0){
                        $('#card-name').val('');
                        $('#card-number').val('');
                        $('#expiration-date').val('');
                        $('#cvv').val('');
    }
});



/*****************Recharge pinless *************/
$('#payment-method').submit(function(e){
   e.preventDefault();
if($('#checkbox-1-5').val() != 1){
    var x = document.getElementById("snackbar")
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
    document.getElementById('snackbar').innerHTML = 'Please agree to the terms & conditions';    
 //alert('Please agree to the terms & conditions');
 return false;
}else{

//alert('in');

card_number = $('#card-number').val();
card_name = GetCardType(card_number);
if(card_name == ''){
    var x = document.getElementById("snackbar")
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
    document.getElementById('snackbar').innerHTML = 'Not a valid card';   

    //alert('Not a valid card');
    return false;
}else{
    card_type = card_name;
}

var exp =$('#expiration-date').val();
 var date = exp.split('-'); 
 var new_date = date[1]+'-'+date[0]; 



      var form_data = new FormData(this);
      form_data.append('card_type',card_type);
      form_data.append('exp_date',new_date);
   
      $.ajax({
                url: MBAjax.ajaxurl,
                data: form_data,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(".loading").show();
                },
                complete: function() {
                    $(".loading").hide();
                },
                success: function(responseData) {
                  if(responseData == 1){
                    alert('Recharged Successfully');
                  }else{
                    alert('Error in Transcation ,Please try again');
                  }
                 location.reload();
                }
            });

}

});




/************CONTACT FORM SUBMISSION**********/
  $('#contact-Form').submit(function(e) {
        e.preventDefault();
        
            var form_data = new FormData(this);
              form_data.append('action','send_contact_mail');
            $.ajax({
                url: MBAjax.ajaxurl,
                data: form_data,
                type: 'POST',
                 cache: false,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(".loading").show();
                },
                complete: function() {
                    $(".loading").hide();
                },
                success: function(responseData) {
                    // console.log(responseData);
                if(responseData == 1){
                    var x = document.getElementById("snackbar")
                    x.className = "show";
                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                    document.getElementById('snackbar').innerHTML = 'Your Mail has been sent Successfully, Thank you.';  
                  }else{
                    var x = document.getElementById("snackbar")
                    x.className = "show";
                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                    document.getElementById('snackbar').innerHTML = 'Please try again later';  
                  }
                 location.reload();
                }
            });
    });



/**deshbord-page**/

$(document).ready(function(){
  
 $('.deshbord-toggle').click(function(){
    $('.deshbord-toggle').toggleClass("toggleactive");
  
    if ($(this).hasClass("toggleactive")){
    
     $(".left_col").css({"display" : "none", "transition" : " 0.8s ease-out" });
     $(".deshbord-menu, .right_col").css({"margin-left" : " 0", "transition" : "0.5s" });




   } else {
    
     $(".left_col").css({"display" : "block",  "transition" : " 0.5s ease-out "});
     $(".deshbord-menu, .right_col").css({"margin-left" : "230px", "transition" : "0.5s" });

   
   }

 });



(function($) {
  
 function mediaSize() {
    if (window.matchMedia('(min-width: 768px)').matches) {
    
     $(".left_col").css({"display" : "block", "transition" : " 0.5s ease-out" });
     $(".deshbord-menu, .right_col").css({"margin-left" : " 230px", "transition" : "0.5s" });

   } else {
    
   $(".left_col").css({"display" : "none", "transition" : " 0.5s ease-out" });
     $(".deshbord-menu, .right_col").css({"margin-left" : " 0", "transition" : "0.5s" });

   }
  };
  
 /* Call the function */
  mediaSize();
  /* Attach the function to the resize event listener */
  window.addEventListener('resize', mediaSize, false);  
 
})(jQuery);

});

})

function check_store_session(){


}

function GetCardType(number)
{
    // visa
    var re = new RegExp("^4");
    if (number.match(re) != null)
        return "Visa";

    // Mastercard 
    // Updated for Mastercard 2017 BINs expansion
     if (/^(5[1-5][0-9]{14}|2(22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12}))$/.test(number)) 
        return "Mastercard";

    // AMEX
    re = new RegExp("^3[47]");
    if (number.match(re) != null)
        return "AMEX";

    // Discover
    re = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
    if (number.match(re) != null)
        return "Discover";

    // Diners
    re = new RegExp("^36");
    if (number.match(re) != null)
        return "Diners";

    // Diners - Carte Blanche
    re = new RegExp("^30[0-5]");
    if (number.match(re) != null)
        return "Diners - Carte Blanche";

    // JCB
    re = new RegExp("^35(2[89]|[3-8][0-9])");
    if (number.match(re) != null)
        return "JCB";

    // Visa Electron
    re = new RegExp("^(4026|417500|4508|4844|491(3|7))");
    if (number.match(re) != null)
        return "Visa Electron";

    return "";
}


function showPassword() {

    var key_attr = $('#password').attr('type');


    if (key_attr != 'text') {

        $('.checkbox').addClass('show');
        $('#password').attr('type', 'text');

    } else {

        $('.checkbox').removeClass('show');
        $('#password').attr('type', 'password');

    }

}






/*********IMAGE RELATED FUNCTION ***********/
 // function mediaSize() {
 //    if (window.matchMedia('(min-width: 768px)').matches) {
    
 //     $(".left_col").css({"display" : "block", "transition" : " 0.5s ease-out" });
 //     $(".deshbord-menu, .right_col").css({"margin-left" : " 230px", "transition" : "0.5s" });

 //   } else {
    
 //   $(".left_col").css({"display" : "none", "transition" : " 0.5s ease-out" });
 //     $(".deshbord-menu, .right_col").css({"margin-left" : " 0", "transition" : "0.5s" });

 //   }
 //  }
 function readURLimg(input) {
     value = input.files[0].size; /* get size of file */
      var size = Math.round(value / 1024); /* file size should be greater than 2 MB */
     if (size > 2097152) {
         alert('File size exceeded from 2 MB');
     } else {
           if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var image = new Image();

                //Set the Base64 string return from FileReader as source.
                image.src = e.target.result;

                //Validate the File Height and Width.
                image.onload = function () {
                    var height = this.height;
                    var width = this.width;

                    if (height > 1024 || width > 1024) {
                        alert("Image should be less than 1024 px.");

                        return false;
                    } else {
                        $("#thumb-container-img").show();
                        $('#preview_img').attr('src', e.target.result);
                        return true;
                    }
                };
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
 }

 $(document).ajaxStop(function(){
    if(pathname == URL + '#store_logout'){
     window.location.replace(URL);
    // location.reload();
   }
 })
 
 /***************** send banner sms *****************/
  setTimeout(function(){
    $('#banner_form1111').submit(function(e) {
        e.preventDefault();
        var form_data = new FormData(this);
        $.ajax({
            url: MBAjax.ajaxurl,
            data: form_data,
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $(".loading").show();
            },
            complete: function() {
                $(".loading").hide();
            },
            success: function(responseData) {
                var obj = jQuery.parseJSON(responseData);
                var status = obj.sentSMS;
                if (status == 'success') {
                    // alert(obj.message);
					var x = document.getElementById("snackbar")
                    x.className = "show";
                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                    document.getElementById('snackbar').innerHTML = obj.message;
                } else if (status == 'error') {
                    console.log(obj.message);
//                    var x = document.getElementById("snackbar")
//                    x.className = "show";
//                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
//                    document.getElementById('snackbar').innerHTML = 'You are an existing customer of Vonecall.  Please create a login credential';
                }
            }
        });
    });
   }, 4000);
	 
 /***************** send banner sms *****************/
 $(document).ready(function(){
    $('#resend_OTP').click(function(e) {
        e.preventDefault();
		var phone = $('#newPhone').val();
        var form_data = "{'action':'resend_otp','mobile_no':'"+phone+"'}";
        $.ajax({
            url: MBAjax.ajaxurl,
            data: {'action':'resend_otp','mobile_no':phone},
            type: 'POST',
            beforeSend: function() {
                $(".loading").show();
            },
            complete: function() {
                $(".loading").hide();
            },
            success: function(responseData) {
                var obj = jQuery.parseJSON(responseData);
                var status = obj.smsStatus;
                if (status == 'success') {
//                    alert(obj.message);
console.log("OTP CODE : "+obj.otp);
                    var x = document.getElementById("snackbar")
                    x.className = "show";
                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                    document.getElementById('snackbar').innerHTML = obj.message;
                } else if (status == 'error') {
                    console.log(obj.message);
                }
            }
        });
    });
    });
	
	
 /***************** send banner sms *****************/
 setTimeout(function(){
 $(document).ready(function(){
    $('#forgot_password_form').submit(function(e) {
         e.preventDefault();
        var form_data = new FormData(this);
        $.ajax({
            url: MBAjax.ajaxurl,
            data: form_data,
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $(".loading").show();
            },
            complete: function() {
                $(".loading").hide();
            },
            success: function(responseData) {
                var obj = jQuery.parseJSON(responseData);
                var status = obj.code;
                if (status == '1') {
                    //alert(obj.message);
                    $('.forget-modal').modal('hide');
                    var x = document.getElementById("snackbar")
                    x.className = "show";
                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                    document.getElementById('snackbar').innerHTML = obj.message;
                    //window.location.replace(URL + '/customer');
                } else if (status == '0') {
                    console.log(obj.message);
                    $('.forget-modal').modal('hide');
//                    var x = document.getElementById("snackbar")
//                    x.className = "show";
//                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
//                    document.getElementById('snackbar').innerHTML = 'You are an existing customer of Vonecall.  Please create a login credential';
                }
            }
        });
    });
    });
    }, 4000);
    
    
    setTimeout(function(){
    $('#banner_form12').submit(function(e) {
        e.preventDefault();
        var form_data = new FormData(this);
        $.ajax({
            url: MBAjax.ajaxurl,
            data: form_data,
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $(".loading").show();
            },
            complete: function() {
                $(".loading").hide();
            },
            success: function(responseData) {
                var obj = jQuery.parseJSON(responseData);
                var status = obj.sentSMS;
                $('#myModal').modal('hide');
                if (status == 'success') {
                    // alert(obj.message);
                    var x = document.getElementById("snackbar")
                    x.className = "show";
                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
                    document.getElementById('snackbar').innerHTML = obj.message;
                } else if (status == 'error') {
                    console.log(obj.message);
//                    var x = document.getElementById("snackbar")
//                    x.className = "show";
//                    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
//                    document.getElementById('snackbar').innerHTML = 'You are an existing customer of Vonecall.  Please create a login credential';
                }
            }
        });
    });
   }, 4000);