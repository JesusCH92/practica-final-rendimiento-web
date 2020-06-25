var EditImageModule = (function(){
    console.log('Edit images and send in ELK');
    var $addTagBtn = $(".add-tag-btn");
    var $inputTag = "#input-tag-";
    var $addTagContainer = "div.tags-container[name-photo='";
    var $tagContainer = $(".tags-container");


    var cleanTagInput = function($idTag){
        var $tagInput = $($inputTag + $idTag);
        $tagInput.val("");
    }

    var paintTag = function({tagText, uuidImage}){
        var $tagInput = tagText;
        var $tagContainer = $($addTagContainer + uuidImage +"']");

        var $tagAdded = '<span class="badge badge-light"></span>';
        $tagContainer.append($tagAdded);

        $tagContainer.children().last().text($tagInput);
        $tagContainer.children().last().append('<div class="delete-tag"></div>')
    }

    var addTagToImage = function({tag, imageName, callback=console.log}){
        $.ajax({
            type: 'POST',
            url: '/create-tag',
            async: true,
            data: {tag, imageName},
            success: function(data){
                callback({tagText: data.tag_create, uuidImage: data.image_name});
            }
        });
    }

    var deleteTagToImage = function({tag, imageName, callback=console.log}){
        $.ajax({
            type: 'DELETE',
            url: '/delete-tag',
            async: true,
            data: {tag, imageName},
            success: function(data){
                callback(data);
            }
        });
    }

    var initEvents = function(){

        $addTagBtn.click(function(){
            var $idTagBtn = $(this).attr('id');
            var $idTag = $idTagBtn.replace("add-tag-btn-", "");

            var $tagInput = $($inputTag + $idTag);
            var $tagInputText = $($inputTag + $idTag).val();

            if ($tagInputText === "") {
                return;
            }
            var $imageName = $tagInput.parent().attr('name-photo');
            addTagToImage({tag: $tagInputText, imageName: $imageName, callback: paintTag});

            cleanTagInput($idTag);
        });

        $tagContainer.click(function(){
            if ( !$(event.target).hasClass('delete-tag')) {
                return;
            }
            console.log('quieres eliminar este tag?');
            var $tagToDelete = $(event.target).parent();
            var $textTagToDelete = $.trim($tagToDelete.text());

            var $imageName = $tagToDelete.parent().attr('name-photo');

            deleteTagToImage({tag: $textTagToDelete, imageName: $imageName});
            $tagToDelete.remove();
        });

    }

    initEvents();
})()