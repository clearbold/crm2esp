/* http://spiffygif.com/ */
(function() {
    SpriteSpinner = function(el, options){
        var self = this,
            img = el.children[0];
        this.interval = options.interval || 10;
        this.diameter = options.diameter || img.width;
        this.count = 0;
        this.el = el;
        img.setAttribute("style", "position:absolute");
        el.style.width = this.diameter+"px";
        el.style.height = this.diameter+"px";
        return this;
    };
    SpriteSpinner.prototype.start = function(){
        var self = this,
            count = 0,
            img = this.el.children[0];
        this.el.display = "block";
        self.loop = setInterval(function(){
            if(count == 19){
                count = 0;
            }
            img.style.top = (-self.diameter*count)+"px";
            count++;
        }, this.interval);
    };
    SpriteSpinner.prototype.stop = function(){
        clearInterval(this.loop);
        this.el.style.display = "none";
    };
    document.SpriteSpinner = SpriteSpinner;
})();

$(document).ready( function() {
    $('.api-string').focus( function(e) {
        $(this).select();
    })

    $('.toggle-json').on('click', function(e) {
        $(this).parent('p').prev('p').children('textarea').slideToggle( function() {
            $(this).parent('p').next('p').children('.toggle-json').text(
                $(this).is(':visible') ? 'Hide JSON' : 'Show JSON'
            );
        });
    })

    $('.run-list button').on('click', function(e) {
        $button = $(this);
        $button.prev('.sprite-spinner-wrapper').fadeIn(50, function(i){
            var s = new SpriteSpinner($(this).children('.sprite-spinner')[0], {
                interval:50
            });
            s.start();
        });
        var jqxhr = $.getJSON('/import-list/' + $(this).data('list-id'), function(data) {
            console.log( "complete" );
        })
        .done(function(data) {
            console.log( "done" );
            $button.prev('.sprite-spinner-wrapper').fadeOut(25);
            console.log(data);
            if (data)
            {
                $activeSubscribers = $button.closest('div.panel').children('h3').children('span').children('.active-subscribers');
                $activeSubscribers.text(($activeSubscribers.text()*1) + data.TotalNewSubscribers);
            }
        })
        .fail(function(data) {
            console.log( "fail (error)" );
        })
        .always(function(data) {
            console.log( "always (complete)" );
        });


    })
})
