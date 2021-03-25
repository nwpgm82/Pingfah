$(document).ready(function () {
    let room = $("#room_price").val()
    let cable = $("#cable_price").val()
    let water = $("#water_price").val()
    let elec = $("#elec_price").val()
    let fines = $("#fines_price").val()
    if (room.indexOf(',') > -1) {
        room = room.replace(/,/g, '')
    }
    if (cable.indexOf(',') > -1) {
        cable = cable.replace(/,/g, '')
    }
    if (water.indexOf(',') > -1) {
        water = water.replace(/,/g, '')
    }
    if (elec.indexOf(',') > -1) {
        elec = elec.replace(/,/g, '')
    }
    if (fines.indexOf(',') > -1) {
        fines = fines.replace(/,/g, '')
    }
    console.log(room)
    console.log(cable)
    console.log(water)
    console.log(elec)
    console.log(fines)

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
    $(document).on("click", "#edit-btn", function () {
        $("#edit-btn").hide()
        $("#edit-option").css("display", "grid")
        let inputs = $("input")
        inputs.each(function () {
            if ($(this).attr("id") != "room_id" && $(this).attr("id") != "room_type" && $(this).attr("id") != "cost_date" && $(this).attr("id") != "cost_status" && $(this).attr("id") != "pay_date") {
                $(this).prop("disabled", false)
                if ($(this).attr("id") == "total_price") {
                    $(this).prop("readonly", true)
                }
            }
        })
    })
    $(document).on("click", "#cancel-edit", function () {
        $('#c').load(location.href + ' #c_detail');
    })
    $(document).on("click", "#accept-edit", function (event) {
        if ($("#room_price").val() == "" || $("#cable_price").val() == "" || $("#water_price").val() == "" || $("#elec_price").val() == "") {
            event.preventDefault()
        } else {
            return confirm("คุณต้องการแก้ไข้รายการชำระเงินใช่หรือไม่")
        }
    })
    $(document).on("click", "#edit-btn2", function () {
        $("#edit-btn2").hide()
        $("#edit-option2").css("display", "grid")
        $("#pay_img").prop("disabled", false)

    })
    $(document).on("click", "#cancel-edit2", function () {
        $('#pay').load(location.href + ' #sub-pay');
    })
    $(document).on("click", "#accept-edit2", function (event) {
        if ($("#pay_img").val() != "") {
            if (confirm("คุณต้องการอัปโหลดหลักฐานการชำระเงินค่าห้องพักใช่หรือไม่ ?")) {
                $("#pay_form").submit()
            } else {
                event.preventDefault()
            }
        } else {
            $(".img-box").css("border-color", "red")
            $("#pay_error").html("โปรดอัปโหลดหลักฐานการชำระเงินค่าห้องพัก")
            event.preventDefault()
        }
    })
    $(document).on("keyup", "#room_price", function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
        if ($("#room_price").val() != "") {
            room = $("#room_price").val()
            $("#room_price").css("border-color", "")
            $("#room_error").html("")
        } else {
            room = 0
            $("#room_price").css("border-color", "red")
            $("#room_error").html("โปรดระบุค่าห้องพัก")
        }
        $("#total_price").val((parseInt(room) + parseInt(cable) + parseInt(water) + parseInt(elec) + parseInt(fines)).toFixed(2))
    })
    $(document).on("keyup", "#cable_price", function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
        if ($("#cable_price").val() != "") {
            cable = $("#cable_price").val()
            $("#cable_price").css("border-color", "")
            $("#cable_error").html("")
        } else {
            cable = 0
            $("#cable_price").css("border-color", "red")
            $("#cable_error").html("โปรดระบุค่าเคเบิล")
        }
        $("#total_price").val((parseInt(room) + parseInt(cable) + parseInt(water) + parseInt(elec) + parseInt(fines)).toFixed(2))
    })
    $(document).on("keyup", "#water_price", function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
        if ($("#water_price").val() != "") {
            water = $("#water_price").val()
            $("#water_price").css("border-color", "")
            $("#water_error").html("")
        } else {
            water = 0
            $("#water_price").css("border-color", "red")
            $("#water_error").html("โปรดระบุค่าน้ำ")
        }
        $("#total_price").val((parseInt(room) + parseInt(cable) + parseInt(water) + parseInt(elec) + parseInt(fines)).toFixed(2))
    })
    $(document).on("keyup", "#elec_price", function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
        if ($("#elec_price").val() != "") {
            elec = $("#elec_price").val()
            $("#elec_price").css("border-color", "")
            $("#elec_error").html("")
        } else {
            elec = 0
            $("#elec_price").css("border-color", "red")
            $("#elec_error").html("โปรดระบุค่าไฟ")
        }
        $("#total_price").val((parseInt(room) + parseInt(cable) + parseInt(water) + parseInt(elec) + parseInt(fines)).toFixed(2))
    })
    $(document).on("keyup", "#fines_price", function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
        if ($("#fines_price").val() != "") {
            fines = $("#fines_price").val()
            $("#fines_price").css("border-color", "")
            $("#fines_error").html("")
        } else {
            fines = 0
            $("#fines_price").css("border-color", "red")
            $("#fines_error").html("โปรดระบุค่าปรับ")
        }
        $("#total_price").val((parseInt(room) + parseInt(cable) + parseInt(water) + parseInt(elec) + parseInt(fines)).toFixed(2))
    })
    $(document).on("change", "#pay_img", function () {
        $("img#img_pay").hide()
        $("iframe#img_pay").hide()
        if (isImage($("#pay_img").val()) == false) {
            $(".img-box").css("border-color", "red")
            $("#pay_error").html("รองรับไฟล์ประเภท jpg, png, pdf ขนาดไม่เกิน 5 MB เท่านั้น")
            $("#pay_img").val("")
            $('#img_pay').attr('src', "");
            $("#img_pay").hide()
        } else {
            if (this.files && this.files[0]) {
                if (this.files[0].size < 5242880) {
                    var reader = new FileReader();
                    if ($("#pay_img").val().split(".").pop() != "pdf") {
                        $("img#img_pay").show()
                        reader.onload = function (e) {
                            $('img#img_pay').attr('src', e.target.result);
                        }
                    } else {
                        $("iframe#img_pay").show()
                        reader.onload = function (e) {
                            $('iframe#img_pay').attr('src', e.target.result);
                        }
                    }
                    reader.readAsDataURL(this.files[0]); // convert to base64 string
                    $(".img-box").css("border-color", "")
                    $("#pay_error").html("")
                } else {
                    $(".img-box").css("border-color", "red")
                    $("#pay_error").html("ขนาดรูปภาพใหญ่เกินไป (ไม่เกิน 5 MB)")
                    $("#pay_img").val("")
                    $('#img_pay').attr('src', "");
                    $("#img_pay").hide()
                }
            }
        }
    })
})