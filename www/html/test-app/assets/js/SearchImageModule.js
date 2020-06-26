var SearchImageModule = (function(){
    console.log('Edit images and send in ELK !!!');
    var $imageSearcherBtn = $("#searcher-image-elk-btn");
    var $imageSearchInput = $("#search-image-elk-input");

    var imageSearcher = function ({imageSearch, callback = console.log}) {
        $.ajax({
            type: 'POST',
            url: '/image-searcher-elk',
            async: true,
            data: {imageSearch},
            success: function(data){
                callback(data);
            }
        });
    }


    var initEvents = function(){
        $imageSearcherBtn.click(function(){
            console.log("don't push me!!!!!");
            console.log($imageSearchInput.val());
            var $fomatprova = `
            {
                "query": {
                    "multi_match": {
                        "query": "88aa02d9",
                        "type": "bool_prefix",
                        "fields": [
                            "image_rename",
                            "image_rename._2gram",
                            "image_rename._3gram"
                        ]
                    }
                }
            }`;
            console.log($fomatprova);
            imageSearcher({imageSearch : $fomatprova});
        })
    }

    initEvents();

})()