$(document).ready(function () {
    let pay_img = $("#file")
    let d_img = $("#file2")

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
    pay_img.change(function () {
        $("img#img_payment").hide()
        $("iframe#img_payment").hide()
        if (isImage(pay_img.val()) == false) {
            $("#img-box").css("border-color", "red")
            $("#pay_error").html("รองรับไฟล์ประเภท jpg, png, pdf ขนาดไม่เกิน 5 MB เท่านั้น")
            pay_img.val("")
            $('#img_payment').attr('src', "");
            $("#img_payment").hide()
        } else {
            if (this.files && this.files[0]) {
                if (this.files[0].size < 5242880) {
                    var reader = new FileReader();
                    if(pay_img.val().split(".").pop() != "pdf"){
                        $("img#img_payment").show()
                        reader.onload = function (e) {
                            $('img#img_payment').attr('src', e.target.result);
                        }
                    }else{
                        $("iframe#img_payment").show()
                        reader.onload = function (e) {
                            $('iframe#img_payment').attr('src', e.target.result);
                        }
                    }
                    reader.readAsDataURL(this.files[0]); // convert to base64 string
                    $("#img-box").css("border-color", "")
                    $("#pay_error").html("")
                    $("#submitForm").prop("disabled", false)
                } else {
                    $("#img-box").css("border-color", "red")
                    $("#pay_error").html("ขนาดรูปภาพใหญ่เกินไป (ไม่เกิน 5 MB)")
                    pay_img.val("")
                    $('#img_payment').attr('src', "");
                    $("#img_payment").hide()
                }
            }
        }
    })
    d_img.change(function () {
        $("img#img_d").hide()
        $("iframe#img_d").hide()
        if (isImage(d_img.val()) == false) {
            $("#img-box2").css("border-color", "red")
            $("#d_error").html("รองรับไฟล์ประเภท jpg, png, pdf ขนาดไม่เกิน 5 MB เท่านั้น")
            d_img.val("")
            $('#img_d').attr('src', "");
            $("#img_d").hide()
        } else {
            if (this.files && this.files[0]) {
                if (this.files[0].size < 5242880) {
                    var reader = new FileReader();
                    if(d_img.val().split(".").pop() != "pdf"){
                        $("img#img_d").show()
                        reader.onload = function (e) {
                            $('img#img_d').attr('src', e.target.result);
                        }
                    }else{
                        $("iframe#img_d").show()
                        reader.onload = function (e) {
                            $('iframe#img_d').attr('src', e.target.result);
                        }
                    }
                    reader.readAsDataURL(this.files[0]); // convert to base64 string
                    $("#img-box2").css("border-color", "")
                    $("#d_error").html("")
                    $("#submitForm").prop("disabled", false)
                } else {
                    $("#img-box2").css("border-color", "red")
                    $("#d_error").html("ขนาดรูปภาพใหญ่เกินไป (ไม่เกิน 5 MB)")
                    d_img.val("")
                    $('#img_d').attr('src', "");
                    $("#img_d").hide()
                }
            }
        }
    })
    $("#submitForm").click(function (event) {
        if (pay_img.val() == "") {
            $(".img-box").css("border-color", "red")
            $("#pay_error").html("โปรดเพิ่มรูปภาพหลักฐานการชำระเงินค่าห้องพัก")
            event.preventDefault()
        } else {
            if (confirm("คุณต้องการยืนยันการแก้ไขใช่หรือไม่ ?")) {
                $("form").submit()
            } else {
                event.preventDefault()
            }
        }
    })
    $(".del-btn").click(function (event) {
        if (confirm("คุณต้องการลบรูปภาพนี้ใช่หรือไม่ ?")) {
            location.href = `function/delImage.php?dailycost_id=${event.target.id}&type=${event.target.name}`
        }
    })
})

// function delImg(id){
//     if(confirm("คุณต้องการลบรูปภาพนี้ใช่หรือไม่ ?")){
//         location.href = `function/delImage.php?dailycost_id=${id}`
//     }
// }