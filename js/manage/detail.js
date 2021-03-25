$(document).ready(function () {
    let checkboxs = $("input[type=checkbox]")
    checkboxs.each(function(){
        if ($(this).prop("checked") == true) {
            $(this).val("on")
        } else {
            $(this).val("off")
        }
    })
    let url_string = window.location.href;
    let url = new URL(url_string);
    let room_type = url.searchParams.get("type");
    function getExtension(filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }
    function isImage(filename) {
        var ext = getExtension(filename);
        switch (ext.toLowerCase()) {
            case 'jpg':
                // case 'pdf':
            case 'png':
                //etc
                return true;
        }
        return false;
    }
    $(document).on("change", "#file", function(){
        let formdata = new FormData();
        let files = $("#file")[0].files[0];
        let file_size = $("#file")[0].files[0].size
        formdata.append("file", files);
        if (isImage($("#file").val()) != false) {
            if (file_size < 5242880) {
                $.ajax({
                    url: `function/addImage.php?type=${room_type}`,
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function () {
                        $("#gal-box").load(location.href + " #gal-content")
                    }
                });
            } else {
                alert("ขนาดรูปภาพใหญ่เกินไป (ไม่เกิน 5 MB)")
                $("#file").val("")
            }
        } else {
            alert("รองรับไฟล์ประเภท jpg, png ขนาดไม่เกิน 5 MB เท่านั้น")
            $("#file").val("")
        }
    });
    $(document).on("click", ".del-btn", function (event) {
        if (confirm('คุณต้องการลบรูปภาพนี้ใช่หรือไม่ ?')) {
            let img_id = event.target.id
            let img_name = event.target.name
            $.ajax({
                url: `function/delImage.php?type=${room_type}`,
                type: 'post',
                data: {
                    id: img_id,
                    img_name: img_name
                },
                success: function () {
                    document.location.reload()
                }
            });
        }
    })
    $(".correct-btn").click(function () {
        if ($("#daily_price").val() != "" && $("#daily_deposit").val() != "" && $("#price").val() != "" && $("#water_bill").val() != "" && $("#elec_bill").val() != "" && $("#cable_charge").val() != "" && $("#fines").val() != "") {
            $.ajax({
                url: `function/addData.php?type=${room_type}`,
                type: 'post',
                data: {
                    daily_price: $("#daily_price").val(),
                    daily_deposit: $("#daily_deposit").val(),
                    price: $("#price").val(),
                    water_bill: $("#water_bill").val(),
                    elec_bill: $("#elec_bill").val(),
                    cable_charge: $("#cable_charge").val(),
                    fines: $("#fines").val(),
                    deposit: $("#deposit").val(),
                    sv_fan: $("#sv_fan").val(),
                    sv_air: $("#sv_air").val(),
                    sv_wifi: $("#sv_wifi").val(),
                    sv_furniture: $("#sv_furniture").val(),
                    sv_readtable: $("#sv_readtable").val(),
                    sv_telephone: $("#sv_telephone").val(),
                    sv_television: $("#sv_television").val(),
                    sv_refrigerator: $("#sv_refrigerator").val(),
                    sv_waterbottle: $("#sv_waterbottle").val(),
                    sv_toilet: $("#sv_toilet").val(),
                    sv_hairdryer: $("#sv_hairdryer").val(),
                    sv_towel: $("#sv_towel").val()
                },
                success: function () {
                    document.location.reload()
                }
            });
        }
    })
    $(".edit-btn").click(function () {
        let inputs = $("input")
        inputs.each(function () {
            $(this).prop("disabled", false)
        })
        $("#edit").hide()
        $("#accept").css("display", "flex")
        $("#accept").css("column-gap", "8px")
    })
    $(".cancel-btn").click(function () {
        let inputs = $("input")
        inputs.each(function () {
            $(this).prop("disabled", true)
        })
        $("#edit").css("display", "flex")
        $("#accept").hide()
        document.location.reload()
    })
    $("input[type=text]").keyup(function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    })
    $("#daily_price").keyup(function () {
        if ($(this).val() == "") {
            $(this).css("border-color", "red")
            $("#daily_price_error").html("โปรดระบุค่าเช่าห้อง(รายวัน)")
        } else {
            $(this).css("border-color", "")
            $("#daily_price_error").html("")
        }
    })
    $("#daily_deposit").keyup(function () {
        if ($(this).val() == "") {
            $(this).css("border-color", "red")
            $("#daily_deposit_error").html("โปรดระบุค่ามัดจำห้องพัก")
        } else {
            $(this).css("border-color", "")
            $("#daily_deposit_error").html("")
        }
    })
    $("#price").keyup(function () {
        if ($(this).val() == "") {
            $(this).css("border-color", "red")
            $("#price_error").html("โปรดระบุค่าเช่าห้อง(รายเดือน)")
        } else {
            $(this).css("border-color", "")
            $("#price_error").html("")
        }
    })
    $("#water_bill").keyup(function () {
        if ($(this).val() == "") {
            $(this).css("border-color", "red")
            $("#water_bill_error").html("โปรดระบุค่าน้ำ")
        } else {
            $(this).css("border-color", "")
            $("#water_bill_error").html("")
        }
    })
    $("#elec_bill").keyup(function () {
        if ($(this).val() == "") {
            $(this).css("border-color", "red")
            $("#elec_bill_error").html("โปรดระบุค่าไฟ")
        } else {
            $(this).css("border-color", "")
            $("#elec_bill_error").html("")
        }
    })
    $("#cable_charge").keyup(function () {
        if ($(this).val() == "") {
            $(this).css("border-color", "red")
            $("#cable_charge_error").html("โปรดระบุค่าเคเบิล")
        } else {
            $(this).css("border-color", "")
            $("#cable_charge_error").html("")
        }
    })
    $("#fines").keyup(function () {
        if ($(this).val() == "") {
            $(this).css("border-color", "red")
            $("#fines_error").html("โปรดระบุค่าปรับ")
        } else {
            $(this).css("border-color", "")
            $("#fines_error").html("")
        }
    })
    $("#deposit").keyup(function () {
        if ($(this).val() == "") {
            $(this).css("border-color", "red")
            $("#d_error").html("โปรดระบุเงินประกัน")
        } else {
            $(this).css("border-color", "")
            $("#d_error").html("")
        }
    })
    $("input[type=checkbox]").click(function(){
        if($(this).prop("checked") == true){
            $(this).val("on")
        }else{
            $(this).val("off")
        }
    })
})