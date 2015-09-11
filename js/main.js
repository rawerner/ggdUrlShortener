$(document).ready(function(){

    $( "#submit" ).on('submit', handler)(function( event ) {
        debugger;

    });

    var client = new ZeroClipboard( document.getElementById("click-to-copy"), {
        moviePath: "../../ZeroClipboard.swf",
        debug: false
    } );

    client.on( "load", function(client) {
        // alert( "movie is loaded" );
        $('#flash-loaded').fadeIn();
        client.on( "copyUrl", function(client, args) {
            // `this` is the element that was clicked
            client.setText( "Set text copied." );
            $('#click-to-copy-text').fadeIn();
        } );
    } );

});


