<?php

$link_page_url = $module->getUrl("link.php") . "&NOAUTH";
$rid_field_name = $module->getRecordIdField();
$target_links = $module->getTargetLinks();
$counter_fields = $module->getCounterFields();

foreach ($target_links as $index => $link) {
	$counter_link = $link_page_url . "&target_link=$index&rid=[$rid_field_name]";
	echo "<div class='card'>
			<h5 class='card-title mx-3 mt-3 mb-0'>Target Link #$index:<br>($link)</h5>
			<hr>
		<div class='card-body pt-0'>
			<h6>Counter Link:</h6><h6>$counter_link</h6>
		</div>
	</div><br>";
}

// show logged count_errors in a table
$query = $module->queryLogs("SELECT timestamp, error_message, request_uri, save_errors WHERE message=?", [
	"count_error"
]);
$error_counts = [];
$offending_uris = [];
while ($row = $query->fetch_assoc()) {
	$error_counts[$row['error_message']]++;
	$offending_uris[$row['error_message']] = $row['request_uri'];
}

if (!empty($module->getProjectSetting("show_error_table_in_links_page"))) {
	$table = "<table id='click_errors'>
		<thead>
			<th>Error</th>
			<th>Count</th>
			<th>Example URI</th>
		</thead>
		<tbody>";
	foreach ($error_counts as $errmsg => $count) {
		$table .= "<tr>
			<td>$errmsg</td>
			<td>$count</td>
			<td>{$offending_uris[$errmsg]}</td>
		</tr>";
	}
	$table .= "</tbody>
		</table>";
		
	echo ($table);
	?>

	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf8">
		ClickCounter = {}
		$(function() {
			ClickCounter.click_errors_datatable = $("table#click_errors").DataTable({
				pageLength: 10,
				order: [
					[1, 'desc']
				]
			});
		});
	</script>

	<?php
}