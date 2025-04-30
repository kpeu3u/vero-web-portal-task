<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="assets/images/favicon.ico">

    <title>VERO Digital</title>

    <!-- Style-->
    <link rel="stylesheet" href="assets/css/color_theme.css">
    <link rel="stylesheet" href="assets/css/horizontal-menu.css">
    <link rel="stylesheet" href="assets/vendor_components/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="assets/icons/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="assets/vendor_components/animate/animate.css">
    <link rel="stylesheet" href="assets/vendor_components/datatable/datatables.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="layout-top-nav light-skin theme-primary fixed">

<div class="wrapper">
    <div id="loader"></div>

    <header class="main-header">
        <div class="inside-header">
            <div class="d-flex align-items-center logo-box justify-content-start">
                <!-- Logo -->
                <a href="#" class="logo">
                    <!-- logo-->
                    <div class="logo-lg">
                        <span class="light-logo" style="color: #FFF">VERO Digital</span>
                    </div>
                </a>
            </div>
        </div>
    </header>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Ticket List using DataTables</h4>
                                <h6 id="lastUpdate" class="box-subtitle"></h6>
                            </div>
                            <div class="box-body p-15">
                                <div class="table-responsive">
                                    <table id="tickets" class="table mt-0 table-hover no-wrap" data-page-size="10">
                                        <thead>
                                        <tr>
                                            <th>Task</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>ColorCode</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <!-- DataTables will populate data here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Ticket List using Normal Nodata table</h4>
                                <h6 id="lastUpdateSimpleTable" class="box-subtitle"></h6>
                            </div>
                            <div class="box-body p-15">
                                <div class="mt-4">
                                    <h4>Simple Table View</h4>
                                    <div class="mb-3">
                                        <label for="simple-table-search"></label>
                                        <input type="text" id="simple-table-search" class="form-control" placeholder="Search in simple table...">
                                    </div>

                                    <table id="simple-tickets" class="table mt-0 table-hover">
                                        <thead>
                                        <tr>
                                            <th style="cursor: pointer; min-width: 150px;">Task <span class="sort-icon">↕</span></th>
                                            <th style="cursor: pointer">Title <span class="sort-icon">↕</span></th>
                                            <th style="cursor: pointer">Description <span class="sort-icon">↕</span></th>
                                            <th style="cursor: pointer; min-width: 150px;">ColorCode <span class="sort-icon">↕</span></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row" id="modalSection">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Modal section</h4>
                                <h6 class="box-subtitle"></h6>
                            </div>
                            <div class="box-body p-15">
                                <div class="mt-4">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">
                                        Click me to open modal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        &copy;
        <script>document.write(new Date().getFullYear())</script>
        <a href="#">Multipurpose Themes</a>. All Rights Reserved.
    </footer>
</div>
<!-- ./wrapper -->

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Image selection</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-20">
                    <h4 id="imageSectionTitle">No image selected</h4>
                    <img id="selectedImage" src="" alt="" class="img-fluid">
                </div>

                <h4>Please select image</h4>
                <input type="file" name="image" id="image" class="form-control" onchange="readImageEdit(this);">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-start" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Vendor JS -->
<script src="assets/js/vendors.min.js"></script>
<script src="assets/vendor_components/datatable/datatables.min.js"></script>
<!-- Deposito Admin App -->
<script src="assets/js/template.js"></script>
<script>
    $(document).ready(function () {
        // Initialize DataTable
        let table = $('#tickets').DataTable({
            "ajax": {
                "url": "fetchTasks.php",
                "dataSrc": function(json) {
                    updateLastRefreshTime();
                    updateSimpleTable(json);
                    return json;
                }
            },
            "columns": [
                { "data": "task" },
                { "data": "title" },
                { "data": "description" },
                { "data": "colorCode" }
            ],
            "processing": true,
            "serverSide": false,
            "createdRow": function(row, data, dataIndex) {
                // Set the background color of the entire row
                $(row).css('background-color', data.colorCode);
            }

        });

        // Initial update time
        updateLastRefreshTime();

        // Refresh every 60 minutes
        setInterval(function() {
            table.ajax.reload(null, false);
        }, 60 * 60 * 1000);

        // Function to update the simple table
        function updateSimpleTable(data) {
            const tbody = $('#simple-tickets tbody');
            tbody.empty();

            data.forEach(function(row) {
                // Append each row to the simple table
                tbody.append(`
                    <tr style="background-color: ${row.colorCode};">
                        <td>${row.task}</td>
                        <td>${row.title}</td>
                        <td>${row.description}</td>
                        <td>${row.colorCode}</td>
                    </tr>
                `);
            });
        }

        $('#simple-table-search').on('keyup', function() {
            const searchText = $(this).val().toLowerCase();

            $('#simple-tickets tbody tr').each(function() {
                const rowText = $(this).text().toLowerCase();
                $(this).toggle(rowText.includes(searchText));
            });
        });

        // Sorting functionality
        $('#simple-tickets th').on('click', function() {
            const table = $(this).parents('table').eq(0);
            const rows = table.find('tr:gt(0)').toArray();
            const colIndex = $(this).index();
            const ascending = $(this).hasClass('asc');

            // Update sort indicators
            table.find('th').removeClass('asc desc');
            $(this).addClass(ascending ? 'desc' : 'asc');

            // Sort the rows
            rows.sort(function(a, b) {
                const A = $(a).children('td').eq(colIndex).text().trim().toLowerCase();
                const B = $(b).children('td').eq(colIndex).text().trim().toLowerCase();

                return ascending ?
                    (A < B ? 1 : (A > B ? -1 : 0)) :
                    (A < B ? -1 : (A > B ? 1 : 0));
            });

            // Remove existing rows and append sorted ones
            table.find('tr:gt(0)').remove();
            table.append(rows);
        });
        // End simple table

        function updateLastRefreshTime() {
            const now = new Date();
            $('#lastUpdate').text('Last updated: ' + now.toLocaleTimeString());
            $('#lastUpdateSimpleTable').text('Last updated: ' + now.toLocaleTimeString());
        }
    });

    function readImageEdit(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.fileName = input.files[0].name;
            reader.onload = function (e) {
                $('#selectedImage').attr('src', e.target.result);
                $('#imageSectionTitle').text('Selected image: ' + reader.fileName);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>
</html>
