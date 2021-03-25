$(document).ready(function () {
    let main = $("#main")
    let employee = $("#employee")
    let roomdetail = $("#roomdetail")
    let gallery = $("#gallery")
    let roomlist = $("#roomlist")
    let daily = $("#daily")
    let dailycost = $("#dailycost")
    let cost = $("#cost")
    let repair_report = $("#repair_report")
    let repair = $("#repair")
    let package = $("#package")
    let rule = $("#rule")
    let appeal = $("#appeal")
    let logs = $("#logs")
    let header = $("#topbar-page")
    localStorage.setItem("i", window.location.pathname)
    if (localStorage.getItem("i") == "/Pingfah/pages/manage/index.php") {
        main.css("background-color", "rgba(131, 120, 47, 0.7)")
        main.css("color", "#fff")
        header.html("หน้าหลัก")
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/employee/index.php") {
        employee.css("background-color", "rgba(131, 120, 47, 0.7)")
        employee.css("color", "#fff")
        header.html("จัดการพนักงาน")
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/employee/addemployee.php") {
        employee.css("background-color", "rgba(131, 120, 47, 0.7)")
        employee.css("color", "#fff")
        header.html("เพิ่มพนักงาน")
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/employee/emHistory.php") {
        employee.css("background-color", "rgba(131, 120, 47, 0.7)")
        employee.css("color", "#fff")
        header.html("ประวัติของพนักงาน")
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/employee/emDetail.php") {
        employee.css("background-color", "rgba(131, 120, 47, 0.7)")
        employee.css("color", "#fff")
        header.html("รายละเอียดข้อมูลพนักงาน")
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/roomDetail/index.php") {
        roomdetail.css("background-color", "rgba(131, 120, 47, 0.7)")
        roomdetail.css("color", "#fff")
        header.html("ประเภทห้องพัก")
        $("#dropdown").show()
    } else if(localStorage.getItem("i") == "/Pingfah/pages/manage/roomDetail/detail.php"){
        roomdetail.css("background-color", "rgba(131, 120, 47, 0.7)")
        roomdetail.css("color", "#fff")
        header.html("รายละเอียดห้องพัก")
        $("#dropdown").show()
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/gallery/index.php") {
        gallery.css("background-color", "rgba(131, 120, 47, 0.7)")
        gallery.css("color", "#fff")
        header.html("แกลเลอรี่")
        $("#dropdown").css("display","block")
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/roomList/index.php") {
        roomlist.css("background-color", "rgba(131, 120, 47, 0.7)")
        roomlist.css("color", "#fff")
        header.html("รายการห้องพัก")
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/roomList/roomHistory.php") {
        roomlist.css("background-color", "rgba(131, 120, 47, 0.7)")
        roomlist.css("color", "#fff")
        header.html("ประวัติการเข้าพัก")
    }else if (localStorage.getItem("i") == "/Pingfah/pages/manage/roomList/addmember.php") {
        roomlist.css("background-color", "rgba(131, 120, 47, 0.7)")
        roomlist.css("color", "#fff")
        header.html("เพิ่มข้อมูลผู้เข้าพัก (คนที่ 2)")
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/roomList/room_id.php" || localStorage.getItem("i") == "/Pingfah/pages/manage/roomList/roomdaily_id.php" || localStorage.getItem("i") == "/Pingfah/pages/manage/roomList/memberDetail.php" || localStorage.getItem("i") == "/Pingfah/pages/manage/roomList/memberDetail_daily.php") {
        roomlist.css("background-color", "rgba(131, 120, 47, 0.7)")
        roomlist.css("color", "#fff")
        header.html("รายละเอียดข้อมูลผู้เข้าพัก")
    }else if (localStorage.getItem("i") == "/Pingfah/pages/manage/roomList/member_quit.php") {
        roomlist.css("background-color", "rgba(131, 120, 47, 0.7)")
        roomlist.css("color", "#fff")
        header.html("แจ้งออก ?")
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/daily/index.php" || localStorage.getItem("i") == "/Pingfah/pages/manage/daily/dailyDetail.php") {
        daily.css("background-color", "rgba(131, 120, 47, 0.7)")
        daily.css("color", "#fff")
        header.html("รายการเช่ารายวัน")
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/daily/confirm_checkout.php"){
        daily.css("background-color", "rgba(131, 120, 47, 0.7)")
        daily.css("color", "#fff")
        header.html("เช็คเอาท์ ?")
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/daily/selectroom.php"){
        daily.css("background-color", "rgba(131, 120, 47, 0.7)")
        daily.css("color", "#fff")
        header.html("ยืนยันการเข้าพัก ?")
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/dailyCost/index.php" || localStorage.getItem("i") == "/Pingfah/pages/manage/dailyCost/dailyCostDetail.php") {
        dailycost.css("background-color", "rgba(131, 120, 47, 0.7)")
        dailycost.css("color", "#fff")
        header.html("รายการชำระเงินรายวัน")
        $("#dropdown2").show()
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/cost/index.php") {
        cost.css("background-color", "rgba(131, 120, 47, 0.7)")
        cost.css("color", "#fff")
        header.html("รายการชำระเงินรายเดือน")
        $("#dropdown2").show()
    } else if(localStorage.getItem("i") == "/Pingfah/pages/manage/cost/costDetail.php"){
        cost.css("background-color", "rgba(131, 120, 47, 0.7)")
        cost.css("color", "#fff")
        header.html("รายละเอียดการชำระเงิน")
        $("#dropdown2").show()
    } else if(localStorage.getItem("i") == "/Pingfah/pages/manage/cost/addcost.php"){
        cost.css("background-color", "rgba(131, 120, 47, 0.7)")
        cost.css("color", "#fff")
        header.html("เพิ่มข้อมูลการชำระเงิน")
        $("#dropdown2").show()
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/repair/repairReport.php") {
        repair_report.css("background-color", "rgba(131, 120, 47, 0.7)")
        repair_report.css("color", "#fff")
        header.html("รายการค่าใช้จ่ายจากการแจ้งซ่อม")
        $("#dropdown2").show()
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/repair/index.php") {
        repair.css("background-color", "rgba(131, 120, 47, 0.7)")
        repair.css("color", "#fff")
        header.html("รายการแจ้งซ่อม")
    } else if(localStorage.getItem("i") == "/Pingfah/pages/manage/repair/addRepair.php"){
        repair.css("background-color", "rgba(131, 120, 47, 0.7)")
        repair.css("color", "#fff")
        header.html("เพิ่มรายการแจ้งซ่อม")
    } else if(localStorage.getItem("i") == "/Pingfah/pages/manage/repair/repairDetail.php"){
        repair.css("background-color", "rgba(131, 120, 47, 0.7)")
        repair.css("color", "#fff")
        header.html("ข้อมูลการแจ้งซ่อม")
    }else if (localStorage.getItem("i") == "/Pingfah/pages/manage/package/index.php" || localStorage.getItem("i") == "/Pingfah/pages/manage/package/addPackage.php") {
        package.css("background-color", "rgba(131, 120, 47, 0.7)")
        package.css("color", "#fff")
        header.html("รายการพัสดุ")
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/rule/index.php") {
        rule.css("background-color", "rgba(131, 120, 47, 0.7)")
        rule.css("color", "#fff")
        header.html("กฎระเบียบหอพัก")
    } else if (localStorage.getItem("i") == "/Pingfah/pages/manage/appeal/index.php") {
        appeal.css("background-color", "rgba(131, 120, 47, 0.7)")
        appeal.css("color", "#fff")
        header.html("รายการร้องเรียน")
    }else if (localStorage.getItem("i") == "/Pingfah/pages/manage/appeal/addAppeal.php") {
        appeal.css("background-color", "rgba(131, 120, 47, 0.7)")
        appeal.css("color", "#fff")
        header.html("แจ้งร้องเรียน")
    } else if(localStorage.getItem("i") == "/Pingfah/pages/manage/appeal/appealDetail.php"){
        appeal.css("background-color", "rgba(131, 120, 47, 0.7)")
        appeal.css("color", "#fff")
        header.html("ข้อมูลการร้องเรียน")
    }else if(localStorage.getItem("i") == "/Pingfah/pages/manage/logs.php"){
        logs.css("background-color", "rgba(131, 120, 47, 0.7)")
        logs.css("color", "#fff")
        header.html("ประวัติการใช้งาน")
    } else if(localStorage.getItem("i") == "/Pingfah/pages/manage/myprofile/index.php") {
        $(".profile").css("background-color", "rgba(131, 120, 47, 0.7)")
        $(".profile").css("color", "#fff")
        header.html("ข้อมูลส่วนตัว")
    }
    $(".burger").click(function () {
        if(window.matchMedia('(max-width: 767px)').matches){
            if ($(".sidebar").css("left") == "-230px") {
                $(".topbar").animate({
                    left: "80%",
                }, 400)
                $(".sidebar").animate({
                    left: 0,
                    width: "80%"
                }, 400)
                $("#topbar-page").animate({
                    opacity : 0
                },400)
                $(".profile").fadeOut(400)
                $(".bg-close").fadeIn(400)
            } else {
                $(".topbar").animate({
                    left: 0,
                }, 400)
                $(".sidebar").animate({
                    left: -230,
                    width: 230
                }, 400)
                $(".box").animate({
                    paddingLeft: 0
                }, 400)
                $("#topbar-page").animate({
                    opacity : 1
                },400)
                $(".profile").fadeIn(400)
                $(".bg-close").fadeOut(400)
            }
        }else{
            if ($(".sidebar").css("left") == "-230px") {
                $(".topbar").animate({
                    paddingLeft: 230,
                }, 400)
                $(".sidebar").animate({
                    left: 0
                }, 400)
                $(".bg-close").fadeIn(400)
            } else {
                $(".topbar").animate({
                    paddingLeft: 0,
                }, 400)
                $(".sidebar").animate({
                    left: -230
                }, 400)
                $(".box").animate({
                    paddingLeft: 0
                }, 400)
                $(".bg-close").fadeOut(400)
            }
        }
    })
    $(".bg-close").click(function () {
        if(window.matchMedia('(max-width: 767px)').matches){
            $(".topbar").animate({
                left: 0,
            }, 400)
            $(".sidebar").animate({
                left: -230,
                width: 230
            }, 400)
            $(".box").animate({
                paddingLeft: 0
            }, 400)
            $("#topbar-page").animate({
                opacity : 1
            },400)
            $(".profile").fadeIn(400)
            $(".bg-close").fadeOut(400)
        }else{
            $(".topbar").animate({
                paddingLeft: 0,
            }, 400)
            $(".sidebar").animate({
                left: -230
            }, 400)
            $(".box").animate({
                paddingLeft: 0
            }, 400)
            $(".bg-close").fadeOut(400)
        }
        
    })
    $(".dropdown-btn").click(function(){
        $("#dropdown").toggle()
    })
    $(".dropdown-btn2").click(function(){
        $("#dropdown2").toggle()
    })
})