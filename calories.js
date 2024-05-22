//bar
let number = document.getElementById("number");
let number2 =  document.getElementById("myElement");
let counter = 0;
var takenCal = 0;
var calories;
/*setInterval(() => {
if(counter==100){
  clearInterval();
}
else{
counter+=1;
number2.innerHTML=counter + "%";
}
},22);*/
// Function to retrieve and display calories data
window.onload = function () {
    fetch('macros.json')
    .then(response => response.json())
    .then(data => {
      let counter = 0;
      calories = Math.floor(data.calories / 10) * 10;
      let progress = (takenCal / calories) * 100;  // Calculate the percentage based on fetched calories
      let scaledValue = 560 - (progress * 5.6);  // Map progress to stroke-dashoffset range (560)
      document.body.style.setProperty("--progress-num", scaledValue);
        //const valueToDisplay = data.calories; // Assuming "calories" is the key
        //document.getElementById("myElement").innerHTML = progress;
        //calory=parseFloat(data.calories);
        //left_calories=calory;
        //left_calories=3000;
        //bar interval
        intervalId = setInterval(() => {
          if(counter == takenCal){
            clearInterval(intervalId); 
            document.getElementById("myElement").innerHTML = "Your Taken Calories Is " + takenCal + " And only "+ (calories - takenCal)+" Left to reach your goal";
          }
          else{
          counter+=10;
          document.getElementById("myElement").innerHTML = counter;
          }
          },22);
    })
    .catch(error => {
      console.error("Error fetching or parsing JSON:", error);
    });
  }  


  function changeCal(takenCa){
    takenCal = takenCa;
    restart();
  }
  
  /*
function getCalories() {
    fetch('macros.json')
      .then(response => response.json())
      .then(data => {
        const calories = data.calories;
        console.log("Calories:", calories);
        // You can also display the data in the HTML using DOM manipulation
        document.getElementById("calories").textContent = calories;
      })
      .catch(error => {
        console.error("Error fetching or parsing JSON:", error);
      });
  }*/
