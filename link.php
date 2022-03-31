<?php

// get target links and counter fields
$target_links = $module->getTargetLinks();
$counter_fields = $module->getCounterFields();

// get request URI and parse parameters
$uri = $_SERVER['REQUEST_URI'];
parse_str($uri, $args);

$uri_clean = htmlentities($uri, ENT_QUOTES);
$subsetting_index = htmlentities($args['target_link'], ENT_QUOTES);
$rid = htmlentities($args['rid'], ENT_QUOTES);

// determine the target link and record ID
if (empty($subsetting_index) && $subsetting_index != "0") {
	$module->log("count_error", [
		"error_message" => "Encountered an empty subsetting_index value.",
		"request_uri" => $uri_clean
	]);
	exit();
}
$subsetting_index = (int) $subsetting_index;
$target_link = $target_links[$subsetting_index];
$counter_field = $counter_fields[$subsetting_index];

if (empty($target_link) or empty($counter_field)) {
	$module->log("count_error", [
		"error_message" => "Encountered a missing target_link or counter_field value.",
		"request_uri" => $uri_clean
	]);
	exit();
}


// update counter
$rid_field = $module->getRecordIdField();
$get_parameters = [
	"project_id" => $module->getProjectId(),
	"return_format" => "json",
	"records" => $rid,
	"fields" => [$counter_field, $rid_field]
];

$data = json_decode(\REDCap::getData($get_parameters));

if (empty($data) or empty($data[0])) {
	$module->log("count_error", [
		"error_message" => "Data returned from getData was empty.",
		"request_uri" => $uri_clean
	]);
	exit();
} else {
	$data = $data[0];
}

if ($data->$rid_field != $rid) {
	$module->log("count_error", [
		"error_message" => "\\REDCap::getData returned data but the $rid_field value did't match.",
		"request_uri" => $uri_clean
	]);
	exit();
}

if (empty($data->$counter_field)) {
	$data->$counter_field = 1;
} else {
	$data->$counter_field = $data->$counter_field + 1;
}

$save_params = [
	"project_id" => $module->getProjectId(),
	"dataFormat" => "json",
	"data" => json_encode([$data]),
	"overwriteBehavior" => "overwrite"
];
$save_result = \REDCap::saveData($save_params);

if (empty($save_result)) {
	$module->log("count_error", [
		"error_message" => "Empty save result after trying to update record's click counter field value.",
		"request_uri" => $uri_clean
	]);
}
if (empty(!$save_result['errors'])) {
	$module->log("count_error", [
		"error_message" => "Counter update save attempt returned errors.",
		"request_uri" => $uri_clean,
		"save_errors" => implode($save_result['errors'], "\n")
	]);
}

// forward user to target link
header("Location: $target_link");
exit();
