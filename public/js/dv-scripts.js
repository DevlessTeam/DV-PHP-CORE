var copyBtn = function() {

	var el;

	var text = document.querySelector('.code').querySelector('.number2').innerText;

	el = document.createElement("input");
	el.setAttribute("value", text);

	$('.modal-body').append(el);
	el.select();

	document.execCommand("copy", true);
	$('input').remove();

};
