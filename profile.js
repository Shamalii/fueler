document.addEventListener("DOMContentLoaded", () => {
    const uname = document.getElementById("uname");
    const email = document.getElementById("email");
    const age = document.getElementById("age");
    const height = document.getElementById("height");
    const weight = document.getElementById("weight");
    const maleRadio = document.getElementById("dot-1");
    const femaleRadio = document.getElementById("dot-2");
    const checkboxInputs = document.querySelectorAll('input[type="checkbox"]');
    

    async function getJsonData() {
        try {
            const response = await fetch("allinfo.json"); // Replace with your JSON file path
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            return data;
        } catch (error) {
            console.error("Error fetching data:", error);
            return null; // Or handle the error differently
        }
    }

    async function updateUserData() {

        const userData = await getJsonData();
        if (userData) {
            uname.value = userData.username;
            email.value = userData.email;
            age.value = userData.age;
            height.value = userData.height;
            weight.value = userData.weight;
            if (userData.gender === "male") {
                maleRadio.checked = true;           
                maleRadio.disabled = true; femaleRadio.disabled = true;
            } else if (userData.gender === "female") {
                femaleRadio.checked = true;         
                maleRadio.disabled = true;femaleRadio.disabled = true;
            }
        } else {
            console.error("Error retrieving user data");
        }
    }

    async function checkCheckboxes() {
        try {
            const response = await fetch('allergies.json');
            const jsonData = await response.json();
            
            // Get all checkboxes
            const checkboxInputs = document.querySelectorAll('input[type="checkbox"]');
            
            // Loop through checkboxes and set checked based on JSON data
            checkboxInputs.forEach(checkbox => {
                const allergy = checkbox.id; // Get allergy name from checkbox ID
                checkbox.checked = jsonData[allergy] === '1'; // Check if value is "1"
                checkbox.disabled = true;
            });

        } catch (error) {
            console.error('Error fetching or parsing JSON data:', error);
            // Handle errors gracefully, e.g., display an error message to the user
        }
        const radioInputs = document.querySelectorAll('input[type="radio"]');
                
        // Loop through radio buttons and set checked based on JSON data
        radioInputs.forEach(radio => {
            const allergy = radio.id; // Get allergy name from radio button ID
            radio.disabled = true; // Disable the radio button
        });
    }

    function fetchData() {
        fetch('active.json')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Display data in the <p> tags
                document.getElementById('activity_level').textContent =  `${data.activity_level}`;
                document.getElementById('goal').textContent =  `${data.goal}`;
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    fetchData();
    updateUserData();
    checkCheckboxes();
});