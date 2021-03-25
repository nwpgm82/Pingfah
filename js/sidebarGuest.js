function menubar() {
    // localStorage.removeItem("i");
    let main = document.querySelector("#main")
    // let profile = document.querySelector("#profile")
    let cost = document.querySelector("#cost")
    let repair = document.querySelector("#repair")
    let package = document.querySelector("#package")
    let rule = document.querySelector("#rule")
    let appeal = document.querySelector("#appeal")
    let header = document.querySelector("#topbar-page")

    localStorage.setItem("i", window.location.pathname)
    localStorage.getItem("i")
    if (localStorage.getItem("i") == "/Pingfah/pages/guest/index.php") {
        main.style.backgroundColor = "rgba(131, 120, 47, 0.7)"
        main.style.color = "#fff"
        header.innerHTML = "หน้าหลัก"
    } else if (localStorage.getItem("i") == "/Pingfah/pages/guest/myprofile/index.php") {
        header.innerHTML = "ประวัติส่วนตัว"
    }else if (localStorage.getItem("i") == "/Pingfah/pages/guest/cost/index.php") {
        cost.style.backgroundColor = "rgba(131, 120, 47, 0.7)"
        cost.style.color = "#fff"
        header.innerHTML = "ตรวจสอบค่าใช้จ่าย"
    } else if (localStorage.getItem("i") == "/Pingfah/pages/guest/repair/index.php" || localStorage.getItem("i") == "/Pingfah/pages/guest/repair/addRepair.php" || localStorage.getItem("i") == "/Pingfah/pages/guest/repair/repairDetail.php") {
        repair.style.backgroundColor = "rgba(131, 120, 47, 0.7)"
        repair.style.color = "#fff"
        header.innerHTML = "รายการแจ้งซ่อม"
    } else if (localStorage.getItem("i") == "/Pingfah/pages/guest/package/index.php") {
        package.style.backgroundColor = "rgba(131, 120, 47, 0.7)"
        package.style.color = "#fff"
        header.innerHTML = "รายการพัสดุ"
    } else if (localStorage.getItem("i") == "/Pingfah/pages/guest/rule/index.php") {
        rule.style.backgroundColor = "rgba(131, 120, 47, 0.7)"
        rule.style.color = "#fff"
        header.innerHTML = "กฎระเบียบหอพัก"
    } else if (localStorage.getItem("i") == "/Pingfah/pages/guest/appeal/index.php" || localStorage.getItem("i") == "/Pingfah/pages/guest/appeal/addAppeal.php" || localStorage.getItem("i") == "/Pingfah/pages/guest/appeal/appealDetail.php") {
        appeal.style.backgroundColor = "rgba(131, 120, 47, 0.7)"
        appeal.style.color = "#fff"
        header.innerHTML = "รายการร้องเรียน"
    }
}