 
$( document ).ready(function() {
	$('body').on('click', '.btn--see-description', function(){
		$(this).parent().find('div').toggle();
		return false;
	});
});
 