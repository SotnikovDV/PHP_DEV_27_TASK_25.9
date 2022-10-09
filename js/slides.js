var slideIndex = 1;
var logged = 0;

// Следущее/Предыдущее
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Показать выбранное фото
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showComments(n) {
  //document.getElementById("photoID").innerHTML = n;

  var comments = document.querySelectorAll(".card-comment");
  comments.forEach(function (elem) {
    elem.parentNode.removeChild(elem);
  });

  if (!n) {
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        var elem = document.getElementById("comments");
        elem.innerHTML = elem.innerHTML + this.responseText;
      }
    }
    xmlhttp.open("GET", "comment?act=get&pid=" + n + '&login=' + logged, true);
    xmlhttp.send();
  }
}

// Выбор фото
function showSlides(n) {
  var i;
  // видимость фотографии
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("demo");
  var captionText = document.getElementById("caption");
  if (n > slides.length) {
    slideIndex = 1
  }
  if (n < 1) {
    slideIndex = slides.length
  }
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].className += " active";
  captionText.innerHTML = dots[slideIndex - 1].alt;

  // видимость коментариев
  var comments = document.getElementsByClassName("card-comment");
  var id = slides[slideIndex - 1].id;
/*  
  for (i = 0; i < comments.length; i++) {
    comments[i].style.display = "none";
  }
  comments = document.getElementsByClassName("photo" + id);
  for (i = 0; i < comments.length; i++) {
    comments[i].style.display = "block";
  }
*/
showComments(id);

}


// Удалить фото
function deleteSlide() {
  var slides = document.getElementsByClassName("mySlides");

  var id = slides[slideIndex - 1].id;

  window.location.href = '/delete?id=' + id;

}

// Добавить/Изменить/Удалить коментарий
function doComment(action, id = null) {
  var slides = document.getElementsByClassName("mySlides");

  var pid = slides[slideIndex - 1].id;
  var url = '/comment?pid=' + pid + '&action=' + action;
  if (action !== 'add') {
    url = url + '&id=' + id;
  }

  window.location.href = url;
}