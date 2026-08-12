function restrictCurrency(e) {
	return restrictInput(e,'0123456789.');
}

function restrictZip(e){
	return restrictInput(e,'0123456789-');
}

function restrictCreditCardNo(e){
	return restrictInput(e,'0123456789');
}

function restrictNumber(e){
	return restrictInput(e,'0123456789');
}

function restrictEIN(e){
	return restrictInput(e,'0123456789');
}

// copyright 1999 Idocs, Inc. http://www.idocs.com
// Distribute this script freely but keep this notice in place
function restrictInput(e, allowedChars) {

	var key;
	var keychar;


	if (window.event){
		key = window.event.keyCode ;
	}

	if (!key){
		key = e.which;
	}

	keychar = String.fromCharCode(key);


// control keys
	if ((key==null) || (key==0) || (key==8) ||
			(key==9) || (key==13) || (key==27) ){
		return true;

	} else if (((allowedChars).indexOf(keychar) > -1)){
		return true;
	}else{
		return false;
	}

}

var phone_field_length=0;
function tabNext(obj,event,len,next_field) {
	if (event == "down") {
		phone_field_length=obj.value.length;
	}
	else if (event == "up") {
		if (obj.value.length != phone_field_length) {
			phone_field_length=obj.value.length;
			if (phone_field_length == len) {
				next_field.focus();
			}
		}
	}
}

var ein_field_length=0;
function formatEIN(obj,event) {

	if( obj.value.length == 2 && event.keyCode != 8) {
		obj.value = obj.value + '-';
	}
	return true;
}

function formatZIP(obj,event) {
	if (event.keyCode == 109) {
		obj.value = obj.value.substring(0,obj.value.length -1);
	}
	else if (obj.value.length == 6) {
		obj.value = obj.value.substring(0,obj.value.length - 1) + '-' + obj.value.substring(obj.value.length - 1, obj.value.length);
	}

	return true;
}
