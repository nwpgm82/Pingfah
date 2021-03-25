function preview_image(event,pic) 
{
 var reader = new FileReader();
 reader.onload = function()
 {
  var output = document.getElementById(`output_image${pic}`);
  output.src = reader.result;
 }
 reader.readAsDataURL(event.target.files[0]);
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


$(document).ready(function(){
    $('#birthday').dateDropper({
        theme: "my-style",
        large: true,
        largeDefault: true,
        format: "Y-m-d",
        lang: "th",
        startFromMonday: false,
    });

    $('#birthday').change(function(){
        $('#birth_date').html(formatDate(new Date($('#birthday').val())))
    })
})