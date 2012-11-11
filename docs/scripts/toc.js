function build_toc( ref ) {
	$('#toc').html(
		'<table>' + 
		'<tr>' + 
		'<td>' + 
		'<strong>Basic Information</strong>' +
		'<ul>' +
			'<li><a href="'+ref+'basic/license.html">License</a></li>' + 		
			'<li><a href="'+ref+'basic/system-requirements.html">System Requirements</a></li>' + 
			'<li><a href="'+ref+'basic/installation.html">Installation</a></li>' + 
			'<li><a href="'+ref+'basic/configuration.html">Configuration</a></li>' + 
		'</ul>' + 
		'</td>' +
		'<td>' + 
		'<strong>Database</strong>' +
		'<ul>' +
			'<li><a href="'+ref+'database/basic-database-functions.html">Basic Database Functions</a></li>' + 
		'</ul>' + 
		'</td>' +		
		'<td>' + 
		'<strong>Others</strong>' +
		'<ul>' +
			'<li><a href="'+ref+'others/faqs.html">FAQs</a></li>' + 
		'</ul>' + 
		'</td>' +		
		'</tr>' + 
		'</table>'
	);
}