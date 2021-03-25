$(document).ready(function () {
    let check_in = $("#check_in")
    let check_out = $("#check_out")
    let check_in_date = $("#check_in_date")
    let check_out_date = $("#check_out_date")
    let people = $("#people")
    let banner = $("#banner")
    let today_monthNames = [
        "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
    ];
    let today = new Date()
    let today_day = today.getDate()
    let today_month = today.getMonth() + 1
    let today_year = today.getFullYear()
    let tomorrow_day = today.getDate() + 1
    let tomorrow_month = today.getMonth() + 1
    let tomorrow_year = today.getFullYear()
    if (today_day < 10) {
        today_day = '0' + today_day.toString()
    }
    if (today_month < 10) {
        today_month = '0' + today_month.toString()
    }
    if (tomorrow_day < 10) {
        tomorrow_day = '0' + tomorrow_day.toString()
    }
    if (tomorrow_month < 10) {
        tomorrow_month = '0' + tomorrow_month.toString()
    }
    let current_dayShow = today_day + ' ' + today_monthNames[today_month - 1] + ' ' + today_year
    let next_dayShow = tomorrow_day + ' ' + today_monthNames[tomorrow_month - 1] + ' ' + tomorrow_year
    let current_day = today_year + '-' + today_month + '-' + today_day
    let next_day = tomorrow_year + '-' + tomorrow_month + '-' + tomorrow_day
    check_in.val(current_day)
    check_out.val(next_day)
    check_in_date.html(current_dayShow)
    check_out_date.html(next_dayShow)
    //////////////////////////
    $(document).on('readystatechange', readyStateChanged); 
    function readyStateChanged() {
        console.log(document.readyState)
        if (document.readyState !== "complete") { 
            console.log("xx")
            $("body").css("visibility","hidden")
            $("#l").show()
        } else { 
            console.log("yy")
            $("#l").fadeOut(500)
            $("body").css("visibility","visible")
            banner.css("transform", "scale(1)")
            banner.css("opacity", 1)
        } 
    }
    ///////////////////////// 
    function formatDate(date) {
        var monthNames = [
            "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
        ];
        var day = date.getDate();
        var monthIndex = date.getMonth();
        var year = date.getFullYear();
        if (day < 10) {
            day = "0" + day
        }
        return day + ' ' + monthNames[monthIndex] + ' ' + year;
    }
    function formatDate2(inputDate) {
        var date = new Date(inputDate);
        if (!isNaN(date.getTime())) {
            // Months use 0 index.
            return date.getMonth() + 1 + '/' + date.getDate() + '/' + date.getFullYear();
        }
    }
    $('.roundtrip-input').dateDropper({
        roundtrip: "my-trip",
        theme: "my-style",
        format: "Y-m-d",
        lang: "th",
        lock: "from",
        startFromMonday: false,
    });

    $(window).scroll(function () {
        if (window.pageYOffset > 180) {
            $(".clickTotop").show()
        } else {
            $(".clickTotop").hide()
        }
    })
    $(".clickTotop").click(function () {
        $("html, body").animate({
            scrollTop: 0,
        }, 1000)
    })
    check_in_date.click(function () {
        check_in.dateDropper('show');
    })
    check_out_date.click(function () {
        check_out.dateDropper('show')
    })
    check_in.change(function () {
        check_in_date.html(formatDate(new Date(check_in.val())))
        if (new Date(check_in.val()) >= new Date(check_out.val())) {
            let nDate = new Date(check_in.val())
            nDate.setDate(nDate.getDate() + 1)
            let nDate_date = nDate.getDate()
            let nDate_month = nDate.getMonth() + 1
            if (nDate_date < 10) {
                nDate_date = "0" + nDate_date.toString()
            }
            if (nDate_month < 10) {
                nDate_month = "0" + nDate_month.toString()
            }
            check_out.val(nDate.getFullYear() + "-" + nDate_month + "-" + nDate_date)
            check_out_date.html(formatDate(new Date(check_out.val())))
        } else {
            check_out_date.html(formatDate(new Date(check_out.val())))
        }
        // alert(formatDate2(check_in.val()))
        
        // let date1 = new Date(formatDate2(check_in.val()))
        // let date2 = new Date(formatDate2(check_out.val()))
        // let Difference_In_Time = date2.getTime() - date1.getTime();
        // let Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);  
        // alert(Difference_In_Days)
    })
    check_out.change(function () {
        if (new Date(check_in.val()) >= new Date(check_out.val())) {
            let nDate = new Date(check_in.val())
            nDate.setDate(nDate.getDate() + 1)
            let nDate_date = nDate.getDate()
            let nDate_month = nDate.getMonth() + 1
            if (nDate_date < 10) {
                nDate_date = "0" + nDate_date.toString()
            }
            if (nDate_month < 10) {
                nDate_month = "0" + nDate_month.toString()
            }
            check_out.val(nDate.getFullYear() + "-" + nDate_month + "-" + nDate_date)
            check_out_date.html(formatDate(new Date(check_out.val())))

        } else {
            check_out_date.html(formatDate(new Date(check_out.val())))
        }
        // alert(formatDate2(check_out.val()))
        let date1 = new Date(formatDate2(check_in.val()))
        let date2 = new Date(formatDate2(check_out.val()))
        let Difference_In_Time = date2.getTime() - date1.getTime();
        let Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);  
        $("#summary").html(`(${Difference_In_Days} คืน)`)
    })
    people.keyup(function (event) {
        if (event.which !== 8 && event.which !== 0 && event.which < 48 || event.which > 57) {
            $(this).val(function (index, value) {
                return value.replace(/\D/g, "");
            });
        }
        if (people.val() <= 0) {
            people.css("border-color", "red")
        } else {
            people.css("border-color", "")
        }
    })
    $("#checkRoom").click(function () {
        if (people.val() != 0) {
            window.open(`pages/checkRoom.php?check_in=${check_in.val()}&check_out=${check_out.val()}&people=${people.val()}`, "_blank")
        } else {
            people.css("border-color", "red")
        }
    })
})