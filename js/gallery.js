$(document).ready(function () {
    $(document).on('readystatechange', readyStateChanged); 
    function readyStateChanged() {
        console.log(document.readyState)
        if (document.readyState !== "complete") { 
            console.log("xx")
            $("body").css("visibility","hidden")
            $("#l").show()
        } else { 
            console.log("yy")
            $("#l").fadeOut(500)
            $("body").css("visibility","visible")
        } 
    }
    AOS.init();
    $(window).scroll(function () {
        if (window.pageYOffset > 180) {
            $(".clickTotop").show()
        } else {
            $(".clickTotop").hide()
        }
    })
    $(".clickTotop").click(function () {
        $("html, body").animate({
            scrollTop: 0,
        }, 1000)
    })

})

function showImg(num) {
    let modal = document.querySelector(`#modal${num}`)
    modal.style.display = "flex"
    // console.log("yyy")
}

function close_modal(num) {
    let modal = document.querySelector(`#modal${num}`)
    modal.style.display = "none"
}