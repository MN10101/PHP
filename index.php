<?php
if(isset($_POST['button'])){
  $videoUrl = $_POST['imgurl'];
  $ch = curl_init($videoUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $downloadVideo = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  // Check for server error
  if($httpCode != 200) {
      echo "Failed to download video. Please try again later.";
      exit;
  }

  // Basic validation (replace with ffmpeg check if possible)
  if (strlen($downloadVideo) < 100) {
      echo "Downloaded content seems invalid. Please try again later.";
      exit;
  }

  // Continue with serving video content if successful
  header('Content-Type: video/mp4');
  header('Content-Disposition: attachment; filename="video.mp4"');
  echo $downloadVideo;
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <title>Download Video</title>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <header>Download Video Now</header>
        <div class="url-input">
            <span class="title">Paste video url:</span>
            <div class="field">
                <input type="text" name="imgurl" placeholder="https://www.youtube.com/watch?v=lqwdD2ivIbM" required>
                <span class="bottom-line"></span>
            </div>
        </div>
        <div class="preview-area">
            <img class="thumbnail" src="" alt="">
            <i class="icon fas fa-cloud-download-alt"></i>
            <span>Paste video url to see preview</span>
        </div>
        <button class="download-btn" type="submit" name="button">Download Video</button>
    </form>
    
</body>
<script>
    // Selecting necessary elements from the HTML
    const urlField = document.querySelector(".field input"), // Input field for the video URL
        previewArea = document.querySelector(".preview-area"), // Div for displaying the video thumbnail preview
        imgTag = previewArea.querySelector(".thumbnail"), // Image tag for displaying the thumbnail
        button = document.querySelector(".download-btn"); // Download button

    // Function to execute when the user types in the video URL input field
    urlField.onkeyup = () => {
        // Getting the value of the input field
        let imgUrl = urlField.value;
        
        // Adding 'active' class to the preview area
        previewArea.classList.add("active");
        
        // Enabling the download button
        button.style.pointerEvents = "auto";
        
        // Checking if the URL is from YouTube
        if (imgUrl.indexOf("https://www.youtube.com/watch?v=") != -1) {
            // Extracting the YouTube video ID
            let vidId = imgUrl.split('v=')[1].substring(0, 11);
            // Generating the URL for the YouTube video thumbnail
            let ytImgUrl = `https://img.youtube.com/vi/${vidId}/maxresdefault.jpg`;
            // Setting the thumbnail source
            imgTag.src = ytImgUrl;
        } else if (imgUrl.indexOf("https://youtu.be/") != -1) {
            // Extracting the YouTube video ID
            let vidId = imgUrl.split('be/')[1].substring(0, 11);
            // Generating the URL for the YouTube video thumbnail
            let ytImgUrl = `https://img.youtube.com/vi/${vidId}/maxresdefault.jpg`;
            // Setting the thumbnail source
            imgTag.src = ytImgUrl;
        } else if (imgUrl.match(/\.(jpe?g|png|gif|bmp|webp)$/i)) {
            // Checking if the URL is an image URL
            // Setting the thumbnail source
            imgTag.src = imgUrl;
        } else {
            // If the URL is not recognized as a YouTube URL or an image URL
            // Clearing the thumbnail source
            imgTag.src = "";
            // Disabling the download button
            button.style.pointerEvents = "none";
            // Removing 'active' class from the preview area
            previewArea.classList.remove("active");
        }
    }
</script>

</html>