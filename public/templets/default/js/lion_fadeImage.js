(function($){
    $.fn.fadeImage = function(o) {
        o = $.extend({speed:"normal", duration:0}, o || {});

        return this.each(function(){
            var me = $(this), noop = function(){},
                items = $("." + me[0].className + " ul li"),
                btns = $("." + me[0].className + " ol li"),
                num = items.size(),
                current = 0,

                leftBtn = $(".arrow_left"),
                rightBtn = $(".arrow_right"),
                timer = "";
            items.each(function(index){
                items.eq(index).css("display","none");
            });
            autoPlay();

            function autoPlay(){
                btns.eq(current).addClass("active").siblings().removeClass("active");
                items.fadeOut(o.speed);
                items.eq(current).fadeIn(o.speed);
                //items.css("display","none");
                //items.eq(current).css("display","block");
                current++;
                if (current == num ) { current = 0; }
                timer = setTimeout(autoPlay, o.duration);
            }
            items.mouseover(function(){
                clearTimeout(timer);
            });
            items.mouseleave(function(){
                timer = setTimeout(autoPlay, o.duration);
            });
            leftBtn.click(function(){
                if(current <= -num+1) {
                    current = num -1;
                }
                else {
                    current = current - 2;
                }
                clearTimeout(timer);
                autoPlay();
            });
            rightBtn.click(function(){
                if(current == num) { current = 0; }
                clearTimeout(timer);
                autoPlay();
            });
            btns.each(function(index){
                $(this).click(function(){
                    clearTimeout(timer);
                    $(this).addClass("active").siblings().removeClass("active");
                    current = index;
                    if(current == num - 1) {
                        current = -1;
                    }
                    items.fadeOut(o.speed);
                    items.eq(current).fadeIn(o.speed / 2);
                    current++;
                    timer = setTimeout(autoPlay, o.duration);
                });
            });

        });

    };
})(jQuery); 