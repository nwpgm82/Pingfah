// $(document).ready(function () {
    let today = new Date()
    let today_day = today.getDate()
    let today_month = today.getMonth() + 1
    let today_year = today.getFullYear()
    if (today_day < 10) {
        today_day = '0' + today_day.toString()
    }
    if (today_month < 10) {
        today_month = '0' + today_month.toString()
    }
    let current_day = today_year + '-' + today_month
    $("#cost-month").val(current_day)
    $("#month-detail").html(formatDate(new Date(current_day)))
    $('#cost-month').dateDropper({
        format: "Y-m",
        theme: "my-style",
        lang: "th",
        lock: "to",
        hideDay: true,
        hideMonth: false,
        hideYear: false
    })
    $("#search-cost-month").change(function () {
        $("#month-detail").html(formatDate(new Date($('#cost-month').val())))
        var value = $('#cost-month').val()
        var name = $('#cost-month').attr('name')
        $.post('index-query.php', {
            value: value,
            searchcost: name
        }, function (data) {
            // $("#search_results").html(data);
            if (data != 0) {
                $("#total_cost").html(addCommas(data) + " บาท")
            } else {
                $("#total_cost").html(0 + " บาท")
            }
        });
        return false
    })

    function formatDate(date) {
        var monthNames = [
            "ม.ค.", "ก.พ.", "มี.ค.",
            "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.",
            "ส.ค.", "ก.ย.", "ต.ค.",
            "พ.ค.", "ธ.ค."
        ];
        var monthIndex = date.getMonth();
        var year = date.getFullYear();
        return monthNames[monthIndex] + ' ' + year;
    }

    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
// })