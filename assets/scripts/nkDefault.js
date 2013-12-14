$(document).ready(function(){
    // -> all Initialize function
    initDivSystem();
    tooltips();
    datepicker();
    $(".lightbox").fancybox({ 'padding': 2 });
    // -> close popup private message
    $('#nkNewPrivateMsgClose').click(function(){
        document.cookie = "popup=false";
        $(this).parent().slideUp(200);
    });
    // -> Redirect link
    $('a').click(function() {
        var hrefLink = $(this).attr('href');
        if (hrefLink == 'index.php?file=User&nuked_nude=index&op=logout') {
            var dataHref = 'index.php?file=User&nuked_nude=index&op=formLogout';
            $(this).attr('data-title', 'Déconnexion').attr('href', dataHref);
            userModal(this);
            $(this).attr('href', hrefLink);
            return false;
        }
        if (hrefLink == 'index.php?file=User&nuked_nude=index&op=login') {
            var dataHref = 'index.php?file=User&nuked_nude=index&op=formLogin';
            $(this).attr('data-title', 'Connexion').attr('href', dataHref);
            userModal(this);
            $(this).attr('href', hrefLink);
            return false;
        }
        if (hrefLink == 'index.php?file=User&op=reg_screen') {
            var dataHref = 'index.php?file=User&nuked_nude=index&op=formRegister';
            $(this).attr('data-title', 'Inscription').attr('href', dataHref);
            userModal(this);
            $(this).attr('href', hrefLink);
            return false;
        }
        if (hrefLink == 'index.php?file=User&op=oubli_pass') {
            var dataHref = 'index.php?file=User&nuked_nude=index&op=formLostPassword';
            $(this).attr('data-title', 'Mot de passe Perdu').attr('href', dataHref);
            userModal(this);
            $(this).attr('href', hrefLink);
            return false;
        }

        if (hrefLink == 'index.php?file=User') {
            var dataHref = 'index.php?file=User&nuked_nude=index&op=index';
            $(this).attr('data-title', 'Déconnexion').attr('href', dataHref);
            userModalFull(this);
            $(this).attr('href', hrefLink);
            return false;
        }
    });
    $('#formUsers').submit(function() {
        return false;
    });
});

// -> Fuction jquery
    // -> Initialize nkDialog
    function initDivSystem(){
        $('<div id="nkDialog"></div>').prependTo('body');
    }
    // -> user modal
    function userModal(dataThis) {
        var getUrl = $(dataThis).attr('href');
        if (getUrl == undefined) {
            getUrl = $(dataThis).data('href');
        }
        var titleDialog  = 'Formulaire';
        if ($(dataThis).data('title') != undefined) {
            titleDialog = $(dataThis).data('title');
        }
        // -> Empty div nkDialog
        $('.ui-dialog').empty().removeAttr('style');
        // -> ajax
        $.ajax({
            type: 'GET',
            url: getUrl,
            // -> success
            success:function(data) {
                $('#nkDialog').html(data);
                // -> effect background
                $('body').addClass('body');
                $('html').addClass('html');
                // -> dialog ui
                $('#nkDialog').dialog({
                    title: titleDialog,
                    modal: true,
                    draggable: false,
                    resizable: false,
                    width: 475,
                    maxWidth: 600,
                    minHeight: 0,
                    hide: 'slideUp',
                    show: 'slideDown',
                    closeOnEscape: true,
                    maxHeight: $(window).innerHeight() - 50,
                }); // -> end dialog ui
                form($('#nkDialog > form'));
                lostPassword();
            }, // -> end success
            // -> error
            error:function(){
                $('#nkDialog').html("ERROR !");
            } // -> end error
        }); // -> end ajax
        closeModal();
    }
    // -> user modal fullscreen
    function userModalFull(getUrl) {
        // -> Empty div nkDialog
        $('.ui-dialog').empty().removeAttr('style');
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
    function closeModal() {
        $('.ui-widget-overlay, ui-front').click(function() {
            $(this).fadeOut(1000);
            $('#nkDialog').fadeOut(1000);
            alert('test');
        });
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
    // -> initialize mods users
    function modsUsers() {
        // -> close modal user
        closeModalFull();
        // -> initialize popup edit
        editUsers();
        // -> initialize change page
        switchPage();
        // -> initialize easyTabs
        easyTabs();
        // -> initialize action double click for edit
        dblclickEdit();
    }
    function switchPage() {
        $('#modsUser').on('click', '.jqueryLinksSwtich', function(event) {
            event.preventDefault();
            // -> variables
            var value  = $(this).attr('href').replace('#','');
            var getUrl = 'index.php?file=User&nuked_nude=index&op=' + value;
            var title  = $(this).data('title');
            var icon   = $(this).data('icon');
            $('#jquerySections').fadeOut(450, function() {
                // -> ajax
                $.ajax({
                    type: 'GET',
                    url: getUrl,
                    beforeSend:function() {
                    },
                    // -> success
                    success:function(data) {
                        $('#jquerySections').empty().append(data).fadeIn(450);
                    }, // -> end success
                    complete:function() {
                        tooltips();
                        easyTabs();
                    }
                }); // -> end ajax
            });
            $('#jqueryTitle').empty().append(title);
            $('#jqueryIcon').removeAttr('class').attr('class', icon);
        });
    }
    function easyTabs() {
        $('#tab-container').easytabs({
            animationSpeed: 300,
            collapsible: false,
            tabActiveClass: "clicked"
        });
    }
    function editUsers() {
        // -> variables
        var testIdDataInput = $("#testIdDataInput");
        // -> insert div#jqueryDataInput
        if (testIdDataInput.length) {
            testIdDataInput.empty();
        } else {
            $('<div id="jqueryDataInput"></div>').prependTo('section#modsUser');
        }
    }
    function dblclickEdit() {
        // -> double click
        $('#modsUser form .contentInfos > div, .jqueryEdit').dblclick(function() {
            // -> variables
            var getUrlSave = 'index.php?file=User&nuked_nude=index&op=saveJquery';
            var value  = $(this).data('value');
            var title  = $(this).data('title');
            var name   = $(this).data('name');
            // -> ajax
            $.ajax({
                type: 'POST',
                data: {name:name},
                url: getUrlSave,
                // -> success
                success:function(data) {
                    // -> insert data
                    $('#jqueryDataInput').html(data);
                    // -> dialog ui
                    $('#jqueryDataInput').dialog({
                        title: title,
                        modal: true,
                        draggable: true,
                        resizable: true,
                        closeOnEscape: false,
                        minHeight: 165,
                    }); // -> end dialog ui
                    $(".jQueryChosen").chosen();
                    datepicker();
                }, // -> end success
                complete:function() {
                    form($('#formUsers'));
                }
            }); // -> end ajax
        }); // -> end dblclick
    }
    function form(form) {
        var formLogin = $('#' + $(form).attr('id'));
        var formId    = '#' + $(form).attr('id');
        formLogin.submit(function() {
            $.ajax({
                type: 'POST',
                url: formLogin.attr('action'),
                dataType: "json",
                data: formLogin.serialize(),
                success: function(dataLogin) {
                    var errorMsg  = dataLogin.errorMsg;
                    var bgClass   = dataLogin.bgClass;
                    var uiButton  = $('.ui-button-blue');
                    var wUiButton = uiButton.width();
                    uiButton.animate(
                        {
                        width: "100%",
                        margin: 0,
                        },
                        1000 )
                    .attr('disabled', true)
                    .val(errorMsg);

                    if (dataLogin.redirectLink == '') {
                        $(formId + ' input').attr('disabled', true);
                        setTimeout(function() {
                            uiButton
                                .addClass('disabledError');
                        }, 500);
                        setTimeout(function() {
                            uiButton.removeAttr('style')
                            .attr('disabled', false)
                            .val('Send');
                        }, 3500);
                        setTimeout(function() {
                            $(formId + ' input').attr('disabled', false);
                        }, 3550);
                    }
                    else if (dataLogin.redirectLink == '#') {
                        setTimeout(function() {
                            uiButton
                                .addClass('disabledSuccess')
                                .val(dataLogin.redirectedName);
                        }, 1000);
                        setTimeout(function() {
                            $(".ui-dialog-content").dialog("close");
                        }, 3000);
                        setTimeout(function() {
                            var getUrl = 'index.php?file=User&nuked_nude=index&op=home';
                            var title  = 'Accueil';
                            var icon   = 'icon-home';
                            $('#jquerySections').fadeOut(450, function() {
                                // -> ajax
                                $.ajax({
                                    type: 'GET',
                                    url: getUrl,
                                    // -> success
                                    success:function(data) {
                                        $('#jquerySections').empty().append(data).fadeIn(450);
                                        tooltips();
                                        easyTabs();
                                        dblclickEdit();
                                    }, // -> end success
                                }); // -> end ajax
                            });
                            $('#jqueryTitle').empty().append(title);
                            $('#jqueryIcon').removeAttr('class').attr('class', icon);
                        }, 3500);
                    }
                    else {
                        $(formId + ' input').attr('disabled', true);
                        setTimeout(function() {
                            uiButton
                                .addClass('disabledSuccess')
                                .val(dataLogin.redirectedName);
                        }, 1700);
                        setTimeout(function() {
                            location.href = dataLogin.redirectLink;
                        }, 3500);
                    }
                }
            });
            return false;
        });
    }
    // -> form lost password
    function lostPassword() {
        $('#passLost').click(function() {
            var nkDialog    = $('#nkDialog');
            var heightModal = nkDialog.height();
            var getUrl      = 'index.php?file=User&nuked_nude=index&op=formLostPassword';
            nkDialog.css({minHeight: heightModal+'px'});
            $('#formUsers').slideUp(500, function(){
                $(this).delay(500).remove();
            });
            $.ajax({
                type: 'GET',
                url: getUrl,
                success: function(data){
                   nkDialog.animate({minHeight: '0'}, 1000).html(data);
                   form($('#nkDialog > form'));
                }
            });
        });
    }

    // -> Initialize DatePicker
    function datepicker() {
        $( ".datepicker" ).datepicker({
            defaultDate: +7,
            showOtherMonths:true,
            autoSize: true,
            appendText: 'jours - mois - années',
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            showAnim: 'slide',
            maxDate: "-12Y",
            minDate: "-60Y"
        });

        $.datepicker.regional['fr'] = {
            closeText: 'Fermer',
            prevText: '<Préc',
            nextText: 'Suiv>',
            currentText: 'Courant',
            monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
            'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
            monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun',
            'Jul','Aoû','Sep','Oct','Nov','Déc'],
            dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
            dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
            dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
            weekHeader: 'Sm',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: true,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['fr']);
    }

    // -> Initialize tooltips
    function tooltips() {
        $('.tipN').tipsy({gravity: 'n',fade: true, html:true});
        $('.tipS').tipsy({gravity: 's',fade: true, html:true});
        $('.tipW').tipsy({gravity: 'w',fade: true, html:true});
        $('.tipE').tipsy({gravity: 'e',fade: true, html:true});
    }
    // -> Initialize EasyTabs
    (function(a){a.easytabs=function(j,e){var f=this,q=a(j),i={animate:true,panelActiveClass:"active",tabActiveClass:"active",defaultTab:"li:first-child",animationSpeed:"normal",tabs:"> ul > li",updateHash:true,cycle:false,collapsible:false,collapsedClass:"collapsed",collapsedByDefault:true,uiTabs:false,transitionIn:"fadeIn",transitionOut:"fadeOut",transitionInEasing:"swing",transitionOutEasing:"swing",transitionCollapse:"slideUp",transitionUncollapse:"slideDown",transitionCollapseEasing:"swing",transitionUncollapseEasing:"swing",containerClass:"",tabsClass:"",tabClass:"",panelClass:"",cache:true,panelContext:q},h,l,v,m,d,t={fast:200,normal:400,slow:600},r;f.init=function(){f.settings=r=a.extend({},i,e);if(r.uiTabs){r.tabActiveClass="ui-tabs-selected";r.containerClass="ui-tabs ui-widget ui-widget-content ui-corner-all";r.tabsClass="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all";r.tabClass="ui-state-default ui-corner-top";r.panelClass="ui-tabs-panel ui-widget-content ui-corner-bottom"}if(r.collapsible&&e.defaultTab!==undefined&&e.collpasedByDefault===undefined){r.collapsedByDefault=false}if(typeof(r.animationSpeed)==="string"){r.animationSpeed=t[r.animationSpeed]}a("a.anchor").remove().prependTo("body");q.data("easytabs",{});f.setTransitions();f.getTabs();b();g();w();n();c();q.attr("data-easytabs",true)};f.setTransitions=function(){v=(r.animate)?{show:r.transitionIn,hide:r.transitionOut,speed:r.animationSpeed,collapse:r.transitionCollapse,uncollapse:r.transitionUncollapse,halfSpeed:r.animationSpeed/2}:{show:"show",hide:"hide",speed:0,collapse:"hide",uncollapse:"show",halfSpeed:0}};f.getTabs=function(){var x;f.tabs=q.find(r.tabs),f.panels=a(),f.tabs.each(function(){var A=a(this),z=A.children("a"),y=A.children("a").data("target");A.data("easytabs",{});if(y!==undefined&&y!==null){A.data("easytabs").ajax=z.attr("href")}else{y=z.attr("href")}y=y.match(/#([^\?]+)/)[0].substr(1);x=r.panelContext.find("#"+y);if(x.length){x.data("easytabs",{position:x.css("position"),visibility:x.css("visibility")});x.not(r.panelActiveClass).hide();f.panels=f.panels.add(x);A.data("easytabs").panel=x}else{f.tabs=f.tabs.not(A)}})};f.selectTab=function(x,C){var y=window.location,B=y.hash.match(/^[^\?]*/)[0],z=x.parent().data("easytabs").panel,A=x.parent().data("easytabs").ajax;if(r.collapsible&&!d&&(x.hasClass(r.tabActiveClass)||x.hasClass(r.collapsedClass))){f.toggleTabCollapse(x,z,A,C)}else{if(!x.hasClass(r.tabActiveClass)||!z.hasClass(r.panelActiveClass)){o(x,z,A,C)}else{if(!r.cache){o(x,z,A,C)}}}};f.toggleTabCollapse=function(x,y,z,A){f.panels.stop(true,true);if(u(q,"easytabs:before",[x,y,r])){f.tabs.filter("."+r.tabActiveClass).removeClass(r.tabActiveClass).children().removeClass(r.tabActiveClass);if(x.hasClass(r.collapsedClass)){if(z&&(!r.cache||!x.parent().data("easytabs").cached)){q.trigger("easytabs:ajax:beforeSend",[x,y]);y.load(z,function(C,B,D){x.parent().data("easytabs").cached=true;q.trigger("easytabs:ajax:complete",[x,y,C,B,D])})}x.parent().removeClass(r.collapsedClass).addClass(r.tabActiveClass).children().removeClass(r.collapsedClass).addClass(r.tabActiveClass);y.addClass(r.panelActiveClass)[v.uncollapse](v.speed,r.transitionUncollapseEasing,function(){q.trigger("easytabs:midTransition",[x,y,r]);if(typeof A=="function"){A()}})}else{x.addClass(r.collapsedClass).parent().addClass(r.collapsedClass);y.removeClass(r.panelActiveClass)[v.collapse](v.speed,r.transitionCollapseEasing,function(){q.trigger("easytabs:midTransition",[x,y,r]);if(typeof A=="function"){A()}})}}};f.matchTab=function(x){return f.tabs.find("[href='"+x+"'],[data-target='"+x+"']").first()};f.matchInPanel=function(x){return(x?f.panels.filter(":has("+x+")").first():[])};f.selectTabFromHashChange=function(){var y=window.location.hash.match(/^[^\?]*/)[0],x=f.matchTab(y),z;if(r.updateHash){if(x.length){d=true;f.selectTab(x)}else{z=f.matchInPanel(y);if(z.length){y="#"+z.attr("id");x=f.matchTab(y);d=true;f.selectTab(x)}else{if(!h.hasClass(r.tabActiveClass)&&!r.cycle){if(y===""||f.matchTab(m).length||q.closest(y).length){d=true;f.selectTab(l)}}}}}};f.cycleTabs=function(x){if(r.cycle){x=x%f.tabs.length;$tab=a(f.tabs[x]).children("a").first();d=true;f.selectTab($tab,function(){setTimeout(function(){f.cycleTabs(x+1)},r.cycle)})}};f.publicMethods={select:function(x){var y;if((y=f.tabs.filter(x)).length===0){if((y=f.tabs.find("a[href='"+x+"']")).length===0){if((y=f.tabs.find("a"+x)).length===0){if((y=f.tabs.find("[data-target='"+x+"']")).length===0){if((y=f.tabs.find("a[href$='"+x+"']")).length===0){a.error("Tab '"+x+"' does not exist in tab set")}}}}}else{y=y.children("a").first()}f.selectTab(y)}};var u=function(A,x,z){var y=a.Event(x);A.trigger(y,z);return y.result!==false};var b=function(){q.addClass(r.containerClass);f.tabs.parent().addClass(r.tabsClass);f.tabs.addClass(r.tabClass);f.panels.addClass(r.panelClass)};var g=function(){var y=window.location.hash.match(/^[^\?]*/)[0],x=f.matchTab(y).parent(),z;if(x.length===1){h=x;r.cycle=false}else{z=f.matchInPanel(y);if(z.length){y="#"+z.attr("id");h=f.matchTab(y).parent()}else{h=f.tabs.parent().find(r.defaultTab);if(h.length===0){a.error("The specified default tab ('"+r.defaultTab+"') could not be found in the tab set.")}}}l=h.children("a").first();p(x)};var p=function(z){var y,x;if(r.collapsible&&z.length===0&&r.collapsedByDefault){h.addClass(r.collapsedClass).children().addClass(r.collapsedClass)}else{y=a(h.data("easytabs").panel);x=h.data("easytabs").ajax;if(x&&(!r.cache||!h.data("easytabs").cached)){q.trigger("easytabs:ajax:beforeSend",[l,y]);y.load(x,function(B,A,C){h.data("easytabs").cached=true;q.trigger("easytabs:ajax:complete",[l,y,B,A,C])})}h.data("easytabs").panel.show().addClass(r.panelActiveClass);h.addClass(r.tabActiveClass).children().addClass(r.tabActiveClass)}};var w=function(){f.tabs.children("a").bind("click.easytabs",function(x){r.cycle=false;d=false;f.selectTab(a(this));x.preventDefault()})};var o=function(z,D,E,H){f.panels.stop(true,true);if(u(q,"easytabs:before",[z,D,r])){var A=f.panels.filter(":visible"),y=D.parent(),F,x,C,G,B=window.location.hash.match(/^[^\?]*/)[0];if(r.animate){F=s(D);x=A.length?k(A):0;C=F-x}m=B;G=function(){q.trigger("easytabs:midTransition",[z,D,r]);if(r.animate&&r.transitionIn=="fadeIn"){if(C<0){y.animate({height:y.height()+C},v.halfSpeed).css({"min-height":""})}}if(r.updateHash&&!d){window.location.hash="#"+D.attr("id")}else{d=false}D[v.show](v.speed,r.transitionInEasing,function(){y.css({height:"","min-height":""});q.trigger("easytabs:after",[z,D,r]);if(typeof H=="function"){H()}})};if(E&&(!r.cache||!z.parent().data("easytabs").cached)){q.trigger("easytabs:ajax:beforeSend",[z,D]);D.load(E,function(J,I,K){z.parent().data("easytabs").cached=true;q.trigger("easytabs:ajax:complete",[z,D,J,I,K])})}if(r.animate&&r.transitionOut=="fadeOut"){if(C>0){y.animate({height:(y.height()+C)},v.halfSpeed)}else{y.css({"min-height":y.height()})}}f.tabs.filter("."+r.tabActiveClass).removeClass(r.tabActiveClass).children().removeClass(r.tabActiveClass);f.tabs.filter("."+r.collapsedClass).removeClass(r.collapsedClass).children().removeClass(r.collapsedClass);z.parent().addClass(r.tabActiveClass).children().addClass(r.tabActiveClass);f.panels.filter("."+r.panelActiveClass).removeClass(r.panelActiveClass);D.addClass(r.panelActiveClass);if(A.length){A[v.hide](v.speed,r.transitionOutEasing,G)}else{D[v.uncollapse](v.speed,r.transitionUncollapseEasing,G)}}};var s=function(y){if(y.data("easytabs")&&y.data("easytabs").lastHeight){return y.data("easytabs").lastHeight}var z=y.css("display"),x=y.wrap(a("<div>",{position:"absolute",visibility:"hidden",overflow:"hidden"})).css({position:"relative",visibility:"hidden",display:"block"}).outerHeight();y.unwrap();y.css({position:y.data("easytabs").position,visibility:y.data("easytabs").visibility,display:z});y.data("easytabs").lastHeight=x;return x};var k=function(y){var x=y.outerHeight();if(y.data("easytabs")){y.data("easytabs").lastHeight=x}else{y.data("easytabs",{lastHeight:x})}return x};var n=function(){if(typeof a(window).hashchange==="function"){a(window).hashchange(function(){f.selectTabFromHashChange()})}else{if(a.address&&typeof a.address.change==="function"){a.address.change(function(){f.selectTabFromHashChange()})}}};var c=function(){var x;if(r.cycle){x=f.tabs.index(h);setTimeout(function(){f.cycleTabs(x+1)},r.cycle)}};f.init()};a.fn.easytabs=function(c){var b=arguments;return this.each(function(){var e=a(this),d=e.data("easytabs");if(undefined===d){d=new a.easytabs(this,c);e.data("easytabs",d)}if(d.publicMethods[c]){return d.publicMethods[c](Array.prototype.slice.call(b,1))}})}})(jQuery);
    // -> Initialize tooltips tipsy
    (function(e){function t(e){if(e.attr("title")||typeof e.attr("original-title")!="string"){e.attr("original-title",e.attr("title")||"").removeAttr("title")}}function n(n,r){this.$element=e(n);this.options=r;this.enabled=true;t(this.$element)}n.prototype={show:function(){var t=this.getTitle();if(t&&this.enabled){var n=this.tip();n.find(".tipsy-inner")[this.options.html?"html":"text"](t);n[0].className="tipsy";n.remove().css({top:0,left:0,visibility:"hidden",display:"block"}).appendTo(document.body);var r=e.extend({},this.$element.offset(),{width:this.$element[0].offsetWidth,height:this.$element[0].offsetHeight});var i=n[0].offsetWidth,s=n[0].offsetHeight;var o=typeof this.options.gravity=="function"?this.options.gravity.call(this.$element[0]):this.options.gravity;var u;switch(o.charAt(0)){case"n":u={top:r.top+r.height+this.options.offset,left:r.left+r.width/2-i/2};break;case"s":u={top:r.top-s-this.options.offset,left:r.left+r.width/2-i/2};break;case"e":u={top:r.top+r.height/2-s/2,left:r.left-i-this.options.offset};break;case"w":u={top:r.top+r.height/2-s/2,left:r.left+r.width+this.options.offset};break}if(o.length==2){if(o.charAt(1)=="w"){u.left=r.left+r.width/2-15}else{u.left=r.left+r.width/2-i+15}}n.css(u).addClass("tipsy-"+o);if(this.options.fade){n.stop().css({opacity:0,display:"block",visibility:"visible"}).animate({opacity:this.options.opacity})}else{n.css({visibility:"visible",opacity:this.options.opacity})}}},hide:function(){if(this.options.fade){this.tip().stop().fadeOut(function(){e(this).remove()})}else{this.tip().remove()}},getTitle:function(){var e,n=this.$element,r=this.options;t(n);var e,r=this.options;if(typeof r.title=="string"){e=n.attr(r.title=="title"?"original-title":r.title)}else if(typeof r.title=="function"){e=r.title.call(n[0])}e=(""+e).replace(/(^\s*|\s*$)/,"");return e||r.fallback},tip:function(){if(!this.$tip){this.$tip=e('<div class="tipsy"></div>').html('<div class="tipsy-arrow"></div><div class="tipsy-inner"/></div>')}return this.$tip},validate:function(){if(!this.$element[0].parentNode){this.hide();this.$element=null;this.options=null}},enable:function(){this.enabled=true},disable:function(){this.enabled=false},toggleEnabled:function(){this.enabled=!this.enabled}};e.fn.tipsy=function(t){function r(r){var i=e.data(r,"tipsy");if(!i){i=new n(r,e.fn.tipsy.elementOptions(r,t));e.data(r,"tipsy",i)}return i}function i(){var e=r(this);e.hoverState="in";if(t.delayIn==0){e.show()}else{setTimeout(function(){if(e.hoverState=="in")e.show()},t.delayIn)}}function s(){var e=r(this);e.hoverState="out";if(t.delayOut==0){e.hide()}else{setTimeout(function(){if(e.hoverState=="out")e.hide()},t.delayOut)}}if(t===true){return this.data("tipsy")}else if(typeof t=="string"){return this.data("tipsy")[t]()}t=e.extend({},e.fn.tipsy.defaults,t);if(!t.live)this.each(function(){r(this)});if(t.trigger!="manual"){var o=t.live?"live":"bind",u=t.trigger=="hover"?"mouseenter":"focus",a=t.trigger=="hover"?"mouseleave":"blur";this[o](u,i)[o](a,s)}return this};e.fn.tipsy.defaults={delayIn:0,delayOut:0,fade:false,fallback:"",gravity:"n",html:false,live:false,offset:0,opacity:.8,title:"title",trigger:"hover"};e.fn.tipsy.elementOptions=function(t,n){return e.metadata?e.extend({},n,e(t).metadata()):n};e.fn.tipsy.autoNS=function(){return e(this).offset().top>e(document).scrollTop()+e(window).height()/2?"s":"n"};e.fn.tipsy.autoWE=function(){return e(this).offset().left>e(document).scrollLeft()+e(window).width()/2?"e":"w"}})(jQuery);
    // -> Initialize fancybox
    ;(function(b){var m,t,u,f,D,j,E,n,z,A,q=0,e={},o=[],p=0,d={},l=[],G=null,v=new Image,J=/\.(jpg|gif|png|bmp|jpeg)(.*)?$/i,W=/[^\.]\.(swf)\s*$/i,K,L=1,y=0,s="",r,i,h=false,B=b.extend(b("<div/>")[0],{prop:0}),M=b.browser.msie&&b.browser.version<7&&!window.XMLHttpRequest,N=function(){t.hide();v.onerror=v.onload=null;G&&G.abort();m.empty()},O=function(){if(false===e.onError(o,q,e)){t.hide();h=false}else{e.titleShow=false;e.width="auto";e.height="auto";m.html('<p id="fancybox-error">The requested content cannot be loaded.<br />Please try again later.</p>');
    F()}},I=function(){var a=o[q],c,g,k,C,P,w;N();e=b.extend({},b.fn.fancybox.defaults,typeof b(a).data("fancybox")=="undefined"?e:b(a).data("fancybox"));w=e.onStart(o,q,e);if(w===false)h=false;else{if(typeof w=="object")e=b.extend(e,w);k=e.title||(a.nodeName?b(a).attr("title"):a.title)||"";if(a.nodeName&&!e.orig)e.orig=b(a).children("img:first").length?b(a).children("img:first"):b(a);if(k===""&&e.orig&&e.titleFromAlt)k=e.orig.attr("alt");c=e.href||(a.nodeName?b(a).attr("href"):a.href)||null;if(/^(?:javascript)/i.test(c)||
    c=="#")c=null;if(e.type){g=e.type;if(!c)c=e.content}else if(e.content)g="html";else if(c)g=c.match(J)?"image":c.match(W)?"swf":b(a).hasClass("iframe")?"iframe":c.indexOf("#")===0?"inline":"ajax";if(g){if(g=="inline"){a=c.substr(c.indexOf("#"));g=b(a).length>0?"inline":"ajax"}e.type=g;e.href=c;e.title=k;if(e.autoDimensions)if(e.type=="html"||e.type=="inline"||e.type=="ajax"){e.width="auto";e.height="auto"}else e.autoDimensions=false;if(e.modal){e.overlayShow=true;e.hideOnOverlayClick=false;e.hideOnContentClick=
    false;e.enableEscapeButton=false;e.showCloseButton=false}e.padding=parseInt(e.padding,10);e.margin=parseInt(e.margin,10);m.css("padding",e.padding+e.margin);b(".fancybox-inline-tmp").unbind("fancybox-cancel").bind("fancybox-change",function(){b(this).replaceWith(j.children())});switch(g){case "html":m.html(e.content);F();break;case "inline":if(b(a).parent().is("#fancybox-content")===true){h=false;break}b('<div class="fancybox-inline-tmp" />').hide().insertBefore(b(a)).bind("fancybox-cleanup",function(){b(this).replaceWith(j.children())}).bind("fancybox-cancel",
    function(){b(this).replaceWith(m.children())});b(a).appendTo(m);F();break;case "image":h=false;b.fancybox.showActivity();v=new Image;v.onerror=function(){O()};v.onload=function(){h=true;v.onerror=v.onload=null;e.width=v.width;e.height=v.height;b("<img />").attr({id:"fancybox-img",src:v.src,alt:e.title}).appendTo(m);Q()};v.src=c;break;case "swf":e.scrolling="no";C='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'+e.width+'" height="'+e.height+'"><param name="movie" value="'+c+
    '"></param>';P="";b.each(e.swf,function(x,H){C+='<param name="'+x+'" value="'+H+'"></param>';P+=" "+x+'="'+H+'"'});C+='<embed src="'+c+'" type="application/x-shockwave-flash" width="'+e.width+'" height="'+e.height+'"'+P+"></embed></object>";m.html(C);F();break;case "ajax":h=false;b.fancybox.showActivity();e.ajax.win=e.ajax.success;G=b.ajax(b.extend({},e.ajax,{url:c,data:e.ajax.data||{},error:function(x){x.status>0&&O()},success:function(x,H,R){if((typeof R=="object"?R:G).status==200){if(typeof e.ajax.win==
    "function"){w=e.ajax.win(c,x,H,R);if(w===false){t.hide();return}else if(typeof w=="string"||typeof w=="object")x=w}m.html(x);F()}}}));break;case "iframe":Q()}}else O()}},F=function(){var a=e.width,c=e.height;a=a.toString().indexOf("%")>-1?parseInt((b(window).width()-e.margin*2)*parseFloat(a)/100,10)+"px":a=="auto"?"auto":a+"px";c=c.toString().indexOf("%")>-1?parseInt((b(window).height()-e.margin*2)*parseFloat(c)/100,10)+"px":c=="auto"?"auto":c+"px";m.wrapInner('<div style="width:'+a+";height:"+c+
    ";overflow: "+(e.scrolling=="auto"?"auto":e.scrolling=="yes"?"scroll":"hidden")+';position:relative;"></div>');e.width=m.width();e.height=m.height();Q()},Q=function(){var a,c;t.hide();if(f.is(":visible")&&false===d.onCleanup(l,p,d)){b.event.trigger("fancybox-cancel");h=false}else{h=true;b(j.add(u)).unbind();b(window).unbind("resize.fb scroll.fb");b(document).unbind("keydown.fb");f.is(":visible")&&d.titlePosition!=="outside"&&f.css("height",f.height());l=o;p=q;d=e;if(d.overlayShow){u.css({"background-color":d.overlayColor,
    opacity:d.overlayOpacity,cursor:d.hideOnOverlayClick?"pointer":"auto",height:b(document).height()});if(!u.is(":visible")){M&&b("select:not(#fancybox-tmp select)").filter(function(){return this.style.visibility!=="hidden"}).css({visibility:"hidden"}).one("fancybox-cleanup",function(){this.style.visibility="inherit"});u.show()}}else u.hide();i=X();s=d.title||"";y=0;n.empty().removeAttr("style").removeClass();if(d.titleShow!==false){if(b.isFunction(d.titleFormat))a=d.titleFormat(s,l,p,d);else a=s&&s.length?
    d.titlePosition=="float"?'<table id="fancybox-title-float-wrap" cellpadding="0" cellspacing="0"><tr><td id="fancybox-title-float-left"></td><td id="fancybox-title-float-main">'+s+'</td><td id="fancybox-title-float-right"></td></tr></table>':'<div id="fancybox-title-'+d.titlePosition+'">'+s+"</div>":false;s=a;if(!(!s||s==="")){n.addClass("fancybox-title-"+d.titlePosition).html(s).appendTo("body").show();switch(d.titlePosition){case "inside":n.css({width:i.width-d.padding*2,marginLeft:d.padding,marginRight:d.padding});
    y=n.outerHeight(true);n.appendTo(D);i.height+=y;break;case "over":n.css({marginLeft:d.padding,width:i.width-d.padding*2,bottom:d.padding}).appendTo(D);break;case "float":n.css("left",parseInt((n.width()-i.width-40)/2,10)*-1).appendTo(f);break;default:n.css({width:i.width-d.padding*2,paddingLeft:d.padding,paddingRight:d.padding}).appendTo(f)}}}n.hide();if(f.is(":visible")){b(E.add(z).add(A)).hide();a=f.position();r={top:a.top,left:a.left,width:f.width(),height:f.height()};c=r.width==i.width&&r.height==
    i.height;j.fadeTo(d.changeFade,0.3,function(){var g=function(){j.html(m.contents()).fadeTo(d.changeFade,1,S)};b.event.trigger("fancybox-change");j.empty().removeAttr("filter").css({"border-width":d.padding,width:i.width-d.padding*2,height:e.autoDimensions?"auto":i.height-y-d.padding*2});if(c)g();else{B.prop=0;b(B).animate({prop:1},{duration:d.changeSpeed,easing:d.easingChange,step:T,complete:g})}})}else{f.removeAttr("style");j.css("border-width",d.padding);if(d.transitionIn=="elastic"){r=V();j.html(m.contents());
    f.show();if(d.opacity)i.opacity=0;B.prop=0;b(B).animate({prop:1},{duration:d.speedIn,easing:d.easingIn,step:T,complete:S})}else{d.titlePosition=="inside"&&y>0&&n.show();j.css({width:i.width-d.padding*2,height:e.autoDimensions?"auto":i.height-y-d.padding*2}).html(m.contents());f.css(i).fadeIn(d.transitionIn=="none"?0:d.speedIn,S)}}}},Y=function(){if(d.enableEscapeButton||d.enableKeyboardNav)b(document).bind("keydown.fb",function(a){if(a.keyCode==27&&d.enableEscapeButton){a.preventDefault();b.fancybox.close()}else if((a.keyCode==
    37||a.keyCode==39)&&d.enableKeyboardNav&&a.target.tagName!=="INPUT"&&a.target.tagName!=="TEXTAREA"&&a.target.tagName!=="SELECT"){a.preventDefault();b.fancybox[a.keyCode==37?"prev":"next"]()}});if(d.showNavArrows){if(d.cyclic&&l.length>1||p!==0)z.show();if(d.cyclic&&l.length>1||p!=l.length-1)A.show()}else{z.hide();A.hide()}},S=function(){if(!b.support.opacity){j.get(0).style.removeAttribute("filter");f.get(0).style.removeAttribute("filter")}e.autoDimensions&&j.css("height","auto");f.css("height","auto");
    s&&s.length&&n.show();d.showCloseButton&&E.show();Y();d.hideOnContentClick&&j.bind("click",b.fancybox.close);d.hideOnOverlayClick&&u.bind("click",b.fancybox.close);b(window).bind("resize.fb",b.fancybox.resize);d.centerOnScroll&&b(window).bind("scroll.fb",b.fancybox.center);if(d.type=="iframe")b('<iframe id="fancybox-frame" name="fancybox-frame'+(new Date).getTime()+'" frameborder="0" hspace="0" '+(b.browser.msie?'allowtransparency="true""':"")+' scrolling="'+e.scrolling+'" src="'+d.href+'"></iframe>').appendTo(j);
    f.show();h=false;b.fancybox.center();d.onComplete(l,p,d);var a,c;if(l.length-1>p){a=l[p+1].href;if(typeof a!=="undefined"&&a.match(J)){c=new Image;c.src=a}}if(p>0){a=l[p-1].href;if(typeof a!=="undefined"&&a.match(J)){c=new Image;c.src=a}}},T=function(a){var c={width:parseInt(r.width+(i.width-r.width)*a,10),height:parseInt(r.height+(i.height-r.height)*a,10),top:parseInt(r.top+(i.top-r.top)*a,10),left:parseInt(r.left+(i.left-r.left)*a,10)};if(typeof i.opacity!=="undefined")c.opacity=a<0.5?0.5:a;f.css(c);
    j.css({width:c.width-d.padding*2,height:c.height-y*a-d.padding*2})},U=function(){return[b(window).width()-d.margin*2,b(window).height()-d.margin*2,b(document).scrollLeft()+d.margin,b(document).scrollTop()+d.margin]},X=function(){var a=U(),c={},g=d.autoScale,k=d.padding*2;c.width=d.width.toString().indexOf("%")>-1?parseInt(a[0]*parseFloat(d.width)/100,10):d.width+k;c.height=d.height.toString().indexOf("%")>-1?parseInt(a[1]*parseFloat(d.height)/100,10):d.height+k;if(g&&(c.width>a[0]||c.height>a[1]))if(e.type==
    "image"||e.type=="swf"){g=d.width/d.height;if(c.width>a[0]){c.width=a[0];c.height=parseInt((c.width-k)/g+k,10)}if(c.height>a[1]){c.height=a[1];c.width=parseInt((c.height-k)*g+k,10)}}else{c.width=Math.min(c.width,a[0]);c.height=Math.min(c.height,a[1])}c.top=parseInt(Math.max(a[3]-20,a[3]+(a[1]-c.height-40)*0.5),10);c.left=parseInt(Math.max(a[2]-20,a[2]+(a[0]-c.width-40)*0.5),10);return c},V=function(){var a=e.orig?b(e.orig):false,c={};if(a&&a.length){c=a.offset();c.top+=parseInt(a.css("paddingTop"),
    10)||0;c.left+=parseInt(a.css("paddingLeft"),10)||0;c.top+=parseInt(a.css("border-top-width"),10)||0;c.left+=parseInt(a.css("border-left-width"),10)||0;c.width=a.width();c.height=a.height();c={width:c.width+d.padding*2,height:c.height+d.padding*2,top:c.top-d.padding-20,left:c.left-d.padding-20}}else{a=U();c={width:d.padding*2,height:d.padding*2,top:parseInt(a[3]+a[1]*0.5,10),left:parseInt(a[2]+a[0]*0.5,10)}}return c},Z=function(){if(t.is(":visible")){b("div",t).css("top",L*-40+"px");L=(L+1)%12}else clearInterval(K)};
    b.fn.fancybox=function(a){if(!b(this).length)return this;b(this).data("fancybox",b.extend({},a,b.metadata?b(this).metadata():{})).unbind("click.fb").bind("click.fb",function(c){c.preventDefault();if(!h){h=true;b(this).blur();o=[];q=0;c=b(this).attr("rel")||"";if(!c||c==""||c==="nofollow")o.push(this);else{o=b("a[rel="+c+"], area[rel="+c+"]");q=o.index(this)}I()}});return this};b.fancybox=function(a,c){var g;if(!h){h=true;g=typeof c!=="undefined"?c:{};o=[];q=parseInt(g.index,10)||0;if(b.isArray(a)){for(var k=
    0,C=a.length;k<C;k++)if(typeof a[k]=="object")b(a[k]).data("fancybox",b.extend({},g,a[k]));else a[k]=b({}).data("fancybox",b.extend({content:a[k]},g));o=jQuery.merge(o,a)}else{if(typeof a=="object")b(a).data("fancybox",b.extend({},g,a));else a=b({}).data("fancybox",b.extend({content:a},g));o.push(a)}if(q>o.length||q<0)q=0;I()}};b.fancybox.showActivity=function(){clearInterval(K);t.show();K=setInterval(Z,66)};b.fancybox.hideActivity=function(){t.hide()};b.fancybox.next=function(){return b.fancybox.pos(p+
    1)};b.fancybox.prev=function(){return b.fancybox.pos(p-1)};b.fancybox.pos=function(a){if(!h){a=parseInt(a);o=l;if(a>-1&&a<l.length){q=a;I()}else if(d.cyclic&&l.length>1){q=a>=l.length?0:l.length-1;I()}}};b.fancybox.cancel=function(){if(!h){h=true;b.event.trigger("fancybox-cancel");N();e.onCancel(o,q,e);h=false}};b.fancybox.close=function(){function a(){u.fadeOut("fast");n.empty().hide();f.hide();b.event.trigger("fancybox-cleanup");j.empty();d.onClosed(l,p,d);l=e=[];p=q=0;d=e={};h=false}if(!(h||f.is(":hidden"))){h=
    true;if(d&&false===d.onCleanup(l,p,d))h=false;else{N();b(E.add(z).add(A)).hide();b(j.add(u)).unbind();b(window).unbind("resize.fb scroll.fb");b(document).unbind("keydown.fb");j.find("iframe").attr("src",M&&/^https/i.test(window.location.href||"")?"javascript:void(false)":"about:blank");d.titlePosition!=="inside"&&n.empty();f.stop();if(d.transitionOut=="elastic"){r=V();var c=f.position();i={top:c.top,left:c.left,width:f.width(),height:f.height()};if(d.opacity)i.opacity=1;n.empty().hide();B.prop=1;
    b(B).animate({prop:0},{duration:d.speedOut,easing:d.easingOut,step:T,complete:a})}else f.fadeOut(d.transitionOut=="none"?0:d.speedOut,a)}}};b.fancybox.resize=function(){u.is(":visible")&&u.css("height",b(document).height());b.fancybox.center(true)};b.fancybox.center=function(a){var c,g;if(!h){g=a===true?1:0;c=U();!g&&(f.width()>c[0]||f.height()>c[1])||f.stop().animate({top:parseInt(Math.max(c[3]-20,c[3]+(c[1]-j.height()-40)*0.5-d.padding)),left:parseInt(Math.max(c[2]-20,c[2]+(c[0]-j.width()-40)*0.5-
    d.padding))},typeof a=="number"?a:200)}};b.fancybox.init=function(){if(!b("#fancybox-wrap").length){b("body").append(m=b('<div id="fancybox-tmp"></div>'),t=b('<div id="fancybox-loading"><div></div></div>'),u=b('<div id="fancybox-overlay"></div>'),f=b('<div id="fancybox-wrap"></div>'));D=b('<div id="fancybox-outer"></div>').append('<div class="fancybox-bg" id="fancybox-bg-n"></div><div class="fancybox-bg" id="fancybox-bg-ne"></div><div class="fancybox-bg" id="fancybox-bg-e"></div><div class="fancybox-bg" id="fancybox-bg-se"></div><div class="fancybox-bg" id="fancybox-bg-s"></div><div class="fancybox-bg" id="fancybox-bg-sw"></div><div class="fancybox-bg" id="fancybox-bg-w"></div><div class="fancybox-bg" id="fancybox-bg-nw"></div>').appendTo(f);
    D.append(j=b('<div id="fancybox-content"></div>'),E=b('<a id="fancybox-close"></a>'),n=b('<div id="fancybox-title"></div>'),z=b('<a href="javascript:;" id="fancybox-left"><span class="fancy-ico" id="fancybox-left-ico"></span></a>'),A=b('<a href="javascript:;" id="fancybox-right"><span class="fancy-ico" id="fancybox-right-ico"></span></a>'));E.click(b.fancybox.close);t.click(b.fancybox.cancel);z.click(function(a){a.preventDefault();b.fancybox.prev()});A.click(function(a){a.preventDefault();b.fancybox.next()});
    b.fn.mousewheel&&f.bind("mousewheel.fb",function(a,c){if(h)a.preventDefault();else if(b(a.target).get(0).clientHeight==0||b(a.target).get(0).scrollHeight===b(a.target).get(0).clientHeight){a.preventDefault();b.fancybox[c>0?"prev":"next"]()}});b.support.opacity||f.addClass("fancybox-ie");if(M){t.addClass("fancybox-ie6");f.addClass("fancybox-ie6");b('<iframe id="fancybox-hide-sel-frame" src="'+(/^https/i.test(window.location.href||"")?"javascript:void(false)":"about:blank")+'" scrolling="no" border="0" frameborder="0" tabindex="-1"></iframe>').prependTo(D)}}};
    b.fn.fancybox.defaults={padding:10,margin:40,opacity:false,modal:false,cyclic:false,scrolling:"auto",width:560,height:340,autoScale:true,autoDimensions:true,centerOnScroll:false,ajax:{},swf:{wmode:"transparent"},hideOnOverlayClick:true,hideOnContentClick:false,overlayShow:true,overlayOpacity:0.7,overlayColor:"#777",titleShow:true,titlePosition:"float",titleFormat:null,titleFromAlt:false,transitionIn:"fade",transitionOut:"fade",speedIn:300,speedOut:300,changeSpeed:300,changeFade:"fast",easingIn:"swing",
    easingOut:"swing",showCloseButton:true,showNavArrows:true,enableEscapeButton:true,enableKeyboardNav:true,onStart:function(){},onCancel:function(){},onComplete:function(){},onCleanup:function(){},onClosed:function(){},onError:function(){}};b(document).ready(function(){b.fancybox.init()})})(jQuery);
