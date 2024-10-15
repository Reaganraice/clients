document.getElementById('clientForm').onsubmit = function(e) {
    e.preventDefault(); // Prevent default form submission

    // Get form data
    const name = document.querySelector('input[name="name"]').value;
    const description = document.querySelector('textarea[name="description"]').value;

    // Validate inputs
    let errors = [];
    if (!name) {
        errors.push("Name is required.");
    } else if (!/^[a-zA-Z\s]+$/.test(name)) {
        errors.push("Name must only contain letters.");
    }

    if (!description) {
        errors.push("Description is required.");
    }

    if (errors.length > 0) {
        alert(errors.join("\n"));
        return false; // Stop form submission
    }

    // Check if client code is unique with AJAX BEFORE submitting
    const clientCode = document.querySelector('input[name="client_code"]').value;

    fetch('check_client_code.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({client_code: clientCode})
    })
    .then(response => response.json())
    .then(data => {
        if (!data.unique) {
            alert("The client code is already taken. Please choose another.");
        } else {
            // If everything is valid, submit the form
            document.getElementById('clientForm').submit();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
};
