<?php
function delete_admin($id)
{
    // ____________________________________________________________________________
    // connect to database.
    global $wpdb;
    // check connection
    if (!$wpdb) {
        $wpdb->show_errors();
    }

    // ____________________________________________________________________________
    // Table name
    $table_name = $wpdb->prefix . 'admin';
    // SQL query to delete the row with the specified ID
    $wpdb->delete($table_name, array('id' => $id));
}


// _______________________________
// Call the delete ADMIN function
if (isset($_GET['action']) && $_GET['action'] == 'delete(admin)' && isset($_GET['id'])) {
    delete_admin($_GET['id']);
    // Redirect to the student details page after deletion
    header('location:' . site_url() . '/admin/');
    exit;
}




?>