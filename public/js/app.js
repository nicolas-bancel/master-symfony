var tags = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('Name'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    prefetch: '/tag.json'
});
tags.initialize();

$('[data-role="tagsinput"]').tagsinput({
    typeaheadjs: {
        name: 'tags',
        displayKey: 'Name',
        valueKey: 'Name',
        source: tags.ttAdapter()
    }
});

$('[type="file"]').on('change', function() {
    var label = $(this).val().split('\\').pop();
    $(this).next().text(label);
    var field = $(this);

    var reader = new FileReader();
    reader.addEventListener('load', function(file) {
        console.log(file.target.result);
        $('.custom-file img').remove();
        var img = $('<img class="img-fluid mt-5" width="250" />');
        img.attr('src', file.target.result);
        var customFile = field.parent();
        customFile.prepend(img);
    });
    reader.readAsDataURL(this.files[0]);
});