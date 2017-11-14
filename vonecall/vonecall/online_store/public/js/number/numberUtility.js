/**
 * Hàm này dùng để cho text box chỉ nhập được chử thôi
 * <input type="text" onkeypress="return onlyNumbers(event)" />
 */
function onlyNumbers(evt)
{
	var e = window.event || evt; // for trans-browser compatibility
	var charCode = e.which || e.keyCode;

	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;

	return true;

}

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
		if (CurrentElement.val().length >= FieldLength && ((KeyID >= 48 && KeyID <= 90) ||(KeyID >= 96 && KeyID <= 105)))
			NextElement.focus();
		});
	}
