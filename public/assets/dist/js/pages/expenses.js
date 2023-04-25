/**
 * Calculate Parcels
 */
function calc() {
    var value = document.getElementById("value").value;
    var value = parseFloat(value, 10);
    var parcels = document.getElementById("parcels").value;
    var parcels = parseFloat(parcels, 10);
    var parcel_vl = value.toFixed(2) / parcels;
    document.getElementById("parcel_vl").value = parcel_vl.toFixed(2);
}

/**
 * Show Inputs on cred payment method
 */
const checkbox = document.getElementById('showparcels');

$(document).ready(function () {
    $('input[type="radio"]').click(function () {
        if ($(this).attr('id') == 'cred') {
            document.getElementById('show_cred').style.display = '';
        }
        else {
            document.getElementById('show_cred').style.display = 'none';
            document.getElementById('show_parcels').style.display = 'none';
            document.getElementById("showparcels").checked = false;
        }
    });
});

checkbox.addEventListener('click', function handleClick() {
    if (checkbox.checked) {
        document.getElementById('show_parcels').style.display = '';
    } else {
        document.getElementById('show_parcels').style.display = 'none';
    }
});

/** Select Categories */

$(function () {
    //Initialize Select2 Elements
    $('.select2categories').select2({
        theme: 'bootstrap4'
    })
});

$(document).on('select2:open', () => {
    document.querySelector('.select2-search__field').focus();
});

/**
 * check old cred inputs
 */
function check_cred_inputs() {
    $(document).ready(function () {
        if ($("#cred").is(':checked')) {
            document.getElementById('show_cred').style.display = '';
        }
        else {
            document.getElementById('show_cred').style.display = 'none';
            document.getElementById('show_parcels').style.display = 'none';
            $("id:showparcels").removeAttr("checked");
        }
    });

    $(document).ready(function () {
        if ($("#showparcels").is(':checked')) {
            document.getElementById('show_parcels').style.display = '';
        } else {
            document.getElementById('show_parcels').style.display = 'none';
        }
    });
}

