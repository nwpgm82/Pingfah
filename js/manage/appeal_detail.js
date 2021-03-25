$(document).ready(function () {
    $("#topic").keyup(function () {
        if ($(this).val() == "") {
            $(this).css("border-color", "red")
            $("#t_error").html("โปรดระบุหัวข้อร้องเรียน")
        } else {
            $(this).css("border-color", "")
            $("#t_error").html("")
        }
    })
    $("#detail").keyup(function () {
        if ($(this).val() == "") {
            $(this).css("border-color", "red")
            $("#d_error").html("โปรดระบุเนื้อหาการร้องเรียน")
        } else {
            $(this).css("border-color", "")
            $("#d_error").html("")
        }
    })
    $("#confirm_appeal").click(function (event) {
        if ($("#topic").val() != "" && $("#detail") != "") {
            if (confirm('คุณต้องการส่งคำร้องเรียนใช่หรือไม่ ?')) {
                $("form").submit()
            }else{
                event.preventDefault()
            }
        }else{
            if($("#topic").val() == ""){
                $("#topic").css("border-color", "red")
                $("#t_error").html("โปรดระบุหัวข้อร้องเรียน")
            }
            if($("#detail").val() == ""){
                $("#detail").css("border-color", "red")
                $("#d_error").html("โปรดระบุเนื้อหาการร้องเรียน")
            }
            event.preventDefault()
        }
    })
})