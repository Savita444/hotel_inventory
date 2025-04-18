
<script>
  if (/iPhone|iPad|iPod/i.test(navigator.userAgent) && !window.matchMedia('(display-mode: standalone)').matches) {
    // Show custom banner or prompt asking user to add to home screen
    alert("To install this app, tap the Share button and select 'Add to Home Screen'");
}
</script>

<script>
  function showLoader() {
      document.getElementById("custome_loader").style.display = "flex";
  }

  function hideLoader() {
      document.getElementById("custome_loader").style.display = "none";
  }
</script>

<script>
    const toggleDrawer = document.getElementById("toggleDrawer");
    const drawer = document.getElementById("drawer");
    const backdrop = document.getElementById("backdrop");
    
    // Toggle drawer and backdrop
    toggleDrawer.addEventListener("click", () => {
      drawer.classList.toggle("open");
      backdrop.classList.toggle("show");
    });
    
    // Close drawer and backdrop when clicking the backdrop
    backdrop.addEventListener("click", () => {
      drawer.classList.remove("open");
      backdrop.classList.remove("show");
    });
 </script>
 <script>
  $(document).ready(function() {
    $('.select2').select2({
      minimumResultsForSearch: 0,
      placeholder: "Select Location", // Optional placeholder
      allowClear: true,// Optional to allow clearing the selection
      width: '100%',
      // closeOnSelect: false,
      tags: false
    });
  });
</script>

 <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", () => {
      // const deleteButton = document.querySelector(".btn-delete");
      // const editButton = document.querySelector(".edit-btn");
      // const popup = document.getElementById("editPopup");
      // const addButton = document.querySelector(".add-btn");
      // const popupadd = document.getElementById("addPopup");
      // const confirmPopup = document.getElementById("confirmPopup");
      // const cancelDeleteButton = document.getElementById("cancelDelete");
      // const confirmDeleteButton = document.getElementById("confirmDelete");

      // const editButtonCategory = document.querySelector(".edit-btn-category");
      // const popupcategory = document.getElementById("editPopupCategory");
      // const deleteButtonCategory = document.querySelector(".btn-delete-category");
      // const confirmPopupCategory = document.getElementById("confirmPopupCategory");
      // const confirmDeleteButtonCategory = document.getElementById("confirmDeleteCategory");



    
      // // Open Popup
      // addButton.addEventListener("click", () => {
      //   popupadd.style.display = "flex";
      // });

      // Open Popup
      // editButton.addEventListener("click", () => {
      //   popup.style.display = "flex";
      // });
    
      // Close Popup when clicking outside
      // popup.addEventListener("click", (e) => {
      //   if (e.target === popup) {
      //     popup.style.display = "none";
      //   }
      // });
    
      // Show Confirmation Popup
      // deleteButton.addEventListener("click", () => {
      //   popup.style.display = "none"; // Close the bottom popup
      //   confirmPopup.style.display = "flex"; // Show the confirmation popup
      // });

      // deleteButtonCategory.addEventListener("click", () => {
      //   popupcategory.style.display = "none"; // Close the bottom popup
      //   confirmPopupCategory.style.display = "flex"; // Show the confirmation popup
      // });
    
      // Close Confirmation Popup on Cancel
      // cancelDeleteButton.addEventListener("click", () => {
      //   confirmPopup.style.display = "none";
      // });
    
      // Perform Action on Confirm Delete
      // confirmDeleteButton.addEventListener("click", () => {
      //   confirmPopup.style.display = "none";
      //           $("#delete_id").val($("#edit-location-id").val());
      //           $("#deleteform").submit();
      //   alert("User deleted successfully!");
      //   // Add delete logic here
      // });

      // confirmDeleteButtonCategory.addEventListener("click", () => {
      //   confirmPopupCategory.style.display = "none";
      //           $("#delete_id").val($("#edit-category-id").val());
      //           $("#deleteform").submit();
      //   alert("User deleted successfully!");
      //   // Add delete logic here
      // });
    });
 </script>

<!-- <script>
 $(document).ready(function() {
  // Open the popup when Edit button is clicked
  $('.edit-btn').on('click', function() {
    var locationId = $(this).data('id'); // Get the location ID from the button
    
    // AJAX request to get location data
    $.ajax({
      url: '{{ route('edit-hotels') }}', // Your route to fetch the location data
      type: 'GET',
      data: {
                locationId: locationId
            },
      success: function(response) {
        // console.log('responseresponseresponseresponse',response.location_data);
        // alert('ppppppppppppppppppp');
        // Populate the popup with the fetched data
        $('#edit-location').val(response.location_data.location); // Set location value
        $('#edit-role').val(response.location_data.role); // Set role value
        $('#edit-location-id').val(response.location_data.id); // Set role value
        
        // Show the popup
        $('#editPopup').show();

// Add the CSS property for flex display
document.getElementById('editPopup').style.display = "flex";
      },
      error: function() {
        alert('Failed to load location data.');
      }
    });
  });
  // Close the popup if clicked outside (optional)
  // $(document).on('click', function(event) {
  //   if (!$(event.target).closest('#editPopup, .edit-btn').length) {
  //     $('#editPopup').hide();
  //   }
  // });
});
</script>  -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if(session('alert_status') && session('alert_msg'))
            Swal.fire({
                title: "{{ session('alert_status') == 'success' ? 'Success' : 'Error' }}",
                text: "{{ session('alert_msg') }}",
                icon: "{{ session('alert_status') }}",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            });
        @endif
    });
</script>

<!-- <script>
    document.getElementById("closePopup").addEventListener("click", function(event) {
        event.preventDefault(); // Prevent default link behavior
        document.getElementById("addPopup").style.display = "none"; // Hide the popup
    });
</script> -->



<!-- <script>
  
  if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone) {
    console.log("ℹ️ PWA is already installed, skipping prompt.");
} else {
    window.addEventListener('beforeinstallprompt', (event) => {
        event.preventDefault();
        deferredPrompt = event;

        setTimeout(() => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('✅ User installed the PWA');
                    } else {
                        console.log('❌ User dismissed the install prompt');
                    }
                    deferredPrompt = null;
                });
            }
        }, 3000);
    });
}

  </script> -->

<script type="text/javascript">
         
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('sw.js')
                .then(reg => console.log("Service Worker Registered!", reg))
                .catch(err => console.log("Service Worker Registration Failed!", err));
        });
    }


</script>

</div>
</body>
</html>