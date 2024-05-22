const scrollWrapper = document.querySelector(".scroll-wrapper");
const nextBtn = document.querySelector(".next-btn");
const prevBtn = document.querySelector(".prev-btn");
let currentScrollPosition = 0;
const itemsPerPage = 11;  // Number of items to show at once
let nutAllergy;
let lactoseAllergy;
let wheatAllergy;
let NoAllergy;
//allergy.json
var allergy_type;




//bar
let number = document.getElementById("number");
let number2 =  document.getElementById("myElement");
let counter = 0;
takenCal=0;
var calories;
let count=0;
let firstLiText;
let img;
const fetchData = async () => {
  try {
      const response = await fetch(dataUrl);
      const jsonData = await response.json();

for (const item of jsonData) {
  // Create a new item element from the template
const secDiv = document.createElement("div");
secDiv.classList.add("inside-div");
var secondContent = document.querySelectorAll(".second-div");
secDiv.innerHTML = `<h4>${item.meal_name}</h4>
<ul>
 <li>${item.calories}</li>
 <li>${item.protein}</li>
 <li>${item.carbs}</li>
 <li>${item.fat}</li> 
</ul>`;
secondContent[0].appendChild(secDiv);

}}catch (error) {
console.error('Error fetching data:', error);
}
} 

async function getAllergyData() {
  try {
    const response = await fetch('allergies.json');
    const jsonData = await response.json();  // Parse JSON response

    // Access allergy data
     nutAllergy = jsonData.nut;
    lactoseAllergy = jsonData.lactose;
    wheatAllergy = jsonData.wheat;
    if(nutAllergy != 1 && lactoseAllergy !=1 && wheatAllergy !=1 )
    NoAllergy = 1;

    // Use the allergy data here (optional)
  } catch (error) {
    console.error("Error fetching allergy data:", error);
  }
}





nextBtn.addEventListener('click', () => {
  currentScrollPosition += scrollWrapper.scrollWidth / itemsPerPage;
  scrollWrapper.scrollTo({ left: currentScrollPosition, behavior: 'smooth' });
  prevBtn.disabled = false;
  if (currentScrollPosition == scrollWrapper.scrollWidth) {
    nextBtn.disabled = true;
  }
  console.log(currentScrollPosition);
});
prevBtn.addEventListener('click', () => {
  currentScrollPosition -= scrollWrapper.scrollWidth / itemsPerPage;
  scrollWrapper.scrollTo({ left: currentScrollPosition, behavior: 'smooth' });
  nextBtn.disabled = false;
  if (currentScrollPosition <= 0) {
    prevBtn.disabled = true;
  }
  console.log(currentScrollPosition);
  
});
//div addings
async function loadItems(dataUrl) {
  try {
    const response = await fetch(dataUrl);
    const jsonData = await response.json();
    const scrollWrapper = document.querySelector(".scroll-wrapper");
    getAllergyData();


    for (const item of jsonData) {
      // Create a new item element from the template
      const newItem = document.createElement("div");
      newItem.classList.add("item");
      // Replace placeholders with actual data from the JSON object
      //console.log(wheatAllergy);
      if(NoAllergy == 1){
        allergy_type="images/";
        newItem.innerHTML = `
        <img src="images/${item.meal_photo}.jpeg" class="img" id="img" data-info="images/${item.meal_photo}.jpeg">
          <h4>${item.meal_name}<br></h4>  <ul style="margin: 0; padding: 0;" class="ull">
            <li>calories: ${item.calories}<br></li> <li>protein: ${item.protein}<br></li>
            <li>carb: ${item.carbs}<br></li>
            <li>fat: ${item.fat} </li><br>
          </ul>
        `;

      }
      else if(nutAllergy == 1){
        allergy_type="nut_allergy_photos/";
      newItem.innerHTML = `
      <img src="nut_allergy_photos/${item.meal_photo}.jpeg" class="img" id="img" data-info="nut_allergy_photos/${item.meal_photo}.jpeg">
        <h4>${item.meal_name}<br></h4>  <ul style="margin: 0; padding: 0;" class="ull">
          <li>calories: ${item.calories}<br></li> <li>protein: ${item.protein}<br></li>
          <li>carb: ${item.carbs}<br></li>
          <li>fat: ${item.fat} </li><br>
        </ul>
      `;
    } 
      else if(lactoseAllergy == 1){
        allergy_type="lactose_allergy/";
        newItem.innerHTML = `
      <img src="lactose_allergy/${item.meal_photo}.jpeg" class="img" id="img" data-info="lactose_allergy/${item.meal_photo}.jpeg">
        <h4>${item.meal_name}<br></h4>  <ul style="margin: 0; padding: 0;" class="ull">
          <li>calories: ${item.calories}<br></li> <li>protein: ${item.protein}<br></li>
          <li>carb: ${item.carbs}<br></li>
          <li>fat: ${item.fat} </li><br>
        </ul>
      `;} 

      else if(wheatAllergy == 1) {
        allergy_type="wheat_allergy_photos/";
        newItem.innerHTML = `
        <img src="wheat_allergy_photos/${item.meal_photo}.jpeg" class="img" id="img" data-info="wheat_allergy_photos/${item.meal_photo}.jpeg">
          <h4>${item.meal_name}<br></h4>  <ul style="margin: 0; padding: 0;" class="ull">
            <li>calories: ${item.calories}<br></li> <li>protein: ${item.protein}<br></li>
            <li>carb: ${item.carbs}<br></li>
            <li>fat: ${item.fat} </li><br>
          </ul>
        `;
      }

      const newItem2 = document.createElement("div");
const newItem3 = document.createElement("div");
newItem3.classList.add("item");
newItem2.classList.add("item");
newItem2.innerHTML = newItem.innerHTML;
      const ingredientsPromise = getIngredients('ingrediants.json', item.meal_photo);
        var ingrediant;
        ingredientsPromise.then(ingredients => {
        if (ingredients) {
        // Here, "ingredients" will be the actual string data you want
        ingrediant=JSON.stringify(ingredients);
        newItem3.innerHTML=newItem.innerHTML;
            newItem.innerHTML = `
       <img src="${allergy_type}${item.meal_photo}.jpeg" class="img" id="img" data-info="wheat_allergy_photos/${item.meal_photo}.jpeg">
         <h4>${item.meal_name}<br></h4> <p class="disc">${ingrediant}</p>
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
      // Append the new item to the scroll wrapper
      scrollWrapper.appendChild(newItem);
    }
  } catch (error) {
    console.error("Error fetching data:", error);
  }
  clickDiv();

  
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

function clickDiv(){
  var clickableDivs = document.querySelectorAll('.item');
  clickableDivs.forEach(function(div) {
      div.addEventListener('click', function() {
        let calorieText = div.querySelector('li').textContent.trim();
        takenCal += parseInt(calorieText.match(/\d+/)[0]); 
        // Remove the clicked div
          if (!isNaN(takenCal)) {
            
            // Remove the clicked div
            var scrollWrapper = document.querySelector('.scroll-wrapper');
            div.parentNode.removeChild(div);
            bar();
            const secDiv = document.createElement("div");
            secDiv.classList.add("inside-div");
            var secondContent = document.querySelectorAll(".second-div");
const h4Element = div.querySelector('h4');
const liElements = div.querySelectorAll('li');
    img = div.querySelector('img'); 
      const srcAttributeValue = img.getAttribute('src');
      secDiv.setAttribute('data-info',srcAttributeValue);
      // Get the value of the src attribute
            secDiv.innerHTML = `<div class="inner-div"><h4>${h4Element.textContent}</h4></div>
<ul>
${Array.from(liElements).map(li => `<li>${li.textContent.trim()}</li>`).join('')}
</ul>`;
const newItem2 = document.createElement("div");
const newItem3 = document.createElement("div");
newItem3.classList.add("item");
newItem2.classList.add("item");
newItem2.innerHTML = secDiv.innerHTML;
      var regex = /img(\d+)/;
      var matches = srcAttributeValue.match(regex);
        var imgName = matches[0]; // Entire matched part including "img" and number}
        const ingredientsPromise = getIngredients('ingrediants.json', imgName);

        var ingrediant;
        ingredientsPromise.then(ingredients => {
        if (ingredients) {
        // Here, "ingredients" will be the actual string data you want
        ingrediant=JSON.stringify(ingredients);
        secDiv.innerHTML =  `

          <h4>${h4Element.textContent}<br></h4> <p class="disc">${ingrediant}</p>
        `;
        newItem3.innerHTML=secDiv.innerHTML;

      // newItem2.innerHTML=secDiv.innerHTML; allergy_type
         console.log("Ingredients for", 'img3', ":", ingredients); // Optional: Convert to JSON string for complex data
        } else {
        console.log(imgName);
        }
        }).catch(error => {
         console.error("Error retrieving ingredients:", error);
        });
        secDiv.addEventListener("mouseover", function() {
        //ingrediants
        secDiv.innerHTML = newItem2.innerHTML;


    });
    secDiv.addEventListener("mouseout", function() {
      secDiv.innerHTML = newItem3.innerHTML;

    });





secondContent[0].appendChild(secDiv);

            //creating second div below
          
    
            
          }
          else {
            console.error("Invalid calorie text:", takenCal);
            // Handle the error (e.g., display an error message to the user)
        }
        
      });
      
  });

}
//Replace "data.json" with the actual URL or path to your JSON file
const dataUrl = "data.json";

getAllergyData();


 //main functions................


 async function executeFunctions() {
  document.addEventListener("DOMContentLoaded", function() {
      const parentDiv = document.querySelector('.second-div');
      const itemDiv = document.querySelector('.item');
      loadItems(dataUrl);
      if (!parentDiv) {
          console.error("No element with class 'first-div' found.");
          return;
      }
      window.addEventListener("load", function() {
      parentDiv.addEventListener("click", function(event) {
          if (event.target.classList.contains("inside-div")) {
              const clickedDiv = event.target;
              img=clickedDiv.getAttribute('data-info');
              firstLiText = clickedDiv.querySelector('li:first-child').innerText;
              const numberMatch = firstLiText.match(/\d+/);
              let numInteger = parseInt(numberMatch[0], 10); 
              takenCal= parseInt(takenCal - numInteger);    
              if(takenCal<0)
              takenCal=0;         
              clickedDiv.remove(); // Remove only the clicked div
              bar2(takenCal);
              const scrollWrapper = document.querySelector('.scroll-wrapper'); // Assuming scroll-wrapper has an ID
  const newItem = document.createElement("div");
  newItem.classList.add("item");          
  const targetContent = event.target.innerHTML; // Get the entire innerHTML of the clicked div 
  const name = clickedDiv.querySelector('h4').innerText;
  const caloriesMatch = targetContent.match(/calories: (\d+)/); // Regex to find calories
  const proteinMatch = targetContent.match(/protein: (\d+)/); // Regex to find protein
  const carbMatch = targetContent.match(/carb: (\d+)/); // Regex to find carbs
  const fatMatch = targetContent.match(/fat: (\d+)/); // Regex to find fat
    // Check if matches were found, otherwise use default values (optional)
  const calories = caloriesMatch ? caloriesMatch[1] : "X"; // Set calories or default to "X"
  const protein = proteinMatch ? proteinMatch[1] : "X"; // Set protein or default to "X"
  const carb = carbMatch ? carbMatch[1] : "X"; // Set carb or default to "X"
  const fat = fatMatch ? fatMatch[1] : "X"; // Set fat or default to "X"

  newItem.innerHTML = `
    <img src="${img}" class="img" id="img">
    <h4> ${name} <br></h4> 
    <ul style="margin: 0; padding: 0;" class="ull">
      <li>calories: ${calories} <br></li>
      <li>protein: ${protein} <br></li>
      <li>carb: ${carb} <br></li>
      <li>fat: ${fat} </li><br>
    </ul>
  `;
  const newItem2 = document.createElement("div");
  const newItem3 = document.createElement("div");
  newItem3.classList.add("item");
  newItem2.classList.add("item");
  newItem2.innerHTML = newItem.innerHTML;
  var regex = /img(\d+)/;
  var matches = img.match(regex);
  if (matches) {
    var imgName = matches[0]; // Entire matched part including "img" and number
    console.log("Image name:", imgName); // Output: Image name: img12
} else {
    console.log("No match found");
}
  
  newItem3.innerHTML=newItem.innerHTML;
        const ingredientsPromise = getIngredients('ingrediants.json', imgName);
          var ingrediant;
          ingredientsPromise.then(ingredients => {
          if (ingredients) {
          // Here, "ingredients" will be the actual string data you want
          ingrediant=JSON.stringify(ingredients);
          newItem3.innerHTML=newItem.innerHTML;
              newItem.innerHTML = `
         <img src="${img}" class="img" id="img" data-info="${img}">
           <h4>${name}<br></h4> <p class="disc">${ingrediant}</p>
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

      });  scrollWrapper.appendChild(newItem);

       clickDiv();//scrollWrapper.innerHTML += newItem.outerHTML;
            }
      });
  });

  console.log("First function is done, now executing the second function.");
})}


//div info
async function retrieveInfo() {
    const response = await fetch('info.json');
  const data = await response.json();
  const calo = document.getElementById("cal");
  const carb = document.getElementById("carb");
  const protein = document.getElementById("protein");
  const fat = document.getElementById("fat");
  //const photo = document.getElementById("img");
  //photo.setAttribute("src", "images/"+ data.meal_photo + ".jpeg");
  calo.innerHTML = Math.floor(data.calories); 
  carb.innerHTML = Math.floor(data.carbs); 
  protein.innerHTML = Math.floor(data.protein); 
  fat.innerHTML = Math.floor(data.fat); 
  
  //removing div's

    // This will log the parsed JSON data
    // You can now access the data using properties like data.calories, data.fat, etc.
}

//div click 
function bar() {
  fetch('info.json')
  .then(response => response.json())
  .then(data => {
    if(takenCal >= calories){
      calories = Math.floor(data.calories / 10) * 10;
    let progress = (calories / calories) * 100;  // Calculate the percentage based on fetched calories
    let scaledValue = 560 - (progress * 5.6);
      document.body.style.setProperty("--progress-num", scaledValue);
      document.getElementById("myElement").innerHTML = "Your Taken Calories Is "+ takenCal + " and You Are Done for Today";
      var items = document.querySelectorAll('.item');     
       items.forEach(function(item) {
        item.classList.add('disabled-div');
    });
      return;

    }
    calories = Math.floor(data.calories / 10) * 10;
    let progress = (takenCal / calories) * 100;  // Calculate the percentage based on fetched calories
    let scaledValue = 560 - (progress * 5.6);  // Map progress to stroke-dashoffset range (560)s
    if(count==0){
      document.body.style.setProperty("--progress-num", scaledValue);
    var myElement = document.getElementById("cir"); 
    myElement.style.animation = "anime 2s linear forwards";
  count+=1;
}
    else{
      document.body.style.setProperty("--progress-num", scaledValue);
      var myElement = document.getElementById("cir"); 
      myElement.style.animation = "anim 3s linear forwards";

    }
      intervalId = setInterval(() => {
        if(counter == takenCal ){
          clearInterval(intervalId); 
         
          document.getElementById("myElement").innerHTML =takenCal;
           // Map progress to stroke-dashoffset range (560)
        }
        else{
          
        counter+=10;
        document.getElementById("myElement").innerHTML = counter;
        
      }
        },22);
      
  })

  //click and remove below div
  .catch(error => {
    console.error("Error fetching or parsing JSON:", error);
  });
  
 
}  


function bar2(takenCal2){
  fetch('info.json')
  .then(response => response.json())
  .then(data => {
  if(takenCal2 >= calories){
    calories = Math.floor(data.calories / 10) * 10;
  let progress = (calories / calories) * 100;  // Calculate the percentage based on fetched calories
  let scaledValue = 560 - (progress * 5.6);
    document.body.style.setProperty("--progress-num", scaledValue);
    document.getElementById("myElement").innerHTML = "Your Taken Calories Is "+ takenCal2 + " and You Are Done for Today";
    var items = document.querySelectorAll('.item');     
     items.forEach(function(item) {
      item.classList.add('disabled-div');
  });
    return;

  }
  calories = Math.floor(data.calories / 10) * 10; 
    let progress = (takenCal2 / calories) * 100;  // Calculate the percentage based on fetched calories
    let scaledValue = 560 - (progress * 5.6);
    counter=0;  // Map progress to stroke-dashoffset range (560)s
    if(count==0){
      document.body.style.setProperty("--progress-num", scaledValue);
    var myElement = document.getElementById("cir"); 
    myElement.style.animation = "anime 2s linear forwards";
  count+=1;
}
else{
  document.body.style.setProperty("--progress-num", scaledValue);
  var myElement = document.getElementById("cir"); 
  myElement.style.animation = "anim 3s linear forwards";
}
  intervalId = setInterval(() => {
    if(counter == takenCal2 ){
      clearInterval(intervalId); 
     
      document.getElementById("myElement").innerHTML = takenCal2 ;
       // Map progress to stroke-dashoffset range (560)
    }
    else{
      
    counter+=10;
    document.getElementById("myElement").innerHTML = counter;
    
  }
    },22); });
}
//2nd 
// function bar2(takenCal) {
//   fetch('info.json')
//   .then(response => response.json())
//   .then(data => {
//     calories = data.calories;
//     takenCal+=takenCal;
//     console.log(parseFloat(takenCal));  
//     let counter = 0;
//     let progress = (takenCal / calories) * 100;  // Calculate the percentage based on fetched calories
//     let scaledValue = 560 - (progress * 5.6);  // Map progress to stroke-dashoffset range (560)
//     document.body.style.setProperty("--progress-num", scaledValue);
//       intervalId = setInterval(() => {
//         if(takenCal>calories){
//           document.getElementById("myElement").innerHTML = "you exceeded the needed calories for to day";

//         }
//         if(counter == takenCal){
//           clearInterval(intervalId); 
//           document.getElementById("myElement").innerHTML = "Your Taken Calories Is " + takenCal + " And only "+ (calories - takenCal)+" Left to reach your goal";
//         }
//         else {
//         counter+=10;
//         document.getElementById("myElement").innerHTML = counter;
//         }
//         },22);
//   })
//   .catch(error => {
//     console.error("Error fetching or parsing JSON:", error);
//   });
// }  
function click2(){
document.addEventListener("DOMContentLoaded", function() {
  const clickableDivs = document.querySelectorAll(".inside-div");

  if (clickableDivs.length === 0) {
    console.error("No elements with class 'second-div' found.");
  } else {
    clickableDivs.forEach(function(div) {
      div.addEventListener("click", function() {
        this.remove(); // Remove only the clicked div
      });
    });
  }
});
}
window.onload = executeFunctions();
window.onload = retrieveInfo();
window.onload = bar();
