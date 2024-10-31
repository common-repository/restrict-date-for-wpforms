(function($) {
    "use strict";
    $( document ).ready( function () { 
        $("body").on("change",".wpforms-field-option-row-date_min select",function(){
            var value = $(this).val();
            var field = $(this);
            if(value == ""){
                $(".wpforms-field-option-row-min_current_date, .wpforms-field-option-row-min_pick",field).addClass("hidden");
            }else if(value == "current_date"){
                $(".wpforms-field-option-row-min_pick",field).addClass("hidden");
                $(".wpforms-field-option-row-min_current_date",field).removeClass("hidden");
            }else{
                $(".wpforms-field-option-row-min_pick",field).removeClass("hidden");
                $(".wpforms-field-option-row-min_current_date",field).addClass("hidden");
            }
        })
        $("body").on("change",".wpforms-field-option-row-date_max select",function(){
           var field = $(this);
            var value = $(this).val();
            if(value == ""){
                $(".wpforms-field-option-row-max_current_date, .wpforms-field-option-row-max_pick",field).addClass("hidden");
            }else if(value == "current_date"){
                $(".wpforms-field-option-row-max_pick",field).addClass("hidden");
                $(".wpforms-field-option-row-max_current_date",field).removeClass("hidden");
            }else{
                $(".wpforms-field-option-row-max_pick",field).removeClass("hidden");
                $(".wpforms-field-option-row-max_current_date",field).addClass("hidden");
            }
        })
    })
})(jQuery);
