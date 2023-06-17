<?php
// ADMIN
add_shortcode('admin-update', 'admin_update');
function admin_update($id)
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
    // Update activities
    if (isset($_POST['submit'])) {
        $client = $_POST['client'];
        $client_details = $_POST['client_details'];
        $client_vehicle = $_POST['client_vehicle'];
        $category = implode(",", $_POST['category']);
        $job_status = $_POST['job_status'];
        $complete_date = $_POST['complete_date'];
        $img_url = $_POST['img_url'];
        $price = $_POST['price'];
        $post_date = $_POST['post_date'];

        // update mysql query
        $sql = "UPDATE $table_name SET client='$client', client_details='$client_details', client_vehicle='$client_vehicle', category='$category', job_status='$job_status', complete_date='$complete_date', img_url='$img_url', price='$price', post_date='$post_date' WHERE id=$id";
        $result = $wpdb->query($sql);

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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  ';



    $output .= '
<div class="container">
<div class="row justify-content-center">
<div class="col-sm-12">
';
    // ID
    $output .= '<form method="post">';
    $output .= '<input type="hidden" name="id" value="' . $admin->id . '">';

    // client
    $output .= '
        <p>
        <label for="client">Client</label><br>
        <input type="text" id="client" name="client" value="' . $admin->client . '" required>
        </p>';

    // client_details
    $output .= '
    <p>
    <label for="client_details">Client Details</label><br>
    <input type="text" id="client_details" name="client_details" value="' . $admin->client_details . '" required>
    </p>';

    // client_vehicle
    $output .= '
        <p>
        <label for="client_vehicle">Client Vehicle</label><br>
        <input type="text" id="client_vehicle" name="client_vehicle" value="' . $admin->client_vehicle . '" required>
        </p>';

// category
$output .= '
<p>
<label for="category">Category</label><br>';

$categories = explode(",", $admin->category);
$allCategories = array(
    'Window Graphics and Signs',
    'Vehicle signage and branding',
    'Vehicle Wraping',
    'Vehicle Paint Protection',
    'Car Window Tinting'
);

foreach ($allCategories as $category) {
    $checked = in_array($category, $categories) ? 'checked' : '';
    $output .= '<input type="checkbox" name="category[]" value="' . $category . '" ' . $checked . '> ' . $category . '<br>';
}

$output .= '</p>';

$output .= '
<script>
    jQuery(document).ready(function($) {
        // Retrieve the selected categories from the database
        var selectedCategories = ["' . implode('","', $categories) . '"];

        // Check the corresponding checkboxes based on the selected categories
        $.each(selectedCategories, function(index, value) {
            $("input[name=\'category[]\'][value=\'" + value + "\']").prop("checked", true);
        });
    });
</script>';



// post_date
$output .= '
<p>
<label for="post_date">Post Date</label><br>';
// Display current post date first
$output .= '<input type="text" id="post_date" name="post_date" value="' . ($admin->post_date === '0000-00-00 00:00:00' ? 'Not Complete' : date('Y-m-d', strtotime($admin->post_date))) . '" required>';
$output .= '</p>';

    // job_status
    $output .= '
    <p>
    <label for="job_status">Job Status</label><br>
    <select id="job_status" name="job_status" required>';

    // Display current job status first
    $output .= '<option value="' . $admin->job_status . '" selected>' . $admin->job_status . '</option>';
    $output .= '
    <option value="Not Complete">Not Complete</option>
    <option value="Complete">Complete</option>
    ';

    $output .= '
    </select>
    </p>';

    // complete_date
    $output .= '
    <p id="complete_date_field">
    <label for="complete_date">Complete Date</label><br>
    <input id="complete_date" name="complete_date" value="' . ($admin->complete_date === '0000-00-00 00:00:00' ? 'Not Complete' : date('Y-m-d', strtotime($admin->complete_date)))  . '">
    </p>';

    // JavaScript to show/hide the complete_date field based on the job_status value
    $output .= '
    <script>
        jQuery(document).ready(function($) {
            var jobStatusSelect = $("#job_status");
            var completeDateField = $("#complete_date_field");

            jobStatusSelect.change(function() {
                if (jobStatusSelect.val() === "Complete") {
                    completeDateField.show();
                } else {
                    completeDateField.hide();
                }
            });

            // Initial check on page load
            if (jobStatusSelect.val() === "Complete") {
                completeDateField.show();
            } else {
                completeDateField.hide();
            }
        });
    </script>
    ';

    // image
    $output .= '
    <p>
    <label for="img_url">Image URL</label><br>
    <input type="text" id="img_url" name="img_url" value="' . $admin->img_url . '" required>
    </p>';

    // price
    $output .= '
    <p>
    <label for="price">Price</label><br>
    <input type="number" id="price" name="price" value="' . $admin->price . '" required>
    </p>';


    // submit btn
    $output .= '

    <input class="submit-btn px-5" type="submit" name="submit" value="Update">
';

    $output .= '</form>';

    $output .= '
    </div>
    </div>
    </div>
    ';

        // JavaScript validation
        $output .= '
        <script>
            jQuery(document).ready(function($) {
                var form = $("form");
    
                // Check if any input field is modified
                form.find("input, select").on("input change", function() {
                    form.data("modified", true);
                });
    
                // On form submission, check if any input field is modified
                form.submit(function() {
                    if (!form.data("modified")) {
                        alert("Please make changes before submitting.");
                        return false; // Prevent form submission
                    }
                });
            });
        </script>
        ';

$output .= '
<style scoped>
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


.submit-btn{
    background-color: #deef3f;
    color: black !important;
    width:100%;
}

</style>
';

    // ____________________________________________________________________________  
    return $output;
}
?>
