$(document).ready(function() {
    let postId = $('#post-id').val();
    let res = $.ajax({
        url: 'http://usersite.local/api/posts/' + postId + '/show',
        type: 'GET',
        dataType: 'json'
    });
    res.done(function(data) {
        $('#page-content').html(data.content.split('<p><br></p>').join(""));
    })
})