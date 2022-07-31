
$(document).ready(function () {
$("#form").submit(function (e) {
    var phone = $("#phone").val();
    var adhaar = $("#adhaar").val();
    var isMale = $('#inlineRadio1').is(':checked');
    var isfemale = $('#inlineRadio2').is(':checked');

    if (String(phone).length != 10) {
    display_error("Please enter a valid phone number");
    interrupt();
    } else if(isMale!=true && isfemale!=true){
    display_error('Please select a gender');
    interrupt();
    }else if(adhaar.length != 12){
    display_error('Please enter a valid adhaar number');
    interrupt();
    }

    function interrupt() {
    e.preventDefault(e);;
    }
});

function display_error(error){
    $("#alert").css("display", "block");
    $("#alert").text(error);
}

});