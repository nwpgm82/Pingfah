// function searchDate(){
//     let x = document.getElementById("code").value
//     location.href = `checkCode.php?code=${x}`
// }

// function preview_image(event, pic) {
//     console.log(pic)
//     var reader = new FileReader();
//     reader.onload = function () {
//         var output = document.getElementById(`output_image${pic}`);
//         output.src = reader.result;
//     }
//     reader.readAsDataURL(event.target.files[0]);
//     document.querySelector("button[type=submit]").disabled = false;
// }

function delImg(id,name) {
    if (confirm('คุณต้องการลบรูปภาพนี้ใช่หรือไม่ ?')) {
        location.href = `mainpage_function/delImage.php?daily_id=${id}&name=${name}`
    }
}

// function cancel_daily(id){
//     if(confirm("คุณต้องการยกเลิกการจองใช่หรือไม่ ?")){
//         location.href = `mainpage_function/cancelDaily.php?daily_id=${id}`
//     }
// }

$(document).ready(function(){
    let id_img = $("#pic_idcard1")
    function getExtension(filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }

    function isImage(filename) {
        var ext = getExtension(filename);
        switch (ext.toLowerCase()) {
            case 'jpg':
            case 'pdf':
            case 'png':
                //etc
                return true;
        }
        return false;
    }
    $("#searchCode").click(function(){
        if($("#code").val() != ""){
            location.href = `checkCode.php?code=${$("#code").val()}`
        }else{
            $("#code").css("border-color","red")
            $("#code_error").html("โปรดระบุเลขที่การจอง")
        }
    })
    $("#code").keyup(function(){
        if($("#code").val() != ""){
            $("#code").css("border-color","")
            $("#code_error").html("")
        }else{
            $("#code").css("border-color","red")
            $("#code_error").html("โปรดระบุเลขที่การจอง")
        }
    })
    id_img.change(function () {
        $("img#img_id").hide()
        $("iframe#img_id").hide()
        if (id_img.val() == "") {
            $("#id_box").css("border-color", "red")
            $("#idimg_error").html("โปรดเพิ่มรูปภาพหลักฐานการชำระเงิน")
        } else if (isImage(id_img.val()) == false) {
            $("#id_box").css("border-color", "red")
            $("#idimg_error").html("รองรับไฟล์ประเภท jpg, png, pdf ขนาดไม่เกิน 5 MB เท่านั้น")
            id_img.val("")
            $("#img_id").hide()
        } else {
            if (this.files && this.files[0]) {
                if (this.files[0].size < 5242880) {
                    var reader = new FileReader();
                    if(id_img.val().split(".").pop() != "pdf"){
                        $("img#img_id").show()
                        reader.onload = function (e) {
                            $('img#img_id').attr('src', e.target.result);
                        }
                    }else{
                        $("iframe#img_id").show()
                        reader.onload = function (e) {
                            $('iframe#img_id').attr('src', e.target.result);
                        }
                    }
                    reader.readAsDataURL(this.files[0]); // convert to base64 string
                    $("#id_box").css("border-color", "")
                    $("#idimg_error").html("")
                    $("button[type=submit]").prop("disabled", false)
                } else {
                    $("#id_box").css("border-color", "red")
                    $("#idimg_error").html("ขนาดรูปภาพใหญ่เกินไป (ไม่เกิน 5 MB)")
                    id_img.val("")
                    $("#img_id").hide()
                }
            }
        }
    })
    $("button[type=submit]").click(function(event){
        if(confirm("คุณต้องการยืนยันการแก้ไขใช่หรือไม่ ?")){
            if(id_img.val() == ""){
                $("#id_box").css("border-color", "red")
                $("#idimg_error").html("โปรดเพิ่มรูปภาพหลักฐานการชำระเงิน")
                event.preventDefault()
            }
        }else{
            event.preventDefault()
        }
    })
    $("#cancel_daily").click(function(){
        if(confirm("คุณต้องการยกเลิกการจองใช่หรือไม่ ?")){
            location.href = `mainpage_function/cancelDaily.php?daily_id=${id}`
        }
    })
})