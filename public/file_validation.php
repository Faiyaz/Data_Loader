<?php
// If true, then a file has been uploaded under 'data' attribute
if (!empty($_FILES) && isset($_FILES['data'])) {
    // Proceed if no errors
    if ($_FILES["data"]["error"] < 1) {
        // 1. Make sure user supplied a zip file
        if ($_FILES['data']['type'] === 'application/zip') {
            // 2. Proceed only if zip file name passes validation
            if (validate_name($_FILES["data"]["name"])) {
                // Instantiate a new zip archive
                $zip = new ZipArchive();
                $tmp_name = $_FILES["data"]["tmp_name"]; // Get the temporary file
                $zip_name = $_FILES["data"]["name"]; // Get the original zip name

                // Open the zip, and extract the data into an array
                if ($zip->open($tmp_name) === TRUE) {
                    //3. Validate tsv file names exist in the root, and file structure is not nested
                    for ($i=0; $i < $zip->numFiles; $i++) {
                        if (in_array($zip->getNameIndex($i), $filenames_array)) {
                            // Get the file index values only
                            $f_i[] = $i;
                            $good_files[] = $zip->getNameIndex($i);
                        } else {
                            // Collect the bad file names
                            $bad_files[] = $zip->getNameIndex($i);
                        }
                    }  

                    // if the above validation was successful 
                    if (isset($f_i) and count($f_i) >= 4) {
                        require 'db.php';
                    } else { 
                        $_SESSION['error_msg'] = "Sorry, unable to match one or more .tsv files.";
                        redirect('/');
                    }

                    $zip->close();
                } else {
                    $_SESSION['error_msg'] = "Unable to open <strong>$zip_name</strong>.<br/>
                    Check the file and try again.";
                    redirect('/');
                }
            } else {
                $_SESSION['error_msg'] = "Sorry, <strong>" . $_FILES["data"]["name"] . "</strong> doesn't match 
                <strong>yyyy_mm_dd.zip</strong> naming convention.<br/>
                Please try with correct file name.";
                redirect('/');
            }
        } else {
            $_SESSION['error_msg'] = "<strong>{$_FILES["data"]["name"]}</strong> is not a zip file.<br/> 
            Make sure your file ends with <strong>.zip</strong> extension and is a valid zip file";
            redirect('/');
        }
    } else {
        $i = $_FILES['data']['error'];
        $_SESSION['error_msg'] = $file_errors_array[$i];
        redirect('/');
    }
} 
?>