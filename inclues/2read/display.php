<?php
// Register the shortcode
add_shortcode('display', 'display_card');

// shortcode function
function display_card(){
// ____________________________________________________________________________
// connect to database.
  global $wpdb;
// check connection
  if (!$wpdb) {
    $wpdb->show_errors();
  }

  // ____________________________________________________________________________
  // Set table name that is being called
  $table_name = $wpdb->prefix . 'admin';

  // SQL query to retrieve data from the table
  $data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY post_date DESC");

  // ____________________________________________________________________________
// HTML DISPLAY

// external links
$output = '
<!-- bootstrap css -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
';

 $output .= '
 <div class="container">
 <div class="row justify-content-center">
 ';
// for each data item in the table
  foreach ($data as $i) {
    $output .= '<div class="col-sm-12 col-md-5 col-lg-3" id="display_card">';
    $output .= '<img id="image" height="100%" width="100%" src="' . $i->img_url . '" alt="' . $i->client_vehicle . '">';
    $output .= '<p>Category: ' . $i->category . '</p>';
    $output .= '<p>Vehicle: ' . $i->client_vehicle . '</p>';
    $output .= '        <!-- VIEW BUTTON -->
    <a href="' . site_url() . '/admin_single/?id=' . $i->id . '" class="button-view btn">View</a>
    <a class="get_a_quote btn" href="https://form.jotform.com/shanestevensxk9/lime-wraps-quote-request-form" target="_blank">Get a quote</a>
    ';
    $output .= '</div>';
  }

  $output .= '
  </div>
  </div>
  ';

  $output.='
  <style scoped>
  #display_card{
    background-color: rgba(255, 255, 255, 0.493);
    color: #deef3f !important;
    margin: 10px;
    padding: 10px;
    border-top-right-radius: 15px;
    border-bottom-left-radius: 15px;
    font-weight: 500;
  }

  #image{
    border-top-right-radius: 15px;
    border-bottom-left-radius: 15px;
    margin-bottom: 5px;
    min-height: 25vh;
    border: 1px solid grey;
  }

  .button-view, .get_a_quote{
    width: 100%;
    background-color: #111930 !important;
    color: #deef3f;
    font-weight: 500;
    margin: 5px;
  }
  </style>
  ';

// external links
  $output .= '
  <!-- bootstrap js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  ';
  // ____________________________________________________________________________
  // Return the table html
  return $output;
}

?>
