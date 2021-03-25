// function searchDate() {
//     let x = document.getElementById("check_in").value
//     let y = document.getElementById("check_out").value
//     if (x != "" && y != "") {
//         location.assign(`index.php?check_in=${x}&check_out=${y}`)
//     } else {
//         alert("กรุณาระบุวันที่ที่ต้องการค้นหา")
//     }

// }

// function searchCode() {
//     let z = document.getElementById("code").value
//     if (z != "") {
//         location.assign(`index.php?Code=${z}`)
//     } else {
//         alert("กรุณาระบุเลขที่ในการจองที่ต้องการค้นหา")
//     }
// }

// function delDailyCost(id) {
//     if (confirm('คุณต้องการลบรายการชำระเงินนี้ใช่หรือไม่ ?')) {
//         location.href = `function/delDailyCost.php?id=${id}`
//     }
// }

// function formatDate(date) {
//     var monthNames = [
//         "ม.ค.", "ก.พ.", "มี.ค.",
//         "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.",
//         "ส.ค.", "ก.ย.", "ต.ค.",
//         "พ.ค.", "ธ.ค."
//     ];
//     var day = date.getDate();
//     var monthIndex = date.getMonth();
//     var year = date.getFullYear();
//     return day + ' ' + monthNames[monthIndex] + ' ' + year;
// }

// function formatDate2(inputDate) {
//     var date = new Date(inputDate);
//     if (!isNaN(date.getTime())) {
//         // Months use 0 index.
//         return date.getMonth() + 1 + '/' + date.getDate() + '/' + date.getFullYear();
//     }
// }
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
    $(".del").click(function(event){
        if (confirm('คุณต้องการลบรายการชำระเงินนี้ใช่หรือไม่ ?')) {
            location.href = `function/delDailyCost.php?id=${event.target.id}`
        }
    })
})