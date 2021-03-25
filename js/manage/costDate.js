$(document).ready(function () {
    let from = $("#date_from")
    let to = $("#date_to")

    function BasicDate(date) {
        let year = date.getFullYear()
        let month = date.getMonth() + 1
        if (month < 10) {
            month = "0" + month.toString()
        }
        return year + "-" + month
    }

    function BasicDate2(date) {
        let year = date.getFullYear()
        let month = date.getMonth() + 2
        if (month < 10) {
            month = "0" + month.toString()
        }
        return year + "-" + month
    }

    function formatDate2(inputDate) {
        var date = new Date(inputDate);
        if (!isNaN(date.getTime())) {
            // Months use 0 index.
            return date.getMonth() + 1 + '/' + date.getDate() + '/' + date.getFullYear();
        }
    }

    from.dateDropper({
        // roundtrip: true,
        theme: "my-style",
        lang: "th",
        format: "M Y",
        lock: "to",
        hideDay: true,
        hideMonth: false,
        hideYear: false,
        startFromMonday: false,
    });

    to.dateDropper({
        // roundtrip: true,
        theme: "my-style",
        lang: "th",
        format: "M Y",
        lock: "to",
        hideDay: true,
        hideMonth: false,
        hideYear: false,
        startFromMonday: false,
    });

    from.change(function () {
        const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
        const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        let from_toeng = from.val().split(" ")
        from_toeng[0] = search.findIndex(el => el === from_toeng[0])
        from_toeng[0] = replace[from_toeng[0]]
        from_toeng = from_toeng.join(" ")
        from_toeng = BasicDate(new Date(from_toeng))
        to.dateDropper('set', {
            minDate: formatDate2(from_toeng)
        });
        from.css("border-color", "")
        from.css("background-image", "")
        $("#from_error").html("")
    })

    to.change(function () {
        const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
        const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        let to_toeng = to.val().split(" ")
        to_toeng[0] = search.findIndex(el => el === to_toeng[0])
        to_toeng[0] = replace[to_toeng[0]]
        to_toeng = to_toeng.join(" ")
        to_toeng = BasicDate2(new Date(to_toeng))
        from.dateDropper('set', {
            maxDate: formatDate2(to_toeng)
        });
        to.css("border-color", "")
        to.css("background-image", "")
        $("#to_error").html("")
    })

    $("#searchDate").click(function () {
        if (from.val() == "" || to.val() == "") {
            if (from.val() == "") {
                from.css("border-color", "red")
                from.css("background-image", "url('../../../img/tool/calendar-error.png')")
                $("#from_error").html("โปรดระบุวันที่ต้องการค้นหา")
            }
            if (to.val() == "") {
                to.css("border-color", "red")
                to.css("background-image", "url('../../../img/tool/calendar-error.png')")
                $("#to_error").html("โปรดระบุวันที่ต้องการค้นหา")
            }
        } else {
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let from_toeng = from.val().split(" ")
            from_toeng[0] = search.findIndex(el => el === from_toeng[0])
            from_toeng[0] = replace[from_toeng[0]]
            from_toeng = from_toeng.join(" ")
            from_toeng = BasicDate(new Date(from_toeng))
            let to_toeng = to.val().split(" ")
            to_toeng[0] = search.findIndex(el => el === to_toeng[0])
            to_toeng[0] = replace[to_toeng[0]]
            to_toeng = to_toeng.join(" ")
            to_toeng = BasicDate(new Date(to_toeng))
            location.href = `index.php?from=${from_toeng}&to=${to_toeng}`
        }
    })
    $("#all").click(function () {
        if (from.val() != "" && to.val() != "") {
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let from_toeng = from.val().split(" ")
            from_toeng[0] = search.findIndex(el => el === from_toeng[0])
            from_toeng[0] = replace[from_toeng[0]]
            from_toeng = from_toeng.join(" ")
            from_toeng = BasicDate(new Date(from_toeng))
            let to_toeng = to.val().split(" ")
            to_toeng[0] = search.findIndex(el => el === to_toeng[0])
            to_toeng[0] = replace[to_toeng[0]]
            to_toeng = to_toeng.join(" ")
            to_toeng = BasicDate(new Date(to_toeng))
            location.href = `index.php?from=${from_toeng}&to=${to_toeng}`
        } else {
            location.href = "index.php"
        }
    })
    $("#success").click(function () {
        if (from.val() != "" && to.val() != "") {
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let from_toeng = from.val().split(" ")
            from_toeng[0] = search.findIndex(el => el === from_toeng[0])
            from_toeng[0] = replace[from_toeng[0]]
            from_toeng = from_toeng.join(" ")
            from_toeng = BasicDate(new Date(from_toeng))
            let to_toeng = to.val().split(" ")
            to_toeng[0] = search.findIndex(el => el === to_toeng[0])
            to_toeng[0] = replace[to_toeng[0]]
            to_toeng = to_toeng.join(" ")
            to_toeng = BasicDate(new Date(to_toeng))
            location.href = `index.php?from=${from_toeng}&to=${to_toeng}&status=success`
        } else {
            location.href = "index.php?status=success"
        }
    })
    $("#pending").click(function () {
        if (from.val() != "" && to.val() != "") {
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let from_toeng = from.val().split(" ")
            from_toeng[0] = search.findIndex(el => el === from_toeng[0])
            from_toeng[0] = replace[from_toeng[0]]
            from_toeng = from_toeng.join(" ")
            from_toeng = BasicDate(new Date(from_toeng))
            let to_toeng = to.val().split(" ")
            to_toeng[0] = search.findIndex(el => el === to_toeng[0])
            to_toeng[0] = replace[to_toeng[0]]
            to_toeng = to_toeng.join(" ")
            to_toeng = BasicDate(new Date(to_toeng))
            location.href = `index.php?from=${from_toeng}&to=${to_toeng}&status=pending`
        } else {
            location.href = "index.php?status=pending"
        }
    })
    $("#unsuccess").click(function () {
        if (from.val() != "" && to.val() != "") {
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let from_toeng = from.val().split(" ")
            from_toeng[0] = search.findIndex(el => el === from_toeng[0])
            from_toeng[0] = replace[from_toeng[0]]
            from_toeng = from_toeng.join(" ")
            from_toeng = BasicDate(new Date(from_toeng))
            let to_toeng = to.val().split(" ")
            to_toeng[0] = search.findIndex(el => el === to_toeng[0])
            to_toeng[0] = replace[to_toeng[0]]
            to_toeng = to_toeng.join(" ")
            to_toeng = BasicDate(new Date(to_toeng))
            location.href = `index.php?from=${from_toeng}&to=${to_toeng}&status=unsuccess`
        } else {
            location.href = "index.php?status=unsuccess"
        }
    })
    $(".del-btn").click(function (event) {
        if (confirm("คุณต้องการลบรายการชำระเงินนี้ใช่หรือไม่ ?")) {
            location.href = `function/delcostData.php?cost_id=${event.target.id}`
        }
    })
})