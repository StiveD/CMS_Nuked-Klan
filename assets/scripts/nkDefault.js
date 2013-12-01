$(document).ready(function(){
    // -> all Initialize function
    initDivSystem();
    // -> close popup private message
    $('#nkNewPrivateMsgClose').click(function(){
        document.cookie = "popup=false";
        $(this).parent().slideUp(200);
    });
    // -> Redirect link
    $('a').click(function() {
        var hrefLink = $(this).attr('href');

        if (hrefLink == 'index.php?file=User') {
            var dataHref = 'index.php?file=User&nuked_nude=index&op=index';
            $(this).attr('data-title', 'Déconnexion').attr('href', dataHref);
            userModalFull(this);
            $(this).attr('href', hrefLink);
            return false;
        }
    });
});

// -> Fuction jquery
    // -> Initialize nkDialog
    function initDivSystem(){
        $('<div id="nkDialog"></div>').prependTo('body');
    }
    // -> user modal fullscreen
    function userModalFull(getUrl) {
        // -> add style for user
        $("head").append( $(document.createElement("link")).attr({rel:"stylesheet", type:"text/css", href:"modules/User/User.css"}));
        // -> ajax
        $.ajax({
            type: 'GET',
            url: getUrl,
            // -> success
            success:function(data) {
                $('#nkDialog').html(data);
                $('#nkDialog').css({
                    display: 'none',
                    padding: '0',
                    position: 'fixed',
                    left: 0,
                    right: 0,
                    zIndex: 600000,
                })
                .fadeIn(500);
                $('body').css({overflow: 'hidden'});
                modsUsers();
                tooltips();
            }, // -> end success
            // -> error
            error:function(){
                $('#nkDialog').html("ERROR !");
            }// -> error
        }); // -> end ajax

        closeModalFull();
    }
    // -> user modal close
    function closeModalFull() {
        $(document).keyup(function(e) {
            if (e.keyCode == 27) {
                $('#nkDialog').fadeOut(1000);
                $('body').css({overflow: 'auto'});
            }
        });
        $('#jqueryClose').click(function() {
            $('#nkDialog').fadeOut(1000);
            $('body').css({overflow: 'auto'});
            return false;
        });
    }
    function modsUsers() {
        closeModalFull();
        // -> Variables
        animateHoverListFriends = false;
        // -> tabs
        $('#tab-container').easytabs({
            animationSpeed: 300,
            collapsible: false,
            tabActiveClass: "clicked"
        });
        $('.jqueryLinksSwtich').each(function() {
            $(this).click(function(event) {
                event.preventDefault();
                // -> Variables
                var value  = $(this).attr('href').replace('#','');
                var getUrl = 'index.php?file=User&nuked_nude=index&op=' + value;
                var title  = $(this).data('title');
                var icon   = $(this).data('icon');
                $('#jquerySections').fadeOut(450, function() {
                    // -> ajax
                    $.ajax({
                        type: 'GET',
                        url: getUrl,
                        // -> success
                        success:function(data) {
                            $('#jquerySections').empty().append(data).fadeIn(450);
                            modsUsers();
                            tooltips();
                        } // -> end success
                    }); // -> end ajax
                });
                $('#jqueryTitle').empty().append(title);
                $('#jqueryIcon').removeAttr('class').attr('class', icon);
            });
        });
    }
