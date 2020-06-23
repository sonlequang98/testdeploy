var nextPageURLCat = '';
var listPostsCat = '';
$(document).ready(function() {
    $('#more-posts').hide();
    let categoryId = $('#categoryId').val();
    getPosts(`/api/posts/` + categoryId);
})

$('#btn-more-post').click(function() {
    getPosts(nextPageURLCat);
});

function getPosts(url) {
    let posts = [];
    let res = $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json'
    });
    res.done(function(data) {
        nextPageURLCat = data.next_page_url;
        if (nextPageURLCat) {
            $('#more-posts').show();
        } else {
            $('#more-posts').hide();
        }
        posts = data.data;
        for (let i = 0; i < posts.length; i++) {
            listPostsCat += `<div class="post-item">
            <a href="http://usersite.local/posts/` + posts[i].id + `"><img class="image-post" src="http://adminsite.local/` + posts[i].image.upload_path + `" alt=""></a>
                    <p>` + posts[i].title + `</p>
                    <a class="link-icon" href="http://usersite.local/posts/` + posts[i].id + `"><img class="icon-detail" src="/image/right-arrow.png" width="20px" class="icon-post-detail" alt=""></a>
                    </div>`
        }
        $('#block-post-items').html('' + listPostsCat);
    });
}