<!DOCTYPE html>
<html>
<head>
    <title>PDF Conversion Progress</title>

    <style>
	.progressbar_block{
		width: 98%;
  background: #eee;
  border-radius: 3px;
  overflow: hidden;
  border: 1px solid green;
  padding: 4px;
	} 
.popup-overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 999;
    }
        /* Close button */
    .popup-close {
      position: absolute;
      top: 8px; right: 12px;
      font-size: 20px;
      cursor: pointer;
      color: #888;
    }

    .popup-close:hover {
      color: #000;
    }
       #uploadModal ,#errorModal{
       position: fixed;

  
  top: 50%;
  left: 50%;

    
  transform: translate(-50%, -50%);
  width: 500px;             
  max-height: 550px;            
  padding: 30px 40px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  text-align: center;
  font-size: 15px;
  overflow: auto;
  z-index: 1000;        
}

	.uploadMessage {
		width:100%
	}
    </style>
</head>
<body>

<form>
    <input type="file" name="file" id="file" multiple >
    <input type="button" value="Upload" onclick="uploadFile()">
</form>


<div class="popup-overlay" id="popup">
<div id="uploadModal"  >
	 <span class="popup-close" onclick="closePopup()">x</span>
  <div>
    <h1 style="font-size:20px">File Upload Progress</h1>
	
    <div   class="progressbar_block" >
      <div id="progressBar" style="width:0%; height:20px; background:#4caf50;"></div>
    </div>
    <p id="progressText">0%</p>

  </div>
  <div id="display" style="white-space: pre-wrap;"></div>
</div>
</div>
  

<script src="js/jquery-1.11.0.min.js"></script>
<script>


function uploadFile() {
    const files = $('#file')[0].files;
    if (files.length === 0) {
        alert('Please select at least one file.');
        return;
    }

    const fd = new FormData();
    for (let i = 0; i < files.length; i++) {
        fd.append('file[]', files[i]);
    }
    fd.append('upload', 'true');

        $('#uploadModal').fadeIn();
		$('#popup').fadeIn();
        $('#progressBar').css('width', '0%');
        $('#progressText').text('0%');
       


    const xhr = new XMLHttpRequest();

    xhr.upload.addEventListener('progress', function (e) {
        if (e.lengthComputable) {
            let percent = Math.round((e.loaded / e.total) * 100);
            $('#progressBar').css('width', percent + '%');
            $('#progressText').text(percent + '%');
        }
    });

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.LOADING || xhr.readyState === XMLHttpRequest.DONE) {
     
            document.getElementById("display").textContent = xhr.responseText;

            console.log(xhr.responseText);
        }
        if (xhr.readyState === XMLHttpRequest.DONE) {
            console.log("Upload complete");
        }
    };

    xhr.open('POST', 'u.php', true);
    xhr.send(fd);




   /* $.ajax({
        url: 'u.php',
        type: 'POST',
        data: fd,
        processData: false,
        contentType: false
    });

    // Start polling progress
    const interval = setInterval(function(){
        $.getJSON('u.php?progress=1', function(data) {
            if (data.total > 0) {
                let percent = Math.round((data.page / data.total) * 100);
                $('#progressFill').css('width', percent + '%');
                $('#progressText').text(data.status + ' - Page ' + data.page + ' of ' + data.total);
                if (percent >= 100) clearInterval(interval);
            } else {
                $('#progressText').text(data.status);
            }
        });
    }, 500);*/
}


function closePopup() {
  document.getElementById("popup").style.display = "none";
    document.getElementById("popup").style.display = "none";
   location.reload();
  
}
</script>

</body>
</html>
