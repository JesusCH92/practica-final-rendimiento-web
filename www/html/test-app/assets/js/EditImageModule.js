var EditImageModule = (function(){
    console.log('Edit images and send in ELK');
    var $addTagBtn = $(".add-tag-btn");
    var $inputTag = "#input-tag-";
    var $addTagContainer = "div.tags-container[name-photo='";
    var $tagContainer = $(".tags-container");
    var $addDescriptionBtn = $(".add-description-btn");
    var $inputDescription = "#input-description-";
    var $addDescriptionContainer = "div.description-container[name-photo='";


    var cleanElement = function({element}) {
        element.val("");
    }

    var paintTag = function({tagText, uuidImage}) {
        var $tagInput = tagText;
        var $tagContainer = $($addTagContainer + uuidImage +"']");

        var $tagAdded = '<span class="badge badge-light"></span>';
        $tagContainer.append($tagAdded);

        $tagContainer.children().last().text($tagInput);
        $tagContainer.children().last().append('<div class="delete-tag"></div>')
    }

    // var paintdeleteElement = function({element}){
    //     element.remove();
    // }

    var paintDescription = function({descriptionText, uuidImage}){
        var $descriptionContainer = $($addDescriptionContainer + uuidImage + "']");
        $descriptionContainer.children().text(descriptionText);
    }

    var addTagToImage = function({tag, imageName, callback=console.log}) {
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

    var deleteTagToImage = function({tag, imageName, callback=console.log}) {
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

    var addDescriptionToImage = function ({description, imageName, callback = console.log}) {
        $.ajax({
            type: 'POST',
            url: '/add-description',
            async: true,
            data: {description, imageName},
            success: function(data){
                callback({descriptionText:data.description, uuidImage: data.image_name});
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

            cleanElement({element: $($inputTag + $idTag)});
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

        $addDescriptionBtn.click(function (){
            var $idDescriptionBtn = $(this).attr('id');
            var $idDescription = $idDescriptionBtn.replace("add-description-btn-", "");

            var $descriptionInput = $($inputDescription + $idDescription);
            var $descriptionInputText = $($inputDescription + $idDescription).val();

            if ($descriptionInputText === "") {
                return;
            }
            var $imageName = $descriptionInput.parent().attr('name-photo');
            console.log($descriptionInputText);
            console.log($imageName);
            addDescriptionToImage({description: $descriptionInputText, imageName: $imageName, callback:paintDescription});

            cleanElement({element: $($inputDescription + $idDescription)});
        });

    }

    initEvents();
})()