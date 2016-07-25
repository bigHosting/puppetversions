<!-- //skrip id datatables, nanti idnya di panggil di index.php -->
$(document).ready(function() {
	var table = $('#contoh_gan').dataTable({
		'iDisplayLength': 5,
		'aLengthMenu': [[5, 50, 100, 500, -1], [5, 50, 100, 500, "All"]]
	});

	//di bawah ini skrip untuk manggil fitur copy, export csv/excel/pdf dan print
	var tt = new $.fn.dataTable.TableTools( table );
	$( tt.fnContainer() ).insertBefore('div.dataTables_wrapper');
} );