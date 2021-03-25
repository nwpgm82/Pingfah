var slideIndex1 = 1;
var slideIndex2 = 2;
showSlides1(slideIndex1);
showSlides2(slideIndex2);

function plusSlides1(n) {
    showSlides1(slideIndex1 += n);
}

function currentSlide1(n) {
    showSlides1(slideIndex1 = n);
}

function plusSlides2(n) {
    showSlides2(slideIndex2 += n);
}

function currentSlide2(n) {
    showSlides2(slideIndex2 = n);
}

function showSlides1(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides1");
    // let dots = document.getElementsByClassName("demo1");
    //   var captionText = document.getElementById("caption");
    if (n > slides.length) {
        slideIndex1 = 1
    }
    if (n < 1) {
        slideIndex1 = slides.length
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    // for (i = 0; i < dots.length; i++) {
    //     dots[i].className = dots[i].className.replace(" active", "");
    // }
    slides[slideIndex1 - 1].style.display = "block";
    // dots[slideIndex1 - 1].className += " active";
    //   captionText.innerHTML = dots[slideIndex-1].alt;
}

function showSlides2(m) {
    let j;
    let slides = document.getElementsByClassName("mySlides2");
    // let dots = document.getElementsByClassName("demo2");
    //   var captionText = document.getElementById("caption");
    if (m > slides.length) {
        slideIndex2 = 1
    }
    if (m < 1) {
        slideIndex2 = slides.length
    }
    for (j = 0; j < slides.length; j++) {
        slides[j].style.display = "none";
    }
    // for (j = 0; j < dots.length; j++) {
    //     dots[j].className = dots[j].className.replace(" active", "");
    // }
    slides[slideIndex2 - 1].style.display = "block";
    // dots[slideIndex2 - 1].className += " active";
    //   captionText.innerHTML = dots[slideIndex-1].alt;
}

$(document).ready(function(){
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
    $(document).on("click", "#edit-vat", function () {
        $("#edit-vat").hide()
        $("#vat-option").css("display","flex")
        $("#daily_vat").prop("disabled", false)
    })
    $(document).on("click", "#correct-vat", function () {
        if($("#daily_vat").val() != ""){
            $.ajax({
                url: `function/edit_vat.php`,
                type: 'post',
                data: {
                    vat: $("#daily_vat").val()
                },
                success: function () {
                    $('#vat-box').load(location.href + ' #vat-content');
                }
            });
        }
    })
    $(document).on("click", "#cancel-vat", function () {
        $('#vat-box').load(location.href + ' #vat-content');
    })
    $(document).on("keyup", "#daily_vat", function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
        if ($(this).val() == "") {
            $(this).css("border-color", "red")
            $("#daily_vat_error").html("โปรดระบุภาษีมูลค่าเพิ่ม(VAT)")
        } else {
            $(this).css("border-color", "")
            $("#daily_vat_error").html("")
        }
    })

    $(document).on("click", "#edit-prompt", function(){
        $("#edit-prompt").hide()
        $("#prompt-option").css("display","flex")
        $("#prompt_num").prop("disabled", false)
        $("#prompt_name").prop("disabled", false)
        $("#prompt_img").prop("disabled", false)
    })
    $(document).on("click", "#correct-prompt", function () {
        if($("#prompt_num").val() != "" && $("#prompt_name").val() != ""){
            let formdata = new FormData();
            let prompt_num = $("#prompt_num").val()
            let prompt_name = $("#prompt_name").val()
            let files = $("#prompt_img")[0].files[0]
            formdata.append("prompt_num", prompt_num)
            formdata.append("prompt_name", prompt_name)
            if(files){
                formdata.append("prompt_img", files)
            }
            $.ajax({
                url: `function/edit_prompt.php`,
                type: "POST",
                data: formdata,
                processData: false,
                contentType: false,
                success: function (e) {
                    $("#prompt-box").load(location.href + " #prompt-content")   
                }
            });
        }
    })
    $(document).on("click", "#cancel-prompt", function(){
        $("#prompt-box").load(location.href + " #prompt-content")
    })
    $(document).on("keyup", "#prompt_num", function(event){
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
        if ($(this).val() == "") {
            $(this).css("border-color", "red")
            $("#prompt_error").html("โปรดระบุเลขพร้อมเพย์")
        } else {
            $(this).css("border-color", "")
            $("#prompt_error").html("")
        }
    })
    $(document).on("keyup", "#prompt_name", function(){
        if ($(this).val() == "") {
            $(this).css("border-color", "red")
            $("#prompt_name_error").html("โปรดระบุชื่อเจ้าของพร้อมเพย์")
        } else {
            $(this).css("border-color", "")
            $("#prompt_name_error").html("")
        }
    })
    $(document).on("change", "#prompt_img", function(){
        if (isImage($("#prompt_img").val()) == false) {
            $(".img-box").css("border-color", "red")
            $("#img_error").html("รองรับไฟล์ประเภท jpg, png ขนาดไม่เกิน 5 MB เท่านั้น")
            $("#prompt_img").val("")
            $('#img_prompt').attr('src', "");
            $("#img_prompt").hide()
        } else {
            if (this.files && this.files[0]) {
                if (this.files[0].size < 5242880) {
                    $("#img_prompt").show()
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#img_prompt').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]); // convert to base64 string
                    $(".img-box").css("border-color", "")
                    $("#img_error").html("")
                } else {
                    $(".img-box").css("border-color", "red")
                    $("#img_error").html("ขนาดรูปภาพใหญ่เกินไป (ไม่เกิน 5 MB)")
                    $("#prompt_img").val("")
                    $('#img_prompt').attr('src', "");
                    $("#img_prompt").hide()
                }
            }
        }
    })
})