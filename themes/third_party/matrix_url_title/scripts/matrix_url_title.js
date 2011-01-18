(function($) {


var $document = $(document),
	sep = EE.publish.word_separator,
	multiSep = RegExp(sep + "{2,}","g"),
	wrongSep = (sep !== "_") ? /\_/g : /\-/g;


Matrix.bind('matrix_url_title', 'display', function(cell){

	// is this already set to something?
	if (cell.dom.$inputs.val()) return;

	// give Matrix a chance to finish initializing the row
	setTimeout(function(){

		// find the title input
		for (var i = 0; i < cell.row.cells.length; i++) {
			var otherCell = cell.row.cells[i];
			if (otherCell !== cell && otherCell.col.name == cell.settings.title_col) {
				$titleInput = otherCell.dom.$inputs;
				break;
			}
		}

		// doesn't exist?
		if (typeof $titleInput == 'undefined') {
			console.log('could not find title col'); return;
		}

		$titleInput.bind('blur, keyup', function() {
			// start with the Title value
			var val = $titleInput.val();

			// make it lowercase and use the correct word separator
			val = val.toLowerCase().replace(wrongSep, sep);

			// filter out non ASCII characters
			var asciiVal = '';
			for (c = 0; c < val.length; c++) {
				charCode = val.charCodeAt(c);

				if (charCode >= 32 && charCode < 128)
					asciiVal += val.charAt(c);
				else if (charCode in EE.publish.foreignChars)
					asciiVal += EE.publish.foreignChars[charCode]
			}
			val = asciiVal;

			// other filters, etc.
			val = val.replace("/<(.*?)>/g", "");      // strip HTML tags
			val = val.replace(/\s+/g, sep);           // replace whitespace with word separator
			val = val.replace(/\//g, sep);            // replace forward slashes with word separator
			val = val.replace(/[^a-z0-9\-\._]/g, ""); // strip non alphanumeric/hyphen/period/underscore
			val = val.replace(/\+/g, sep);            // strip plus signs with word separator
			val = val.replace(multiSep, sep);         // replace adjacent word separators  with singles
			val = val.replace(/^[-_]|[-_]$/g, "");    // strip beginning of line or end of line word separators
			val = val.replace(/\.+$/g,"");            // strip end of line period

			// save the new value
			cell.dom.$inputs.val(val);
		});

	}, 1);

});


})(jQuery);
