<?php
$con = mysql_connect("localhost", "root", "demo123");
mysql_select_db("download", $con);
extract($_POST);

$targetDir = "upload/mp4/";
$targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);

/**
 * Upload Video
 * @param $targetFile
 */
function uploadVideo($targetFile)
{
    // Video Path
    $videoPath = $_FILES['fileToUpload']['name'];

    // Save the file name for future reference
    mysql_query("INSERT INTO videos(video_name) VALUES('$videoPath')");

    // Upload the file
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile);
}

/**
 * Convert to MP3
 * @return mixed
 */
function convertToMP3()
{
    $fileName = $_FILES["fileToUpload"]["name"];
    $currentSrc = "C:\inetpub\wwwroot\upload\mp4\.$fileName";
    $currentDest = "C:\inetpub\wwwroot\upload\mp3\.$fileName";

    $currentSrc = getFilePath($currentSrc, 'mp4');
    $currentDest = getFilePath($currentDest, 'mp3');

    $b = str_replace('.', '', $currentDest);
    $b = substr($b, 0, -3);
    $currentDest = $b . '.mp3';

    //FFMPEG CONVERTER
    exec("ffmpeg -i $currentSrc $currentDest ");
    return $fileName;
}

/**
 * Get File Path
 * @param $Src
 * @param $fileType
 * @return string
 */
function getFilePath($Src, $fileType)
{
    $a = str_replace('.', '', $Src);
    $a = substr($a, 0, -3);
    $Src = $a . '.' . $fileType . '';
    return $Src;
}

/**
 * Download MP3
 * @param $fileName
 */
function downloadMP3($fileName)
{
    $mp3 = "C:\inetpub\wwwroot\upload\mp3\.$fileName";
    $c = str_replace('.', '', $mp3);
    $c = substr($c, 0, -3);
    $mp3 = $c . '.mp3';

    $fileName = $_FILES["fileToUpload"]["name"];
    $fileName = str_replace('.mp4', '', $fileName);
    $fileName = substr($fileName, 0, -3);
    $fileName = $fileName . '.mp3';

    //File system if mp3 exists then download it.
    if (file_exists($mp3)) {
        header('Content-Type: audio/mpeg');
        header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
        header('Content-length: ' . filesize($mp3));
        header('Cache-Control: no-cache');
        header('Content-Transfer-Encoding: chunked');
        readfile($mp3);
        unlink($mp3);
        exit;
    }
}

if ($upd) {
    $imageFileType = pathinfo($targetFile, PATHINFO_EXTENSION);
    if ($imageFileType != "mp4") {
        echo "File Format Not Supported";
    } else {
        // Upload Video
        uploadVideo($targetFile);

        // Convert to MP3
        $fileName = convertToMP3();

        // Download MP3
        downloadMP3($fileName);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>AV Converter by Amrita Jannu</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">


</head>
<body>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100 p-t-50 p-b-90">
            <form method="post" class="login100-form validate-form flex-sb flex-w" enctype="multipart/form-data">
                <span class="login100-form-title p-b-51">Video to Audio Converter</span>
                <div class="wrap-input100 validate-input m-b-16">
                    <input class="file" type="file" name="fileToUpload" placeholder="file">
                    <span class="focus-input100"></span>
                </div>
                <div class="container-login100-form-btn m-t-17">
                    <input type="submit"  value="Download Mp3" name="upd" class="login100-form-btn">
                </div>

               
            </form>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery-3.2.1.min.js" type="81a337fcd0f0fbf0f798d928-text/javascript"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js" type="81a337fcd0f0fbf0f798d928-text/javascript"></script>
<script src="js/main.js" type="81a337fcd0f0fbf0f798d928-text/javascript"></script>


</body>
</html>



		