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
    // Special stuff to do when this link is clicked...

    // Cancel the default action
    e.preventDefault();
});