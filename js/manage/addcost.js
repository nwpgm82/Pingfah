$(document).ready(function () {
    $("#room_select").change(function () {
        location.href = `addcost.php?room_select=${$("#room_select").val()}`
    })
    $("button[type=submit]").click(function (event) {
        if ($("#room_select").val() == "" || $("#cable_price").val() == "" || $("#water_people").val() == "" || $("#water_price").val() == "" || $("#elec_unit").val() == "" || $("#elec_price").val() == "" || $("#total_price").val() == "") {
            let inputs = $("input")
            inputs.each(function () {
                if ($(this).val() == "") {
                    $(this).css("border-color", "red")
                    event.preventDefault()
                }
                if ($("#room_select").val() == "") {
                    $("#room_select").css("border-color", "red")
                    event.preventDefault()
                }
            })
        }
    })
})