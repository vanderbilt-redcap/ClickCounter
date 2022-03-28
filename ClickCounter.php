<?php
namespace Vanderbilt\ClickCounter;

class ClickCounter extends \ExternalModules\AbstractExternalModule {
	
	function redcap_module_link_check_display($project_id, $link) {
		if ($link['name'] == 'Link Forwarding Page') {
			return false;
		}
		return $link;
	}
	
	public function getTargetLinks() {
		$target_links = [];
		$target_link_subsettings = $this->getSubSettings("target_links");
		foreach ($target_link_subsettings as $index => $subsetting) {
			$target_links[$index] = $subsetting['target_link'];
		}
		return $target_links;
	}
	
	public function getCounterFields() {
		$counter_fields = [];
		$target_link_subsettings = $this->getSubSettings("target_links");
		foreach ($target_link_subsettings as $index => $subsetting) {
			$counter_fields[$index] = $subsetting['counter_field'];
		}
		return $counter_fields;
	}
	
}
