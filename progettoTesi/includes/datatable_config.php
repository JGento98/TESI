var extensions = {
			"sFilter": "dataTables_filter dataTableSearch",
		}
		// Used when bJQueryUI is false
		$.extend($.fn.dataTableExt.oStdClasses, extensions);

		var table = $('#myDatatable').DataTable({
      'sInfoEmpty': "Nessun dato disponibile.",
			'sEmptyTable': "Nessun dato disponibile.",
      'sZeroRecords': "Nessun dato trovato con il filtro selezionato.",
      'searching':true,
      'paging':true,
      'pageLength':10,  //lunghezza della
      'ordering':true,
      'searchHighlight': true

    });
    table.on( 'draw', function () {
        var body = $( table.table().body() );
 
        body.unhighlight();
        body.highlight( table.search() );   
    });