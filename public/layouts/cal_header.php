<!DOCTYPE html>
<html lang="en">
  <head>
<!-- Miscellaneous header stuff -->
	<title>Fabrication Technologies: Logged In</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8">
	<meta name="google-translate-customization" content="c204f825f07c97ed-5726fe290189ae1c-g429a6c1d35fb055c-34"></meta>
<!-- Included Stylesheets -->
	<link rel="stylesheet" href="../stylesheets/dist/css/bootstrap.min.css" media="screen">
    <link href="../stylesheets/dist/css/bootstrap-theme.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../stylesheets/bootstrap-calendar-master/css/calendar.min.css">
	<link rel="stylesheet" href="../stylesheets/font-awesome/css/font-awesome.min.css" media="screen">
	<link rel="shortcut icon" href="../stock_images/logged_in_icon.jpg">
<!-- Included 3rd Party Scripts -->
    <script type="text/javascript" src="../stylesheets/bootstrap-calendar-master/components/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="../stylesheets/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../stylesheets/bootstrap-calendar-master/components/underscore/underscore-min.js"></script>
    <script type="text/javascript" src="../stylesheets/bootstrap-calendar-master/js/calendar.js"></script>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
<!-- Custom Styling Modifications -->
<style type="text/css">
	body {
        padding-bottom: 40px;
      }
/* Navbar links: increase padding for taller navbar */
    .navbar .nav > li > a {
      padding: 15px 20px;
    }

/* Offset the responsive button for proper vertical alignment */
    .navbar .btn-navbar {
      margin-top: 25px;
    }

	img.brand {
	  padding: 0px;
	}
/* Featurettes ------------------------- */
    /* Center align the text within the three columns below the carousel */
    .center-text {
      text-align: center;
    }

    .featurette-divider {
      margin: 5px 0; /* Space out the Bootstrap <hr> more */
    }
    .featurette {
      padding-top: 120px; /* Vertically center images part 1: add padding above and below text. */
      overflow: hidden; /* Vertically center images part 2: clear their floats. */
    }
    .featurette-image {
      margin-top: -120px; /* Vertically center images part 3: negative margin up the image the same amount of the padding to center it. */
    }

    /* Give some space on the sides of the floated elements so text doesn't run right into it. */
    .featurette-image.pull-left {
      margin-right: 40px;
    }
    .featurette-image.pull-right {
      margin-left: 40px;
    }

    /* Thin out the marketing headings */
    .featurette-heading {
      font-size: 50px;
      font-weight: 300;
      line-height: 1;
      letter-spacing: -1px;
    }
    .carousel-indicators {
     position: auto;
     left: 45%;
	}
	
	.dropdown-backdrop {
 	position: static;
	}
	
/*	body {
		background-image:url('../stylesheets/brushed_alu/brushed_alu_@2X.png');
	}*/
	
</style>
<script type="text/javascript">
//A function returning the string displayed if options are selected. All currently selected options are passed as argument.
		
//footer clock script
		function updateClock ( )
		{
		  var currentTime = new Date ( );

		  var currentHours = currentTime.getHours ( );
		  var currentMinutes = currentTime.getMinutes ( );
		  var currentSeconds = currentTime.getSeconds ( );

		  // Pad the minutes and seconds with leading zeros, if required
		  currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
		  currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

		  // Choose either "AM" or "PM" as appropriate
		  var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

		  // Convert the hours component to 12-hour format if needed
		  currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

		  // Convert an hours component of "0" to "12"
		  currentHours = ( currentHours == 0 ) ? 12 : currentHours;

		  // Compose the string for display
		  var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;

		  // Update the time display
		  document.getElementById("clock").firstChild.nodeValue = currentTimeString;
		}

</script>
</head>