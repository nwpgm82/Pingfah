$(document).ready(function () {
    function getExtension(filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }

    function isImage(filename) {
        var ext = getExtension(filename);
        switch (ext.toLowerCase()) {
            case 'jpg':
            case 'png':
                //etc
                return true;
        }
        return false;
    }
    $("#profile_img").change(function () {
        if (isImage($("#profile_img").val()) == false) {
            $("#profile_box").css("border-color", "red")
            $("#profileimg_error").html("รองรับไฟล์ประเภท jpg, pdf, png ขนาดไม่เกิน 5 MB เท่านั้น")
            $("#profile_img").val("")
            $('#img_profile').attr('src', "");
            $("#img_profile").hide()
        } else {
            if (this.files && this.files[0]) {
                if (this.files[0].size < 5242880) {
                    $("#img_profile").show()
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#img_profile').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]); // convert to base64 string
                    $("#profile_box").css("border-color", "")
                    $("#profileimg_error").html("")
                } else {
                    $("#profile_box").css("border-color", "red")
                    $("#profileimg_error").html("ขนาดรูปภาพใหญ่เกินไป (ไม่เกิน 5 MB)")
                    $("#profile_img").val("")
                    $('#img_profile').attr('src', "");
                    $("#img_profile").hide()
                }
            }
        }
    })
    $("#edit").click(function () {
        $("#option-btn").hide()
        $("#edit-option").css("display", "flex")
        $("#profile_img").prop("disabled", false)
    })
    $("#cancel-edit").click(function () {
        $("#option-btn").show()
        $("#edit-option").hide()
        $("#profile_img").prop("disabled", true)
        document.location.reload()
    })
})