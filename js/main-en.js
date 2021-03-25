let check_in = document.getElementById("check_in")
let check_out = document.getElementById("check_out")
let check_in_date = document.getElementById("check_in_date")
let check_out_date = document.getElementById("check_out_date")
let people = document.getElementById("people")
function bannerload() {
    var banner = document.getElementById("banner")
    banner.style.transform = "scale(1)"
    banner.style.opacity = 1
    let today_monthNames = [
        "JAN", "FEB", "MAR",
        "APR", "MAY", "JUN", "JUL",
        "AUG", "SEP", "OCT",
        "NOV", "DEC"
    ];
    let today = new Date()
    let today_day = today.getDate()
    let today_month = today.getMonth() + 1
    let today_year = today.getFullYear()
    let tomorrow_day = today.getDate() + 1
    let tomorrow_month = today.getMonth() + 1
    let tomorrow_year = today.getFullYear()
    let current_dayShow = today_day + ' ' + today_monthNames[today_month - 1] + ' ' + today_year
    let next_dayShow = tomorrow_day + ' ' + today_monthNames[tomorrow_month - 1] + ' ' + tomorrow_year
    if (today_day < 10) {
        today_day = '0' + today_day.toString()
    }
    if (today_month < 10) {
        today_month = '0' + today_month.toString()
    }
    if (tomorrow_day < 10) {
        tomorrow_day = '0' + tomorrow_day.toString()
    }
    if (tomorrow_month < 10) {
        tomorrow_month = '0' + tomorrow_month.toString()
    }
    let current_day = today_year + '-' + today_month + '-' + today_day

    let next_day = tomorrow_year + '-' + tomorrow_month + '-' + tomorrow_day
    check_in.value = current_day
    check_in.setAttribute("min", current_day)
    check_out.value = next_day
    check_out.setAttribute("min", check_out.value)
    check_in_date.innerHTML = current_dayShow
    check_out_date.innerHTML = next_dayShow
    console.log(check_in.value)
    console.log(check_out.value)
}
function checkRoom() {
    if (check_in.value !="" && check_out.value != "" && people.value != "") {
        location.href = `pages/checkRoom.php?check_in=${check_in.value}&check_out=${check_out.value}&people=${people.value}`
    } else {
        alert("กรุณากรอกวันที่ค้นหาให้ครบ");
    }
}
function checkInDate(value) {
    console.log(value)
    check_in_date.innerHTML = formatDate(new Date(value))
    check_out.setAttribute("min", check_in.value)
    if (new Date(value).getTime() >= new Date(check_out.value).getTime()) {
        let nDate = new Date(check_in.value)
        nDate.setDate(nDate.getDate() + 1)
        let nDate_date = nDate.getDate()
        let nDate_month = nDate.getMonth() + 1
        if(nDate_date < 10){
            nDate_date = "0" + nDate_date.toString()
        }
        if(nDate_month < 10){
            nDate_month = "0" + nDate_month.toString()
        }
        check_out.value = nDate.getFullYear() + "-" + nDate_month + "-" + nDate_date
        check_out_date.innerHTML = formatDate(new Date(check_out.value))
        check_out.setAttribute("min",check_out.value)
    }
}
function checkInDate2(value) {
    console.log(value)
    check_out_date.innerHTML = formatDate(new Date(value))
}
function formatDate(date) {
    var monthNames = [
        "JAN", "FEB", "MAR",
        "APR", "MAY", "JUN", "JUL",
        "AUG", "SEP", "OCT",
        "NOV", "DEC"
    ];
    var day = date.getDate();
    var monthIndex = date.getMonth();
    var year = date.getFullYear();
    return day + ' ' + monthNames[monthIndex] + ' ' + year;
}