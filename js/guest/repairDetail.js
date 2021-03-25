function changeStatus(room,app,cat,date){
    var status = document.getElementById("status").value
    console.log(status) 
    if(confirm('คุณต้องการยืนยันใช่หรือไม่ ?')){
        location.href = `../repair/function/repairChangeStatus.php?room_id=${room}&repairappliance=${app}&repaircategory=${cat}&repairdate=${date}&repairstatus=${status}`
    }
}

$(document).ready(function(){
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
    // function formatDate2(inputDate) {
    //     var date = new Date(inputDate);
    //     if (!isNaN(date.getTime())) {
    //         // Months use 0 index.
    //         return date.getMonth() + 1 + '/' + date.getDate() + '/' + date.getFullYear();
    //     }
    // }
    let today_monthNames = [
        "ม.ค.", "ก.พ.", "มี.ค.",
        "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.",
        "ส.ค.", "ก.ย.", "ต.ค.",
        "พ.ค.", "ธ.ค."
    ];
    let today = new Date()
    let today_day = today.getDate()
    let today_month = today.getMonth() + 1
    let today_year = today.getFullYear()
    let current_dayShow = today_day + ' ' + today_monthNames[today_month - 1] + ' ' + today_year
    if (today_day < 10) {
        today_day = '0' + today_day.toString()
    }
    if (today_month < 10) {
        today_month = '0' + today_month.toString()
    }
    let current_day = today_year + '-' + today_month + '-' + today_day
    $("#success_date").val(current_day)
    $("#repair_successdate").html(current_dayShow)
    $("#success_date").dateDropper({
        theme: "my-style",
        lang: "th",
        format: "Y-m-d",
        large: true,
        lock: "to",
        largeDefault: true,
        startFromMonday: false,
    })
    $("#success_date").change(function(){
        $("#repair_successdate").html(formatDate(new Date($("#success_date").val())))
    })

    $("#status").change(function(){
        if($("#status").val() == "ซ่อมเสร็จแล้ว"){
            $("#success_status").show()
        }else{
            $("#success_status").hide()
        }
    })
})