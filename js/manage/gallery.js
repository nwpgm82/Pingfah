$(document).ready(function () {
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
    $("#submitForm").on("change", function () {
        var formData = new FormData(this)
        let file_size = $("#file")[0].files[0].size
        if (isImage($("#file").val()) != false) {
            if (file_size < 5242880) {
                $.ajax({
                    url: "function/addImage.php",
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function (response) {
                        document.location.reload()
                    }
                });
            } else {
                alert("ขนาดรูปภาพใหญ่เกินไป (ไม่เกิน 5 MB)")
                $("#file").val("")
            }
        }else{
            alert("รองรับไฟล์ประเภท jpg, png ขนาดไม่เกิน 5 MB เท่านั้น")
            $("#file").val("")
        }
    })
    $(document).on("click", ".del-btn", function (event) {
        if (confirm("คุณต้องการลบรูปภาพนี้ใช่หรือไม่ ?")) {
            let img_id = event.target.id
            let img_name = event.target.name
            $.ajax({
                url: `function/delImage.php`,
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
})

// function delImg(id, name) {
//     if (confirm("คุณต้องการลบรูปภาพนี้ใช่หรือไม่ ?")) {
//         location.href = `function/delImage.php?id=${id}&name=${name}`
//     }
// }