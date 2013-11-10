$(document).ready(function() {

    // Tab behaviour.
    $('#myTab a:first').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    })

    $('#myTab a:last').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    })

    $(".heart").one("click", function() {
        var artistId = $('#pop_liked').data('artist-id');
        // Ping the server to register the like.
        $.post(artistId + '/like');
        // Show the popup.
        $('#pop_liked').popover('show');
        $('.heart').css( 'opacity', '0.6' );
        // Allow the popup to be closed.
        $(this).one("click",function() {
           $('#pop_liked').popover('hide');
           $('.heart').css( 'opacity', '1' );
        });
    });

    //Toggle short bio in Artists Listing
    $(".artist_listing").on("mouseenter", function() {
        $(':nth-child(2)', $(this)).toggle("show");
    });

});
