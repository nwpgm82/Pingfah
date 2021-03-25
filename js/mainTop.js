$(document).ready(function () {
    $(".burger").click(function () {
        if (window.matchMedia('(max-width: 767px)').matches) {
            if ($("#menu").css("display") == "none") {
                $("#menu").fadeIn(200)
                $(".topbar ul a").animate({
                    opacity: 1
                }, 200)
            } else {
                $("#menu").fadeOut(200)
                $(".topbar ul a").animate({
                    opacity: 0
                }, 200)
            }
        } else {
            if ($("#menu").css("display") == "none") {
                $("#menu").css("display", "flex").fadeIn(400)
                $("#menu").animate({
                    height: "120px"
                }, 400)
                $(".topbar ul a").delay(400).animate({
                    opacity: 1
                }, 400)
            } else {
                $(".topbar ul a").css("opacity", 0)
                $("#menu").animate({
                    height: 0
                }, 400)
                $("#menu").fadeOut(400)
            }
        }
    })
})