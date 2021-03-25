$(document).ready(function () {
    let code = $("#code")
    let company = $("#package_company")
    let name = $("#package_name")
    let room = $("#package_room")
    let arrived = $("#package_arrived")
    arrived.dateDropper({
        theme: "my-style",
        lang: "th",
        format: "d M Y",
        lock: "to",
        large: true,
        largeDefault: true,
        startFromMonday: false,
    })
    code.keyup(function () {
        if (code.val() != "") {
            code.css("border-color", "")
            $("#code_error").html("")
        } else {
            code.css("border-color", "red")
            $("#code_error").html("โปรดระบุเลขพัสดุ")
        }
    })
    company.keyup(function () {
        if (company.val() != "") {
            company.css("border-color", "")
            $("#company_error").html("")
        } else {
            company.css("border-color", "red")
            $("#company_error").html("โปรดระบุชื่อบริษัท")
        }
    })
    name.keyup(function () {
        if (name.val() != "") {
            name.css("border-color", "")
            $("#name_error").html("")
            let value = name.val()
            $.post('room_ajax.php', {
                name: value,
            }, function (data) {
                room.empty()
                room.append("<option value=''>--</option>")
                room.append(data)
            });
            return false
        } else {
            room.empty()
            room.append("<option value=''>--</option>")
            name.css("border-color", "red")
            $("#name_error").html("โปรดระบุชื่อเจ้าของพัสดุ")
        }
    })
    room.change(function () {
        if (room.val() != "") {
            room.css("border-color", "")
            $("#room_error").html("")
        } else {
            room.css("border-color", "red")
            $("#room_error").html("โปรดระบุเลขห้อง")
        }
    })
    arrived.change(function () {
        if (arrived.val() != "") {
            arrived.css("border-color", "")
            arrived.css("background-image", "url('../../../img/tool/calendar.png')")
            $("#date_error").html("")
        } else {
            arrived.css("border-color", "red")
            arrived.css("background-image", "url('../../../img/tool/calendar-error.png')")
            $("#date_error").html("โปรดระบุวันที่พัสดุมาถึง")
        }
    })
    $("button[type=submit]").click(function (event) {
        if (code.val() == "" || company.val() == "" || name.val() == "" || room.val() == "") {
            let inputs = $("input")
            inputs.each(function () {
                if ($(this).val() == "") {
                    $(this).css("border-color", "red")
                    if ($(this).attr("id") == "code") {
                        $("#code_error").html("โปรดระบุเลขพัสดุ")
                    } else if ($(this).attr("id") == "package_company") {
                        $("#company_error").html("โปรดระบุชื่อบริษัท")
                    } else if ($(this).attr("id") == "package_name") {
                        $("#name_error").html("โปรดระบุชื่อเจ้าของพัสดุ")
                    } else if ($(this).attr("id") == "package_arrived") {
                        $("#date_error").html("โปรดระบุวันที่พัสดุมาถึง")
                        arrived.css("background-image", "url('../../../img/tool/calendar-error.png')")
                    }
                }
            })
            if (room.val() == "") {
                room.css("border-color", "red")
                $("#room_error").html("โปรดระบุเลขห้อง")
            }
            if (code.val() != "") {
                let letter = /^[0-9a-zA-Z]+$/;
                if (!$("#code").val().match(letter)) {
                    $("#code").css("border-color", "red")
                    $("#code_error").html("ระบุข้อความ a-z, A-Z หรือ 0-9 ได้เท่านั้น")
                }
            }
            event.preventDefault()
        }
    })
})