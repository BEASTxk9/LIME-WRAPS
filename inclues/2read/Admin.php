<?php
// Register the shortcode
add_shortcode('admin', 'admin');

// create function
function admin() {
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
    $admin = $wpdb->get_results("SELECT * FROM $table_name");

    

    // ____________________________________________________________________________
    // HTML DISPLAY

    // external links
    $output = '
    <!-- bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    ';

    $output .= '
    <section id="admin-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12">

                    <form class="row" method="get" action="">
                        <div class="col-sm-2 mb-3">
                            <select name="client_filter" class="form-select">
                                <option value="">All Clients</option>
                                ';
    // fetch unique client values from the database
    $unique_clients = $wpdb->get_results("SELECT DISTINCT client FROM $table_name");
    foreach ($unique_clients as $client) {
        $output .= '<option value="' . $client->client . '">' . $client->client . '</option>';
    }

    $output .= '
                            </select>
                        </div>
                        <div class="col-sm-2 mb-3">
                            <select name="vehicle_filter" class="form-select">
                                <option value="">All Vehicles</option>
                                ';
    // fetch unique vehicle values from the database
    $unique_vehicles = $wpdb->get_results("SELECT DISTINCT client_vehicle FROM $table_name");
    foreach ($unique_vehicles as $vehicle) {
        $output .= '<option value="' . $vehicle->client_vehicle . '">' . $vehicle->client_vehicle . '</option>';
    }

    $output .= '
    </select>
</div>
<div class="col-sm-2 mb-3">
    <select name="category_filter" class="form-select">
        <option value="">All Categories</option>';
// Fetch unique category values from the database
$unique_categories = $wpdb->get_results("SELECT DISTINCT category FROM $table_name");
foreach ($unique_categories as $category) {
$output .= '<option value="' . $category->category . '">' . $category->category . '</option>';
}

    $output .= '
                            </select>
                        </div>
                        <div class="col-sm-2 mb-3">
                            <select name="status_filter" class="form-select">
                                <option value="">All Statuses</option>
                                ';
    // fetch unique status values from the database
    $unique_statuses = $wpdb->get_results("SELECT DISTINCT job_status FROM $table_name");
    foreach ($unique_statuses as $status) {
        $output .= '<option value="' . $status->job_status . '">' . $status->job_status . '</option>';
    }

    $output .= '
                            </select>
                        </div>
                        <div class="col-sm-2 mb-3">
                            <select name="price_filter" class="form-select">
                                <option value="">All Prices</option>
                                ';
    // fetch unique price values from the database
    $unique_prices = $wpdb->get_results("SELECT DISTINCT price FROM $table_name");
    foreach ($unique_prices as $price) {
        $output .= '<option value="' . $price->price . '">' . $price->price . '</option>';
    }

    $output .= '
                            </select>
                        </div>
                        <div class="col-sm-2 mb-3">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                        </div>
                    </form>

                    <a href="' . site_url() . '/admin-create/" class="btn button-add my-3">ADD DATA</a>
                    
                    <table id="admin_table" class="table-bordered table table-responsive w-100 d-block d-md-table">
                        <thead>
                            <tr>
                                <th id="th-id">ID</th>
                                <th id="th-client">CLIENT</th>
                                <th id="th-client_details">CLIENT DETAILS</th>
                                <th id="th-client_vehicle">CLIENT VEHICLE</th>
                                <th id="th-category">CATEGORY</th>
                                <th id="th-post_date">DATE</th>
                                <th id="th-job_status">JOB STATUS</th>
                                <th id="th-complete_date">COMPLETE DATE</th>
                                <th id="th-img_url">IMAGE</th>
                                <th id="th-price">PRICE</th>
                                <th id="th-user_name">USER NAME</th>
                                <th id="th-operators">OPERATORS</th>
                            </tr>
                        </thead>

                        <tbody>
    ';

    // Retrieve filter values from the form submission
    $client_filter = isset($_GET['client_filter']) ? sanitize_text_field($_GET['client_filter']) : '';
    $vehicle_filter = isset($_GET['vehicle_filter']) ? sanitize_text_field($_GET['vehicle_filter']) : '';
    $date_filter = isset($_GET['date_filter']) ? sanitize_text_field($_GET['date_filter']) : '';
    $category_filter = isset($_GET['category_filter']) ? sanitize_text_field($_GET['category_filter']) : '';
    $status_filter = isset($_GET['status_filter']) ? sanitize_text_field($_GET['status_filter']) : '';
    $price_filter = isset($_GET['price_filter']) ? sanitize_text_field($_GET['price_filter']) : '';

    // Build the SQL query based on the selected filters
    $query = "SELECT * FROM $table_name WHERE 1=1";

    if (!empty($client_filter)) {
        $query .= " AND client = '$client_filter'";
    }
    if (!empty($vehicle_filter)) {
        $query .= " AND client_vehicle = '$vehicle_filter'";
    }
    if (!empty($date_filter)) {
        $query .= " AND post_date = '$date_filter'";
    }
    if (!empty($category_filter)) {
      $query .= " AND category = '$category_filter'";
  }
    if (!empty($status_filter)) {
        $query .= " AND job_status = '$status_filter'";
    }
    if (!empty($price_filter)) {
        $query .= " AND price = '$price_filter'";
    }

     // Add the ORDER BY clause to sort the results by post_date in descending order
     $query .= " ORDER BY post_date DESC";

     // Execute the filtered query
     $admin = $wpdb->get_results($query);

    // fetch and display data from wpdb
    foreach ($admin as $i) {
        $output .= '<tr>';
        $output .= '<td id="td-id">' . $i->id . '</td>';
        $output .= '<td id="td-client">' . $i->client . '</td>';
        $output .= '<td id="td-client_details">' . $i->client_details . '</td>';
        $output .= '<td id="td-client_vehicle">' . $i->client_vehicle . '</td>';
        $output .= '<td id="td-category">' . $i->category . '</td>';
        $output .= '<td id="td-post_date" width="25%">' . ($i->post_date === '0000-00-00 00:00:00' ? 'Not Complete' : date('Y-m-d', strtotime($i->post_date))) . '</td>';
        $output .= '<td id="td-job_status">' . $i->job_status . '</td>';
        $output .= '<td id="td-complete_date" width="25%">' . ($i->complete_date === '0000-00-00 00:00:00' ? 'Not Complete' : date('Y-m-d', strtotime($i->complete_date))) . '</td>';
        $output .= '<td id="td-img_url">
        <img height="100%" width="100%" src="' . $i->img_url . '" alt="' . $i->category . '">
        </td>';
        $output .= '<td id="td-price">R' . $i->price . '</td>';
        $output .= '<td id="td-user_name">' . $i->user_name . '</td>';
        $output .= '<td id="td-OPERATORS">
        <!-- VIEW BUTTON -->
        <a href="' . site_url() . '/admin_single/?id=' . $i->id . '" class="button-view btn">View</a>
        <!-- UPDATE BUTTON -->
        <a href="' . '/admin-update/?page=wp_admin&action=admin_update&id=' . $i->id . '" class="button-update btn my-2">Update</a>
        <!-- DELETE BUTTON -->
        <a href="' . admin_url('./4delete/delete.php?page=wp_admin&action=delete(admin)&id=' . $i->id) . '" class="button-delete btn">Delete</a>
        </td>';
        $output .= '<tr>';
    }

    $output .= '
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </section>
    ';

    // custom CSS
    $output .= '
    <style scoped>
    section#admin-section{
      min-height: 100vh;
    }

    .form-select{
      background-color: rgba(255, 255, 255, 0.493);
      color: white;
    }


    .button-add{
      width: 100%;
    background-color: rgba(255, 255, 255, 0.493);
    }

    #admin_table thead th{
      background-color: #deef3f;
      color: black;
      font-size: 0.8rem;
    }
    
    table#admin_table{
      overflow-y: scroll !important;
      overflow-x: scroll !important;
      border: black 1px solid !important;
      max-height: 90vh !important;
      background-color: grey;
      color: white;
      padding: 5px;
      font-weight: 300;
    }

    .button-view, .button-update, .button-delete{
      background-color: grey;
      color: white;
      width: 100%; 
    }
    </style>
    ';

    // external links
    $output .= '
    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoPpDW7plMhmoIpbvmYObFd5PrUXB" crossorigin="anonymous"></script>
    ';

    return $output;
}
