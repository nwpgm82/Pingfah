$(document).ready(function () {
    (function ($) {
        $.fn.inputFilter = function (inputFilter) {
            return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function () {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value
                    this.oldSelectionStart = this.selectionStart
                    this.oldSelectionEnd = this.selectionEnd
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    this.value = ""
                }
            })
        }
    }(jQuery));
    function BasicDate(date) {
        let year = date.getFullYear()
        let month = date.getMonth() + 1
        let day = date.getDate()
        if (day < 10) {
            day = "0" + day.toString()
        }
        if (month < 10) {
            month = "0" + month.toString()
        }
        return year + "-" + month + "-" + day
    }
    $("#addRoom").click(function () {
        if(window.matchMedia('(max-width: 767px)').matches){
            $("#add2").fadeIn(400)
        }else{
           $("#add").toggle() 
        }
        
    })
    $(".bg").click(function(){
        $("#add2").fadeOut(400)
    })
    $("#room_id").inputFilter(function (value) {
        return /^\d*$/.test(value); // Allow digits only, using a RegExp
    });
    $("#room_id").keyup(function () {
        if ($("#room_id").val().length >= 1) {
            $("#room_id_check").html("")
            $("#room_id").css("border-color", "")
        } else {
            $("#room_id_check").html("กรุณากรอกเลขห้อง")
            $("#room_id").css("border-color", "red")
            $("input#room_id").addClass("input_placeholder_error")
        }
    })
    $("button[type=submit]").click(function (event) {
        if ($("#room_id").val().length < 1) {
            $("#room_id_check").html("กรุณากรอกเลขห้อง")
            $("#room_id").css("border-color", "red")
            $("input#room_id").addClass("input_placeholder_error")
            event.preventDefault()
        }
    })
    $("#all").click(function () {
        location.href = "index.php"
    })
    $("#avai_all").click(function () {
        location.href = "index.php?Status=avai_all"
    })
    $("#unavai_all").click(function () {
        location.href = "index.php?Status=unavai_all"
    })
    $("#avai_daily").click(function () {
        location.href = "index.php?Status=avai_daily"
    })
    $("#unavai_daily").click(function () {
        location.href = "index.php?Status=unavai_daily"
    })
    $("#avai_month").click(function () {
        location.href = "index.php?Status=avai_month"
    })
    $("#unavai_month").click(function () {
        location.href = "index.php?Status=unavai_month"
    })
    $(".del-btn").click(function (event) {
        let room_id = event.target.id
        if (confirm(`คุณต้องการลบห้อง ${room_id} ใช่หรือไม่ ? `)) {
            location.href = `function/delRoom.php?ID=${room_id}`
        }
    })
    $("#daily_search").click(function () {
        let date_from = $("#date_from").val()
        let date_to = $("#date_to").val()
        let people = $("#people").val()
        if (date_from != "" && date_to != "") {
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            date_from = date_from.split(" ")
            date_from[1] = search.findIndex(el => el === date_from[1])
            date_from[1] = replace[date_from[1]]
            date_from = date_from.join(" ")
            date_from = BasicDate(new Date(date_from))
            date_to = date_to.split(" ")
            date_to[1] = search.findIndex(el => el === date_to[1])
            date_to[1] = replace[date_to[1]]
            date_to = date_to.join(" ")
            date_to = BasicDate(new Date(date_to))
            location.href = `index.php?from=${date_from}&to=${date_to}&people=${people}&style=daily`
        }
    })
    $("#month_search").click(function () {
        let date_from = $("#date_from").val()
        let date_to = $("#date_to").val()
        let people = $("#people").val()
        if (date_from != "" && date_to != "") {
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            date_from = date_from.split(" ")
            date_from[1] = search.findIndex(el => el === date_from[1])
            date_from[1] = replace[date_from[1]]
            date_from = date_from.join(" ")
            date_from = BasicDate(new Date(date_from))
            date_to = date_to.split(" ")
            date_to[1] = search.findIndex(el => el === date_to[1])
            date_to[1] = replace[date_to[1]]
            date_to = date_to.join(" ")
            date_to = BasicDate(new Date(date_to))
            location.href = `index.php?from=${date_from}&to=${date_to}&people=${people}&style=month`
        }
    })
    $(".roundtrip-input").dateDropper({
        roundtrip: "my-trip",
        theme: "my-style",
        format: "d M Y",
        lang: "th",
        lock: "from",
        startFromMonday: false,
    })

    // function formatDate(date) {
    //     var monthNames = [
    //         "ม.ค.", "ก.พ.", "มี.ค.",
    //         "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.",
    //         "ส.ค.", "ก.ย.", "ต.ค.",
    //         "พ.ค.", "ธ.ค."
    //     ];
    //     var day = date.getDate();
    //     var monthIndex = date.getMonth();
    //     var year = date.getFullYear();
    //     return day + ' ' + monthNames[monthIndex] + ' ' + year;
    // }
    // if ($("#date_from").val() != "" && $("#date_to").val() != "") {
    //     const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
    //     const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    //     let date_from = $("#date_from").val()
    //     let date_to = $("#date_to").val()
    //     date_from = date_from.split(" ")
    //     date_from[1] = search.findIndex(el => el === date_from[1])
    //     date_from[1] = replace[date_from[1]]
    //     date_from = date_from.join(" ")
    //     date_from = BasicDate(new Date(date_from))
    //     date_to = date_to.split(" ")
    //     date_to[1] = search.findIndex(el => el === date_to[1])
    //     date_to[1] = replace[date_to[1]]
    //     date_to = date_to.join(" ")
    //     date_to = BasicDate(new Date(date_to))
    //     $("#date_from").val(date_from)
    //     $("#date_to").val(date_to)
    // } else {
    //     $("#people").val(1)
    // }
    $("#date_from").change(function () {
        $("#date_from").css("border-color","")
        $("#date_from").css("background-image", "url('../../../img/tool/calendar.png')")
        $("#error-text").html("")
        // $('#from_date').html(formatDate(new Date($('#date_from').val())))
        // if (new Date($("#date_from").val()) >= new Date($("#date_to").val())) {
        //     let nDate = new Date($("#date_from").val())
        //     nDate.setDate(nDate.getDate() + 1)
        //     let nDate_date = nDate.getDate()
        //     let nDate_month = nDate.getMonth() + 1
        //     if (nDate_date < 10) {
        //         nDate_date = "0" + nDate_date.toString()
        //     }
        //     if (nDate_month < 10) {
        //         nDate_month = "0" + nDate_month.toString()
        //     }
        //     $("#date_to").val(nDate.getFullYear() + "-" + nDate_month + "-" + nDate_date)
        //     $("#to_date").html(formatDate(new Date($("#date_to").val())))
        // } else {
        //     $('#to_date').html(formatDate(new Date($('#date_to').val())))
        // }
    })
    $('#date_to').change(function () {
        $("#date_to").css("border-color","")
        $("#date_to").css("background-image", "url('../../../img/tool/calendar.png')")
        $("#error-text").html("")
        // if (new Date($("#date_from").val()) >= new Date($("#date_to").val())) {
        //     let nDate = new Date($("#date_from").val())
        //     nDate.setDate(nDate.getDate() + 1)
        //     let nDate_date = nDate.getDate()
        //     let nDate_month = nDate.getMonth() + 1
        //     if (nDate_date < 10) {
        //         nDate_date = "0" + nDate_date.toString()
        //     }
        //     if (nDate_month < 10) {
        //         nDate_month = "0" + nDate_month.toString()
        //     }
        //     $("#date_to").val(nDate.getFullYear() + "-" + nDate_month + "-" + nDate_date)
        //     $("#to_date").html(formatDate(new Date($("#date_to").val())))
        // } else {
        //     $('#to_date').html(formatDate(new Date($('#date_to').val())))
        // }
    })
    $("#people").keyup(function(event){
        if (event.which !== 8 && event.which !== 0 && event.which < 48 || event.which > 57) {
            $(this).val(function (index, value) {
                return value.replace(/\D/g, "");
            });
        }
        if($("#people").val() <= 0){
            $("#people").css("border-color","red")
            $("#people").css("color","red")
            $("#error-number").html("โปรดระบุจำนวน")
        }else{
            $("#people").css("border-color","")
            $("#people").css("color","")
            $("#error-number").html("")
        }
    })
    $("#search_room").click(function () {
        let date_from = $("#date_from").val()
        let date_to = $("#date_to").val()
        let people = $("#people").val()
        if (date_from == "" || date_to == "" || people == 0) {
            if (date_from == "" && date_to == "") {
                $(".roundtrip-input").css("border-color", "red")
                $(".roundtrip-input").css("background-image", "url('../../../img/tool/calendar-error.png')")
                $("#error-text").html("โปรดระบุวันที่ต้องการค้นหา")
            }
            if(people == 0){
                $("#people").css("border-color","red")
                $("#people").css("color","red")
            }
        } else {
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            date_from = date_from.split(" ")
            date_from[1] = search.findIndex(el => el === date_from[1])
            date_from[1] = replace[date_from[1]]
            date_from = date_from.join(" ")
            date_from = BasicDate(new Date(date_from))
            date_to = date_to.split(" ")
            date_to[1] = search.findIndex(el => el === date_to[1])
            date_to[1] = replace[date_to[1]]
            date_to = date_to.join(" ")
            date_to = BasicDate(new Date(date_to))
            location.href = `index.php?from=${date_from}&to=${date_to}&people=${people}&style=daily`
        }

    })
})