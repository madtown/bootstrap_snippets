<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
global $locationClass;
global $machineClass;
?>
<?php include_layout_template('admin_header.php'); ?>
<div class="container">
	<h1 class="center-text">Equipment Table Structure<a href="#equipment" class="btn btn-inverse pull-right">Equipment Data</a></h1>
	<pre>
    	<?php 
			$equipments_column = $database->query("DESCRIBE equipment");
			foreach ($equipments_column as $equipment) {
				print_r($equipment);
			}
			$equipments = $machineClass->find_every_single_machine();
		?>
    </pre>
    <h1 class="center-text">Building Table Structure<a href="#building" class="btn btn-inverse pull-right">Building Data</a></h1>
    <pre>
    	<?php 
			$buildings_column = $database->query("DESCRIBE building");
			foreach ($buildings_column as $building) {
				print_r($building);
			}
			$buildings = $locationClass->find_every_single_building();
		?>
    </pre>
    <h1 class="center-text">Section-Building Relationship Table Structure<a href="#section_building" class="btn btn-inverse pull-right">Section-Building Data</a></h1>
    <pre>
    	<?php 
			$section_buildings_column = $database->query("DESCRIBE section_building");
			foreach ($section_buildings_column as $section_building) {
				print_r($section_building);
			}
			$section_buildings = $locationClass->find_every_single_section_building();
		?>
    </pre>
    <h1 class="center-text">Section Table Structure<a href="#section" class="btn btn-inverse pull-right">Section Data</a></h1>
    <pre>
    	<?php 
			$sections_column = $database->query("DESCRIBE section");
			foreach ($sections_column as $section) {
				print_r($section);
			}
			$sections = $locationClass->find_every_single_section();
		?>
    </pre>
    <h1 class="center-text">Cell-Section Relationship Table Structure<a href="#cell_section" class="btn btn-inverse pull-right">Cell-Section Data</a></h1>
    <pre>
    	<?php 
			$cell_section_column = $database->query("DESCRIBE cell_section");
			foreach ($cell_section_column as $cell_section) {
				print_r($cell_section);
			}
			$cell_sections = $locationClass->find_every_single_cell_section();
		?>
    </pre>
    <h1 class="center-text">Cell Table Structure<a href="#cell" class="btn btn-inverse pull-right">Cell Data</a></h1>
    <pre>
    	<?php 
			$cells_column = $database->query("DESCRIBE cell");
			foreach ($cells_column as $cell) {
				print_r($cell);
			}
			$cells = $locationClass->find_every_single_cell();
		?>
    </pre>
    <section id="equipment">
    	<h1 class="center-text">Equipment Table Data</h1>	
        <pre>
            <?php 
                foreach ($equipments as $equipment) {
                    print_r($equipment);
                }
            ?>
        </pre>
 	</section>
    <section id="building">	
    	<h1 class="center-text">Building Table Data</h1>
        <pre>
            <?php 
                foreach ($buildings as $building) {
                    print_r($building);
                }
            ?>
        </pre>
 	</section>
    <section id="section_building">	
    	<h1 class="center-text">Section-Building Table Data</h1>
        <pre>
            <?php 
                foreach ($section_buildings as $section_building) {
                    print_r($section_building);
                }
            ?>
        </pre>
 	</section>
    <section id="section">	
    	<h1 class="center-text">Section Table Data</h1>
        <pre>
            <?php 
                foreach ($sections as $section) {
                    print_r($section);
                }
            ?>
        </pre>
 	</section>
    <section id="cell_section">	
    	<h1 class="center-text">Cell-Section Table Data</h1>
        <pre>
            <?php 
                foreach ($cell_sections as $cell_section) {
                    print_r($cell_section);
                }
            ?>
        </pre>
 	</section>
    <section id="cell">	
    	<h1 class="center-text">Cell Table Data</h1>
        <pre>
            <?php 
                foreach ($cells as $cell) {
                    print_r($cell);
                }
            ?>
        </pre>
 	</section>
</div>
<?php include_layout_template('admin_footer.php'); ?>
		
