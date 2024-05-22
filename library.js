
const productsPerPage = 12;
    let currentPage = 1;
async function items(){
try{
    const resp = await fetch('library.json');
    const data = await resp.json();
    const mainDiv = document.querySelector(".product-section");
    for(const item of data){
        const newItem = document.createElement("div");
        newItem.classList.add("product-card");
      console.log(data.calories);
        newItem.innerHTML = `<img src="images/${item.meal_photo}.jpeg" alt="">
        <h2 class="h22">${item.meal_name}</h2>
        <ul style="margin: 0; padding: 0;" class="ull">
          <li>calories:${item.calories}<br></li> <li>protein: ${item.protein}<br></li>
          <li>carbs:  ${item.carbs}<br></li>
          <li>fat: ${item.fat}  </li><br>
        </ul>`;
//----------------------------- hover
const newItem2 = document.createElement("div");
  const newItem3 = document.createElement("div");
  newItem3.classList.add("item");
  newItem2.classList.add("item");
  newItem2.innerHTML = newItem.innerHTML;
  var regex = /img(\d+)/;
  var matches = item.meal_photo.match(regex);
  if (matches) {
    var imgName = matches[0]; // Entire matched part including "img" and number
    console.log("Image name:", imgName); // Output: Image name: img12
} else {
    console.log("No match found");
}
  


  newItem3.innerHTML=newItem.innerHTML;
        const ingredientsPromise = getIngredients('allingrediants.json', imgName);
          var ingrediant;
          ingredientsPromise.then(ingredients => {
          if (ingredients) {
          // Here, "ingredients" will be the actual string data you want
          ingrediant=JSON.stringify(ingredients);
          newItem3.innerHTML=newItem.innerHTML;
              newItem.innerHTML = `<img src="images/${item.meal_photo}.jpeg" alt="">
              <h2 class="h22">${item.meal_name}</h2>
              <p class="disc2">${ingrediant}</p>
              `;
           console.log("Ingredients for", 'img3', ":", ingredients); // Optional: Convert to JSON string for complex data
          newItem2.innerHTML=newItem.innerHTML;
          } else {
          console.log("No ingredients found for", 'img3');
          }
          }).catch(error => {
           console.error("Error retrieving ingredients:", error);
          });
        newItem.addEventListener("mouseover", function() {
          //ingrediants 
          newItem.innerHTML = newItem3.innerHTML;

          
  
  
     
      });
      newItem.addEventListener("mouseout", function() {
        newItem.innerHTML = newItem2.innerHTML;

      });















//--------------------------------
        mainDiv.appendChild(newItem);

    }
    displayProducts(currentPage);    generatePaginationButtons();
    
}
catch(error){
    console.error('Error fetching data:', error);

}
}


function displayProducts(page) {
    const products = document.querySelectorAll('.product-card');
    const startIndex = (page - 1) * productsPerPage;
    const endIndex = startIndex + productsPerPage;
    
    // Hide all products
    products.forEach(product => {
        product.style.display = 'none';
    });
    for (let i = startIndex; i < endIndex && i < products.length; i++) {
        products[i].style.display = 'block';
}}

function generatePaginationButtons() {
    const totalPages = Math.ceil(document.querySelectorAll('.product-card').length / productsPerPage);
    const paginationDiv = document.querySelector('.pagination');
    paginationDiv.innerHTML = '';
    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.innerText = i;
        button.addEventListener('click', () => {
            currentPage = i;
            displayProducts(currentPage);
        });
        paginationDiv.appendChild(button);
    }
}



async function getIngredients(fileName, mealPhotoName) {
  try {
    // 1. Fetch the JSON data asynchronously
    const response = await fetch(fileName);

    // 2. Check for successful response
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    // 3. Parse the JSON data
    const jsonData = await response.json();

    // 4. Find the meal object matching the mealPhotoName
    const meal = jsonData.find(meal => meal.meal_photo === mealPhotoName);

    // 5. Check if meal is found
    if (!meal) {
      throw new Error(`Meal with photo name "${mealPhotoName}" not found in JSON data.`);
    }

    // 6. Extract and return ingredients (assuming "ingredients" is the property name)
    return meal.ingredients;
  } catch (error) {
    console.error("Error retrieving ingredients:", error);
    // Handle errors gracefully, e.g., display an error message to the user
    return null; // Or return an empty array or default value
  }
}

window.onload = items();