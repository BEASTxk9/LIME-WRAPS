<?php
// register shortcode
add_shortcode('admin-create', 'admin_create');
// create function
function admin_create()
{
    // ____________________________________________________________________________    
    // connect to database.
    global $wpdb;
    // check connection
    if (is_null($wpdb)) {
        $wpdb->show_errors();
    }

    // ____________________________________________________________________________    
    // Set table name that is being called
    $table_name = $wpdb->prefix . 'admin';

    // if content is added/submitted/posted take the data and do the msql add query
    if (isset($_POST['submit'])) {
        // id is automatically set
        $client = $_POST['client'];
        $client_details = $_POST['client_details'];
        $client_vehicle = $_POST['client_vehicle'];
        $categories = isset($_POST['category']) ? $_POST['category'] : array(); // Use an array to store the selected categories
        $job_status = $_POST['job_status'];
        $complete_date = $_POST['complete_date'];
        $img_url = $_POST['img_url'];
        $price = $_POST['price'];
        $user_id = $_POST['user_id'];
        $user_name = $_POST['user_name'];
        // Retrieve the current user information
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        $user_name = $current_user->display_name;
 
        // mysql add query
        $sql = "INSERT INTO $table_name (client, client_details, client_vehicle, category, job_status, complete_date, img_url, price, post_date, user_id, user_name) 
        values('$client', '$client_details', '$client_vehicle', '" . implode(",", $categories) . "', '$job_status', '$complete_date', '$img_url', '$price', NOW(), '$user_id', '$user_name')";

        $result = $wpdb->query($sql);
        
        // if successful redirect
        if ($result) {
            $redirect_url = site_url('/admin/');
            ?>
            <script>
                window.location.href = "<?php echo $redirect_url; ?>";
            </script>
            <?php
            exit;
        } else {
            wp_die($wpdb->last_error);
        }
    }

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
            <div class="col-sm-12">
                <form method="POST" action="">
                <!-- client -->
                <label for="client">client</label><br>
                <input type="text" id="client" name="client" placeholder="Enter Client Name." required><br>
                <!-- client_details -->
                <label for="client_details">Client Contact Details</label><br>
                <input type="text" id="client_details" name="client_details" placeholder="Enter Client Contact Details." required><br>
                <!-- client_vehicle -->
                <label for="client_vehicle">Client vehicle</label><br>
                <input type="text" id="client_vehicle" name="client_vehicle" placeholder="Enter Client Vehicle." required><br>
                    <!-- category -->
                    <label for="category">Catgory</label><br>
                    <div id="category-div">
                        <!-- option 1 -->
                        <div>
                            <input type="checkbox" name="category[]" value="Window Graphics and Signs" id="category1">
                            <label for="category1">Window Graphics and Signs</label>
                        </div>
                        <!-- option 2 -->
                        <div>
                            <input type="checkbox" name="category[]" value="Vehicle signage and branding" id="category2">
                            <label for="category2">Vehicle signage and branding</label>
                        </div>
                        <!-- option 3 -->
                        <div>
                            <input type="checkbox" name="category[]" value="Vehicle Wraping" id="category3">
                            <label for="category3">Vehicle Wraping</label>
                        </div>
                        <!-- option 4 -->
                        <div>
                            <input type="checkbox" name="category[]" value="Vehicle Paint Protection" id="category4">
                            <label for="category4">Vehicle Paint Protection</label>
                        </div>
                        <!-- option 5 -->
                        <div>
                            <input type="checkbox" name="category[]" value="Car Window Tinting" id="category5">
                            <label for="category5">Car Window Tinting</label>
                        </div>
                    </div>
                    <!-- job_status -->
                    <label for="job_status">JOB STATUS</label><br>
                    <select id="job_status" name="job_status" required>
                        <option value="Not Complete">Not Complete</option>
                        <option value="Complete">Complete</option>
                    </select><br>
                    <!-- complete_date -->
                    <input type="date" id="complete_date" name="complete_date" style="display: none;"><br>
                    <!-- img_url -->
                    <label for="img_url">IMAGE URL</label> <a id="img_host_link" href="https://postimages.org" target="_blank">(CLICK HERE TO HOST AN IMAGE AND GET THE LINK)</a><br>
                    <input type="text" id="img_url" name="img_url" placeholder="Enter image url." required><br>
                    <!-- price -->
                    <label for="price">price</label><br>
                    <input type="number" id="price" name="price" placeholder="Enter Job Cost/Price." required><br>
                    <!-- submit -->
                    <input class="submit-btn px-5 my-2" type="submit" name="submit" value="Add Details">
                </form>
            </div>
        </div>
    </div>
    ';

    $output .='
    <style scoped>
    
    #category-div {
        border: white 1px solid;
        width: 100%;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 5px;
    }

    form{
        background-color: rgba(255, 255, 255, 0.493);
        color: white;
        border-top-right-radius: 15px;
        border-bottom-left-radius: 15px;
        padding: 10px;
    }

    form label{
        font-size: 1rem;
        font-weight: 300;
        text-transform: uppercase;
        letter-spacing: 3px;
    }


    input, select{
        color: white !important;
    }

    #img_host_link{
        color: black;
        text-decoration: none;
        text-transform: uppercase;
        transition: all 0.3s ease-in-out;
    }

    #img_host_link:hover{
        text-decoration: underline;
    }

    .submit-btn{
        background-color: #deef3f;
        color: black !important;
        width:100%;
    }

    </style>
    ';

    // external links
    $output .= '
    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    ';

    // JavaScript/jQuery code for hiding/showing the complete_date field
    $output .= '
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
            $("#job_status").change(function() {
                if ($(this).val() === "Complete") {
                    $("#complete_date").show();
                } else {
                    $("#complete_date").hide();
                }
            });
        });
    </script>
    ';

    // Return the create item form in html
    return $output;
}
?>
