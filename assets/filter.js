$(document).ready(function() {
    $('#searchInput').selectize({
        plugins: ["clear_button", "remove_button", "restore_on_backspace"],
        create: true,
        valueField: 'value',
        labelField: 'text',
        searchField: 'text',
        load: function(query, callback) {
            if (query.length < 1) {
                callback([]);
                return;
            }
            $.request('onGetKeywords', {
                data: { query: query },
                success: function(response) {
                    callback(response);
                }
            });
        },
        render: {
            option_create: function(data, escape) {
                return '<div class="create">Search for: <strong>' + escape(data.input) + '</strong>&hellip;</div>';
            }
        },
        highlight: true,
        sortField: 'text',
        loadThrottle: 300,
        noResultsText: 'No results found',
        onChange: function(value) { 
            updateProjectList();
        }
    });

    $('#sortField, #sortDirection').selectize({
        onChange: function(value) {
            updateProjectList();
        }
    });

    $('#startDate').yearpicker({
        onChange: function(value) {
            updateProjectList();
        }
    });

    $('#endDate').yearpicker({
        onChange: function(value) {
            updateProjectList();
        }
    });

    $('#clearDates').click(function() {
        $('#startDate').val('');
        $('#endDate').val('');
        updateProjectList();
    });

    function updateProjectList() {
        var sortField = $('#sortField').val();
        var sortDirection = $('#sortDirection').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
    

        $.request('onSearchRecords', {
            data: {
                searchTerms: $('#searchInput').val(),
                sortField: sortField, 
                sortDirection: sortDirection,
                startDate: startDate,
                endDate: endDate,
            },
            update: { '@records': '#recordsContainer' }
        });
    }
    
});

$(document).keydown(function(e) {
    
    // 191 = /
    if (e.keyCode === 191) {
        e.preventDefault();
        $('#searchInput')[0].selectize.focus();
    }

    // 27 = esc
    if (e.keyCode === 27) {
        e.preventDefault();
        $('#searchInput')[0].selectize.close();
        $('#searchInput')[0].selectize.blur();
    }
});