$('.carousel').carousel({
  interval:2000
})

$(function(){
  var rand = Math.floor(Math.random() * 10);
  var fact = ["Pizza is a $30 billion industry in the United States." ,"Pizzerias represent 17 percent of all U.S. restaurants." ,"Ninety-three percent of Americans eat pizza at least once a month." ,"Women are twice as likely as men to order vegetarian toppings on their pizza." ,"The first known pizzeria, Antica Pizzeria, opened in Naples, Italy, in 1738." ,"The world’s largest round pizza is 40 meters in diameter" ,"Mozzarella has the best baking properties." ,"114 200 951 kgs of pepperoni are consumed every year." ,"In 2014, a pizza delivery guy received a $1,268 tip." ,"In 2011, Pizza was considered a “vegetable” by the government"];
  document.getElementById('moto').innerHTML = fact[rand];
})

$(function(){
  close = document.getElementById("closebtn");
  close.onclick = function(){
    var div = this.parentElement;
    div.style.opacity="0";
    setTimeout(function(){ div.style.display = "none" }, 600);
    setTimeout(function(){ $("#alert-spacer").css("display", "none"); }, 600);
  }
})

$(function(){
  close = document.getElementById("closebtn");
  close.onclick = function(){
    var div = this.parentElement;
    div.style.opacity="0";
    setTimeout(function(){ div.style.display = "none" }, 600);
    setTimeout(function(){ $("#alert-spacer-s").css("display", "none"); }, 600);
  }
})


$(document).ready(function() {
  setTimeout(function() {
      $("#success-reg,#error-reg").css("opacity", "0");
  }, 4400);

  setTimeout(function() {
      $("#success-reg,#error-reg").css("display", "none");
      $("#alert-spacer").css("display", "none");
  }, 5000);
})

$(document).ready(function() {
  setTimeout(function() {
      $("#success-reg-s").css("opacity", "0");
  }, 9400);

  setTimeout(function() {
      $("#success-reg-s").css("display", "none");
      $("#alert-spacer").css("display", "none");
  }, 10000);
})

$(document).ready(function() {
  var clicked=0;
  $(".store-item-description").hide();
  $(".store-item-spacer-d").hide();
  $(".show").show();
  $(".less").hide();
  $(".1").click(function() {
      if(clicked==1){
        $(this).find(".store-item-description").slideToggle("slow");
        $(this).find(".store-item-spacer-d").slideToggle("slow");
        $(this).find(".less").hide();
        $(this).find(".show").show();
        clicked=0;
      }
      else{
        $(this).find(".store-item-description").slideToggle("slow");
        $(this).find(".store-item-spacer-d").slideToggle("slow");
        $(this).find(".less").show();
        $(this).find(".show").hide();
        clicked=1;
      }
  })
})
