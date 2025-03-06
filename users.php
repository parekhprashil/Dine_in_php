<?php include("./layouts/sidebar.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <style>
        .user-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            cursor: pointer;
            transition: transform 0.2s;
        }

        /* Enlarge image slightly on hover */
        .user-image:hover {
            transform: scale(1.1);
        }

        .table td.description {
            max-height: 4.5em;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
        }

        /* Image Styling */
        .blog-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .blogTable_length {
            position: relative;
            z-index: 10;
            width: 20px;
        }

        div.dataTables_wrapper div.dataTables_length select {
            display: inline-block !important;
            width: 100px !important;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 18px;
        }

        .switch input {
            opacity: 0;
            width: 10px;
            height: 10px;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 12px;
            width: 12px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
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
                            <h5>Users Entries</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="userTable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Image</th>
                                            <th>First Name</th>
                                            <th>Middle Name</th>
                                            <th>Last Name</th>
                                            <th>Phone No</th>
                                            <th>Email</th>
                                            <th>Gender</th>
                                            <th>Is Blocked</th>
                                            <th>Block Timing</th>
                                            <th>Create Date</th>
                                            <th>Update Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="userTableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Image Preview Modal -->
                    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">User Image</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img id="modalImage" src="" class="img-fluid" alt="User Image">
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
                            $('#userTable').DataTable({
                                "destroy": true,
                                "searching": true,
                                "ordering": true,
                                "paging": true,
                                "info": true,
                            });

                            $(".user-image").on("click", function() {
                                let imageUrl = $(this).attr("src");
                                $("#modalImage").attr("src", imageUrl);
                                $("#imageModal").modal("show");
                            });

                        });
                        
                    </script>
                     
                      <?php include_once './layouts/footer.php' ?>                
</body>

</html>

