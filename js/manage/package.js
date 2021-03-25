$(document).ready(function () {
    let from = $("#date_from")
    let to = $("#date_to")
    let code = $("#code")
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
    $('.roundtrip-input').dateDropper({
        roundtrip: true,
        theme: "my-style",
        lang: "th",
        format: "d M Y",
        lock: "to",
        startFromMonday: false,
    });

    $("#searchDate").click(function () {
        if (from.val() == "" || to.val() == "") {
            from.css("border-color", "red")
            from.css("background-image", "url('../../../img/tool/calendar-error.png')")
            to.css("border-color", "red")
            to.css("background-image", "url('../../../img/tool/calendar-error.png')")
            $("#from_error").html("โปรดระบุวันที่ต้องการค้นหา")
            $("#to_error").html("โปรดระบุวันที่ต้องการค้นหา")
        } else {
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let from_toeng = from.val().split(" ")
            from_toeng[1] = search.findIndex(el => el === from_toeng[1])
            from_toeng[1] = replace[from_toeng[1]]
            from_toeng = from_toeng.join(" ")
            from_toeng = BasicDate(new Date(from_toeng))
            let to_toeng = to.val().split(" ")
            to_toeng[1] = search.findIndex(el => el === to_toeng[1])
            to_toeng[1] = replace[to_toeng[1]]
            to_toeng = to_toeng.join(" ")
            to_toeng = BasicDate(new Date(to_toeng))
            location.href = `index.php?from=${from_toeng}&to=${to_toeng}`
        }
    })
    $("#searchCode").click(function(){
        if(code.val() == ""){
            code.css("border-color","red")
            $("#code_error").html("โปรดระบุเลขพัสดุที่ต้องการค้นหา")
        }else{
            location.href = `index.php?code=${code.val()}`
        }
    })
    from.change(function () {
        from.css("border-color", "")
        from.css("background-image", "")
        to.css("border-color", "")
        to.css("background-image", "")
    })

    to.change(function () {
        from.css("border-color", "")
        from.css("background-image", "")
        to.css("border-color", "")
        to.css("background-image", "")
    })
    code.keyup(function(){
        if(code.val() == ""){
            code.css("border-color","red")
            $("#code_error").html("โปรดระบุเลขพัสดุที่ต้องการค้นหา")
        }else{
            code.css("border-color","")
            $("#code_error").html("")
        }
    })
    $("#all").click(function(){
        if(from.val() != "" && to.val() != ""){
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let from_toeng = from.val().split(" ")
            from_toeng[1] = search.findIndex(el => el === from_toeng[1])
            from_toeng[1] = replace[from_toeng[1]]
            from_toeng = from_toeng.join(" ")
            from_toeng = BasicDate(new Date(from_toeng))
            let to_toeng = to.val().split(" ")
            to_toeng[1] = search.findIndex(el => el === to_toeng[1])
            to_toeng[1] = replace[to_toeng[1]]
            to_toeng = to_toeng.join(" ")
            to_toeng = BasicDate(new Date(to_toeng))
            location.href = `index.php?from=${from_toeng}&to=${to_toeng}`
        }else{
            location.href = "index.php"
        }
    })
    $("#unsuccess").click(function(){
        if(from.val() != "" && to.val() != ""){
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let from_toeng = from.val().split(" ")
            from_toeng[1] = search.findIndex(el => el === from_toeng[1])
            from_toeng[1] = replace[from_toeng[1]]
            from_toeng = from_toeng.join(" ")
            from_toeng = BasicDate(new Date(from_toeng))
            let to_toeng = to.val().split(" ")
            to_toeng[1] = search.findIndex(el => el === to_toeng[1])
            to_toeng[1] = replace[to_toeng[1]]
            to_toeng = to_toeng.join(" ")
            to_toeng = BasicDate(new Date(to_toeng))
            location.href = `index.php?from=${from_toeng}&to=${to_toeng}&status=unsuccess`
        }else{
            location.href = "index.php?status=unsuccess"
        }
    })
    $("#success").click(function(){
        if(from.val() != "" && to.val() != ""){
            const search = ["", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
            const replace = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            let from_toeng = from.val().split(" ")
            from_toeng[1] = search.findIndex(el => el === from_toeng[1])
            from_toeng[1] = replace[from_toeng[1]]
            from_toeng = from_toeng.join(" ")
            from_toeng = BasicDate(new Date(from_toeng))
            let to_toeng = to.val().split(" ")
            to_toeng[1] = search.findIndex(el => el === to_toeng[1])
            to_toeng[1] = replace[to_toeng[1]]
            to_toeng = to_toeng.join(" ")
            to_toeng = BasicDate(new Date(to_toeng))
            location.href = `index.php?from=${from_toeng}&to=${to_toeng}&status=success`
        }else{
            location.href = "index.php?status=success"
        }
    })
    $(".del-btn").click(function(event){
        if (confirm('คุณต้องการลบรายการแจ้งซ่อมนี้ใช่หรือไม่ ?')) {
            location.href = `function/delPackage.php?package_id=${event.target.id}`
        }
    })
})