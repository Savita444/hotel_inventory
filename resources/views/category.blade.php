@include('layouts.header')
@include('layouts.sidebar')
@yield('content')
<style>
    .error-text {
        color: red;
        font-size: 12px;
    }

    .is-invalid {
        border-color: red;
    }

    .is-valid {
        border-color: green;
    }

    /* Pagination styles */
    .pagination {
        margin: 20px 0;
        margin-left: 17px;
    }

    .pagination ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .pagination ul li {
        display: inline;
        margin-right: 5px;
    }

    .pagination ul li a,
    .pagination ul li span {
        padding: 5px 10px;
        border: 1px solid #ccc;
        text-decoration: none;
        color: #333;
    }

    .pagination ul li.active a {
        background-color: #007bff;
        color: #fff;
        border-color: #007bff;
    }

    .pagination ul li.disabled span {
        color: #ccc;
    }

    img,
    svg {
        vertical-align: middle;
        width: 2%;
    }

    div.dataTables_wrapper div.dataTables_info {
        display: none;
    }

    div.dataTables_wrapper div.dataTables_paginate ul.pagination {
        display: none;
    }

    .pagination .flex .flex {
        display: none;
    }

    .btn_css:hover {
        color: blue;
    }

    @media (max-width: 472px) {
        .pagination ul {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 0;
        }

        .pagination ul li {
            margin: 2px;
        }

        .pagination ul li:nth-child(n+1) {
            margin-top: 15px;
        }

        .pagination ul li a,
        .pagination ul li span {
            padding: 8px 12px;
            font-size: 14px;
        }

        .pagination ul li.active a {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }
    }
</style>
<div class="main">
    <div class="inner-top container-fluid p-3">
        <!-- Top Bar -->
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('/dashboard') }}">
                <button class="btn btn-light">
                    <i class="bi bi-arrow-90deg-left"></i>
                </button>
            </a>
            <h5 class="sub-title">Category</h5>
            <!-- <a href="approve-users.html"> -->
            <button class="btn btn-light add-btn">
                <i class="bi bi-plus-lg"></i>
                <span>Add Category</span>
            </button>
            <!-- </a> -->
        </div>
    </div>
    <div class="filter">
        <div class="shopping-list-row d-flex align-items-center p-3">
            <!-- Search Input -->
            <!-- <div class="input-group search-input">
            <input
              type="text"
              class="form-control"
              placeholder="Search..."
              aria-label="Search"
            />
            <button class="btn btn-srh" type="button">
              <i class="bi bi-search"></i>
            </button>
            </div> -->
            <!-- Search Input -->
            <div class="input-group search-input">
                <input type="text" class="form-control" placeholder="Search..." aria-label="Search"
                    id="search-query" />
                <!-- <button class="btn btn-srh" type="button">
            <i class="bi bi-search"></i>
            </button> -->
            </div>
            <!-- Location Icon -->
            <!-- <button class="btn btn-white mx-2">
            <i class="bi bi-geo-alt-fill"></i>
            </button> -->
        </div>
    </div>
    <!-- user requestion section  -->
    <div class="user-request">
        @if (!empty($category_data) && count($category_data) > 0)
            <div class="container-fluid px-3" id="search-results">
                @foreach ($category_data as $item)
                    <!-- User Request Box -->
                    <div class="user-request-box p-3 shadow rounded mb-2">
                        <!-- Top Row -->
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Left Section -->
                            <div class="d-flex align-items-center gap-2">
                                <span
                                    class="act-user">{{ ($category_data->currentPage() - 1) * $category_data->perPage() + $loop->iteration }})</span>
                                <span class="act-user">Name :{{ $item->category_name }}</span>
                                <span class="act-user">Priority :{{ $item->priority }}</span>
                            </div>
                            <!-- Right Section -->
                            <div>
                                <button class="btn btn-edit text-center shadow-sm edit-btn-category"
                                    data-id="{{ $item->id }}">
                                    <i class="bi bi-pencil-square"></i> <br />Edit
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                <div class="col-md-8">
                    <div class="pagination">
                        @if ($category_data->lastPage() > 1)
                            <ul class="pagination">
                                <li class="{{ $category_data->currentPage() == 1 ? ' disabled' : '' }}">
                                    @if ($category_data->currentPage() > 1)
                                        <a
                                            href="{{ $category_data->url($category_data->currentPage() - 1) }}">Previous</a>
                                    @else
                                        <span>Previous</span>
                                    @endif
                                </li>
                                @php
                                    $currentPage = $category_data->currentPage();
                                    $lastPage = $category_data->lastPage();
                                    $startPage = max($currentPage - 5, 1);
                                    $endPage = min($currentPage + 4, $lastPage);
                                @endphp
                                @if ($startPage > 1)
                                    <li>
                                        <a href="{{ $category_data->url(1) }}">1</a>
                                    </li>
                                    @if ($startPage > 2)
                                        <li>
                                            <span>...</span>
                                        </li>
                                    @endif
                                @endif
                                @for ($i = $startPage; $i <= $endPage; $i++)
                                    <li class="{{ $currentPage == $i ? ' active' : '' }}">
                                        <a href="{{ $category_data->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                @if ($endPage < $lastPage)
                                    @if ($endPage < $lastPage - 1)
                                        <li>
                                            <span>...</span>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="{{ $category_data->url($lastPage) }}">{{ $lastPage }}</a>
                                    </li>
                                @endif
                                <li class="{{ $currentPage == $lastPage ? ' disabled' : '' }}">
                                    @if ($currentPage < $lastPage)
                                        <a href="{{ $category_data->url($currentPage + 1) }}">Next</a>
                                    @else
                                        <span>Next</span>
                                    @endif
                                </li>
                                <!-- <li>
                     <span>Page {{ $currentPage }}</span>
                     </li> -->
                            </ul>
                        @endif
                    </div>
                </div>
                <!-- Pagination for each category -->
            </div>
        @else
            <div class="border-box mb-4" id="search-results">
                <!-- Header Title -->
                <div class="grid-header text-center">
                    <h6 class="m-0 text-white">No Data Found</h6>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- Add Popup -->
<div id="addPopup" class="popup-container">
    <div class="popup-content">
        <form class="forms-sample" id="frm_register_add" name="frm_register" method="post" role="form"
            action="{{ route('add-category') }}" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
            <!-- Popup Title -->
            <h4 class="popup-title">Add Category</h4>
            <hr />
            <!-- Select Options -->
            <div class="row mb-3">
                <label class="col-md-6 col-sm-12 col-lg-6 form-label">Category Name</label>
                <div class="col-md-6 col-sm-12 col-lg-6">
                    <input type="text" class="form-control" placeholder="Category Name" name="category_name"
                        id="abc" style="text-transform: capitalize;" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="form-label col-md-6 col-sm-12 col-lg-6">priority
                </label>
                <div class="col-md-6 col-sm-12 col-lg-6">
                    <input type="text" class="form-control" placeholder="" name="priority" value="0" />
                </div>
            </div>
            <hr />
            <div class="d-flex justify-content-around">
                <a class="btn btn-secondary btn-lg w-100 me-2" id="closePopup">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <button class="btn btn-success btn-lg w-100">
                    <i class="bi bi-plus-circle"></i> Add
                </button>
            </div>
        </form>
    </div>
</div>
<!-- edit popup  -->
<div id="editPopupCategory" class="popup-container">
    <div class="popup-content">
        <form class="forms-sample" id="editCategoryForm" name="editCategoryForm" method="post" role="form"
            action="{{ route('update-category') }}" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
            <!-- Popup Title -->
            <h4 class="popup-title">Edit Category</h4>
            <hr />
            <!-- Select Options -->
            <div class="row mb-3">
                <label class="col-md-6 col-sm-12 col-lg-6 form-label">Category Name</label>
                <div class="col-md-6 col-sm-12 col-lg-6">
                    <input type="text" class="form-control" placeholder="Category Name" name="category_name"
                        id="category_id" style="text-transform: capitalize;" />
                    <input type="hidden" class="form-control" placeholder="Enter Location Name" name="edit_id"
                        id="edit-category-id" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="form-label col-md-6 col-sm-12 col-lg-6">priority
                </label>
                <div class="col-md-6 col-sm-12 col-lg-6">
                    <input type="text" class="form-control" placeholder="" name="priority" id="priority"
                        value="0" />
                </div>
            </div>
            <hr />
            <div class="d-flex justify-content-around">
                <!-- <button class="btn btn-outline-danger btn-delete btn-lg w-100 me-2">
               <i class="bi bi-trash"></i> Delete
               </button> -->
                <a class="btn btn-outline-danger btn-delete-category btn-lg w-100 me-2">
                    <i class="bi bi-trash"></i> Delete
                </a>
                <button class="btn btn-danger btn-lg w-100">
                    <i class="bi bi-arrow-repeat"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
<!-- Delete Confirmation Popup -->
<div id="confirmPopupCategory" class="confirm-popup-container">
    <div class="confirm-popup-content">
        <h4 class="confirm-popup-title">Please Confirm</h4>
        <p class="confirm-popup-text">
            Are you sure to delete this Category? <br />
            this Category will not recover back
        </p>
        <div class="d-flex justify-content-around mt-4 confrm">
            <button id="cancelDelete" class="btn br btn_css">NO</button>
            <button id="confirmDeleteCategory" class="btn btn_css">YES</button>
        </div>

        <!-- Delete Confirmation Popup -->
        <div id="confirmPopupCategory" class="confirm-popup-container">
            <div class="confirm-popup-content">
                <h4 class="confirm-popup-title">Please Confirm</h4>
                <p class="confirm-popup-text">
                    Are you sure to delete this Category? <br />
                    this Category will not recover back
                </p>
                <div class="d-flex justify-content-around mt-4 confrm">
                    <button id="cancelDelete" class="btn br btn_css">NO</button>
                    <button id="confirmDeleteCategory" class="btn btn_css">YES</button>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ url('/delete-category') }}" id="deleteform">
        @csrf
        <input type="hidden" name="delete_id" id="delete_id" value="">
    </form>

    @extends('layouts.footer')

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", () => {
            // const deleteButton = document.querySelector(".btn-delete");
            // const editButton = document.querySelector(".edit-btn");
            // const popup = document.getElementById("editPopup");
            const addButton = document.querySelector(".add-btn");
            const popupadd = document.getElementById("addPopup");
            // const confirmPopup = document.getElementById("confirmPopup");
            const cancelDeleteButton = document.getElementById("cancelDelete");
            const closePopUpButton = document.getElementById("closePopup");

            const editButtonCategory = document.querySelector(".edit-btn-category");
            const popupcategory = document.getElementById("editPopupCategory");
            const deleteButtonCategory = document.querySelector(".btn-delete-category");
            const confirmPopupCategory = document.getElementById("confirmPopupCategory");
            const confirmDeleteButtonCategory = document.getElementById("confirmDeleteCategory");




            // // Open Popup
            addButton.addEventListener("click", () => {
                popupadd.style.display = "flex";
            });

            // Close Popup
            closePopUpButton.addEventListener("click", () => {
                document.getElementById("frm_register_add").reset();
                popupadd.style.display = "none";
            });

            // Close Popup when clicking outside
            popupcategory.addEventListener("click", (e) => {
                if (e.target === popupcategory) {
                    popupcategory.style.display = "none";
                }
            });

            popupadd.addEventListener("click", (e) => {
                if (e.target === popupadd) {
                    // document.getElementById("abc").value = '';
                    document.getElementById("frm_register_add").reset();
                    popupadd.style.display = "none";

                }
            });

            deleteButtonCategory.addEventListener("click", () => {
                popupcategory.style.display = "none"; // Close the bottom popup
                confirmPopupCategory.style.display = "flex"; // Show the confirmation popup
            });

            // Close Confirmation Popup on Cancel
            cancelDeleteButton.addEventListener("click", () => {
                confirmPopupCategory.style.display = "none";
            });

            confirmDeleteButtonCategory.addEventListener("click", () => {
                confirmPopupCategory.style.display = "none";
                $("#delete_id").val($("#edit-category-id").val());
                $("#deleteform").submit();
            });
        });
    </script>


    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", () => {
            const addButton = document.querySelector(".add-btn");
            const popupadd = document.getElementById("addPopup");
            const cancelDeleteButton = document.getElementById("cancelDelete");
            const closePopUpButton = document.getElementById("closePopup");

            const editButtonCategory = document.querySelector(".edit-btn-category");
            const popupcategory = document.getElementById("editPopupCategory");
            const deleteButtonCategory = document.querySelector(".btn-delete-category");
            const confirmPopupCategory = document.getElementById("confirmPopupCategory");
            const confirmDeleteButtonCategory = document.getElementById("confirmDeleteCategory");

            // // Open Popup
            addButton.addEventListener("click", () => {
                popupadd.style.display = "flex";
            });

            // Close Popup
            closePopUpButton.addEventListener("click", () => {
                document.getElementById("frm_register_add").reset();

                // Remove validation messages
                document.querySelectorAll(".text-danger, .error-text").forEach((el) => el.innerText = "");

                // Remove 'is-invalid' class from inputs
                document.querySelectorAll(".form-control").forEach((el) => el.classList.remove(
                    "is-invalid"));
                popupadd.style.display = "none";
            });

            // Close Popup when clicking outside
            popupcategory.addEventListener("click", (e) => {
                if (e.target === popupcategory) {
                    popupcategory.style.display = "none";
                }
            });

            popupadd.addEventListener("click", (e) => {
                if (e.target === popupadd) {
                    // document.getElementById("abc").value = '';
                    document.getElementById("frm_register_add").reset();
                    document.querySelectorAll(".text-danger, .error-text").forEach((el) => el.innerText =
                        "");

                    // Remove 'is-invalid' class from inputs
                    document.querySelectorAll(".form-control").forEach((el) => el.classList.remove(
                        "is-invalid"));

                    popupadd.style.display = "none";

                }
            });

            deleteButtonCategory.addEventListener("click", () => {
                popupcategory.style.display = "none"; // Close the bottom popup
                confirmPopupCategory.style.display = "flex"; // Show the confirmation popup
            });

            // Close Confirmation Popup on Cancel
            cancelDeleteButton.addEventListener("click", () => {
                confirmPopupCategory.style.display = "none";
            });

            confirmDeleteButtonCategory.addEventListener("click", () => {
                confirmPopupCategory.style.display = "none";
                $("#delete_id").val($("#edit-category-id").val());
                $("#deleteform").submit();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.edit-btn-category', function() {
                showLoader();
                var edit_id = $(this).data('id'); // Get the location ID from the button
                
                $.ajax({
                    url: '{{ route('edit-category') }}', // Your route to fetch the location data
                    type: 'GET',
                    data: {
                        edit_id: edit_id
                    },
                    success: function(response) {
                        $('#priority').val(response.category_data
                        .priority); // Set location value
                        $('#category_id').val(response.category_data
                        .category_name); // Set location value
                        $('#edit-category-id').val(response.category_data.id); // Set role value

                        $('#editPopupCategory').show();

                        document.getElementById('editPopupCategory').style.display = "flex";
                        hideLoader();
                    },
                    error: function() {
                        alert('Failed to load location data.');
                        hideLoader();
                    }
                });
            });

        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize validation for the add form
            $(document).ready(function(e) {
                $("#frm_register_add").validate({
                    rules: {
                        category_name: {
                            required: true,
                            minlength: 3
                        }
                    },
                    messages: {
                        category_name: {
                            required: "Please enter the category name",
                            minlength: "Category name must be at least 3 characters long"
                        }
                    },
                    errorElement: "span",
                    errorClass: "error-text",
                    highlight: function(element) {
                        e.preventDefault();
                        $(element).addClass("is-invalid").removeClass("is-valid");
                    },
                    unhighlight: function(element) {
                        $(element).addClass("is-valid").removeClass("is-invalid");
                    }
                });
            });


            // Initialize validation for the edit form
            $("#editCategoryForm").validate({
                rules: {
                    category_name: {
                        required: true,
                        minlength: 3
                    }
                },
                messages: {
                    category_name: {
                        required: "Please enter the category name",
                        minlength: "Category name must be at least 3 characters long"
                    }
                },
                errorElement: "span",
                errorClass: "error-text",
                highlight: function(element) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function(element) {
                    $(element).addClass("is-valid").removeClass("is-invalid");
                }
            });


        });
    </script>
    <script>
        $(document).ready(function() {
            var originalData = $('#search-results').html();
            // Bind keyup event to the search input
            $('#search-query').on('keyup', function() {
                showLoader();
                var query = $(this).val().trim(); // Get the value entered in the search box

                if (query.length > 0) {
                    $.ajax({
                        url: "{{ route('search-category') }}", // Define your search route here
                        method: "GET",
                        data: {
                            query: query
                        },
                        success: function(response) {
                            if (response.length > 0) {
                                // Clear the previous results
                                $('#search-results').html('');

                                // Append the new search results
                                $('#search-results').html(response);
                                hideLoader();
                            } else {
                                $('#search-results').html('No Data Found');
                                hideLoader();
                            }
                        }
                    });
                } else {
                    // Clear the results if input is empty
                    // $('#search-results').html('');
                    $('#search-results').html(originalData);
                    hideLoader();
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#frm_register_add').submit(function(event) {
                event.preventDefault(); // Prevent the form from submitting traditionally

                let form = $(this);
                let formData = new FormData(form[0]); // Collect form data

                $.ajax({
                    url: "{{ route('add-category') }}", // Define the route directly here
                    method: "POST", // POST method for form submission
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == 'success') {
                            // Reload the page after success
                            location.reload();
                        } else {
                            // Handle error message if category was not added
                            alert(response.msg);
                        }
                    },
                    error: function(xhr) {
                        // Handle validation errors here
                        var errors = xhr.responseJSON.errors;
                        // Clear previous error messages
                        $('.text-danger').remove();

                        // Display new errors
                        if (errors.category_name) {
                            $('input[name="category_name"]').after(
                                '<span class="text-danger">' + errors.category_name[0] +
                                '</span>');
                        }

                        // Keep the popup open in case of errors
                        $('#addPopup').show();
                    }
                });
            });
        });
    </script>
