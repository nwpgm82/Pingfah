$(document).ready(function () {
    $("#success_date").dateDropper({
        theme: "my-style",
        lang: "th",
        format: "d M Y",
        large: true,
        lock: "to",
        largeDefault: true,
        startFromMonday: false,
    })
    $("#success_date").change(function () {
        $("#success_date").css("border-color", "")
        $("#success_date").css("background-image", "")
        $("#success_date_error").html("")
    })

    $("#status").change(function () {
        if ($("#status").val() == "ซ่อมเสร็จแล้ว") {
            $("#success_status").show()
        } else {
            $("#success_status").hide()
        }
    })
    $("#income").keyup(function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
        if ($("#income").val() != "") {
            $("#income").css("border-color", "")
            $("#income_error").html("")
            if ($("#expenses").val() != "") {
                let profit = parseFloat($("#income").val()) - parseFloat($("#expenses").val())
                $("#profit").val(profit.toFixed(2))
            } else {
                $("#profit").val(0)
            }
        } else {
            $("#profit").val(0)
            $("#income").css("border-color", "red")
            $("#income_error").html("โปรดระบุรายได้จากการซ่อม")
        }
    })
    $("#expenses").keyup(function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
        if ($("#expenses").val() != "") {
            $("#expenses").css("border-color", "")
            $("#expenses_error").html("")
            if ($("#income").val() != "") {
                let profit = parseFloat($("#income").val()) - parseFloat($("#expenses").val())
                $("#profit").val(profit.toFixed(2))
            } else {
                $("#profit").val(0)
            }
        } else {
            $("#profit").val(0)
            $("#expenses").css("border-color", "red")
            $("#expenses_error").html("โปรดระบุรายจ่ายจากการซ่อม")
        }
    })
    $("button[type=submit]").click(function (event) {
        if ($("#status").val() == "ซ่อมเสร็จแล้ว") {
            if ($("#success_date").val() == "" || $("#income").val() == "" || $("#expenses").val() == "") {
                let inputs = $("input");
                inputs.each(function () {
                    if ($(this).val() == "") {
                        $(this).css("border-color", "red")
                        if ($(this).attr("id") == "success_date") {
                            $(this).css("background-image", "url('../../../img/tool/calendar-error.png')")
                            $("#success_date_error").html("โปรดระบุวันที่ซ่อมเสร็จ")
                        } else if ($(this).attr("id") == "income") {
                            $("#income_error").html("โปรดระบุรายได้จากการซ่อม")
                        } else if ($(this).attr("id") == "expenses") {
                            $("#expenses_error").html("โปรดระบุรายจ่ายจากการซ่อม")
                        }
                    }
                })
                event.preventDefault()
            } else {
                if (confirm("คุณต้องการยืนยันการแก้ไขใช่หรือไม่ ?")) {
                    $("form").submit()
                } else {
                    event.preventDefault()
                }
            }
        } else {
            if (confirm("คุณต้องการยืนยันการแก้ไขใช่หรือไม่ ?")) {
                $("form").submit()
            } else {
                event.preventDefault()
            }
        }
    })
})