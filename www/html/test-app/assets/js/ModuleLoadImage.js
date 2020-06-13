var LoadImagesModule = (function(){
    console.log('load images by ajax');
    Dropzone.options.dropzoneFrom = {
        autoProcessQueue: false,
        acceptedFiles:".png,.jpg,.gif,.bmp,.jpeg",
        parallelUploads: 10,
        init: function(){
            var submitButton = document.querySelector('#clear-photos-btn');
            myDropzone = this;
            submitButton.addEventListener("click", function(){
                myDropzone.processQueue();
            });
            this.on("complete", function($file){
                console.log($file.xhr.response);
                if (this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {
                    var _this = this;
                    _this.removeAllFiles();
                }
                sendPhotoAndSaved();
            });
        },
    };

    var sendPhotoAndSaved = function(callback=console.log){
        $.ajax({
            type: 'POST',
            url: '/save_photos',
            async: true,
            dataType: "json",
            success: function(data){
                callback(data);
            }
        });
    };

})()