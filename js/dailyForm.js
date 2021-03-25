$(document).ready(function () {
    let firstname = $("#firstname")
    let lastname = $("#lastname")
    let id_card = $("#id_card")
    let email = $("#email")
    let tel = $("#tel")
    let deposit_img = $("#deposit_img")

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
    deposit_img.change(function () {
        $("img#img_id").hide()
        $("iframe#img_id").hide()
        if (isImage(deposit_img.val()) == false) {
            $(".img-box").css("border-color", "red")
            $("#qr_error").html("รองรับไฟล์ประเภท jpg, png, pdf ขนาดไม่เกิน 5 MB เท่านั้น")
            deposit_img.val("")
            $('#img_id').attr('src', "");
            $("#img_id").hide()
        } else {
            if (this.files && this.files[0]) {
                if (this.files[0].size < 5242880) {
                    // $("#img_id").show()
                    // var reader = new FileReader();
                    // reader.onload = function (e) {
                    //     $('#img_id').attr('src', e.target.result);
                    // }
                    var reader = new FileReader();
                    if(deposit_img.val().split(".").pop() != "pdf"){
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
                    $(".img-box").css("border-color", "")
                    $("#qr_error").html("")
                } else {
                    $(".img-box").css("border-color", "red")
                    $("#qr_error").html("ขนาดรูปภาพใหญ่เกินไป (ไม่เกิน 5 MB)")
                    deposit_img.val("")
                    $('#img_id').attr('src', "");
                    $("#img_id").hide()
                }
            }
        }
    })
    firstname.keyup(function () {
        if (firstname.val() == "") {
            firstname.css("border-color", "red")
            firstname.addClass("placeholder-error")
            $("#fs_error").html("โปรดระบุชื่อของท่าน")
        } else {
            firstname.css("border-color", "")
            firstname.removeClass("placeholder-error")
            $("#fs_error").html("")
        }
    })
    lastname.keyup(function () {
        if (lastname.val() == "") {
            lastname.css("border-color", "red")
            lastname.addClass("placeholder-error")
            $("#ls_error").html("โปรดระบุนามสกุลของท่าน")
        } else {
            lastname.css("border-color", "")
            lastname.removeClass("placeholder-error")
            $("#ls_error").html("")
        }
    })
    id_card.keyup(function () {
        $(this).val($(this).val().replace(/[^\w\s]+/g, ''));
        if (id_card.val() == "") {
            id_card.css("border-color", "red")
            id_card.addClass("placeholder-error")
            $("#id_error").html("โปรดระบุเลขบัตรประชาชน หรือ Passport No. ของท่าน")
        } else if (id_card.val().length != 7 && id_card.val().length != 8 && id_card.val().length != 9 && id_card.val().length != 13) {
            id_card.css("border-color", "red")
            id_card.addClass("placeholder-error")
            $("#id_error").html("รูปแบบเลขบัตรประชาชน หรือ Passport No. ไม่ตรงกัน")
        } else {
            id_card.css("border-color", "")
            id_card.removeClass("placeholder-error")
            $("#id_error").html("")
        }
    });
    email.keyup(function () {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (email.val() == "") {
            email.css("border-color", "red")
            email.addClass("placeholder-error")
            $("#em_error").html("โปรดระบุอีเมลของท่าน")
        } else {
            if (re.test(String(email.val()))) {
                email.css("border-color", "")
                email.removeClass("placeholder-error")
                $("#em_error").html("")
            } else {
                email.css("border-color", "red")
                email.addClass("placeholder-error")
                $("#em_error").html("รูปแบบอีเมลไม่ตรงกัน")
            }
        }
    })
    tel.keyup(function (event) {
        if (event.which !== 8 && event.which !== 0 && event.which < 48 || event.which > 57) {
            $(this).val(function (index, value) {
                return value.replace(/\D/g, "");
            });
        }
        if (tel.val() == "") {
            tel.css("border-color", "red")
            tel.addClass("placeholder-error")
            $("#tel_error").html("โปรดระบุเบอร์โทรศัพท์ของท่าน")
        } else if (tel.val().length != 10) {
            tel.css("border-color", "red")
            tel.addClass("placeholder-error")
            $("#tel_error").html("รูปแบบเบอร์โทรศัพท์ของท่าน")
        } else {
            tel.css("border-color", "")
            tel.removeClass("placeholder-error")
            $("#tel_error").html("")
        }
    })
    $("#confirm_daily").click(function (event) {
        let inputs = $("input");
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        inputs.each(function () {
            if ($(this).attr("id") != "check_in" && $(this).attr("id") != "check_out" && $(this).attr("id") != "people" && $(this).attr("id") != "air" && $(this).attr("id") != "fan") {
                if ($(this).val() == "" || $(this).val() == 0) {
                    $(this).css("border-color", "red")
                    $(this).addClass("placeholder-error")
                    if ($(this).attr("id") == "firstname") {
                        $("#fs_error").html("โปรดระบุชื่อของท่าน")
                    } else if ($(this).attr("id") == "lastname") {
                        $("#ls_error").html("โปรดระบุนามสกุลของท่าน")
                    } else if ($(this).attr("id") == "id_card") {
                        $("#id_error").html("โปรดระบุเลขบัตรประชาชน หรือ Passport No. ของท่าน")
                    } else if ($(this).attr("id") == "tel") {
                        $("#tel_error").html("โปรดระบุเบอร์โทรศัพท์ของท่าน")
                    } else if ($(this).attr("id") == "email") {
                        $("#em_error").html("โปรดระบุอีเมลของท่าน")
                    } else if ($(this).attr("id") == "deposit_img") {
                        $(".img-box").css("border-color", "red")
                        $("#qr_error").html("โปรดเพิ่มรูปภาพหลักฐานการมัดจำค่าห้องพัก")
                    }
                    event.preventDefault()
                }
            }
        })
        if (id_card.val().length != 7 && id_card.val().length != 8 && id_card.val().length != 9 && id_card.val().length != 13) {
            id_card.css("border-color", "red")
            id_card.addClass("placeholder-error")
            $("#id_error").html("รูปแบบเลขบัตรประชาชน หรือ Passport No. ไม่ตรงกัน")
            event.preventDefault()
        }
        if (email.val() != "") {
            if (!re.test(String(email.val()))) {
                $("#em_error").html("รูปแบบไม่ตรงกัน")
                event.preventDefault()
            }
        }
        if (tel.val().length != 10) {
            tel.css("border-color", "red")
            tel.addClass("placeholder-error")
            $("#tel_error").html("รูปแบบเบอร์โทรศัพท์ของท่าน")
            event.preventDefault()
        }
        
    })
})