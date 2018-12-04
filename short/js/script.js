$(document).ready(function() {
    
    // ZERO FUCKING CLIPBOARD
    var client = new ZeroClipboard($('#btn-shorturl'), {
        moviePath: 'js/ZeroClipboard.swf'
    });

    $('#shorturl').select();
    
    client.on( "load", function(client) {
        //alert( "movie is loaded" );

        client.on( "complete", function(client, args) {
            // `this` is the element that was clicked
            //this.style.display = "none";
            //clientText.setText( 'lalala' );
    //        alert("Copied text to clipboard: " + args.text );
    //        ga('send', 'event', 'social', 'copied url');
        });
    });
    
    // shorten
    $('#shorten').on('click', function() {
        $.ajax({
            method:"POST",
            url:"//djnd.si/yomamasofat/",
            data:{
                fatmama:$('#shorturl').val()
            },
            success:function(resp){
                $('#shorturl').val(resp);
                $('#btn-shorturl').toggleClass('hidden');
                $('#shorten').toggleClass('hidden');
            }
        });
    });
    
    //social
    $('.fb').on('click', function() {
        var url = 'https://www.facebook.com/dialog/feed?app_id=301375193309601&redirect_uri=' + encodeURIComponent(document.location.href) + '&link=' + encodeURIComponent($('#shorturl').val()) + '&ref=responsive';
        window.open(url, '_blank');
        //ga('send', 'event', 'social', 'facebook');
        return false;
    });
    $('.tw').on('click', function() {
        var url = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent($('#shorturl').val());
        window.open(url, '_blank');
        //ga('send', 'event', 'social', 'twitter');
        return false;
    });
    $('.gp').on('click', function() {
        var url = 'https://plus.google.com/share?url=' + encodeURIComponent($('#shorturl').val());
        window.open(url, '_blank');
        //ga('send', 'event', 'social', 'gplus');
        return false;
    });
    $('.email').on('click', function() {
        var url = 'mailto:?subject=Shortnan url&body=' + encodeURIComponent($('#shorturl').val());
        window.open(url, '_blank');
        //ga('send', 'event', 'social', 'email');
        return false;
    });
    
});
