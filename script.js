// waring message on Delete cabin
function confirmDelete(id) {
    console.log("cabin id = ", id);
    var a = confirm('are you sure you want to delete this record?')
    console.log("result of the confirm dialog box = ", a);

    if(a) {
        window.location.href = "deletecabin.php?id=" + id;
    }
}

// Generic failure message for any action
function showFailPopup(action) {
   
    alert("An error occurred while trying to " + action + ". Please try again.");
}

// Generic success message for any action
function showSuccessPopup(action) {

    alert("Successfully " + action + "!");
}

// File choose validation Javascript
function validateFileType() {
    const fileInput = document.getElementById('Cabin_Image');
    const errorDiv = document.getElementById('file-error');
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

    // Clear previous error message
    errorDiv.textContent = "";

    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const fileType = file.type;

        // Check if the file type is allowed
        if (!allowedTypes.includes(fileType)) {
            errorDiv.textContent = "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
            fileInput.value = ""; // Clear the file input
        }
    }
}

// Price per Night and Price per Week validations

function validatePrices() {
    const pricePerNight = document.getElementById('Price_Per_Night');
    const pricePerWeek = document.getElementById('Price_Per_Week');
    const errorDiv = document.getElementById('price-error');

    let errorMessage = "";

    // Automatically set Price per Night to 0 if it's negative
    if (pricePerNight.value < 0) {
        pricePerNight.value = 0; // Set negative values to 0
    }
    
      // Automatically set Price per Week to 0 if it's negative
    if (pricePerWeek.value < 0) {
        pricePerWeek.value = 0; // Set negative values to 0
    }

    // Validate price per week (not more than 5 times price per night)
    if (pricePerWeek.value > 5 * pricePerNight.value) {
        errorMessage = "Price/Week cannot exceed 5 times the Price/Night.";
    }

    // Check if value contains a decimal point
    if (pricePerNight.value.includes('.')) {
        errorMessage = 'Price/Night must be a whole number.';
        pricePerNight.value = ''; // Clear invalid input
    }
        
    // Display error message
    if (errorMessage !== "") {
        errorDiv.textContent = errorMessage;
    } else {
        errorDiv.textContent = ""; // Clear the error message
    }
    
}

// Final Validation Before Form Submit
function checkPricesBeforeSubmit() {
    const pricePerNight = document.getElementById('Price_Per_Night').value;
    const pricePerWeek = document.getElementById('Price_Per_Week').value;
    const errorDiv = document.getElementById('price-error');

    if (pricePerNight == 0 || pricePerWeek == 0) {
        errorDiv.textContent = "Price per night and price per week cannot be zero.";
        return false; // Prevent form submission
    } 
    if (pricePerWeek > 5 * pricePerNight) {
        errorDiv.textContent  = "Price per week cannot exceed 5 times the price per night.";
        return false; // Prevent form submission
    }

    return true; // Allow form submission
}
