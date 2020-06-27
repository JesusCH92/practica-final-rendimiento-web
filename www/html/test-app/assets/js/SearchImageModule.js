var SearchImageModule = (function(){
    var $imageSearcherBtn = $("#searcher-image-elk-btn");
    var $imageSearchInput = $("#search-image-elk-input");
    var $imageContainer = $(".documents-elk-container");

    var imageSearcher = function ({imageSearch, callback = console.log}) {
        $.ajax({
            type: 'POST',
            url: '/image-searcher-elk',
            async: true,
            data: {imageSearch},
            success: function(data){
                callback({imageCollection: data.image_collection});
            }
        });
    }

    var paintResultImage = function({imageCollection}){
        var $numberCards = imageCollection.length;
        for(var i=0;i<$numberCards;i++){
            var $tags = paintTags(imageCollection[i]["tags"]);
            var $imageCard = 
            `
            <div class="col-xl-4 col-md-6 col-10 mx-auto my-3">
                <div class="card">
                    <img class="card-img-top" src="/assets/files/${imageCollection[i]["image_rename"]}.${imageCollection[i]["image_extension"]}" alt="Card image cap">
                    <div class="card-body">
                        <div class="tags-container-elk">
                            ${$tags}
                        </div>
                        <div class="description-container-elk">
                            <textarea class="form-control" rows="2" readonly>${imageCollection[i]["description"]}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            `;
            $imageContainer.append($imageCard);
        }
        if ($numberCards === 0) {
            $imageContainer.text('Not found result');
        }
    }

    var paintTags = function($tags){
        var $tagNumber = $tags.length;
        var $tagsBadge = "";
        for (var i=0; i<$tagNumber; i++){
            $tagsBadge += `
                <span class="badge badge-light">
                    ${$tags[i]} 
                </span>
            `;
        }
        return $tagsBadge;
    }


    var initEvents = function(){
        $imageSearcherBtn.click(function(){
            if ($imageSearchInput.val() === "") {
                return;
            }
            $imageContainer.empty();
            
            imageSearcher({imageSearch : $imageSearchInput.val(), callback: paintResultImage});
            $imageSearchInput.val("");
        })
    }

    initEvents();

})()