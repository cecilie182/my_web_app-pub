import '../css/challenge.css';

jQuery(document).ready(function() {

    $('form[name="levels"]').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: $(this).attr('method'),
            url : $(this).attr('action'),
            data: $(this).serialize()
        }).done(function( result ) {
            $("#problem1").find('#solution').replaceWith($(result).find('#solution'));
        });
    });

    let $productsCollectionHolder = $('div.products');
    $productsCollectionHolder.data('index', $productsCollectionHolder.find('.col').length);

    $productsCollectionHolder.find('.col').each(function() {
        addDeleteLink($(this));
    });

    $('body').on('click', '.add_product', function(e) {
        let $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');
        addFormToCollection($collectionHolderClass);
    })
});


function addFormToCollection($collectionHolderClass) {
    let $collectionHolder = $('.' + $collectionHolderClass);
    let prototype = $collectionHolder.data('prototype');
    let index = $collectionHolder.data('index');

    let newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);

    $collectionHolder.data('index', index + 1);

    let $newFormLi = $(newForm);
    $collectionHolder.append($newFormLi);
    addDeleteLink($newFormLi);
}

function addDeleteLink($productForm) {
    let $removeFormButton = $('<button type="button" class="btn-danger btn">Delete product</button>');
    $productForm.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        $productForm.remove();
    });
}