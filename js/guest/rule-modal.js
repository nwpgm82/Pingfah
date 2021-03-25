$(document).ready(function () {
    $("#submitForm").submit(function () {
        var formData = new FormData(this)
        $.ajax({
            url: "/Pingfah/pages/guest/acceptRule.php",
            type: "POST",
            cache: false,
            contentType: false, // you can also use multipart/form-data replace of false
            processData: false,
            data: formData,
            success: function (response) {
                // alert("Image uploaded Successfully");
                location.href = "index.php"
            }
        });
    })
})

function checked_btn(){
    let checkbox = document.getElementById("check")
    if(checkbox.checked == true){
        document.getElementById("accept-rule").disabled = false
    }else{
        document.getElementById("accept-rule").disabled = true
    }
}