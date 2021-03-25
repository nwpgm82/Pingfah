$(document).ready(function () {
    let url_string = window.location.href
    let url = new URL(url_string);
    let daily_id = url.searchParams.get("daily_id");
    let total_damage = []
    $(document).on("keyup", "input:not(#damage_topic)", function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
        if ($(this).val() != "") {
            $(this).css("border-color", "")

        } else {
            $(this).css("border-color", "red")
        }
    });
    $("#damage_topic").keyup(function(){
        if ($(this).val() != "") {
            $(this).css("border-color", "")
        } else {
            $(this).css("border-color", "red")
        }
    })
    $("#checkout-btn").click(function () {
        let inputs = $("input:not(#damage_topic)")
        let empty = true
        inputs.each(function () {
            if ($(this).val() == "") {
                $(this).css("border-color", "red")
                empty = false
                return false
            }
        })
        if (empty == true) {
            if (confirm("คุณต้องการเช็คเอาท์ใช่หรือไม่ ?")) {
                let div = $(".damage-grid > div:not(:last-child)")
                div.each(function () {
                    let get_topic = $(this).children().children("p").html()
                    let get_price = +$(this).children("input").val()
                    total_damage.push({
                        topic: get_topic,
                        price: get_price
                    })
                })
                console.log(total_damage);
                let total_damage_JSON = JSON.stringify(total_damage)
                $.post(`function/checkout.php?daily_id=${daily_id}`, {
                    data: total_damage_JSON
                }, function (response) {
                    alert("เช็คเอาท์เรียบร้อยแล้ว");
                    location.href = `checkout_success.php?daily_id=${response}`
                });
            }
        }
    })
    $("#add_damage").click(function () {
        $("#add_form").toggle()
    })
    $(document).on("click", "#del_damage", function () {
        $(this).parent('div').parent('div').remove()
    })
    $("#addDamage_form").click(function () {
        if ($("#damage_topic").val() != "") {
            let damage_topic = $("#damage_topic")
            let div = document.createElement("div")
            let div2 = document.createElement("div")
            let p = document.createElement("p")
            let del_btn = document.createElement("button")
            let input = document.createElement("input")
            div2.className = "topic-flex"
            p.innerHTML = damage_topic.val()
            del_btn.className = "del-damage"
            del_btn.innerHTML = "x"
            del_btn.id = "del_damage"
            input.placeholder = "ราคา"
            input.value = 0
            div2.append(p)
            div2.append(del_btn)
            div.append(div2)
            div.append(input)
            $(".add-box").before(div)
            damage_topic.val("")
            $("#add_form").hide()
        }else{
            $("#damage_topic").css("border-color", "red")
        }
    })
})