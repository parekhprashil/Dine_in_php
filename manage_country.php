<?php include("./layouts/sidebar.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <style>
        div.dataTables_wrapper div.dataTables_length select {
            display: inline-block !important;
            width: 100px !important;
        }
    </style>
</head>

<body>
    <div class="pc-container">
        <div class="pc-content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Coutry Entries</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="countryTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Country Name</th>
                                            <th>Country Status</th>
                                            <th>Country AddedBy</th>
                                            <th>Country AddedDate</th>
                                            <th>Country UpdateBy</th>
                                            <th>Country UpdateDate</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="countryTableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Country Modal -->
                    <div class="modal fade" id="editCountryModal" tabindex="-1" aria-labelledby="editCountryModalLabel" aria-hidden="true">
                    <input type="hidden" id="editCountryId" name="country_id">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editCountryModalLabel">Edit Country</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editCountryForm">
                                        <input type="hidden" id="editCountryId">
                                        <div class="mb-3">
                                            <label for="editCountryName" class="form-label">Country Name</label>
                                            <input type="text" class="form-control" id="editCountryName" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update Country</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SweetAlert2 CSS -->
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

                    <!-- SweetAlert2 JavaScript -->
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
                    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
                    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
                    <script src="script.js"></script>
                    <script>
                        $(document).ready(function() {
                            $('#countryTable').DataTable({
                                "destroy": true,
                                "searching": true,
                                "ordering": true,
                                "paging": true,
                                "info": true,
                            });
                        });
                    </script>

                    <?php include_once './layouts/footer.php' ?>
                    
</body>

</html>