



  var animsition_pages;
  function myFunction() {
    animsition_pages = setTimeout(showPage, 00);
  }
  function showPage() {
    $("#loader").css("display", "none");
    $("#loderBG").removeClass("loderBG");
    $("body").css("overflow", "auto");
    $(".animsition").addClass("active");
  }

 /******Navigation Line********* */
(function($) {
    $('#hoverline ul').hoverSlider();

    $('#hoverline1 ul').hoverSlider();
}(jQuery));

 /******Navigation Line********* */
$(document).ready(function () {

  $(".arrow-div a").click(function(){
    $(".auction_toggle").toggleClass('show_auction');
    });

 new WOW().init();

  //for tab
  $(".lgnBtn").click(function(){
    $('.nav.nav-tabs a.nav-link[href="#login"]').tab('show')
  });
  $(".sgnBtn").click(function(){
    $('.nav.nav-tabs a.nav-link[href="#signup"]').tab('show')
  });
//page scroll
//page scroll
$("a.scrollLink").click(function(event){
  event.preventDefault();
  $("html, body").animate({
    scrollTop:$($(this).attr("href")).offset().top-4},800)
  ;})
   //stiky Header
   $(window).scroll(function(){
    if($(document).scrollTop()>50){
      $('.headerSec').addClass("stickyHeader");
    }
    if($(document).scrollTop()<50){
      $('.headerSec').removeClass("stickyHeader");
    }
  });
  //top button
  $('.tbr_btn>a').click(function(){
    $("html, body").animate({ scrollTop: 0 }, 800);
    return false;
});
});

// for timer
var countDownDate = new Date("Sep 5, 2018 15:37:25").getTime();

/*var x = setInterval(function() {

  var now = new Date().getTime();

  var distance = countDownDate - now;

  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
  var dd=  days + "<strong>" + "DAY"+"</strong>";
  var hh=  hours + "<strong>" + "HRS"+"</strong>";
  var mm=  minutes + "<strong>" + "MIN"+"</strong>";
  var ss=  seconds + "<strong>" + "SEC"+"</strong>";

  document.getElementById("demo").innerHTML ="<span>"+  dd + "</span>" + "<span>"+hh +"</span>"+
  "<span>"+ mm +"</span>"+"<span>" +ss+"</span>";

  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "EXPIRED";
  }
}, 1000);*/
 
$(document).ready(function() {
    $('#myCarousel').carousel({
    interval: 10000
  })
  
  $('#myCarousel').on('slid.bs.carousel', function() {
    //alert("slid");
  });

    $('.multiple-items').slick({
      infinite: false,
      dots: true,
      slidesToShow: 3,
      slidesToScroll: 3
  });

    // slider
    $("#clientSlider").slick({
        dots: false,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: false,
        arrows: true,
        centerMode: true,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 520,
                settings: {
                    slidesToShow: 1,
                    autoplay: true,
                    arrows: false,
                }
            }

        ]
    });
});



 






