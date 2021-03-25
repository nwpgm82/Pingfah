$(document).ready(function () {
    let room_select = []
    let id_img = $("#id_img")
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
    id_img.change(function () {
        $("img#img_id").hide()
        $("iframe#img_id").hide()
        // console.log(id_img.val().split(".").pop());
        if (id_img.val() == "") {
            $("#id_box").css("border-color", "red")
            $("#idimg_error").html("โปรดเพิ่มรูปภาพสำเนาบัตรประชาชน")
            $("#img_id").hide()
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
                } else {
                    $("#id_box").css("border-color", "red")
                    $("#idimg_error").html("ขนาดรูปภาพใหญ่เกินไป (ไม่เกิน 5 MB)")
                    id_img.val("")
                    $("#img_id").hide()
                }
            }
        }
    })
    $(".air").click(function (event) {
        if (room_select.indexOf(event.target.id) == -1) {
            if (parseInt($("#air_count").html()) != 0) {
                room_select.push(event.target.id)
                $("#air_count").html(parseInt($("#air_count").html()) - 1)
                $(`#${event.target.id}`).css("background-color", "grey")
                $("#room_select").val(room_select.join(", "))
            }
        } else {
            room_select.splice(room_select.indexOf(event.target.id), 1)
            $("#air_count").html(parseInt($("#air_count").html()) + 1)
            $(`#${event.target.id}`).css("background-color", "")
            $("#room_select").val(room_select.join(", "))
        }
    })
    $(".fan").click(function (event) {
        if (room_select.indexOf(event.target.id) == -1) {
            if (parseInt($("#fan_count").html()) != 0) {
                room_select.push(event.target.id)
                $("#fan_count").html(parseInt($("#fan_count").html()) - 1)
                $(`#${event.target.id}`).css("background-color", "grey")
                $("#room_select").val(room_select.join(", "))
            }
        } else {
            room_select.splice(room_select.indexOf(event.target.id), 1)
            $("#fan_count").html(parseInt($("#fan_count").html()) + 1)
            $(`#${event.target.id}`).css("background-color", "")
            $("#room_select").val(room_select.join(", "))
        }
    })
    $("#confirmRent").click(function(event){
        if(confirm("คุณต้องการยืนยันการจองพักใช่หรือไม่ ?")){
            if(room_select.length == 0 || parseInt($("#air_count").html()) != 0 || parseInt($("#fan_count").html()) != 0 || id_img.val() == ""){
                if (id_img.val() == "") {
                    $("#id_box").css("border-color", "red")
                    $("#idimg_error").html("โปรดเพิ่มรูปภาพสำเนาบัตรประชาชน")
                }
                alert("โปรดระบุข้อมูล และเลือกห้องให้ครบ")
                event.preventDefault()
            }
        }else{
            event.preventDefault()
        }
    })
})