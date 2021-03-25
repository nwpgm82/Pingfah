function navigation() {
    var first = document.getElementById("first")
    var second = document.getElementById("second")
    if (first.style.display == 'none') {
        first.style.display = 'block'
        second.style.display = 'none'
    } else if (second.style.display == 'none') {
        first.style.display = 'none'
        second.style.display = 'block'
    }
}

function formatDate(date) {
    var monthNames = [
        "ม.ค.", "ก.พ.", "มี.ค.",
        "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.",
        "ส.ค.", "ก.ย.", "ต.ค.",
        "พ.ค.", "ธ.ค."
    ];
    var day = date.getDate();
    var monthIndex = date.getMonth();
    var year = date.getFullYear();
    return day + ' ' + monthNames[monthIndex] + ' ' + year;
}

function formatDate2(inputDate) {
    var date = new Date(inputDate);
    if (!isNaN(date.getTime())) {
        // Months use 0 index.
        return date.getMonth() + 1 + '/' + date.getDate() + '/' + date.getFullYear();
    }
}

if($('#birthday').val() != ""){
    $('#birth_date').html(formatDate(new Date($('#birthday').val())))
}
if($('#birthday2').val() != ""){
    $('#birth_date2').html(formatDate(new Date($('#birthday2').val())))
}
$('#birthday').dateDropper({
    theme: "my-style",
    large: true,
    largeDefault: true,
    format: "Y-m-d",
    lang: "th",
    startFromMonday: false,
    defaultDate: formatDate2($('#birthday').val())
});

$('#birthday2').dateDropper({
    theme: "my-style",
    large: true,
    largeDefault: true,
    format: "Y-m-d",
    lang: "th",
    startFromMonday: false,
    defaultDate: formatDate2($('#birthday2').val())
});