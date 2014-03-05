<?php
require_once('../../includes/initialize.php');

function getLocations(){
	global $connection;
	global $database;
	global $locationClass;
	$buildings = $locationClass->find_all_buildings();
	echo "<option value=''></option>";
	//--------------------------------- Start Buildings ---------------------------------
	foreach ($buildings as $building) {
		if($building['building_name']) {
			echo "<optgroup label='".$building['building_name']."'>";
			?>
            <option value="0,<?php echo trim($building['building_id'], '"');?>">
				<?php echo htmlentities("<i class='icon-building'></i> ".trim($building['building_name'], '"'));?>
            </option>
			<?php
			//--------------------------------- Start Sections ---------------------------------
			$sections = $locationClass->find_building_sections($building['building_id']);
			foreach ($sections as $section):
				$section = $locationClass->find_section_by_id($section['section_id']);
				while ($section_assoc = $section->fetch_assoc()) {
					$cells = $locationClass->find_section_cells($section_assoc['section_id']);
					?>
					<option value="1,<?php echo trim($section_assoc['section_id'], '"');?>">
						<?php echo htmlentities("&nbsp;&nbsp;<i class='icon-th-large'></i> ".trim($section_assoc['section_name'], '"'));?>
					</option>
					<?php
					//--------------------------------- Start Cells ---------------------------------
					foreach ($cells as $cell):
                        $cell = $locationClass->find_cell_by_id($cell['cell_id']);
                        while ($cell_assoc = $cell->fetch_assoc()) {?>
							<option value="2,<?php echo trim($cell_assoc['cell_id'], '"');?>">
								<?php echo htmlentities("&nbsp;&nbsp;&nbsp;&nbsp;<i class='icon-th'></i> ".trim($cell_assoc['cell_name'], '"'));?>
							</option><?php
						}
					endforeach;
					//--------------------------------- Stop Cells ---------------------------------
				}
			endforeach;
			while ($section=$sections->fetch_assoc());
			echo "</optgroup>";
			//--------------------------------- Stop Sections ---------------------------------
		}
	}	
	//--------------------------------- Stop Buildings ---------------------------------
}
getLocations();
?>
