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

                // console.log(response);

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


    // Block users

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


    // Add country 

    $("#countryForm").on("submit", function (e) {
        e.preventDefault();

        var countryName = $("#country_name").val().trim();
        var errorMsg = $("#country_error");

        if (countryName === "") {
            errorMsg.text("Country name is required.");
            return false;
        } else if (countryName.length < 3) {
            errorMsg.text("Country name must be at least 3 characters.");
            return false;
        }

        var currentDate = new Date().toISOString().slice(0, 19).replace("T", " ");

        $.ajax({
            url: "./ajax/insert_country.php",
            type: "POST",
            data: {
                country_name: countryName,
                country_status: 0,
                isdelete: 0,
                country_addedby: 1,
                country_addeddate: currentDate,
                // country_updatedby: 0,  
                // country_updateddate: currentDate  
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: "Success!",
                        text: "Country added successfully!",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(() => {
                        // location.reload();
                        window.location.href = "manage_country.php"
                    });
                } else {
                    errorMsg.text(response.error);
                    Swal.fire({
                        title: "Error!",
                        text: response.error,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            },
            error: function () {
                Swal.fire({
                    title: "Error!",
                    text: "An error occurred. Please try again.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            },
        });
    });


    // select Country

    function loadCountry() {
        $.ajax({
            url: "./ajax/select_country.php",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }

                let tableBody = "";
                $.each(response.data, function (index, country) {
                    tableBody += `
                <tr>
                    <td>${country.country_id}</td>
                    <td>${country.country_name}</td>
                    <td>${country.country_status}</td>
                    <td>${country.country_addedby}</td>
                    <td>${country.country_addeddate}</td>
                    <td>${country.country_updatedby}</td>
                    <td>${country.country_updateddate}</td>
                    <td>${country.actions}</td>
                </tr>`;
                });

                if ($.fn.DataTable.isDataTable("#countryTable")) {
                    $("#countryTable").DataTable().destroy();
                }

                $("#countryTableBody").html(tableBody);

                $("#countryTable").DataTable({
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
                alert("Failed to load country data.");
            }
        });
    }
    loadCountry();


    // Change Country Status

    $(document).on("click", ".toggle-status", function () {
        let countryId = $(this).data("id");
        let currentStatus = $(this).data("status");
        let newStatus = currentStatus == 1 ? 0 : 1;
        let statusText = newStatus == 1 ? "Deactivate" : "Activate";

        Swal.fire({
            title: `Are you sure?`,
            text: `Do you want to ${statusText} this country?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: newStatus == 1 ? "#dc3545" : "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Yes, ${statusText} it!`
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "./ajax/update_country_status.php",
                    type: "POST",
                    data: { country_id: countryId, country_status: newStatus },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Success!",
                                text: `Country status updated successfully.`,
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                            loadCountry();
                        } else {
                            Swal.fire("Error!", "Failed to update country status.", "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error!", "Something went wrong.", "error");
                    }
                });
            }
        });
    });


    // Delete Country

    $(document).on("click", ".delete-country", function () {
        let country_id = $(this).data("id");
    
        Swal.fire({
            title: "Are you sure?",
            text: "This will mark the country as deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "./ajax/delete_country.php",
                    type: "POST",
                    data: { country_id: country_id },
                    dataType: "json",
                    success: function (response) {
                        console.log("Response from server:", response); // Debugging
    
                        if (response.success) {
                            Swal.fire("Deleted!", response.message, "success");
                            loadCountry();
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("AJAX Error:", error); // Debugging
                        Swal.fire("Error!", "Failed to delete the country.", "error");
                    }
                });
            }
        });
    });
    


    // Get Country

    $(document).on("click", ".edit-country", function () {
        let countryId = $(this).data("id");
        // console.log("Editing Country ID:", countryId);
        $.ajax({
            url: "./ajax/get_country.php",
            type: "POST",
            data: { country_id: countryId },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#editCountryModal").modal("show");
                    $("#editCountryId").val(countryId); 
                    $("#editCountryName").val(response.data.country_name);

                } else {
                    alert("Failed to fetch country details.");
                }
            },
            error: function () {
                alert("Error fetching country data.");
            }
        });
    });

    // Update Country

    $("#editCountryForm").on("submit", function (e) {
        e.preventDefault();

        var countryId = $("#editCountryId").val();
        var countryName = $("#editCountryName").val();
        // console.log(countryId);

        // var errorMsg = $("#editCountryError");

        // if (countryName === "") {
        //     errorMsg.text("Country name is required.");
        //     return false;
        // } else if (countryName.length < 3) {
        //     errorMsg.text("Country name must be at least 3 characters.");
        //     return false;
        // }
        $.ajax({
            url: "./ajax/update_country.php",
            type: "POST",
            data: { country_id: countryId, country_name: countryName },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#editCountryModal").modal("hide");
                    Swal.fire({
                        title: "Success!",
                        text: "Country updated successfully!",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(() => {
                        loadCountry();
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: response.error || "Failed to update country.",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            },
            error: function () {
                Swal.fire({
                    title: "Error!",
                    text: "An error occurred. Please try again.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        });
    });
});
