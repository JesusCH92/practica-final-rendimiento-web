var EditImageModule = (function(){
    console.log('Edit images and send in ELK');
    var $addTagBtn = $(".add-tag-btn");
    var $inputTag = "#input-tag-";
    var $addTagContainer = "#tags-container-";
    var $tagContainer = $(".tags-container");


    var cleanTagInput = function($idTag){
        var $tagInput = $($inputTag + $idTag);
        $tagInput.val("");
    }

    var paintTag = function($idTagBtn){
        var $tagInput = $($inputTag + $idTagBtn).val();
        var $tagContainer = $($addTagContainer + $idTagBtn);

        var $tagAdded = '<span class="badge badge-light"></span>';
        $tagContainer.append($tagAdded);

        $tagContainer.children().last().text($tagInput);
        $tagContainer.children().last().append('<div class="delete-tag"></div>')
        console.log($tagInput);
    }


    var initEvents = function(){

        $addTagBtn.click(function(){
            var $idTagBtn = $(this).attr('id');
            var $idTag = $idTagBtn.replace("add-tag-btn-", "");

            if ($($inputTag + $idTag).val() === "") {
                return;
            }
            paintTag($idTag);

            cleanTagInput($idTag);
        });

        $tagContainer.click(function(){
            if ( !$(event.target).hasClass('delete-tag')) {
                return;
            }
            console.log('quieres eliminar este tag?');
            var $tagToDelete = $(event.target).parent();
            var $textTagToDelete = $.trim($tagToDelete.text());
            console.log($textTagToDelete);
            $tagToDelete.remove();
        });

    }

    initEvents();
})()