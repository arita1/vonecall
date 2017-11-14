function WireAutoTab(CurrentElementID, NextElementID, FieldLength) {
    //Get a reference to the two elements in the tab sequence.
    var CurrentElement = $('#' + CurrentElementID);
    var NextElement = $('#' + NextElementID);
 
    CurrentElement.keyup(function(e) {
        //Retrieve which key was pressed.
        var KeyID = (window.event) ? event.keyCode : e.keyCode;
 
        //If the user has filled the textbox to the given length and
        //the user just pressed a number or letter, then move the
        //cursor to the next element in the tab sequence.   
        
        if (CurrentElement.val().length >= FieldLength
            && ((KeyID >= 48 && KeyID <= 90) ||
            (KeyID >= 96 && KeyID <= 105)))
            NextElement.focus();
    });
}

function check_phone_number(area_code,prefix_code,phone_number){
	var ret = true;
	if($("#" + area_code ).val() == "" || !$("#" + area_code ).val().match(/\d{3}/) ){
		$("#" + area_code ).addClass("tx_red");
		$("#" + area_code ).focus();
		ret = false;
	}
	
	if($("#" + prefix_code ).val() == "" || !$("#" + prefix_code ).val().match(/\d{3}/) ){
		$("#" + prefix_code ).addClass("tx_red");
		$("#" + prefix_code ).focus();
		ret = false;
	}
	
	if($("#" + phone_number ).val() == "" || !$("#" + phone_number ).val().match(/\d{4}/) ){
		$("#" + phone_number ).addClass("tx_red");
		$("#" + phone_number ).focus();
		ret = false;
	}

	return ret;

}
function currency_format(amount) {
	var i = parseFloat(amount);
	if(isNaN(i)) { i = 0.00; }
	var minus = '';
	if(i < 0) { minus = '-'; }
	i = Math.abs(i);
	i = parseInt((i + .005) * 100);
	i = i / 100;
	s = new String(i);
	if(s.indexOf('.') < 0) { s += '.00'; }
	if(s.indexOf('.') == (s.length - 2)) { s += '0'; }
	s = minus + s;
	return s;
}
function float_format(amount) {
	var i = parseFloat(amount);
	if(isNaN(i)) { i = 0; }
	return i;
}
function IsNumeric(input) {
    return (input - 0) == input && (input+'').replace(/^\s+|\s+$/g, "").length > 0;
}