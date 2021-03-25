var electric = ["หลอดไฟ", "พัดลม", "เครื่องปรับอากาศ", "เครื่องทำน้ำอุ่น", "โทรศัพท์", "โทรทัศน์", "ตู้เย็น", "ไดร์เป่าผม"]
var furniture = ["ตู้เสื้อผ้า", "เตียงนอน", "โต๊ะอ่านหนังสือ", "ประตู", "หน้าต่าง"]
var bathroom = ["ชักโครก", "สายชำระ", "อ่างล้างหน้า", "ก๊อกน้ำ"]
var html = ''

function categoryList() {
    var select = document.getElementById("select").value
    var mydiv = document.getElementById('list')
    while (mydiv.firstChild) {
        mydiv.removeChild(mydiv.firstChild)
    }
    if (select == "เครื่องใช้ไฟฟ้า") {
        for (var i = 0; i < electric.length; i++) {
            html = `<option value="${electric[i]}">${electric[i]}</option>`
            document.getElementById('list').innerHTML += html
        }
        console.log(select)
    } else if (select == "เฟอร์นิเจอร์") {
        for (var i = 0; i < furniture.length; i++) {
            html = `<option value="${furniture[i]}">${furniture[i]}</option>`
            document.getElementById('list').innerHTML += html
        }
        console.log(select)
    } else if (select == "สุขภัณฑ์") {
        for (var i = 0; i < bathroom.length; i++) {
            html = `<option value="${bathroom[i]}">${bathroom[i]}</option>`
            document.getElementById('list').innerHTML += html
        }
        console.log(select)
    } else {

    }
    console.log(html)
}

$(document).ready(function () {
    $("#repairDate").dateDropper({
        theme: "my-style",
        lang: "th",
        format: "d M Y",
        lock: "to",
        large: true,
        largeDefault: true,
        startFromMonday: false,
    })
    $("button[type=submit]").click(function (event) {
        let selects = $("select")
        selects.each(function () {
            if ($(this).val() == "") {
                $(this).css("border-color", "red")
                if ($(this).attr("id") == "room_select") {
                    $("#room_error").html("โปรดเลือกเลขห้อง")
                } else if ($(this).attr("id") == "select") {
                    $("#cat_error").html("โปรดเลือกประเภท")
                }
                event.preventDefault()
            }
        })
        if ($("#repair_detail").val() == "") {
            $("#repair_detail").css("border-color", "red")
            $("#detail_error").html("โปรดระบุรายละเอียด")
        }
        if ($("#repairDate").val() == "") {
            $("#repairDate").css("border-color", "red")
            $("#repairDate").css("background-image", "url('../../../img/tool/calendar-error.png')")
            $("#date_error").html("โปรดระบุวันที่แจ้งซ่อม")
        }
    })
    $("#room_select").change(function(){
        if($("#room_select").val() != ""){
            $("#room_select").css("border-color","")
            $("#room_error").html("")
        }else{
            $("#room_select").css("border-color","red")
            $("#room_error").html("โปรดเลือกเลขห้อง")
        }
        
    })
    $("#select").change(function(){
        if($("#select").val() != ""){
            $("#select").css("border-color","")
            $("#cat_error").html("")
        }else{
            $("#select").css("border-color","red")
            $("#cat_error").html("โปรดเลือกประเภท")
        }
    })
    $("#repair_detail").keyup(function(){
        if($("#repair_detail").val() != ""){
            $("#repair_detail").css("border-color", "")
            $("#detail_error").html("")
        }else{
            $("#repair_detail").css("border-color", "red")
            $("#detail_error").html("โปรดระบุรายละเอียด")
        }
    })
    $("#repairDate").change(function(){
        $("#repairDate").css("border-color","")
        $("#date_error").html("")
        $("#repairDate").css("background-image", "")
    })
})