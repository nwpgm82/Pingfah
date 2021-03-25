window.addEventListener("scroll", function () {
    var topbar = document.getElementById("topbar")
    var ul = document.getElementById("ulShow")
    if (window.pageYOffset > 0) {
        topbar.style.background = "url(/Pingfah/img/tool/topbar-bg.png) #fcf7e5 no-repeat bottom right"
        topbar.style.boxShadow = "2px 0px 4px 0px rgba(0, 0, 0, 0.4)"
    } else {
        if (window.matchMedia("(max-width: 1023px)")) {
            if (ul.style.display == 'block') {
                topbar.style.background = "url(/Pingfah/img/tool/topbar-bg.png) #fcf7e5 no-repeat bottom right"
                topbar.style.boxShadow = "2px 0px 4px 0px rgba(0, 0, 0, 0.4)"
            } else {
                topbar.style.background = "transparent"
                topbar.style.boxShadow = "none"
            }
        } else {
            topbar.style.background = "transparent"
            topbar.style.boxShadow = "none"
        }
    }
})

function burgerShow() {
    var topbar = document.getElementById("topbar")
    var ul = document.getElementById("ulShow")
    if (window.matchMedia("(max-width: 1023px)")) {
        if (ul.style.display == '' || ul.style.display == 'none') {
            ul.style.display = 'block'
            topbar.style.background = "url(/Pingfah/img/tool/topbar-bg.png) #fcf7e5 no-repeat bottom right"
            topbar.style.boxShadow = "2px 0px 4px 0px rgba(0, 0, 0, 0.4)"
        } else if (ul.style.display == 'block') {
            ul.style.display = 'none'
            if (window.pageYOffset > 0) {
                topbar.style.background = "url(/Pingfah/img/tool/topbar-bg.png) #fcf7e5 no-repeat bottom right"
                topbar.style.boxShadow = "2px 0px 4px 0px rgba(0, 0, 0, 0.4)"
            } else {
                topbar.style.background = "transparent"
                topbar.style.boxShadow = "none"
            }
        }
    }
}