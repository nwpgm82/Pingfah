$(document).ready(function () {
    let url_string = window.location.href
    let url = new URL(url_string);
    let id = url.searchParams.get("ID");
    let total_damage = []

    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
    $("#confirm_quit").click(function (event) {
        let err_count = 0
        let inputs = $("input:not(#damage_topic, #d_price)")
        inputs.each(function () {
            if ($(this).val() == "") {
                err_count = err_count + 1
                $(this).css("border-color", "red")
            }
        })
        if (err_count == 0) {
            if (confirm("คุณต้องการยืนยันการแจ้งออกใช่หรือไม่ ?")) {
                let div = $("#damage-list p")
                div.each(function () {
                    let get_topic = $(this).children("label").html()
                    let get_price = +$(this).attr("name")
                    total_damage.push({
                        topic: get_topic,
                        price: get_price
                    })
                    // console.log($(this).children("label").html());
                })
                console.log(total_damage);
                let total_damage_JSON = JSON.stringify(total_damage)
                $.post(`function/action.php?ID=${id}`, {
                    quit: $("#confirm_quit").attr("name"),
                    cable_charge: $("#cable_price").html(),
                    water_price: $("#water_price").html(),
                    elec_price: $("#elec_price").html(),
                    damage_price: $("#damage_price").html(),
                    deposit_after: $("#b_deposit").html(),
                    total_price: $("#b_total").html(),
                    data: total_damage_JSON
                }, function (response) {
                    // alert(response)
                    alert("เช็คเอาท์เรียบร้อยแล้ว");
                    // console.log(response)
                    location.href = `quit_success.php?member_id=${response}`
                });
            } else {
                event.preventDefault()
            }
        } else {
            event.preventDefault()
        }
    })
    $("#checkbox").click(function () {
        if ($(this).prop("checked") == true) {
            $("#confirm_quit").prop("disabled", false)
        } else {
            $("#confirm_quit").prop("disabled", true)
        }
    })
    $(".plus-btn").click(function () {
        $("#add_form").toggle()
    })
    $("#damage_topic").keyup(function() {
        if ($(this).val() != "") {
            $(this).css("border-color", "")
        } else {
            $(this).css("border-color", "red")
        }
    })
    $("#d_price").keyup(function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
        if ($(this).val() != "") {
            $(this).css("border-color", "")
        } else {
            $(this).css("border-color", "red")
        }
    })
    $("#addDamage_form").click(function () {
        if ($("#damage_topic").val() != "" && $("#d_price") != "") {
            let topic = $("#damage_topic")
            let d_price = $("#d_price")
            $("#damage-list").append(`<p name="${d_price.val()}">- <label>${topic.val()}</label> (${formatNumber(d_price.val())} บาท) <button class="del_damage">x</button></p>`)
            $("#damage_unit").html(parseInt($("#damage_unit").html()) + 1)
            $("#damage_price").html(parseFloat($("#damage_price").html()) + parseFloat(d_price.val()))
            total_price.html(parseFloat(cable.html()) + parseFloat(water.html()) + parseFloat(elec.html()) + parseFloat(damage.html()))
            $("#b_deposit").html(parseFloat(deposit.html()) - parseFloat(total_price.html()))
            if (parseFloat($("#b_deposit").html()) <= 0) {
                $("#b_total").html(Math.abs(parseFloat($("#b_deposit").html())))
                $("#b_deposit").html(0)
            } else {
                $("#b_total").html(0)
            }
            topic.val("")
            d_price.val("")
            $("#add_form").toggle()
        }else{
            if($("#damage_topic").val() == ""){
                $("#damage_topic").css("border-color", "red")
            }
            if($("#d_price") == ""){
                $("#d_price").css("border-color", "red")
            }
        }
    })
    $(document).on("click", ".del_damage", function () {
        $("#damage_price").html(parseFloat($("#damage_price").html()) - parseFloat($(this).parent('p').attr("name")))
        total_price.html(parseFloat(cable.html()) + parseFloat(water.html()) + parseFloat(elec.html()) + parseFloat(damage.html()))
        $("#b_deposit").html(parseFloat(deposit.html()) - parseFloat(total_price.html()))
        if (parseFloat($("#b_deposit").html()) <= 0) {
            $("#b_total").html(Math.abs(parseFloat($("#b_deposit").html())))
            $("#b_deposit").html(0)
        } else {
            $("#b_total").html(0)
        }
        $("#damage_unit").html(parseInt($("#damage_unit").html()) - 1)
        $(this).parent('p').remove()
    })
})