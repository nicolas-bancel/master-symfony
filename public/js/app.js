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