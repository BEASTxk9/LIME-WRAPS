<?php
// ADMIN
add_shortcode('admin_single', 'admin_single');
function admin_single($id)
{
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

    // Get the id from the URL
    $id = $_GET['id'];

    // SQL query to retrieve data from the table
    $admin = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$id");
    

    // ____________________________________________________________________________    
    // HTML DISPLAY

    // external links
    $output = '
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  ';


  $output .= '
  <div class="container">
    <div class="row justify-content-center">
        <!-- img -->
<div class="col-sm-12 col-md-5">
<p>'.$admin->id.'</p>
<img class="single-img" height="100%" width="100%" src="' . $admin->img_url . '" alt="' . $admin->category . '">
</div>
<!-- text -->
<div class="col-sm-12 col-md-5 single_text">
    <p>'.$admin->category.'</p>
    <p>'.$admin->client_vehicle.'</p>
</div>
    </div>
</div>
  ';


$output.='
<style scoped>

.single-img{
    border-top-right-radius: 15px;
    border-bottom-left-radius: 15px;
}

.single_text{
    background-color: rgba(255, 255, 255, 0.493);
    color: #deef3f !important;
}

</style>
';

    // ____________________________________________________________________________  
    return $output;
}
?>
