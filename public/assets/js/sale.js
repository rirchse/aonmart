$(document).ready(function () {

    // Add product to sale
    $(document).on('click', '.add-product-btn', function (e) {
        e.preventDefault();
        let stock = $(this).data('stock');
        if (stock === 0) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            Toast.fire({
                type: 'error',
                title: 'You Product Stock is Empty !! Please Update It'
            });
        } else {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let price = $(this).data('price');

            for (let i = 0; i < $('.order-list .items').length; i++) {
                let old_product_id = $("[data-rowNumber=" + id + "] td:nth-child(1)").data('id');
                let old_product_qty = $("[data-rowNumber=" + id + "] td:nth-child(3) input").val();

                if (old_product_id == id) {
                    let add = parseInt(old_product_qty) + 1;
                    if (add <= stock) {
                        $("[data-rowNumber=" + id + "] td:nth-child(3) input").val(add);
                        var all = add * price;
                    }
                    $("[data-rowNumber=" + id + "] td:nth-child(4)").html(all);
                    calculateTotal();
                    calculateTotalAmount();
                    calculateCredit();
                    return true;
                }
            }
            let html =
                `<tr data-rowNumber="${id}" class="form-group items">
                    <td data-id="${id}" class="name">${name}</td>
                    <input type="hidden" name="product[]" value="${id}">
                    <td style="display: flex;">
                    <input id="qty${id}" style="width: 100% !important;" type="number" step="0.1" name="quantity[]" data-price="${price}" data-stock="${stock}" class="form-control custom-input input-sm product-quantity" min="1" value="1">
                    </td>
                    <td class="text-center product-price">${price}</td>
                    <td class="text-center"><button type="button" class="btn btn-light btn-sm remove-product-btn" data-id="${id}"><span class="fa fa-trash"></span></button></td>
                </tr>`;
            $('.order-list').append(html);
            calculateTotal();
            calculateTotalAmount();
            calculateCredit();
            return true;
        }
    });

    //to calculate total price
    $(document).on('click', '.disabled', function (e) {
        e.preventDefault();
    });

    let OrderList = $('.order-list');

    OrderList.on('click', '.remove-product-btn', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $(this).closest('tr').remove();
        //to calculate total price
        calculateTotal();
        calculateTotalAmount();
        calculateCredit();
    }); //end of remove product btn

    OrderList.on('change keyup', '.product-quantity', function (e) {
        var reg = /([A-Z a-z])$/g;
        let quantity = parseFloat($(this).val()); //2
        if(reg.test(quantity))
        {
            $(this).val(0);
        }
        let unitPrice = $(this).data('price'); //150
        $(this).closest('tr').find('.product-price').html((quantity * unitPrice).toFixed(2));
        calculateTotal();
        calculateTotalAmount();
        calculateCredit();
    }); //end of product quantity change

    $(document).on('change keyup', '.discount', function () {
        calculateTotalAmount();
        let totalAmount = $('#total-amount').val();
        let paid = $('#paid').val();
        let credit = totalAmount - paid;
        $("#paid").attr({
            // "max": totalAmount, // substitute your own
            "min": 0 // values (or letiables) here
        });
        if (credit < 0) {
            $('#cash_change').val(paid - totalAmount);
            $('#credit').val(0);
        } else {
            $('#cash_change').val(0);
            $('#credit').val(credit);
        }
    }); //end of product quantity change

    $(document).on('change keyup', '.paid', function () {
        calculateCredit();
        let totalAmount = $('#total-amount').val();
        let paid = $('#paid').val();
        if (paid === 0) {
            $('#select option[value="nopaid"]').prop('selected', true);
        } else if (totalAmount == paid) {
            $('#select option[value="paid"]').prop('selected', true);
        } else {
            $('#select option[value="due"]').prop('selected', true);
        }
    });
    $(document).on('change', '.paid', function () {
        $('#select option[value="due"]').prop('selected', true);
    });
}); //end of document ready

function calculateTotal() {
    let price = 0;
    $('.order-list .product-price').each(function (index) {
        price += parseInt($(this).html());
    }); //end of product price
    //$('.total-price').html(price);
    $('.total-price').val(price);
} //end of calculate total

function calculateTotalAmount() {
    let total = $('.total-price').val();
    let discount = $('#discount').val();
    let total_amount = total - discount;
    $('#total-amount').val(total_amount);
    $('#paid').val(total_amount);
} //end of calculate total Amount

function calculateCredit() {
    let totalAmount = $('#total-amount').val();
    let paid = $('#paid').val();
    let credit = totalAmount - paid;
    $("#paid").attr({
        // "max": totalAmount, // substitute your own
        "min": 0 // values (or letiables) here
    });

    if (credit < 0) {
        $('#cash_change').val(paid - totalAmount);
        $('#credit').val(0);
    } else {
        $('#cash_change').val(0);
        $('#credit').val(credit);
    }
}
