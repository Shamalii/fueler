function getVal(){
const height = parseFloat(document.getElementById("height_1").value);
const weight = parseFloat(document.getElementById("weight_1").value);
const age = parseFloat(document.getElementById("age_1").value);
//gender
const gender = document.getElementById("gender_1").options[document.getElementById("gender_1").selectedIndex].text;


if((document.getElementById("gender_1").selectedIndex)==0)
return;
}
function getGoal(){
   var g = document.getElementById("uname");
   var goal = g.selectedIndex;
   if(goal==0){
    alert("enter value please");
    return;
}
   goal = g.options[goal].text;
}
function activeLVL(){
    var a = document.getElementById("uname");
    var active = a.selectedIndex;
    if(active==0){
        alert("enter value please");
        return;
    }
    active = a.options[active].text;
}
let y= ['hello world', 'he', 'hi ' ,'he'];
console.log(y.unshift('hi'));
console.log(y.push('heee'));
console.log(y);

//********** button */
function bu(){
    //document.documentElement.style.setProperty("--progress-num", "0");
    var a = document.getElementById("number");
    a.innerHTML = "20%";
    let counter = 0;
    let x = 560-560*0.2;
    document.documentElement.style.setProperty("--progress-num", x);
    var myElement = document.getElementById("cir"); 
    myElement.style.animation = "anime 0.7s linear forwards";// adjust the 0.7s to be good with the progress

    setInterval(() => {
    if(counter==20){
      clearInterval();
    }
    else{
    counter+=1;
    a.innerHTML=counter + "%";
}
},22);
}
