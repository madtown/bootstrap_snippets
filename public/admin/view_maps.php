<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php
  // Find all the photos
  $photos = $mapClass->find_all();
?>
<?php include_layout_template('admin_header.php'); ?>
<?php include_layout_template('navbar.php'); ?>
<div class="container col-md-12 col-md-offset-0">
    <div class="col-md-12 col-md-offset-0">	
        <div class="center-text"><h2>Maps</h2></div>
		<?php echo output_message($message); ?>
        <table class="table table-striped table-hover">
          <tr>
            <th style='text-align:center;vertical-align:middle'>View</th>
            <th style='text-align:center;vertical-align:middle'>Map Name</th>
            <th style='text-align:center;vertical-align:middle'>Location</th>
            <th style='text-align:center;vertical-align:middle'>Created</th>
            <th style='text-align:center;vertical-align:middle'>Author</th>
            <th style='text-align:center;vertical-align:middle'>Delete</th>
          </tr>
        <?php foreach($photos as $photo): ?>
          <tr>
            <td style='text-align:center;vertical-align:middle'><a href="<?php echo $photo->image_path(); ?>"><h1><i class="icon-globe icon-2x"></i></h1></a></td>
            <td style='text-align:center;vertical-align:middle'><?php echo $photo->map_name; ?></td>
            <td style='text-align:center;vertical-align:middle'><?php echo $photo->location; ?></td>
            <td style='text-align:center;vertical-align:middle'><h6><?php echo $photo->timestamp; ?></h6></td>
            <td style='text-align:center;vertical-align:middle'><?php echo $photo->username; ?></td>
            <td style='text-align:center;vertical-align:middle'><a class="btn btn-danger" style='text-align:center;vertical-align:middle' href="delete_map.php?id=<?php echo $photo->id; ?>" onclick="return confirm('Are you sure you want to delete <?php echo $photo->map_name; ?>? This action CANNOT be undone. Proceed with deletion? Last warning.');"><i class="icon-trash icon-4x"> </i></a></p>
            </td>
          </tr>
        <?php endforeach; ?>
        </table>
        <br />
        <a class="btn btn-success" style='text-align:center;vertical-align:middle' href="edit_maps.php"><i class="icon-plus"></i> New map</a>
    </div>
</div>
		
<?php include_layout_template('admin_footer.php'); ?>
