/**
 *  @author Eugene Terentev <eugene@terentev.net>
 */
var doneScroll = true;
window.addEventListener("wheel", function(e) {
    if(doneScroll){
        doneScroll = false;
        var dir = Math.sign(e.deltaY);
        var currentSlide = $(".slide-area .slide.active");
        var n = currentSlide.data('slide');
        var maxSlide = $('.slide-area .slide:last-child').data('slide');
        n = n + dir;
        if(maxSlide >= n && n > 0){
            currentSlide.removeClass('active');
            $('.slide-area .slide[data-slide="'+ n + '"]').addClass('active');
        }
        setTimeout(function(){doneScroll = true;}, 1000);
        
    }
});
