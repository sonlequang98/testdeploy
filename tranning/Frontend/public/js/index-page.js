var nextPageURL = '';
var listPosts = '';
$(document).ready(function() {
    getPosts('/api/posts');
    $('#btn-more-post').click(function() {
        getPosts(nextPageURL);
    })


});


function getPosts(url) {
    let posts = [];
    let res = $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json'
    });
    res.done(function(data) {
        nextPageURL = data.next_page_url;
        if (!data.next_page_url) {
            $('#btn-more-post').css('display', 'none');
        }
        posts = data.data;
        for (let i = 0; i < posts.length; i++) {
            listPosts += `<div class="post-item">
            <a href="http://usersite.local/posts/` + posts[i].id + `"><img class="image-post" src="http://adminsite.local/` + posts[i].image.upload_path + `" alt=""></a>
                    <p>` + posts[i].title + `</p>
                    <a class="link-icon" href="http://usersite.local/posts/` + posts[i].id + `"><img class="icon-detail" src="/image/right-arrow.png" width="20px" class="icon-post-detail" alt=""></a>
                    </div>`
        }
        $('#block-post-items').html('' + listPosts);
    })
}