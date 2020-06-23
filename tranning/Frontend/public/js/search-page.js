var nextPageSearchPost = '';
var resultSearch = '';
var pagination = true;

$(document).ready(function() {
    var searchQuery = $('#searchQuery').val();
    $('#more-posts').hide();
    $('#input-search').val(searchQuery);
    getResultSearchPost(`/api/posts/search/?searchQuery=` + searchQuery);

})

$('#btn-more-post').click(function() {
    getResultSearchPost(nextPageSearchPost);
});

function getResultSearchPost(url) {

    let response = [];
    let res = $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json'
    });

    res.done(function(data) {
        response = data.data;
        nextPageSearchPost = data.next_page_url;
        if (nextPageSearchPost) {
            $('#more-posts').show();
        } else {
            $('#more-posts').hide();
        }
        if (response.length == 0) {
            resultSearch += `<div>
                <h3>0 Article</h3>
                <br>
                <span>No Results</span>
            </div>`
        } else {
            if (pagination) {
                resultSearch += `
                <div>
                    <h3>Result:` + data.total + `Article</h3>
                </div>
            `
                pagination = false;
            }
            for (let i = 0; i < response.length; i++) {
                resultSearch += `<div class="post-item">
                <a href="http://usersite.local/posts/` + response[i].id + `"><img class="image-post" src="http://adminsite.local/` + response[i].image.upload_path + `" alt=""></a>
                        <p>` + response[i].title + `</p>
                        <a class="link-icon" href="http://usersite.local/posts/` + response[i].id + `"><img class="icon-detail" src="/image/right-arrow.png" width="20px" class="icon-post-detail" alt=""></a>
                        </div>`
            }
        }
        $('#list-result-search').html('' + resultSearch);
    });
}