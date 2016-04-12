$("#topicContent").find( "img" ).each(function(){
    var t = $(this);
    var src = t.attr('src');
    t.attr('class','img-fluid');
});

function getFeedCate(slug){
    $.get( "/getFeed/", { slug: slug } )
        .done(function( data ) {
            $('#homeFeed').html(data);
        });
}

function ipLogger()
{
    $.getJSON('http://ipinfo.io', function(data){
        return data;
    })
}

$('a.card-link').click(function(e)
{
    // Cancel the default action
    e.preventDefault();
});