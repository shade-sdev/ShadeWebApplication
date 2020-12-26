
// When document ready, call loadDataTable() function
$(document).ready(function () {
    loadDataTable(null);
});
$currentpage = null;
// Function used to load data into the Table, adding a set of <tr><td></td></tr> into the <tbody for each row or any data.
function loadDataTable($count) {
    $.ajax({
        type: "POST",
        url: 'home/homeHandler.php',
        data: {

            getData: $count
        },
        success: function (response) {
            var result = JSON.parse(response);
            var rows = result.rows;
            var count = result.count;
            console.log(count);

            for (var row of rows) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <th>` + row.id + `</th>
                    <td>` + row.firstname + `</td>
                    <td>` + row.lastname + `</td>
                    <td>` + row.age + `</td>
                    <td class="footable-editing footable-last-visible" style="display: table-cell;">
                    <div class="btn-group btn-group-xs" role="group">
                    <button type="button" class="btn btn-default footable-edit" name = "`+ row.id + `" style="color:#8b8d9b !important;" data-toggle="modal" data-target="#editForm">
                    <span class="fooicon fooicon-pencil"></span>
                    </button>
                    <button class="btn btn-default footable-delete" name = "`+ row.id + `" style="color:#8b8d9b !important;" type="button">
                    <span class="fooicon fooicon-trash"></span>
                    </button>
                    </div>
                    </td>
                   
                    `;
                document.getElementById('table').appendChild(tr);
            }

            if ($count == null) {
                if (count % 10 != 0) {
                    var numpage = ((count / 10) + 1);
                } else {
                    var numpage = count / 10;
                }





                for (var i = 1; i <= numpage; i++) {
                    const li = document.createElement('li');
                    li.classList.add("footable-page", "visible");
                    li.setAttribute('count', i);
                    li.innerHTML = `<a class="footable-page-link pagenav" data-count="` + i + `" href="#">` + i + `</a>`;
                    document.getElementById('paging').appendChild(li);
                }
            }


        }
    });
}

// Clearing form on form close

$(document).on('click', '.pagenav', function (val) {
    count = $(this).data('count');
    $currentpage = count;
    removeDataFromTable();
    loadDataTable(count);

});

// Removing all child elements into the  <tbody> of the table
function removeDataFromTable() {
    var e = document.querySelector("#table");
    var child = e.lastElementChild;
    while (child) {
        e.removeChild(child);
        child = e.lastElementChild;
    }
}

// Clearing Form

function clearform() {
    $("#dataid").val('');
    $("#firstname").val('');
    $("#lastname").val('');
    $("#age").val('');
}

// Clearing form on form close

$(document).on('click', '.close11', function (val) {

    clearform();
});


// On edit button click, get data from database and fill the form for editing
$(document).on('click', '.footable-edit', function (val) {

    $.ajax({
        type: "GET",
        url: 'home/homeHandler.php',
        data: {
            getSingleData: $(this).attr("name")

        },
        success: function (response) {
            var modaltitle = document.getElementById('modaltitle');
            modaltitle.innerHTML = "Edit Data";
            document.getElementById('savechange').classList.remove('addData');
            document.getElementById('savechange').classList.add('updateData');

            var result = JSON.parse(response);
            var data = result.rows;

            $("#dataid").val(data[0]['id']);
            $("#firstname").val(data[0]['firstname']);
            $("#lastname").val(data[0]['lastname']);
            $("#age").val(data[0]['age']);

        }
    });
});

// On Add button click, changing class
$(document).on('click', '.footable-add', function (val) {

    var modaltitle = document.getElementById('modaltitle');
    modaltitle.innerHTML = "Add Data";
    document.getElementById('savechange').classList.remove('updateData');
    document.getElementById('savechange').classList.add('addData');

});

// Adding Data from form
$(document).on('click', '.addData', function (val) {

    $.ajax({
        type: "GET",
        url: 'home/homeHandler.php',
        data: {
            addData: '',
            firstname: $("#firstname").val(),
            lastname: $("#lastname").val(),
            age: $("#age").val()

        },
        success: function (response) {

            var result = JSON.parse(response);
            removeDataFromTable();
            loadDataTable($currentpage);
            clearform();
        }
    });
});

// Updating Data from form
$(document).on('click', '.updateData', function (val) {

    $.ajax({
        type: "GET",
        url: 'home/homeHandler.php',
        data: {
            updateData: '',
            dataid: $("#dataid").val(),
            firstname: $("#firstname").val(),
            lastname: $("#lastname").val(),
            age: $("#age").val()

        },
        success: function (response) {

            var result = JSON.parse(response);
            removeDataFromTable();
            loadDataTable($currentpage);
            clearform();
        }
    });
});

// On delete button click, deleting data
$(document).on('click', '.footable-delete', function (val) {

    $.ajax({
        type: "GET",
        url: 'home/homeHandler.php',
        data: {
            delData: $(this).attr("name")

        },
        success: function (response) {
            var result = JSON.parse(response);
            removeDataFromTable();
            loadDataTable($currentpage);
            clearform();

        }
    });
});

