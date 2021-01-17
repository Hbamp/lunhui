$(document).ready(function () {
    function placeholderSupport() {
        return 'placeholder' in document.createElement('input');
    }
    if(!placeholderSupport()){   // 判断浏览器是否支持 placeholder
        $("[placeholder]").each(function(){
            var _this = $(this);
            var height = _this.css("height");
            var lineheight = _this.css("line-height");
            var left = _this.css("padding-left");
            _this.parent().append('<span class="placeholder" data-type="placeholder" style="position: absolute;top: 0;left: ' + left + ';height:' + height + ';line-height:' + lineheight + ' ;">' + _this.attr("placeholder") + '</span>');
            if(_this.val() != ""){
                _this.parent().find("span.placeholder").hide();
            }
            else{
                _this.parent().find("span.placeholder").show();
            }
        }).bind("click", function(){
            $(this).parent().find("span.placeholder").hide();
        }).bind("blur", function(){
            var _this = $(this);
            if(_this.val() != ""){
                _this.parent().find("span.placeholder").hide();
            }
            else{
                _this.parent().find("span.placeholder").show();
            }
        });
        // 点击表示placeholder的标签相当于触发input
        $("span.placeholder").bind("click", function(){
            $(this).hide();
            $(this).siblings("[placeholder]").trigger("click");
        });
    }
})