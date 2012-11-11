$(function() {
	// Handler for .ready() called.
	$('.menu-toc').click(function(){
		$('#toc-container').slideToggle(200);
	});
	
	$('#close-container').click(function(){
		$('#toc-container').slideToggle(200);
	});	
});