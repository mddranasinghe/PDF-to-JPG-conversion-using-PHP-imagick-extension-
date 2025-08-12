<?php
 while (ob_get_level()) {
    ob_end_clean();
}  
header('Content-Type: text/plain'); // or text/event-stream for SSE
header('Cache-Control: no-cache');
header('X-Accel-Buffering: no');


if (!empty($_POST['upload']) && isset($_FILES['file'])) {
    if (!empty($_FILES['file']['name'][0])) {



        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($_FILES['file']['name'] as $key => $filename) {
            $tempPath = $_FILES['file']['tmp_name'][$key];

            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if ($ext !== 'pdf') {
                echo "Skipping {$filename} (not a PDF).\n";
                flush();
                continue;
            }


            try {

            ob_end_clean();
                
              echo "{$filename} Starting Convert.......... \n";
                flush();
                $imagick = new Imagick();

                $imagick->setResolution(100, 100);
                 
                $imagick->readImage($tempPath);
             

                $imagick->setImageFormat('jpg');

                $i = 1;
                foreach ($imagick as $page) {
               
                    $page->setImageFormat('jpg');
                    $imagePath = $uploadDir . pathinfo($filename, PATHINFO_FILENAME) . "_page_{$i}.jpg";
                    if ($page->writeImage($imagePath)) {
                        sleep(1);
                        echo "Saved image: {$imagePath} \n";
                        flush();
                       

                    
                    } else {
                        echo "Error saving image for page {$i} of {$filename}.\n";
                          flush();
                    }
                    $i++;
                   
             

                }

                $imagick->clear();
                $imagick->destroy();
                echo "All done!\n";
                flush();
            } catch (Exception $e) {
                echo "Error processing {$filename}: " . $e->getMessage() . "\n";
                  flush();
            }
        }
    } else {
        echo "No files selected for upload.";
          flush();
    }
    exit;
}















// Progress tracking file
//$progressFile = sys_get_temp_dir() . "/pdf_convert_progress.json";

// Reset progress at start
/*file_put_contents($progressFile, json_encode([
    'status' => 'starting',
    'page' => 0,
    'total' => 0
]));

if (!empty($_POST['upload']) && isset($_FILES['file'])) {
    if (!empty($_FILES['file']['name'][0])) {

        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        foreach ($_FILES['file']['name'] as $key => $filename) {
            $tempPath = $_FILES['file']['tmp_name'][$key];
            $pdfName = pathinfo($filename, PATHINFO_FILENAME);

            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if ($ext !== 'pdf') continue;

            try {
                $imagick = new Imagick();
                $imagick->setResolution(100, 100);
                $imagick->readImage($tempPath);

                $totalPages = $imagick->getNumberImages();
                file_put_contents($progressFile, json_encode([
                    'status' => "converting $filename",
                    'page' => 0,
                    'total' => $totalPages
                ]));

                $i = 0;
                foreach ($imagick as $page) {
                    $page->setImageFormat('jpg');
                    $imagePath = $uploadDir . "{$pdfName}_page_{$i}.jpg";
                    $page->writeImage($imagePath);

                    // Update progress after each page
                    file_put_contents($progressFile, json_encode([
                        'status' => "Converting $filename",
                        'page' => $i + 1,
                        'total' => $totalPages
                    ]));

                    usleep(300); // just to simulate slower processing for testing
                    $i++;
                }

                $imagick->clear();
                $imagick->destroy();

                file_put_contents($progressFile, json_encode([
                    'status' => "Completed $filename",
                    'page' => $totalPages,
                    'total' => $totalPages
                ]));

            } catch (Exception $e) {
                file_put_contents($progressFile, json_encode([
                    'status' => 'error: ' . $e->getMessage(),
                    'page' => 0,
                    'total' => 0
                ]));
            }
        }
    }
    exit;
}

// Progress API
if (isset($_GET['progress'])) {
    header('Content-Type: application/json');
    if (file_exists($progressFile)) {
        echo file_get_contents($progressFile);
    } else {
        echo json_encode(['status' => 'idle', 'page' => 0, 'total' => 0]);
    }
    exit;
}*/
?>