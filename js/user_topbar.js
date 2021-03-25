var user = new Vue({
    el: '#user',
    data: {
        menulist: [{
            link: '/Pingfah/pages/user/home.php',
            text: 'หน้าแรก',
            id: 'home'
        }, {
            link: '/Pingfah/pages/user/profile.php',
            text: 'ข้อมูลส่วนตัว',
            id: 'profile'
        }, {
            link: '/Pingfah/pages/user/#',
            text: 'ค่าใช้จ่าย',
            id: 'payment'
        }, {
            link: '/Pingfah/pages/user/package.php',
            text: 'รายการพัสดุ',
            id: 'package'
        }, {
            link: '/Pingfah/pages/user/#',
            text: 'แจ้งซ่อม',
            id: 'repair'
        }, {
            link: '/Pingfah/pages/user/#',
            text: 'ร้องเรียน',
            id: 'appeal'
        }, {
            link: '/Pingfah/pages/user/#',
            text: 'ออกจากระบบ',
            id: 'logout'
        }]
    },
    methods: {
        burgerShow() {
            var ul = document.getElementById("ulShow")
            if (window.matchMedia("(max-width: 1023px)")) {
                if (ul.style.display == '' || ul.style.display == 'none') {
                    ul.style.display = 'block'
                } else if (ul.style.display == 'block') {
                    ul.style.display = 'none'
                }
            }
        }
    },
    mounted(){
        if(window.location.pathname == "/Pingfah/pages/user/home.php"){
            document.getElementById("home").style.backgroundColor = 'rgba(131, 120, 47, 0.7)'
            document.getElementById("home").style.color = 'white'
            localStorage.setItem("i", window.location.pathname)
        }else if(window.location.pathname == "/Pingfah/pages/user/profile.php"){
            document.getElementById("profile").style.backgroundColor = 'rgba(131, 120, 47, 0.7)'
            document.getElementById("profile").style.color = 'white'
            localStorage.setItem("i", window.location.pathname)
        }else if(window.location.pathname == "/Pingfah/pages/user/package.php"){
            document.getElementById("package").style.backgroundColor = 'rgba(131, 120, 47, 0.7)'
            document.getElementById("package").style.color = 'white'
            localStorage.setItem("i", window.location.pathname)
        }
        
    }
})