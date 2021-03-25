function searchDate() {
    let x = document.getElementById("check_in").value
    let y = document.getElementById("check_out").value
    if (x != "" && y != "") {
        location.assign(`index.php?check_in=${x}&check_out=${y}`)
    } else {
        alert("กรุณาระบุวันที่ที่ต้องการค้นหา")
    }
}

function searchCode() {
    let z = document.getElementById("code").value
    if (z != "") {
        location.assign(`index.php?Code=${z}`)
    } else {
        alert("กรุณาระบุเลขที่ในการจองที่ต้องการค้นหา")
    }
}

function searchCheck(id) {
    var check = document.getElementById(id)
    var come = document.getElementById("come")
    var checkout = document.getElementById("checkout")
    var pending = document.getElementById("pending")
    var cancel = document.getElementById("cancel")
    come.checked = false
    checkout.checked = false
    pending.checked = false
    cancel.checked = false
    check.checked = true
    if (id != "all") {
        location.href = `index.php?Status=${check.id}`
    } else {
        location.href = "index.php"
    }

}

function searchCheck2(checkIn, checkOut, id) {
    var check = document.getElementById(id)
    var come = document.getElementById("come")
    var checkout = document.getElementById("checkout")
    var pending = document.getElementById("pending")
    var cancel = document.getElementById("cancel")
    come.checked = false
    checkout.checked = false
    pending.checked = false
    cancel.checked = false
    check.checked = true
    if (id != "all") {
        location.href = `index.php?check_in=${checkIn}&check_out=${checkOut}&Status=${check.id}`
    } else {
        location.href = `index.php?check_in=${checkIn}&check_out=${checkOut}`
    }

}

function acceptRent(id) {
    if (confirm("คุณต้องการยืนยันการเข้าอยู่ของลูกค้าท่านนี้ใช่หรือไม่")) {
        location.href = `function/acceptRent.php?daily_id=${id}`
    }
}

function selectRoom(num) {
    let btn = document.getElementById(`btn${num}`)
    let room = document.getElementById(`select${num}`)
    btn.style.display = "none"
    room.style.display = "block"
}

function confirmRoom(id, num) {
    let room_select = document.getElementById(`room_select${num}`).value
    if (room_select != "") {
        if (confirm(`คุณต้องการเลือกห้องนี้ ${room_select} ใช่หรือไม่ ? `)) {
            location.href = `function/addDailyData.php?daily_id=${id}&room_select=${room_select}`
        }
    } else {
        alert("กรุณาโปรดเลือกห้อง");
    }

}

function del(id) {
    if (confirm('คุณต้องการลบรายการจองห้องพักใช่หรือไม่ ?')) {
        location.href = `function/delDaily.php?id=${id}`
    }
}

function delImg(id, name) {
    if (confirm("คุณต้องการลบรูปภาพนี้ใช่หรือไม่ ?")) {
        location.href = `function/delImage.php?id=${id}&name=${name}`
    }
}

function check_out(id) {
    if (confirm('คุณต้องการเช็คเอ้าท์ใช่หรือไม่ ?')) {
        location.href = `function/checkout.php?daily_id=${id}`
    }
}

function formatDate(date) {
    var monthNames = [
        "ม.ค.", "ก.พ.", "มี.ค.",
        "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.",
        "ส.ค.", "ก.ย.", "ต.ค.",
        "พ.ค.", "ธ.ค."
    ];
    var day = date.getDate();
    var monthIndex = date.getMonth();
    var year = date.getFullYear();
    return day + ' ' + monthNames[monthIndex] + ' ' + year;
}

$(document).ready(function () {
    let check_in = $("#check_in")
    let check_out = $("#check_out")
    let code = $("#code")

    function BasicDate(date) {
        let year = date.getFullYear()
        let month = date.getMonth() + 1
        let day = date.getDate()
        if (day < 10) {
            day = "0" + day.toString()
        }
        if (month < 10) {
            month = "0" + month.toString()
        }
        return year + "-" + month + "-" + day
    }
    $('.roundtrip-input').dateDropper({
        roundtrip: true,
        theme: "my-style",
        lang: "th",
        format: "d M Y",
        startFromMonday: false,
    });
    $("#searchDate").click(function () {
        if (check_in.val() != "" && check_out.val() != "") {
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let checkIn_toeng = check_in.val().split(" ")
            checkIn_toeng[1] = search.findIndex(el => el === checkIn_toeng[1])
            checkIn_toeng[1] = replace[checkIn_toeng[1]]
            checkIn_toeng = checkIn_toeng.join(" ")
            checkIn_toeng = BasicDate(new Date(checkIn_toeng))
            let checkOut_toeng = check_out.val().split(" ")
            checkOut_toeng[1] = search.findIndex(el => el === checkOut_toeng[1])
            checkOut_toeng[1] = replace[checkOut_toeng[1]]
            checkOut_toeng = checkOut_toeng.join(" ")
            checkOut_toeng = BasicDate(new Date(checkOut_toeng))
            location.href = `index.php?check_in=${checkIn_toeng}&check_out=${checkOut_toeng}`
        } else {
            check_in.css("border-color", "red")
            check_in.css("background-image", "url('../../../img/tool/calendar-error.png')")
            $("#checkIn_error").html("โปรดระบุวันที่ต้องการค้นหา")
            check_out.css("border-color", "red")
            check_out.css("background-image", "url('../../../img/tool/calendar-error.png')")
            $("#checkOut_error").html("โปรดระบุวันที่ต้องการค้นหา")

        }
    })
    check_in.change(function () {
        check_in.css("border-color", "")
        check_in.css("background-image", "")
        $("#checkIn_error").html("")
    })
    check_out.change(function(){
        check_out.css("border-color", "")
        check_out.css("background-image", "")
        $("#checkOut_error").html("")
    })
    $("#searchCode").click(function () {
        if (code.val() != "") {
            location.href = `index.php?code=${code.val()}`
        } else {
            code.css("border-color", "red")
            $("#code_error").html("โปรดระบุเลขที่ในการจอง")
        }
    })
    $("#all").click(function(){
        if(check_in.val() != "" && check_out.val() != ""){
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let checkIn_toeng = check_in.val().split(" ")
            checkIn_toeng[1] = search.findIndex(el => el === checkIn_toeng[1])
            checkIn_toeng[1] = replace[checkIn_toeng[1]]
            checkIn_toeng = checkIn_toeng.join(" ")
            checkIn_toeng = BasicDate(new Date(checkIn_toeng))
            let checkOut_toeng = check_out.val().split(" ")
            checkOut_toeng[1] = search.findIndex(el => el === checkOut_toeng[1])
            checkOut_toeng[1] = replace[checkOut_toeng[1]]
            checkOut_toeng = checkOut_toeng.join(" ")
            checkOut_toeng = BasicDate(new Date(checkOut_toeng))
            location.href = `index.php?check_in=${checkIn_toeng}&check_out=${checkOut_toeng}`
        }else{
            location.href = "index.php"
        }
    })
    $("#waiting").click(function(){
        if(check_in.val() != "" && check_out.val() != ""){
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let checkIn_toeng = check_in.val().split(" ")
            checkIn_toeng[1] = search.findIndex(el => el === checkIn_toeng[1])
            checkIn_toeng[1] = replace[checkIn_toeng[1]]
            checkIn_toeng = checkIn_toeng.join(" ")
            checkIn_toeng = BasicDate(new Date(checkIn_toeng))
            let checkOut_toeng = check_out.val().split(" ")
            checkOut_toeng[1] = search.findIndex(el => el === checkOut_toeng[1])
            checkOut_toeng[1] = replace[checkOut_toeng[1]]
            checkOut_toeng = checkOut_toeng.join(" ")
            checkOut_toeng = BasicDate(new Date(checkOut_toeng))
            location.href = `index.php?check_in=${checkIn_toeng}&check_out=${checkOut_toeng}&status=waiting`
        }else{
            location.href = "index.php?status=waiting"
        }
    })
    $("#pending").click(function(){
        if(check_in.val() != "" && check_out.val() != ""){
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let checkIn_toeng = check_in.val().split(" ")
            checkIn_toeng[1] = search.findIndex(el => el === checkIn_toeng[1])
            checkIn_toeng[1] = replace[checkIn_toeng[1]]
            checkIn_toeng = checkIn_toeng.join(" ")
            checkIn_toeng = BasicDate(new Date(checkIn_toeng))
            let checkOut_toeng = check_out.val().split(" ")
            checkOut_toeng[1] = search.findIndex(el => el === checkOut_toeng[1])
            checkOut_toeng[1] = replace[checkOut_toeng[1]]
            checkOut_toeng = checkOut_toeng.join(" ")
            checkOut_toeng = BasicDate(new Date(checkOut_toeng))
            location.href = `index.php?check_in=${checkIn_toeng}&check_out=${checkOut_toeng}&status=pending`
        }else{
            location.href = "index.php?status=pending"
        }
    })
    $("#come").click(function(){
        if(check_in.val() != "" && check_out.val() != ""){
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let checkIn_toeng = check_in.val().split(" ")
            checkIn_toeng[1] = search.findIndex(el => el === checkIn_toeng[1])
            checkIn_toeng[1] = replace[checkIn_toeng[1]]
            checkIn_toeng = checkIn_toeng.join(" ")
            checkIn_toeng = BasicDate(new Date(checkIn_toeng))
            let checkOut_toeng = check_out.val().split(" ")
            checkOut_toeng[1] = search.findIndex(el => el === checkOut_toeng[1])
            checkOut_toeng[1] = replace[checkOut_toeng[1]]
            checkOut_toeng = checkOut_toeng.join(" ")
            checkOut_toeng = BasicDate(new Date(checkOut_toeng))
            location.href = `index.php?check_in=${checkIn_toeng}&check_out=${checkOut_toeng}&status=come`
        }else{
            location.href = "index.php?status=come"
        }
    })
    $("#checkout").click(function(){
        if(check_in.val() != "" && check_out.val() != ""){
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let checkIn_toeng = check_in.val().split(" ")
            checkIn_toeng[1] = search.findIndex(el => el === checkIn_toeng[1])
            checkIn_toeng[1] = replace[checkIn_toeng[1]]
            checkIn_toeng = checkIn_toeng.join(" ")
            checkIn_toeng = BasicDate(new Date(checkIn_toeng))
            let checkOut_toeng = check_out.val().split(" ")
            checkOut_toeng[1] = search.findIndex(el => el === checkOut_toeng[1])
            checkOut_toeng[1] = replace[checkOut_toeng[1]]
            checkOut_toeng = checkOut_toeng.join(" ")
            checkOut_toeng = BasicDate(new Date(checkOut_toeng))
            location.href = `index.php?check_in=${checkIn_toeng}&check_out=${checkOut_toeng}&status=checkout`
        }else{
            location.href = "index.php?status=checkout"
        }
    })
    $("#cancel").click(function(){
        if(check_in.val() != "" && check_out.val() != ""){
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let checkIn_toeng = check_in.val().split(" ")
            checkIn_toeng[1] = search.findIndex(el => el === checkIn_toeng[1])
            checkIn_toeng[1] = replace[checkIn_toeng[1]]
            checkIn_toeng = checkIn_toeng.join(" ")
            checkIn_toeng = BasicDate(new Date(checkIn_toeng))
            let checkOut_toeng = check_out.val().split(" ")
            checkOut_toeng[1] = search.findIndex(el => el === checkOut_toeng[1])
            checkOut_toeng[1] = replace[checkOut_toeng[1]]
            checkOut_toeng = checkOut_toeng.join(" ")
            checkOut_toeng = BasicDate(new Date(checkOut_toeng))
            location.href = `index.php?check_in=${checkIn_toeng}&check_out=${checkOut_toeng}&status=cancel`
        }else{
            location.href = "index.php?status=cancel"
        }
    })
    $("button[type=submit]").click(function(event){
        if(confirm("คุณต้องการยืนยันการเข้าอยู่ของลูกค้าท่านนี้ใช่หรือไม่")){
            $("form").submit()
        }else{
            event.preventDefault()
        }
    })
    $(".del-btn").click(function(event){
        if (confirm('คุณต้องการลบรายการจองห้องพักใช่หรือไม่ ?')) {
            location.href = `function/delDaily.php?daily_id=${event.target.id}`
        }
    })
})