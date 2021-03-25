// let check_in = document.getElementById("check_in")
// let check_out = document.getElementById("check_out")
// let check_in_date = document.getElementById("check_in_date")
// let check_out_date = document.getElementById("check_out_date")
var url_string = window.location.href
var url = new URL(url_string)
var peoplePath = parseInt(url.searchParams.get("people"))

// function checkRoomLoad() {
//     check_in_date.innerHTML = formatDate(new Date(check_in.value))
//     check_out_date.innerHTML = formatDate(new Date(check_out.value))
// }
let slide_time = 5000;
var slideIndex1 = 0;
var slideIndex2 = 0;
if(document.getElementsByClassName("mySlides1").length > 0){
    showSlides11()
}

if(document.getElementsByClassName("mySlides2").length > 0){
   showSlides22() 
}


if (document.getElementById("air")) {
    showSlides1(slideIndex1);
    document.getElementById('row1').addEventListener('mousewheel', function (e) {
        this.scrollLeft -= (e.wheelDelta) * 4;
        e.preventDefault();
    }, false);
}
if (document.getElementById("fan")) {
    showSlides2(slideIndex2);
    document.getElementById('row2').addEventListener('mousewheel', function (e) {
        this.scrollLeft -= (e.wheelDelta) * 4;
        e.preventDefault();
    }, false);
}


function plusSlides1(n) {
    showSlides1(slideIndex1 += n);
    document.querySelector("#row1").scrollLeft = document.querySelector("#row1").querySelector(".active").offsetLeft
}

function currentSlide1(n) {
    showSlides1(slideIndex1 = n);
}

function plusSlides2(n) {
    showSlides2(slideIndex2 += n);
    document.querySelector("#row2").scrollLeft = document.querySelector("#row2").querySelector(".active").offsetLeft
}

function currentSlide2(n) {
    showSlides2(slideIndex2 = n);
}

function showSlides1(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides1");
    let dots = document.getElementsByClassName("demo1");
    //   var captionText = document.getElementById("caption");
    if (n > slides.length) {
        slideIndex1 = 1
    }
    if (n < 1) {
        slideIndex1 = slides.length
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex1 - 1].style.display = "block";
    dots[slideIndex1 - 1].className += " active";
    //   captionText.innerHTML = dots[slideIndex-1].alt;
}

function showSlides2(m) {
    let j;
    let slides = document.getElementsByClassName("mySlides2");
    let dots = document.getElementsByClassName("demo2");
    //   var captionText = document.getElementById("caption");
    if (m > slides.length) {
        slideIndex2 = 1
    }
    if (m < 1) {
        slideIndex2 = slides.length
    }
    for (j = 0; j < slides.length; j++) {
        slides[j].style.display = "none";
    }
    for (j = 0; j < dots.length; j++) {
        dots[j].className = dots[j].className.replace(" active", "");
    }
    slides[slideIndex2 - 1].style.display = "block";
    dots[slideIndex2 - 1].className += " active";
    //   captionText.innerHTML = dots[slideIndex-1].alt;
}

function showSlides11() {
    let i;
    let slides = document.getElementsByClassName("mySlides1");
    let dots = document.getElementsByClassName("demo1");
    for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
    }
    slideIndex1++;
    if (slideIndex1 > slides.length) {slideIndex1 = 1}    
    for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex1-1].style.display = "block";  
    dots[slideIndex1-1].className += " active";
    setTimeout(showSlides11, slide_time); // Change image every 2 seconds
  }

  function showSlides22() {
    let i;
    let slides = document.getElementsByClassName("mySlides2");
    let dots = document.getElementsByClassName("demo2");
    for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
    }
    slideIndex2++;
    if (slideIndex2 > slides.length) {slideIndex2 = 1}    
    for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex2-1].style.display = "block";  
    dots[slideIndex2-1].className += " active";
    setTimeout(showSlides22, slide_time); // Change image every 2 seconds
  }

function increase(num) {
    let people = parseInt(document.getElementById(`people${num}`).value, 10);
    let peoplemax = parseInt(document.getElementById(`people${num}`).getAttribute("max"))
    if (people > peoplemax || people == peoplemax) {
        document.getElementById(`people${num}`).value = peoplemax;
    } else {
        if (peoplePath != 0) {
            people++;
            peoplePath -= 1
            document.getElementById(`people${num}`).value = people;
        }
    }
}

function decrease(num) {
    let people = parseInt(document.getElementById(`people${num}`).value, 10);
    if (people > 0 || people != 0) {
        people--;
        peoplePath += 1
        document.getElementById(`people${num}`).value = people;
    }
}

$(document).ready(function () {
    let check_in = $("#check_in")
    let check_out = $("#check_out")
    let check_in_date = $("#check_in_date")
    let check_out_date = $("#check_out_date")
    let people = $("#people")

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
    check_in_date.html(formatDate(new Date(check_in.val())))
    check_out_date.html(formatDate(new Date(check_out.val())))
    $('.roundtrip-input').dateDropper({
        roundtrip: "my-trip",
        theme: "my-style",
        format: "Y-m-d",
        lang: "th",
        lock: "from",
        startFromMonday: false,
    });
    check_in_date.click(function () {
        check_in.dateDropper('show');
    })
    check_out_date.click(function () {
        check_out.dateDropper('show')
    })
    check_in.change(function () {
        console.log("check in :", $('#check_in').val())
        $('#check_in_date').html(formatDate(new Date($('#check_in').val())))
        if (new Date($("#check_in").val()) >= new Date($("#check_out").val())) {
            let nDate = new Date($("#check_in").val())
            nDate.setDate(nDate.getDate() + 1)
            let nDate_date = nDate.getDate()
            let nDate_month = nDate.getMonth() + 1
            if (nDate_date < 10) {
                nDate_date = "0" + nDate_date.toString()
            }
            if (nDate_month < 10) {
                nDate_month = "0" + nDate_month.toString()
            }
            $("#check_out").val(nDate.getFullYear() + "-" + nDate_month + "-" + nDate_date)
            $("#check_out_date").html(formatDate(new Date($("#check_out").val())))

        } else {
            $('#check_out_date').html(formatDate(new Date($('#check_out').val())))
        }
    })
    check_out.change(function () {
        if (new Date($("#check_in").val()) >= new Date($("#check_out").val())) {
            let nDate = new Date($("#check_in").val())
            nDate.setDate(nDate.getDate() + 1)
            let nDate_date = nDate.getDate()
            let nDate_month = nDate.getMonth() + 1
            if (nDate_date < 10) {
                nDate_date = "0" + nDate_date.toString()
            }
            if (nDate_month < 10) {
                nDate_month = "0" + nDate_month.toString()
            }
            $("#check_out").val(nDate.getFullYear() + "-" + nDate_month + "-" + nDate_date)
            $("#check_out_date").html(formatDate(new Date($("#check_out").val())))

        } else {
            $('#check_out_date').html(formatDate(new Date($('#check_out').val())))
        }
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
    $("#search").click(function () {
        if (people.val() != 0) {
            location.href = `checkRoom.php?check_in=${check_in.val()}&check_out=${check_out.val()}&people=${people.val()}`
        } else {
            people.css("border-color", "red")
        }
    })
    $("button[type=submit]").click(function (event) {
        let people_check = Math.ceil(parseInt($("#people").val()) / 2)
        let room_check = parseInt($("#people1").val()) + parseInt($("#people2").val())
        if (people_check > room_check) {
            alert("กรุณาเลือกห้องพักให้เพียงพอกับผู้พัก")
            event.preventDefault()
        }
    })
})