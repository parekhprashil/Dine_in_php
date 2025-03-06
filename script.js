$(document).ready(function () {

    // select users
    function loadUsers() {
        $.ajax({
            url: "./ajax/select_user.php",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }
    
                console.log(response);
    
                let tableBody = "";
                $.each(response.data, function (index, user) {
                    tableBody += `
                    <tr>
                        <td>${user.user_id}</td>
                        <td>${user.user_image}</td>
                        <td>${user.user_first_name}</td>
                        <td>${user.user_middle_name}</td>
                        <td>${user.user_last_name}</td>
                        <td>${user.user_phone_number}</td>
                        <td>${user.user_email}</td>
                        <td>${user.user_gender}</td>
                        <td>${user.isblock}</td>
                        <td>${user.user_block_timing}</td>
                        <td>${user.user_addeddate}</td>
                        <td>${user.user_updateddate}</td>
                        <td>${user.actions}</td>
                    </tr>`;
                });
    
                if ($.fn.DataTable.isDataTable("#userTable")) {
                    $("#userTable").DataTable().destroy();
                }
                    $("#userTableBody").html(tableBody);
    
                $("#userTable").DataTable({
                    responsive: true,
                    autoWidth: false,
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true
                });
    
            },
            error: function (xhr, status, error) {
                console.log("AJAX Error:", error);
                alert("Failed to load users.");
            }
        });
    }
    
        loadUsers();


        // delete users

        $(document).on("click", ".delete-user", function () {
            let user_id = $(this).data("id");
    
            Swal.fire({
                title: "Are you sure?",
                text: "This will mark the user as deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "./ajax/delete_user.php",
                        type: "POST",
                        data: { user_id: user_id },
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                Swal.fire("Deleted!", response.message, "success");
    
                                loadUsers();
                            } else {
                                Swal.fire("Error!", response.message, "error");
                            }
                        },
                        error: function () {
                            Swal.fire("Error!", "Failed to delete the user.", "error");
                        }
                    });
                }
            });
        });
        $(document).on("change", ".toggle-status", function () {
            let user_id = $(this).data("id");
            let isblock = $(this).prop("checked") ? 1 : 0; // 1 = Blocked, 0 = Active
        
            $.ajax({
                url: "./ajax/block_user.php",
                type: "POST",
                data: { user_id: user_id, isblock: isblock },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Updated!",
                            text: response.message,
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
        
                        // Ensure button status changes immediately
                        let checkbox = $(".toggle-status[data-id='" + user_id + "']");
                        checkbox.prop("checked", response.isblock == 1);
                    } else {
                        Swal.fire("Error!", response.message, "error");
                    }
                },
                error: function () {
                    Swal.fire("Error!", "Failed to update user status.", "error");
                }
            });
        });
        
        
});
